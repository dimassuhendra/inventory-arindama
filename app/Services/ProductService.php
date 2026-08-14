<?php

namespace App\Services;

use App\Services\CategoryService;

use App\Models\Pic;
use App\Models\Products;
use App\Models\Category;
use App\Models\Suppliers;
use App\Models\Department;
use App\Models\SubCategory;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function getProductPageData(Request $request): array
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $companyName = $request->input('company_name');
        $assetStatus = $request->input('asset_status');
        $perPage = $request->input('per_page', 10);
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        // Eager Loading Relasi Lengkap
        $query = Products::with(['category', 'subCategory', 'supplier', 'department', 'pic']);

        // Filter Kategori Berdasarkan Role
        $categories = Category::orderBy('name', 'asc')->get();
        $allowedCategoryIds = $categories->filter(function ($cat) {
            return CategoryService::canUserManage($cat);
        })->pluck('id')->toArray();

        $query->whereIn('category_id', $allowedCategoryIds);

        // Filter Search Multi-Column
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('brand_model', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('po_invoice_number', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($catQ) use ($search) {
                        $catQ->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('pic', function ($picQ) use ($search) {
                        $picQ->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('department', function ($deptQ) use ($search) {
                        $deptQ->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter Kategori, Perusahaan & Status
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        if ($companyName) {
            $query->where('company_name', $companyName);
        }
        if ($assetStatus) {
            $query->where('asset_status', $assetStatus);
        }

        // Sorting
        $allowedSorts = ['name', 'quantity', 'created_at', 'purchase_cost'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        $limit = $perPage === 'all' ? 10000 : (int) $perPage;
        $products = $query->paginate($limit)->appends($request->all());

        // 6. Mini Analytics Data (Kondisional Berdasarkan Role)
        $user = Auth::user();
        $allProducts = Products::whereIn('category_id', $allowedCategoryIds)->get();

        // Variabel Analytics Default
        $analytics = [];

        if ($user && ($user->hasRole('HRGA') || $user->hasRole('Super Admin'))) {
            // METRIK KHUSUS HRGA / SUPER ADMIN (Helicopter View & Finansial)
            $analytics = [
                'total_products_count' => $allProducts->count(),
                'total_asset_value'    => $allProducts->sum('purchase_cost'),
                'total_book_value'     => $allProducts->sum(fn($p) => $p->current_book_value),
                'disposed_count'       => $allProducts->where('asset_status', 'Dihentikan/Afkir')->count(),
            ];
        } else {
            // METRIK KHUSUS GUDANG / STAFF (Operasional & Fisik Stock)
            $analytics = [
                'total_physical_stock' => $allProducts->sum('quantity'),
                'low_stock_count'      => $allProducts->where('quantity', '<=', 5)->count(),
                'stored_in_warehouse'  => $allProducts->where('asset_status', 'Tersimpan Gudang')->count(),
                'under_maintenance'    => $allProducts->where('asset_status', 'Dalam Perawatan')->count(),
            ];
        }

        // Master Data Dropdown
        $allowedCategories = $categories->filter(function ($cat) {
            return CategoryService::canUserManage($cat);
        });

        $subCategories = SubCategory::whereIn('category_id', $allowedCategoryIds)->get();
        $suppliers     = Suppliers::orderBy('name', 'asc')->get();
        $departments   = Department::orderBy('name', 'asc')->get();
        $pics          = Pic::orderBy('name', 'asc')->get();

        return array_merge([
            'products'      => $products,
            'categories'    => $allowedCategories,
            'subCategories' => $subCategories,
            'suppliers'     => $suppliers,
            'departments'   => $departments,
            'pics'          => $pics,
        ], $analytics);
    }

    public function createProduct(array $data, Request $request): Products
    {
        $category = Category::findOrFail($data['category_id']);
        if (!CategoryService::canUserManage($category)) {
            throw new \Exception('Anda tidak memiliki izin untuk menambah aset pada kategori ini.');
        }

        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);
        $data['quantity'] = !empty($data['quantity']) ? $data['quantity'] : 1;

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
            throw new \Exception('Anda tidak memiliki izin untuk mengedit aset pada kategori ini.');
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
            throw new \Exception('Anda tidak memiliki izin untuk menghapus aset pada kategori ini.');
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        return $product->delete();
    }

    public function getPublicProductDetail(string $slug): array
    {
        $product = Products::with(['category', 'subCategory', 'supplier', 'department', 'pic'])
            ->where('slug', $slug)
            ->firstOrFail();

        $supplierName = $product->supplier->name ?? 'Tidak Ada Vendor';
        $maskedSupplier = $supplierName;

        if ($product->supplier && strlen($supplierName) > 6) {
            $firstThree = substr($supplierName, 0, 3);
            $lastThree = substr($supplierName, -3);
            $maskedLen = strlen($supplierName) - 6;
            $maskedSupplier = $firstThree . str_repeat('*', $maskedLen) . $lastThree;
        }

        return compact('product', 'maskedSupplier');
    }
}
