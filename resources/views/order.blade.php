@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Orders Log</h2>
                <p class="text-sm text-slate-500 uppercase tracking-widest mt-1">Riwayat & Status Pesanan</p>
            </div>
            <button onclick="document.getElementById('modalOrder').classList.remove('hidden')"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-2xl shadow-lg flex items-center gap-2 font-bold text-sm">
                <i class="fa-solid fa-cart-plus"></i> Buat Pesanan Baru
            </button>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase">No. Order / Pelanggan</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase">Produk</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase text-center">Qty</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase">Status</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($orders as $order)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-6 py-4">
                                <div class="text-xs font-mono text-indigo-600 font-bold">{{ $order->order_number }}</div>
                                <div class="text-sm font-bold text-slate-800">{{ $order->customer_name }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $order->product->name }}</td>
                            <td class="px-6 py-4 text-center font-bold text-slate-700">{{ $order->quantity }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $styles = [
                                        'pending' => 'bg-amber-100 text-amber-600',
                                        'completed' => 'bg-green-100 text-green-600',
                                        'cancelled' => 'bg-slate-100 text-slate-500'
                                    ];
                                @endphp
                                <span
                                    class="{{ $styles[$order->status] }} px-3 py-1 rounded-full text-[10px] font-bold uppercase">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($order->status == 'pending')
                                    <div class="flex justify-center gap-2">
                                        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit"
                                                class="text-[10px] font-bold bg-green-500 text-white px-3 py-1 rounded-lg hover:bg-green-600">Selesaikan</button>
                                        </form>
                                        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit"
                                                class="text-[10px] font-bold bg-red-100 text-red-500 px-3 py-1 rounded-lg hover:bg-red-200">Batal</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-[10px] text-slate-400 italic">No Action</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="modalOrder"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md overflow-hidden">
            <div class="bg-indigo-600 p-6 text-white flex justify-between items-center">
                <h3 class="font-bold">BUAT PESANAN BARU</h3>
                <button onclick="document.getElementById('modalOrder').classList.add('hidden')"><i
                        class="fa-solid fa-circle-xmark"></i></button>
            </div>
            <form action="{{ route('orders.store') }}" method="POST" class="p-8 space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Nama Pelanggan</label>
                    <input type="text" name="customer_name" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Produk</label>
                    <select name="product_id" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none">
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} (Stok: {{ $product->quantity }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Jumlah Pesanan</label>
                    <input type="number" name="quantity" step="0.01" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <button type="submit"
                    class="w-full bg-indigo-600 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-indigo-700 transition uppercase text-xs tracking-widest">
                    Simpan Pesanan
                </button>
            </form>
        </div>
    </div>
@endsection