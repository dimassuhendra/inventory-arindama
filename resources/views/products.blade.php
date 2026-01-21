@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Master Produk</h2>
                <p class="text-sm text-slate-500 font-domine uppercase tracking-widest mt-1">Kelola Daftar Barang</p>
            </div>
            <button onclick="openModal('add')"
                class="bg-arindama hover:bg-blue-700 text-white px-6 py-3 rounded-2xl shadow-lg transition flex items-center gap-2 font-bold text-sm">
                <i class="fa-solid fa-plus"></i> Tambah Produk
            </button>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Produk
                        </th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">
                            Stok Saat Ini</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-slate-800">{{ $product->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="text-sm font-domine {{ $product->quantity <= 5 ? 'text-red-500 font-bold' : 'text-slate-600' }}">
                                    {{ $product->quantity }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <button
                                        onclick="openModal('edit', {{ $product->id }}, '{{ $product->name }}', {{ $product->quantity }})"
                                        class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition flex items-center justify-center">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </button>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus produk ini?')">
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
                            <td colspan="3" class="px-6 py-10 text-center text-slate-400 italic text-sm">Belum ada produk
                                terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4">{{ $products->links() }}</div>
        </div>
    </div>

    <div id="modalProduct"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="bg-arindama p-6 text-white flex justify-between items-center">
                <h3 id="modalTitle" class="font-bold text-lg font-domine">Pendaftaran Produk</h3>
                <button onclick="closeModal()" class="text-white/80 hover:text-white"><i
                        class="fa-solid fa-circle-xmark text-xl"></i></button>
            </div>

            <form id="productForm" method="POST" class="p-8 space-y-5">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Produk
                        Baru</label>
                    <input type="text" name="name" id="prod_name" required placeholder="Contoh: Laptop HP Pavillion"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-arindama outline-none">
                </div>

                <div id="inputQuantityWrapper">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Stok
                        (Koreksi)</label>
                    <input type="number" name="quantity" id="prod_qty" min="0"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-arindama outline-none">
                    <p class="text-[10px] text-amber-500 mt-1">*Hanya gunakan untuk koreksi darurat</p>
                </div>

                <button type="submit"
                    class="w-full bg-arindama text-white font-bold py-4 rounded-xl shadow-lg hover:bg-blue-700 transition uppercase text-[10px] tracking-widest">
                    Konfirmasi Simpan
                </button>
            </form>
        </div>
    </div>

    <script>
        function openModal(mode, id = null, name = '', qty = 0) {
            const modal = document.getElementById('modalProduct');
            const form = document.getElementById('productForm');
            const method = document.getElementById('formMethod');
            const title = document.getElementById('modalTitle');
            const qtyWrapper = document.getElementById('inputQuantityWrapper');

            modal.classList.remove('hidden');

            if (mode === 'edit') {
                title.innerText = 'Edit / Koreksi Produk';
                form.action = `/products/${id}`;
                method.value = 'PUT';
                document.getElementById('prod_name').value = name;
                document.getElementById('prod_qty').value = qty;
                qtyWrapper.classList.remove('hidden');
            } else {
                title.innerText = 'Daftarkan Produk Baru';
                form.action = "{{ route('products.store') }}";
                method.value = 'POST';
                form.reset();
                qtyWrapper.classList.add('hidden');
            }
        }

        function closeModal() {
            document.getElementById('modalProduct').classList.add('hidden');
        }
    </script>
@endsection