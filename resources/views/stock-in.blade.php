@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-white">Stock In (Barang Masuk)</h2>
                <p class="text-sm text-blue-100/70 font-acme uppercase tracking-widest mt-1">Kelola Riwayat Stok Masuk</p>
            </div>
            <button onclick="openModal('add')"
                class="bg-arindama hover:bg-blue-700 text-white px-6 py-3 rounded-2xl shadow-lg transition flex items-center gap-2 font-bold text-sm">
                <i class="fa-solid fa-plus"></i> Tambah Stok
            </button>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Produk</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Qty
                        </th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Supplier</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($entries as $entry)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 text-xs font-medium text-slate-600">
                                {{ date('d M Y', strtotime($entry->entry_date)) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-slate-800">{{ $entry->product->name }}</div>
                                <div class="text-[9px] text-slate-400">Petugas: {{ $entry->user->name ?? 'System' }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-green-50 text-green-600 px-3 py-1 rounded-full text-xs font-bold">
                                    +{{ number_format($entry->quantity, 0) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">
                                {{ $entry->supplier->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <button
                                        onclick="openModal('edit', {{ $entry->id }}, {{ $entry->product_id }}, {{ $entry->quantity }}, '{{ $entry->entry_date }}')"
                                        class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition flex items-center justify-center">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>
                                    <form action="{{ route('stock-in.destroy', $entry->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus data ini? Stok produk akan berkurang.')">
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
                            <td colspan="5" class="px-6 py-10 text-center text-slate-400 italic">Belum ada riwayat masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4">{{ $entries->links() }}</div>
        </div>
    </div>

    <div id="modalStockIn"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="bg-arindama p-6 text-white flex justify-between items-center">
                <h3 id="modalTitle" class="font-bold text-lg font-acme tracking-wide">INPUT STOK MASUK</h3>
                <button onclick="closeModal()" class="text-white/80 hover:text-white"><i
                        class="fa-solid fa-circle-xmark text-xl"></i></button>
            </div>

            <form id="stockForm" method="POST" class="p-8 space-y-5">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Pilih
                        Produk</label>
                    <select name="product_id" id="prod_id" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-arindama outline-none">
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">[{{ $product->unit }}] {{ $product->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-[9px] text-amber-500 mt-1">*Supplier akan terdeteksi otomatis sesuai database produk</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Jumlah
                            (Qty)</label>
                        <input type="number" name="quantity" id="prod_qty" step="0.01" min="0.01" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-arindama">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Tanggal
                            Masuk</label>
                        <input type="date" name="entry_date" id="prod_date" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-arindama">
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-arindama text-white font-bold py-4 rounded-xl shadow-lg hover:bg-blue-700 transition uppercase text-xs tracking-widest">
                    Konfirmasi Simpan
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
            @if(session('success')) Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 2000, showConfirmButton: false }); @endif
        @if(session('error')) Swal.fire({ icon: 'error', title: 'Gagal', text: "{{ session('error') }}" }); @endif

            function openModal(mode, id = null, prodId = null, qty = null, date = null) {
                const modal = document.getElementById('modalStockIn');
                const form = document.getElementById('stockForm');
                const method = document.getElementById('formMethod');
                const title = document.getElementById('modalTitle');

                modal.classList.remove('hidden');
                if (mode === 'edit') {
                    title.innerText = 'Edit Riwayat Stok';
                    form.action = `/stock-in/${id}`;
                    method.value = 'PUT';
                    document.getElementById('prod_id').value = prodId;
                    document.getElementById('prod_id').disabled = true; // Produk tidak boleh diganti saat edit agar stok tidak kacau
                    document.getElementById('prod_qty').value = qty;
                    document.getElementById('prod_date').value = date;
                } else {
                    title.innerText = 'Input Stok Masuk';
                    form.action = "{{ route('stock-in.store') }}";
                    method.value = 'POST';
                    form.reset();
                    document.getElementById('prod_id').disabled = false;
                    document.getElementById('prod_date').valueAsDate = new Date();
                }
            }

        function closeModal() { document.getElementById('modalStockIn').classList.add('hidden'); }
    </script>
@endsection