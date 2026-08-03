@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-end gap-4">
            <div>
                <h2 class="text-2xl lg:text-3xl font-bold text-primary font-serif">Master Produk</h2>
                <p class="text-xs text-secondary font-sans uppercase tracking-widest mt-2 font-semibold">
                    Total Inventaris: <span class="text-primary font-bold">{{ $products->total() }} Item</span>
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <button onclick="openSyncModal()"
                    class="bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2.5 rounded-xl shadow-sm transition-all flex items-center gap-2 font-bold text-sm tracking-wide">
                    <i class="fa-solid fa-rotate"></i> Sinkronisasi Data
                </button>

                <button onclick="openImportModal()"
                    class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-xl shadow-sm transition-all flex items-center gap-2 font-bold text-sm tracking-wide">
                    <i class="fa-solid fa-file-import"></i> Import
                </button>

                <a href="{{ route('products.export') }}"
                    class="bg-secondary hover:bg-accent hover:text-primary text-white px-4 py-2.5 rounded-xl shadow-sm transition-all flex items-center gap-2 font-bold text-sm tracking-wide">
                    <i class="fa-solid fa-file-excel"></i> Ekspor
                </a>

                <button onclick="openModal('add')"
                    class="bg-primary hover:bg-secondary text-white px-5 py-2.5 rounded-xl shadow-lg shadow-primary/20 transition-all flex items-center gap-2 font-bold text-sm tracking-wide">
                    <i class="fa-solid fa-plus"></i> Tambah Produk
                </button>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div
            class="bg-white p-4 rounded-2xl shadow-sm border border-accent/30 flex flex-col md:flex-row justify-between items-center gap-4">
            <!-- Form Search & View Size -->
            <form method="GET" action="{{ route('products.index') }}" class="w-full flex flex-col md:flex-row gap-4">
                <!-- Pertahankan parameter sort saat mencari -->
                <input type="hidden" name="sort_by" value="{{ request('sort_by', 'created_at') }}">
                <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">

                <!-- Select View Size -->
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500 font-medium">Tampilkan:</span>
                    <select name="per_page" onchange="this.form.submit()"
                        class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-primary focus:border-primary block p-2 outline-none">
                        <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                        <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua</option>
                    </select>
                </div>

                <!-- Search Input -->
                <div class="relative flex-grow max-w-md">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-primary focus:border-primary block w-full pl-10 p-2.5 outline-none transition-all"
                        placeholder="Cari nama produk, kategori, atau supplier...">

                    @if (request('search'))
                        <a href="{{ route('products.index') }}"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-red-400 hover:text-red-600">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </div>

                <button type="submit"
                    class="hidden md:block bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-xl text-sm font-bold transition">
                    Cari
                </button>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-accent/30 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-max">
                    <thead class="bg-primary/5 border-b border-accent/20">
                        <tr>
                            <!-- Helper Sort Link Generator -->
                            @php
                                function sortLink($column, $label)
                                {
                                    $currentCol = request('sort_by', 'created_at');
                                    $currentOrd = request('sort_order', 'desc');
                                    $newOrd = $currentCol == $column && $currentOrd == 'asc' ? 'desc' : 'asc';

                                    $icon = 'fa-sort text-gray-300';
                                    if ($currentCol == $column) {
                                        $icon =
                                            $currentOrd == 'asc'
                                                ? 'fa-sort-up text-primary'
                                                : 'fa-sort-down text-primary';
                                    }

                                    $url = request()->fullUrlWithQuery(['sort_by' => $column, 'sort_order' => $newOrd]);

                                    return "<a href='{$url}' class='flex items-center gap-1 hover:text-secondary transition'>{$label} <i class='fa-solid {$icon}'></i></a>";
                                }
                            @endphp

                            <th
                                class="px-6 py-4 text-[10px] font-bold text-primary uppercase tracking-widest whitespace-nowrap">
                                {!! sortLink('name', 'Info Produk') !!}
                            </th>
                            <th class="px-6 py-4 text-[10px] font-bold text-primary uppercase tracking-widest">Kategori &
                                Supplier</th>
                            <th
                                class="px-6 py-4 text-[10px] font-bold text-primary uppercase tracking-widest text-center whitespace-nowrap justify-center flex">
                                {!! sortLink('quantity', 'Stok & Unit') !!}
                            </th>
                            <th class="px-6 py-4 text-[10px] font-bold text-primary uppercase tracking-widest text-center">
                                Mulai Digunakan
                            </th>
                            <th class="px-6 py-4 text-[10px] font-bold text-primary uppercase tracking-widest text-center">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 font-sans">
                        @forelse($products as $product)
                            <tr class="hover:bg-primary/5 transition duration-200">
                                <td class="px-6 py-4 flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-background border border-accent/30 overflow-hidden shadow-sm flex-shrink-0">
                                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://ui-avatars.com/api/?name=' . urlencode($product->name) . '&background=F2EDC2&color=346739&bold=true' }}"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800 line-clamp-1">{{ $product->name }}</p>
                                        <p class="text-[10px] text-gray-400 font-mono italic mt-0.5">{{ $product->slug }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1.5">
                                        <span
                                            class="text-[9px] font-bold text-primary bg-accent/30 border border-accent/50 px-2.5 py-1 rounded-md w-fit uppercase tracking-wider">
                                            {{ $product->category->name }}
                                        </span>
                                        <span class="text-[10px] text-gray-500 flex items-center gap-1 font-medium">
                                            <i class="fa-solid fa-truck-fast text-secondary"></i>
                                            {{ $product->supplier->name ?? 'Supplier Belum Tersedia' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <p
                                        class="text-sm font-bold {{ $product->quantity <= 5 ? 'text-red-500 animate-pulse' : 'text-gray-800' }}">
                                        {{ number_format($product->quantity, 0) }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 uppercase font-semibold mt-0.5">
                                        {{ $product->unit }}</p>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if ($product->first_used_at)
                                        <span
                                            class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-lg inline-flex items-center gap-1">
                                            <i class="fa-solid fa-circle-check"></i>
                                            {{ \Carbon\Carbon::parse($product->first_used_at)->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span
                                            class="text-[10px] font-bold text-amber-600 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-lg inline-flex items-center gap-1">
                                            <i class="fa-solid fa-box-archive"></i>
                                            Belum Digunakan
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <!-- Tombol Detail / QR -->
                                        <button
                                            onclick="openDetailModal(
                                                '{{ addslashes($product->name) }}', 
                                                '{{ $product->slug }}', 
                                                '{{ $product->category->name }}', 
                                                '{{ $product->supplier->name ?? 'Supplier Belum Tersedia' }}', 
                                                '{{ number_format($product->quantity, 0) }} {{ $product->unit }}', 
                                                '{{ addslashes($product->description) }}', 
                                                '{{ $product->image ? asset('storage/' . $product->image) : '' }}', 
                                                '{{ $product->first_used_at ? \Carbon\Carbon::parse($product->first_used_at)->format('d M Y') : 'Belum Digunakan' }}',
                                                '{{ $product->first_used_at ? \Carbon\Carbon::parse($product->first_used_at)->format('Y-m-d') : '' }}'
                                            )"
                                            class="w-9 h-9 rounded-xl bg-cyan-500 text-white hover:bg-cyan-600 transition flex items-center justify-center shadow-sm"
                                            title="Lihat Detail & QR">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </button>

                                        <!-- Tombol Edit -->
                                        <button
                                            onclick="openModal('edit', {{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->quantity }}, {{ $product->category_id }}, '{{ $product->supplier_id ?? '' }}', '{{ $product->unit }}', '{{ addslashes($product->description) }}', '{{ $product->first_used_at ? \Carbon\Carbon::parse($product->first_used_at)->format('Y-m-d') : '' }}')"
                                            class="w-9 h-9 rounded-xl bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition flex items-center justify-center shadow-sm">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus produk ini secara permanen?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="w-9 h-9 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition flex items-center justify-center shadow-sm">
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
                                        <i class="fa-solid fa-box-open text-4xl text-accent mb-4"></i>
                                        <p class="text-gray-400 italic text-sm">
                                            {{ request('search') ? 'Pencarian tidak ditemukan.' : 'Belum ada data produk yang terdaftar.' }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Tampilkan Pagination KECUALI jika view 'all' -->
            @if (request('per_page') != 'all')
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Detail Produk & QR Code -->
    <div id="modalDetailProduct"
        class="fixed inset-0 bg-primary/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div
            class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all border border-accent/20">
            <div class="bg-primary p-6 text-white flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-qrcode text-lg"></i>
                    </div>
                    <h3 class="font-bold text-lg font-serif tracking-wide">Detail & QR Code Produk</h3>
                </div>
                <button onclick="closeDetailModal()"
                    class="text-white/80 hover:text-white transition-all transform hover:rotate-90">
                    <i class="fa-solid fa-circle-xmark text-2xl"></i>
                </button>
            </div>

            <div class="p-6 space-y-6 max-h-[75vh] overflow-y-auto font-sans">
                <!-- Bagian Kartu QR Code (Siap di-download) -->
                <div id="qrPrintArea"
                    class="bg-gray-50 border border-gray-200 rounded-2xl p-5 text-center flex flex-col items-center">
                    <div id="qrcode" class="p-3 bg-white rounded-xl shadow-sm border border-gray-100 mb-3"></div>
                    <p id="detail_name_qr" class="font-bold text-gray-800 text-sm mb-0.5"></p>
                    <p id="detail_slug_qr" class="text-[10px] text-gray-400 font-mono"></p>
                    <p class="text-[9px] text-gray-400 mt-2 italic">Scan QR ini untuk melihat informasi publik</p>
                </div>

                <button onclick="downloadQR()"
                    class="w-full bg-secondary text-white border border-secondary hover:text-primary hover:bg-accent font-bold py-2.5 rounded-xl text-xs uppercase tracking-wider transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-download"></i> Unduh Gambar QR Code
                </button>

                <!-- Informasi Ringkas internal -->
                <div class="space-y-3 pt-2">
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <span class="text-[10px] text-gray-400 font-bold block uppercase">Kategori</span>
                            <span id="detail_category" class="font-semibold text-gray-700"></span>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <span class="text-[10px] text-gray-400 font-bold block uppercase">Stok & Unit</span>
                            <span id="detail_stock" class="font-semibold text-emerald-600"></span>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 text-xs">
                        <span class="text-[10px] text-gray-400 font-bold block uppercase">Supplier Utama</span>
                        <span id="detail_supplier" class="font-semibold text-gray-700"></span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <span class="text-[10px] text-gray-400 font-bold block uppercase">Mulai Digunakan</span>
                            <span id="detail_first_used" class="font-semibold text-primary"></span>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <span class="text-[10px] text-gray-400 font-bold block uppercase">Masa Pakai</span>
                            <span id="detail_usage_age" class="font-semibold text-emerald-600"></span>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 text-xs">
                        <span class="text-[10px] text-gray-400 font-bold block uppercase mb-1">Deskripsi</span>
                        <p id="detail_desc" class="text-gray-600 leading-relaxed"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Edit Produk --}}
    <div id="modalProduct"
        class="fixed inset-0 bg-primary/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div
            class="bg-white rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden transform transition-all border border-accent/20">
            <div class="bg-primary p-6 text-white flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-box text-lg"></i>
                    </div>
                    <h3 id="modalTitle" class="font-bold text-lg font-serif tracking-wide">Pendaftaran Produk</h3>
                </div>
                <button onclick="closeModal()"
                    class="text-accent hover:text-white transition-all transform hover:rotate-90">
                    <i class="fa-solid fa-circle-xmark text-2xl"></i>
                </button>
            </div>

            <form id="productForm" method="POST" enctype="multipart/form-data"
                class="p-8 space-y-5 overflow-y-auto max-h-[75vh] font-sans custom-scrollbar">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Kategori
                            <span class="text-red-500">*</span></label>
                        <select name="category_id" id="prod_category" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Supplier
                            Utama</label>
                        <select name="supplier_id" id="prod_supplier"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                            <option value="">-- Supplier Belum Tersedia --</option>
                            @foreach ($suppliers as $sup)
                                <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Nama Lengkap
                        Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="prod_name" required
                        placeholder="Contoh: Cisco Switch Catalyst 2960"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Satuan
                            (Unit) <span class="text-red-500">*</span></label>
                        <input type="text" name="unit" id="prod_unit" required placeholder="Pcs / Box / Unit"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                    </div>
                    <div id="qtySection">
                        <label id="qtyLabel"
                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">
                            Stok Awal (Opsional)
                        </label>
                        <input type="number" name="quantity" id="prod_qty" step="0.01" placeholder="0"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Deskripsi
                        Produk <span class="text-red-500">*</span></label>
                    <textarea name="description" id="prod_desc" required rows="3" placeholder="Jelaskan spesifikasi produk..."
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"></textarea>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">
                        Mulai Digunakan (Opsional)
                    </label>
                    <input type="date" name="first_used_at" id="prod_first_used"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                    <p class="mt-1 text-[9px] text-gray-400">*Kosongkan jika barang masih tersimpan sebagai stok gudang.
                    </p>
                </div>

                <div class="p-4 border-2 border-dashed border-accent/40 rounded-2xl bg-gray-50">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Foto Produk
                        (Opsional)</label>
                    <input type="file" name="image"
                        class="block w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-primary file:text-white hover:file:bg-secondary transition-all cursor-pointer">
                    <p class="mt-2 text-[9px] text-gray-400 font-medium">*Format JPG, JPEG, PNG. Max 2MB.</p>
                </div>

                <button type="submit"
                    class="w-full bg-primary text-white font-bold py-4 rounded-xl shadow-lg shadow-primary/20 hover:bg-secondary transition-all uppercase text-xs tracking-widest flex items-center justify-center gap-2 mt-2">
                    <i class="fa-solid fa-floppy-disk"></i> Konfirmasi & Simpan Data
                </button>
            </form>
        </div>
    </div>

    <!-- Modal Import Excel (Sama persis seperti sebelumnya) -->
    <div id="modalImport"
        class="fixed inset-0 bg-primary/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div
            class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all border border-accent/20">
            <div class="bg-amber-500 p-6 text-white flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-file-excel text-lg"></i>
                    </div>
                    <h3 class="font-bold text-lg font-serif tracking-wide">Import Data Produk</h3>
                </div>
                <button onclick="closeImportModal()"
                    class="text-white/80 hover:text-white transition-all transform hover:rotate-90">
                    <i class="fa-solid fa-circle-xmark text-2xl"></i>
                </button>
            </div>

            <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data"
                class="p-8 space-y-5 font-sans">
                @csrf
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-center">
                    <p class="text-xs text-amber-700 font-medium mb-3">Gunakan template Excel yang telah disediakan untuk
                        memastikan format data terbaca oleh sistem.</p>
                    <a href="{{ route('products.template') }}"
                        class="inline-flex items-center gap-2 bg-white text-amber-600 border border-amber-300 hover:bg-amber-500 hover:text-white font-bold py-2 px-4 rounded-lg text-[11px] uppercase tracking-wider transition-all">
                        <i class="fa-solid fa-download"></i> Unduh Template Excel
                    </a>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Upload File
                        Excel <span class="text-red-500">*</span></label>
                    <input type="file" name="file_excel" required accept=".xlsx, .xls, .csv"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 transition-all border border-gray-200 rounded-xl cursor-pointer">
                </div>
                <button type="submit"
                    class="w-full bg-amber-500 text-white font-bold py-4 rounded-xl shadow-lg shadow-amber-500/30 hover:bg-amber-600 transition-all uppercase text-xs tracking-widest flex items-center justify-center gap-2 mt-4">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Mulai Proses Import
                </button>
            </form>
        </div>
    </div>

    <!-- Modal Sinkronisasi Data Master -->
    <div id="modalSyncData"
        class="fixed inset-0 bg-primary/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div
            class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all border border-accent/20">
            <div class="bg-cyan-500 p-6 text-white flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-rotate text-lg"></i>
                    </div>
                    <h3 class="font-bold text-lg font-serif tracking-wide">Sinkronisasi Data Master</h3>
                </div>
                <button onclick="closeSyncModal()"
                    class="text-white/80 hover:text-white transition-all transform hover:rotate-90">
                    <i class="fa-solid fa-circle-xmark text-2xl"></i>
                </button>
            </div>

            <form action="{{ route('products.import-edit') }}" method="POST" enctype="multipart/form-data"
                class="p-8 space-y-5 font-sans">
                @csrf

                <div class="bg-cyan-50 border border-cyan-200 rounded-xl p-5 text-center">
                    <h4 class="text-cyan-800 font-bold text-sm mb-1">Tahap 1: Ekstraksi Data</h4>
                    <p class="text-[11px] text-cyan-700 font-medium mb-3">Unduh dokumen inventaris saat ini. Lakukan
                        penyesuaian nilai secara luring (offline) tanpa mengubah kolom ID Produk.</p>
                    <a href="{{ route('products.export-edit') }}"
                        class="inline-flex items-center gap-2 bg-white text-cyan-600 border border-cyan-300 hover:bg-cyan-500 hover:text-white font-bold py-2.5 px-5 rounded-lg text-[11px] uppercase tracking-wider transition-all shadow-sm">
                        <i class="fa-solid fa-download"></i> Unduh Dokumen Master
                    </a>
                </div>

                <div class="pt-2">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Tahap 2: Unggah
                        Penyesuaian <span class="text-red-500">*</span></label>
                    <input type="file" name="file_excel_edit" required accept=".xlsx, .xls, .csv"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 transition-all border border-gray-200 rounded-xl cursor-pointer focus:ring-2 focus:ring-cyan-500">
                </div>

                <button type="submit"
                    class="w-full bg-cyan-500 text-white font-bold py-4 rounded-xl shadow-lg shadow-cyan-500/30 hover:bg-cyan-600 transition-all uppercase text-xs tracking-widest flex items-center justify-center gap-2 mt-4">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Jalankan Sinkronisasi
                </button>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
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
        @if (session('error') || $errors->any())
            @php
                $errorMsg = session('error');
                if ($errors->any()) {
                    $errorMsg = implode('<br>', $errors->all());
                }
            @endphp
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                html: "{!! $errorMsg !!}", // Menggunakan html agar pesan error tampil per baris
                confirmButtonColor: '#346739',
                borderRadius: '20px'
            });
        @endif

        let qrcodeObj = null;

        function openDetailModal(name, slug, category, supplier, stock, desc, image, firstUsedFormatted, rawFirstUsed) {
            document.getElementById('modalDetailProduct').classList.remove('hidden');

            document.getElementById('detail_name_qr').innerText = name;
            document.getElementById('detail_slug_qr').innerText = slug;
            document.getElementById('detail_category').innerText = category;
            document.getElementById('detail_supplier').innerText = supplier;
            document.getElementById('detail_stock').innerText = stock;
            document.getElementById('detail_desc').innerText = desc;
            document.getElementById('detail_first_used').innerText = firstUsedFormatted;

            // --- LOGIKA HITUNG MASA PAKAI JS ---
            const usageAgeElem = document.getElementById('detail_usage_age');

            if (rawFirstUsed) {
                const startDate = new Date(rawFirstUsed);
                const today = new Date();

                // Sederhanakan jam ke 00:00:00 untuk komparasi tanggal
                startDate.setHours(0, 0, 0, 0);
                today.setHours(0, 0, 0, 0);

                let years = today.getFullYear() - startDate.getFullYear();
                let months = today.getMonth() - startDate.getMonth();
                let days = today.getDate() - startDate.getDate();

                if (days < 0) {
                    months--;
                    // Ambil jumlah hari di bulan sebelumnya
                    const prevMonth = new Date(today.getFullYear(), today.getMonth(), 0);
                    days += prevMonth.getDate();
                }

                if (months < 0) {
                    years--;
                    months += 12;
                }

                let ageText = '';
                if (years > 0) {
                    let parts = [`${years} Thn`];
                    if (months > 0) parts.push(`${months} Bln`);
                    if (days > 0) parts.push(`${days} Hari`);
                    ageText = parts.join(' ');
                } else if (months > 0) {
                    let parts = [`${months} Bln`];
                    if (days > 0) parts.push(`${days} Hari`);
                    ageText = parts.join(' ');
                } else {
                    ageText = days === 0 ? 'Hari ini' : `${days} Hari`;
                }

                usageAgeElem.innerText = ageText;
                usageAgeElem.className = "font-semibold text-emerald-600";
            } else {
                usageAgeElem.innerText = '-';
                usageAgeElem.className = "font-semibold text-gray-400";
            }

            // Generasi URL Publik untuk QR
            const publicUrl = `${window.location.origin}/p/${slug}`;

            // Reset & Generate QR Code
            const qrContainer = document.getElementById('qrcode');
            qrContainer.innerHTML = '';

            qrcodeObj = new QRCode(qrContainer, {
                text: publicUrl,
                width: 140,
                height: 140,
                colorDark: "#1e293b",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        }

        function closeDetailModal() {
            document.getElementById('modalDetailProduct').classList.add('hidden');
        }

        function downloadQR() {
            const qrImage = document.querySelector('#qrcode img');
            if (!qrImage) {
                alert('QR Code belum siap untuk diunduh.');
                return;
            }

            const name = document.getElementById('detail_name_qr').innerText;
            const link = document.createElement('a');
            link.href = qrImage.src;
            link.download = `QR-${name.replace(/[^a-zA-Z0-9]/g, '_')}.png`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function openModal(mode, id = null, name = '', qty = 0, catId = '', supId = '', unit = '', desc = '', firstUsed =
            '') {
            const modal = document.getElementById('modalProduct');
            const form = document.getElementById('productForm');
            const method = document.getElementById('formMethod');
            const title = document.getElementById('modalTitle');

            // Ambil elemen label dan input qty untuk manipulasi styling
            const qtyLabel = document.getElementById('qtyLabel');
            const prodQty = document.getElementById('prod_qty');

            modal.classList.remove('hidden');

            if (mode === 'edit') {
                title.innerText = 'Perbarui Data Produk';
                form.action = `/products/${id}`;
                method.value = 'PUT';

                document.getElementById('prod_name').value = name;
                document.getElementById('prod_category').value = catId;
                document.getElementById('prod_supplier').value = supId ? supId : "";
                document.getElementById('prod_unit').value = unit;
                document.getElementById('prod_desc').value = desc;
                document.getElementById('prod_first_used').value = firstUsed;

                // Mode Edit: Styling warna Amber untuk Koreksi Stok
                qtyLabel.innerText = 'Koreksi Stok Manual';
                qtyLabel.className = 'block text-[10px] font-bold text-amber-500 uppercase tracking-widest mb-2 italic';
                prodQty.className =
                    'w-full bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-amber-400 outline-none transition-all';
                prodQty.value = qty;

            } else {
                title.innerText = 'Pendaftaran Produk Baru';
                form.action = "{{ route('products.store') }}";
                method.value = 'POST';
                form.reset();

                // Mode Tambah: Styling Normal untuk Stok Awal Opsional
                qtyLabel.innerText = 'Stok Awal (Opsional)';
                qtyLabel.className = 'block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2';
                prodQty.className =
                    'w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all';
                prodQty.value = ''; // Kosongkan, biarkan user mengisi jika mau
            }
        }

        function closeModal() {
            document.getElementById('modalProduct').classList.add('hidden');
        }

        function openImportModal() {
            document.getElementById('modalImport').classList.remove('hidden');
        }

        function closeImportModal() {
            document.getElementById('modalImport').classList.add('hidden');
        }

        function openSyncModal() {
            document.getElementById('modalSyncData').classList.remove('hidden');
        }

        function closeSyncModal() {
            document.getElementById('modalSyncData').classList.add('hidden');
        }

        window.onclick = function(event) {
            const modalProduct = document.getElementById('modalProduct');
            const modalImport = document.getElementById('modalImport');
            const modalSync = document.getElementById('modalSyncData');
            const modalDetail = document.getElementById('modalDetailProduct');

            if (event.target == modalProduct) closeModal();
            if (event.target == modalImport) closeImportModal();
            if (event.target == modalSync) closeSyncModal();
            if (event.target == modalDetail) closeDetailModal();
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
@endsection
