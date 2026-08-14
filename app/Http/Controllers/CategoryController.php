<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        // Ambil seluruh data halaman termasuk $total_categories, $top_category_name, dll dari Service
        $data = $this->categoryService->getCategoryPageData();
        $data['isSuperAdmin'] = CategoryService::isSuperAdmin();

        return view('categories', $data);
    }

    public function store(CategoryRequest $request)
    {
        try {
            $this->categoryService->createCategory($request->all());

            return redirect()->back()->with('success', 'Kategori berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambah kategori: ' . $e->getMessage());
        }
    }

    public function update(CategoryRequest $request, $id)
    {
        try {
            $category = Category::findOrFail($id);

            if (!CategoryService::canUserManage($category)) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengubah kategori ini.');
            }

            $data = $request->validated();

            if (!CategoryService::isSuperAdmin()) {
                unset($data['allowed_roles']);
            } else if (!isset($data['allowed_roles'])) {
                $data['allowed_roles'] = null;
            }

            $category->update($data);

            return redirect()->back()->with('success', 'Kategori berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui kategori: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);

            if (!CategoryService::canUserManage($category)) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus kategori ini.');
            }

            $category->delete();

            return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus kategori: ' . $e->getMessage());
        }
    }
}
