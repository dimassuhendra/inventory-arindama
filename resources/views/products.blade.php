@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-white">Master Produk</h2>
                <p class="text-sm text-blue-100/70 font-domine uppercase tracking-widest mt-1">
                    Total Inventaris: <span class="text-arindama text-blue-100/70">{{ $products->total() }} Item</span>
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('products.export') }}"
                    class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-2xl shadow-lg transition flex items-center gap-2 font-bold text-sm">
                    <i class="fa-solid fa-file-excel"></i> Ekspor Excel
                </a>

                <button onclick="openModal('add')"
                    class="bg-white hover:bg-blue-100/70 text-blue-700 px-6 py-3 rounded-2xl shadow-lg transition flex items-center gap-2 font-bold text-sm">
                    <i class="fa-solid fa-box-archive"></i> Tambah Produk Baru
                </button>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Info Produk
                        </th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kategori &
                            Supplier</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">
                            Stok & Unit</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-slate-100 border border-slate-200 overflow-hidden shadow-sm">
                                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://ui-avatars.com/api/?name=' . urlencode($product->name) . '&background=E2E8F0&color=64748B' }}"
                                        class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 line-clamp-1">{{ $product->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-mono italic">{{ $product->slug }}</p>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <span
                                        class="text-[9px] font-bold text-arindama bg-blue-50 px-2 py-0.5 rounded-md w-fit uppercase tracking-tighter">
                                        {{ $product->category->name }}
                                    </span>
                                    <span class="text-[10px] text-slate-500 flex items-center gap-1">
                                        <i class="fa-solid fa-truck-fast text-[9px] text-slate-300"></i>
                                        {{ $product->supplier->name ?? 'Supplier Belum Tersedia' }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <p
                                    class="text-sm font-bold {{ $product->quantity <= 5 ? 'text-red-500 animate-pulse' : 'text-slate-700' }}">
                                    {{ number_format($product->quantity, 0) }}
                                </p>
                                <p class="text-[10px] text-slate-400 uppercase font-medium">{{ $product->unit }}</p>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <button
                                        onclick="openModal('edit', {{ $product->id }}, '{{ $product->name }}', {{ $product->quantity }}, {{ $product->category_id }}, '{{ $product->supplier_id ?? '' }}', '{{ $product->unit }}', '{{ addslashes($product->description) }}')"
                                        class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition flex items-center justify-center">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus produk ini secara permanen?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="w-9 h-9 rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition flex items-center justify-center">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-box-open text-4xl text-slate-200 mb-4"></i>
                                    <p class="text-slate-400 italic text-sm font-domine">Belum ada data produk yang terdaftar.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    <div id="modalProduct"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-xl overflow-hidden transform transition-all">
            <div class="bg-arindama p-6 text-white flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-box text-lg"></i>
                    </div>
                    <h3 id="modalTitle" class="font-bold text-lg font-domine uppercase tracking-wide">Pendaftaran Produk
                    </h3>
                </div>
                <button onclick="closeModal()" class="text-white/60 hover:text-white transition-all scale-125">
                    <i class="fa-solid fa-circle-xmark"></i>
                </button>
            </div>

            <form id="productForm" method="POST" enctype="multipart/form-data"
                class="p-8 space-y-5 overflow-y-auto max-h-[75vh]">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Kategori
                            <span class="text-red-500">*</span></label>
                        <select name="category_id" id="prod_category" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-arindama outline-none transition-all">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Supplier
                            Utama</label>
                        <select name="supplier_id" id="prod_supplier"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-arindama outline-none transition-all">
                            <option value="">-- Supplier Belum Tersedia --</option>
                            @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Lengkap
                        Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="prod_name" required placeholder="Contoh: Kertas A4 80gr"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-arindama outline-none transition-all">
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Satuan
                            (Unit) <span class="text-red-500">*</span></label>
                        <input type="text" name="unit" id="prod_unit" required placeholder="Pcs / Box / Rim"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-arindama outline-none transition-all">
                    </div>

                    <div id="qtySection" class="hidden">
                        <label
                            class="block text-[10px] font-bold text-amber-500 uppercase tracking-widest mb-2 italic">Koreksi
                            Stok Manual</label>
                        <input type="number" name="quantity" id="prod_qty" step="0.01"
                            class="w-full bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-amber-400 outline-none transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Deskripsi
                        Produk <span class="text-red-500">*</span></label>
                    <textarea name="description" id="prod_desc" required rows="3"
                        placeholder="Jelaskan spesifikasi produk..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-arindama transition-all"></textarea>
                </div>

                <div class="p-4 border-2 border-dashed border-slate-100 rounded-2xl bg-slate-50/50">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Foto Produk
                        (Opsional)</label>
                    <input type="file" name="image"
                        class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-arindama file:text-white hover:file:bg-blue-700 transition-all cursor-pointer">
                    <p class="mt-2 text-[9px] text-slate-400">*Format JPG, JPEG, PNG. Max 2MB.</p>
                </div>

                <button type="submit"
                    class="w-full bg-arindama text-white font-bold py-4 rounded-2xl shadow-xl hover:bg-blue-700 transition-all uppercase text-[10px] tracking-widest flex items-center justify-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Konfirmasi & Simpan Data
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2500,
                background: '#ffffff',
                borderRadius: '20px'
            });
        @endif

        @if(session('error') || $errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: "{{ session('error') ?? 'Pastikan semua input wajib sudah terisi dengan benar.' }}",
                confirmButtonColor: '#2b51cc'
            });
        @endif

            // Modal Logic
            function openModal(mode, id = null, name = '', qty = 0, catId = '', supId = '', unit = '', desc = '') {
                const modal = document.getElementById('modalProduct');
                const form = document.getElementById('productForm');
                const method = document.getElementById('formMethod');
                const title = document.getElementById('modalTitle');
                const qtySection = document.getElementById('qtySection');

                // Reset & Tampilkan Modal
                modal.classList.remove('hidden');

                if (mode === 'edit') {
                    title.innerText = 'Perbarui Data Produk';
                    form.action = `/products/${id}`;
                    method.value = 'PUT';

                    // Isi Value
                    document.getElementById('prod_name').value = name;
                    document.getElementById('prod_category').value = catId;
                    document.getElementById('prod_supplier').value = supId ? supId : "";
                    document.getElementById('prod_unit').value = unit;
                    document.getElementById('prod_desc').value = desc;
                    document.getElementById('prod_qty').value = qty;

                    qtySection.classList.remove('hidden');
                } else {
                    title.innerText = 'Pendaftaran Produk Baru';
                    form.action = "{{ route('products.store') }}";
                    method.value = 'POST';
                    form.reset();

                    qtySection.classList.add('hidden');
                }
            }

        function closeModal() {
            document.getElementById('modalProduct').classList.add('hidden');
        }

        // Close modal on background click
        window.onclick = function (event) {
            const modal = document.getElementById('modalProduct');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
@endsection