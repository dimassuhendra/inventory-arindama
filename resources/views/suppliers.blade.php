@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-2xl lg:text-3xl font-bold text-primary font-serif">Daftar Supplier</h2>
                <p class="text-xs text-secondary font-sans uppercase tracking-widest mt-2 font-semibold">Kelola Rekanan
                    Pemasok Barang</p>
            </div>
            <button onclick="openModal('add')"
                class="bg-primary hover:bg-secondary text-white px-6 py-3.5 rounded-xl shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-3 font-bold text-sm tracking-wide">
                <i class="fa-solid fa-truck-field"></i> Tambah Supplier
            </button>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-accent/30 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-primary/5 border-b border-accent/20">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold text-primary uppercase tracking-widest">Nama Perusahaan
                        </th>
                        <th class="px-6 py-4 text-[10px] font-bold text-primary uppercase tracking-widest">Kontak</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-primary uppercase tracking-widest">Alamat</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-primary uppercase tracking-widest text-center">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 font-sans">
                    @forelse($suppliers as $supplier)
                        <tr class="hover:bg-primary/5 transition duration-200">
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-800">{{ $supplier->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 font-medium">
                                <i
                                    class="fa-solid fa-phone text-[10px] text-gray-400 mr-1.5"></i>{{ $supplier->telp ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 truncate max-w-xs">
                                {{ $supplier->address ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <button
                                        onclick="openModal('edit', {{ $supplier->id }}, '{{ addslashes($supplier->name) }}', '{{ $supplier->telp }}', '{{ addslashes($supplier->address) }}')"
                                        class="w-9 h-9 rounded-xl bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition flex items-center justify-center shadow-sm">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </button>
                                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus supplier ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="w-9 h-9 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition flex items-center justify-center shadow-sm">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic text-sm">Belum ada data
                                supplier yang tersimpan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">{{ $suppliers->links() }}</div>
        </div>
    </div>

    <!-- Modal Form -->
    <div id="modalSupplier"
        class="fixed inset-0 bg-primary/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div
            class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all border border-accent/20">
            <div class="bg-primary p-6 text-white flex justify-between items-center">
                <h3 id="modalTitle" class="font-bold text-lg font-serif tracking-wide">Tambah Supplier</h3>
                <button onclick="closeModal()" class="text-accent hover:text-white transition transform hover:rotate-90">
                    <i class="fa-solid fa-circle-xmark text-2xl"></i>
                </button>
            </div>

            <!-- KODE BARU DI MODAL SUPPLIER -->
            <form id="supplierForm" action="{{ route('suppliers.store') }}" method="POST" class="p-8 space-y-5 font-sans">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value=""> <!-- Kosongkan nilai defaultnya -->

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Nama Supplier /
                        Perusahaan <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="sup_name" required
                        placeholder="Contoh: PT. Arindama Andra Tech"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">No. Telepon /
                        WhatsApp</label>
                    <input type="text" name="telp" id="sup_telp" placeholder="0812..."
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Alamat
                        Kantor</label>
                    <textarea name="address" id="sup_address" rows="3" placeholder="Alamat lengkap..."
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all"></textarea>
                </div>

                <button type="submit"
                    class="w-full bg-primary hover:bg-secondary text-white font-bold py-4 rounded-xl shadow-lg shadow-primary/20 transition-all uppercase text-xs tracking-widest mt-2">
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
                method.value = 'PUT'; // Mengirim _method = PUT untuk Update
                document.getElementById('sup_name').value = name;
                document.getElementById('sup_telp').value = telp;
                document.getElementById('sup_address').value = address;
            } else {
                title.innerText = 'Tambah Supplier Baru';
                form.action = "{{ route('suppliers.store') }}";
                method.value = ''; // KOSONGKAN _method untuk POST biasa (mencegah Error 419)
                form.reset();
            }
        }

        function closeModal() {
            document.getElementById('modalSupplier').classList.add('hidden');
        }

        // Tutup modal jika klik di luar box
        window.onclick = function(event) {
            const modal = document.getElementById('modalSupplier');
            if (event.target == modal) {
                closeModal();
            }
        }

        function closeModal() {
            document.getElementById('modalSupplier').classList.add('hidden');
        }
    </script>
@endsection
