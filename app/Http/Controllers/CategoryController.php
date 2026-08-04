<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('products')
            ->orderBy('name', 'asc')
            ->get();

        $roles = Role::orderBy('name', 'asc')->get();
        $isSuperAdmin = CategoryService::isSuperAdmin();

        return view('categories', compact('categories', 'roles', 'isSuperAdmin'));
    }

    public function store(CategoryRequest $request)
    {
        try {
            $data = $request->validated();

            // Hanya Superadmin yang berhak menentukan allowed_roles
            if (!CategoryService::isSuperAdmin()) {
                unset($data['allowed_roles']);
            }

            Category::create($data);

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
                // Jika Superadmin mengosongkan pilihan role, ubah menjadi kategori Public (null)
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
