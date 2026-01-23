@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-white">Daftar Supplier</h2>
                <p class="text-sm text-blue-100/70 font-acme uppercase tracking-widest mt-1">Kelola Rekanan Pemasok Barang
                </p>
            </div>
            <button onclick="openModal('add')"
                class="bg-white hover:bg-blue-100/70 text-blue-700 px-6 py-3 rounded-2xl shadow-lg transition flex items-center gap-2 font-bold text-sm">
                <i class="fa-solid fa-truck-field"></i> Tambah Supplier
            </button>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Perusahaan
                        </th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kontak</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Alamat</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($suppliers as $supplier)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-slate-800">{{ $supplier->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $supplier->telp ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500 truncate max-w-xs">
                                {{ $supplier->address ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <button
                                        onclick="openModal('edit', {{ $supplier->id }}, '{{ $supplier->name }}', '{{ $supplier->telp }}', '{{ $supplier->address }}')"
                                        class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition flex items-center justify-center">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </button>
                                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus supplier ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition flex items-center justify-center">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic text-sm">Belum ada data
                                supplier.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 bg-slate-50">{{ $suppliers->links() }}</div>
        </div>
    </div>

    <div id="modalSupplier"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="bg-arindama p-6 text-white flex justify-between items-center">
                <h3 id="modalTitle" class="font-bold text-lg font-acme">Tambah Supplier</h3>
                <button onclick="closeModal()" class="text-white/80 hover:text-white transition"><i
                        class="fa-solid fa-circle-xmark text-xl"></i></button>
            </div>

            <form id="supplierForm" method="POST" class="p-8 space-y-5">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Supplier /
                        Perusahaan</label>
                    <input type="text" name="name" id="sup_name" required placeholder="Contoh: PT. Arindama Jaya"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-arindama outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">No. Telepon /
                        WhatsApp</label>
                    <input type="text" name="telp" id="sup_telp" placeholder="0812..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-arindama outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Alamat
                        Kantor</label>
                    <textarea name="address" id="sup_address" rows="3" placeholder="Alamat lengkap..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-arindama outline-none"></textarea>
                </div>
                <button type="submit"
                    class="w-full bg-arindama text-white font-bold py-4 rounded-xl shadow-lg hover:bg-blue-700 transition uppercase text-[10px] tracking-widest">
                    Simpan Supplier
                </button>
            </form>
        </div>
    </div>

    <script>
        function openModal(mode, id = null, name = '', telp = '', address = '') {
            const modal = document.getElementById('modalSupplier');
            const form = document.getElementById('supplierForm');
            const method = document.getElementById('formMethod');
            const title = document.getElementById('modalTitle');

            modal.classList.remove('hidden');
            if (mode === 'edit') {
                title.innerText = 'Edit Supplier';
                form.action = `/suppliers/${id}`;
                method.value = 'PUT';
                document.getElementById('sup_name').value = name;
                document.getElementById('telp').value = telp;
                document.getElementById('sup_address').value = address;
            } else {
                title.innerText = 'Tambah Supplier Baru';
                form.action = "{{ route('suppliers.store') }}";
                method.value = 'POST';
                form.reset();
            }
        }
        function closeModal() { document.getElementById('modalSupplier').classList.add('hidden'); }
    </script>
@endsection