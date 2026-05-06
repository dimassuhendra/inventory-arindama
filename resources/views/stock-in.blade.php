@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-2xl lg:text-3xl font-bold text-primary font-serif">Stock In (Masuk)</h2>
                <p class="text-xs text-secondary font-sans uppercase tracking-widest mt-2 font-semibold">Kelola Riwayat Stok
                    Masuk</p>
            </div>
            <button onclick="openModal('add')"
                class="bg-primary hover:bg-secondary text-white px-6 py-3.5 rounded-xl shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-3 font-bold text-sm tracking-wide">
                <i class="fa-solid fa-plus"></i> Tambah Stok
            </button>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-accent/30 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-primary/5 border-b border-accent/20">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold text-primary uppercase tracking-widest">Tanggal</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-primary uppercase tracking-widest">Produk</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-primary uppercase tracking-widest text-center">Qty
                        </th>
                        <th class="px-6 py-4 text-[10px] font-bold text-primary uppercase tracking-widest">Supplier</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-primary uppercase tracking-widest text-center">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 font-sans">
                    @forelse($entries as $entry)
                        <tr class="hover:bg-primary/5 transition duration-200">
                            <td class="px-6 py-4 text-xs font-medium text-gray-600">
                                {{ date('d M Y', strtotime($entry->entry_date)) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-800">{{ $entry->product->name }}</div>
                                <div class="text-[10px] text-gray-400 mt-0.5">Petugas: <span
                                        class="font-medium text-gray-500">{{ $entry->user->name ?? 'System' }}</span></div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="bg-secondary/20 text-primary border border-secondary/30 px-3 py-1.5 rounded-full text-[11px] font-bold tracking-wide">
                                    +{{ number_format($entry->quantity, 0) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500">
                                {{ $entry->supplier->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <button
                                        onclick="openModal('edit', {{ $entry->id }}, {{ $entry->product_id }}, {{ $entry->quantity }}, '{{ $entry->entry_date }}')"
                                        class="w-8 h-8 rounded-lg bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition flex items-center justify-center shadow-sm">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>
                                    <form action="{{ route('stock-in.destroy', $entry->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus data ini? Stok produk akan berkurang.')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition flex items-center justify-center shadow-sm">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic text-sm">Belum ada riwayat
                                masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-50">{{ $entries->links() }}</div>
        </div>
    </div>

    <!-- Modal Form -->
    <div id="modalStockIn"
        class="fixed inset-0 bg-primary/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div
            class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all border border-accent/20">
            <div class="bg-primary p-6 text-white flex justify-between items-center">
                <h3 id="modalTitle" class="font-bold text-lg font-serif tracking-wide">INPUT STOK MASUK</h3>
                <button onclick="closeModal()" class="text-accent hover:text-white transition transform hover:rotate-90">
                    <i class="fa-solid fa-circle-xmark text-2xl"></i>
                </button>
            </div>

            <form id="stockForm" method="POST" class="p-8 space-y-5 font-sans">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Pilih
                        Produk</label>
                    <select name="product_id" id="prod_id" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">[{{ $product->unit }}] {{ $product->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-[9px] text-secondary mt-1.5 font-medium"><i class="fa-solid fa-circle-info mr-1"></i>
                        Supplier otomatis menyesuaikan master produk</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Jumlah
                            (Qty)</label>
                        <input type="number" name="quantity" id="prod_qty" step="0.01" min="0.01" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-primary focus:border-primary transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Tanggal
                            Masuk</label>
                        <input type="date" name="entry_date" id="prod_date" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-primary focus:border-primary transition">
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-primary text-white font-bold py-4 rounded-xl shadow-lg shadow-primary/30 hover:bg-secondary transition-all uppercase text-xs tracking-widest mt-4">
                    Konfirmasi Simpan
                </button>
            </form>
        </div>
    </div>

    <!-- Scripts remain identical -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false
            });
        @endif
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: "{{ session('error') }}"
            });
        @endif

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
                document.getElementById('prod_id').disabled = true;
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

        function closeModal() {
            document.getElementById('modalStockIn').classList.add('hidden');
        }
    </script>
@endsection
