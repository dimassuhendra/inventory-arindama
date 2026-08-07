@extends('layouts.app')

@section('content')
    <!-- Tambahan untku CSS & JS TomSelect fitur Searchable Dropdown -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <style>
        .ts-control {
            border-radius: 0.75rem !important;
            padding: 0.6rem 0.85rem !important;
            border-color: #cbd5e1 !important;
            background-color: #ffffff !important;
            font-size: 0.75rem !important;
        }

        .ts-dropdown {
            border-radius: 0.75rem !important;
            font-size: 0.75rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        }
    </style>

    <div class="space-y-6">

        <!-- 1. HEADER & ACTION BUTTON -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-xl lg:text-2xl font-serif font-bold text-slate-800">Stock In (Barang Masuk)</h2>
                <p class="text-xs text-slate-500 mt-1">Pencatatan riwayat pasokan dan penambahan inventaris gudang</p>
            </div>

            <button onclick="openModal('add')"
                class="bg-gradient-to-r from-inv-teal to-inv-primary hover:from-inv-hover hover:to-inv-hover text-white px-5 py-2.5 rounded-xl shadow-md transition-all flex items-center justify-center gap-2 font-bold text-xs cursor-pointer">
                <i class="fa-solid fa-plus"></i> Tambah Stok Masuk
            </button>
        </div>

        <!-- 2. MINI ANALYTICS BAR (4 CARDS PALET INV) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Qty Masuk Bulan Ini -->
            <div
                class="bg-gradient-to-br from-[#00a8b5] to-[#2dd4bf] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-teal-100 uppercase tracking-widest">Masuk Bulan Ini</p>
                        <h3 class="text-2xl font-serif font-bold text-white mt-1">+{{ number_format($total_monthly_qty) }}
                            <span class="text-xs font-normal">Unit</span>
                        </h3>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg">
                        <i class="fa-solid fa-circle-arrow-down"></i>
                    </div>
                </div>
                <i
                    class="fa-solid fa-circle-arrow-down absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
            </div>

            <!-- Frekuensi Transaksi Bulan Ini -->
            <div
                class="bg-gradient-to-br from-[#0c66c8] to-[#2563eb] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-blue-100 uppercase tracking-widest">Transaksi Masuk</p>
                        <h3 class="text-2xl font-serif font-bold text-white mt-1">{{ number_format($total_monthly_tx) }}
                            <span class="text-xs font-normal">Kali</span>
                        </h3>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                </div>
                <i
                    class="fa-solid fa-file-invoice absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
            </div>

            <!-- Top Produk Masuk -->
            <div
                class="bg-gradient-to-br from-[#081d34] via-[#0d2a4a] to-[#00a8b5] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-teal-200 uppercase tracking-widest">Pasokan Terbanyak</p>
                        <h3 class="text-sm font-serif font-bold text-inv-mint mt-1 truncate max-w-[150px]">
                            {{ $top_product_name }}</h3>
                        <p class="text-[10px] text-slate-300">Bulan berjalan</p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur-md text-inv-mint flex items-center justify-center text-lg">
                        <i class="fa-solid fa-trophy"></i>
                    </div>
                </div>
                <i
                    class="fa-solid fa-trophy absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
            </div>

            <!-- Top Supplier -->
            <div
                class="bg-gradient-to-br from-slate-700 to-slate-900 p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">Supplier Teraktif</p>
                        <h3 class="text-sm font-serif font-bold text-white mt-1 truncate max-w-[150px]">
                            {{ $top_supplier_name }}</h3>
                        <p class="text-[10px] text-slate-400">Bulan berjalan</p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur-md text-white flex items-center justify-center text-lg">
                        <i class="fa-solid fa-truck"></i>
                    </div>
                </div>
                <i
                    class="fa-solid fa-truck absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
            </div>
        </div>

        <!-- 3. FILTER & SEARCH BAR (DENGAN DATE RANGE) -->
        <div class="bg-slate-200/60 backdrop-blur-md p-4 rounded-2xl border border-slate-300/80">
            <form method="GET" action="{{ route('stock-in.index') }}"
                class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">

                <!-- Live Search Bar -->
                <div class="relative sm:col-span-4">
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari produk, supplier, petugas..."
                        class="w-full bg-slate-100 border border-slate-300 rounded-xl pl-9 pr-8 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-inv-teal transition-colors">
                    @if (request('search'))
                        <a href="{{ route('stock-in.index') }}"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </a>
                    @endif
                </div>

                <!-- Date Range From -->
                <div class="sm:col-span-2">
                    <input type="date" name="from_date" value="{{ request('from_date') }}" title="Dari Tanggal"
                        class="w-full bg-slate-100 border border-slate-300 text-slate-700 text-xs rounded-xl p-2 outline-none focus:border-inv-teal">
                </div>

                <!-- Date Range To -->
                <div class="sm:col-span-2">
                    <input type="date" name="to_date" value="{{ request('to_date') }}" title="Sampai Tanggal"
                        class="w-full bg-slate-100 border border-slate-300 text-slate-700 text-xs rounded-xl p-2 outline-none focus:border-inv-teal">
                </div>

                <!-- Select Supplier -->
                <div class="sm:col-span-2">
                    <select name="supplier_id" onchange="this.form.submit()"
                        class="w-full bg-slate-100 border border-slate-300 text-slate-700 text-xs rounded-xl p-2 outline-none focus:border-inv-teal">
                        <option value="">-- Supplier --</option>
                        @foreach ($suppliers as $sup)
                            <option value="{{ $sup->id }}" {{ request('supplier_id') == $sup->id ? 'selected' : '' }}>
                                {{ $sup->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Filter Button -->
                <div class="sm:col-span-2 flex items-center gap-1.5">
                    <button type="submit"
                        class="w-full bg-inv-teal hover:bg-inv-hover text-white font-bold py-2 px-3 rounded-xl text-xs transition cursor-pointer">
                        Filter
                    </button>
                    @if (request('from_date') || request('to_date') || request('supplier_id') || request('search'))
                        <a href="{{ route('stock-in.index') }}"
                            class="bg-slate-300 hover:bg-slate-400 text-slate-700 font-bold p-2 rounded-xl text-xs transition"
                            title="Reset Filter">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- 4. TABEL RIWAYAT STOK MASUK -->
        <div class="bg-slate-200/60 backdrop-blur-md rounded-2xl border border-slate-300/80 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-max">
                    <thead class="bg-slate-300/60 border-b border-slate-300">
                        <tr>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Tanggal
                            </th>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Info
                                Produk</th>
                            <th
                                class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest text-center">
                                Qty Masuk</th>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Supplier
                            </th>
                            <th
                                class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest text-center">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-300/60">
                        @forelse($entries as $entry)
                            @php
                                $canManageCategory = \App\Services\CategoryService::canUserManage(
                                    $entry->product->category,
                                );
                            @endphp
                            <tr class="hover:bg-slate-300/40 transition-colors">
                                <!-- Tanggal -->
                                <td class="px-5 py-3.5 text-xs font-bold text-slate-700">
                                    {{ date('d M Y', strtotime($entry->entry_date)) }}
                                </td>

                                <!-- Produk & Petugas -->
                                <td class="px-5 py-3.5">
                                    <div class="text-xs font-bold text-slate-800">{{ $entry->product->name }}</div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">
                                        Petugas: <span
                                            class="font-semibold text-slate-600">{{ $entry->user->name ?? 'System' }}</span>
                                    </div>
                                </td>

                                <!-- Qty -->
                                <td class="px-5 py-3.5 text-center">
                                    <span
                                        class="bg-emerald-500/10 text-emerald-700 border border-emerald-500/30 px-3 py-1 rounded-full text-xs font-bold tracking-wide">
                                        +{{ number_format($entry->quantity, 0) }} {{ $entry->product->unit }}
                                    </span>
                                </td>

                                <!-- Supplier -->
                                <td class="px-5 py-3.5 text-xs text-slate-600 font-medium">
                                    {{ $entry->supplier->name ?? 'N/A' }}
                                </td>

                                <!-- Aksi -->
                                <td class="px-5 py-3.5 text-center">
                                    @if ($canManageCategory)
                                        <div class="flex justify-center items-center gap-1.5">
                                            <button
                                                onclick="openModal('edit', {{ $entry->id }}, {{ $entry->product_id }}, {{ $entry->quantity }}, '{{ $entry->entry_date }}')"
                                                class="p-1.5 text-amber-600 hover:bg-slate-300 rounded-lg transition text-xs cursor-pointer"
                                                title="Edit Transaksi">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <form action="{{ route('stock-in.destroy', $entry->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus riwayat masuk ini? Stok produk akan dikurangi.')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="p-1.5 text-rose-600 hover:bg-slate-300 rounded-lg transition text-xs cursor-pointer"
                                                    title="Hapus Transaksi">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-[10px] text-slate-400 italic"
                                            title="Kategori ini terkunci untuk role Anda">
                                            <i class="fa-solid fa-lock"></i>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-boxes-packing text-3xl mb-2 opacity-30"></i>
                                    <p class="text-xs italic">Belum ada riwayat transaksi stok masuk.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (request('per_page') != 'all')
                <div class="px-5 py-3 bg-slate-300/40 border-t border-slate-300">
                    {{ $entries->links() }}
                </div>
            @endif
        </div>

        <!-- 5. MODAL INPUT / EDIT STOK MASUK -->
        <div id="modalStockIn"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
            <div
                class="bg-slate-100 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-300 transform transition-all">

                <div
                    class="bg-gradient-to-r from-inv-teal to-inv-primary p-5 text-white flex justify-between items-center">
                    <h3 id="modalTitle" class="font-serif font-bold text-base">Input Stok Masuk</h3>
                    <button onclick="closeModal()" class="text-white/70 hover:text-white transition cursor-pointer">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form id="stockForm" method="POST" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="space-y-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                                <i class="fa-solid fa-filter text-inv-teal mr-1"></i> Saring Kategori (Opsional)
                            </label>
                            <select id="modal_category_filter" onchange="filterProductsByCategory(this.value)"
                                class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-700 outline-none focus:border-inv-teal">
                                <option value="">-- Semua Kategori --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">
                                Pilih / Cari Nama Produk <span class="text-rose-500">*</span>
                            </label>
                            <select name="product_id" id="prod_id" required placeholder="Ketik nama produk..."
                                autocomplete="off">
                                <option value="">-- Ketik Nama Produk --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-category="{{ $product->category_id }}"
                                        data-stock="{{ $product->quantity }}" data-unit="{{ $product->unit }}">
                                        [{{ $product->unit }}] {{ $product->name }} (Stok:
                                        {{ number_format($product->quantity, 0) }})
                                    </option>
                                @endforeach
                            </select>
                            <p id="currentStockHint" class="text-[10px] text-inv-teal mt-1 font-semibold hidden">
                                <i class="fa-solid fa-info-circle mr-1"></i> Stok Gudang Saat Ini: <span
                                    id="currentStockValue">0</span>
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                Jumlah (Qty) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" name="quantity" id="prod_qty" step="0.01" min="0.01" required
                                placeholder="0"
                                class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-inv-teal">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                Tanggal Masuk <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="entry_date" id="prod_date" required
                                class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-inv-teal">
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-inv-teal to-inv-primary hover:from-inv-hover hover:to-inv-hover text-white font-bold py-3 rounded-xl shadow-md text-xs tracking-wider uppercase cursor-pointer mt-2">
                        Simpan Transaksi Masuk
                    </button>
                </form>
            </div>
        </div>

    </div>

    <!-- SCRIPTS & SWEETALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false,
                borderRadius: '20px'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: "{{ session('error') }}",
                borderRadius: '20px'
            });
        @endif

        let tomSelectInstance = null;

        document.addEventListener('DOMContentLoaded', function() {
            tomSelectInstance = new TomSelect("#prod_id", {
                create: false,
                sortField: {
                    field: "text",
                    order: "asc"
                },
                onChange: function(value) {
                    const selectElem = document.getElementById('prod_id');
                    const selectedOption = selectElem.querySelector(`option[value="${value}"]`);
                    const stockHint = document.getElementById('currentStockHint');
                    const stockValue = document.getElementById('currentStockValue');

                    if (selectedOption && value) {
                        const stock = selectedOption.getAttribute('data-stock');
                        const unit = selectedOption.getAttribute('data-unit');
                        stockValue.innerText = `${Number(stock).toLocaleString('id-ID')} ${unit}`;
                        stockHint.classList.remove('hidden');
                    } else {
                        stockHint.classList.add('hidden');
                    }
                }
            });
        });

        function updateStockInfo(selectElem) {
            const selectedOption = selectElem.options[selectElem.selectedIndex];
            const stockHint = document.getElementById('currentStockHint');
            const stockValue = document.getElementById('currentStockValue');

            if (selectedOption.value) {
                const stock = selectedOption.getAttribute('data-stock');
                const unit = selectedOption.getAttribute('data-unit');
                stockValue.innerText = `${Number(stock).toLocaleString('id-ID')} ${unit}`;
                stockHint.classList.remove('hidden');
            } else {
                stockHint.classList.add('hidden');
            }
        }

        function filterProductsByCategory(categoryId) {
            if (!tomSelectInstance) return;

            tomSelectInstance.clear();
            tomSelectInstance.clearOptions();

            const originalSelect = document.getElementById('prod_id');
            const options = originalSelect.querySelectorAll('option');

            options.forEach(opt => {
                if (!opt.value) return; // Skip placeholder

                const optCat = opt.getAttribute('data-category');
                if (!categoryId || optCat == categoryId) {
                    tomSelectInstance.addOption({
                        value: opt.value,
                        text: opt.text
                    });
                }
            });

            tomSelectInstance.refreshOptions(false);
        }

        function openModal(mode, id = null, prodId = null, qty = null, date = null) {
            const modal = document.getElementById('modalStockIn');
            const form = document.getElementById('stockForm');
            const method = document.getElementById('formMethod');
            const title = document.getElementById('modalTitle');

            modal.classList.remove('hidden');

            document.getElementById('modal_category_filter').value = '';

            if (mode === 'edit') {
                title.innerText = 'Edit Riwayat Stok Masuk';
                form.action = `/stock-in/${id}`;
                method.value = 'PUT';

                tomSelectInstance.setValue(prodId);
                tomSelectInstance.disable();

                document.getElementById('prod_qty').value = qty;
                document.getElementById('prod_date').value = date;
            } else {
                title.innerText = 'Input Stok Masuk Baru';
                form.action = "{{ route('stock-in.store') }}";
                method.value = 'POST';
                form.reset();

                tomSelectInstance.clear();
                tomSelectInstance.enable();

                document.getElementById('prod_date').valueAsDate = new Date();
                document.getElementById('currentStockHint').classList.add('hidden');
            }
        }

        function closeModal() {
            document.getElementById('modalStockIn').classList.add('hidden');
        }
    </script>
@endsection
