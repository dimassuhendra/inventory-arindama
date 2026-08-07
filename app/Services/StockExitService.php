<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Products;
use App\Models\StockExits;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StockExitService
{
    public function getStockExitPageData(Request $request): array
    {
        $search = $request->input('search');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $categoryId = $request->input('category_id');
        $perPage = $request->input('per_page', 10);

        // 1. Query Utama dengan Eager Loading
        $query = StockExits::with(['product.category', 'user']);

        // 2. Filter Search (Nama Produk, Deskripsi/Tujuan, atau Petugas)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('product', function ($pQ) use ($search) {
                    $pQ->where('name', 'like', "%{$search}%");
                })
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uQ) use ($search) {
                        $uQ->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // 3. Filter Rentang Tanggal
        if ($fromDate) {
            $query->whereDate('exit_date', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('exit_date', '<=', $toDate);
        }

        // 4. Filter Kategori Produk
        if ($categoryId) {
            $query->whereHas('product', function ($pQ) use ($categoryId) {
                $pQ->where('category_id', $categoryId);
            });
        }

        $limit = $perPage === 'all' ? 10000 : (int) $perPage;
        $exits = $query->latest('exit_date')->latest('id')->paginate($limit)->appends($request->all());

        // 5. Produk Tersedia (Hanya stok > 0 dan diizinkan RBAC)
        $allProducts = Products::with('category')->where('quantity', '>', 0)->orderBy('name', 'asc')->get();
        $allowedProducts = $allProducts->filter(function ($product) {
            return CategoryService::canUserManage($product->category);
        });

        // 6. Mini Analytics Data (Bulan Ini)
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $monthlyExits = StockExits::whereBetween('exit_date', [$startOfMonth, $endOfMonth])->get();

        $totalMonthlyQty = $monthlyExits->sum('quantity');
        $totalMonthlyTx = $monthlyExits->count();

        // Top Produk Keluar Bulan Ini
        $topProductExit = StockExits::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->whereBetween('exit_date', [$startOfMonth, $endOfMonth])
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product')
            ->first();

        return [
            'exits' => $exits,
            'products' => $allowedProducts,
            'categories' => Category::orderBy('name', 'asc')->get(),
            'total_monthly_qty' => $totalMonthlyQty,
            'total_monthly_tx' => $totalMonthlyTx,
            'top_product_name' => $topProductExit->product->name ?? '-',
        ];
    }

    public function createStockExit(array $data): StockExits
    {
        return DB::transaction(function () use ($data) {
            $product = Products::findOrFail($data['product_id']);

            if (!CategoryService::canUserManage($product->category)) {
                throw new \Exception('Anda tidak memiliki hak akses untuk mengeluarkannya pada kategori ini.');
            }

            if ($product->quantity < $data['quantity']) {
                throw new \Exception("Stok tidak mencukupi! Sisa stok saat ini: " . number_format($product->quantity, 2) . " {$product->unit}");
            }

            $exit = StockExits::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'quantity' => $data['quantity'],
                'exit_date' => $data['exit_date'],
                'description' => $data['description'] ?? null,
            ]);

            $product->decrement('quantity', $data['quantity']);

            return $exit;
        });
    }

    public function updateStockExit(int $id, array $data): StockExits
    {
        return DB::transaction(function () use ($id, $data) {
            $exit = StockExits::findOrFail($id);
            $product = Products::findOrFail($exit->product_id);

            if (!CategoryService::canUserManage($product->category)) {
                throw new \Exception('Anda tidak memiliki hak akses untuk mengedit transaksi kategori ini.');
            }

            $selisih = $data['quantity'] - $exit->quantity;

            // Jika menambah jumlah pengeluaran, pastikan stok gudang mencukupi
            if ($selisih > 0 && $product->quantity < $selisih) {
                throw new \Exception("Stok gudang tidak mencukupi untuk penambahan pengeluaran ini! Sisa stok: " . number_format($product->quantity, 2));
            }

            // Kurangi/Tambah stok produk sesuai selisih
            $product->decrement('quantity', $selisih);

            $exit->update([
                'quantity' => $data['quantity'],
                'exit_date' => $data['exit_date'],
                'description' => $data['description'] ?? null,
            ]);

            return $exit;
        });
    }

    public function deleteStockExit(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $exit = StockExits::findOrFail($id);
            $product = Products::findOrFail($exit->product_id);

            if (!CategoryService::canUserManage($product->category)) {
                throw new \Exception('Anda tidak memiliki hak akses untuk membatalkan pengeluaran kategori ini.');
            }

            // Kembalikan stok ke gudang
            $product->increment('quantity', $exit->quantity);
            return $exit->delete();
        });
    }
}
