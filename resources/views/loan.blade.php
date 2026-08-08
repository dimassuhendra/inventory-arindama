@extends('layouts.app')

@section('content')
    <div x-data="{
        openReturnModal: false,
        returnLoanId: null,
        returnBorrower: '',
        returnProduct: '',
    
        openCatalogModal: false,
        catalogSearch: '',
        selectedProductId: '',
        selectedProductName: '',
        selectedProductStock: null,
    
        // Inject array products dari Controller ke JavaScript
        allProducts: {{ json_encode($products) }},
    
        get filteredProducts() {
            if (!this.catalogSearch.trim()) {
                return this.allProducts;
            }
            return this.allProducts.filter(p =>
                p.name.toLowerCase().includes(this.catalogSearch.toLowerCase())
            );
        },
    
        selectProduct(product) {
            this.selectedProductId = product.id;
            this.selectedProductName = product.name;
            this.selectedProductStock = product.quantity;
            this.openCatalogModal = false;
        }
    }" class="space-y-6">

        <!-- 1. HEADER & ACTION BUTTON -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-xl lg:text-2xl font-serif font-bold text-slate-800">Peminjaman Aset & Barang</h2>
                <p class="text-xs text-slate-500 mt-1">Sirkulasi peminjaman, pengawasan jatuh tempo, dan pengembalian stok
                </p>
            </div>

            <button onclick="document.getElementById('modalLoan').classList.remove('hidden')"
                class="bg-gradient-to-r from-inv-teal to-inv-primary hover:from-inv-hover hover:to-inv-hover text-white px-5 py-2.5 rounded-xl shadow-md transition-all flex items-center justify-center gap-2 font-bold text-xs cursor-pointer">
                <i class="fa-solid fa-hand-holding-hand"></i> Tambah Peminjaman Baru
            </button>
        </div>

        <!-- 2. MINI ANALYTICS BAR (4 CARDS PALET INV) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Barang Dipinjam -->
            <div
                class="bg-gradient-to-br from-[#00a8b5] to-[#2dd4bf] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-teal-100 uppercase tracking-widest">Total Barang Dipinjam</p>
                        <h3 class="text-2xl font-serif font-bold text-white mt-1">{{ number_format($total_borrowed_qty) }}
                            <span class="text-xs font-normal">Unit</span></h3>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg">
                        <i class="fa-solid fa-boxes-packing"></i>
                    </div>
                </div>
                <i
                    class="fa-solid fa-box-open absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
            </div>

            <!-- Peminjaman Aktif -->
            <div
                class="bg-gradient-to-br from-[#0c66c8] to-[#2563eb] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-blue-100 uppercase tracking-widest">Sesi Aktif Pinjam</p>
                        <h3 class="text-2xl font-serif font-bold text-white mt-1">{{ number_format($active_loans_count) }}
                            <span class="text-xs font-normal">Transaksi</span></h3>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                </div>
                <i
                    class="fa-solid fa-handshake absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
            </div>

            <!-- Terlambat / Overdue -->
            <div
                class="bg-gradient-to-br from-rose-500 to-rose-600 p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-rose-100 uppercase tracking-widest">Jatuh Tempo (Overdue)</p>
                        <h3 class="text-2xl font-serif font-bold text-white mt-1">{{ number_format($overdue_loans_count) }}
                            <span class="text-xs font-normal">Transaksi</span></h3>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                </div>
                <i
                    class="fa-solid fa-bell-exclamation absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
            </div>

            <!-- Dikembalikan Bulan Ini -->
            <div
                class="bg-gradient-to-br from-[#081d34] via-[#0d2a4a] to-[#00a8b5] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-teal-200 uppercase tracking-widest">Selesai Bulan Ini</p>
                        <h3 class="text-2xl font-serif font-bold text-inv-mint mt-1">
                            {{ number_format($completed_this_month) }} <span
                                class="text-xs font-normal text-slate-300">Selesai</span></h3>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur-md text-inv-mint flex items-center justify-center text-lg">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
                <i
                    class="fa-solid fa-rotate-left absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
            </div>
        </div>

        <!-- 3. FILTER & SEARCH BAR -->
        <div class="bg-slate-200/60 backdrop-blur-md p-4 rounded-2xl border border-slate-300/80">
            <form method="GET" action="{{ route('loans.index') }}"
                class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                <!-- Search Bar -->
                <div class="relative sm:col-span-5">
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari peminjam, barang, kontak, atau kode..."
                        class="w-full bg-slate-100 border border-slate-300 rounded-xl pl-9 pr-8 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-inv-teal transition-colors">
                    @if (request('search'))
                        <a href="{{ route('loans.index') }}"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </a>
                    @endif
                </div>

                <!-- Filter Status -->
                <div class="sm:col-span-4">
                    <select name="status" onchange="this.form.submit()"
                        class="w-full bg-slate-100 border border-slate-300 text-slate-700 text-xs rounded-xl p-2 outline-none focus:border-inv-teal">
                        <option value="">-- Semua Status Peminjaman --</option>
                        <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Sedang Dipinjam
                        </option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Terlambat (Overdue)
                        </option>
                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Sudah Dikembalikan
                        </option>
                    </select>
                </div>

                <!-- Per Page -->
                <div class="sm:col-span-3 flex items-center justify-end gap-2">
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

        <!-- 4. TABEL PEMINJAMAN -->
        <div class="bg-slate-200/60 backdrop-blur-md rounded-2xl border border-slate-300/80 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-max">
                    <thead class="bg-slate-300/60 border-b border-slate-300">
                        <tr>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Identitas
                                Peminjam</th>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Barang &
                                Jumlah</th>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Jadwal
                                Pengembalian</th>
                            <th
                                class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest text-center">
                                Status</th>
                            <th
                                class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest text-center">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-300/60">
                        @forelse($loans as $loan)
                            @php
                                $isOverdue =
                                    $loan->status === 'borrowed' &&
                                    \Carbon\Carbon::parse($loan->return_date)->isPast() &&
                                    !\Carbon\Carbon::parse($loan->return_date)->isToday();
                                $daysOverdue = $isOverdue
                                    ? \Carbon\Carbon::parse($loan->return_date)->diffInDays(now())
                                    : 0;
                                $productName = $loan->product?->name ?? 'Produk Dihapus';

                                // WhatsApp Reminder Direct Link
                                $cleanContact = preg_replace('/[^0-9]/', '', $loan->borrower_contact);
                                if (str_starts_with($cleanContact, '0')) {
                                    $cleanContact = '62' . substr($cleanContact, 1);
                                }
                                $waMessage = urlencode(
                                    "Halo {$loan->borrower_name}, mengingatkan jadwal pengembalian barang {$productName} ({$loan->quantity} unit) yang dipinjam sejak " .
                                        \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') .
                                        ' dengan batas pengembalian ' .
                                        \Carbon\Carbon::parse($loan->return_date)->format('d/m/Y') .
                                        '. Terima kasih.',
                                );
                            @endphp
                            <tr class="hover:bg-slate-300/40 transition-colors {{ $isOverdue ? 'bg-rose-500/5' : '' }}">
                                <!-- Peminjam & Kontak -->
                                <td class="px-5 py-3.5">
                                    <span class="text-xs font-bold text-slate-800 block">{{ $loan->borrower_name }}</span>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-[11px] text-slate-500 font-mono"><i
                                                class="fa-solid fa-phone text-[9px] mr-1 text-slate-400"></i>{{ $loan->borrower_contact }}</span>
                                        <a href="https://wa.me/{{ $cleanContact }}?text={{ $waMessage }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 bg-emerald-500/10 border border-emerald-500/20 px-1.5 py-0.2 rounded hover:bg-emerald-600 hover:text-white transition"
                                            title="Kirim Pesan Pengingat WhatsApp">
                                            <i class="fa-brands fa-whatsapp"></i> Ingatkan
                                        </a>
                                    </div>
                                </td>

                                <!-- Barang & Qty -->
                                <td class="px-5 py-3.5">
                                    <span class="text-xs font-bold text-slate-800 block">{{ $productName }}</span>
                                    <span
                                        class="bg-inv-primary/10 text-inv-primary border border-inv-primary/20 px-2 py-0.5 rounded text-[10px] font-bold mt-0.5 inline-block">
                                        {{ $loan->quantity }} Unit
                                    </span>
                                </td>

                                <!-- Tanggal Kembali -->
                                <td class="px-5 py-3.5 text-xs">
                                    <span
                                        class="text-slate-700 font-bold block">{{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y') }}</span>
                                    <span class="text-[10px] text-slate-400">Dipinjam:
                                        {{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</span>
                                </td>

                                <!-- Status & Overdue Badge -->
                                <td class="px-5 py-3.5 text-center">
                                    @if ($loan->status == 'borrowed')
                                        @if ($isOverdue)
                                            <span
                                                class="bg-rose-500/10 text-rose-700 border border-rose-500/30 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider block w-max mx-auto">
                                                <i class="fa-solid fa-triangle-exclamation mr-1"></i> Terlambat
                                                {{ $daysOverdue }} Hari
                                            </span>
                                        @else
                                            <span
                                                class="bg-amber-500/10 text-amber-700 border border-amber-500/30 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                                <i class="fa-solid fa-clock mr-1"></i> Dipinjam
                                            </span>
                                        @endif
                                    @else
                                        <span
                                            class="bg-emerald-500/10 text-emerald-700 border border-emerald-500/30 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                            <i class="fa-solid fa-circle-check mr-1"></i> Dikembalikan
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="px-5 py-3.5 text-center">
                                    @if ($loan->status == 'borrowed')
                                        <button
                                            @click="returnLoanId = {{ $loan->id }}; returnBorrower = '{{ addslashes($loan->borrower_name) }}'; returnProduct = '{{ addslashes($productName) }}'; openReturnModal = true"
                                            class="bg-gradient-to-r from-inv-teal to-inv-primary hover:from-inv-hover hover:to-inv-hover text-white px-3 py-1.5 rounded-lg text-[10px] font-bold shadow-sm transition cursor-pointer tracking-wider uppercase">
                                            Proses Pengembalian
                                        </button>
                                    @else
                                        <span class="text-slate-400 text-[10px] italic">
                                            <i class="fa-solid fa-check text-emerald-600 mr-1"></i> Selesai
                                            ({{ $loan->actual_return_date ? \Carbon\Carbon::parse($loan->actual_return_date)->format('d/m/Y') : '-' }})
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-box-open text-3xl mb-2 opacity-30"></i>
                                    <p class="text-xs italic">Belum ada data peminjaman barang yang tersimpan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (request('per_page') != 'all')
                <div class="px-5 py-3 bg-slate-300/40 border-t border-slate-300">
                    {{ $loans->links() }}
                </div>
            @endif
        </div>

        <!-- 5. MODAL FORM PEMINJAMAN BARU -->
        <div id="modalLoan"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
            <div
                class="bg-slate-100 rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-slate-300 transform transition-all">
                <div
                    class="bg-gradient-to-r from-inv-teal to-inv-primary p-5 text-white flex justify-between items-center">
                    <h3 class="font-serif font-bold text-base">Form Peminjaman Barang</h3>
                    <button onclick="document.getElementById('modalLoan').classList.add('hidden')"
                        class="text-white/70 hover:text-white transition cursor-pointer">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form action="{{ route('loans.store') }}" method="POST" class="p-6 space-y-4">
                    @csrf

                    <!-- Input Barang dengan Tombol Pilih Modal Katalog -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                Barang Yang Dipinjam <span class="text-rose-500">*</span>
                            </label>

                            <input type="hidden" name="product_id" x-model="selectedProductId" required>

                            <div class="flex gap-2">
                                <input type="text" x-model="selectedProductName" readonly required
                                    placeholder="-- Klik Cari Barang --"
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none cursor-pointer"
                                    @click="openCatalogModal = true">

                                <button type="button" @click="openCatalogModal = true"
                                    class="bg-inv-teal hover:bg-inv-hover text-white font-bold px-3.5 py-2.5 rounded-xl text-xs transition cursor-pointer flex items-center gap-1 shrink-0">
                                    <i class="fa-solid fa-magnifying-glass"></i> Cari
                                </button>
                            </div>

                            <p class="text-[10px] text-slate-500 mt-1" x-show="selectedProductStock !== null">
                                Stok Tersedia: <strong class="text-inv-teal"
                                    x-text="selectedProductStock + ' Unit'"></strong>
                            </p>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                Jumlah <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" name="quantity" min="1" :max="selectedProductStock || 9999"
                                required placeholder="1"
                                class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-inv-teal">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                Nama Peminjam <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="borrower_name" required placeholder="Nama lengkap peminjam..."
                                class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-inv-teal">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                No. HP / WhatsApp <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="borrower_contact" required placeholder="Contoh: 081234567890"
                                class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-inv-teal">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                Tanggal Pinjam <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="loan_date" value="{{ date('Y-m-d') }}" required
                                class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-inv-teal">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                Batas Tgl Kembali <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="return_date" value="{{ date('Y-m-d', strtotime('+7 days')) }}"
                                required
                                class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-inv-teal">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                            Keterangan / Keperluan (Opsional)
                        </label>
                        <textarea name="notes" rows="2" placeholder="Tujuan peminjaman atau lokasi penggunaan..."
                            class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-inv-teal"></textarea>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="document.getElementById('modalLoan').classList.add('hidden')"
                            class="flex-1 bg-slate-300 hover:bg-slate-400 text-slate-700 font-bold py-3 rounded-xl transition text-xs">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 bg-gradient-to-r from-inv-teal to-inv-primary hover:from-inv-hover hover:to-inv-hover text-white font-bold py-3 rounded-xl shadow-md transition text-xs tracking-wider uppercase cursor-pointer">
                            Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL KATALOG PENCARIAN BARANG (ALPINE.JS) -->
        <div x-show="openCatalogModal"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[110] flex items-center justify-center p-4"
            style="display: none;">
            <div class="bg-slate-100 rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden border border-slate-300">

                <div
                    class="bg-gradient-to-r from-inv-teal to-inv-primary p-4 text-white flex justify-between items-center">
                    <h3 class="font-serif font-bold text-sm"><i class="fa-solid fa-boxes-stacked mr-1.5"></i> Pilih Barang
                        Dari Katalog</h3>
                    <button @click="openCatalogModal = false"
                        class="text-white/70 hover:text-white transition cursor-pointer">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="p-4 space-y-3">
                    <!-- Live Search Bar -->
                    <div class="relative">
                        <i
                            class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" x-model="catalogSearch" placeholder="Ketik nama barang untuk mencari..."
                            class="w-full bg-white border border-slate-300 rounded-xl pl-9 pr-3 py-2 text-xs text-slate-800 outline-none focus:border-inv-teal">
                    </div>

                    <!-- Tabel Katalog Hasil Pencarian -->
                    <div class="max-h-64 overflow-y-auto border border-slate-300 rounded-xl bg-white custom-scrollbar">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead class="bg-slate-100 border-b border-slate-200 sticky top-0">
                                <tr>
                                    <th class="p-2.5 font-bold text-slate-600">Nama Barang</th>
                                    <th class="p-2.5 font-bold text-slate-600 text-center">Stok</th>
                                    <th class="p-2.5 font-bold text-slate-600 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                <template x-for="product in filteredProducts" :key="product.id">
                                    <tr class="hover:bg-slate-50">
                                        <td class="p-2.5 font-bold text-slate-800" x-text="product.name"></td>
                                        <td class="p-2.5 text-center">
                                            <span
                                                class="bg-emerald-500/10 text-emerald-700 font-bold px-2 py-0.5 rounded text-[10px]"
                                                x-text="product.quantity + ' Unit'"></span>
                                        </td>
                                        <td class="p-2.5 text-center">
                                            <button type="button" @click="selectProduct(product)"
                                                class="bg-inv-teal hover:bg-inv-hover text-white font-bold px-3 py-1 rounded-lg text-[10px] transition cursor-pointer">
                                                Pilih
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="filteredProducts.length === 0">
                                    <td colspan="3" class="p-6 text-center text-slate-400 italic">Barang tidak
                                        ditemukan atau stok habis.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. MODAL KONFIRMASI PENGEMBALIAN (ALPINE.JS) -->
        <div x-show="openReturnModal"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4"
            style="display: none;">
            <div class="bg-slate-100 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-300">
                <div
                    class="bg-gradient-to-r from-inv-teal to-inv-primary p-5 text-white flex justify-between items-center">
                    <h3 class="font-serif font-bold text-base">Konfirmasi Pengembalian Barang</h3>
                    <button @click="openReturnModal = false"
                        class="text-white/70 hover:text-white transition cursor-pointer">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form :action="'/loans/' + returnLoanId + '/return'" method="POST" class="p-6 space-y-4">
                    @csrf
                    <p class="text-xs text-slate-700 font-medium">
                        Memproses pengembalian barang <strong class="text-slate-900" x-text="returnProduct"></strong> dari
                        peminjam <strong class="text-slate-900" x-text="returnBorrower"></strong>. Stok akan otomatis
                        ditambahkan kembali.
                    </p>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                            Catatan Kondisi Barang Saat Kembali (Opsional)
                        </label>
                        <textarea name="return_notes" rows="2" placeholder="Contoh: Kondisi fisik bagus, kelengkapan utuh..."
                            class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-inv-teal"></textarea>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="openReturnModal = false"
                            class="flex-1 bg-slate-300 hover:bg-slate-400 text-slate-700 font-bold py-3 rounded-xl transition text-xs">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 bg-gradient-to-r from-inv-teal to-inv-primary hover:from-inv-hover hover:to-inv-hover text-white font-bold py-3 rounded-xl shadow-md transition text-xs tracking-wider uppercase cursor-pointer">
                            Konfirmasi Selesai
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- SWEETALERT -->
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
    </script>
@endsection
