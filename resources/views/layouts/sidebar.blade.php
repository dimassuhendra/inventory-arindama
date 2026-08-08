<aside x-cloak
    :class="[
        isCollapsed ? 'lg:w-20' : 'lg:w-72',
        isMobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
    ]"
    class="fixed lg:static left-0 top-0 bottom-0 z-50 h-screen bg-gradient-to-b from-inv-dark via-[#0a233f] to-inv-dark text-white flex flex-col justify-between transition-all duration-300 border-r border-white/10 shadow-xl">

    <!-- 1. Header Sidebar: Logo Inventory.png & Toggle Button -->
    <div class="p-5 flex items-center border-b border-white/10 relative"
        :class="isCollapsed ? 'justify-center' : 'justify-between'">

        <!-- Logo & Brand (Tampil saat terbuka) -->
        <div class="flex items-center gap-3 overflow-hidden" x-show="!isCollapsed">
            <img src="{{ asset('img/Inventory.png') }}" alt="Mybolo Inventory"
                class="w-10 h-10 object-contain filter drop-shadow">
            <div class="flex flex-col whitespace-nowrap">
                <span class="text-base font-serif font-bold text-white leading-none">Mybolo</span>
                <span class="text-[10px] font-semibold text-inv-mint tracking-wider uppercase mt-1">Asset &
                    Inventory System</span>
            </div>
        </div>

        <!-- Toggle Collapse Button (Desktop) -->
        <button @click="isCollapsed = !isCollapsed"
            class="hidden lg:flex w-8 h-8 rounded-xl bg-white/10 hover:bg-inv-teal items-center justify-center text-white/80 hover:text-white transition-all cursor-pointer">
            <i class="fa-solid" :class="isCollapsed ? 'fa-chevron-right' : 'fa-chevron-left'"></i>
        </button>

        <!-- Close Mobile Button -->
        <button @click="isMobileOpen = false" class="lg:hidden text-white/70 hover:text-white cursor-pointer">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>

    <!-- 2. Navigasi Menu (Spasi diperlebar dengan space-y-6 dan py-5 agar terisi penuh) -->
    <nav
        class="flex-1 px-4 py-6 space-y-6 overflow-y-auto custom-scrollbar overflow-x-hidden flex flex-col justify-around">

        <!-- GROUP 1: MAIN MENU (Default Open: true) -->
        <div x-data="{ open: true }">
            <button @click="open = !open"
                class="w-full flex items-center justify-between px-2 py-1.5 text-inv-teal/80 hover:text-inv-mint cursor-pointer">
                <span class="text-[10px] font-bold uppercase tracking-widest" x-show="!isCollapsed">Main Menu</span>
                <i class="fa-solid fa-chevron-down text-[9px] transition-transform duration-300" x-show="!isCollapsed"
                    :class="open ? 'rotate-0' : '-rotate-90'"></i>
            </button>

            <ul x-show="open || isCollapsed" x-collapse class="space-y-1.5 mt-2">
                <li>
                    <a href="{{ route('dashboard') }}" title="Dashboard"
                        class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-inv-teal/20 text-inv-mint font-semibold border-l-4 border-inv-mint' : 'text-slate-300 hover:text-white hover:bg-white/10' }}">
                        <i
                            class="fa-solid fa-house-chimney text-base w-6 text-center {{ request()->routeIs('dashboard') ? 'text-inv-mint' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span class="ml-3 text-xs font-medium whitespace-nowrap" x-show="!isCollapsed">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('activity-logs.index') }}" title="Log Aktivitas"
                        class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('activity-logs.*') ? 'bg-inv-teal/20 text-inv-mint font-semibold border-l-4 border-inv-mint' : 'text-slate-300 hover:text-white hover:bg-white/10' }}">
                        <i
                            class="fa-solid fa-clock-rotate-left text-base w-6 text-center {{ request()->routeIs('activity-logs.*') ? 'text-inv-mint' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span class="ml-3 text-xs font-medium whitespace-nowrap" x-show="!isCollapsed">Log
                            Aktivitas</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- GROUP 2: INVENTORY CONTROL (Default Open: true) -->
        <div x-data="{ open: true }">
            <button @click="open = !open"
                class="w-full flex items-center justify-between px-2 py-1.5 text-inv-teal/80 hover:text-inv-mint cursor-pointer">
                <span class="text-[10px] font-bold uppercase tracking-widest" x-show="!isCollapsed">Inventory</span>
                <i class="fa-solid fa-chevron-down text-[9px] transition-transform duration-300" x-show="!isCollapsed"
                    :class="open ? 'rotate-0' : '-rotate-90'"></i>
            </button>

            <ul x-show="open || isCollapsed" x-collapse class="space-y-1.5 mt-2">
                <li>
                    <a href="{{ route('stock-in.index') }}" title="Barang Masuk"
                        class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('stock-in.*') ? 'bg-inv-teal/20 text-inv-mint font-semibold border-l-4 border-inv-mint' : 'text-slate-300 hover:text-white hover:bg-white/10' }}">
                        <i
                            class="fa-solid fa-arrow-down-to-bracket text-base w-6 text-center {{ request()->routeIs('stock-in.*') ? 'text-inv-mint' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span class="ml-3 text-xs font-medium whitespace-nowrap" x-show="!isCollapsed">Barang
                            Masuk</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('stock-out.index') }}" title="Barang Keluar"
                        class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('stock-out.*') ? 'bg-inv-teal/20 text-inv-mint font-semibold border-l-4 border-inv-mint' : 'text-slate-300 hover:text-white hover:bg-white/10' }}">
                        <i
                            class="fa-solid fa-arrow-up-from-bracket text-base w-6 text-center {{ request()->routeIs('stock-out.*') ? 'text-inv-mint' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span class="ml-3 text-xs font-medium whitespace-nowrap" x-show="!isCollapsed">Barang
                            Keluar</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('loans.index') }}" title="Peminjaman"
                        class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('loans.*') ? 'bg-inv-teal/20 text-inv-mint font-semibold border-l-4 border-inv-mint' : 'text-slate-300 hover:text-white hover:bg-white/10' }}">
                        <i
                            class="fa-solid fa-hand-holding-box text-base w-6 text-center {{ request()->routeIs('loans.*') ? 'text-inv-mint' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span class="ml-3 text-xs font-medium whitespace-nowrap" x-show="!isCollapsed">Peminjaman
                            Aset</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- GROUP 3: MASTER DATA (Default Open: true) -->
        <div x-data="{ open: true }">
            <button @click="open = !open"
                class="w-full flex items-center justify-between px-2 py-1.5 text-inv-teal/80 hover:text-inv-mint cursor-pointer">
                <span class="text-[10px] font-bold uppercase tracking-widest" x-show="!isCollapsed">Master Data</span>
                <i class="fa-solid fa-chevron-down text-[9px] transition-transform duration-300" x-show="!isCollapsed"
                    :class="open ? 'rotate-0' : '-rotate-90'"></i>
            </button>

            <ul x-show="open || isCollapsed" x-collapse class="space-y-1.5 mt-2">
                <li>
                    <a href="{{ route('products.index') }}" title="Data Produk"
                        class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('products.*') ? 'bg-inv-teal/20 text-inv-mint font-semibold border-l-4 border-inv-mint' : 'text-slate-300 hover:text-white hover:bg-white/10' }}">
                        <i
                            class="fa-solid fa-boxes-stacked text-base w-6 text-center {{ request()->routeIs('products.*') ? 'text-inv-mint' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span class="ml-3 text-xs font-medium whitespace-nowrap" x-show="!isCollapsed">Data
                            Produk</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('categories.index') }}" title="Kategori"
                        class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('categories.*') ? 'bg-inv-teal/20 text-inv-mint font-semibold border-l-4 border-inv-mint' : 'text-slate-300 hover:text-white hover:bg-white/10' }}">
                        <i
                            class="fa-solid fa-tags text-base w-6 text-center {{ request()->routeIs('categories.*') ? 'text-inv-mint' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span class="ml-3 text-xs font-medium whitespace-nowrap" x-show="!isCollapsed">Kategori
                            Barang</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('suppliers.index') }}" title="Supplier"
                        class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('suppliers.*') ? 'bg-inv-teal/20 text-inv-mint font-semibold border-l-4 border-inv-mint' : 'text-slate-300 hover:text-white hover:bg-white/10' }}">
                        <i
                            class="fa-solid fa-truck-ramp-box text-base w-6 text-center {{ request()->routeIs('suppliers.*') ? 'text-inv-mint' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span class="ml-3 text-xs font-medium whitespace-nowrap" x-show="!isCollapsed">Data
                            Supplier</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- GROUP 4: SISTEM (Default Open: true) -->
        <div x-data="{ open: true }">
            <button @click="open = !open"
                class="w-full flex items-center justify-between px-2 py-1.5 text-inv-teal/80 hover:text-inv-mint cursor-pointer">
                <span class="text-[10px] font-bold uppercase tracking-widest" x-show="!isCollapsed">Sistem</span>
                <i class="fa-solid fa-chevron-down text-[9px] transition-transform duration-300" x-show="!isCollapsed"
                    :class="open ? 'rotate-0' : '-rotate-90'"></i>
            </button>

            <ul x-show="open || isCollapsed" x-collapse class="space-y-1.5 mt-2">
                <li>
                    <a href="{{ route('users.index') }}" title="Manajemen Pengguna"
                        class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('users.*') ? 'bg-inv-teal/20 text-inv-mint font-semibold border-l-4 border-inv-mint' : 'text-slate-300 hover:text-white hover:bg-white/10' }}">
                        <i
                            class="fa-solid fa-users-gear text-base w-6 text-center {{ request()->routeIs('users.*') ? 'text-inv-mint' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span class="ml-3 text-xs font-medium whitespace-nowrap" x-show="!isCollapsed">Manajemen Pengguna</span>
                    </a>
                </li>
            </ul>
        </div>

    </nav>

    <!-- 3. Footer Logout -->
    <div class="p-4 border-t border-white/10">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" title="Logout"
                class="w-full flex items-center justify-center lg:justify-start px-3.5 py-3 bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white rounded-xl transition-all duration-300 group cursor-pointer">
                <i class="fa-solid fa-arrow-right-from-bracket text-base"></i>
                <span class="ml-3 font-semibold text-xs whitespace-nowrap" x-show="!isCollapsed">Keluar Sistem</span>
            </button>
        </form>
    </div>
</aside>
