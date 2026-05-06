<aside x-cloak x-show="window.innerWidth >= 1024 ? true : isSideOpen"
    x-transition:enter="transition transform ease-out duration-300" x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0" x-transition:leave="transition transform ease-in duration-300"
    x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
    class="fixed left-0 lg:left-6 top-0 lg:top-6 bottom-0 lg:bottom-6 w-72 bg-primary backdrop-blur-xl lg:rounded-3xl flex flex-col z-50 shadow-2xl border border-secondary/30"
    @click.away="if(window.innerWidth < 1024) isSideOpen = false">

    <!-- Tombol Close Mobile -->
    <div class="lg:hidden absolute right-4 top-4">
        <button @click="isSideOpen = false" class="text-accent hover:text-white transition-colors">
            <i class="fa-solid fa-circle-xmark text-2xl"></i>
        </button>
    </div>

    <!-- Logo Area -->
    <div class="p-2 flex flex-col items-center justify-center">
        <div class="w-40 h-40 bg-background rounded-xl flex items-center justify-center shadow-lg"
            style="-webkit-mask-image: url('{{ asset('img/logo-new.png') }}'); -webkit-mask-size: 70%; -webkit-mask-repeat: no-repeat; -webkit-mask-position: center; mask-image: url('{{ asset('img/logo-new.png') }}'); mask-size: 70%; mask-repeat: no-repeat; mask-position: center;">
        </div>
    </div>

    <!-- Menu Navigasi -->
    <nav class="flex-1 px-5 space-y-2 overflow-y-auto pb-10 custom-scrollbar">

        <!-- MAIN MENU -->
        <div x-data="{ open: {{ request()->routeIs('dashboard', 'activity-log.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2 mt-2 group">
                <p class="text-[10px] font-bold text-accent uppercase tracking-widest">Main Menu</p>
                <i class="fa-solid fa-chevron-down text-[10px] text-accent/70 transition-transform duration-300"
                    :class="open ? 'rotate-0' : '-rotate-90'"></i>
            </button>
            <ul x-show="open" x-collapse class="space-y-1 mt-1">
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-secondary text-white shadow-md' : 'text-background/70 hover:text-white hover:bg-white/10' }}">
                        <i class="fa-solid fa-house w-6 text-[1.1rem]"></i>
                        <span class="ml-3 text-sm font-medium">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('activity-log.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all duration-200 {{ request()->routeIs('activity-log.*') ? 'bg-secondary text-white shadow-md' : 'text-background/70 hover:text-white hover:bg-white/10' }}">
                        <i class="fa-solid fa-clock-rotate-left w-6 text-[1.1rem]"></i>
                        <span class="ml-3 text-sm font-medium">Log Aktivitas</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- INVENTORY -->
        <div x-data="{ open: {{ request()->routeIs('stock-*', 'loans.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2 mt-4 group">
                <p class="text-[10px] font-bold text-accent uppercase tracking-widest">Inventory</p>
                <i class="fa-solid fa-chevron-down text-[10px] text-accent/70 transition-transform duration-300"
                    :class="open ? 'rotate-0' : '-rotate-90'"></i>
            </button>
            <ul x-show="open" x-collapse class="space-y-1 mt-1">
                <li>
                    <a href="{{ route('stock-in.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all duration-200 {{ request()->routeIs('stock-in.*') ? 'bg-secondary text-white shadow-md' : 'text-background/70 hover:text-white hover:bg-white/10' }}">
                        <i class="fa-solid fa-arrow-turn-down w-6 text-[1.1rem]"></i>
                        <span class="ml-3 text-sm font-medium">Barang Masuk</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('stock-out.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all duration-200 {{ request()->routeIs('stock-out.*') ? 'bg-secondary text-white shadow-md' : 'text-background/70 hover:text-white hover:bg-white/10' }}">
                        <i class="fa-solid fa-arrow-turn-up w-6 text-[1.1rem]"></i>
                        <span class="ml-3 text-sm font-medium">Barang Keluar</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('loans.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all duration-200 {{ request()->routeIs('loans.*') ? 'bg-secondary text-white shadow-md' : 'text-background/70 hover:text-white hover:bg-white/10' }}">
                        <i class="fa-solid fa-hand-holding-hand w-6 text-[1.1rem]"></i>
                        <span class="ml-3 text-sm font-medium">Peminjaman</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- MASTER DATA -->
        <div x-data="{ open: {{ request()->routeIs('products.*', 'categories.*', 'suppliers.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2 mt-4 group">
                <p class="text-[10px] font-bold text-accent uppercase tracking-widest">Master Data</p>
                <i class="fa-solid fa-chevron-down text-[10px] text-accent/70 transition-transform duration-300"
                    :class="open ? 'rotate-0' : '-rotate-90'"></i>
            </button>
            <ul x-show="open" x-collapse class="space-y-1 mt-1">
                <li>
                    <a href="{{ route('products.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all duration-200 {{ request()->routeIs('products.*') ? 'bg-secondary text-white shadow-md' : 'text-background/70 hover:text-white hover:bg-white/10' }}">
                        <i class="fa-solid fa-box-open w-6 text-[1.1rem]"></i>
                        <span class="ml-3 text-sm font-medium">Data Produk</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('categories.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all duration-200 {{ request()->routeIs('categories.*') ? 'bg-secondary text-white shadow-md' : 'text-background/70 hover:text-white hover:bg-white/10' }}">
                        <i class="fa-solid fa-tags w-6 text-[1.1rem]"></i>
                        <span class="ml-3 text-sm font-medium">Kategori Barang</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('suppliers.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all duration-200 {{ request()->routeIs('suppliers.*') ? 'bg-secondary text-white shadow-md' : 'text-background/70 hover:text-white hover:bg-white/10' }}">
                        <i class="fa-solid fa-truck-field w-6 text-[1.1rem]"></i>
                        <span class="ml-3 text-sm font-medium">Data Supplier</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- SISTEM -->
        <div x-data="{ open: false }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2 mt-4 group">
                <p class="text-[10px] font-bold text-accent uppercase tracking-widest">Sistem</p>
                <i class="fa-solid fa-chevron-down text-[10px] text-accent/70 transition-transform duration-300"
                    :class="open ? 'rotate-0' : '-rotate-90'"></i>
            </button>
            <ul x-show="open" x-collapse class="space-y-1 mt-1">
                {{-- <li>
                    <a href="#"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all duration-200 text-background/70 hover:text-white hover:bg-white/10">
                        <i class="fa-solid fa-chart-pie w-6 text-[1.1rem]"></i>
                        <span class="ml-3 text-sm font-medium">Laporan Stok</span>
                    </a>
                </li> --}}
                <li>
                    <a href="{{ route('users.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all duration-200 text-background/70 hover:text-white hover:bg-white/10">
                        <i class="fa-solid fa-users-gear w-6 text-[1.1rem]"></i>
                        <span class="ml-3 text-sm font-medium">Manajemen Pengguna</span>
                    </a>
                </li>
            </ul>
        </div>

    </nav>

    <!-- Logout Area -->
    <div class="p-6 border-t border-white/10">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center px-4 py-3 text-accent hover:text-white hover:bg-red-500/80 rounded-xl group transition-all duration-300">
                <i class="fa-solid fa-right-from-bracket w-6 text-[1.1rem]"></i>
                <span class="ml-3 font-semibold text-sm">Logout</span>
            </button>
        </form>
    </div>
</aside>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(159, 203, 152, 0.3);
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(159, 203, 152, 0.6);
    }

    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: rgba(159, 203, 152, 0.3) transparent;
    }
</style>
