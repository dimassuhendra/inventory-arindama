<?php

namespace App\Services;

use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierService
{
    public function getSupplierPageData(Request $request): array
    {
        $user = Auth::user();
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        // Query Utama dengan Eager Loading Creator & Roles
        $query = Suppliers::with(['creator.roles'])->withCount('products');

        // ISOLASI DATA BERDASARKAN ROLE
        if (!$user->hasRole('Super Admin')) {
            // Ambil daftar ID user yang memiliki role sejenis dengan user yang sedang login
            $userRoleNames = $user->getRoleNames()->toArray();

            $query->where(function ($q) use ($user, $userRoleNames) {
                // 1. Tampilkan data yang dibuat oleh User itu sendiri
                $q->where('user_id', $user->id)
                    // 2. Atau dibuat oleh user lain yang memiliki Role yang sama (misal sesama HRGA)
                    ->orWhereHas('creator.roles', function ($roleQuery) use ($userRoleNames) {
                        $roleQuery->whereIn('name', $userRoleNames);
                    });
            });
        }

        // Filter Pencarian Keyword
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('telp', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $limit = $perPage === 'all' ? 10000 : (int) $perPage;
        $suppliers = $query->orderBy('name', 'asc')->paginate($limit)->appends($request->all());

        // Mini Analytics Data (Scoped Sesuai Hak Akses)
        $analyticsQuery = Suppliers::withCount('products');
        if (!$user->hasRole('Super Admin')) {
            $userRoleNames = $user->getRoleNames()->toArray();
            $analyticsQuery->where(function ($q) use ($user, $userRoleNames) {
                $q->where('user_id', $user->id)
                    ->orWhereHas('creator.roles', function ($roleQuery) use ($userRoleNames) {
                        $roleQuery->whereIn('name', $userRoleNames);
                    });
            });
        }

        $allSuppliers = $analyticsQuery->get();
        $totalSuppliersCount = $allSuppliers->count();
        $topSupplier = $allSuppliers->sortByDesc('products_count')->first();
        $totalMappedProducts = $allSuppliers->sum('products_count');

        return [
            'suppliers'             => $suppliers,
            'total_suppliers_count' => $totalSuppliersCount,
            'top_supplier_name'     => $topSupplier ? $topSupplier->name : '-',
            'top_supplier_products' => $topSupplier ? $topSupplier->products_count : 0,
            'total_mapped_products' => $totalMappedProducts,
        ];
    }

    public function createSupplier(array $data): Suppliers
    {
        // Otomatis mengaitkan akun pembuat
        $data['user_id'] = Auth::id();
        return Suppliers::create($data);
    }

    public function updateSupplier(int $id, array $data): Suppliers
    {
        $supplier = Suppliers::with('creator.roles')->findOrFail($id);
        $user = Auth::user();

        // Validasi Hak Akses Edit
        if (!$user->hasRole('Super Admin')) {
            $supplierRoleNames = $supplier->creator ? $supplier->creator->getRoleNames()->toArray() : [];
            $userRoleNames = $user->getRoleNames()->toArray();

            $isOwner = $supplier->user_id === $user->id;
            $isSameRole = (bool) array_intersect($userRoleNames, $supplierRoleNames);

            if (!$isOwner && !$isSameRole) {
                throw new \Exception("Anda tidak memiliki akses untuk mengubah supplier milik role lain.");
            }
        }

        $supplier->update($data);
        return $supplier;
    }

    public function deleteSupplier(int $id): bool
    {
        $supplier = Suppliers::with(['creator.roles'])->withCount(['products', 'stockEntries'])->findOrFail($id);
        $user = Auth::user();

        // Validasi Hak Akses Hapus
        if (!$user->hasRole('Super Admin')) {
            $supplierRoleNames = $supplier->creator ? $supplier->creator->getRoleNames()->toArray() : [];
            $userRoleNames = $user->getRoleNames()->toArray();

            $isOwner = $supplier->user_id === $user->id;
            $isSameRole = (bool) array_intersect($userRoleNames, $supplierRoleNames);

            if (!$isOwner && !$isSameRole) {
                throw new \Exception("Anda tidak memiliki akses untuk menghapus supplier milik role lain.");
            }
        }

        if ($supplier->products_count > 0 || ($supplier->stock_entries_count ?? 0) > 0) {
            throw new \Exception("Gagal menghapus! Supplier '{$supplier->name}' masih terhubung dengan {$supplier->products_count} produk / riwayat transaksi.");
        }

        return $supplier->delete();
    }
}
