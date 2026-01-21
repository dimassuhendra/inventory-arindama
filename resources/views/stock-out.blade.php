@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Stock Out (Barang Keluar)</h2>
                <p class="text-sm text-slate-500 font-domine uppercase tracking-widest mt-1">Pengeluaran Inventaris</p>
            </div>
            <button onclick="document.getElementById('modalStockOut').classList.remove('hidden')"
                class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-2xl shadow-lg shadow-red-200 transition flex items-center gap-2 font-bold text-sm">
                <i class="fa-solid fa-minus"></i> Catat Barang Keluar
            </button>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-sm border border-red-50 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal Keluar
                        </th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Produk
                        </th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jumlah</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($exits as $exit)
                        <tr class="hover:bg-red-50/30 transition group">
                            <td class="px-6 py-4 text-sm text-slate-600 font-domine">
                                {{ \Carbon\Carbon::parse($exit->exit_date)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-slate-800">{{ $exit->product->name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-[10px] font-bold">
                                    -{{ $exit->quantity }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <button onclick="alert('Detail: {{ $exit->description ?: 'Tanpa keterangan' }}')"
                                        class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition flex items-center justify-center">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </button>
                                    <form action="{{ route('stock-out.destroy', $exit->id) }}" method="POST"
                                        onsubmit="return confirm('Batalkan transaksi ini? Stok akan dikembalikan.')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition flex items-center justify-center">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic text-sm">Belum ada riwayat
                                pengeluaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="modalStockOut"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="bg-red-500 p-6 text-white flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-file-export"></i>
                    <h3 class="font-bold text-lg font-domine">Input Barang Keluar</h3>
                </div>
                <button onclick="document.getElementById('modalStockOut').classList.add('hidden')"
                    class="text-white/80 hover:text-white transition">
                    <i class="fa-solid fa-circle-xmark text-xl"></i>
                </button>
            </div>

            <form action="{{ route('stock-out.store') }}" method="POST" class="p-8 space-y-5">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Pilih
                        Barang</label>
                    <select name="product_id" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-red-500">
                        <option value="">-- Tersedia --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} (Stok: {{ $product->quantity }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Jumlah
                            Keluar</label>
                        <input type="number" name="quantity" min="1" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Tanggal</label>
                        <input type="date" name="exit_date" value="{{ date('Y-m-d') }}" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Keterangan /
                        Keperluan</label>
                    <textarea name="description" rows="2" placeholder="Contoh: Distribusi ke Cabang A"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-red-500"></textarea>
                </div>
                <button type="submit"
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-red-100 transition duration-300 uppercase text-xs tracking-widest">
                    Konfirmasi Keluar
                </button>
            </form>
        </div>
    </div>
@endsection