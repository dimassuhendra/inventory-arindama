<?php

namespace App\Services;

use App\Models\Products;
use App\Models\Category;
use App\Models\Suppliers;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductService
{
    public function getProductPageData(Request $request): array
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $stockStatus = $request->input('stock_status');
        $perPage = $request->input('per_page', 10);
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        // Query Utama dengan Eager Loading
        $query = Products::with(['category', 'supplier']);

        // Filter hanya produk yang kategorinya diizinkan untuk role user saat ini
        $categories = Category::orderBy('name', 'asc')->get();
        $allowedCategoryIds = $categories->filter(function ($cat) {
            return CategoryService::canUserManage($cat);
        })->pluck('id')->toArray();

        $query->whereIn('category_id', $allowedCategoryIds);

        // 1. Filter Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($catQ) use ($search) {
                        $catQ->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('supplier', function ($supQ) use ($search) {
                        $supQ->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // 2. Filter Kategori
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // 3. Filter Status Stok
        if ($stockStatus === 'low') {
            $query->where('quantity', '<=', 5);
        } elseif ($stockStatus === 'safe') {
            $query->where('quantity', '>', 5);
        } elseif ($stockStatus === 'used') {
            $query->whereNotNull('first_used_at');
        } elseif ($stockStatus === 'stored') {
            $query->whereNull('first_used_at');
        }

        // 4. Sorting
        $allowedSorts = ['name', 'quantity', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        // 5. Paginasi
        $limit = $perPage === 'all' ? 10000 : (int) $perPage;
        $products = $query->paginate($limit)->appends($request->all());

        // 6. Mini Analytics Data
        $allProducts = Products::whereIn('category_id', $allowedCategoryIds)->get();
        $totalProductsCount = $allProducts->count();
        $totalQuantitySum = $allProducts->sum('quantity');
        $lowStockCount = $allProducts->where('quantity', '<=', 5)->count();
        $activeUsedCount = $allProducts->whereNotNull('first_used_at')->count();

        // Filter list kategori pada dropdown agar hanya menampilkan kategori milik role user
        $allowedCategories = $categories->filter(function ($cat) {
            return CategoryService::canUserManage($cat);
        });
        $suppliers = Suppliers::orderBy('name', 'asc')->get();

        return [
            'products' => $products,
            'categories' => $allowedCategories,
            'suppliers' => $suppliers,
            'total_products_count' => $totalProductsCount,
            'total_quantity_sum' => $totalQuantitySum,
            'low_stock_count' => $lowStockCount,
            'active_used_count' => $activeUsedCount,
        ];
    }

    public function createProduct(array $data, Request $request): Products
    {
        $category = Category::findOrFail($data['category_id']);
        if (!CategoryService::canUserManage($category)) {
            throw new \Exception('Anda tidak memiliki izin untuk menambah produk pada kategori ini.');
        }

        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);
        $data['quantity'] = !empty($data['quantity']) ? $data['quantity'] : 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        return Products::create($data);
    }

    public function updateProduct(int $id, array $data, Request $request): Products
    {
        $product = Products::findOrFail($id);

        $category = Category::findOrFail($data['category_id']);
        if (!CategoryService::canUserManage($category)) {
            throw new \Exception('Anda tidak memiliki izin untuk mengedit produk pada kategori ini.');
        }

        $data['slug'] = Str::slug($data['name']);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        return $product;
    }

    public function deleteProduct(int $id): bool
    {
        $product = Products::findOrFail($id);

        if (!CategoryService::canUserManage($product->category)) {
            throw new \Exception('Anda tidak memiliki izin untuk menghapus produk pada kategori ini.');
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        return $product->delete();
    }

    public function getPublicProductDetail(string $slug): array
    {
        $product = Products::with(['category', 'supplier'])->where('slug', $slug)->firstOrFail();

        $supplierName = $product->supplier->name ?? 'Tidak Ada Supplier';
        $maskedSupplier = $supplierName;

        if ($product->supplier && strlen($supplierName) > 6) {
            $firstThree = substr($supplierName, 0, 3);
            $lastThree = substr($supplierName, -3);
            $maskedLen = strlen($supplierName) - 6;
            $maskedSupplier = $firstThree . str_repeat('*', $maskedLen) . $lastThree;
        } elseif ($product->supplier) {
            $maskedSupplier = substr($supplierName, 0, 2) . '***';
        }

        $usageAge = null;
        if ($product->first_used_at) {
            $start = \Carbon\Carbon::parse($product->first_used_at)->startOfDay();
            $now = \Carbon\Carbon::now()->startOfDay();
            $diff = $start->diff($now);

            $years = $diff->y;
            $months = $diff->m;
            $days = $diff->d;

            if ($years > 0) {
                $parts = ["{$years} Tahun"];
                if ($months > 0) $parts[] = "{$months} Bulan";
                if ($days > 0) $parts[] = "{$days} Hari";
                $usageAge = implode(' ', $parts);
            } elseif ($months > 0) {
                $parts = ["{$months} Bulan"];
                if ($days > 0) $parts[] = "{$days} Hari";
                $usageAge = implode(' ', $parts);
            } else {
                $usageAge = $days == 0 ? 'Hari ini' : "{$days} Hari";
            }
        }

        return compact('product', 'maskedSupplier', 'usageAge');
    }
}
