<div class="fixed bottom-5 left-1/2 -translate-x-1/2 z-50 w-auto">
    <nav
        class="bg-inv-navy/90 backdrop-blur-2xl border border-inv-teal/40 rounded-full px-4 py-2.5 flex items-center gap-2 lg:gap-3 dock-glow relative">

        <!-- DOCK ITEM 1: Dashboard -->
        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-2 px-4 py-2 rounded-full transition-all duration-300 relative group {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-inv-teal to-inv-box text-white font-semibold shadow-lg shadow-inv-teal/30' : 'text-inv-muted hover:text-white hover:bg-white/5' }}">
            <i class="fa-solid fa-house-chimney text-sm"></i>
            <span class="text-xs hidden sm:inline">Dashboard</span>
        </a>

        <!-- DOCK ITEM 2: Activity Log -->
        <a href="{{ route('activity-log.index') }}"
            class="flex items-center gap-2 px-4 py-2 rounded-full transition-all duration-300 relative group {{ request()->routeIs('activity-log.*') ? 'bg-gradient-to-r from-inv-teal to-inv-box text-white font-semibold shadow-lg shadow-inv-teal/30' : 'text-inv-muted hover:text-white hover:bg-white/5' }}">
            <i class="fa-solid fa-clock-rotate-left text-sm"></i>
            <span class="text-xs hidden sm:inline">Log</span>
        </a>

        <div class="h-5 w-[1px] bg-white/10 my-auto"></div>

        <!-- DOCK ITEM 3: Inventory Dropup Menu -->
        <div class="relative" x-data="{ openMenu: false }">
            <button @click="openMenu = !openMenu"
                class="flex items-center gap-2 px-4 py-2 rounded-full transition-all duration-300 relative {{ request()->routeIs('stock-*', 'loans.*') ? 'bg-gradient-to-r from-inv-teal to-inv-box text-white font-semibold shadow-lg shadow-inv-teal/30' : 'text-inv-muted hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-boxes-packing text-sm"></i>
                <span class="text-xs hidden sm:inline">Inventory</span>
                <i class="fa-solid fa-chevron-up text-[9px] transition-transform duration-300"
                    :class="openMenu ? 'rotate-180' : 'rotate-0'"></i>
            </button>

            <!-- Dropup Card -->
            <div x-show="openMenu" @click.away="openMenu = false" x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="absolute bottom-full mb-3 left-1/2 -translate-x-1/2 w-48 bg-inv-navy/95 backdrop-blur-2xl rounded-2xl border border-inv-teal/40 p-2 shadow-2xl z-50">

                <a href="{{ route('stock-in.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs text-inv-muted hover:text-white hover:bg-inv-teal/20 transition-colors">
                    <i class="fa-solid fa-arrow-down-to-bracket text-inv-mint"></i>
                    <span>Barang Masuk</span>
                </a>
                <a href="{{ route('stock-out.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs text-inv-muted hover:text-white hover:bg-inv-teal/20 transition-colors">
                    <i class="fa-solid fa-arrow-up-from-bracket text-inv-mint"></i>
                    <span>Barang Keluar</span>
                </a>
                <a href="{{ route('loans.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs text-inv-muted hover:text-white hover:bg-inv-teal/20 transition-colors">
                    <i class="fa-solid fa-hand-holding-box text-inv-mint"></i>
                    <span>Peminjaman Aset</span>
                </a>
            </div>
        </div>

        <!-- DOCK ITEM 4: Master Data Dropup Menu -->
        <div class="relative" x-data="{ openMenu: false }">
            <button @click="openMenu = !openMenu"
                class="flex items-center gap-2 px-4 py-2 rounded-full transition-all duration-300 relative {{ request()->routeIs('products.*', 'categories.*', 'suppliers.*') ? 'bg-gradient-to-r from-inv-teal to-inv-box text-white font-semibold shadow-lg shadow-inv-teal/30' : 'text-inv-muted hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-database text-sm"></i>
                <span class="text-xs hidden sm:inline">Master Data</span>
                <i class="fa-solid fa-chevron-up text-[9px] transition-transform duration-300"
                    :class="openMenu ? 'rotate-180' : 'rotate-0'"></i>
            </button>

            <!-- Dropup Card -->
            <div x-show="openMenu" @click.away="openMenu = false" x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="absolute bottom-full mb-3 left-1/2 -translate-x-1/2 w-48 bg-inv-navy/95 backdrop-blur-2xl rounded-2xl border border-inv-teal/40 p-2 shadow-2xl z-50">

                <a href="{{ route('products.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs text-inv-muted hover:text-white hover:bg-inv-teal/20 transition-colors">
                    <i class="fa-solid fa-boxes-stacked text-inv-mint"></i>
                    <span>Data Produk</span>
                </a>
                <a href="{{ route('categories.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs text-inv-muted hover:text-white hover:bg-inv-teal/20 transition-colors">
                    <i class="fa-solid fa-tags text-inv-mint"></i>
                    <span>Kategori Barang</span>
                </a>
                <a href="{{ route('suppliers.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs text-inv-muted hover:text-white hover:bg-inv-teal/20 transition-colors">
                    <i class="fa-solid fa-truck-ramp-box text-inv-mint"></i>
                    <span>Data Supplier</span>
                </a>
            </div>
        </div>

        <div class="h-5 w-[1px] bg-white/10 my-auto"></div>

        <!-- DOCK ITEM 5: Users System -->
        <a href="{{ route('users.index') }}"
            class="flex items-center gap-2 px-4 py-2 rounded-full transition-all duration-300 relative group {{ request()->routeIs('users.*') ? 'bg-gradient-to-r from-inv-teal to-inv-box text-white font-semibold shadow-lg shadow-inv-teal/30' : 'text-inv-muted hover:text-white hover:bg-white/5' }}">
            <i class="fa-solid fa-users-gear text-sm"></i>
            <span class="text-xs hidden sm:inline">Pengguna</span>
        </a>

    </nav>
</div>
