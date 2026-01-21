@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Stock In (Barang Masuk)</h2>
                <p class="text-sm text-slate-500 font-domine uppercase tracking-widest mt-1">Management Riwayat Stok</p>
            </div>
            <button onclick="openModal('add')"
                class="bg-arindama hover:bg-blue-700 text-white px-6 py-3 rounded-2xl shadow-lg shadow-blue-200 transition flex items-center gap-2 font-bold text-sm">
                <i class="fa-solid fa-plus"></i> Tambah Stok
            </button>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-blue-50 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Produk
                        </th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jumlah</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($entries as $entry)
                        <tr class="hover:bg-blue-50/50 transition group">
                            <td class="px-6 py-4 text-sm text-slate-600 font-domine">
                                {{ \Carbon\Carbon::parse($entry->entry_date)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-slate-800">{{ $entry->product->name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-[10px] font-bold">
                                    +{{ $entry->quantity }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <button
                                        onclick="viewDetail('{{ $entry->product->name }}', '{{ $entry->quantity }}', '{{ $entry->entry_date }}', '{{ $entry->description }}')"
                                        class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition flex items-center justify-center">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </button>
                                    <button
                                        onclick="openModal('edit', {{ $entry->id }}, {{ $entry->product_id }}, {{ $entry->quantity }}, '{{ $entry->entry_date }}', '{{ $entry->description }}')"
                                        class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition flex items-center justify-center">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>
                                    <form action="{{ route('stock-in.destroy', $entry->id) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini? Stok produk akan dikurangi kembali.')">
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
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic text-sm">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="modalStockIn"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="bg-arindama p-6 text-white flex justify-between items-center">
                <h3 id="modalTitle" class="font-bold text-lg font-domine">Input Stok Masuk</h3>
                <button onclick="closeModal()" class="text-white/80 hover:text-white"><i
                        class="fa-solid fa-circle-xmark text-xl"></i></button>
            </div>

            <form id="stockForm" action="{{ route('stock-in.store') }}" method="POST" class="p-8 space-y-5">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Pilih
                        Produk</label>
                    <select name="product_id" id="prod_id" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-arindama outline-none">
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Jumlah
                            Qty</label>
                        <input type="number" name="quantity" id="prod_qty" min="1" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-arindama outline-none">
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Tanggal</label>
                        <input type="date" name="entry_date" id="prod_date" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-arindama outline-none">
                    </div>
                </div>
                <div>
                    <label
                        class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Keterangan</label>
                    <textarea name="description" id="prod_desc" rows="2"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-arindama outline-none"></textarea>
                </div>
                <button type="submit"
                    class="w-full bg-arindama text-white font-bold py-4 rounded-xl shadow-lg hover:bg-blue-700 transition uppercase text-xs tracking-widest">Simpan
                    Data</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(mode, id = null, prodId = null, qty = null, date = null, desc = '') {
            const modal = document.getElementById('modalStockIn');
            const form = document.getElementById('stockForm');
            const method = document.getElementById('formMethod');
            const title = document.getElementById('modalTitle');

            modal.classList.remove('hidden');
            if (mode === 'edit') {
                title.innerText = 'Edit Stok Masuk';
                form.action = `/stock-in/${id}`;
                method.value = 'PUT';
                document.getElementById('prod_id').value = prodId;
                document.getElementById('prod_qty').value = qty;
                document.getElementById('prod_date').value = date;
                document.getElementById('prod_desc').value = desc;
            } else {
                title.innerText = 'Input Stok Masuk';
                form.action = "{{ route('stock-in.store') }}";
                method.value = 'POST';
                form.reset();
            }
        }

        function closeModal() {
            document.getElementById('modalStockIn').classList.add('hidden');
        }

        function viewDetail(name, qty, date, desc) {
            alert(`Detail Barang Masuk:\n\nProduk: ${name}\nJumlah: ${qty}\nTanggal: ${date}\nKeterangan: ${desc}`);
        }
    </script>
@endsection