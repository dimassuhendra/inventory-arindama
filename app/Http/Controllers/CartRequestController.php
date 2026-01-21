<?php

namespace App\Http\Controllers;

use App\Models\{CartRequest, CartRequestItems, Products, StockExits};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Auth};

class CartRequestController extends Controller
{
    public function index()
    {
        $requests = CartRequest::with(['user', 'items.product'])->latest()->paginate(10);
        $products = Products::where('quantity', '>', 0)->get();

        $activeCart = CartRequest::with('items.product')
            ->where('user_id', Auth::id())
            ->where('status', 'draft')
            ->first();

        return view('cart-request', compact('requests', 'products', 'activeCart'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.1'
        ]);

        DB::transaction(function () use ($request) {
            $cart = CartRequest::firstOrCreate(
                ['user_id' => Auth::id(), 'status' => 'draft'],
                ['reference_number' => 'REQ-' . time()]
            );

            CartRequestItems::create([
                'cart_request_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        });

        return redirect()->back()->with('success', 'Barang ditambahkan ke keranjang.');
    }

    public function submitRequest($id)
    {
        $cart = CartRequest::findOrFail($id);
        $cart->update(['status' => 'pending']);
        return redirect()->back()->with('success', 'Permintaan telah dikirim ke Admin.');
    }

    public function approve($id)
    {
        $cart = CartRequest::with('items.product')->findOrFail($id);

        try {
            DB::transaction(function () use ($cart) {
                foreach ($cart->items as $item) {
                    // 1. Kurangi Stok
                    $item->product->decrement('quantity', $item->quantity);

                    // 2. Catat di Stock Out (Otomatis log lewat Observer)
                    StockExits::create([
                        'product_id' => $item->product_id,
                        'user_id' => $cart->user_id,
                        'quantity' => $item->quantity,
                        'exit_date' => now(),
                        'description' => "Approved Request: " . $cart->reference_number
                    ]);
                }
                $cart->update(['status' => 'approved']);
            });
            return redirect()->back()->with('success', 'Permintaan disetujui, stok telah dipotong.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}