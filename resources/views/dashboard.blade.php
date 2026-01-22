@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            <div
                class="bg-white/90 backdrop-blur-md p-6 rounded-[2.5rem] shadow-xl border border-white/20 hover:scale-105 transition-all duration-300 relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-domine">Total Produk</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-2">{{ $total_products }}</h3>
                    <div
                        class="mt-4 flex items-center text-[10px] font-bold text-blue-600 bg-blue-50 w-max px-2 py-1 rounded-lg">
                        <i class="fa-solid fa-box mr-1"></i> Data Barang
                    </div>
                </div>
                <i
                    class="fa-solid fa-boxes-stacked absolute -right-4 -bottom-4 text-6xl text-slate-100 group-hover:text-blue-100/50 transition-colors"></i>
            </div>

            <div
                class="bg-white/90 backdrop-blur-md p-6 rounded-[2.5rem] shadow-xl border border-white/20 hover:scale-105 transition-all duration-300 relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-domine">Kategori</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-2">{{ $total_categories }}</h3>
                    <div
                        class="mt-4 flex items-center text-[10px] font-bold text-purple-600 bg-purple-50 w-max px-2 py-1 rounded-lg">
                        <i class="fa-solid fa-tag mr-1"></i> Klasifikasi
                    </div>
                </div>
                <i
                    class="fa-solid fa-tags absolute -right-4 -bottom-4 text-6xl text-slate-100 group-hover:text-purple-100/50 transition-colors"></i>
            </div>

            <div
                class="bg-white/90 backdrop-blur-md p-6 rounded-[2.5rem] shadow-xl border border-white/20 hover:scale-105 transition-all duration-300 relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-domine">Suppliers</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-2">{{ $total_suppliers }}</h3>
                    <div
                        class="mt-4 flex items-center text-[10px] font-bold text-orange-600 bg-orange-50 w-max px-2 py-1 rounded-lg">
                        <i class="fa-solid fa-handshake mr-1"></i> Mitra Kerja
                    </div>
                </div>
                <i
                    class="fa-solid fa-truck-ramp-box absolute -right-4 -bottom-4 text-6xl text-slate-100 group-hover:text-orange-100/50 transition-colors"></i>
            </div>

            <div
                class="bg-white/90 backdrop-blur-md p-6 rounded-[2.5rem] shadow-xl border border-white/20 hover:scale-105 transition-all duration-300 relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-domine">Aset Dipinjam</p>

                    <div class="flex items-baseline gap-2 mt-2">
                        <h3 class="text-3xl font-bold text-amber-600">{{ $total_borrowed_units }}</h3>
                        <span class="text-xs font-bold text-slate-400 uppercase">Unit</span>
                    </div>

                    <div class="mt-4 flex flex-col gap-2">
                        <div
                            class="flex items-center text-[10px] font-bold text-amber-700 bg-amber-50 w-max px-3 py-1.5 rounded-xl border border-amber-100">
                            <i class="fa-solid fa-layer-group mr-1.5"></i>
                            {{ $total_borrowed_types }} Jenis Barang
                        </div>
                    </div>
                </div>

                <i
                    class="fa-solid fa-hand-holding-box absolute -right-4 -bottom-4 text-6xl text-slate-100 group-hover:text-amber-100/50 transition-colors"></i>
            </div>

            <div
                class="bg-red-500 p-6 rounded-[2.5rem] shadow-xl shadow-red-200/50 hover:scale-105 transition-all duration-300 relative overflow-hidden group">
                <div class="relative z-10 text-white">
                    <p class="text-[10px] font-bold opacity-80 uppercase tracking-widest font-domine">Stok Menipis</p>
                    <h3 class="text-4xl font-bold mt-2">{{ $low_stock }}</h3>
                    <div class="mt-4 flex items-center text-[10px] font-bold bg-white/20 w-max px-2 py-1 rounded-lg italic">
                        <i class="fa-solid fa-triangle-exclamation mr-1"></i> Re-stock
                    </div>
                </div>
                <i
                    class="fa-solid fa-fire-flame-curved absolute -right-4 -bottom-4 text-7xl text-white/10 group-hover:scale-110 transition-transform"></i>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="bg-white/90 backdrop-blur-md p-8 rounded-[3rem] shadow-xl border border-amber-100 flex flex-col">
                <div class="flex items-center gap-4 mb-6">
                    <div class="bg-amber-500 text-white p-4 rounded-2xl shadow-lg shadow-amber-200">
                        <i class="fa-solid fa-bell text-xl animate-bounce"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-blue-900">Jatuh Tempo Besok</h3>
                        <p class="text-xs text-gray-400 font-domine">Segera hubungi peminjam</p>
                    </div>
                </div>

                <div class="space-y-4 flex-1 overflow-y-auto max-h-[350px] pr-2 custom-scrollbar">
                    @forelse($upcomingReturns as $item)
                        <div
                            class="group flex justify-between items-center p-4 bg-amber-50/50 rounded-2xl border border-amber-100 hover:bg-amber-100 transition-all">
                            <div class="text-sm">
                                <p class="font-bold text-amber-900">{{ $item->borrower_name }}</p>
                                <p class="text-[10px] text-amber-700 font-domine mt-0.5">{{ $item->product->name }} •
                                    {{ $item->quantity }} unit
                                </p>
                            </div>

                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 text-center text-gray-400">
                            <i class="fa-solid fa-calendar-check text-4xl mb-3 opacity-20"></i>
                            <p class="text-xs italic">Aman! Tidak ada pengembalian untuk besok.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 p-8">
                <h4 class="font-bold text-slate-800 mb-6 flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-green-500 rounded-full"></span> Riwayat Masuk
                    </span>
                    <i class="fa-solid fa-circle-plus text-green-100 text-2xl"></i>
                </h4>
                <div class="space-y-4">
                    @forelse($recent_entries as $entry)
                        <div
                            class="flex items-center justify-between p-4 bg-slate-50/50 rounded-2xl border border-transparent hover:border-green-100 transition-all">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm text-green-600">
                                    <i class="fa-solid fa-arrow-up-long"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 line-clamp-1">{{ $entry->product->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-domine">{{ $entry->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <span class="font-bold text-green-600 text-sm">+{{ $entry->quantity }}</span>
                        </div>
                    @empty
                        <p class="text-center py-6 text-slate-400 italic text-sm">Belum ada data masuk.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 p-8">
                <h4 class="font-bold text-slate-800 mb-6 flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-red-500 rounded-full"></span> Riwayat Keluar
                    </span>
                    <i class="fa-solid fa-circle-minus text-red-100 text-2xl"></i>
                </h4>
                <div class="space-y-4">
                    @forelse($recent_exits as $exit)
                        <div
                            class="flex items-center justify-between p-4 bg-slate-50/50 rounded-2xl border border-transparent hover:border-red-100 transition-all">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm text-red-500">
                                    <i class="fa-solid fa-arrow-down-long"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 line-clamp-1">{{ $exit->product->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-domine">{{ $exit->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <span class="font-bold text-red-600 text-sm">-{{ $exit->quantity }}</span>
                        </div>
                    @empty
                        <p class="text-center py-6 text-slate-400 italic text-sm">Belum ada data keluar.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection