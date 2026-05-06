@extends('layouts.app')

@section('content')
    <div x-data="{ openModal: false, activeLog: {} }" class="space-y-6">
        <!-- Header Section -->
        <div>
            <h2 class="text-2xl lg:text-3xl font-bold text-primary tracking-tight font-serif">System Activity Log</h2>
            <p class="text-xs text-secondary font-sans uppercase tracking-[0.2em] mt-2 font-semibold">
                Audit Trail & Perubahan Data
            </p>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-accent/30 overflow-hidden">
            <table class="w-full text-left border-collapse text-xs">
                <thead class="bg-primary/5 border-b border-accent/20">
                    <tr>
                        <th class="px-6 py-4 font-bold text-primary uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-4 font-bold text-primary uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 font-bold text-primary uppercase tracking-wider">Aktivitas</th>
                        <th class="px-6 py-4 font-bold text-primary uppercase tracking-wider">Model</th>
                        <th class="px-6 py-4 font-bold text-primary uppercase tracking-wider text-center">Detail</th>
                        <th class="px-6 py-4 font-bold text-primary uppercase tracking-wider">Alamat IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 font-sans">
                    @foreach ($logs as $log)
                        <tr class="hover:bg-primary/5 transition duration-200">
                            <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                                {{ $log->created_at->format('d/m/y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-800">{{ $log->user->name ?? 'System' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1.5 rounded-md text-[10px] uppercase tracking-wider {{ str_contains($log->activity, 'Menghapus') ? 'bg-red-50 text-red-600' : 'bg-accent/30 text-primary' }} font-bold">
                                    {{ $log->activity }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ class_basename($log->model_type) }} <span class="text-gray-400 text-[10px]">(ID:
                                    {{ $log->model_id }})</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($log->properties)
                                    <button @click="activeLog = {{ json_encode($log) }}; openModal = true"
                                        class="w-8 h-8 rounded-lg bg-secondary/20 text-primary hover:bg-primary hover:text-white transition-all flex items-center justify-center mx-auto shadow-sm">
                                        <i class="fa-solid fa-magnifying-glass text-[11px]"></i>
                                    </button>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-[10px] text-gray-400 font-mono">
                                {{ $log->ip_address }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-50">{{ $logs->links() }}</div>
        </div>

        <!-- Modal Detail -->
        <div x-show="openModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">

            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-primary/80 backdrop-blur-sm" @click="openModal = false"></div>

                <div
                    class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all border border-accent/20">
                    <div class="bg-primary p-6 text-white flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-lg font-serif" x-text="activeLog.activity"></h3>
                            <p class="text-xs text-accent mt-1"
                                x-text="'Ref: ' + activeLog.model_type + ' (ID: ' + activeLog.model_id + ')'"></p>
                        </div>
                        <button @click="openModal = false"
                            class="text-accent hover:text-white transition transform hover:rotate-90">
                            <i class="fa-solid fa-circle-xmark text-2xl"></i>
                        </button>
                    </div>

                    <div class="p-8">
                        <div class="space-y-6">
                            <div class="grid grid-cols-2 gap-4 pb-6 border-b border-gray-100 text-xs font-sans">
                                <div>
                                    <span class="block text-gray-400 uppercase font-bold tracking-widest mb-1">IP
                                        Address</span>
                                    <span class="font-mono text-gray-800 bg-gray-50 px-2 py-1 rounded"
                                        x-text="activeLog.ip_address"></span>
                                </div>
                                <div>
                                    <span class="block text-gray-400 uppercase font-bold tracking-widest mb-1">User
                                        Agent</span>
                                    <span class="text-gray-600 line-clamp-2" x-text="activeLog.user_agent"></span>
                                </div>
                            </div>

                            <div>
                                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Data
                                    Changes / Properties</span>
                                <div class="bg-gray-900 rounded-2xl p-6 overflow-x-auto shadow-inner">
                                    <pre class="text-accent font-mono text-xs leading-relaxed" x-text="JSON.stringify(activeLog.properties, null, 4)"></pre>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8">
                            <button @click="openModal = false"
                                class="w-full bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold py-3.5 rounded-xl transition text-xs uppercase tracking-widest border border-gray-200">
                                Tutup Detail
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
