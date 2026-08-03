@extends('layouts.app')

@section('content')
    <!-- Library ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <div class="space-y-6">

        <!-- BARIS 1: 5 CORE STAT CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

            <!-- Total Produk -->
            <a href="{{ route('products.index') }}"
                class="bg-slate-200/60 backdrop-blur-md p-5 rounded-2xl border border-slate-300/80 hover:border-inv-teal hover:shadow-lg transition-all duration-300 relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Total Produk</p>
                    <h3 class="text-3xl font-serif font-bold text-slate-800 mt-1.5">{{ $total_products }}</h3>
                    <div
                        class="mt-3 inline-flex items-center text-[10px] font-bold text-inv-primary bg-inv-primary/10 px-2.5 py-1 rounded-lg">
                        <i class="fa-solid fa-boxes-stacked mr-1"></i> Data Barang
                    </div>
                </div>
                <i
                    class="fa-solid fa-box-archive absolute -right-3 -bottom-3 text-6xl text-inv-primary/10 group-hover:scale-110 transition-transform"></i>
            </a>

            <!-- Kategori Barang -->
            <a href="{{ route('categories.index') }}"
                class="bg-slate-200/60 backdrop-blur-md p-5 rounded-2xl border border-slate-300/80 hover:border-inv-teal hover:shadow-lg transition-all duration-300 relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Kategori</p>
                    <h3 class="text-3xl font-serif font-bold text-slate-800 mt-1.5">{{ $total_categories }}</h3>
                    <div
                        class="mt-3 inline-flex items-center text-[10px] font-bold text-inv-teal bg-inv-teal/10 px-2.5 py-1 rounded-lg">
                        <i class="fa-solid fa-tags mr-1"></i> Klasifikasi
                    </div>
                </div>
                <i
                    class="fa-solid fa-tag absolute -right-3 -bottom-3 text-6xl text-inv-teal/10 group-hover:scale-110 transition-transform"></i>
            </a>

            <!-- Supplier -->
            <a href="{{ route('suppliers.index') }}"
                class="bg-slate-200/60 backdrop-blur-md p-5 rounded-2xl border border-slate-300/80 hover:border-inv-box hover:shadow-lg transition-all duration-300 relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Supplier</p>
                    <h3 class="text-3xl font-serif font-bold text-slate-800 mt-1.5">{{ $total_suppliers }}</h3>
                    <div
                        class="mt-3 inline-flex items-center text-[10px] font-bold text-inv-box bg-inv-box/10 px-2.5 py-1 rounded-lg">
                        <i class="fa-solid fa-truck-field mr-1"></i> Mitra Kerja
                    </div>
                </div>
                <i
                    class="fa-solid fa-truck-ramp-box absolute -right-3 -bottom-3 text-6xl text-inv-box/10 group-hover:scale-110 transition-transform"></i>
            </a>

            <!-- Aset Dipinjam -->
            <a href="{{ route('loans.index') }}"
                class="bg-slate-200/60 backdrop-blur-md p-5 rounded-2xl border border-slate-300/80 hover:border-amber-500 hover:shadow-lg transition-all duration-300 relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Aset Dipinjam</p>
                    <div class="flex items-baseline gap-1.5 mt-1.5">
                        <h3 class="text-3xl font-serif font-bold text-amber-600">{{ $total_borrowed_units }}</h3>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Unit</span>
                    </div>
                    <div
                        class="mt-3 inline-flex items-center text-[10px] font-bold text-amber-700 bg-amber-500/10 px-2.5 py-1 rounded-lg">
                        <i class="fa-solid fa-hand-holding-box mr-1"></i> {{ $total_borrowed_types }} Jenis Barang
                    </div>
                </div>
                <i
                    class="fa-solid fa-handshake-angle absolute -right-3 -bottom-3 text-6xl text-amber-500/10 group-hover:scale-110 transition-transform"></i>
            </a>

            <!-- Stok Menipis -->
            <a href="{{ route('products.index') }}"
                class="bg-gradient-to-br from-red-500 to-rose-600 p-5 rounded-2xl shadow-lg shadow-red-500/20 text-white hover:scale-[1.02] transition-all duration-300 relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-[10px] font-bold text-red-100 uppercase tracking-widest">Stok Menipis</p>
                    <h3 class="text-3xl font-serif font-bold mt-1.5">{{ $low_stock }}</h3>
                    <div
                        class="mt-3 inline-flex items-center text-[10px] font-bold bg-white/20 px-2.5 py-1 rounded-lg backdrop-blur-sm">
                        <i class="fa-solid fa-triangle-exclamation mr-1"></i> Restock Segera
                    </div>
                </div>
                <i
                    class="fa-solid fa-fire-flame-curved absolute -right-3 -bottom-3 text-6xl text-white/15 group-hover:scale-110 transition-transform"></i>
            </a>

        </div>

        <!-- BARIS 2: KARTU ANALISIS TAMBAHAN -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <!-- Health Rate Card -->
            <div
                class="bg-slate-200/60 backdrop-blur-md p-4 rounded-2xl border border-slate-300/80 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Kesehatan Stok</p>
                    <h4 class="text-2xl font-serif font-bold text-emerald-600 mt-1">{{ $stock_health_rate }}%</h4>
                    <p class="text-[10px] text-slate-500 mt-0.5">Stok dalam batas aman</p>
                </div>
                <div
                    class="w-11 h-11 rounded-2xl bg-emerald-500/15 text-emerald-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-heart-pulse"></i>
                </div>
            </div>

            <!-- Monthly Movement Card -->
            <div
                class="bg-slate-200/60 backdrop-blur-md p-4 rounded-2xl border border-slate-300/80 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Lalu Lintas Bulan Ini</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs font-bold text-emerald-600">+{{ $monthly_entries_qty }} In</span>
                        <span class="text-slate-300">|</span>
                        <span class="text-xs font-bold text-rose-600">-{{ $monthly_exits_qty }} Out</span>
                    </div>
                    <p class="text-[10px] text-slate-500 mt-0.5">Total pergerakan fisik</p>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-inv-teal/15 text-inv-teal flex items-center justify-center text-lg">
                    <i class="fa-solid fa-arrow-up-right-dots"></i>
                </div>
            </div>

            <!-- Overdue Loans Card -->
            <div
                class="bg-slate-200/60 backdrop-blur-md p-4 rounded-2xl border border-slate-300/80 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Pinjaman Terlambat</p>
                    <h4
                        class="text-2xl font-serif font-bold {{ $overdue_loans_count > 0 ? 'text-rose-600' : 'text-slate-700' }} mt-1">
                        {{ $overdue_loans_count }} <span class="text-xs font-normal text-slate-400">Unit</span>
                    </h4>
                    <p class="text-[10px] text-slate-500 mt-0.5">Lewat tanggal pengembalian</p>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-rose-500/15 text-rose-500 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-user-clock"></i>
                </div>
            </div>

            <!-- Cart Requests Pending -->
            <div
                class="bg-slate-200/60 backdrop-blur-md p-4 rounded-2xl border border-slate-300/80 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Antrean Permintaan</p>
                    <h4 class="text-2xl font-serif font-bold text-inv-box mt-1">
                        {{ $pending_cart_requests }} <span class="text-xs font-normal text-slate-400">Pending</span>
                    </h4>
                    <p class="text-[10px] text-slate-500 mt-0.5">Menunggu verifikasi</p>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-inv-box/15 text-inv-box flex items-center justify-center text-lg">
                    <i class="fa-solid fa-cart-flatbed"></i>
                </div>
            </div>

        </div>

        <!-- BARIS 3: GRAPH / CHARTS SECTION -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Line Chart: Arus Barang 6 Bulan -->
            <div class="lg:col-span-2 bg-slate-200/60 backdrop-blur-md p-5 rounded-2xl border border-slate-300/80">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="font-serif font-bold text-slate-800 text-base">Arus Barang Masuk vs Keluar</h3>
                        <p class="text-[11px] text-slate-500">Rekapitulasi transaksi 6 bulan terakhir</p>
                    </div>
                    <div class="w-8 h-8 rounded-xl bg-inv-teal/10 text-inv-teal flex items-center justify-center text-xs">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                </div>
                <div id="monthlyFlowChart" class="min-h-[280px]"></div>
            </div>

            <!-- Donut Chart: Komposisi Kategori -->
            <div class="bg-slate-200/60 backdrop-blur-md p-5 rounded-2xl border border-slate-300/80">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="font-serif font-bold text-slate-800 text-base">Sebaran Kategori</h3>
                        <p class="text-[11px] text-slate-500">Persentase produk per kategori</p>
                    </div>
                    <div class="w-8 h-8 rounded-xl bg-inv-mint/10 text-inv-teal flex items-center justify-center text-xs">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                </div>
                <div id="categoryChart" class="min-h-[280px] flex items-center justify-center"></div>
            </div>

        </div>

        <!-- BARIS 4: CHART TOP PRODUCTS & JATUH TEMPO / RECENT LISTS -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Fast Moving Products Bar Chart -->
            <div class="bg-slate-200/60 backdrop-blur-md p-5 rounded-2xl border border-slate-300/80">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="font-serif font-bold text-slate-800 text-base">Top 5 Barang Fast Moving</h3>
                        <p class="text-[11px] text-slate-500">Paling sering keluar/dipakai</p>
                    </div>
                    <div class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-fire"></i>
                    </div>
                </div>
                <div id="fastMovingChart" class="min-h-[260px]"></div>
            </div>

            <!-- Jatuh Tempo Besok Card List -->
            <div class="bg-slate-200/60 backdrop-blur-md p-5 rounded-2xl border border-slate-300/80 flex flex-col">
                <div class="flex items-center gap-3 mb-4">
                    <div
                        class="w-9 h-9 bg-amber-500/15 text-amber-600 rounded-xl flex items-center justify-center text-sm">
                        <i class="fa-solid fa-bell animate-bounce"></i>
                    </div>
                    <div>
                        <h3 class="font-serif font-bold text-slate-800 text-sm">Jatuh Tempo Pengembalian Besok</h3>
                        <p class="text-[10px] text-slate-500">Segera hubungi peminjam</p>
                    </div>
                </div>

                <div class="space-y-2.5 flex-1 overflow-y-auto max-h-[240px] pr-1 custom-scrollbar">
                    @forelse($upcomingReturns as $item)
                        <div
                            class="p-3 bg-amber-500/10 rounded-xl border border-amber-500/20 flex items-center justify-between">
                            <div>
                                <p class="font-bold text-xs text-slate-800">{{ $item->borrower_name }}</p>
                                <p class="text-[10px] text-amber-700 mt-0.5">{{ $item->product->name }} •
                                    {{ $item->quantity }} unit</p>
                            </div>
                            <span
                                class="text-[9px] font-bold text-amber-700 bg-amber-200/60 px-2 py-0.5 rounded-full">Besok</span>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-10 text-center text-slate-400">
                            <i class="fa-solid fa-calendar-check text-3xl mb-2 opacity-30"></i>
                            <p class="text-xs italic">Aman! Tidak ada batas pengembalian besok.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Entries & Exits Activity Split -->
            <div class="bg-slate-200/60 backdrop-blur-md p-5 rounded-2xl border border-slate-300/80 space-y-4">

                <!-- Riwayat Masuk Terbaru -->
                <div>
                    <p
                        class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-arrow-down-to-bracket text-xs"></i> Barang Masuk Terbaru
                    </p>
                    <div class="space-y-1.5">
                        @forelse($recent_entries->take(3) as $entry)
                            <div
                                class="flex items-center justify-between p-2 rounded-lg bg-slate-100/80 border border-slate-300/60 text-xs">
                                <span
                                    class="font-semibold text-slate-800 truncate max-w-[160px]">{{ $entry->product->name }}</span>
                                <span class="font-bold text-emerald-600">+{{ $entry->quantity }}</span>
                            </div>
                        @empty
                            <p class="text-[10px] text-slate-400 italic">Belum ada aktivitas.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Riwayat Keluar Terbaru -->
                <div class="pt-2 border-t border-slate-300/60">
                    <p class="text-[11px] font-bold text-rose-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-arrow-up-from-bracket text-xs"></i> Barang Keluar Terbaru
                    </p>
                    <div class="space-y-1.5">
                        @forelse($recent_exits->take(3) as $exit)
                            <div
                                class="flex items-center justify-between p-2 rounded-lg bg-slate-100/80 border border-slate-300/60 text-xs">
                                <span
                                    class="font-semibold text-slate-800 truncate max-w-[160px]">{{ $exit->product->name }}</span>
                                <span class="font-bold text-rose-600">-{{ $exit->quantity }}</span>
                            </div>
                        @empty
                            <p class="text-[10px] text-slate-400 italic">Belum ada aktivitas.</p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- SCRIPT APEXCHARTS DENGAN PALET WARNA INVENTORY -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // Palet Warna Brand Inventory
            const COLOR_TEAL = '#00a8b5';
            const COLOR_MINT = '#2dd4bf';
            const COLOR_BOX = '#2563eb';
            const COLOR_PRIMARY = '#0c66c8';
            const COLOR_AMBER = '#f59e0b';
            const COLOR_ROSE = '#ef4444';

            // 1. LINE CHART: ARUS BARANG
            const flowOptions = {
                series: [{
                    name: 'Barang Masuk',
                    data: @json($monthlyFlowChart['entries'])
                }, {
                    name: 'Barang Keluar',
                    data: @json($monthlyFlowChart['exits'])
                }],
                chart: {
                    type: 'area',
                    height: 260,
                    toolbar: {
                        show: false
                    },
                    fontFamily: 'Outfit, sans-serif'
                },
                colors: [COLOR_TEAL, COLOR_ROSE],
                fill: {
                    type: 'gradient',
                    gradient: {
                        opacityFrom: 0.45,
                        opacityTo: 0.05
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                xaxis: {
                    categories: @json($monthlyFlowChart['categories']),
                    labels: {
                        style: {
                            colors: '#64748b',
                            fontSize: '11px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#64748b',
                            fontSize: '11px'
                        }
                    }
                },
                grid: {
                    borderColor: '#cbd5e1',
                    strokeDashArray: 4
                },
                tooltip: {
                    theme: 'light'
                }
            };
            new ApexCharts(document.querySelector("#monthlyFlowChart"), flowOptions).render();


            // 2. DONUT CHART: KOMPOSISI KATEGORI
            const categoryOptions = {
                series: @json($categoryDistributionChart['series']),
                labels: @json($categoryDistributionChart['labels']),
                chart: {
                    type: 'donut',
                    height: 260,
                    fontFamily: 'Outfit, sans-serif'
                },
                colors: [COLOR_TEAL, COLOR_BOX, COLOR_MINT, COLOR_AMBER, COLOR_PRIMARY],
                legend: {
                    position: 'bottom',
                    fontSize: '11px',
                    labels: {
                        colors: '#334155'
                    }
                },
                dataLabels: {
                    enabled: true
                },
                stroke: {
                    show: false
                }
            };
            new ApexCharts(document.querySelector("#categoryChart"), categoryOptions).render();


            // 3. BAR CHART HORIZONTAL: FAST MOVING PRODUCTS
            const fastMovingOptions = {
                series: [{
                    name: 'Jumlah Keluar',
                    data: @json($fastMovingProductsChart['series'])
                }],
                chart: {
                    type: 'bar',
                    height: 240,
                    toolbar: {
                        show: false
                    },
                    fontFamily: 'Outfit, sans-serif'
                },
                plotOptions: {
                    bar: {
                        borderRadius: 6,
                        horizontal: true,
                        barHeight: '50%'
                    }
                },
                colors: [COLOR_MINT],
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: @json($fastMovingProductsChart['labels']),
                    labels: {
                        style: {
                            colors: '#64748b',
                            fontSize: '10px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#334155',
                            fontSize: '11px'
                        }
                    }
                },
                grid: {
                    borderColor: '#cbd5e1',
                    strokeDashArray: 4
                }
            };
            new ApexCharts(document.querySelector("#fastMovingChart"), fastMovingOptions).render();

        });
    </script>
@endsection
