@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                <h3 class="font-bold text-slate-800 mb-4 italic">Pilih Barang</h3>
                <form action="{{ route('cart.add') }}" method="POST" class="space-y-4">
                    @csrf
                    <select name="product_id"
                        class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500">
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} (Stok: {{ $p->quantity }})</option>
                        @endforeach
                    </select>
                    <input type="number" name="quantity" step="0.01" placeholder="Jumlah"
                        class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-sm">
                    <button
                        class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl hover:bg-indigo-700 transition uppercase text-[10px] tracking-widest">
                        + Tambah ke List
                    </button>
                </form>
            </div>

            @if($activeCart)
                <div class="bg-indigo-900 p-6 rounded-[2rem] text-white shadow-xl">
                    <h3 class="font-bold mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-cart-shopping"></i> Keranjang Anda
                    </h3>
                    <div class="space-y-3 mb-6">
                        @foreach($activeCart->items as $item)
                            <div class="flex justify-between text-xs border-b border-indigo-800 pb-2">
                                <span>{{ $item->product->name }}</span>
                                <span class="font-bold text-indigo-300">{{ $item->quantity }}</span>
                            </div>
                        @endforeach
                    </div>
                    <form action="{{ route('cart.submit', $activeCart->id) }}" method="POST">
                        @csrf
                        <button
                            class="w-full bg-white text-indigo-900 font-bold py-3 rounded-xl text-[10px] tracking-widest uppercase">
                            Submit Permintaan
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                <table class="w-full text-left border-collapse text-xs">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 font-bold text-slate-400">REF / USER</th>
                            <th class="px-6 py-4 font-bold text-slate-400">BARANG</th>
                            <th class="px-6 py-4 font-bold text-slate-400">STATUS</th>
                            <th class="px-6 py-4 font-bold text-slate-400">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($requests as $req)
                            <tr>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-slate-800">{{ $req->reference_number }}</span>
                                    <p class="text-[10px] text-slate-400">{{ $req->user->name }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $req->items->count() }} Item(s)
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 rounded-full text-[10px] font-bold uppercase 
                                        {{ $req->status == 'approved' ? 'bg-green-100 text-green-600' : 'bg-amber-100 text-amber-600' }}">
                                        {{ $req->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($req->status == 'pending' && Auth::user()->role == 'admin')
                                        <form action="{{ route('cart.approve', $req->id) }}" method="POST">
                                            @csrf
                                            <button
                                                class="bg-green-500 text-white px-3 py-1 rounded-lg font-bold text-[10px]">APPROVE</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection