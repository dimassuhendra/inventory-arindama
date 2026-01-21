<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Products::latest()->paginate(10);
        return view('products', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Products::create([
            'name' => $request->name,
            'quantity' => 0,
        ]);

        return redirect()->back()->with('success', 'Produk baru berhasil didaftarkan dengan stok 0.');
    }

    public function update(Request $request, $id)
    {
        $product = Products::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
        ]);

        $product->update($request->all());
        return redirect()->back()->with('success', 'Produk berhasil diupdate.');
    }

    public function destroy($id)
    {
        $product = Products::findOrFail($id);
        // Pastikan Anda menangani relasi jika produk sudah memiliki riwayat transaksi
        $product->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }
}