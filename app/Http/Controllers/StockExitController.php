<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\StockExits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockExitController extends Controller
{
    public function index()
    {
        // Load relasi user untuk menampilkan siapa yang mengeluarkan barang
        $exits = StockExits::with(['product', 'user'])->latest()->paginate(10);
        $products = Products::where('quantity', '>', 0)->orderBy('name', 'asc')->get();

        return view('stock-out', compact('exits', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.01',
            'exit_date' => 'required|date',
        ]);

        $product = Products::findOrFail($request->product_id);

        // Validasi manual stok mencukupi
        if ($product->quantity < $request->quantity) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi! Sisa stok: ' . $product->quantity);
        }

        try {
            DB::transaction(function () use ($request, $product) {
                // 1. Catat riwayat keluar dengan user_id
                StockExits::create([
                    'product_id' => $request->product_id,
                    'user_id' => Auth::id(),
                    'quantity' => $request->quantity,
                    'exit_date' => $request->exit_date,
                    'description' => $request->description,
                ]);

                // 2. Kurangi stok produk
                $product->decrement('quantity', $request->quantity);
            });

            return redirect()->back()->with('success', 'Barang berhasil dikeluarkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $exit = StockExits::findOrFail($id);
                $product = Products::findOrFail($exit->product_id);

                $product->increment('quantity', $exit->quantity);
                $exit->delete();
            });

            return redirect()->back()->with('success', 'Transaksi dibatalkan, stok dikembalikan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membatalkan: ' . $e->getMessage());
        }
    }
}