@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <!-- 1. HEADER & ACTION BUTTONS -->
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
            <div>
                <h2 class="text-xl lg:text-2xl font-serif font-bold text-slate-800">Master Data Aset & Inventaris</h2>
                <p class="text-xs text-slate-500 mt-1">Sistem Manajemen Aset Group Company, Pelacakan Depresiasi, & QR Code
                </p>
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
                    <i class="fa-solid fa-plus"></i> Tambah Aset
                </button>
            </div>
        </div>

        <!-- 2. MINI ANALYTICS BAR (DINAMIS SAMA ROLE) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            @if (auth()->user()->hasRole('HRGA') || auth()->user()->hasRole('Super Admin'))
                <!-- ==================== TAMPILAN CARD KHUSUS HRGA / SUPER ADMIN ==================== -->

                <!-- Total Jenis Aset Group -->
                <div
                    class="bg-gradient-to-br from-[#00a8b5] to-[#2dd4bf] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <p class="text-[10px] font-bold text-teal-100 uppercase tracking-widest">Total Jenis Aset</p>
                            <h3 class="text-2xl font-serif font-bold text-white mt-1">
                                {{ number_format($total_products_count ?? 0) }}</h3>
                        </div>
                        <div
                            class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg">
                            <i class="fa-solid fa-boxes-stacked"></i>
                        </div>
                    </div>
                    <i
                        class="fa-solid fa-box-open absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
                </div>

                <!-- Nilai Perolehan (Finansial) -->
                <div
                    class="bg-gradient-to-br from-[#0c66c8] to-[#2563eb] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <p class="text-[10px] font-bold text-blue-100 uppercase tracking-widest">Est. Nilai Perolehan
                            </p>
                            <h3 class="text-base font-serif font-bold text-white mt-1">Rp
                                {{ number_format($total_asset_value ?? 0, 0, ',', '.') }}</h3>
                        </div>
                        <div
                            class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg">
                            <i class="fa-solid fa-sack-dollar"></i>
                        </div>
                    </div>
                    <i
                        class="fa-solid fa-vault absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
                </div>

                <!-- Nilai Buku (Depresiasi) -->
                <div
                    class="bg-gradient-to-br from-purple-600 to-indigo-600 p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <p class="text-[10px] font-bold text-purple-100 uppercase tracking-widest">Est. Nilai Buku Saat
                                Ini</p>
                            <h3 class="text-base font-serif font-bold text-white mt-1">Rp
                                {{ number_format($total_book_value ?? 0, 0, ',', '.') }}</h3>
                        </div>
                        <div
                            class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg">
                            <i class="fa-solid fa-chart-line-down"></i>
                        </div>
                    </div>
                    <i
                        class="fa-solid fa-calculator absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
                </div>

                <!-- Aset Afkir / Non-Aktif -->
                <div
                    class="bg-gradient-to-br from-rose-500 to-red-600 p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <p class="text-[10px] font-bold text-rose-100 uppercase tracking-widest">Aset Afkir / Non-Aktif
                            </p>
                            <h3 class="text-2xl font-serif font-bold text-white mt-1">
                                {{ number_format($disposed_count ?? 0) }} <span class="text-xs font-normal">Unit</span></h3>
                        </div>
                        <div
                            class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg">
                            <i class="fa-solid fa-trash-can"></i>
                        </div>
                    </div>
                    <i
                        class="fa-solid fa-ban absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
                </div>
            @else
                <!-- ==================== TAMPILAN CARD KHUSUS GUDANG / STAFF ==================== -->

                <!-- Total Stok Fisik Gudang -->
                <div
                    class="bg-gradient-to-br from-[#00a8b5] to-[#2dd4bf] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <p class="text-[10px] font-bold text-teal-100 uppercase tracking-widest">Total Fisik Stok</p>
                            <h3 class="text-2xl font-serif font-bold text-white mt-1">
                                {{ number_format($total_physical_stock ?? 0) }} <span
                                    class="text-xs font-normal">Unit</span></h3>
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
                    class="bg-gradient-to-br from-rose-500 to-red-600 p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <p class="text-[10px] font-bold text-rose-100 uppercase tracking-widest">Stok Menipis (<=5)< /p>
                                    <h3 class="text-2xl font-serif font-bold text-white mt-1">
                                        {{ number_format($low_stock_count ?? 0) }} <span
                                            class="text-xs font-normal">Item</span></h3>
                        </div>
                        <div
                            class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                    </div>
                    <i
                        class="fa-solid fa-fire-flame-curved absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
                </div>

                <!-- Tersimpan di Gudang -->
                <div
                    class="bg-gradient-to-br from-slate-600 to-slate-800 p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <p class="text-[10px] font-bold text-slate-200 uppercase tracking-widest">Tersimpan Gudang</p>
                            <h3 class="text-2xl font-serif font-bold text-white mt-1">
                                {{ number_format($stored_in_warehouse ?? 0) }} <span class="text-xs font-normal">Unit</span>
                            </h3>
                        </div>
                        <div
                            class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg">
                            <i class="fa-solid fa-box-archive"></i>
                        </div>
                    </div>
                    <i
                        class="fa-solid fa-boxes-packing absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
                </div>

                <!-- Perlu Perawatan -->
                <div
                    class="bg-gradient-to-br from-amber-500 to-orange-600 p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <p class="text-[10px] font-bold text-amber-100 uppercase tracking-widest">Dalam Perawatan</p>
                            <h3 class="text-2xl font-serif font-bold text-white mt-1">
                                {{ number_format($under_maintenance ?? 0) }} <span class="text-xs font-normal">Unit</span>
                            </h3>
                        </div>
                        <div
                            class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                        </div>
                    </div>
                    <i
                        class="fa-solid fa-gears absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
                </div>
            @endif

        </div>

        <!-- 3. FILTER & SEARCH BAR -->
        <div class="bg-slate-200/60 backdrop-blur-md p-4 rounded-2xl border border-slate-300/80">
            <form method="GET" action="{{ route('products.index') }}"
                class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                <input type="hidden" name="sort_by" value="{{ request('sort_by', 'created_at') }}">
                <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">

                <!-- Search Input -->
                <div class="relative md:col-span-4">
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari Aset, SN, Merek, PIC, Lokasi..."
                        class="w-full bg-slate-100 border border-slate-300 rounded-xl pl-9 pr-8 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-inv-teal transition-colors">
                    @if (request('search'))
                        <a href="{{ route('products.index') }}"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </a>
                    @endif
                </div>

                <!-- Select Filter Perusahaan -->
                <div class="md:col-span-3">
                    <select name="company_name" onchange="this.form.submit()"
                        class="w-full bg-slate-100 border border-slate-300 text-slate-700 text-xs rounded-xl p-2 outline-none focus:border-inv-teal">
                        <option value="">-- Semua Perusahaan --</option>
                        @foreach (['Perusahaan A', 'Perusahaan B', 'Perusahaan C', 'Perusahaan D', 'Perusahaan E', 'General'] as $comp)
                            <option value="{{ $comp }}" {{ request('company_name') == $comp ? 'selected' : '' }}>
                                {{ $comp }}</option>
                        @endforeach
                    </select>
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

        <!-- 4. TABEL DATA PRODUK/ASET -->
        <div class="bg-slate-200/60 backdrop-blur-md rounded-2xl border border-slate-300/80 overflow-hidden shadow-sm">
            <!-- TABEL DATA ASET KESELURUHAN -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-max">
                    <thead class="bg-slate-300/60 border-b border-slate-300">
                        <tr>
                            <th class="px-4 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">
                                Informasi Aset & SN</th>
                            <th class="px-4 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">
                                Perusahaan & Klasifikasi</th>
                            <th class="px-4 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">
                                Penempatan & PIC</th>
                            <th
                                class="px-4 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest text-center">
                                Status & Perawatan</th>
                            <th
                                class="px-4 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest text-right">
                                Finansial & Stok</th>
                            <th
                                class="px-4 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest text-center">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-300/60 text-xs">
                        @forelse($products as $product)
                            @php
                                $canManageCategory = \App\Services\CategoryService::canUserManage($product->category);
                            @endphp
                            <tr class="hover:bg-slate-300/40 transition-colors">

                                <!-- 1. INFO ASET & SN -->
                                <td class="px-4 py-3 flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-slate-300/80 border border-slate-300 overflow-hidden flex-shrink-0">
                                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://ui-avatars.com/api/?name=' . urlencode($product->name) . '&background=00a8b5&color=ffffff&bold=true' }}"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 line-clamp-1">{{ $product->name }}</p>
                                        <p class="text-[10px] text-slate-500">
                                            <span
                                                class="text-slate-700 font-semibold">{{ $product->brand_model ?? 'No Brand' }}</span>
                                            |
                                            SN: <span
                                                class="font-mono text-inv-primary font-bold">{{ $product->serial_number ?? '-' }}</span>
                                        </p>
                                    </div>
                                </td>

                                <!-- 2. PERUSAHAAN & KLASIFIKASI -->
                                <td class="px-4 py-3">
                                    <div class="flex flex-col gap-0.5">
                                        <span
                                            class="text-[9px] font-bold text-slate-700 bg-slate-300 px-2 py-0.5 rounded-md w-fit uppercase">
                                            {{ $product->company_name }}
                                        </span>
                                        <p class="text-[11px] text-slate-700 font-medium mt-0.5">
                                            {{ $product->category->name }}
                                            @if ($product->subCategory)
                                                <i class="fa-solid fa-angle-right text-[8px] text-slate-400 mx-0.5"></i>
                                                <span class="text-slate-500">{{ $product->subCategory->name }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </td>

                                <!-- 3. PENEMPATAN & PIC -->
                                <td class="px-4 py-3">
                                    <div class="flex flex-col gap-0.5">
                                        <p class="text-slate-800 font-bold flex items-center gap-1">
                                            <i class="fa-solid fa-user-tie text-[10px] text-inv-teal"></i>
                                            {{ $product->pic->name ?? 'Belum ada PIC' }}
                                        </p>
                                        <p class="text-[10px] text-slate-500">
                                            {{ $product->department->name ?? 'Dept -' }} • <span
                                                class="italic">{{ $product->location ?? 'Lokasi -' }}</span>
                                        </p>
                                    </div>
                                </td>

                                <!-- 4. STATUS & PERAWATAN -->
                                <td class="px-4 py-3 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <span
                                            class="text-[9px] font-bold px-2 py-0.5 rounded-md border
                                {{ $product->asset_status === 'Aktif Digunakan' ? 'bg-emerald-500/10 text-emerald-700 border-emerald-500/30' : '' }}
                                {{ $product->asset_status === 'Tersimpan Gudang' ? 'bg-slate-300/60 text-slate-600 border-slate-300' : '' }}
                                {{ $product->asset_status === 'Dalam Perawatan' ? 'bg-amber-500/10 text-amber-700 border-amber-500/30' : '' }}
                                {{ $product->asset_status === 'Dihentikan/Afkir' ? 'bg-rose-500/10 text-rose-700 border-rose-500/30' : '' }}">
                                            {{ $product->asset_status }}
                                        </span>
                                        <span class="text-[9px] text-slate-500 font-medium">
                                            Kondisi: <strong class="text-slate-700">{{ $product->condition }}</strong>
                                        </span>
                                    </div>
                                </td>

                                <!-- 5. FINANSIAL & STOK -->
                                <td class="px-4 py-3 text-right">
                                    <p class="font-bold text-slate-800">
                                        Rp {{ number_format($product->purchase_cost, 0, ',', '.') }}
                                    </p>
                                    <p class="text-[10px] text-emerald-700 font-mono font-semibold">
                                        Buku: Rp {{ number_format($product->current_book_value, 0, ',', '.') }}
                                    </p>
                                    <p class="text-[9px] text-slate-400 font-medium mt-0.5">
                                        Stok: <strong class="text-slate-700">{{ number_format($product->quantity) }}
                                            {{ $product->unit }}</strong>
                                    </p>
                                </td>

                                <!-- 6. AKSI -->
                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center items-center gap-1.5">
                                        <!-- Detail Button -->
                                        <button onclick='openDetailModal(@json($product))'
                                            class="p-1.5 bg-inv-teal text-white hover:bg-inv-hover rounded-lg transition text-xs cursor-pointer"
                                            title="Lihat Detail Lengkap & QR Code">
                                            <i class="fa-solid fa-qrcode"></i>
                                        </button>

                                        @if ($canManageCategory)
                                            <!-- Edit Button -->
                                            <button onclick='openModal("edit", @json($product))'
                                                class="p-1.5 text-amber-600 hover:bg-slate-300 rounded-lg transition text-xs cursor-pointer"
                                                title="Edit Data Aset">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>

                                            <!-- Delete Button -->
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus aset ini secara permanen?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="p-1.5 text-rose-600 hover:bg-slate-300 rounded-lg transition text-xs cursor-pointer"
                                                    title="Hapus Aset">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-[10px] text-slate-400 italic"
                                                title="Kategori terkunci untuk role Anda">
                                                <i class="fa-solid fa-lock"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-boxes-stacked text-3xl mb-2 opacity-30"></i>
                                    <p class="text-xs italic">Data aset tidak ditemukan.</p>
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

        <!-- 5. MODAL FORM ADD/EDIT ASET -->
        <div id="modalProduct"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
            <div
                class="bg-slate-100 rounded-3xl shadow-2xl w-full max-w-3xl overflow-hidden border border-slate-300 transform transition-all">
                <div
                    class="bg-gradient-to-r from-inv-teal to-inv-primary p-5 text-white flex justify-between items-center">
                    <h3 id="modalTitle" class="font-serif font-bold text-base">Pendaftaran Aset Baru</h3>
                    <button onclick="closeModal()" class="text-white/70 hover:text-white transition cursor-pointer">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form id="productForm" method="POST" enctype="multipart/form-data"
                    class="p-6 space-y-4 max-h-[75vh] overflow-y-auto custom-scrollbar">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <!-- SECTION 1: KLASIFIKASI & IDENTIFIKASI -->
                    <div class="p-3.5 bg-slate-200/50 rounded-2xl border border-slate-300/80 space-y-3">
                        <p class="text-[11px] font-bold text-inv-teal uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fa-solid fa-building"></i> Identifikasi & Klasifikasi Aset
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Perusahaan <span
                                        class="text-rose-500">*</span></label>
                                <select name="company_name" id="prod_company" required
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-800 outline-none focus:border-inv-teal">
                                    @foreach (['Perusahaan A', 'Perusahaan B', 'Perusahaan C', 'Perusahaan D', 'Perusahaan E', 'General'] as $comp)
                                        <option value="{{ $comp }}">{{ $comp }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Kategori Utama
                                    <span class="text-rose-500">*</span></label>
                                <select name="category_id" id="prod_category" onchange="filterSubCategories()" required
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-800 outline-none focus:border-inv-teal">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Subkategori</label>
                                <select name="sub_category_id" id="prod_subcategory"
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-800 outline-none focus:border-inv-teal">
                                    <option value="">-- Semua Subkategori --</option>
                                    @foreach ($subCategories as $sub)
                                        <option value="{{ $sub->id }}" data-category="{{ $sub->category_id }}">
                                            {{ $sub->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Nama Aset <span
                                        class="text-rose-500">*</span></label>
                                <input type="text" name="name" id="prod_name" required
                                    placeholder="Misal: Laptop ThinkPad T14"
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Merek /
                                    Model</label>
                                <input type="text" name="brand_model" id="prod_brand_model"
                                    placeholder="Misal: Lenovo / Gen 2"
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Nomor Seri
                                    (SN)</label>
                                <input type="text" name="serial_number" id="prod_serial_number"
                                    placeholder="SN123456789"
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: PENGADAAN & FINANSIAL -->
                    <div class="p-3.5 bg-slate-200/50 rounded-2xl border border-slate-300/80 space-y-3">
                        <p class="text-[11px] font-bold text-inv-teal uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fa-solid fa-coins"></i> Pengadaan & Finansial
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Vendor /
                                    Pemasok</label>
                                <select name="supplier_id" id="prod_supplier"
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                                    <option value="">-- Pilih Vendor --</option>
                                    @foreach ($suppliers as $sup)
                                        <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">No. PO /
                                    Invoice</label>
                                <input type="text" name="po_invoice_number" id="prod_po_invoice"
                                    placeholder="INV/2026/001"
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Tanggal
                                    Pembelian</label>
                                <input type="date" name="purchase_date" id="prod_purchase_date"
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Biaya Perolehan
                                    (Rp)</label>
                                <input type="number" step="0.01" name="purchase_cost" id="prod_purchase_cost"
                                    placeholder="0"
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Nilai Residu
                                    (Rp)</label>
                                <input type="number" step="0.01" name="residual_value" id="prod_residual_value"
                                    placeholder="0"
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Umur Manfaat
                                    (Tahun)</label>
                                <input type="number" name="useful_life_years" id="prod_useful_life" placeholder="4"
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 3: PENEMPATAN, MAINTENANCE & STATUS -->
                    <div class="p-3.5 bg-slate-200/50 rounded-2xl border border-slate-300/80 space-y-3">
                        <p class="text-[11px] font-bold text-inv-teal uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fa-solid fa-compass"></i> Penempatan, Status & Perawatan
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Departemen</label>
                                <select name="department_id" id="prod_department"
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                                    <option value="">-- Pilih Departemen --</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Lokasi
                                    Ruangan</label>
                                <input type="text" name="location" id="prod_location"
                                    placeholder="Ruang Server Lt. 2"
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Pengguna /
                                    PIC</label>
                                <select name="pic_id" id="prod_pic"
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                                    <option value="">-- Pilih PIC --</option>
                                    @foreach ($pics as $pic)
                                        <option value="{{ $pic->id }}">{{ $pic->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Kondisi <span
                                        class="text-rose-500">*</span></label>
                                <select name="condition" id="prod_condition" required
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                                    @foreach (['Sangat Baik', 'Baik', 'Rusak Ringan', 'Rusak Berat'] as $cond)
                                        <option value="{{ $cond }}">{{ $cond }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Status Aset <span
                                        class="text-rose-500">*</span></label>
                                <select name="asset_status" id="prod_asset_status" required
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                                    @foreach (['Aktif Digunakan', 'Tersimpan Gudang', 'Dalam Perawatan', 'Dipinjamkan', 'Dihentikan/Afkir'] as $st)
                                        <option value="{{ $st }}">{{ $st }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Tgl Perawatan
                                    Terakhir</label>
                                <input type="date" name="last_maintenance_date" id="prod_last_maint"
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Frekuensi
                                    (Hari)</label>
                                <input type="number" name="maintenance_frequency_days" id="prod_maint_freq"
                                    placeholder="90"
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 4: LAINNYA -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Jumlah & Satuan <span
                                    class="text-rose-500">*</span></label>
                            <div class="flex gap-2">
                                <input type="number" step="0.01" name="quantity" id="prod_qty" placeholder="1"
                                    class="w-1/2 bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                                <input type="text" name="unit" id="prod_unit" required placeholder="UNIT / PCS"
                                    class="w-1/2 bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Mulai Digunakan
                                (Tgl)</label>
                            <input type="date" name="first_used_at" id="prod_first_used"
                                class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Catatan /
                            Spesifikasi</label>
                        <textarea name="description" id="prod_desc" rows="2" placeholder="Catatan spesifik aset..."
                            class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal"></textarea>
                    </div>

                    <div class="p-3 border-2 border-dashed border-slate-300 rounded-xl bg-white">
                        <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Foto Aset</label>
                        <input type="file" name="image"
                            class="block w-full text-xs text-slate-500 file:mr-3 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-inv-teal file:text-white cursor-pointer">
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-inv-teal to-inv-primary hover:from-inv-hover hover:to-inv-hover text-white font-bold py-3 rounded-xl shadow-md text-xs tracking-wider uppercase cursor-pointer">
                        Simpan Data Aset
                    </button>
                </form>
            </div>
        </div>

        <!-- 6. MODAL DETAIL PRODUK/ASET -->
        <!-- MODAL DETAIL ASET LENGKAP & STIKER QR CODE -->
        <div id="modalDetailProduct"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
            <div
                class="bg-slate-100 rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden border border-slate-300 transform transition-all">

                <!-- Header Modal -->
                <div
                    class="bg-gradient-to-r from-inv-teal to-inv-primary p-4 text-white flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-lg"></i>
                        <h3 class="font-serif font-bold text-base">Lembar Lembar Informasi & Tag Aset</h3>
                    </div>
                    <button onclick="closeDetailModal()" class="text-white/70 hover:text-white transition cursor-pointer">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="p-5 space-y-4 max-h-[80vh] overflow-y-auto custom-scrollbar">

                    <!-- AREA PRINT STIKER QR CODE -->
                    <div
                        class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center bg-white p-4 rounded-2xl border border-slate-200">
                        <div id="qrPrintArea"
                            class="md:col-span-5 border-2 border-dashed border-slate-300 rounded-xl p-3 text-center flex flex-col items-center bg-slate-50">
                            <span class="text-[9px] font-bold text-inv-teal uppercase tracking-widest mb-1"
                                id="detail_company_tag">COMPANY NAME</span>
                            <div id="qrcode" class="p-1.5 bg-white rounded-lg border border-slate-200 mb-1.5"></div>
                            <p id="detail_name_qr" class="font-serif font-bold text-slate-800 text-xs leading-tight"></p>
                            <p id="detail_sn_qr" class="text-[10px] text-inv-primary font-mono font-bold mt-0.5"></p>
                        </div>

                        <div class="md:col-span-7 space-y-2">
                            <p class="text-xs text-slate-600">Gunakan QR Code ini untuk menempel stiker inventaris fisik
                                pada unit barang.</p>
                            <div class="flex gap-2">
                                <button onclick="downloadQR()"
                                    class="w-full bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-2 rounded-xl text-xs transition cursor-pointer flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-download"></i> PNG
                                </button>
                                <button onclick="printStickerLabel()"
                                    class="w-full bg-inv-teal hover:bg-inv-hover text-white font-bold py-2 rounded-xl text-xs transition cursor-pointer flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-print"></i> Cetak Stiker
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- LEAFLET 22 KOLOM INFORMASI ASET -->
                    <div class="space-y-3">

                        <!-- GROUP 1: IDENTIFIKASI & KLASIFIKASI -->
                        <div class="p-3 bg-white rounded-xl border border-slate-200 space-y-2">
                            <p
                                class="text-[10px] font-bold text-inv-teal uppercase tracking-wider border-b border-slate-100 pb-1">
                                1. Identifikasi & Klasifikasi Aset
                            </p>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-xs">
                                <div>
                                    <span class="text-[9px] text-slate-400 font-semibold block uppercase">Perusahaan</span>
                                    <span id="dt_company" class="font-bold text-slate-800"></span>
                                </div>
                                <div>
                                    <span class="text-[9px] text-slate-400 font-semibold block uppercase">Kategori
                                        Utama</span>
                                    <span id="dt_category" class="font-semibold text-slate-700"></span>
                                </div>
                                <div>
                                    <span
                                        class="text-[9px] text-slate-400 font-semibold block uppercase">Subkategori</span>
                                    <span id="dt_subcategory" class="font-semibold text-slate-700"></span>
                                </div>
                                <div>
                                    <span class="text-[9px] text-slate-400 font-semibold block uppercase">Merek /
                                        Model</span>
                                    <span id="dt_brand" class="font-semibold text-slate-700"></span>
                                </div>
                                <div>
                                    <span class="text-[9px] text-slate-400 font-semibold block uppercase">Nomor Seri
                                        (SN)</span>
                                    <span id="dt_sn" class="font-mono font-bold text-inv-primary"></span>
                                </div>
                                <div>
                                    <span class="text-[9px] text-slate-400 font-semibold block uppercase">Jumlah Stok /
                                        Satuan</span>
                                    <span id="dt_qty" class="font-bold text-slate-800"></span>
                                </div>
                            </div>
                        </div>

                        <!-- GROUP 2: PENGADAAN & FINANSIAL -->
                        <div class="p-3 bg-white rounded-xl border border-slate-200 space-y-2">
                            <p
                                class="text-[10px] font-bold text-inv-teal uppercase tracking-wider border-b border-slate-100 pb-1">
                                2. Pengadaan & Depresiasi Finansial
                            </p>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-xs">
                                <div>
                                    <span class="text-[9px] text-slate-400 font-semibold block uppercase">Vendor /
                                        Pemasok</span>
                                    <span id="dt_supplier" class="font-semibold text-slate-700"></span>
                                </div>
                                <div>
                                    <span class="text-[9px] text-slate-400 font-semibold block uppercase">No. PO /
                                        Invoice</span>
                                    <span id="dt_po" class="font-semibold text-slate-700"></span>
                                </div>
                                <div>
                                    <span class="text-[9px] text-slate-400 font-semibold block uppercase">Tanggal
                                        Pembelian</span>
                                    <span id="dt_purchase_date" class="font-semibold text-slate-700"></span>
                                </div>
                                <div>
                                    <span class="text-[9px] text-slate-400 font-semibold block uppercase">Biaya
                                        Perolehan</span>
                                    <span id="dt_cost" class="font-bold text-slate-800"></span>
                                </div>
                                <div>
                                    <span class="text-[9px] text-slate-400 font-semibold block uppercase">Nilai
                                        Residu</span>
                                    <span id="dt_residual" class="font-semibold text-slate-700"></span>
                                </div>
                                <div>
                                    <span class="text-[9px] text-slate-400 font-semibold block uppercase">Est. Nilai Buku
                                        Saat Ini</span>
                                    <span id="dt_book_value" class="font-bold text-emerald-600"></span>
                                </div>
                            </div>
                        </div>

                        <!-- GROUP 3: PENEMPATAN, PIC & MAINTENANCE -->
                        <div class="p-3 bg-white rounded-xl border border-slate-200 space-y-2">
                            <p
                                class="text-[10px] font-bold text-inv-teal uppercase tracking-wider border-b border-slate-100 pb-1">
                                3. Penempatan, Status & Perawatan
                            </p>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-xs">
                                <div>
                                    <span class="text-[9px] text-slate-400 font-semibold block uppercase">Departemen</span>
                                    <span id="dt_department" class="font-semibold text-slate-700"></span>
                                </div>
                                <div>
                                    <span class="text-[9px] text-slate-400 font-semibold block uppercase">Lokasi
                                        Ruangan</span>
                                    <span id="dt_location" class="font-semibold text-slate-700"></span>
                                </div>
                                <div>
                                    <span class="text-[9px] text-slate-400 font-semibold block uppercase">Pengguna /
                                        PIC</span>
                                    <span id="dt_pic" class="font-bold text-inv-teal"></span>
                                </div>
                                <div>
                                    <span class="text-[9px] text-slate-400 font-semibold block uppercase">Kondisi
                                        Fisik</span>
                                    <span id="dt_condition" class="font-bold text-slate-800"></span>
                                </div>
                                <div>
                                    <span class="text-[9px] text-slate-400 font-semibold block uppercase">Status
                                        Operasional</span>
                                    <span id="dt_status" class="font-bold text-slate-800"></span>
                                </div>
                                <div>
                                    <span class="text-[9px] text-slate-400 font-semibold block uppercase">Perawatan
                                        Terakhir</span>
                                    <span id="dt_last_maint" class="font-semibold text-slate-700"></span>
                                </div>
                            </div>
                        </div>

                        <!-- CATATAN / DESKRIPSI -->
                        <div class="p-3 bg-white rounded-xl border border-slate-200 text-xs">
                            <span class="text-[9px] text-slate-400 font-bold block uppercase mb-0.5">Catatan
                                Spesifikasi</span>
                            <p id="dt_desc" class="text-slate-600 leading-relaxed italic"></p>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- JS SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        // Filter Subkategori sesuai Kategori Utama yang dipilih
        function filterSubCategories() {
            const catId = document.getElementById('prod_category').value;
            const subOptions = document.querySelectorAll('#prod_subcategory option');

            subOptions.forEach(opt => {
                if (!opt.value) return; // Skip default option
                if (opt.getAttribute('data-category') === catId) {
                    opt.style.display = 'block';
                } else {
                    opt.style.display = 'none';
                }
            });
        }

        function openModal(mode, product = null) {
            const modal = document.getElementById('modalProduct');
            const form = document.getElementById('productForm');
            const method = document.getElementById('formMethod');
            const title = document.getElementById('modalTitle');

            modal.classList.remove('hidden');

            if (mode === 'edit' && product) {
                title.innerText = 'Perbarui Data Aset';
                form.action = `/products/${product.id}`;
                method.value = 'PUT';

                document.getElementById('prod_company').value = product.company_name ?? 'General';
                document.getElementById('prod_category').value = product.category_id ?? '';
                filterSubCategories();
                document.getElementById('prod_subcategory').value = product.sub_category_id ?? '';
                document.getElementById('prod_name').value = product.name ?? '';
                document.getElementById('prod_brand_model').value = product.brand_model ?? '';
                document.getElementById('prod_serial_number').value = product.serial_number ?? '';
                document.getElementById('prod_supplier').value = product.supplier_id ?? '';
                document.getElementById('prod_po_invoice').value = product.po_invoice_number ?? '';
                document.getElementById('prod_purchase_date').value = product.purchase_date ?? '';
                document.getElementById('prod_purchase_cost').value = product.purchase_cost ?? '';
                document.getElementById('prod_residual_value').value = product.residual_value ?? '';
                document.getElementById('prod_useful_life').value = product.useful_life_years ?? '';
                document.getElementById('prod_department').value = product.department_id ?? '';
                document.getElementById('prod_location').value = product.location ?? '';
                document.getElementById('prod_pic').value = product.pic_id ?? '';
                document.getElementById('prod_condition').value = product.condition ?? 'Sangat Baik';
                document.getElementById('prod_asset_status').value = product.asset_status ?? 'Tersimpan Gudang';
                document.getElementById('prod_last_maint').value = product.last_maintenance_date ?? '';
                document.getElementById('prod_maint_freq').value = product.maintenance_frequency_days ?? '';
                document.getElementById('prod_qty').value = product.quantity ?? 1;
                document.getElementById('prod_unit').value = product.unit ?? 'UNIT';
                document.getElementById('prod_first_used').value = product.first_used_at ?? '';
                document.getElementById('prod_desc').value = product.description ?? '';
            } else {
                title.innerText = 'Pendaftaran Aset Baru';
                form.action = "{{ route('products.store') }}";
                method.value = 'POST';
                form.reset();
            }
        }

        function closeModal() {
            document.getElementById('modalProduct').classList.add('hidden');
        }

        function openDetailModal(product) {
            document.getElementById('modalDetailProduct').classList.remove('hidden');

            // Section QR Header
            document.getElementById('detail_company_tag').innerText = product.company_name || 'GENERAL';
            document.getElementById('detail_name_qr').innerText = product.name || '-';
            document.getElementById('detail_sn_qr').innerText = product.serial_number ? `SN: ${product.serial_number}` :
                'No SN';

            // Group 1: Identifikasi
            document.getElementById('dt_company').innerText = product.company_name || '-';
            document.getElementById('dt_category').innerText = product.category ? product.category.name : '-';
            document.getElementById('dt_subcategory').innerText = product.sub_category ? product.sub_category.name : '-';
            document.getElementById('dt_brand').innerText = product.brand_model || '-';
            document.getElementById('dt_sn').innerText = product.serial_number || '-';
            document.getElementById('dt_qty').innerText = `${product.quantity || 0} ${product.unit || 'UNIT'}`;

            // Group 2: Pengadaan & Finansial
            document.getElementById('dt_supplier').innerText = product.supplier ? product.supplier.name : '-';
            document.getElementById('dt_po').innerText = product.po_invoice_number || '-';
            document.getElementById('dt_purchase_date').innerText = product.purchase_date || '-';
            document.getElementById('dt_cost').innerText =
                `Rp ${new Intl.NumberFormat('id-ID').format(product.purchase_cost || 0)}`;
            document.getElementById('dt_residual').innerText =
                `Rp ${new Intl.NumberFormat('id-ID').format(product.residual_value || 0)}`;
            document.getElementById('dt_book_value').innerText =
                `Rp ${new Intl.NumberFormat('id-ID').format(product.current_book_value || 0)}`;

            // Group 3: Penempatan & Perawatan
            document.getElementById('dt_department').innerText = product.department ? product.department.name : '-';
            document.getElementById('dt_location').innerText = product.location || '-';
            document.getElementById('dt_pic').innerText = product.pic ? product.pic.name : '-';
            document.getElementById('dt_condition').innerText = product.condition || '-';
            document.getElementById('dt_status').innerText = product.asset_status || '-';
            document.getElementById('dt_last_maint').innerText = product.last_maintenance_date ?
                `${product.last_maintenance_date} (${product.maintenance_frequency_days || 0} hari)` : '-';
            document.getElementById('dt_desc').innerText = product.description || 'Tidak ada catatan tambahan.';

            // Generate QR Code
            const publicUrl = `${window.location.origin}/p/${product.slug}`;
            const qrContainer = document.getElementById('qrcode');
            qrContainer.innerHTML = '';

            new QRCode(qrContainer, {
                text: publicUrl,
                width: 110,
                height: 110,
                colorDark: "#081d34",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        }

        function closeDetailModal() {
            document.getElementById('modalDetailProduct').classList.add('hidden');
        }
    </script>
@endsection
