@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header & Action -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-xl lg:text-2xl font-serif font-bold text-slate-800">Master Data PIC (Person In Charge)</h2>
                <p class="text-xs text-slate-500 mt-1">Kelola Penanggung Jawab & Pengguna Aset Perusahaan</p>
            </div>
            <button onclick="openPicModal('add')"
                class="bg-gradient-to-r from-inv-teal to-inv-primary hover:from-inv-hover hover:to-inv-hover text-white px-4 py-2.5 rounded-xl shadow-md transition-all flex items-center gap-2 font-bold text-xs cursor-pointer w-fit">
                <i class="fa-solid fa-user-plus"></i> Tambah PIC
            </button>
        </div>

        <!-- Filter Bar -->
        <div class="bg-slate-200/60 backdrop-blur-md p-4 rounded-2xl border border-slate-300/80">
            <form method="GET" action="{{ route('pics.index') }}"
                class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                <!-- Search -->
                <div class="relative sm:col-span-4">
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari Nama, NIP, Jabatan, Email..."
                        class="w-full bg-slate-100 border border-slate-300 rounded-xl pl-9 pr-8 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-inv-teal">
                </div>

                <!-- Filter Perusahaan -->
                <div class="sm:col-span-3">
                    <select name="company_name" onchange="this.form.submit()"
                        class="w-full bg-slate-100 border border-slate-300 text-slate-700 text-xs rounded-xl p-2 outline-none focus:border-inv-teal">
                        <option value="">-- Semua Perusahaan --</option>
                        @foreach (['Perusahaan A', 'Perusahaan B', 'Perusahaan C', 'Perusahaan D', 'Perusahaan E', 'General'] as $comp)
                            <option value="{{ $comp }}" {{ request('company_name') == $comp ? 'selected' : '' }}>
                                {{ $comp }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Departemen -->
                <div class="sm:col-span-3">
                    <select name="department_id" onchange="this.form.submit()"
                        class="w-full bg-slate-100 border border-slate-300 text-slate-700 text-xs rounded-xl p-2 outline-none focus:border-inv-teal">
                        <option value="">-- Semua Departemen --</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}"
                                {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }} ({{ $dept->company_name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2 flex items-center justify-end">
                    <span class="text-xs text-slate-500">Total PIC: <strong
                            class="text-slate-800">{{ $total_pics }}</strong></span>
                </div>
            </form>
        </div>

        <!-- Tabel PIC -->
        <div class="bg-slate-200/60 backdrop-blur-md rounded-2xl border border-slate-300/80 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-max">
                    <thead class="bg-slate-300/60 border-b border-slate-300">
                        <tr>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">
                                Perusahaan</th>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Nama &
                                NIP</th>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">
                                Departemen & Jabatan</th>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Kontak
                            </th>
                            <th
                                class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest text-center">
                                Aset Dipegang</th>
                            <th
                                class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest text-center">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-300/60 text-xs">
                        @forelse($pics as $pic)
                            <tr class="hover:bg-slate-300/40 transition-colors">
                                <td class="px-5 py-3.5">
                                    <span
                                        class="text-[9px] font-bold text-slate-700 bg-slate-300 px-2 py-0.5 rounded-md uppercase">
                                        {{ $pic->company_name ?? 'General' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-inv-teal/20 text-inv-teal font-bold flex items-center justify-center text-xs">
                                            {{ strtoupper(substr($pic->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800">{{ $pic->name }}</p>
                                            <p class="text-[10px] text-slate-500 font-mono">NIP: {{ $pic->nip ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="font-semibold text-slate-700">{{ $pic->department->name ?? '-' }}</p>
                                    <p class="text-[10px] text-slate-500">{{ $pic->position ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="text-slate-700"><i
                                            class="fa-solid fa-phone text-[9px] text-slate-400 mr-1"></i>
                                        {{ $pic->phone ?? '-' }}</p>
                                    <p class="text-slate-500 text-[10px]"><i
                                            class="fa-solid fa-envelope text-[9px] text-slate-400 mr-1"></i>
                                        {{ $pic->email ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span
                                        class="bg-inv-teal/10 text-inv-teal font-bold px-2.5 py-1 rounded-lg text-[10px] border border-inv-teal/20">
                                        <i class="fa-solid fa-laptop text-[9px] mr-1"></i> {{ $pic->products_count }} Unit
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex justify-center items-center gap-1.5">
                                        <button onclick='openPicModal("edit", @json($pic))'
                                            class="p-1.5 text-amber-600 hover:bg-slate-300 rounded-lg transition cursor-pointer"
                                            title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <form action="{{ route('pics.destroy', $pic->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus PIC ini?')">
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
                                <td colspan="6" class="px-6 py-8 text-center text-slate-400 italic">Data PIC tidak
                                    ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-3 bg-slate-300/40 border-t border-slate-300">
                {{ $pics->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Form PIC -->
    <div id="modalPic"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
        <div class="bg-slate-100 rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-slate-300">
            <div class="bg-gradient-to-r from-inv-teal to-inv-primary p-4 text-white flex justify-between items-center">
                <h3 id="picModalTitle" class="font-serif font-bold text-sm">Pendaftaran PIC Baru</h3>
                <button onclick="closePicModal()" class="text-white/70 hover:text-white cursor-pointer"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="picForm" method="POST" class="p-5 space-y-3">
                @csrf
                <input type="hidden" name="_method" id="picFormMethod" value="POST">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Perusahaan <span
                                class="text-rose-500">*</span></label>
                        <select name="company_name" id="pic_company" onchange="filterDepartmentsByCompany()" required
                            class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-800 outline-none focus:border-inv-teal">
                            @foreach (['Perusahaan A', 'Perusahaan B', 'Perusahaan C', 'Perusahaan D', 'Perusahaan E', 'General'] as $comp)
                                <option value="{{ $comp }}">{{ $comp }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">NIP / NIK</label>
                        <input type="text" name="nip" id="pic_nip" placeholder="1029384"
                            class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Nama Lengkap <span
                                class="text-rose-500">*</span></label>
                        <input type="text" name="name" id="pic_name" required placeholder="Ahmad Subagja"
                            class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Departemen <span
                                class="text-rose-500">*</span></label>
                        <select name="department_id" id="pic_dept" required
                            class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                            <option value="">-- Pilih Departemen --</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->id }}" data-company="{{ $dept->company_name }}">
                                    {{ $dept->name }} ({{ $dept->company_name }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Jabatan</label>
                        <input type="text" name="position" id="pic_position" placeholder="Staff IT"
                            class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">No. WhatsApp</label>
                        <input type="text" name="phone" id="pic_phone" placeholder="08123456789"
                            class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Email</label>
                        <input type="email" name="email" id="pic_email" placeholder="ahmad@company.com"
                            class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs outline-none focus:border-inv-teal">
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-inv-teal to-inv-primary hover:from-inv-hover hover:to-inv-hover text-white font-bold py-2.5 rounded-xl shadow-md text-xs uppercase tracking-wider mt-2">
                    Simpan Data PIC
                </button>
            </form>
        </div>
    </div>

    <script>
        // Filter Pilihan Departemen berdasarkan Perusahaan yang dipilih di Modal
        function filterDepartmentsByCompany() {
            const selectedCompany = document.getElementById('pic_company').value;
            const deptOptions = document.querySelectorAll('#pic_dept option');

            deptOptions.forEach(opt => {
                if (!opt.value) return; // Skip pilihan default
                const deptCompany = opt.getAttribute('data-company');
                if (deptCompany === selectedCompany || deptCompany === 'General' || selectedCompany === 'General') {
                    opt.style.display = 'block';
                } else {
                    opt.style.display = 'none';
                }
            });
        }

        function openPicModal(mode, pic = null) {
            const modal = document.getElementById('modalPic');
            const form = document.getElementById('picForm');
            const title = document.getElementById('picModalTitle');
            const method = document.getElementById('picFormMethod');

            modal.classList.remove('hidden');

            if (mode === 'edit' && pic) {
                title.innerText = 'Edit Data PIC';
                form.action = `/pics/${pic.id}`;
                method.value = 'PUT';
                document.getElementById('pic_company').value = pic.company_name || 'General';
                filterDepartmentsByCompany();
                document.getElementById('pic_nip').value = pic.nip || '';
                document.getElementById('pic_name').value = pic.name || '';
                document.getElementById('pic_dept').value = pic.department_id || '';
                document.getElementById('pic_position').value = pic.position || '';
                document.getElementById('pic_phone').value = pic.phone || '';
                document.getElementById('pic_email').value = pic.email || '';
            } else {
                title.innerText = 'Pendaftaran PIC Baru';
                form.action = "{{ route('pics.store') }}";
                method.value = 'POST';
                form.reset();
                filterDepartmentsByCompany();
            }
        }

        function closePicModal() {
            document.getElementById('modalPic').classList.add('hidden');
        }
    </script>
@endsection
