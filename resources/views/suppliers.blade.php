@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <!-- 1. HEADER & ACTION BUTTON -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-xl lg:text-2xl font-serif font-bold text-slate-800">Daftar Supplier / Vendor</h2>
                <p class="text-xs text-slate-500 mt-1">Kelola data rekanan pemasok dan saluran pengadaan barang</p>
            </div>

            <button onclick="openModal('add')"
                class="bg-gradient-to-r from-inv-teal to-inv-primary hover:from-inv-hover hover:to-inv-hover text-white px-5 py-2.5 rounded-xl shadow-md transition-all flex items-center justify-center gap-2 font-bold text-xs cursor-pointer">
                <i class="fa-solid fa-truck-field"></i> Tambah Supplier Baru
            </button>
        </div>

        <!-- 2. MINI ANALYTICS BAR (3 CARDS PALET INV) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Total Supplier -->
            <div
                class="bg-gradient-to-br from-[#00a8b5] to-[#2dd4bf] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-teal-100 uppercase tracking-widest">Total Rekanan</p>
                        <h3 class="text-2xl font-serif font-bold text-white mt-1">
                            {{ number_format($total_suppliers_count) }} <span class="text-xs font-normal">Perusahaan</span>
                        </h3>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg">
                        <i class="fa-solid fa-handshake"></i>
                    </div>
                </div>
                <i class="fa-solid fa-building-flag absolute -right-3 -bottom-3 text-6xl text-white/10"></i>
            </div>

            <!-- Supplier Terbanyak Memasok -->
            <div
                class="bg-gradient-to-br from-[#0c66c8] to-[#2563eb] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-blue-100 uppercase tracking-widest">Pemasok Teraktif</p>
                        <h3 class="text-sm font-serif font-bold text-white mt-1 truncate max-w-[170px]">
                            {{ $top_supplier_name }}
                        </h3>
                        <p
                            class="text-[10px] text-blue-100 font-semibold bg-white/15 px-2 py-0.5 rounded-md w-max mt-1 backdrop-blur-sm">
                            {{ $top_supplier_products }} Jenis Produk
                        </p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg">
                        <i class="fa-solid fa-award"></i>
                    </div>
                </div>
                <i class="fa-solid fa-trophy absolute -right-3 -bottom-3 text-6xl text-white/10"></i>
            </div>

            <!-- Total Produk Terhubung -->
            <div
                class="bg-gradient-to-br from-[#081d34] via-[#0d2a4a] to-[#00a8b5] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-teal-200 uppercase tracking-widest">Produk Terhubung</p>
                        <h3 class="text-2xl font-serif font-bold text-inv-mint mt-1">
                            {{ number_format($total_mapped_products) }} <span
                                class="text-xs font-normal text-slate-300">Item</span>
                        </h3>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur-md text-inv-mint flex items-center justify-center text-lg">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                </div>
                <i class="fa-solid fa-cubes absolute -right-3 -bottom-3 text-6xl text-white/10"></i>
            </div>
        </div>

        <!-- 3. FILTER & SEARCH BAR -->
        <div class="bg-slate-200/60 backdrop-blur-md p-4 rounded-2xl border border-slate-300/80">
            <form method="GET" action="{{ route('suppliers.index') }}"
                class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                <div class="relative sm:col-span-8">
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari supplier, no. telepon, atau alamat..."
                        class="w-full bg-slate-100 border border-slate-300 rounded-xl pl-9 pr-8 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-inv-teal transition-colors">
                    @if (request('search'))
                        <a href="{{ route('suppliers.index') }}"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </a>
                    @endif
                </div>

                <div class="sm:col-span-4 flex items-center justify-end gap-2">
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

        <!-- 4. TABEL SUPPLIER -->
        <div class="bg-slate-200/60 backdrop-blur-md rounded-2xl border border-slate-300/80 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-max">
                    <thead class="bg-slate-300/60 border-b border-slate-300">
                        <tr>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Nama
                                Perusahaan</th>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Dibuat
                                Oleh</th>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Kontak &
                                WA</th>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Alamat
                                Kantor</th>
                            <th
                                class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest text-center">
                                Produk Pasokan</th>
                            <th
                                class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest text-center">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-300/60">
                        @forelse($suppliers as $supplier)
                            <tr class="hover:bg-slate-300/40 transition-colors">
                                <!-- Nama Perusahaan -->
                                <td class="px-5 py-3.5">
                                    <span class="text-xs font-bold text-slate-800 block">{{ $supplier->name }}</span>
                                </td>

                                <!-- Dibuat Oleh (User & Role Badge) -->
                                <td class="px-5 py-3.5 text-xs">
                                    <p class="font-bold text-slate-700">{{ $supplier->creator->name ?? 'System' }}</p>
                                    <div class="flex gap-1 mt-0.5">
                                        @if ($supplier->creator)
                                            @foreach ($supplier->creator->getRoleNames() as $role)
                                                <span
                                                    class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-slate-300 text-slate-700 uppercase">
                                                    {{ $role }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span
                                                class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-slate-300 text-slate-500 uppercase">N/A</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Kontak & WhatsApp Direct Button -->
                                <td class="px-5 py-3.5 text-xs text-slate-600 font-medium">
                                    @if ($supplier->telp)
                                        @php
                                            $cleanTelp = preg_replace('/[^0-9]/', '', $supplier->telp);
                                            if (str_starts_with($cleanTelp, '0')) {
                                                $cleanTelp = '62' . substr($cleanTelp, 1);
                                            }
                                        @endphp
                                        <div class="flex items-center gap-2">
                                            <span class="text-slate-700 font-mono">{{ $supplier->telp }}</span>
                                            <a href="https://wa.me/{{ $cleanTelp }}" target="_blank"
                                                class="inline-flex items-center gap-1 bg-emerald-500/10 text-emerald-600 border border-emerald-500/30 px-2 py-0.5 rounded-md text-[10px] font-bold hover:bg-emerald-600 hover:text-white transition"
                                                title="Chat via WhatsApp">
                                                <i class="fa-brands fa-whatsapp text-xs"></i> Chat
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic text-[11px]">-</span>
                                    @endif
                                </td>

                                <!-- Alamat -->
                                <td class="px-5 py-3.5 text-xs text-slate-600 truncate max-w-xs">
                                    {{ $supplier->address ?: '-' }}
                                </td>

                                <!-- Jumlah Produk Pasokan -->
                                <td class="px-5 py-3.5 text-center">
                                    <span
                                        class="bg-inv-primary/10 text-inv-primary border border-inv-primary/20 px-2.5 py-0.5 rounded-md text-xs font-bold">
                                        {{ number_format($supplier->products_count) }} Item
                                    </span>
                                </td>

                                <!-- Aksi -->
                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex justify-center items-center gap-1.5">
                                        <button
                                            onclick="openModal('edit', {{ $supplier->id }}, '{{ addslashes($supplier->name) }}', '{{ $supplier->telp }}', '{{ addslashes($supplier->address) }}')"
                                            class="p-1.5 text-amber-600 hover:bg-slate-300 rounded-lg transition text-xs cursor-pointer"
                                            title="Edit Supplier">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus supplier {{ addslashes($supplier->name) }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 text-rose-600 hover:bg-slate-300 rounded-lg transition text-xs cursor-pointer"
                                                title="Hapus Supplier">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-truck-field text-3xl mb-2 opacity-30"></i>
                                    <p class="text-xs italic">Belum ada data supplier yang tersimpan untuk role Anda.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (request('per_page') != 'all')
                <div class="px-5 py-3 bg-slate-300/40 border-t border-slate-300">
                    {{ $suppliers->links() }}
                </div>
            @endif
        </div>

        <!-- 5. MODAL FORM SUPPLIER -->
        <div id="modalSupplier"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
            <div
                class="bg-slate-100 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-300 transform transition-all">
                <div
                    class="bg-gradient-to-r from-inv-teal to-inv-primary p-5 text-white flex justify-between items-center">
                    <h3 id="modalTitle" class="font-serif font-bold text-base">Tambah Supplier Baru</h3>
                    <button onclick="closeModal()" class="text-white/70 hover:text-white transition cursor-pointer">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form id="supplierForm" method="POST" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                            Nama Perusahaan / Supplier <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="sup_name" required
                            placeholder="Contoh: PT. Arindama Andra Tech"
                            class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-inv-teal">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                            No. Telepon / WhatsApp <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="telp" id="sup_telp"
                            placeholder="Isi strip (-) jika tidak ada nomor telepon"
                            class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-inv-teal">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                            Alamat Kantor <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="address" id="sup_address" rows="3" placeholder="Isi strip (-) jika tidak ada alamat"
                            class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-inv-teal"></textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-inv-teal to-inv-primary hover:from-inv-hover hover:to-inv-hover text-white font-bold py-3 rounded-xl shadow-md text-xs tracking-wider uppercase cursor-pointer mt-2">
                        Simpan Data Supplier
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

        function openModal(mode, id = null, name = '', telp = '', address = '') {
            const modal = document.getElementById('modalSupplier');
            const form = document.getElementById('supplierForm');
            const method = document.getElementById('formMethod');
            const title = document.getElementById('modalTitle');

            modal.classList.remove('hidden');

            if (mode === 'edit') {
                title.innerText = 'Edit Data Supplier';
                form.action = `/suppliers/${id}`;
                method.value = 'PUT';
                document.getElementById('sup_name').value = name;
                document.getElementById('sup_telp').value = telp;
                document.getElementById('sup_address').value = address;
            } else {
                title.innerText = 'Tambah Supplier Baru';
                form.action = "{{ route('suppliers.store') }}";
                method.value = 'POST';
                form.reset();
            }
        }

        function closeModal() {
            document.getElementById('modalSupplier').classList.add('hidden');
        }

        window.onclick = function(event) {
            const modal = document.getElementById('modalSupplier');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
@endsection
