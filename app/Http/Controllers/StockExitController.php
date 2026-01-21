<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\StockExits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockExitController extends Controller
{
    public function index()
    {
        $exits = StockExits::with('product')->latest()->paginate(10);
        $products = Products::where('quantity', '>', 0)->orderBy('name', 'asc')->get();

        return view('stock-out', compact('exits', 'products'));
    }

    public function store(Request $request)
    {
        $product = Products::findOrFail($request->product_id);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1|max:' . $product->quantity,
            'exit_date' => 'required|date',
        ], [
            'quantity.max' => 'Stok tidak mencukupi! Sisa stok saat ini: ' . $product->quantity
        ]);

        try {
            DB::transaction(function () use ($request, $product) {
                // 1. Catat riwayat keluar
                StockExits::create([
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'exit_date' => $request->exit_date,
                    'description' => $request->description,
                ]);

                // 2. Kurangi stok produk
                $product->decrement('quantity', $request->quantity);
            });

            return redirect()->back()->with('success', 'Barang berhasil dikeluarkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $exit = StockExits::findOrFail($id);
        $product = Products::findOrFail($exit->product_id);

        DB::transaction(function () use ($exit, $product) {
            // Kembalikan stok yang tadinya keluar
            $product->increment('quantity', $exit->quantity);
            $exit->delete();
        });

        return redirect()->back()->with('success', 'Data berhasil dihapus, stok dikembalikan.');
    }
}