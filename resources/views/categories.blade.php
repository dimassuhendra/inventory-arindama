@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <!-- 1. HEADER & ACTION BAR -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl lg:text-2xl font-serif font-bold text-slate-800">Kategori Produk</h2>
                <p class="text-xs text-slate-500 mt-1">Pengelompokan inventaris dan hierarki sub-kategori</p>
            </div>

            <button onclick="openCategoryModal('add')"
                class="bg-gradient-to-r from-inv-teal to-inv-primary hover:from-inv-hover hover:to-inv-hover text-white px-5 py-2.5 rounded-xl shadow-md transition-all flex items-center justify-center gap-2 font-bold text-xs cursor-pointer">
                <i class="fa-solid fa-plus text-sm"></i>
                <span>Tambah Kategori / Sub Kategori</span>
            </button>
        </div>

        <!-- 2. MINI ANALYTICS BAR (3 CARDS TEMA INV) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            <!-- Total Kategori Induk -->
            <div
                class="bg-gradient-to-br from-[#00a8b5] to-[#2dd4bf] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-teal-100 uppercase tracking-widest">Kategori Utama</p>
                        <h3 class="text-2xl font-serif font-bold text-white mt-1">{{ number_format($total_categories) }}
                        </h3>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg">
                        <i class="fa-solid fa-tags"></i>
                    </div>
                </div>
                <i class="fa-solid fa-folder-tree absolute -right-3 -bottom-3 text-6xl text-white/10"></i>
            </div>

            <!-- Kategori Terbanyak -->
            <div
                class="bg-gradient-to-br from-[#0c66c8] to-[#2563eb] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-blue-200 uppercase tracking-widest">Kategori Terbanyak</p>
                        <h3 class="text-base font-serif font-bold text-white mt-1 truncate max-w-[160px]">
                            {{ $top_category_name }}
                        </h3>
                        <p
                            class="text-[10px] text-blue-100 font-semibold bg-white/15 px-2 py-0.5 rounded-md w-max mt-1 backdrop-blur-sm">
                            {{ $top_category_count }} Jenis Produk
                        </p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg">
                        <i class="fa-solid fa-trophy"></i>
                    </div>
                </div>
                <i class="fa-solid fa-crown absolute -right-3 -bottom-3 text-6xl text-white/10"></i>
            </div>

            <!-- Total Sub Kategori -->
            <div
                class="bg-gradient-to-br from-[#081d34] via-[#0d2a4a] to-[#00a8b5] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-teal-200 uppercase tracking-widest">Total Sub-Kategori</p>
                        <h3 class="text-2xl font-serif font-bold text-inv-mint mt-1">
                            {{ number_format($total_sub_categories) }}</h3>
                        <p class="text-[10px] text-slate-300">Terdaftar di sistem</p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur-md text-inv-mint flex items-center justify-center text-lg">
                        <i class="fa-solid fa-sitemap"></i>
                    </div>
                </div>
                <i class="fa-solid fa-diagram-project absolute -right-3 -bottom-3 text-6xl text-white/10"></i>
            </div>
        </div>

        <!-- 3. SEARCH BAR -->
        <div class="bg-slate-200/60 backdrop-blur-md p-3 rounded-2xl border border-slate-300/80">
            <div class="relative w-full">
                <i
                    class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" id="categorySearchInput" onkeyup="searchCategories()"
                    placeholder="Cari nama kategori atau sub-kategori..."
                    class="w-full bg-slate-100 border border-slate-300 rounded-xl pl-9 pr-4 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-inv-teal transition-colors">
            </div>
        </div>

        <!-- 4. GRID CARDS KATEGORI & SUB KATEGORI -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5" id="categoryGrid">
            @forelse($categories as $category)
                <div class="category-card bg-slate-200/60 backdrop-blur-md p-5 rounded-2xl border border-slate-300/80 flex flex-col justify-between min-h-[220px] shadow-sm relative overflow-hidden"
                    data-name="{{ strtolower($category->name) }}">

                    <div>
                        <!-- Header Top Card -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-inv-teal/15 text-inv-teal border border-inv-teal/30 flex items-center justify-center text-lg">
                                    <i class="fa-solid fa-folder-open"></i>
                                </div>
                                <div>
                                    <h3 class="font-serif font-bold text-slate-800 text-base leading-tight">
                                        {{ $category->name }}</h3>
                                    <p class="text-[10px] text-slate-500 mt-0.5">Dibuat oleh: <strong
                                            class="text-slate-700">{{ $category->creator->name ?? 'System' }}</strong></p>
                                </div>
                            </div>

                            <!-- Action Edit/Delete Induk -->
                            <div class="flex items-center gap-1">
                                <button
                                    onclick="openCategoryModal('edit', {{ $category->id }}, '{{ addslashes($category->name) }}', null)"
                                    class="p-1.5 text-amber-600 hover:bg-slate-300 rounded-lg transition text-xs cursor-pointer"
                                    title="Edit Kategori">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus kategori ini beserta sub kategorinya?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="p-1.5 text-rose-600 hover:bg-slate-300 rounded-lg transition text-xs cursor-pointer"
                                        title="Hapus Kategori">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Sub Kategori Container -->
                        <div class="mt-3 pt-3 border-t border-slate-300/60 space-y-2">
                            <p
                                class="text-[10px] font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1">
                                <i class="fa-solid fa-turn-up rotate-90 text-slate-400"></i> Sub-Kategori
                                ({{ $category->children->count() }})
                            </p>

                            @if ($category->children->count() > 0)
                                <div class="grid grid-cols-1 gap-1.5 max-h-40 overflow-y-auto custom-scrollbar pr-1">
                                    @foreach ($category->children as $child)
                                        <div
                                            class="flex items-center justify-between bg-white/70 backdrop-blur-sm px-3 py-1.5 rounded-xl border border-slate-200 text-xs">
                                            <div class="flex items-center gap-2">
                                                <i class="fa-solid fa-layer-group text-[10px] text-inv-teal"></i>
                                                <span class="font-medium text-slate-700">{{ $child->name }}</span>
                                            </div>

                                            <div class="flex items-center gap-1">
                                                <button
                                                    onclick="openCategoryModal('edit', {{ $child->id }}, '{{ addslashes($child->name) }}', {{ $category->id }})"
                                                    class="text-amber-600 hover:text-amber-800 p-1 text-[11px]"
                                                    title="Edit Sub-Kategori">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <form action="{{ route('categories.destroy', $child->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus sub kategori {{ addslashes($child->name) }}?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="text-rose-600 hover:text-rose-800 p-1 text-[11px]"
                                                        title="Hapus Sub-Kategori">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-[11px] text-slate-400 italic">Belum ada sub-kategori.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Footer Badges -->
                    <div class="mt-4 pt-3 border-t border-slate-300/60 flex items-center justify-between text-[10px]">
                        <span class="text-slate-500 font-semibold">{{ $category->products_count }} Jenis Produk</span>
                        <div class="flex gap-1">
                            @if ($category->creator)
                                @foreach ($category->creator->getRoleNames() as $role)
                                    <span
                                        class="bg-slate-300 text-slate-700 px-2 py-0.5 rounded font-bold uppercase">{{ $role }}</span>
                                @endforeach
                            @endif
                        </div>
                    </div>

                </div>
            @empty
                <div
                    class="col-span-full bg-slate-200/60 border-2 border-dashed border-slate-300 rounded-2xl p-12 text-center text-slate-400">
                    <i class="fa-solid fa-tags text-4xl mb-3 opacity-30"></i>
                    <p class="text-xs italic">Belum ada kategori yang tersimpan untuk role Anda.</p>
                </div>
            @endforelse
        </div>

        <!-- 5. MODAL FORM CATEGORY & SUBCATEGORY -->
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

                <form id="categoryForm" method="POST" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <!-- Pilihan Kategori Induk -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                            Kategori Induk (Opsional)
                        </label>
                        <select name="parent_id" id="cat_parent"
                            class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-inv-teal">
                            <option value="">-- Tanpa Induk (Kategori Utama) --</option>
                            @foreach ($parent_categories as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-slate-400 mt-1">Pilih Induk jika kategori ini adalah **Sub-Kategori**.
                        </p>
                    </div>

                    <!-- Input Nama Kategori -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                            Nama Kategori / Sub-Kategori <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="cat_name" required
                            placeholder="Contoh: Router Mikrotik"
                            class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-inv-teal">
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-inv-teal to-inv-primary hover:from-inv-hover hover:to-inv-hover text-white font-bold py-3.5 rounded-xl shadow-md transition-all text-xs tracking-wider uppercase cursor-pointer mt-2">
                        Simpan Kategori
                    </button>
                </form>
            </div>
        </div>

    </div>

    <!-- SCRIPTS -->
    <script>
        function searchCategories() {
            const query = document.getElementById('categorySearchInput').value.toLowerCase();
            const cards = document.querySelectorAll('.category-card');

            cards.forEach(card => {
                const name = card.getAttribute('data-name');
                if (name.includes(query)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function openCategoryModal(mode, id = null, name = '', parentId = null) {
            const modal = document.getElementById('modalCategory');
            const form = document.getElementById('categoryForm');
            const method = document.getElementById('formMethod');
            const title = document.getElementById('modalTitle');

            modal.classList.remove('hidden');

            if (mode === 'edit') {
                title.innerText = 'Edit Data Kategori';
                form.action = `/categories/${id}`;
                method.value = 'PUT';
                document.getElementById('cat_name').value = name;
                document.getElementById('cat_parent').value = parentId || '';
            } else {
                title.innerText = 'Tambah Kategori Baru';
                form.action = "{{ route('categories.store') }}";
                method.value = 'POST';
                form.reset();
            }
        }

        function closeModal() {
            document.getElementById('modalCategory').classList.add('hidden');
        }

        window.onclick = function(event) {
            const modal = document.getElementById('modalCategory');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
@endsection
