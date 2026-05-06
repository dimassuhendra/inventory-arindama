@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-2xl lg:text-3xl font-bold text-primary font-serif">Manajemen Pengguna</h2>
                <p class="text-xs text-secondary font-sans uppercase tracking-widest mt-2 font-semibold">
                    Total Pengguna: <span class="text-primary font-bold">{{ $users->total() }} Orang</span>
                </p>
            </div>
            <button onclick="openModal('add')"
                class="bg-primary hover:bg-secondary text-white px-6 py-3.5 rounded-xl shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-3 font-bold text-sm tracking-wide">
                <i class="fa-solid fa-user-plus"></i> Tambah Pengguna
            </button>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-accent/30 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-primary/5 border-b border-accent/20">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold text-primary uppercase tracking-widest">Informasi
                            Pengguna</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-primary uppercase tracking-widest">Departemen</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-primary uppercase tracking-widest text-center">Hak
                            Akses (Role)</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-primary uppercase tracking-widest text-center">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 font-sans">
                    @forelse($users as $user)
                        <tr class="hover:bg-primary/5 transition duration-200">
                            <!-- Info Pengguna -->
                            <td class="px-6 py-4 flex items-center gap-4">
                                <div
                                    class="w-10 h-10 rounded-full border border-accent/30 overflow-hidden shadow-sm flex-shrink-0 bg-background">
                                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=F2EDC2&color=346739&bold=true' }}"
                                        class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800 line-clamp-1">{{ $user->name }}</p>
                                    <p class="text-[11px] text-gray-500 font-medium mt-0.5">{{ $user->email }}</p>
                                </div>
                            </td>

                            <!-- Departemen -->
                            <td class="px-6 py-4">
                                <span
                                    class="text-[10px] font-bold text-secondary bg-accent/20 border border-accent/30 px-3 py-1.5 rounded-md uppercase tracking-wider">
                                    <i class="fa-solid fa-building text-[9px] mr-1"></i> {{ $user->department }}
                                </span>
                            </td>

                            <!-- Role -->
                            <td class="px-6 py-4 text-center">
                                @foreach ($user->roles as $role)
                                    <span
                                        class="text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-full
                                        {{ $role->name === 'Super Admin' ? 'bg-amber-50 text-amber-600 border border-amber-200' : 'bg-primary/10 text-primary border border-primary/20' }}">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </td>

                            <!-- Aksi -->
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <button
                                        onclick="openModal('edit', {{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ $user->department }}', '{{ $user->roles->first()->name ?? '' }}')"
                                        class="w-9 h-9 rounded-xl bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition flex items-center justify-center shadow-sm">
                                        <i class="fa-solid fa-user-pen text-xs"></i>
                                    </button>

                                    <!-- Tombol Hapus (Disabled untuk diri sendiri) -->
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Anda yakin ingin menghapus pengguna ini secara permanen?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="w-9 h-9 rounded-xl transition flex items-center justify-center shadow-sm {{ auth()->id() === $user->id ? 'bg-gray-100 text-gray-300 cursor-not-allowed' : 'bg-red-50 text-red-500 hover:bg-red-500 hover:text-white' }}"
                                            {{ auth()->id() === $user->id ? 'disabled title="Anda tidak bisa menghapus diri sendiri"' : '' }}>
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-users-slash text-4xl text-accent mb-4"></i>
                                    <p class="text-gray-400 italic text-sm">Belum ada data pengguna yang terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Paginasi -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Form User -->
    <div id="modalUser"
        class="fixed inset-0 bg-primary/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div
            class="bg-white rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden transform transition-all border border-accent/20">
            <!-- Modal Header -->
            <div class="bg-primary p-6 text-white flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-user-shield text-lg"></i>
                    </div>
                    <h3 id="modalTitle" class="font-bold text-lg font-serif tracking-wide">Pendaftaran Pengguna</h3>
                </div>
                <button onclick="closeModal()"
                    class="text-accent hover:text-white transition-all transform hover:rotate-90">
                    <i class="fa-solid fa-circle-xmark text-2xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form id="userForm" method="POST"
                class="p-8 space-y-5 overflow-y-auto max-h-[75vh] font-sans custom-scrollbar">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Nama Lengkap
                        <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="user_name" required placeholder="Contoh: Dimas Suhendra"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Alamat Email
                            <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="user_email" required placeholder="nama@arindama.com"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Departemen
                            <span class="text-red-500">*</span></label>
                        <input type="text" name="department" id="user_department" required
                            placeholder="Contoh: IT / Gudang"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Hak Akses
                            (Role) <span class="text-red-500">*</span></label>
                        <select name="role" id="user_role" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                            <option value="">-- Pilih Role --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2"
                            id="passwordLabel">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" id="user_password" placeholder="Minimal 8 karakter"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                        <p id="passwordHint" class="text-[9px] text-amber-500 mt-1.5 font-medium hidden">
                            <i class="fa-solid fa-circle-info mr-1"></i> Kosongkan jika tidak ingin mengubah password
                        </p>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <button type="submit"
                    class="w-full bg-primary text-white font-bold py-4 rounded-xl shadow-lg shadow-primary/20 hover:bg-secondary transition-all uppercase text-xs tracking-widest flex items-center justify-center gap-2 mt-4">
                    <i class="fa-solid fa-floppy-disk"></i> Konfirmasi & Simpan
                </button>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Notifikasi SweetAlert
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2500,
                background: '#ffffff',
                borderRadius: '20px'
            });
        @endif

        @if (session('error') || $errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: "{{ session('error') ?? $errors->first() }}",
                confirmButtonColor: '#346739',
                borderRadius: '20px'
            });
        @endif

        // Logika Modal
        function openModal(mode, id = null, name = '', email = '', department = '', role = '') {
            const modal = document.getElementById('modalUser');
            const form = document.getElementById('userForm');
            const method = document.getElementById('formMethod');
            const title = document.getElementById('modalTitle');

            const passwordInput = document.getElementById('user_password');
            const passwordLabel = document.getElementById('passwordLabel');
            const passwordHint = document.getElementById('passwordHint');

            modal.classList.remove('hidden');

            if (mode === 'edit') {
                title.innerText = 'Perbarui Data Pengguna';
                form.action = `/users/${id}`;
                method.value = 'PUT';

                document.getElementById('user_name').value = name;
                document.getElementById('user_email').value = email;
                document.getElementById('user_department').value = department;
                document.getElementById('user_role').value = role;

                // Password handling for edit
                passwordInput.required = false;
                passwordLabel.innerHTML = 'Password (Opsional)';
                passwordHint.classList.remove('hidden');
            } else {
                title.innerText = 'Pendaftaran Pengguna Baru';
                form.action = "{{ route('users.store') }}";
                method.value = 'POST';
                form.reset();

                // Password handling for create
                passwordInput.required = true;
                passwordLabel.innerHTML = 'Password <span class="text-red-500">*</span>';
                passwordHint.classList.add('hidden');
            }
        }

        function closeModal() {
            document.getElementById('modalUser').classList.add('hidden');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('modalUser');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
@endsection
