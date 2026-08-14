@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header & Action -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-xl lg:text-2xl font-serif font-bold text-slate-800">Master Data Departemen</h2>
                <p class="text-xs text-slate-500 mt-1">Kelola Struktur Unit Organisasi Perusahaan Group</p>
            </div>
            <button onclick="openDepartmentModal('add')"
                class="bg-gradient-to-r from-inv-teal to-inv-primary hover:from-inv-hover hover:to-inv-hover text-white px-4 py-2.5 rounded-xl shadow-md transition-all flex items-center gap-2 font-bold text-xs cursor-pointer w-fit">
                <i class="fa-solid fa-plus"></i> Tambah Departemen
            </button>
        </div>

        <!-- Search & Filter Bar -->
        <div class="bg-slate-200/60 backdrop-blur-md p-4 rounded-2xl border border-slate-300/80">
            <form method="GET" action="{{ route('departments.index') }}"
                class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                <!-- Search -->
                <div class="relative sm:col-span-6">
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama atau kode departemen..."
                        class="w-full bg-slate-100 border border-slate-300 rounded-xl pl-9 pr-8 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-inv-teal">
                </div>

                <!-- Filter Perusahaan -->
                <div class="sm:col-span-4">
                    <select name="company_name" onchange="this.form.submit()"
                        class="w-full bg-slate-100 border border-slate-300 text-slate-700 text-xs rounded-xl p-2 outline-none focus:border-inv-teal">
                        <option value="">-- Semua Perusahaan --</option>
                        @foreach (['PT Agung Putra Nirantara Mandiri', 'PT Kirana Baskara Kuwara', 'PT Lancar Anja Kuwaga', 'PT Praguwa Wahyu Astama', 'PT Teknologi Arindama Andra', 'General'] as $comp)
                            <option value="{{ $comp }}" {{ request('company_name') == $comp ? 'selected' : '' }}>
                                {{ $comp }}</option>
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
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">
                                Perusahaan</th>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Kode &
                                Departemen</th>
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
                                    <span
                                        class="text-[9px] font-bold text-slate-700 bg-slate-300 px-2 py-0.5 rounded-md uppercase">
                                        {{ $dept->company_name ?? 'General' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <span
                                            class="bg-inv-teal/10 text-inv-teal font-mono font-bold text-[10px] px-2 py-0.5 rounded-md border border-inv-teal/20">
                                            {{ $dept->code ?? 'N/A' }}
                                        </span>
                                        <span class="font-bold text-slate-800">{{ $dept->name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-slate-600">
                                    {{ $dept->description ?? '-' }}
                                </td>
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

    <!-- Modal Form Departemen -->
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
                    <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Perusahaan <span
                            class="text-rose-500">*</span></label>
                    <select name="company_name" id="dept_company" required
                        class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-800 outline-none focus:border-inv-teal">
                        @foreach (['Perusahaan A', 'Perusahaan B', 'Perusahaan C', 'Perusahaan D', 'Perusahaan E', 'General'] as $comp)
                            <option value="{{ $comp }}">{{ $comp }}</option>
                        @endforeach
                    </select>
                </div>

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
                    <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Deskripsi / Catatan</label>
                    <textarea name="description" id="dept_desc" rows="2" placeholder="Catatan singkat departemen..."
                        class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal"></textarea>
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-inv-teal to-inv-primary hover:from-inv-hover hover:to-inv-hover text-white font-bold py-2.5 rounded-xl shadow-md text-xs uppercase tracking-wider">
                    Simpan Departemen
                </button>
            </form>
        </div>
    </div>

    <script>
        function openDepartmentModal(mode, dept = null) {
            const modal = document.getElementById('modalDepartment');
            const form = document.getElementById('deptForm');
            const title = document.getElementById('deptModalTitle');
            const method = document.getElementById('deptFormMethod');

            modal.classList.remove('hidden');

            if (mode === 'edit' && dept) {
                title.innerText = 'Edit Departemen';
                form.action = `/departments/${dept.id}`;
                method.value = 'PUT';
                document.getElementById('dept_company').value = dept.company_name || 'General';
                document.getElementById('dept_code').value = dept.code || '';
                document.getElementById('dept_name').value = dept.name || '';
                document.getElementById('dept_desc').value = dept.description || '';
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
    </script>
@endsection
