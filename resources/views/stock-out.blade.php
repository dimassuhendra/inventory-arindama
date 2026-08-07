@extends('layouts.app')

@section('content')
    <!-- TomSelect Assets -->
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
                <h2 class="text-xl lg:text-2xl font-serif font-bold text-slate-800">Stock Out (Barang Keluar)</h2>
                <p class="text-xs text-slate-500 mt-1">Pencatatan distribusi dan pengeluaran barang dari gudang</p>
            </div>

            <button onclick="openModal('add')"
                class="bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 text-white px-5 py-2.5 rounded-xl shadow-md transition-all flex items-center justify-center gap-2 font-bold text-xs cursor-pointer">
                <i class="fa-solid fa-minus"></i> Catat Barang Keluar
            </button>
        </div>

        <!-- 2. MINI ANALYTICS BAR (CARDS TEMA EXITS) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Qty Keluar Bulan Ini -->
            <div
                class="bg-gradient-to-br from-rose-500 to-rose-600 p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-rose-100 uppercase tracking-widest">Keluar Bulan Ini</p>
                        <h3 class="text-2xl font-serif font-bold text-white mt-1">-{{ number_format($total_monthly_qty) }}
                            <span class="text-xs font-normal">Unit</span></h3>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg">
                        <i class="fa-solid fa-circle-arrow-up"></i>
                    </div>
                </div>
                <i
                    class="fa-solid fa-box-open absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
            </div>

            <!-- Transaksi Keluar Bulan Ini -->
            <div
                class="bg-gradient-to-br from-[#081d34] via-[#0d2a4a] to-[#00a8b5] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-teal-200 uppercase tracking-widest">Transaksi Keluar</p>
                        <h3 class="text-2xl font-serif font-bold text-white mt-1">{{ number_format($total_monthly_tx) }}
                            <span class="text-xs font-normal">Kali</span></h3>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur-md text-inv-mint flex items-center justify-center text-lg">
                        <i class="fa-solid fa-dolly"></i>
                    </div>
                </div>
                <i
                    class="fa-solid fa-truck-ramp-box absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
            </div>

            <!-- Top Item Keluar -->
            <div
                class="bg-gradient-to-br from-slate-700 to-slate-900 p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">Permintaan Terbanyak</p>
                        <h3 class="text-sm font-serif font-bold text-white mt-1 truncate max-w-[180px]">
                            {{ $top_product_name }}</h3>
                        <p class="text-[10px] text-slate-400">Bulan berjalan</p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur-md text-white flex items-center justify-center text-lg">
                        <i class="fa-solid fa-fire"></i>
                    </div>
                </div>
                <i
                    class="fa-solid fa-arrow-trend-up absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
            </div>
        </div>

        <!-- 3. FILTER & SEARCH BAR -->
        <div class="bg-slate-200/60 backdrop-blur-md p-4 rounded-2xl border border-slate-300/80">
            <form method="GET" action="{{ route('stock-out.index') }}"
                class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">

                <!-- Live Search -->
                <div class="relative sm:col-span-4">
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari produk, keterangan, petugas..."
                        class="w-full bg-slate-100 border border-slate-300 rounded-xl pl-9 pr-8 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-rose-500 transition-colors">
                    @if (request('search'))
                        <a href="{{ route('stock-out.index') }}"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </a>
                    @endif
                </div>

                <!-- Date From -->
                <div class="sm:col-span-2">
                    <input type="date" name="from_date" value="{{ request('from_date') }}" title="Dari Tanggal"
                        class="w-full bg-slate-100 border border-slate-300 text-slate-700 text-xs rounded-xl p-2 outline-none focus:border-rose-500">
                </div>

                <!-- Date To -->
                <div class="sm:col-span-2">
                    <input type="date" name="to_date" value="{{ request('to_date') }}" title="Sampai Tanggal"
                        class="w-full bg-slate-100 border border-slate-300 text-slate-700 text-xs rounded-xl p-2 outline-none focus:border-rose-500">
                </div>

                <!-- Filter Kategori -->
                <div class="sm:col-span-2">
                    <select name="category_id" onchange="this.form.submit()"
                        class="w-full bg-slate-100 border border-slate-300 text-slate-700 text-xs rounded-xl p-2 outline-none focus:border-rose-500">
                        <option value="">-- Kategori --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Button -->
                <div class="sm:col-span-2 flex items-center gap-1.5">
                    <button type="submit"
                        class="w-full bg-rose-500 hover:bg-rose-600 text-white font-bold py-2 px-3 rounded-xl text-xs transition cursor-pointer">
                        Filter
                    </button>
                    @if (request('from_date') || request('to_date') || request('category_id') || request('search'))
                        <a href="{{ route('stock-out.index') }}"
                            class="bg-slate-300 hover:bg-slate-400 text-slate-700 font-bold p-2 rounded-xl text-xs transition"
                            title="Reset Filter">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- 4. TABEL RIWAYAT STOK KELUAR -->
        <div class="bg-slate-200/60 backdrop-blur-md rounded-2xl border border-slate-300/80 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-max">
                    <thead class="bg-slate-300/60 border-b border-slate-300">
                        <tr>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Tanggal
                            </th>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Produk &
                                Keterangan</th>
                            <th
                                class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest text-center">
                                Jumlah Keluar</th>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Petugas
                            </th>
                            <th
                                class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest text-center">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-300/60">
                        @forelse($exits as $exit)
                            @php
                                $canManageCategory = \App\Services\CategoryService::canUserManage(
                                    $exit->product->category,
                                );
                            @endphp
                            <tr class="hover:bg-slate-300/40 transition-colors">
                                <td class="px-5 py-3.5 text-xs font-bold text-slate-700">
                                    {{ date('d M Y', strtotime($exit->exit_date)) }}
                                </td>

                                <td class="px-5 py-3.5">
                                    <div class="text-xs font-bold text-slate-800">{{ $exit->product->name }}</div>
                                    <div class="text-[10px] text-slate-500 italic mt-0.5 line-clamp-1">
                                        {{ $exit->description ?: 'Tanpa keterangan' }}
                                    </div>
                                </td>

                                <td class="px-5 py-3.5 text-center">
                                    <span
                                        class="bg-rose-500/10 text-rose-700 border border-rose-500/30 px-3 py-1 rounded-full text-xs font-bold tracking-wide">
                                        -{{ number_format($exit->quantity, 0) }} {{ $exit->product->unit }}
                                    </span>
                                </td>

                                <td class="px-5 py-3.5 text-xs text-slate-600 font-medium">
                                    {{ $exit->user->name ?? 'System' }}
                                </td>

                                <td class="px-5 py-3.5 text-center">
                                    @if ($canManageCategory)
                                        <div class="flex justify-center items-center gap-1.5">
                                            <!-- Edit Button -->
                                            <button
                                                onclick="openModal('edit', {{ $exit->id }}, {{ $exit->product_id }}, {{ $exit->quantity }}, '{{ $exit->exit_date }}', '{{ addslashes($exit->description) }}')"
                                                class="p-1.5 text-amber-600 hover:bg-slate-300 rounded-lg transition text-xs cursor-pointer"
                                                title="Edit Transaksi">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>

                                            <!-- Cancel / Delete Button -->
                                            <form action="{{ route('stock-out.destroy', $exit->id) }}" method="POST"
                                                onsubmit="return confirm('Batalkan pengeluaran ini? Stok akan dikembalikan ke gudang.')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="p-1.5 text-rose-600 hover:bg-slate-300 rounded-lg transition text-xs cursor-pointer"
                                                    title="Batalkan Pengeluaran">
                                                    <i class="fa-solid fa-rotate-left"></i>
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
                                    <i class="fa-solid fa-box-open text-3xl mb-2 opacity-30"></i>
                                    <p class="text-xs italic">Belum ada riwayat transaksi pengeluaran barang.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (request('per_page') != 'all')
                <div class="px-5 py-3 bg-slate-300/40 border-t border-slate-300">
                    {{ $exits->links() }}
                </div>
            @endif
        </div>

        <!-- 5. MODAL INPUT / EDIT BARANG KELUAR -->
        <div id="modalStockOut"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
            <div
                class="bg-slate-100 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-300 transform transition-all">

                <div class="bg-gradient-to-r from-rose-500 to-rose-600 p-5 text-white flex justify-between items-center">
                    <h3 id="modalTitle" class="font-serif font-bold text-base">Input Barang Keluar</h3>
                    <button onclick="closeModal()" class="text-white/70 hover:text-white transition cursor-pointer">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form id="stockOutForm" method="POST" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <!-- Filter Kategori + Searchable Select Produk -->
                    <div class="space-y-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                                <i class="fa-solid fa-filter text-rose-500 mr-1"></i> Saring Kategori (Opsional)
                            </label>
                            <select id="modal_category_filter" onchange="filterProductsByCategory(this.value)"
                                class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-700 outline-none focus:border-rose-500">
                                <option value="">-- Semua Kategori --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">
                                Pilih / Cari Barang <span class="text-rose-500">*</span>
                            </label>
                            <select name="product_id" id="prod_id" required placeholder="Ketik nama barang..."
                                autocomplete="off">
                                <option value="">-- Ketik Nama Barang --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-category="{{ $product->category_id }}"
                                        data-stock="{{ $product->quantity }}" data-unit="{{ $product->unit }}">
                                        [{{ $product->unit }}] {{ $product->name }} (Sisa Stok:
                                        {{ number_format($product->quantity, 0) }})
                                    </option>
                                @endforeach
                            </select>
                            <p id="currentStockHint" class="text-[10px] text-rose-600 mt-1 font-semibold hidden">
                                <i class="fa-solid fa-info-circle mr-1"></i> Stok Gudang Tersedia: <span
                                    id="currentStockValue">0</span>
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Qty Keluar -->
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                Jumlah Keluar <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" name="quantity" id="prod_qty" step="0.01" min="0.01" required
                                placeholder="0"
                                class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-rose-500">
                        </div>

                        <!-- Tanggal Keluar -->
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                Tanggal Keluar <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="exit_date" id="prod_date" required
                                class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-rose-500">
                        </div>
                    </div>

                    <!-- Keterangan / Tujuan -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                            Keterangan / Tujuan Pengeluaran
                        </label>
                        <textarea name="description" id="prod_desc" rows="2"
                            placeholder="Misal: Kebutuhan maintenance Gedung Utama..."
                            class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-rose-500"></textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 text-white font-bold py-3 rounded-xl shadow-md text-xs tracking-wider uppercase cursor-pointer mt-2">
                        Konfirmasi Barang Keluar
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

        function filterProductsByCategory(categoryId) {
            if (!tomSelectInstance) return;

            tomSelectInstance.clear();
            tomSelectInstance.clearOptions();

            const originalSelect = document.getElementById('prod_id');
            const options = originalSelect.querySelectorAll('option');

            options.forEach(opt => {
                if (!opt.value) return;
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

        function openModal(mode, id = null, prodId = null, qty = null, date = null, desc = '') {
            const modal = document.getElementById('modalStockOut');
            const form = document.getElementById('stockOutForm');
            const method = document.getElementById('formMethod');
            const title = document.getElementById('modalTitle');

            modal.classList.remove('hidden');
            document.getElementById('modal_category_filter').value = '';

            if (mode === 'edit') {
                title.innerText = 'Edit Transaksi Barang Keluar';
                form.action = `/stock-out/${id}`;
                method.value = 'PUT';

                tomSelectInstance.setValue(prodId);
                tomSelectInstance.disable();

                document.getElementById('prod_qty').value = qty;
                document.getElementById('prod_date').value = date;
                document.getElementById('prod_desc').value = desc;
            } else {
                title.innerText = 'Catat Barang Keluar Baru';
                form.action = "{{ route('stock-out.store') }}";
                method.value = 'POST';
                form.reset();

                tomSelectInstance.clear();
                tomSelectInstance.enable();

                document.getElementById('prod_date').valueAsDate = new Date();
                document.getElementById('currentStockHint').classList.add('hidden');
            }
        }

        function closeModal() {
            document.getElementById('modalStockOut').classList.add('hidden');
        }
    </script>
@endsection
