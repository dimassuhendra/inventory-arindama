<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\StockEntries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockEntryController extends Controller
{
    public function index()
    {
        // Mengambil riwayat stok masuk terbaru
        $entries = StockEntries::with('product')->latest()->paginate(10);
        $products = Products::orderBy('name', 'asc')->get();

        return view('stock-in', compact('entries', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1',
            'entry_date' => 'required|date',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Catat ke tabel stock_entries
                StockEntries::create([
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'entry_date' => $request->entry_date,
                    'description' => $request->description ?? 'Barang Masuk Reguler',
                ]);

                // 2. Update jumlah stok di tabel products
                $product = Products::find($request->product_id);
                $product->increment('quantity', $request->quantity);
            });

            return redirect()->back()->with('success', 'Stok berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $entry = StockEntries::findOrFail($id);
        $product = Products::findOrFail($entry->product_id);

        // Rollback stok lama, lalu tambah stok baru
        $product->decrement('quantity', $entry->quantity);
        $product->increment('quantity', $request->quantity);

        $entry->update([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'entry_date' => $request->entry_date,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        $entry = StockEntries::findOrFail($id);
        $product = Products::findOrFail($entry->product_id);

        // Kurangi stok produk sebelum menghapus riwayat
        $product->decrement('quantity', $entry->quantity);
        $entry->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}