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

    public function index()
    {
        $data = $this->categoryService->getCategoryPageData();
        $data['isSuperAdmin'] = CategoryService::isSuperAdmin();

        return view('categories', $data);
    }

    public function store(CategoryRequest $request)
    {
        try {
            $this->categoryService->createCategory($request->validated());
            return redirect()->back()->with('success', 'Kategori berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function update(CategoryRequest $request, $id)
    {
        try {
            $category = Category::findOrFail($id);
            $this->categoryService->updateCategory($category, $request->validated());
            return redirect()->back()->with('success', 'Kategori berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $this->categoryService->deleteCategory($category);
            return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}
