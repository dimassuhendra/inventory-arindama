<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\StockEntries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockEntryController extends Controller
{
    public function index()
    {
        // Memuat relasi produk dan supplier untuk riwayat
        $entries = StockEntries::with(['product', 'supplier'])->latest()->paginate(10);
        $products = Products::orderBy('name', 'asc')->get();

        return view('stock-in', compact('entries', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.01',
            'entry_date' => 'required|date',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $product = Products::findOrFail($request->product_id);

                StockEntries::create([
                    'product_id' => $request->product_id,
                    'supplier_id' => $product->supplier_id, 
                    'user_id' => Auth::id(),           
                    'quantity' => $request->quantity,
                    'entry_date' => $request->entry_date,
                ]);

                // 2. Update stok di tabel products
                $product->increment('quantity', $request->quantity);
            });

            return redirect()->back()->with('success', 'Stok berhasil masuk dan tercatat!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $entry = StockEntries::findOrFail($id);

        $request->validate([
            'quantity' => 'required|numeric|min:0.01',
            'entry_date' => 'required|date',
        ]);

        try {
            DB::transaction(function () use ($request, $entry) {
                $product = Products::findOrFail($entry->product_id);

                $selisih = $request->quantity - $entry->quantity;
                $product->increment('quantity', $selisih);

                // Update riwayat
                $entry->update([
                    'quantity' => $request->quantity,
                    'entry_date' => $request->entry_date,
                ]);
            });

            return redirect()->back()->with('success', 'Riwayat stok diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Update gagal: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $entry = StockEntries::findOrFail($id);
                $product = Products::findOrFail($entry->product_id);

                $product->decrement('quantity', $entry->quantity);
                $entry->delete();
            });

            return redirect()->back()->with('success', 'Riwayat berhasil dihapus dan stok dikurangi.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Hapus gagal: ' . $e->getMessage());
        }
    }
}