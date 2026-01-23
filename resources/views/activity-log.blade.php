@extends('layouts.app')

@section('content')
    <div x-data="{ openModal: false, activeLog: {} }" class="space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-tight">System Activity Log</h2>

            <p class="text-xs text-blue-100/70 font-acme uppercase tracking-[0.2em] mt-1">
                Audit Trail & Perubahan Data
            </p>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse text-xs">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-bold text-slate-400 uppercase">Waktu</th>
                        <th class="px-6 py-4 font-bold text-slate-400 uppercase">User</th>
                        <th class="px-6 py-4 font-bold text-slate-400 uppercase">Aktivitas</th>
                        <th class="px-6 py-4 font-bold text-slate-400 uppercase">Model</th>
                        <th class="px-6 py-4 font-bold text-slate-400 uppercase text-center">Detail</th>
                        <th class="px-6 py-4 font-bold text-slate-400 uppercase">Alamat IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($logs as $log)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 text-slate-500 whitespace-nowrap">
                                {{ $log->created_at->format('d/m/y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-800">{{ $log->user->name ?? 'System' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 rounded-md {{ str_contains($log->activity, 'Menghapus') ? 'bg-red-50 text-red-600' : 'bg-blue-50 text-blue-600' }} font-semibold">
                                    {{ $log->activity }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ class_basename($log->model_type) }} (ID: {{ $log->model_id }})
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($log->properties)
                                    <button @click="activeLog = {{ json_encode($log) }}; openModal = true"
                                        class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition flex items-center justify-center mx-auto">
                                        <i class="fa-solid fa-magnifying-glass text-[10px]"></i>
                                    </button>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-[10px] text-slate-400 font-mono">
                                {{ $log->ip_address }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-slate-50">{{ $logs->links() }}</div>
        </div>

        <div x-show="openModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">

            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openModal = false"></div>

                <div
                    class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all">
                    <div class="bg-slate-800 p-6 text-white flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-lg" x-text="activeLog.activity"></h3>
                            <p class="text-xs text-slate-400"
                                x-text="'Ref: ' + activeLog.model_type + ' (ID: ' + activeLog.model_id + ')'"></p>
                        </div>
                        <button @click="openModal = false" class="text-white/50 hover:text-white transition">
                            <i class="fa-solid fa-circle-xmark text-xl"></i>
                        </button>
                    </div>

                    <div class="p-8">
                        <div class="space-y-6">
                            <div class="grid grid-cols-2 gap-4 pb-6 border-b border-slate-100 text-xs">
                                <div>
                                    <span class="block text-slate-400 uppercase font-bold tracking-widest mb-1">IP
                                        Address</span>
                                    <span class="font-mono text-slate-700" x-text="activeLog.ip_address"></span>
                                </div>
                                <div>
                                    <span class="block text-slate-400 uppercase font-bold tracking-widest mb-1">User
                                        Agent</span>
                                    <span class="text-slate-700 line-clamp-1" x-text="activeLog.user_agent"></span>
                                </div>
                            </div>

                            <div>
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Data
                                    Changes / Properties</span>
                                <div class="bg-slate-900 rounded-2xl p-6 overflow-x-auto">
                                    <pre class="text-green-400 font-mono text-xs leading-relaxed"
                                        x-text="JSON.stringify(activeLog.properties, null, 4)"></pre>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8">
                            <button @click="openModal = false"
                                class="w-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-3 rounded-xl transition text-xs uppercase tracking-widest">
                                Tutup Detail
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection