@extends('layouts.app')

@section('content')
    <div class="space-y-6" x-data="categoryApp()">

        <!-- 1. HEADER & ACTION BAR -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl lg:text-2xl font-serif font-bold text-slate-800">Kategori Produk</h2>
                <p class="text-xs text-slate-500 mt-1">Pengelompokan inventaris dan kontrol otorisasi role</p>
            </div>

            @if (Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('superadmin'))
                <button @click="openCategoryModal('add')"
                    class="bg-gradient-to-r from-inv-teal to-inv-primary hover:from-inv-hover hover:to-inv-hover text-white px-5 py-3 rounded-2xl shadow-lg shadow-inv-teal/20 transition-all flex items-center justify-center gap-2.5 font-bold text-xs tracking-wide cursor-pointer">
                    <i class="fa-solid fa-plus text-sm"></i>
                    <span>Tambah Kategori Baru</span>
                </button>
            @endif
        </div>

        <!-- 2. MINI ANALYTICS BAR (3 CARDS BERWARNA TEMA INV) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            <!-- Card Total Kategori (Tema Teal & Mint Gradient) -->
            <div
                class="bg-gradient-to-br from-[#00a8b5] to-[#2dd4bf] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-teal-100 uppercase tracking-widest">Total Kategori</p>
                        <h3 class="text-2xl font-serif font-bold text-white mt-1">{{ $total_categories }}</h3>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg shadow-sm">
                        <i class="fa-solid fa-tags"></i>
                    </div>
                </div>
                <i
                    class="fa-solid fa-folder-tree absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
            </div>

            <!-- Card Top Category (Tema Primary & Box Blue Gradient) -->
            <div
                class="bg-gradient-to-br from-[#0c66c8] to-[#2563eb] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-blue-200 uppercase tracking-widest">Kategori Terbanyak</p>
                        <h3 class="text-base font-serif font-bold text-white mt-1 truncate max-w-[160px]">
                            {{ $top_category_name }}</h3>
                        <p
                            class="text-[10px] text-blue-100 font-semibold bg-white/15 px-2 py-0.5 rounded-md w-max mt-1 backdrop-blur-sm">
                            {{ $top_category_count }} Jenis Produk
                        </p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg shadow-sm">
                        <i class="fa-solid fa-trophy"></i>
                    </div>
                </div>
                <i
                    class="fa-solid fa-crown absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
            </div>

            <!-- Card Terkunci/Restricted (Tema Deep Navy & Teal Tint Gradient) -->
            <div
                class="bg-gradient-to-br from-[#081d34] via-[#0d2a4a] to-[#00a8b5] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-teal-200 uppercase tracking-widest">Restricted Role</p>
                        <h3 class="text-2xl font-serif font-bold text-inv-mint mt-1">{{ $restricted_count }}</h3>
                        <p class="text-[10px] text-slate-300">Kategori terbatas</p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur-md text-inv-mint flex items-center justify-center text-lg shadow-sm">
                        <i class="fa-solid fa-user-lock"></i>
                    </div>
                </div>
                <i
                    class="fa-solid fa-shield-halved absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
            </div>
        </div>

        <!-- 3. FILTER & SEARCH BAR -->
        <div
            class="bg-slate-200/60 backdrop-blur-md p-3 rounded-2xl border border-slate-300/80 flex flex-col sm:flex-row items-center justify-between gap-3">
            <!-- Live Search Bar -->
            <div class="relative w-full sm:w-80">
                <i
                    class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" x-model="searchQuery" placeholder="Cari nama kategori..."
                    class="w-full bg-slate-100 border border-slate-300 rounded-xl pl-9 pr-4 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-inv-teal transition-colors">
            </div>

            <!-- Filter Tab -->
            <div class="flex items-center gap-1.5 w-full sm:w-auto overflow-x-auto text-xs">
                <button @click="filterTab = 'all'"
                    :class="filterTab === 'all' ? 'bg-inv-teal text-white font-semibold' :
                        'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-3 py-1.5 rounded-xl transition-colors cursor-pointer whitespace-nowrap">
                    Semua ({{ $total_categories }})
                </button>
                <button @click="filterTab = 'my_access'"
                    :class="filterTab === 'my_access' ? 'bg-inv-teal text-white font-semibold' :
                        'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-3 py-1.5 rounded-xl transition-colors cursor-pointer whitespace-nowrap">
                    Akses Saya
                </button>
                <button @click="filterTab = 'read_only'"
                    :class="filterTab === 'read_only' ? 'bg-inv-teal text-white font-semibold' :
                        'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-3 py-1.5 rounded-xl transition-colors cursor-pointer whitespace-nowrap">
                    Read-Only / Terkunci
                </button>
            </div>
        </div>

        <!-- 4. GRID CARDS KATEGORI -->
        <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-5">
            @forelse($categories as $category)
                @php
                    $canManage = \App\Services\CategoryService::canUserManage($category);
                    $rolesList = $category->allowed_roles ?? [];
                @endphp

                <div x-show="filterCategory('{{ strtolower(addslashes($category->name)) }}', {{ $canManage ? 'true' : 'false' }})"
                    onclick="openProductsModal(this, '{{ addslashes($category->name) }}')"
                    data-products="{{ json_encode($category->products) }}"
                    class="bg-slate-200/60 backdrop-blur-md p-5 rounded-2xl border transition-all duration-300 group cursor-pointer relative overflow-hidden flex flex-col justify-between min-h-[190px]
                       {{ $canManage ? 'border-slate-300/80 hover:border-inv-teal hover:shadow-lg' : 'border-slate-300/40 bg-slate-200/30 opacity-80' }}">

                    <div>
                        <!-- Card Top Header -->
                        <div class="flex items-center justify-between mb-3 relative z-10">
                            <div
                                class="w-10 h-10 rounded-xl flex items-center justify-center text-lg transition-transform group-hover:scale-105
                                    {{ $canManage ? 'bg-inv-teal/15 text-inv-teal border border-inv-teal/30' : 'bg-slate-300/60 text-slate-500' }}">
                                <i class="fa-solid {{ $canManage ? 'fa-folder-open' : 'fa-folder-closed' }}"></i>
                            </div>

                            <!-- Action Buttons (Edit/Hapus) - Khusus User dengan Akses Kelola -->
                            @if ($canManage)
                                <div
                                    class="flex items-center gap-1 bg-slate-100/90 rounded-lg p-1 border border-slate-300 opacity-0 group-hover:opacity-100 transition-opacity shadow-sm">
                                    <button
                                        onclick="event.stopPropagation(); openCategoryModal('edit', {{ $category->id }}, '{{ addslashes($category->name) }}', {{ json_encode($rolesList) }})"
                                        class="p-1.5 text-amber-600 hover:bg-slate-200 rounded-md transition cursor-pointer"
                                        title="Edit Kategori">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                        onclick="event.stopPropagation();">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus kategori ini?')"
                                            class="p-1.5 text-rose-600 hover:bg-slate-200 rounded-md transition cursor-pointer"
                                            title="Hapus Kategori">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <!-- Badge Lock untuk Non-Akses -->
                                <span
                                    class="text-[10px] font-bold text-slate-500 bg-slate-300/60 px-2 py-0.5 rounded-full border border-slate-400/30 flex items-center gap-1">
                                    <i class="fa-solid fa-lock text-[9px]"></i> Read Only
                                </span>
                            @endif
                        </div>

                        <!-- Category Name & Total Products -->
                        <h3 class="font-serif font-bold text-slate-800 text-base mb-1 relative z-10">{{ $category->name }}
                        </h3>
                        <p
                            class="text-[11px] font-semibold text-slate-500 bg-slate-100/80 px-2.5 py-0.5 rounded-md w-max relative z-10">
                            {{ $category->products_count }} Jenis Produk
                        </p>
                    </div>

                    <!-- Footer Card: Roles Allowed Badges -->
                    <div
                        class="mt-4 pt-3 border-t border-slate-300/60 flex items-center justify-between text-[10px] relative z-10">
                        <span class="text-slate-400">Akses Role:</span>
                        <div class="flex items-center gap-1 flex-wrap justify-end">
                            @if (empty($rolesList))
                                <span
                                    class="text-emerald-700 bg-emerald-500/10 px-2 py-0.5 rounded-full font-bold">Public</span>
                            @else
                                @foreach (array_slice($rolesList, 0, 2) as $r)
                                    <span
                                        class="text-inv-primary bg-inv-primary/10 px-2 py-0.5 rounded-full font-bold">{{ $r }}</span>
                                @endforeach
                                @if (count($rolesList) > 2)
                                    <span class="text-slate-500 font-bold">+{{ count($rolesList) - 2 }}</span>
                                @endif
                            @endif
                        </div>
                    </div>

                </div>
            @empty
                <div
                    class="col-span-full bg-slate-200/60 border-2 border-dashed border-slate-300 rounded-2xl p-12 text-center text-slate-400">
                    <i class="fa-solid fa-tags text-4xl mb-3 opacity-30"></i>
                    <p class="text-xs italic">Belum ada kategori terdaftar.</p>
                </div>
            @endforelse
        </div>

        <!-- 5. MODAL FORM TAMBAH/EDIT KATEGORI & ROLES -->
        <div id="modalCategory"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
            <div
                class="bg-slate-100 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-300 transform transition-all">

                <div class="bg-gradient-to-r from-inv-teal to-inv-primary p-5 text-white flex justify-between items-center">
                    <h3 id="modalTitle" class="font-serif font-bold text-base tracking-wide">Tambah Kategori</h3>
                    <button onclick="closeModal()" class="text-white/70 hover:text-white transition cursor-pointer">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form id="categoryForm" method="POST" class="p-6 space-y-5">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <!-- Input Nama Kategori -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-2">Nama
                            Kategori</label>
                        <input type="text" name="name" id="cat_name" required placeholder="Misal: Perangkat IT"
                            class="w-full bg-white border border-slate-300 rounded-xl px-4 py-3 text-xs text-slate-800 outline-none focus:border-inv-teal transition-all">
                    </div>

                    <!-- Checkbox Roles Allowed -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Role yang
                            Diizinkan Mengelola</label>
                        <p class="text-[10px] text-slate-400 mb-3">Jika tidak dipilih, kategori dapat dilihat oleh semua
                            role (Read-Only).</p>

                        <div class="grid grid-cols-2 gap-2 max-h-36 overflow-y-auto custom-scrollbar p-1">
                            @foreach ($all_roles as $role)
                                <label
                                    class="flex items-center gap-2 p-2 bg-white rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition-colors">
                                    <input type="checkbox" name="allowed_roles[]" value="{{ $role }}"
                                        class="role-checkbox rounded border-slate-300 text-inv-teal focus:ring-inv-teal">
                                    <span class="text-xs font-semibold text-slate-700">{{ $role }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-inv-teal to-inv-primary hover:from-inv-hover hover:to-inv-hover text-white font-bold py-3.5 rounded-xl shadow-lg shadow-inv-teal/20 transition-all text-xs tracking-wider uppercase cursor-pointer">
                        Simpan Kategori
                    </button>
                </form>
            </div>
        </div>

        <!-- 6. MODAL DAFTAR PRODUK (KONTEN ISINYA) -->
        <div id="modalProductsList"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
            <div
                class="bg-slate-100 rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden border border-slate-300 flex flex-col max-h-[85vh]">

                <div
                    class="bg-gradient-to-r from-inv-teal to-inv-primary p-5 text-white flex justify-between items-center shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-boxes-stacked text-base"></i>
                        </div>
                        <div>
                            <h3 class="font-serif font-bold text-base leading-none">Daftar Produk</h3>
                            <p id="modalProductsTitle" class="text-xs text-inv-mint font-medium mt-1">Memuat...</p>
                        </div>
                    </div>
                    <button onclick="closeProductsModal()"
                        class="text-white/70 hover:text-white transition cursor-pointer">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="p-5 overflow-y-auto custom-scrollbar bg-slate-50 flex-1">
                    <table
                        class="w-full text-left border-collapse bg-white rounded-xl overflow-hidden border border-slate-200">
                        <thead class="bg-slate-100 border-b border-slate-200">
                            <tr>
                                <th
                                    class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider w-12 text-center">
                                    No</th>
                                <th class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Nama
                                    Produk</th>
                                <th
                                    class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right">
                                    Stok Tersedia</th>
                            </tr>
                        </thead>
                        <tbody id="modalProductsBody" class="divide-y divide-slate-100">
                            <!-- Injected via JS -->
                        </tbody>
                    </table>
                </div>

                <div class="p-3 bg-white border-t border-slate-200 text-center shrink-0">
                    <button onclick="closeProductsModal()"
                        class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold py-2 px-6 rounded-xl transition-all text-xs cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- SCRIPT LOGIKA PAGE KATEGORI -->
    <script>
        function categoryApp() {
            return {
                searchQuery: '',
                filterTab: 'all',
                filterCategory(name, canManage) {
                    const matchSearch = name.includes(this.searchQuery.toLowerCase());
                    if (this.filterTab === 'my_access') return matchSearch && canManage;
                    if (this.filterTab === 'read_only') return matchSearch && !canManage;
                    return matchSearch;
                }
            }
        }

        // Modal Add/Edit
        function openCategoryModal(mode, id = null, name = '', roles = []) {
            const modal = document.getElementById('modalCategory');
            const form = document.getElementById('categoryForm');
            const method = document.getElementById('formMethod');
            const title = document.getElementById('modalTitle');
            const checkboxes = document.querySelectorAll('.role-checkbox');

            checkboxes.forEach(cb => cb.checked = false);

            modal.classList.remove('hidden');
            if (mode === 'edit') {
                title.innerText = 'Edit Kategori';
                form.action = `/categories/${id}`;
                method.value = 'PUT';
                document.getElementById('cat_name').value = name;

                checkboxes.forEach(cb => {
                    if (roles.includes(cb.value)) cb.checked = true;
                });
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

        // Modal Products List
        function openProductsModal(element, categoryName) {
            const modal = document.getElementById('modalProductsList');
            const title = document.getElementById('modalProductsTitle');
            const tbody = document.getElementById('modalProductsBody');

            const productsData = element.getAttribute('data-products');
            let products = [];

            try {
                products = JSON.parse(productsData);
            } catch (e) {
                console.error("Error parsing JSON", e);
            }

            title.innerText = categoryName;
            tbody.innerHTML = '';

            if (products.length === 0) {
                tbody.innerHTML = `
                <tr>
                    <td colspan="3" class="px-4 py-10 text-center text-slate-400">
                        <i class="fa-solid fa-boxes-stacked text-3xl mb-2 opacity-30"></i>
                        <p class="text-xs italic">Belum ada produk di kategori ini.</p>
                    </td>
                </tr>
            `;
            } else {
                products.forEach((p, idx) => {
                    const stockClass = p.quantity <= 5 ? 'text-rose-600 font-bold' : 'text-slate-800 font-bold';
                    tbody.innerHTML += `
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 text-xs text-slate-500 text-center font-medium">${idx + 1}</td>
                        <td class="px-4 py-3">
                            <p class="text-xs font-bold text-slate-800">${p.name}</p>
                            <p class="text-[10px] text-slate-400 font-mono">${p.slug ?? '-'}</p>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <p class="text-xs ${stockClass}">${Number(p.quantity).toLocaleString('id-ID')}</p>
                            <p class="text-[9px] text-slate-400 uppercase font-semibold">${p.unit ?? 'Unit'}</p>
                        </td>
                    </tr>
                `;
                });
            }

            modal.classList.remove('hidden');
        }

        function closeProductsModal() {
            document.getElementById('modalProductsList').classList.add('hidden');
        }
    </script>
@endsection
