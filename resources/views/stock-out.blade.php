@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Stock Out (Barang Keluar)</h2>
                <p class="text-sm text-slate-500 font-domine uppercase tracking-widest mt-1">Pengeluaran Inventaris</p>
            </div>
            <button onclick="document.getElementById('modalStockOut').classList.remove('hidden')"
                class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-2xl shadow-lg transition flex items-center gap-2 font-bold text-sm">
                <i class="fa-solid fa-minus"></i> Catat Barang Keluar
            </button>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-red-50 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Produk</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">
                            Jumlah</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Petugas</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($exits as $exit)
                        <tr class="hover:bg-red-50/30 transition">
                            <td class="px-6 py-4 text-sm text-slate-600 font-domine">
                                {{ date('d/m/Y', strtotime($exit->exit_date)) }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-slate-800">{{ $exit->product->name }}</span>
                                <p class="text-[10px] text-slate-400 italic line-clamp-1">
                                    {{ $exit->description ?: 'Tanpa keterangan' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-[10px] font-bold">
                                    -{{ $exit->quantity }} {{ $exit->product->unit }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">
                                {{ $exit->user->name ?? 'System' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <form action="{{ route('stock-out.destroy', $exit->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus record ini? Stok akan dikembalikan ke gudang.')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="w-9 h-9 rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition flex items-center justify-center">
                                            <i class="fa-solid fa-rotate-left text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Belum ada riwayat pengeluaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">{{ $exits->links() }}</div>
        </div>
    </div>

    <div id="modalStockOut"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="bg-red-500 p-6 text-white flex justify-between items-center">
                <h3 class="font-bold text-lg font-domine">INPUT BARANG KELUAR</h3>
                <button onclick="document.getElementById('modalStockOut').classList.add('hidden')"
                    class="text-white/80 hover:text-white"><i class="fa-solid fa-circle-xmark text-xl"></i></button>
            </div>

            <form action="{{ route('stock-out.store') }}" method="POST" class="p-8 space-y-5">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Pilih
                        Barang</label>
                    <select name="product_id" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 outline-none">
                        <option value="">-- Pilih Produk Tersedia --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} (Sisa: {{ $product->quantity }}
                                {{ $product->unit }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Jumlah
                            Keluar</label>
                        <input type="number" name="quantity" step="0.01" min="0.01" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 outline-none">
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Tanggal</label>
                        <input type="date" name="exit_date" value="{{ date('Y-m-d') }}" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Keterangan /
                        Tujuan</label>
                    <textarea name="description" rows="2" placeholder="Misal: Untuk kebutuhan kantor"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 outline-none"></textarea>
                </div>

                <button type="submit"
                    class="w-full bg-red-500 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-red-600 transition uppercase text-xs tracking-widest">
                    Konfirmasi Pengeluaran
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success')) Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 2000, showConfirmButton: false }); @endif
        @if(session('error')) Swal.fire({ icon: 'error', title: 'Gagal', text: "{{ session('error') }}" }); @endif
    </script>
@endsection