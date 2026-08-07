<?php

namespace App\Services;

use App\Models\Products;
use App\Models\StockEntries;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StockEntryService
{
    public function getStockEntryPageData(Request $request): array
    {
        $search = $request->input('search');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $supplierId = $request->input('supplier_id');
        $perPage = $request->input('per_page', 10);

        // 1. Query Utama dengan Eager Loading
        $query = StockEntries::with(['product.category', 'supplier', 'user']);

        // 2. Filter Search (Nama Produk, Supplier, atau Petugas)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('product', function ($pQ) use ($search) {
                    $pQ->where('name', 'like', "%{$search}%");
                })
                    ->orWhereHas('supplier', function ($sQ) use ($search) {
                        $sQ->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function ($uQ) use ($search) {
                        $uQ->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // 3. Filter Rentang Tanggal
        if ($fromDate) {
            $query->whereDate('entry_date', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('entry_date', '<=', $toDate);
        }

        // 4. Filter Supplier
        if ($supplierId) {
            $query->where('supplier_id', $supplierId);
        }

        $limit = $perPage === 'all' ? 10000 : (int) $perPage;
        $entries = $query->latest('entry_date')->latest('id')->paginate($limit)->appends($request->all());

        // 5. Query Pilihan Produk (Hanya produk dari kategori yang diizinkan untuk diisi)
        $allProducts = Products::with('category')->orderBy('name', 'asc')->get();
        $allowedProducts = $allProducts->filter(function ($product) {
            return CategoryService::canUserManage($product->category);
        });

        // 6. Mini Analytics Data (Bulan Ini)
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $monthlyEntries = StockEntries::whereBetween('entry_date', [$startOfMonth, $endOfMonth])->get();

        $totalMonthlyQty = $monthlyEntries->sum('quantity');
        $totalMonthlyTx = $monthlyEntries->count();

        // Top Product & Supplier Bulan Ini
        $topProductEntry = StockEntries::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->whereBetween('entry_date', [$startOfMonth, $endOfMonth])
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product')
            ->first();

        $topSupplierEntry = StockEntries::select('supplier_id', DB::raw('COUNT(*) as total_tx'))
            ->whereBetween('entry_date', [$startOfMonth, $endOfMonth])
            ->whereNotNull('supplier_id')
            ->groupBy('supplier_id')
            ->orderByDesc('total_tx')
            ->with('supplier')
            ->first();

        return [
            'entries' => $entries,
            'products' => $allowedProducts,
            'suppliers' => \App\Models\Suppliers::orderBy('name', 'asc')->get(),
            'total_monthly_qty' => $totalMonthlyQty,
            'total_monthly_tx' => $totalMonthlyTx,
            'top_product_name' => $topProductEntry->product->name ?? '-',
            'top_supplier_name' => $topSupplierEntry->supplier->name ?? '-',
        ];
    }

    public function createStockEntry(array $data): StockEntries
    {
        return DB::transaction(function () use ($data) {
            $product = Products::findOrFail($data['product_id']);

            // Cek RBAC Akses Kategori Produk
            if (!CategoryService::canUserManage($product->category)) {
                throw new \Exception('Anda tidak memiliki hak akses untuk menambah stok produk pada kategori ini.');
            }

            $entry = StockEntries::create([
                'product_id' => $product->id,
                'supplier_id' => $product->supplier_id,
                'user_id' => Auth::id(),
                'quantity' => $data['quantity'],
                'entry_date' => $data['entry_date'],
            ]);

            // Update penambahan stok di tabel products
            $product->increment('quantity', $data['quantity']);

            return $entry;
        });
    }

    public function updateStockEntry(int $id, array $data): StockEntries
    {
        return DB::transaction(function () use ($id, $data) {
            $entry = StockEntries::findOrFail($id);
            $product = Products::findOrFail($entry->product_id);

            if (!CategoryService::canUserManage($product->category)) {
                throw new \Exception('Anda tidak memiliki hak akses untuk mengedit riwayat stok kategori ini.');
            }

            $selisih = $data['quantity'] - $entry->quantity;

            // Validasi Pencegahan Stok Minus
            if ($selisih < 0 && ($product->quantity + $selisih) < 0) {
                throw new \Exception("Koreksi ditolak! Stok fisik saat ini (" . number_format($product->quantity, 2) . ") tidak mencukupi untuk pengurangan ini.");
            }

            $product->increment('quantity', $selisih);

            $entry->update([
                'quantity' => $data['quantity'],
                'entry_date' => $data['entry_date'],
            ]);

            return $entry;
        });
    }

    public function deleteStockEntry(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $entry = StockEntries::findOrFail($id);
            $product = Products::findOrFail($entry->product_id);

            if (!CategoryService::canUserManage($product->category)) {
                throw new \Exception('Anda tidak memiliki hak akses untuk menghapus riwayat stok kategori ini.');
            }

            // Validasi Pencegahan Stok Minus saat entri dihapus
            if (($product->quantity - $entry->quantity) < 0) {
                throw new \Exception("Penghapusan ditolak! Stok fisik saat ini (" . number_format($product->quantity, 2) . ") lebih kecil dari jumlah transaksi yang akan dihapus (" . number_format($entry->quantity, 2) . ").");
            }

            $product->decrement('quantity', $entry->quantity);
            return $entry->delete();
        });
    }
}
