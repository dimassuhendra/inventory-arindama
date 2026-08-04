<?php

namespace App\Services;

use App\Models\Category;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class CategoryService
{
    public function getCategoryPageData(): array
    {
        $categories = Category::withCount('products')
            ->with('products')
            ->get();

        // 1. Mini Analytics Data
        $totalCategories = $categories->count();
        $topCategory = $categories->sortByDesc('products_count')->first();
        $restrictedCategoriesCount = $categories->filter(function ($cat) {
            return !empty($cat->allowed_roles);
        })->count();

        // 2. Roles Data untuk Form Checkbox Modal (Khusus Superadmin)
        $allRoles = Role::pluck('name')->toArray();

        return [
            'categories' => $categories,
            'total_categories' => $totalCategories,
            'top_category_name' => $topCategory ? $topCategory->name : '-',
            'top_category_count' => $topCategory ? $topCategory->products_count : 0,
            'restricted_count' => $restrictedCategoriesCount,
            'all_roles' => $allRoles,
        ];
    }

    public function createCategory(array $data): Category
    {
        return Category::create([
            'name' => $data['name'],
            'allowed_roles' => $data['allowed_roles'] ?? null,
        ]);
    }

    public function updateCategory(Category $category, array $data): bool
    {
        return $category->update([
            'name' => $data['name'],
            'allowed_roles' => $data['allowed_roles'] ?? null,
        ]);
    }

    public function deleteCategory(Category $category): bool
    {
        if ($category->products()->count() > 0) {
            throw new \Exception('Kategori tidak bisa dihapus karena masih memiliki produk.');
        }

        return $category->delete();
    }

    /**
     * Helper Check: Apakah user saat ini memiliki akses penuh (write/edit) ke kategori tertentu
     */
    public static function canUserManage(Category $category): bool
    {
        $user = Auth::user();

        // Superadmin selalu bisa mengelola
        if ($user->hasRole('Super Admin') || $user->hasRole('superadmin')) {
            return true;
        }

        // Jika allowed_roles kosong/null, dianggap Public (Read-Only untuk non-superadmin)
        if (empty($category->allowed_roles)) {
            return false;
        }

        // Cek apakah user memiliki salah satu dari allowed_roles
        return $user->hasAnyRole($category->allowed_roles);
    }
}
