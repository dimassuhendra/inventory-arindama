<?php

namespace App\Services;

use App\Models\Suppliers;
use Illuminate\Http\Request;

class SupplierService
{
    public function getSupplierPageData(Request $request): array
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        // Query Utama dengan Count Relasi Produk
        $query = Suppliers::withCount('products');

        // Filter Pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('telp', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $limit = $perPage === 'all' ? 10000 : (int) $perPage;
        $suppliers = $query->orderBy('name', 'asc')->paginate($limit)->appends($request->all());

        // Mini Analytics Data
        $allSuppliers = Suppliers::withCount('products')->get();
        $totalSuppliersCount = $allSuppliers->count();
        $topSupplier = $allSuppliers->sortByDesc('products_count')->first();
        $totalMappedProducts = $allSuppliers->sum('products_count');

        return [
            'suppliers' => $suppliers,
            'total_suppliers_count' => $totalSuppliersCount,
            'top_supplier_name' => $topSupplier ? $topSupplier->name : '-',
            'top_supplier_products' => $topSupplier ? $topSupplier->products_count : 0,
            'total_mapped_products' => $totalMappedProducts,
        ];
    }

    public function createSupplier(array $data): Suppliers
    {
        return Suppliers::create($data);
    }

    public function updateSupplier(int $id, array $data): Suppliers
    {
        $supplier = Suppliers::findOrFail($id);
        $supplier->update($data);
        return $supplier;
    }

    public function deleteSupplier(int $id): bool
    {
        $supplier = Suppliers::withCount(['products', 'stockEntries'])->findOrFail($id);

        // Proteksi jika supplier sudah terikat dengan Produk atau Riwayat Stok Masuk
        if ($supplier->products_count > 0 || ($supplier->stock_entries_count ?? 0) > 0) {
            throw new \Exception("Gagal menghapus! Supplier '{$supplier->name}' masih terhubung dengan {$supplier->products_count} produk / riwayat transaksi.");
        }

        return $supplier->delete();
    }
}
