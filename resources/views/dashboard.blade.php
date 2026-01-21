@extends('layouts.app')

@section('content')
    <div class="space-y-8">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-blue-50 relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-xs font-bold text-slate-400 uppercase font-domine tracking-wider">Total Produk</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-2">{{ $total_products }}</h3>
                    <div
                        class="mt-4 flex items-center text-[10px] font-bold text-arindama bg-blue-50 w-max px-2 py-1 rounded-lg">
                        <i class="fa-solid fa-box mr-1"></i> Data Barang
                    </div>
                </div>
                <i
                    class="fa-solid fa-boxes-stacked absolute -right-4 -bottom-4 text-7xl text-slate-50 group-hover:text-blue-50 transition-colors"></i>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-blue-50 relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-xs font-bold text-slate-400 uppercase font-domine tracking-wider">Kategori</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-2">{{ $total_categories }}</h3>
                    <div
                        class="mt-4 flex items-center text-[10px] font-bold text-purple-600 bg-purple-50 w-max px-2 py-1 rounded-lg">
                        <i class="fa-solid fa-tag mr-1"></i> Klasifikasi
                    </div>
                </div>
                <i
                    class="fa-solid fa-tags absolute -right-4 -bottom-4 text-7xl text-slate-50 group-hover:text-purple-50 transition-colors"></i>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-blue-50 relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-xs font-bold text-slate-400 uppercase font-domine tracking-wider">Suppliers</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-2">{{ $total_suppliers }}</h3>
                    <div
                        class="mt-4 flex items-center text-[10px] font-bold text-orange-600 bg-orange-50 w-max px-2 py-1 rounded-lg">
                        <i class="fa-solid fa-handshake mr-1"></i> Mitra Kerja
                    </div>
                </div>
                <i
                    class="fa-solid fa-truck-ramp-box absolute -right-4 -bottom-4 text-7xl text-slate-50 group-hover:text-orange-50 transition-colors"></i>
            </div>

            <div class="bg-red-500 p-6 rounded-3xl shadow-lg shadow-red-200 relative overflow-hidden">
                <div class="relative z-10 text-white">
                    <p class="text-xs font-bold opacity-80 uppercase font-domine tracking-wider">Stok Menipis</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $low_stock }}</h3>
                    <p class="mt-4 text-[10px] font-bold bg-white/20 w-max px-2 py-1 rounded-lg italic">Segera Re-stock</p>
                </div>
                <i class="fa-solid fa-triangle-exclamation absolute -right-4 -bottom-4 text-7xl text-white/10"></i>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
                <h4 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-arindama rounded-full"></span> Riwayat Stok Masuk
                </h4>
                <div class="space-y-4">
                    @forelse($recent_entries as $entry)
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm text-green-600">
                                    <i class="fa-solid fa-arrow-trend-up"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $entry->product->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-domine">{{ $entry->created_at->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                            <span class="font-bold text-green-600">+{{ $entry->quantity }}</span>
                        </div>
                    @empty
                        <p class="text-center py-6 text-slate-400 italic text-sm">Belum ada data masuk.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
                <h4 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-red-500 rounded-full"></span> Riwayat Stok Keluar
                </h4>
                <div class="space-y-4">
                    @forelse($recent_exits as $exit)
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm text-red-500">
                                    <i class="fa-solid fa-arrow-trend-down"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $exit->product->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-domine">{{ $exit->created_at->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                            <span class="font-bold text-red-600">-{{ $exit->quantity }}</span>
                        </div>
                    @empty
                        <p class="text-center py-6 text-slate-400 italic text-sm">Belum ada data keluar.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection