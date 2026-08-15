<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryService
{
    public static function isSuperAdmin(): bool
    {
        $user = Auth::user();
        if (!$user) return false;

        $adminRoles = ['Super Admin', 'superadmin', 'Superadmin', 'super_admin', 'Administrator', 'administrator', 'Admin', 'admin'];
        return $user->hasAnyRole($adminRoles);
    }

    public function getCategoryPageData(): array
    {
        $user = Auth::user();
        $userRoleNames = $user ? $user->getRoleNames()->toArray() : [];

        // Query Utama: Hanya mengambil Kategori Utama (parent_id = NULL)
        $query = Category::with(['creator.roles', 'children.creator.roles', 'products'])
            ->withCount('products')
            ->whereNull('parent_id');

        // ISOLASI DATA BERDASARKAN ROLE
        if (!self::isSuperAdmin() && $user) {
            $query->where(function ($q) use ($user, $userRoleNames) {
                $q->where('user_id', $user->id)
                    ->orWhereHas('creator.roles', function ($roleQuery) use ($userRoleNames) {
                        $roleQuery->whereIn('name', $userRoleNames);
                    });
            });
        }

        $categories = $query->orderBy('name', 'asc')->get();

        // Induk Kategori untuk Dropdown Pilihan Sub-Kategori di Modal Form
        $parentCategoryQuery = Category::whereNull('parent_id');
        if (!self::isSuperAdmin() && $user) {
            $parentCategoryQuery->where(function ($q) use ($user, $userRoleNames) {
                $q->where('user_id', $user->id)
                    ->orWhereHas('creator.roles', function ($roleQuery) use ($userRoleNames) {
                        $roleQuery->whereIn('name', $userRoleNames);
                    });
            });
        }
        $parentCategories = $parentCategoryQuery->orderBy('name', 'asc')->get();

        // Mini Analytics (Scoped)
        $totalCategories = $categories->count();
        $topCategory = $categories->sortByDesc('products_count')->first();
        $totalSubCategories = Category::whereNotNull('parent_id')->whereIn('parent_id', $categories->pluck('id'))->count();

        return [
            'categories'            => $categories,
            'parent_categories'     => $parentCategories,
            'roles'                 => \Spatie\Permission\Models\Role::orderBy('name', 'asc')->get(),
            'total_categories'      => $totalCategories,
            'top_category_name'     => $topCategory ? $topCategory->name : '-',
            'top_category_count'    => $topCategory ? $topCategory->products_count : 0,
            'total_sub_categories'  => $totalSubCategories,
        ];
    }

    public function createCategory(array $data): Category
    {
        $user = Auth::user();
        $data['user_id'] = Auth::id();

        if (!self::isSuperAdmin() && $user) {
            $userRoles = $user->getRoleNames()->toArray();
            $data['allowed_roles'] = !empty($userRoles) ? array_values($userRoles) : null;
        }

        return Category::create([
            'name'          => $data['name'],
            'parent_id'     => $data['parent_id'] ?? null,
            'user_id'       => $data['user_id'],
            'allowed_roles' => $data['allowed_roles'] ?? null,
        ]);
    }

    public function updateCategory(Category $category, array $data): bool
    {
        $this->validateOwnership($category);

        return $category->update([
            'name'          => $data['name'],
            'parent_id'     => $data['parent_id'] ?? null,
            'allowed_roles' => $data['allowed_roles'] ?? $category->allowed_roles,
        ]);
    }

    public function deleteCategory(Category $category): bool
    {
        $this->validateOwnership($category);

        if ($category->products()->count() > 0) {
            throw new \Exception('Kategori tidak dapat dihapus karena masih terhubung dengan produk.');
        }

        if ($category->children()->count() > 0) {
            throw new \Exception('Kategori induk tidak dapat dihapus karena memiliki sub kategori.');
        }

        return $category->delete();
    }

    public static function canUserManage(Category $category): bool
    {
        $user = Auth::user();
        if (!$user) return false;
        if (self::isSuperAdmin()) return true;

        $supplierRoleNames = $category->creator ? $category->creator->getRoleNames()->toArray() : [];
        $userRoleNames = $user->getRoleNames()->toArray();

        $isOwner = $category->user_id === $user->id;
        $isSameRole = (bool) array_intersect($userRoleNames, $supplierRoleNames);

        return $isOwner || $isSameRole;
    }

    private function validateOwnership(Category $category): void
    {
        if (!self::canUserManage($category)) {
            throw new \Exception('Anda tidak memiliki hak akses untuk memanipulasi kategori ini.');
        }
    }
}
