<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Orders::with(['product', 'user'])->latest()->paginate(10);
        $products = Products::orderBy('name', 'asc')->get();
        return view('order', compact('orders', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'customer_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.1',
        ]);

        $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(2)));

        Orders::create([
            'order_number' => $orderNumber,
            'product_id' => $request->product_id,
            'user_id' => Auth::id(),
            'customer_name' => $request->customer_name,
            'quantity' => $request->quantity,
            'status' => 'pending',
            'note' => $request->note
        ]);

        return redirect()->back()->with('success', 'Pesanan berhasil dibuat dengan status Pending.');
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Orders::findOrFail($id);
        $product = Products::findOrFail($order->product_id);

        if ($request->status == 'completed' && $order->status != 'completed') {
            if ($product->quantity < $order->quantity) {
                return redirect()->back()->with('error', 'Stok tidak cukup untuk menyelesaikan pesanan ini!');
            }

            DB::transaction(function () use ($order, $product) {
                $product->decrement('quantity', $order->quantity);
                $order->update(['status' => 'completed']);
            });
        } elseif ($request->status == 'cancelled') {
            $order->update(['status' => 'cancelled']);
        }

        return redirect()->back()->with('success', 'Status pesanan diperbarui.');
    }
}