@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header & Action Buttons -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-xl lg:text-2xl font-serif font-bold text-slate-800">Master Data Departemen & Perusahaan</h2>
                <p class="text-xs text-slate-500 mt-1">Kelola Katalog Master Departemen & Entitas Perusahaan Group</p>
            </div>
            <div class="flex items-center gap-2">
                <!-- Tombol Kelola Perusahaan (Buka Modal Manager Perusahaan) -->
                <button onclick="openCompanyManagerModal()"
                    class="bg-slate-700 hover:bg-slate-800 text-white px-3.5 py-2.5 rounded-xl shadow-md transition-all flex items-center gap-2 font-bold text-xs cursor-pointer">
                    <i class="fa-solid fa-building"></i> Kelola Perusahaan
                </button>

                <!-- Tombol Tambah Departemen -->
                <button onclick="openDepartmentModal('add')"
                    class="bg-gradient-to-r from-inv-teal to-inv-primary hover:from-inv-hover hover:to-inv-hover text-white px-4 py-2.5 rounded-xl shadow-md transition-all flex items-center gap-2 font-bold text-xs cursor-pointer">
                    <i class="fa-solid fa-plus"></i> Tambah Departemen
                </button>
            </div>
        </div>

        <!-- Search & Filter Bar -->
        <div class="bg-slate-200/60 backdrop-blur-md p-4 rounded-2xl border border-slate-300/80">
            <form method="GET" action="{{ route('departments.index') }}"
                class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                <div class="relative sm:col-span-6">
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama atau kode departemen..."
                        class="w-full bg-slate-100 border border-slate-300 rounded-xl pl-9 pr-8 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-inv-teal">
                </div>

                <div class="sm:col-span-4">
                    <select name="company_id" onchange="this.form.submit()"
                        class="w-full bg-slate-100 border border-slate-300 text-slate-700 text-xs rounded-xl p-2 outline-none focus:border-inv-teal">
                        <option value="">-- Semua Perusahaan --</option>
                        @foreach ($companies as $comp)
                            <option value="{{ $comp->id }}" {{ request('company_id') == $comp->id ? 'selected' : '' }}>
                                {{ $comp->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2 flex items-center justify-end">
                    <span class="text-xs text-slate-500">Total: <strong
                            class="text-slate-800">{{ $total_departments }}</strong></span>
                </div>
            </form>
        </div>

        <!-- Tabel Departemen -->
        <div class="bg-slate-200/60 backdrop-blur-md rounded-2xl border border-slate-300/80 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-max">
                    <thead class="bg-slate-300/60 border-b border-slate-300">
                        <tr>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Kode &
                                Departemen</th>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Digunakan
                                Perusahaan</th>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">
                                Keterangan</th>
                            <th
                                class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest text-center">
                                Jumlah PIC</th>
                            <th
                                class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest text-center">
                                Total Aset</th>
                            <th
                                class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest text-center">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-300/60 text-xs">
                        @forelse($departments as $dept)
                            <tr class="hover:bg-slate-300/40 transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <span
                                            class="bg-inv-teal/10 text-inv-teal font-mono font-bold text-[10px] px-2 py-0.5 rounded-md border border-inv-teal/20">
                                            {{ $dept->code ?? 'N/A' }}
                                        </span>
                                        <span class="font-bold text-slate-800">{{ $dept->name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                        @forelse($dept->companies as $comp)
                                            <span
                                                class="text-[9px] font-bold text-slate-700 bg-slate-300 px-2 py-0.5 rounded-md uppercase">
                                                {{ $comp->name }}
                                            </span>
                                        @empty
                                            <span class="text-[10px] text-slate-400 italic">Belum terasosiasi</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-slate-600">{{ $dept->description ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="bg-slate-300 text-slate-700 font-bold px-2.5 py-1 rounded-lg text-[10px]">
                                        <i class="fa-solid fa-users text-[9px] mr-1"></i> {{ $dept->pics_count }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span
                                        class="bg-emerald-500/10 text-emerald-700 border border-emerald-500/20 font-bold px-2.5 py-1 rounded-lg text-[10px]">
                                        <i class="fa-solid fa-boxes-stacked text-[9px] mr-1"></i>
                                        {{ $dept->products_count }} Unit
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex justify-center items-center gap-1.5">
                                        <button onclick='openDepartmentModal("edit", @json($dept))'
                                            class="p-1.5 text-amber-600 hover:bg-slate-300 rounded-lg transition cursor-pointer"
                                            title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <form action="{{ route('departments.destroy', $dept->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus departemen ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 text-rose-600 hover:bg-slate-300 rounded-lg transition cursor-pointer"
                                                title="Hapus">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-400 italic">Data departemen tidak
                                    ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-3 bg-slate-300/40 border-t border-slate-300">
                {{ $departments->links() }}
            </div>
        </div>
    </div>

    <!-- 🏢 MODAL MANAGER PERUSAHAAN (CRUD PERUSAHAAN) -->
    <div id="modalCompanyManager"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
        <div class="bg-slate-100 rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-slate-300">
            <div class="bg-slate-800 p-4 text-white flex justify-between items-center">
                <h3 class="font-serif font-bold text-sm flex items-center gap-2">
                    <i class="fa-solid fa-building"></i> Manajer Data Perusahaan Group
                </h3>
                <button onclick="closeCompanyManagerModal()" class="text-white/70 hover:text-white cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="p-5 space-y-4">
                <!-- Form Input / Edit Perusahaan -->
                <form id="companyForm" action="{{ route('companies.store') }}" method="POST"
                    class="bg-white p-3.5 rounded-2xl border border-slate-300 space-y-3">
                    @csrf
                    <input type="hidden" name="_method" id="companyFormMethod" value="POST">

                    <div class="flex items-center justify-between">
                        <span id="companyFormTitle" class="text-xs font-bold text-slate-700 uppercase">Tambah Perusahaan
                            Baru</span>
                        <button type="button" id="btnCancelCompanyEdit" onclick="resetCompanyForm()"
                            class="hidden text-[10px] text-rose-600 font-bold hover:underline cursor-pointer">Batal
                            Edit</button>
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <input type="text" name="code" id="comp_code" placeholder="Kode (mis: PTAG)"
                                class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-slate-600">
                        </div>
                        <div class="col-span-2">
                            <input type="text" name="name" id="comp_name" required placeholder="Nama Perusahaan *"
                                class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-slate-600">
                        </div>
                    </div>

                    <button type="submit" id="btnCompanySubmit"
                        class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-2 rounded-xl text-xs uppercase tracking-wider cursor-pointer transition">
                        Simpan Perusahaan
                    </button>
                </form>

                <!-- Daftar Perusahaan Terdaftar -->
                <div class="max-h-56 overflow-y-auto space-y-2 border-t border-slate-300 pt-3">
                    <p class="text-[10px] font-bold text-slate-500 uppercase">Daftar Perusahaan Aktif</p>
                    @foreach ($companies as $comp)
                        <div
                            class="flex items-center justify-between bg-white px-3 py-2 rounded-xl border border-slate-200 hover:border-slate-300">
                            <div class="flex items-center gap-2">
                                @if ($comp->code)
                                    <span
                                        class="bg-slate-200 text-slate-700 text-[9px] font-mono font-bold px-1.5 py-0.5 rounded">{{ $comp->code }}</span>
                                @endif
                                <span class="text-xs font-bold text-slate-800">{{ $comp->name }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <button onclick='editCompany(@json($comp))'
                                    class="p-1 text-amber-600 hover:bg-slate-100 rounded transition cursor-pointer">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </button>
                                <form action="{{ route('companies.destroy', $comp->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus perusahaan {{ $comp->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="p-1 text-rose-600 hover:bg-slate-100 rounded transition cursor-pointer">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- 📑 MODAL FORM DEPARTEMEN -->
    <div id="modalDepartment"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
        <div class="bg-slate-100 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-300">
            <div class="bg-gradient-to-r from-inv-teal to-inv-primary p-4 text-white flex justify-between items-center">
                <h3 id="deptModalTitle" class="font-serif font-bold text-sm">Tambah Departemen Baru</h3>
                <button onclick="closeDepartmentModal()" class="text-white/70 hover:text-white cursor-pointer"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="deptForm" method="POST" class="p-5 space-y-3">
                @csrf
                <input type="hidden" name="_method" id="deptFormMethod" value="POST">

                <div>
                    <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Kode Departemen</label>
                    <input type="text" name="code" id="dept_code" placeholder="Misal: HRGA, IT, PROD"
                        class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Nama Departemen <span
                            class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="dept_name" required
                        placeholder="Misal: Human Resources & General Affair"
                        class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Digunakan Oleh Perusahaan
                        <span class="text-rose-500">*</span></label>
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 gap-2 bg-white p-3 rounded-xl border border-slate-300 max-h-36 overflow-y-auto">
                        @foreach ($companies as $comp)
                            <label
                                class="flex items-center gap-2 cursor-pointer text-xs text-slate-700 hover:text-inv-teal">
                                <input type="checkbox" name="company_ids[]" value="{{ $comp->id }}"
                                    class="dept-company-checkbox rounded border-slate-300 text-inv-teal focus:ring-inv-teal">
                                <span class="text-[11px] leading-tight">{{ $comp->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Deskripsi / Catatan</label>
                    <textarea name="description" id="dept_desc" rows="2" placeholder="Catatan singkat departemen..."
                        class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal"></textarea>
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-inv-teal to-inv-primary hover:from-inv-hover hover:to-inv-hover text-white font-bold py-2.5 rounded-xl shadow-md text-xs uppercase tracking-wider cursor-pointer">
                    Simpan Departemen
                </button>
            </form>
        </div>
    </div>

    <script>
        // --- HANDLER MODAL DEPARTEMEN ---
        function openDepartmentModal(mode, dept = null) {
            const modal = document.getElementById('modalDepartment');
            const form = document.getElementById('deptForm');
            const title = document.getElementById('deptModalTitle');
            const method = document.getElementById('deptFormMethod');
            const checkboxes = document.querySelectorAll('.dept-company-checkbox');

            modal.classList.remove('hidden');
            checkboxes.forEach(cb => cb.checked = false);

            if (mode === 'edit' && dept) {
                title.innerText = 'Edit Departemen';
                form.action = `/departments/${dept.id}`;
                method.value = 'PUT';
                document.getElementById('dept_code').value = dept.code || '';
                document.getElementById('dept_name').value = dept.name || '';
                document.getElementById('dept_desc').value = dept.description || '';

                if (dept.companies && Array.isArray(dept.companies)) {
                    const activeCompanyIds = dept.companies.map(c => c.id);
                    checkboxes.forEach(cb => {
                        if (activeCompanyIds.includes(parseInt(cb.value))) {
                            cb.checked = true;
                        }
                    });
                }
            } else {
                title.innerText = 'Tambah Departemen Baru';
                form.action = "{{ route('departments.store') }}";
                method.value = 'POST';
                form.reset();
            }
        }

        function closeDepartmentModal() {
            document.getElementById('modalDepartment').classList.add('hidden');
        }

        // --- HANDLER MODAL COMPANY MANAGER ---
        function openCompanyManagerModal() {
            document.getElementById('modalCompanyManager').classList.remove('hidden');
        }

        function closeCompanyManagerModal() {
            document.getElementById('modalCompanyManager').classList.add('hidden');
            resetCompanyForm();
        }

        function editCompany(comp) {
            document.getElementById('companyFormTitle').innerText = 'Edit Perusahaan';
            document.getElementById('companyForm').action = `/companies/${comp.id}`;
            document.getElementById('companyFormMethod').value = 'PUT';
            document.getElementById('comp_code').value = comp.code || '';
            document.getElementById('comp_name').value = comp.name || '';
            document.getElementById('btnCompanySubmit').innerText = 'Update Perusahaan';
            document.getElementById('btnCancelCompanyEdit').classList.remove('hidden');
        }

        function resetCompanyForm() {
            document.getElementById('companyFormTitle').innerText = 'Tambah Perusahaan Baru';
            document.getElementById('companyForm').action = "{{ route('companies.store') }}";
            document.getElementById('companyFormMethod').value = 'POST';
            document.getElementById('comp_code').value = '';
            document.getElementById('comp_name').value = '';
            document.getElementById('btnCompanySubmit').innerText = 'Simpan Perusahaan';
            document.getElementById('btnCancelCompanyEdit').classList.add('hidden');
        }
    </script>
@endsection
