<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Category;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use App\Exports\ProductTemplateExport;
use App\Exports\ProductsBulkEditExport;
use App\Imports\ProductsBulkEditImport;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil parameter dari URL (dengan nilai default)
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        // 2. Mulai Query
        $query = Products::with(['category', 'supplier']);

        // 3. Logika Pencarian (Search)
        if ($search) {
            $query
                ->where('name', 'like', "%{$search}%")
                ->orWhere('slug', 'like', "%{$search}%")
                ->orWhereHas('category', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('supplier', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
        }

        // 4. Logika Pengurutan (Sort)
        // Jika sort berdasarkan kategori/supplier (relasi), butuh join khusus.
        // Untuk amannya kita urutkan yang ada di tabel products saja.
        $allowedSorts = ['name', 'quantity', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest(); // Default
        }

        // 5. Eksekusi Paginasi
        // Jika user pilih 'all', kita beri angka yang sangat besar
        $limit = $perPage === 'all' ? 10000 : (int) $perPage;

        // append() agar parameter search/sort tidak hilang saat pindah halaman
        $products = $query->paginate($limit)->appends($request->all());

        $categories = Category::orderBy('name', 'asc')->get();
        $suppliers = Suppliers::orderBy('name', 'asc')->get();

        return view('products', compact('products', 'categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'description' => 'required|string',
            'unit' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $data = $request->all();
            $data['slug'] = Str::slug($request->name) . '-' . Str::random(5);
            $data['quantity'] = $request->filled('quantity') ? $request->quantity : 0;

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            Products::create($data);
            return redirect()->back()->with('success', 'Produk berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $product = Products::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'unit' => 'required',
            'quantity' => 'required|numeric|min:0',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        return redirect()->back()->with('success', 'Data produk diperbarui.');
    }

    public function destroy($id)
    {
        $product = Products::findOrFail($id);
        $product->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }

    // Tambahkan method ini di dalam class
    public function export()
    {
        return Excel::download(new ProductsExport(), 'Laporan-Stok-Arindama-' . date('d-M-Y') . '.xlsx');
    }

    // Method untuk mengunduh template Excel
    public function template()
    {
        return Excel::download(new ProductTemplateExport(), 'Template-Import-Produk.xlsx');
    }

    // Method untuk memproses upload Excel
    public function import(Request $request)
    {
        $request->validate(
            [
                'file_excel' => 'required|mimes:xlsx,xls,csv|max:2048',
            ],
            [
                'file_excel.required' => 'File Excel tidak boleh kosong!',
                'file_excel.mimes' => 'Format file harus .xlsx, .xls, atau .csv',
                'file_excel.max' => 'Ukuran file maksimal 2MB!',
            ],
        );

        try {
            Excel::import(new ProductsImport(), $request->file('file_excel'));
            return redirect()->back()->with('success', 'Data produk dari Excel berhasil diimpor!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor file: Pastikan ID Kategori valid dan format Excel sesuai template.');
        }
    }

    // Unduh file untuk diedit
    public function exportForEdit()
    {
        return Excel::download(new ProductsBulkEditExport(), 'Sinkronisasi-Data-Produk.xlsx');
    }

    // Proses unggah kembali file yang sudah diedit
    public function importEdit(Request $request)
    {
        $request->validate([
            'file_excel_edit' => 'required|mimes:xlsx,xls,csv|max:2048',
        ], [
            'file_excel_edit.required' => 'File Excel tidak boleh kosong!',
        ]);

        try {
            Excel::import(new ProductsBulkEditImport(), $request->file('file_excel_edit'));
            return redirect()->back()->with('success', 'Pembaruan massal produk berhasil diproses!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data: Pastikan kolom ID Produk tidak dihapus atau diubah formatnya.');
        }
    }

    public function publicShow($slug)
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

        return view('products_public', compact('product', 'maskedSupplier'));
    }
}
