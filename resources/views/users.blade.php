@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <!-- 1. HEADER & ACTION BUTTONS -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-xl lg:text-2xl font-serif font-bold text-slate-800">Manajemen Pengguna</h2>
                <p class="text-xs text-slate-500 mt-1">Kelola kredensial, departemen, dan penugasan peran (role) sistem</p>
            </div>

            <div class="flex items-center gap-2">
                @hasrole('Super Admin')
                    <button onclick="openRoleModal()"
                        class="bg-slate-700 hover:bg-slate-800 text-white px-4 py-2.5 rounded-xl shadow-md transition-all flex items-center justify-center gap-2 font-bold text-xs cursor-pointer">
                        <i class="fa-solid fa-user-shield"></i> Kelola Role
                    </button>
                @endhasrole

                <button onclick="openModal('add')"
                    class="bg-gradient-to-r from-inv-teal to-inv-primary hover:from-inv-hover hover:to-inv-hover text-white px-5 py-2.5 rounded-xl shadow-md transition-all flex items-center justify-center gap-2 font-bold text-xs cursor-pointer">
                    <i class="fa-solid fa-user-plus"></i> Tambah Pengguna Baru
                </button>
            </div>
        </div>

        <!-- 2. MINI ANALYTICS BAR -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Total Users -->
            <div
                class="bg-gradient-to-br from-[#00a8b5] to-[#2dd4bf] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-teal-100 uppercase tracking-widest">Total Pengguna</p>
                        <h3 class="text-2xl font-serif font-bold text-white mt-1">{{ number_format($total_users_count) }}
                            <span class="text-xs font-normal">Akun</span>
                        </h3>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <i
                    class="fa-solid fa-address-card absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
            </div>

            <!-- Users Aktif -->
            <div
                class="bg-gradient-to-br from-[#0c66c8] to-[#2563eb] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-blue-100 uppercase tracking-widest">Status Aktif</p>
                        <h3 class="text-2xl font-serif font-bold text-white mt-1">{{ number_format($active_users_count) }}
                            <span class="text-xs font-normal">Siap Login</span>
                        </h3>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                </div>
                <i
                    class="fa-solid fa-user-shield absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
            </div>

            <!-- Petugas Teraktif -->
            <div
                class="bg-gradient-to-br from-[#081d34] via-[#0d2a4a] to-[#00a8b5] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-teal-200 uppercase tracking-widest">Petugas Teraktif Audit</p>
                        <h3 class="text-sm font-serif font-bold text-inv-mint mt-1 truncate max-w-[170px]">
                            {{ $top_user_name }}</h3>
                        <p class="text-[10px] text-slate-300 font-semibold mt-0.5">
                            {{ number_format($top_user_tx) }} Transaksi Recorded
                        </p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur-md text-inv-mint flex items-center justify-center text-lg">
                        <i class="fa-solid fa-award"></i>
                    </div>
                </div>
                <i
                    class="fa-solid fa-chart-line-up absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
            </div>
        </div>

        <!-- 3. FILTER & SEARCH BAR -->
        <div class="bg-slate-200/60 backdrop-blur-md p-4 rounded-2xl border border-slate-300/80">
            <form method="GET" action="{{ route('users.index') }}"
                class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                <div class="relative sm:col-span-5">
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama, email, departemen..."
                        class="w-full bg-slate-100 border border-slate-300 rounded-xl pl-9 pr-8 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-inv-teal transition-colors">
                    @if (request('search'))
                        <a href="{{ route('users.index') }}"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </a>
                    @endif
                </div>

                <div class="sm:col-span-4">
                    <select name="role" onchange="this.form.submit()"
                        class="w-full bg-slate-100 border border-slate-300 text-slate-700 text-xs rounded-xl p-2 outline-none focus:border-inv-teal">
                        <option value="">-- Semua Peran (Role) --</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-3 flex items-center justify-end gap-2">
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

        <!-- 4. TABEL PENGGUNA -->
        <div class="bg-slate-200/60 backdrop-blur-md rounded-2xl border border-slate-300/80 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-max">
                    <thead class="bg-slate-300/60 border-b border-slate-300">
                        <tr>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Informasi
                                Pengguna</th>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">
                                Departemen</th>
                            <th
                                class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest text-center">
                                Hak Akses (Role)</th>
                            <th
                                class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest text-center">
                                Status</th>
                            <th
                                class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest text-center">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-300/60">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-300/40 transition-colors">
                                <td class="px-5 py-3.5 flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-full border border-slate-300 overflow-hidden bg-white shrink-0 shadow-sm">
                                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=00a8b5&color=fff&bold=true' }}"
                                            alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-800 line-clamp-1">{{ $user->name }}</p>
                                        <p class="text-[10px] text-slate-500 font-medium">{{ $user->email }}</p>
                                    </div>
                                </td>

                                <td class="px-5 py-3.5 text-xs text-slate-700 font-semibold">
                                    <span
                                        class="bg-slate-300/80 text-slate-700 px-2.5 py-1 rounded-lg text-[10px] border border-slate-300">
                                        <i class="fa-solid fa-building text-[9px] mr-1 text-inv-teal"></i>
                                        {{ $user->department }}
                                    </span>
                                </td>

                                <td class="px-5 py-3.5 text-center">
                                    @foreach ($user->roles as $role)
                                        <span
                                            class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full
                                        {{ $role->name === 'Super Admin' ? 'bg-amber-500/10 text-amber-700 border border-amber-500/30' : 'bg-inv-teal/10 text-inv-teal border border-inv-teal/30' }}">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </td>

                                <td class="px-5 py-3.5 text-center">
                                    @if (auth()->id() !== $user->id)
                                        <form action="{{ route('users.toggle-status', $user->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold transition cursor-pointer
                                            {{ $user->is_active ? 'bg-emerald-500/10 text-emerald-700 border border-emerald-500/30 hover:bg-emerald-500 hover:text-white' : 'bg-rose-500/10 text-rose-700 border border-rose-500/30 hover:bg-rose-500 hover:text-white' }}">
                                                <i
                                                    class="fa-solid {{ $user->is_active ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </button>
                                        </form>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-700 border border-emerald-500/30">
                                            <i class="fa-solid fa-circle-check"></i> Aktif (Anda)
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex justify-center items-center gap-1.5">
                                        <button
                                            onclick="openModal('edit', {{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ addslashes($user->department) }}', '{{ $user->roles->first()->name ?? '' }}', {{ $user->is_active ? 1 : 0 }})"
                                            class="p-1.5 text-amber-600 hover:bg-slate-300 rounded-lg transition text-xs cursor-pointer"
                                            title="Edit Pengguna">
                                            <i class="fa-solid fa-user-pen"></i>
                                        </button>

                                        @if (auth()->id() !== $user->id)
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus pengguna {{ addslashes($user->name) }} secara permanen?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="p-1.5 text-rose-600 hover:bg-slate-300 rounded-lg transition text-xs cursor-pointer"
                                                    title="Hapus Pengguna">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="p-1.5 text-slate-300 cursor-not-allowed"
                                                title="Anda tidak dapat menghapus akun sendiri">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-users-slash text-3xl mb-2 opacity-30"></i>
                                    <p class="text-xs italic">Belum ada data pengguna yang terdaftar.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (request('per_page') != 'all')
                <div class="px-5 py-3 bg-slate-300/40 border-t border-slate-300">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        <!-- 5. MODAL USER -->
        <div id="modalUser"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
            <div
                class="bg-slate-100 rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-slate-300 transform transition-all">
                <div
                    class="bg-gradient-to-r from-inv-teal to-inv-primary p-5 text-white flex justify-between items-center">
                    <h3 id="modalTitle" class="font-serif font-bold text-base">Pendaftaran Pengguna Baru</h3>
                    <button onclick="closeModal()" class="text-white/70 hover:text-white transition cursor-pointer">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form id="userForm" method="POST" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="user_name" required
                            placeholder="Contoh: Dimas Suhendra"
                            class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-inv-teal">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                Alamat Email <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" name="email" id="user_email" required
                                placeholder="nama@perusahaan.com"
                                class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-inv-teal">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                Departemen <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="department" id="user_department" required
                                placeholder="Contoh: IT / Gudang Utama"
                                class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-inv-teal">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                Hak Akses (Role) <span class="text-rose-500">*</span>
                            </label>
                            <select name="role" id="user_role" required
                                class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-inv-teal">
                                <option value="">-- Pilih Role --</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5"
                                id="passwordLabel">
                                Password <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" name="password" id="user_password" placeholder="Minimal 8 karakter"
                                class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-inv-teal">
                            <p id="passwordHint" class="text-[9px] text-amber-600 mt-1 font-semibold hidden">
                                <i class="fa-solid fa-info-circle mr-1"></i> Biarkan kosong jika tidak diubah
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-1">
                        <input type="checkbox" name="is_active" id="user_is_active" value="1" checked
                            class="w-4 h-4 text-inv-teal rounded border-slate-300 focus:ring-inv-teal cursor-pointer">
                        <label for="user_is_active" class="text-xs font-semibold text-slate-700 cursor-pointer">
                            Status Akun Aktif (Dapat Login)
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-inv-teal to-inv-primary hover:from-inv-hover hover:to-inv-hover text-white font-bold py-3 rounded-xl shadow-md text-xs tracking-wider uppercase cursor-pointer mt-2">
                        Simpan Data Pengguna
                    </button>
                </form>
            </div>
        </div>

        <!-- 6. MODAL KELOLA ROLE (KHUSUS SUPER ADMIN) -->
        @hasrole('Super Admin')
            <div id="modalRole"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
                <div
                    class="bg-slate-100 rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-slate-300 transform transition-all">
                    <div class="bg-slate-800 p-5 text-white flex justify-between items-center">
                        <h3 class="font-serif font-bold text-base flex items-center gap-2">
                            <i class="fa-solid fa-user-shield text-amber-400"></i> Kelola Role & Hak Akses
                        </h3>
                        <button onclick="closeRoleModal()" class="text-white/70 hover:text-white transition cursor-pointer">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <div class="p-6 space-y-5">
                        <!-- Form Tambah / Edit Role -->
                        <form id="roleForm" method="POST" action="{{ route('roles.store') }}" class="flex gap-2">
                            @csrf
                            <input type="hidden" name="_method" id="roleFormMethod" value="POST">
                            <input type="text" name="name" id="role_name" required placeholder="Nama Role Baru..."
                                class="flex-1 bg-white border border-slate-300 rounded-xl px-3.5 py-2 text-xs text-slate-800 outline-none focus:border-slate-800">
                            <button type="submit" id="btnRoleSubmit"
                                class="bg-slate-800 hover:bg-slate-900 text-white font-bold px-4 py-2 rounded-xl text-xs cursor-pointer shrink-0 transition">
                                + Tambah Role
                            </button>
                            <button type="button" id="btnCancelRoleEdit" onclick="resetRoleForm()"
                                class="hidden bg-slate-300 hover:bg-slate-400 text-slate-700 font-bold px-3 py-2 rounded-xl text-xs cursor-pointer shrink-0 transition">
                                Batal
                            </button>
                        </form>

                        <!-- Daftar Role -->
                        <div class="border border-slate-200 rounded-xl overflow-hidden bg-white max-h-60 overflow-y-auto">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-slate-200 text-slate-600 text-[10px] uppercase font-bold sticky top-0">
                                    <tr>
                                        <th class="px-4 py-2">Nama Role</th>
                                        <th class="px-4 py-2 text-center">Pengguna</th>
                                        <th class="px-4 py-2 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                                    @foreach ($roles as $r)
                                        <tr class="hover:bg-slate-50">
                                            <td class="px-4 py-2.5 font-semibold text-slate-800">{{ $r->name }}</td>
                                            <td class="px-4 py-2.5 text-center">
                                                <span
                                                    class="bg-slate-100 px-2 py-0.5 rounded-full text-[10px] font-bold text-slate-600">
                                                    {{ $r->users_count }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2.5 text-center">
                                                @if ($r->name !== 'Super Admin')
                                                    <div class="flex justify-center items-center gap-2">
                                                        <button type="button"
                                                            onclick="editRole({{ $r->id }}, '{{ addslashes($r->name) }}')"
                                                            class="text-amber-600 hover:text-amber-700 text-xs font-bold cursor-pointer"
                                                            title="Edit Role">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </button>
                                                        <form action="{{ route('roles.destroy', $r->id) }}" method="POST"
                                                            onsubmit="return confirm('Hapus role {{ addslashes($r->name) }}?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit"
                                                                class="text-rose-600 hover:text-rose-700 text-xs font-bold cursor-pointer"
                                                                title="Hapus Role">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <span class="text-[10px] text-slate-400 italic">Sistem</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endhasrole

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

        function openModal(mode, id = null, name = '', email = '', department = '', role = '', isActive = 1) {
            const modal = document.getElementById('modalUser');
            const form = document.getElementById('userForm');
            const method = document.getElementById('formMethod');
            const title = document.getElementById('modalTitle');

            const passwordInput = document.getElementById('user_password');
            const passwordLabel = document.getElementById('passwordLabel');
            const passwordHint = document.getElementById('passwordHint');

            modal.classList.remove('hidden');

            if (mode === 'edit') {
                title.innerText = 'Edit Data Pengguna';
                form.action = `/users/${id}`;
                method.value = 'PUT';

                document.getElementById('user_name').value = name;
                document.getElementById('user_email').value = email;
                document.getElementById('user_department').value = department;
                document.getElementById('user_role').value = role;
                document.getElementById('user_is_active').checked = Boolean(isActive);

                passwordInput.required = false;
                passwordLabel.innerHTML = 'Password (Opsional)';
                passwordHint.classList.remove('hidden');
            } else {
                title.innerText = 'Pendaftaran Pengguna Baru';
                form.action = "{{ route('users.store') }}";
                method.value = 'POST';
                form.reset();

                document.getElementById('user_is_active').checked = true;

                passwordInput.required = true;
                passwordLabel.innerHTML = 'Password <span class="text-rose-500">*</span>';
                passwordHint.classList.add('hidden');
            }
        }

        function closeModal() {
            document.getElementById('modalUser').classList.add('hidden');
        }

        // --- JS Modal Role Management ---
        function openRoleModal() {
            document.getElementById('modalRole').classList.remove('hidden');
        }

        function closeRoleModal() {
            document.getElementById('modalRole').classList.add('hidden');
            resetRoleForm();
        }

        function editRole(id, name) {
            const form = document.getElementById('roleForm');
            const method = document.getElementById('roleFormMethod');
            const inputName = document.getElementById('role_name');
            const btnSubmit = document.getElementById('btnRoleSubmit');
            const btnCancel = document.getElementById('btnCancelRoleEdit');

            form.action = `/roles/${id}`;
            method.value = 'PUT';
            inputName.value = name;
            btnSubmit.innerText = 'Update';
            btnSubmit.className =
                'bg-amber-600 hover:bg-amber-700 text-white font-bold px-4 py-2 rounded-xl text-xs cursor-pointer shrink-0 transition';
            btnCancel.classList.remove('hidden');
        }

        function resetRoleForm() {
            const form = document.getElementById('roleForm');
            const method = document.getElementById('roleFormMethod');
            const inputName = document.getElementById('role_name');
            const btnSubmit = document.getElementById('btnRoleSubmit');
            const btnCancel = document.getElementById('btnCancelRoleEdit');

            form.action = "{{ route('roles.store') }}";
            method.value = 'POST';
            inputName.value = '';
            btnSubmit.innerText = '+ Tambah Role';
            btnSubmit.className =
                'bg-slate-800 hover:bg-slate-900 text-white font-bold px-4 py-2 rounded-xl text-xs cursor-pointer shrink-0 transition';
            btnCancel.classList.add('hidden');
        }

        window.onclick = function(event) {
            const modalUser = document.getElementById('modalUser');
            const modalRole = document.getElementById('modalRole');
            if (event.target == modalUser) closeModal();
            if (event.target == modalRole) closeRoleModal();
        }
    </script>
@endsection
