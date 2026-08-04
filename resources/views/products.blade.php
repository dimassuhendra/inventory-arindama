@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <!-- 1. HEADER & ACTION BUTTONS -->
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
            <div>
                <h2 class="text-xl lg:text-2xl font-serif font-bold text-slate-800">Master Data Produk</h2>
                <p class="text-xs text-slate-500 mt-1">Manajemen inventaris barang, pelacakan stok, dan QR Code</p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <button onclick="openSyncModal()"
                    class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-3.5 py-2.5 rounded-xl border border-slate-300/80 transition-all flex items-center gap-2 font-bold text-xs cursor-pointer">
                    <i class="fa-solid fa-rotate text-inv-teal"></i>
                    <span class="hidden sm:inline">Sinkronisasi</span> Bulk
                </button>

                <button onclick="openImportModal()"
                    class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-3.5 py-2.5 rounded-xl border border-slate-300/80 transition-all flex items-center gap-2 font-bold text-xs cursor-pointer">
                    <i class="fa-solid fa-file-import text-amber-600"></i>
                    <span class="hidden sm:inline">Import</span> Excel
                </button>

                <a href="{{ route('products.export') }}"
                    class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-3.5 py-2.5 rounded-xl border border-slate-300/80 transition-all flex items-center gap-2 font-bold text-xs cursor-pointer">
                    <i class="fa-solid fa-file-excel text-emerald-600"></i>
                    <span class="hidden sm:inline">Export</span> Excel
                </a>

                <button onclick="openModal('add')"
                    class="bg-gradient-to-r from-inv-teal to-inv-primary hover:from-inv-hover hover:to-inv-hover text-white px-4 py-2.5 rounded-xl shadow-md transition-all flex items-center gap-2 font-bold text-xs cursor-pointer">
                    <i class="fa-solid fa-plus"></i> Tambah Produk
                </button>
            </div>
        </div>

        <!-- 2. MINI ANALYTICS BAR (4 CARDS PALET INV) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Jenis Produk -->
            <div
                class="bg-gradient-to-br from-[#00a8b5] to-[#2dd4bf] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-teal-100 uppercase tracking-widest">Jenis Produk</p>
                        <h3 class="text-2xl font-serif font-bold text-white mt-1">{{ number_format($total_products_count) }}
                        </h3>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                </div>
                <i
                    class="fa-solid fa-box-open absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
            </div>

            <!-- Akumulasi Unit Stok -->
            <div
                class="bg-gradient-to-br from-[#0c66c8] to-[#2563eb] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-blue-100 uppercase tracking-widest">Total Fisik Stok</p>
                        <h3 class="text-2xl font-serif font-bold text-white mt-1">{{ number_format($total_quantity_sum) }}
                            <span class="text-xs font-normal">Unit</span></h3>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg">
                        <i class="fa-solid fa-cubes"></i>
                    </div>
                </div>
                <i
                    class="fa-solid fa-warehouse absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
            </div>

            <!-- Stok Menipis (Alert) -->
            <div
                class="bg-gradient-to-br from-red-500 to-rose-600 p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-red-100 uppercase tracking-widest">Stok Menipis (<=5)< /p>
                                <h3 class="text-2xl font-serif font-bold text-white mt-1">
                                    {{ number_format($low_stock_count) }} <span class="text-xs font-normal">Item</span></h3>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                </div>
                <i
                    class="fa-solid fa-fire-flame-curved absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
            </div>

            <!-- Terpakai / Digunakan -->
            <div
                class="bg-gradient-to-br from-[#081d34] via-[#0d2a4a] to-[#00a8b5] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-teal-200 uppercase tracking-widest">Aktif Digunakan</p>
                        <h3 class="text-2xl font-serif font-bold text-inv-mint mt-1">{{ number_format($active_used_count) }}
                            <span class="text-xs font-normal text-slate-300">Item</span></h3>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur-md text-inv-mint flex items-center justify-center text-lg">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
                <i
                    class="fa-solid fa-hand-holding-box absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
            </div>
        </div>

        <!-- 3. FILTER & SEARCH BAR -->
        <div class="bg-slate-200/60 backdrop-blur-md p-4 rounded-2xl border border-slate-300/80">
            <form method="GET" action="{{ route('products.index') }}"
                class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                <input type="hidden" name="sort_by" value="{{ request('sort_by', 'created_at') }}">
                <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">

                <!-- Search Input -->
                <div class="relative md:col-span-5">
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari produk, slug, kategori, supplier..."
                        class="w-full bg-slate-100 border border-slate-300 rounded-xl pl-9 pr-8 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-inv-teal transition-colors">
                    @if (request('search'))
                        <a href="{{ route('products.index') }}"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </a>
                    @endif
                </div>

                <!-- Select Filter Kategori -->
                <div class="md:col-span-3">
                    <select name="category_id" onchange="this.form.submit()"
                        class="w-full bg-slate-100 border border-slate-300 text-slate-700 text-xs rounded-xl p-2 outline-none focus:border-inv-teal">
                        <option value="">-- Semua Kategori --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Select Filter Status Stok -->
                <div class="md:col-span-2">
                    <select name="stock_status" onchange="this.form.submit()"
                        class="w-full bg-slate-100 border border-slate-300 text-slate-700 text-xs rounded-xl p-2 outline-none focus:border-inv-teal">
                        <option value="">-- Status Stok --</option>
                        <option value="safe" {{ request('stock_status') == 'safe' ? 'selected' : '' }}>Stok Aman (>5)
                        </option>
                        <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Stok Menipis (<=5)<
                                /option>
                        <option value="used" {{ request('stock_status') == 'used' ? 'selected' : '' }}>Sudah Digunakan
                        </option>
                        <option value="stored" {{ request('stock_status') == 'stored' ? 'selected' : '' }}>Tersimpan Gudang
                        </option>
                    </select>
                </div>

                <!-- Select Per Page -->
                <div class="md:col-span-2 flex items-center justify-end gap-2">
                    <span class="text-[11px] text-slate-500 font-medium">Baris:</span>
                    <select name="per_page" onchange="this.form.submit()"
                        class="bg-slate-100 border border-slate-300 text-slate-700 text-xs rounded-xl p-2 outline-none focus:border-inv-teal">
                        <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                        <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- 4. TABEL DATA PRODUK -->
        <div class="bg-slate-200/60 backdrop-blur-md rounded-2xl border border-slate-300/80 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-max">
                    <thead class="bg-slate-300/60 border-b border-slate-300">
                        <tr>
                            @php
                                function sortLinkProd($column, $label)
                                {
                                    $currentCol = request('sort_by', 'created_at');
                                    $currentOrd = request('sort_order', 'desc');
                                    $newOrd = $currentCol == $column && $currentOrd == 'asc' ? 'desc' : 'asc';

                                    $icon = 'fa-sort text-slate-400';
                                    if ($currentCol == $column) {
                                        $icon =
                                            $currentOrd == 'asc'
                                                ? 'fa-sort-up text-inv-teal'
                                                : 'fa-sort-down text-inv-teal';
                                    }
                                    $url = request()->fullUrlWithQuery(['sort_by' => $column, 'sort_order' => $newOrd]);
                                    return "<a href='{$url}' class='flex items-center gap-1 hover:text-inv-teal transition'>{$label} <i class='fa-solid {$icon}'></i></a>";
                                }
                            @endphp

                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">
                                {!! sortLinkProd('name', 'Info Produk') !!}
                            </th>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Kategori
                                & Supplier</th>
                            <th
                                class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest text-center">
                                {!! sortLinkProd('quantity', 'Stok & Unit') !!}
                            </th>
                            <th
                                class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest text-center">
                                Mulai Digunakan</th>
                            <th
                                class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest text-center">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-300/60">
                        @forelse($products as $product)
                            @php
                                $canManageCategory = \App\Services\CategoryService::canUserManage($product->category);
                            @endphp
                            <tr class="hover:bg-slate-300/40 transition-colors">
                                <!-- Info Produk -->
                                <td class="px-5 py-3.5 flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-slate-300/80 border border-slate-300 overflow-hidden flex-shrink-0">
                                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://ui-avatars.com/api/?name=' . urlencode($product->name) . '&background=00a8b5&color=ffffff&bold=true' }}"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-800 line-clamp-1">{{ $product->name }}</p>
                                        <p class="text-[10px] text-slate-400 font-mono leading-tight mt-0.5">
                                            {{ $product->slug }}</p>
                                    </div>
                                </td>

                                <!-- Kategori & Supplier -->
                                <td class="px-5 py-3.5">
                                    <div class="flex flex-col gap-1">
                                        <span
                                            class="text-[9px] font-bold text-inv-primary bg-inv-primary/10 border border-inv-primary/20 px-2 py-0.5 rounded-md w-fit uppercase">
                                            {{ $product->category->name }}
                                        </span>
                                        <span class="text-[10px] text-slate-500 flex items-center gap-1 font-medium">
                                            <i class="fa-solid fa-truck-fast text-slate-400"></i>
                                            {{ $product->supplier->name ?? 'Supplier Belum Tersedia' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Stok & Unit -->
                                <td class="px-5 py-3.5 text-center">
                                    @if ($product->quantity <= 0)
                                        <span
                                            class="text-xs font-bold text-rose-600 bg-rose-500/10 px-2 py-0.5 rounded-md border border-rose-500/20">Habis</span>
                                    @elseif($product->quantity <= 5)
                                        <span
                                            class="text-xs font-bold text-rose-600 animate-pulse bg-rose-500/10 px-2 py-0.5 rounded-md border border-rose-500/20">
                                            {{ number_format($product->quantity, 0) }}
                                        </span>
                                    @else
                                        <span class="text-xs font-bold text-slate-800">
                                            {{ number_format($product->quantity, 0) }}
                                        </span>
                                    @endif
                                    <p class="text-[9px] text-slate-400 uppercase font-semibold mt-0.5">
                                        {{ $product->unit }}</p>
                                </td>

                                <!-- Mulai Digunakan -->
                                <td class="px-5 py-3.5 text-center">
                                    @if ($product->first_used_at)
                                        <span
                                            class="text-[10px] font-bold text-emerald-700 bg-emerald-500/10 border border-emerald-500/30 px-2 py-0.5 rounded-md inline-flex items-center gap-1">
                                            <i class="fa-solid fa-circle-check"></i>
                                            {{ \Carbon\Carbon::parse($product->first_used_at)->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span
                                            class="text-[10px] font-bold text-slate-500 bg-slate-300/60 border border-slate-300 px-2 py-0.5 rounded-md inline-flex items-center gap-1">
                                            <i class="fa-solid fa-box-archive text-slate-400"></i> Tersimpan
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="px-5 py-3.5">
                                    <div class="flex justify-center items-center gap-1.5">
                                        <!-- Tombol Detail / QR -->
                                        <button
                                            onclick="openDetailModal(
                                        '{{ addslashes($product->name) }}', 
                                        '{{ $product->slug }}', 
                                        '{{ addslashes($product->category->name) }}', 
                                        '{{ addslashes($product->supplier->name ?? 'Supplier Belum Tersedia') }}', 
                                        '{{ number_format($product->quantity, 0) }} {{ $product->unit }}', 
                                        '{{ addslashes($product->description) }}', 
                                        '{{ $product->image ? asset('storage/' . $product->image) : '' }}', 
                                        '{{ $product->first_used_at ? \Carbon\Carbon::parse($product->first_used_at)->format('d M Y') : 'Belum Digunakan' }}',
                                        '{{ $product->first_used_at ? \Carbon\Carbon::parse($product->first_used_at)->format('Y-m-d') : '' }}'
                                    )"
                                            class="p-1.5 bg-inv-teal text-white hover:bg-inv-hover rounded-lg transition text-xs cursor-pointer"
                                            title="Lihat QR Code">
                                            <i class="fa-solid fa-qrcode"></i>
                                        </button>

                                        @if ($canManageCategory)
                                            <!-- Tombol Edit -->
                                            <button
                                                onclick="openModal('edit', {{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->quantity }}, {{ $product->category_id }}, '{{ $product->supplier_id ?? '' }}', '{{ $product->unit }}', '{{ addslashes($product->description) }}', '{{ $product->first_used_at ? \Carbon\Carbon::parse($product->first_used_at)->format('Y-m-d') : '' }}')"
                                                class="p-1.5 text-amber-600 hover:bg-slate-300 rounded-lg transition text-xs cursor-pointer"
                                                title="Edit Produk">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>

                                            <!-- Tombol Hapus -->
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus produk ini secara permanen?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="p-1.5 text-rose-600 hover:bg-slate-300 rounded-lg transition text-xs cursor-pointer"
                                                    title="Hapus Produk">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-[10px] text-slate-400 italic"
                                                title="Kategori ini terkunci untuk role Anda">
                                                <i class="fa-solid fa-lock"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-boxes-stacked text-3xl mb-2 opacity-30"></i>
                                    <p class="text-xs italic">Data produk tidak ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (request('per_page') != 'all')
                <div class="px-5 py-3 bg-slate-300/40 border-t border-slate-300">
                    {{ $products->links() }}
                </div>
            @endif
        </div>

        <!-- 5. MODAL DETAIL PRODUK & PRINT STICKER LABEL QR CODE -->
        <div id="modalDetailProduct"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
            <div
                class="bg-slate-100 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-300 transform transition-all">

                <div
                    class="bg-gradient-to-r from-inv-teal to-inv-primary p-5 text-white flex justify-between items-center">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-qrcode text-lg"></i>
                        <h3 class="font-serif font-bold text-base">Detail & Label QR Code</h3>
                    </div>
                    <button onclick="closeDetailModal()" class="text-white/70 hover:text-white transition cursor-pointer">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto custom-scrollbar">

                    <!-- STICKER LABEL STYLED CONTAINER FOR PRINTING -->
                    <div id="qrPrintArea"
                        class="bg-white border-2 border-dashed border-slate-300 rounded-2xl p-4 text-center flex flex-col items-center shadow-sm">
                        <span class="text-[9px] font-bold text-inv-teal uppercase tracking-widest mb-2">Mybolo Inventory
                            Tag</span>
                        <div id="qrcode" class="p-2 bg-white rounded-xl border border-slate-200 mb-2"></div>
                        <p id="detail_name_qr" class="font-serif font-bold text-slate-800 text-sm leading-tight"></p>
                        <p id="detail_slug_qr" class="text-[10px] text-slate-400 font-mono mt-0.5"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <button onclick="downloadQR()"
                            class="w-full bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-2.5 rounded-xl text-xs transition cursor-pointer flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-download"></i> Download PNG
                        </button>
                        <button onclick="printStickerLabel()"
                            class="w-full bg-inv-teal hover:bg-inv-hover text-white font-bold py-2.5 rounded-xl text-xs transition cursor-pointer flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-print"></i> Cetak Label
                        </button>
                    </div>

                    <!-- Specs Grid -->
                    <div class="space-y-2 pt-2 border-t border-slate-200">
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="bg-white p-2.5 rounded-xl border border-slate-200">
                                <span class="text-[9px] text-slate-400 font-bold block uppercase">Kategori</span>
                                <span id="detail_category" class="font-semibold text-slate-700"></span>
                            </div>
                            <div class="bg-white p-2.5 rounded-xl border border-slate-200">
                                <span class="text-[9px] text-slate-400 font-bold block uppercase">Stok Tersedia</span>
                                <span id="detail_stock" class="font-semibold text-emerald-600"></span>
                            </div>
                        </div>

                        <div class="bg-white p-2.5 rounded-xl border border-slate-200 text-xs">
                            <span class="text-[9px] text-slate-400 font-bold block uppercase">Supplier Utama</span>
                            <span id="detail_supplier" class="font-semibold text-slate-700"></span>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="bg-white p-2.5 rounded-xl border border-slate-200">
                                <span class="text-[9px] text-slate-400 font-bold block uppercase">Mulai Digunakan</span>
                                <span id="detail_first_used" class="font-semibold text-inv-primary"></span>
                            </div>
                            <div class="bg-white p-2.5 rounded-xl border border-slate-200">
                                <span class="text-[9px] text-slate-400 font-bold block uppercase">Masa Pakai</span>
                                <span id="detail_usage_age" class="font-semibold text-emerald-600"></span>
                            </div>
                        </div>

                        <div class="bg-white p-2.5 rounded-xl border border-slate-200 text-xs">
                            <span class="text-[9px] text-slate-400 font-bold block uppercase mb-0.5">Deskripsi</span>
                            <p id="detail_desc" class="text-slate-600 leading-relaxed"></p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- 6. MODAL FORM ADD/EDIT PRODUK -->
        <div id="modalProduct"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
            <div
                class="bg-slate-100 rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden border border-slate-300 transform transition-all">

                <div
                    class="bg-gradient-to-r from-inv-teal to-inv-primary p-5 text-white flex justify-between items-center">
                    <h3 id="modalTitle" class="font-serif font-bold text-base">Pendaftaran Produk</h3>
                    <button onclick="closeModal()" class="text-white/70 hover:text-white transition cursor-pointer">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form id="productForm" method="POST" enctype="multipart/form-data"
                    class="p-6 space-y-4 max-h-[75vh] overflow-y-auto custom-scrollbar">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                Kategori <span class="text-rose-500">*</span>
                            </label>
                            <select name="category_id" id="prod_category" required
                                class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-inv-teal">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                Supplier Utama
                            </label>
                            <select name="supplier_id" id="prod_supplier"
                                class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-inv-teal">
                                <option value="">-- Supplier Belum Tersedia --</option>
                                @foreach ($suppliers as $sup)
                                    <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                            Nama Lengkap Produk <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="prod_name" required
                            placeholder="Misal: Router Mikrotik RB3011"
                            class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-inv-teal">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                Satuan (Unit) <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="unit" id="prod_unit" required placeholder="Pcs / Box / Unit"
                                class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-inv-teal">
                        </div>
                        <div>
                            <label id="qtyLabel"
                                class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                Stok Awal (Opsional)
                            </label>
                            <input type="number" name="quantity" id="prod_qty" step="0.01" placeholder="0"
                                class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-inv-teal">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                            Deskripsi Produk <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="description" id="prod_desc" required rows="2" placeholder="Spesifikasi ringkas..."
                            class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-inv-teal"></textarea>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                            Mulai Digunakan (Opsional)
                        </label>
                        <input type="date" name="first_used_at" id="prod_first_used"
                            class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-inv-teal">
                    </div>

                    <div class="p-3 border-2 border-dashed border-slate-300 rounded-xl bg-white">
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">
                            Foto Produk (Opsional)
                        </label>
                        <input type="file" name="image"
                            class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-inv-teal file:text-white cursor-pointer">
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-inv-teal to-inv-primary hover:from-inv-hover hover:to-inv-hover text-white font-bold py-3 rounded-xl shadow-md text-xs tracking-wider uppercase cursor-pointer">
                        Simpan Data Produk
                    </button>
                </form>
            </div>
        </div>

        <!-- 7. MODAL IMPORT EXCEL -->
        <div id="modalImport"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
            <div
                class="bg-slate-100 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-300 transform transition-all">
                <div class="bg-amber-500 p-5 text-white flex justify-between items-center">
                    <h3 class="font-serif font-bold text-base">Import Data Produk Excel</h3>
                    <button onclick="closeImportModal()" class="text-white/70 hover:text-white transition cursor-pointer">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data"
                    class="p-6 space-y-4">
                    @csrf
                    <div class="bg-amber-500/10 border border-amber-500/30 rounded-xl p-3 text-center">
                        <p class="text-xs text-amber-800 mb-2 font-medium">Gunakan template resmi untuk format yang sesuai.
                        </p>
                        <a href="{{ route('products.template') }}"
                            class="inline-flex items-center gap-1.5 bg-white text-amber-700 border border-amber-300 px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm">
                            <i class="fa-solid fa-download"></i> Unduh Template Excel
                        </a>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">File
                            Excel (*.xlsx)</label>
                        <input type="file" name="file_excel" required accept=".xlsx, .xls, .csv"
                            class="block w-full text-xs text-slate-500 border border-slate-300 rounded-xl bg-white p-2">
                    </div>
                    <button type="submit"
                        class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 rounded-xl text-xs uppercase tracking-wider cursor-pointer">
                        Proses Import
                    </button>
                </form>
            </div>
        </div>

        <!-- 8. MODAL BULK EDIT SINKRONISASI -->
        <div id="modalSyncData"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
            <div
                class="bg-slate-100 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-300 transform transition-all">
                <div class="bg-inv-teal p-5 text-white flex justify-between items-center">
                    <h3 class="font-serif font-bold text-base">Sinkronisasi Bulk Edit</h3>
                    <button onclick="closeSyncModal()" class="text-white/70 hover:text-white transition cursor-pointer">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                <form action="{{ route('products.import-edit') }}" method="POST" enctype="multipart/form-data"
                    class="p-6 space-y-4">
                    @csrf
                    <div class="bg-inv-teal/10 border border-inv-teal/30 rounded-xl p-3 text-center">
                        <p class="text-xs text-slate-700 mb-2 font-medium">1. Unduh dokumen master produk saat ini.</p>
                        <a href="{{ route('products.export-edit') }}"
                            class="inline-flex items-center gap-1.5 bg-white text-inv-teal border border-inv-teal/30 px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm">
                            <i class="fa-solid fa-download"></i> Unduh Dokumen Master
                        </a>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">2. Unggah
                            Hasil Penyesuaian</label>
                        <input type="file" name="file_excel_edit" required accept=".xlsx, .xls, .csv"
                            class="block w-full text-xs text-slate-500 border border-slate-300 rounded-xl bg-white p-2">
                    </div>
                    <button type="submit"
                        class="w-full bg-inv-teal hover:bg-inv-hover text-white font-bold py-3 rounded-xl text-xs uppercase tracking-wider cursor-pointer">
                        Jalankan Sinkronisasi
                    </button>
                </form>
            </div>
        </div>

    </div>

    <!-- SCRIPTS & QR CODE LOGIC -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2500,
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
                html: "{!! $errorMsg !!}",
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

            // Logika Hitung Umur Penggunaan
            const usageAgeElem = document.getElementById('detail_usage_age');

            if (rawFirstUsed) {
                const startDate = new Date(rawFirstUsed);
                const today = new Date();
                startDate.setHours(0, 0, 0, 0);
                today.setHours(0, 0, 0, 0);

                let years = today.getFullYear() - startDate.getFullYear();
                let months = today.getMonth() - startDate.getMonth();
                let days = today.getDate() - startDate.getDate();

                if (days < 0) {
                    months--;
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
                usageAgeElem.className = "font-semibold text-slate-400";
            }

            // Generate QR Code
            const publicUrl = `${window.location.origin}/p/${slug}`;
            const qrContainer = document.getElementById('qrcode');
            qrContainer.innerHTML = '';

            qrcodeObj = new QRCode(qrContainer, {
                text: publicUrl,
                width: 130,
                height: 130,
                colorDark: "#081d34",
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

        function printStickerLabel() {
            const printContents = document.getElementById('qrPrintArea').innerHTML;
            const originalContents = document.body.innerHTML;

            document.body.innerHTML = `
            <div style="display:flex; justify-content:center; align-items:center; height:100vh;">
                <div style="border:2px solid #000; padding:15px; border-radius:10px; text-align:center; width:220px;">
                    ${printContents}
                </div>
            </div>
        `;
            window.print();
            document.body.innerHTML = originalContents;
            window.location.reload();
        }

        function openModal(mode, id = null, name = '', qty = 0, catId = '', supId = '', unit = '', desc = '', firstUsed =
            '') {
            const modal = document.getElementById('modalProduct');
            const form = document.getElementById('productForm');
            const method = document.getElementById('formMethod');
            const title = document.getElementById('modalTitle');

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
                document.getElementById('prod_qty').value = qty;
            } else {
                title.innerText = 'Pendaftaran Produk Baru';
                form.action = "{{ route('products.store') }}";
                method.value = 'POST';
                form.reset();
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
    </script>
@endsection
