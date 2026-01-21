@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Kategori Produk</h2>
                <p class="text-sm text-slate-500 font-domine uppercase tracking-widest mt-1">Pengelompokan Inventaris</p>
            </div>
            <button onclick="openCategoryModal('add')"
                class="bg-arindama hover:bg-blue-700 text-white px-6 py-3 rounded-2xl shadow-lg transition flex items-center gap-2 font-bold text-sm">
                <i class="fa-solid fa-tags"></i> Tambah Kategori
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($categories as $category)
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 bg-blue-50 text-arindama rounded-2xl flex items-center justify-center text-xl">
                            <i class="fa-solid fa-folder-open"></i>
                        </div>
                        <div class="flex gap-1">
                            <button onclick="openCategoryModal('edit', {{ $category->id }}, '{{ $category->name }}')"
                                class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus kategori ini?')"
                                    class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg">{{ $category->name }}</h3>
                    <p class="text-xs text-slate-400 font-domine mt-1">{{ $category->products_count }} Produk Terkait</p>
                </div>
            @empty
                <div class="col-span-3 bg-slate-50 border-2 border-dashed border-slate-200 rounded-3xl p-12 text-center">
                    <p class="text-slate-400 italic">Belum ada kategori. Silahkan tambah kategori baru.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div id="modalCategory"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all">
            <div class="bg-arindama p-6 text-white flex justify-between items-center">
                <h3 id="modalTitle" class="font-bold text-lg font-domine">Tambah Kategori</h3>
                <button onclick="closeModal()" class="text-white/80 hover:text-white"><i
                        class="fa-solid fa-circle-xmark text-xl"></i></button>
            </div>
            <form id="categoryForm" method="POST" class="p-8 space-y-5">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama
                        Kategori</label>
                    <input type="text" name="name" id="cat_name" required placeholder="Misal: Elektronik"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-arindama outline-none">
                </div>
                <button type="submit"
                    class="w-full bg-arindama text-white font-bold py-4 rounded-xl shadow-lg uppercase text-[10px] tracking-widest">
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
        function closeModal() { document.getElementById('modalCategory').classList.add('hidden'); }
    </script>
@endsection