<?php

namespace App\Http\Controllers;

use App\Models\Suppliers; // Pastikan Model Supplier sudah ada
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Suppliers::latest()->paginate(10);
        return view('suppliers', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'telp' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        Suppliers::create($request->all());
        return redirect()->back()->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $supplier = Suppliers::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'telp' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $supplier->update($request->all());
        return redirect()->back()->with('success', 'Data supplier berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $supplier = Suppliers::findOrFail($id);
        // Proteksi jika supplier sudah terikat dengan transaksi barang masuk
        $supplier->delete();
        return redirect()->back()->with('success', 'Supplier berhasil dihapus.');
    }
}