@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-2xl lg:text-3xl font-bold text-primary font-serif">Kategori Produk</h2>
                <p class="text-xs text-secondary font-sans uppercase tracking-widest mt-2 font-semibold">Pengelompokan
                    Inventaris</p>
            </div>
            <button onclick="openCategoryModal('add')"
                class="bg-primary hover:bg-secondary text-white px-6 py-3.5 rounded-xl shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-3 font-bold text-sm tracking-wide">
                <i class="fa-solid fa-tags"></i> Tambah Kategori
            </button>
        </div>

        <!-- Grid Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-6 font-sans">
            @forelse($categories as $category)
                <!-- Card sekarang memiliki cursor-pointer dan onclick untuk membuka modal produk -->
                <div onclick="openProductsModal(this, '{{ addslashes($category->name) }}')"
                    data-products="{{ json_encode($category->products) }}"
                    class="bg-white p-6 rounded-2xl border border-accent/30 shadow-sm hover:shadow-md hover:border-secondary/50 transition-all group cursor-pointer relative overflow-hidden">

                    <div class="flex justify-between items-start mb-5 relative z-10">
                        <div
                            class="w-12 h-12 bg-primary/10 text-primary border border-accent/30 rounded-xl flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-folder-open"></i>
                        </div>

                        <!-- Action Buttons -->
                        <!-- event.stopPropagation() ditambahkan agar klik tombol tidak memicu klik pada card -->
                        <div
                            class="flex gap-1 bg-gray-50 rounded-lg p-1 border border-gray-100 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button
                                onclick="event.stopPropagation(); openCategoryModal('edit', {{ $category->id }}, '{{ addslashes($category->name) }}')"
                                class="p-2 text-amber-500 hover:bg-white hover:shadow-sm rounded-md transition"
                                title="Edit Kategori">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </button>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                onclick="event.stopPropagation();">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Hapus kategori ini? Semua produk terkait mungkin akan terpengaruh.')"
                                    class="p-2 text-red-500 hover:bg-white hover:shadow-sm rounded-md transition"
                                    title="Hapus Kategori">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <h3 class="font-bold text-gray-800 text-lg mb-1 relative z-10">{{ $category->name }}</h3>
                    <p class="text-[11px] text-gray-400 font-medium bg-gray-50 px-3 py-1 rounded-full w-fit relative z-10">
                        {{ $category->products_count }} Produk Terkait
                    </p>

                    <!-- Dekorasi Background Hover -->
                    <div
                        class="absolute -bottom-6 -right-6 w-24 h-24 bg-primary/5 rounded-full blur-xl group-hover:bg-secondary/10 transition-colors z-0">
                    </div>
                </div>
            @empty
                <div
                    class="col-span-1 md:col-span-3 xl:col-span-4 bg-white border-2 border-dashed border-accent/40 rounded-3xl p-12 text-center">
                    <p class="text-gray-400 italic">Belum ada kategori. Silahkan tambah kategori baru untuk mengelompokkan
                        produk.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal Form Tambah/Edit Kategori -->
    <div id="modalCategory"
        class="fixed inset-0 bg-primary/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div
            class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all border border-accent/20">
            <div class="bg-primary p-6 text-white flex justify-between items-center">
                <h3 id="modalTitle" class="font-bold text-lg font-serif tracking-wide">Tambah Kategori</h3>
                <button onclick="closeModal()" class="text-accent hover:text-white transition transform hover:rotate-90">
                    <i class="fa-solid fa-circle-xmark text-2xl"></i>
                </button>
            </div>

            <form id="categoryForm" method="POST" class="p-8 space-y-6 font-sans">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Nama
                        Kategori</label>
                    <input type="text" name="name" id="cat_name" required placeholder="Misal: Elektronik IT"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                </div>

                <button type="submit"
                    class="w-full bg-primary hover:bg-secondary text-white font-bold py-4 rounded-xl shadow-lg shadow-primary/20 transition-all uppercase text-xs tracking-widest">
                    Simpan Kategori
                </button>
            </form>
        </div>
    </div>

    <!-- Modal Detail Produk Kategori -->
    <div id="modalProductsList"
        class="fixed inset-0 bg-primary/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div
            class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all border border-accent/20 flex flex-col max-h-[85vh]">
            <div class="bg-primary p-6 text-white flex justify-between items-center shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-box-open text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg font-serif tracking-wide">Isi Kategori</h3>
                        <p id="modalProductsTitle" class="text-xs text-accent font-medium mt-0.5">Memuat...</p>
                    </div>
                </div>
                <button onclick="closeProductsModal()"
                    class="text-accent hover:text-white transition transform hover:rotate-90">
                    <i class="fa-solid fa-circle-xmark text-2xl"></i>
                </button>
            </div>

            <!-- List Container (Scrollable) -->
            <div class="p-6 overflow-y-auto font-sans custom-scrollbar bg-gray-50/50">
                <table
                    class="w-full text-left border-collapse bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th
                                class="px-4 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest w-12 text-center">
                                No</th>
                            <th class="px-4 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Nama Produk
                            </th>
                            <th class="px-4 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest text-right">
                                Stok Tersedia</th>
                        </tr>
                    </thead>
                    <tbody id="modalProductsBody" class="divide-y divide-gray-50">
                        <!-- Data akan di-inject via JS -->
                    </tbody>
                </table>
            </div>

            <div class="p-4 bg-white border-t border-gray-100 text-center shrink-0">
                <button onclick="closeProductsModal()"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-6 rounded-xl transition-all text-sm">
                    Tutup Tampilan
                </button>
            </div>
        </div>
    </div>

    <script>
        // Logika Modal Kategori (Tambah/Edit)
        function openCategoryModal(mode, id = null, name = '') {
            const modal = document.getElementById('modalCategory');
            const form = document.getElementById('categoryForm');
            const method = document.getElementById('formMethod');
            const title = document.getElementById('modalTitle');

            modal.classList.remove('hidden');
            if (mode === 'edit') {
                title.innerText = 'Edit Kategori';
                form.action = `/categories/${id}`;
                method.value = 'PUT';
                document.getElementById('cat_name').value = name;
            } else {
                title.innerText = 'Tambah Kategori';
                form.action = "{{ route('categories.store') }}";
                method.value = 'POST';
                form.reset();
            }
        }

        function closeModal() {
            document.getElementById('modalCategory').classList.add('hidden');
        }

        // Logika Modal Daftar Produk
        function openProductsModal(element, categoryName) {
            const modal = document.getElementById('modalProductsList');
            const title = document.getElementById('modalProductsTitle');
            const tbody = document.getElementById('modalProductsBody');

            // Ambil data JSON dari atribut element
            const productsData = element.getAttribute('data-products');
            let products = [];

            try {
                products = JSON.parse(productsData);
            } catch (e) {
                console.error("Error parsing products JSON", e);
            }

            title.innerText = categoryName;
            tbody.innerHTML = ''; // Kosongkan tabel sebelumnya

            // Render isi tabel
            if (products.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="3" class="px-4 py-12 text-center">
                            <i class="fa-solid fa-box-open text-3xl text-gray-300 mb-3"></i>
                            <p class="text-gray-400 italic text-sm">Belum ada produk yang terdaftar di kategori ini.</p>
                        </td>
                    </tr>
                `;
            } else {
                products.forEach((product, index) => {
                    // Beri warna merah jika stok menipis (<= 5)
                    const stockClass = product.quantity <= 5 ? 'text-red-500 animate-pulse' : 'text-gray-800';

                    tbody.innerHTML += `
                        <tr class="hover:bg-primary/5 transition duration-200">
                            <td class="px-4 py-3 text-sm text-gray-500 font-medium text-center">${index + 1}</td>
                            <td class="px-4 py-3">
                                <p class="text-sm font-bold text-gray-800">${product.name}</p>
                                <p class="text-[10px] text-gray-400 font-mono italic mt-0.5">${product.slug}</p>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <p class="text-sm font-bold ${stockClass}">${Number(product.quantity).toLocaleString('id-ID')}</p>
                                <p class="text-[10px] text-gray-400 uppercase font-semibold mt-0.5">${product.unit}</p>
                            </td>
                        </tr>
                    `;
                });
            }

            // Tampilkan modal
            modal.classList.remove('hidden');
        }

        function closeProductsModal() {
            document.getElementById('modalProductsList').classList.add('hidden');
        }

        // Menutup modal jika klik di luar area modal
        window.onclick = function(event) {
            const modalCategory = document.getElementById('modalCategory');
            const modalProducts = document.getElementById('modalProductsList');

            if (event.target == modalCategory) {
                closeModal();
            }
            if (event.target == modalProducts) {
                closeProductsModal();
            }
        }
    </script>
@endsection
