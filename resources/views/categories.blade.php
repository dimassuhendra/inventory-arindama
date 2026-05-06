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
                <div
                    class="bg-white p-6 rounded-2xl border border-accent/30 shadow-sm hover:shadow-md hover:border-secondary/50 transition-all group">
                    <div class="flex justify-between items-start mb-5">
                        <div
                            class="w-12 h-12 bg-primary/10 text-primary border border-accent/30 rounded-xl flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-folder-open"></i>
                        </div>
                        <div
                            class="flex gap-1 bg-gray-50 rounded-lg p-1 border border-gray-100 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button
                                onclick="openCategoryModal('edit', {{ $category->id }}, '{{ addslashes($category->name) }}')"
                                class="p-2 text-amber-500 hover:bg-white hover:shadow-sm rounded-md transition">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </button>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Hapus kategori ini? Semua produk terkait mungkin akan terpengaruh.')"
                                    class="p-2 text-red-500 hover:bg-white hover:shadow-sm rounded-md transition">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg mb-1">{{ $category->name }}</h3>
                    <p class="text-[11px] text-gray-400 font-medium bg-gray-50 px-3 py-1 rounded-full w-fit">
                        {{ $category->products_count }} Produk Terkait
                    </p>
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

    <!-- Modal Form -->
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

    <script>
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
    </script>
@endsection
