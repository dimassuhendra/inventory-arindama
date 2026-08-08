@extends('layouts.app')

@section('content')
    <div x-data="{ openModal: false, activeLog: {} }" class="space-y-6">

        <!-- 1. HEADER SECTION -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-xl lg:text-2xl font-serif font-bold text-slate-800">System Activity Log</h2>
                <p class="text-xs text-slate-500 mt-1">Audit trail pencatatan aktivitas pengguna dan riwayat perubahan data
                </p>
            </div>

            @if (\App\Services\CategoryService::isSuperAdmin())
                <!-- Tombol Prune Log Khusus Super Admin -->
                <form action="{{ route('activity-logs.prune') }}" method="POST"
                    onsubmit="return confirm('Bersihkan seluruh log yang berusia lebih dari 30 hari?')">
                    @csrf
                    <input type="hidden" name="days" value="30">
                    <button type="submit"
                        class="bg-slate-300 hover:bg-slate-400 text-slate-700 font-bold px-4 py-2 rounded-xl text-xs transition-colors cursor-pointer flex items-center gap-2">
                        <i class="fa-solid fa-broom text-slate-600"></i> Bersihkan Log Tua (>30 Hari)
                    </button>
                </form>
            @endif
        </div>

        <!-- 2. MINI ANALYTICS BAR (4 CARDS PALET INV) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Log -->
            <div
                class="bg-gradient-to-br from-[#00a8b5] to-[#2dd4bf] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-teal-100 uppercase tracking-widest">Total Activity Log</p>
                        <h3 class="text-2xl font-serif font-bold text-white mt-1">{{ number_format($total_logs_count) }}
                            <span class="text-xs font-normal">Entri</span></h3>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                </div>
                <i
                    class="fa-solid fa-list-check absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
            </div>

            <!-- Log Hari Ini -->
            <div
                class="bg-gradient-to-br from-[#0c66c8] to-[#2563eb] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-blue-100 uppercase tracking-widest">Aktivitas Hari Ini</p>
                        <h3 class="text-2xl font-serif font-bold text-white mt-1">+{{ number_format($today_logs_count) }}
                            <span class="text-xs font-normal">Tercatat</span></h3>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg">
                        <i class="fa-solid fa-calendar-day"></i>
                    </div>
                </div>
                <i
                    class="fa-solid fa-bolt absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
            </div>

            <!-- Event Delete/Hapus -->
            <div
                class="bg-gradient-to-br from-rose-500 to-rose-600 p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-rose-100 uppercase tracking-widest">Aksi Penghapusan</p>
                        <h3 class="text-2xl font-serif font-bold text-white mt-1">{{ number_format($delete_logs_count) }}
                            <span class="text-xs font-normal">Sesi</span></h3>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-lg">
                        <i class="fa-solid fa-trash-can"></i>
                    </div>
                </div>
                <i
                    class="fa-solid fa-shield-cat absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
            </div>

            <!-- User Paling Aktif -->
            <div
                class="bg-gradient-to-br from-[#081d34] via-[#0d2a4a] to-[#00a8b5] p-4 rounded-2xl shadow-md text-white relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-teal-200 uppercase tracking-widest">User Teraktif</p>
                        <h3 class="text-sm font-serif font-bold text-inv-mint mt-1 truncate max-w-[150px]">
                            {{ $top_user_name }}</h3>
                        <p class="text-[10px] text-slate-300 font-semibold mt-0.5">{{ number_format($top_user_count) }} Aksi
                            Recorded</p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur-md text-inv-mint flex items-center justify-center text-lg">
                        <i class="fa-solid fa-user-gear"></i>
                    </div>
                </div>
                <i
                    class="fa-solid fa-chart-line-up absolute -right-3 -bottom-3 text-6xl text-white/10 group-hover:scale-110 transition-transform"></i>
            </div>
        </div>

        <!-- 3. FILTER & SEARCH BAR (WITH DATE RANGE) -->
        <div class="bg-slate-200/60 backdrop-blur-md p-4 rounded-2xl border border-slate-300/80">
            <form method="GET" action="{{ route('activity-logs.index') }}"
                class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">

                <!-- Live Search -->
                <div class="relative sm:col-span-4">
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari user, aktivitas, model, atau IP..."
                        class="w-full bg-slate-100 border border-slate-300 rounded-xl pl-9 pr-8 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-inv-teal transition-colors">
                    @if (request('search'))
                        <a href="{{ route('activity-logs.index') }}"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </a>
                    @endif
                </div>

                <!-- Date Range From -->
                <div class="sm:col-span-2">
                    <input type="date" name="from_date" value="{{ request('from_date') }}" title="Dari Tanggal"
                        class="w-full bg-slate-100 border border-slate-300 text-slate-700 text-xs rounded-xl p-2 outline-none focus:border-inv-teal">
                </div>

                <!-- Date Range To -->
                <div class="sm:col-span-2">
                    <input type="date" name="to_date" value="{{ request('to_date') }}" title="Sampai Tanggal"
                        class="w-full bg-slate-100 border border-slate-300 text-slate-700 text-xs rounded-xl p-2 outline-none focus:border-inv-teal">
                </div>

                <!-- Select Activity Type -->
                <div class="sm:col-span-2">
                    <select name="activity_type" onchange="this.form.submit()"
                        class="w-full bg-slate-100 border border-slate-300 text-slate-700 text-xs rounded-xl p-2 outline-none focus:border-inv-teal">
                        <option value="">-- Jenis Aktivitas --</option>
                        <option value="Tambah" {{ request('activity_type') == 'Tambah' ? 'selected' : '' }}>Tambah (Create)
                        </option>
                        <option value="Ubah" {{ request('activity_type') == 'Ubah' ? 'selected' : '' }}>Ubah (Update)
                        </option>
                        <option value="Hapus" {{ request('activity_type') == 'Hapus' ? 'selected' : '' }}>Hapus (Delete)
                        </option>
                    </select>
                </div>

                <!-- Action Filter -->
                <div class="sm:col-span-2 flex items-center gap-1.5">
                    <button type="submit"
                        class="w-full bg-inv-teal hover:bg-inv-hover text-white font-bold py-2 px-3 rounded-xl text-xs transition cursor-pointer">
                        Filter
                    </button>
                    @if (request('from_date') || request('to_date') || request('activity_type') || request('search'))
                        <a href="{{ route('activity-logs.index') }}"
                            class="bg-slate-300 hover:bg-slate-400 text-slate-700 font-bold p-2 rounded-xl text-xs transition"
                            title="Reset Filter">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- 4. TABEL SYSTEM ACTIVITY LOG -->
        <div class="bg-slate-200/60 backdrop-blur-md rounded-2xl border border-slate-300/80 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-max">
                    <thead class="bg-slate-300/60 border-b border-slate-300">
                        <tr>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Waktu
                            </th>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Pengguna
                            </th>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Aktivitas
                            </th>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Entitas
                                (Model)</th>
                            <th
                                class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest text-center">
                                Detail Properties</th>
                            <th class="px-5 py-3.5 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Alamat IP
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-300/60">
                        @forelse ($logs as $log)
                            <tr class="hover:bg-slate-300/40 transition-colors text-xs">
                                <!-- Waktu -->
                                <td class="px-5 py-3.5 text-slate-700 font-bold whitespace-nowrap">
                                    {{ $log->created_at ? $log->created_at->format('d/m/Y H:i') : '-' }}
                                </td>

                                <!-- User -->
                                <td class="px-5 py-3.5 font-bold text-slate-800">
                                    {{ $log->user->name ?? 'System / Automatic' }}
                                </td>

                                <!-- Aktivitas -->
                                <td class="px-5 py-3.5">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ str_contains(strtolower($log->activity), 'hapus') || str_contains(strtolower($log->activity), 'delete') ? 'bg-rose-500/10 text-rose-700 border border-rose-500/30' : 'bg-inv-teal/10 text-inv-teal border border-inv-teal/30' }}">
                                        {{ $log->activity }}
                                    </span>
                                </td>

                                <!-- Model -->
                                <td class="px-5 py-3.5 text-slate-600 font-medium">
                                    {{ class_basename($log->model_type) }}
                                    <span class="text-slate-400 text-[10px]">(ID: {{ $log->model_id }})</span>
                                </td>

                                <!-- Detail Properties Button -->
                                <td class="px-5 py-3.5 text-center">
                                    @if ($log->properties)
                                        <button @click="activeLog = {{ json_encode($log) }}; openModal = true"
                                            class="p-1.5 text-inv-teal hover:bg-slate-300 rounded-lg transition text-xs cursor-pointer shadow-sm"
                                            title="Lihat Detail Properties">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </button>
                                    @else
                                        <span class="text-slate-400 italic text-[11px]">-</span>
                                    @endif
                                </td>

                                <!-- IP Address -->
                                <td class="px-5 py-3.5 font-mono text-[11px] text-slate-500">
                                    {{ $log->ip_address ?? '127.0.0.1' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-clock-rotate-left text-3xl mb-2 opacity-30"></i>
                                    <p class="text-xs italic">Belum ada rekam jejak aktivitas sistem yang tersimpan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (request('per_page') != 'all')
                <div class="px-5 py-3 bg-slate-300/40 border-t border-slate-300">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>

        <!-- 5. MODAL DETAIL PROPERTIES (ALPINE.JS) -->
        <div x-show="openModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;">

            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openModal = false"></div>

                <div
                    class="relative bg-slate-100 rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden border border-slate-300 z-10">
                    <!-- Modal Header -->
                    <div
                        class="bg-gradient-to-r from-inv-teal to-inv-primary p-5 text-white flex justify-between items-center">
                        <div>
                            <h3 class="font-serif font-bold text-base" x-text="activeLog.activity"></h3>
                            <p class="text-[11px] text-slate-200 mt-0.5"
                                x-text="'Ref: ' + activeLog.model_type + ' (ID: ' + activeLog.model_id + ')'"></p>
                        </div>
                        <button @click="openModal = false"
                            class="text-white/70 hover:text-white transition cursor-pointer">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4 pb-4 border-b border-slate-300 text-xs">
                            <div>
                                <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">IP
                                    Address</span>
                                <span
                                    class="font-mono text-slate-800 bg-white border border-slate-300 px-2.5 py-1 rounded-lg inline-block"
                                    x-text="activeLog.ip_address"></span>
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">User
                                    Agent / Browser</span>
                                <span class="text-slate-600 line-clamp-2 text-[11px]"
                                    x-text="activeLog.user_agent || '-'"></span>
                            </div>
                        </div>

                        <div>
                            <span class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-2">Data
                                Changes Payload (JSON Properties)</span>
                            <div
                                class="bg-slate-900 rounded-2xl p-4 overflow-x-auto shadow-inner max-h-72 custom-scrollbar">
                                <pre class="text-inv-mint font-mono text-xs leading-relaxed" x-text="JSON.stringify(activeLog.properties, null, 4)"></pre>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button @click="openModal = false"
                                class="w-full bg-slate-300 hover:bg-slate-400 text-slate-800 font-bold py-2.5 rounded-xl transition text-xs uppercase tracking-wider cursor-pointer">
                                Tutup Detail
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- SWEETALERT -->
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
    </script>
@endsection
