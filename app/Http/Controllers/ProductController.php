<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Category;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index()
    {
        $products = Products::with(['category', 'supplier'])->latest()->paginate(10);
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
            $data['quantity'] = 0;

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            Products::create($data);
            return redirect()->back()->with('success', 'Produk berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
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
            if ($product->image)
                Storage::disk('public')->delete($product->image);
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
        return Excel::download(new ProductsExport, 'Laporan-Stok-Arindama-' . date('d-M-Y') . '.xlsx');
    }
}