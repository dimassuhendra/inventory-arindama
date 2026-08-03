<aside x-cloak
    :class="[
        isCollapsed ? 'lg:w-20' : 'lg:w-64',
        isMobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
    ]"
    class="fixed lg:static left-0 top-0 bottom-0 z-50 h-screen bg-gradient-to-b from-inv-dark via-[#0a233f] to-inv-dark text-white flex flex-col transition-all duration-300 border-r border-white/10 shadow-xl">

    <!-- 1. Header Sidebar: Logo Inventory.png & Toggle Button -->
    <div class="p-4 flex items-center justify-between border-b border-white/10 relative">

        <!-- Logo & Brand (Sembunyi saat Collapsed) -->
        <div class="flex items-center gap-3 overflow-hidden" x-show="!isCollapsed">
            <img src="{{ asset('img/Inventory.png') }}" alt="Mybolo Inventory"
                class="w-9 h-9 object-contain filter drop-shadow">
            <div class="flex flex-col whitespace-nowrap">
                <span class="text-sm font-serif font-bold text-white leading-none">Mybolo</span>
                <span class="text-[10px] font-semibold text-inv-mint tracking-wider uppercase mt-0.5">Inventory</span>
            </div>
        </div>

        <!-- Logo Icon Only (Tampil saat Collapsed) -->
        <div class="mx-auto" x-show="isCollapsed">
            <img src="{{ asset('img/Inventory.png') }}" alt="Mybolo" class="w-8 h-8 object-contain">
        </div>

        <!-- Toggle Collapse Button (Desktop) -->
        <button @click="isCollapsed = !isCollapsed"
            class="hidden lg:flex w-7 h-7 rounded-lg bg-white/10 hover:bg-inv-teal items-center justify-center text-white/80 hover:text-white transition-colors">
            <i class="fa-solid" :class="isCollapsed ? 'fa-chevron-right' : 'fa-chevron-left'"></i>
        </button>

        <!-- Close Mobile Button -->
        <button @click="isMobileOpen = false" class="lg:hidden text-white/70 hover:text-white">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>

    <!-- 2. Navigasi Menu -->
    <nav class="flex-1 px-3 py-4 space-y-4 overflow-y-auto custom-scrollbar overflow-x-hidden">

        <!-- MAIN MENU -->
        <div x-data="{ open: {{ request()->routeIs('dashboard', 'activity-log.*') ? 'true' : 'false' }} }">
            <!-- Label Section -->
            <button @click="open = !open"
                class="w-full flex items-center justify-between px-2 py-1 text-inv-teal/80 hover:text-inv-mint">
                <span class="text-[10px] font-bold uppercase tracking-widest" x-show="!isCollapsed">Main Menu</span>
                <i class="fa-solid fa-chevron-down text-[9px] transition-transform duration-300" x-show="!isCollapsed"
                    :class="open ? 'rotate-0' : '-rotate-90'"></i>
            </button>

            <ul x-show="open || isCollapsed" x-collapse class="space-y-1 mt-1">
                <li>
                    <a href="{{ route('dashboard') }}" title="Dashboard"
                        class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-inv-teal/20 text-inv-mint font-semibold border-l-4 border-inv-mint' : 'text-slate-300 hover:text-white hover:bg-white/10' }}">
                        <i
                            class="fa-solid fa-house-chimney text-sm w-5 text-center {{ request()->routeIs('dashboard') ? 'text-inv-mint' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span class="ml-3 text-xs whitespace-nowrap" x-show="!isCollapsed">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('activity-log.index') }}" title="Log Aktivitas"
                        class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('activity-log.*') ? 'bg-inv-teal/20 text-inv-mint font-semibold border-l-4 border-inv-mint' : 'text-slate-300 hover:text-white hover:bg-white/10' }}">
                        <i
                            class="fa-solid fa-clock-rotate-left text-sm w-5 text-center {{ request()->routeIs('activity-log.*') ? 'text-inv-mint' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span class="ml-3 text-xs whitespace-nowrap" x-show="!isCollapsed">Log Aktivitas</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- INVENTORY CONTROL -->
        <div x-data="{ open: {{ request()->routeIs('stock-*', 'loans.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full flex items-center justify-between px-2 py-1 text-inv-teal/80 hover:text-inv-mint">
                <span class="text-[10px] font-bold uppercase tracking-widest" x-show="!isCollapsed">Inventory</span>
                <i class="fa-solid fa-chevron-down text-[9px] transition-transform duration-300" x-show="!isCollapsed"
                    :class="open ? 'rotate-0' : '-rotate-90'"></i>
            </button>

            <ul x-show="open || isCollapsed" x-collapse class="space-y-1 mt-1">
                <li>
                    <a href="{{ route('stock-in.index') }}" title="Barang Masuk"
                        class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('stock-in.*') ? 'bg-inv-teal/20 text-inv-mint font-semibold border-l-4 border-inv-mint' : 'text-slate-300 hover:text-white hover:bg-white/10' }}">
                        <i
                            class="fa-solid fa-arrow-down-to-bracket text-sm w-5 text-center {{ request()->routeIs('stock-in.*') ? 'text-inv-mint' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span class="ml-3 text-xs whitespace-nowrap" x-show="!isCollapsed">Barang Masuk</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('stock-out.index') }}" title="Barang Keluar"
                        class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('stock-out.*') ? 'bg-inv-teal/20 text-inv-mint font-semibold border-l-4 border-inv-mint' : 'text-slate-300 hover:text-white hover:bg-white/10' }}">
                        <i
                            class="fa-solid fa-arrow-up-from-bracket text-sm w-5 text-center {{ request()->routeIs('stock-out.*') ? 'text-inv-mint' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span class="ml-3 text-xs whitespace-nowrap" x-show="!isCollapsed">Barang Keluar</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('loans.index') }}" title="Peminjaman"
                        class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('loans.*') ? 'bg-inv-teal/20 text-inv-mint font-semibold border-l-4 border-inv-mint' : 'text-slate-300 hover:text-white hover:bg-white/10' }}">
                        <i
                            class="fa-solid fa-hand-holding-box text-sm w-5 text-center {{ request()->routeIs('loans.*') ? 'text-inv-mint' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span class="ml-3 text-xs whitespace-nowrap" x-show="!isCollapsed">Peminjaman Aset</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- MASTER DATA -->
        <div x-data="{ open: {{ request()->routeIs('products.*', 'categories.*', 'suppliers.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full flex items-center justify-between px-2 py-1 text-inv-teal/80 hover:text-inv-mint">
                <span class="text-[10px] font-bold uppercase tracking-widest" x-show="!isCollapsed">Master Data</span>
                <i class="fa-solid fa-chevron-down text-[9px] transition-transform duration-300" x-show="!isCollapsed"
                    :class="open ? 'rotate-0' : '-rotate-90'"></i>
            </button>

            <ul x-show="open || isCollapsed" x-collapse class="space-y-1 mt-1">
                <li>
                    <a href="{{ route('products.index') }}" title="Data Produk"
                        class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('products.*') ? 'bg-inv-teal/20 text-inv-mint font-semibold border-l-4 border-inv-mint' : 'text-slate-300 hover:text-white hover:bg-white/10' }}">
                        <i
                            class="fa-solid fa-boxes-stacked text-sm w-5 text-center {{ request()->routeIs('products.*') ? 'text-inv-mint' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span class="ml-3 text-xs whitespace-nowrap" x-show="!isCollapsed">Data Produk</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('categories.index') }}" title="Kategori"
                        class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('categories.*') ? 'bg-inv-teal/20 text-inv-mint font-semibold border-l-4 border-inv-mint' : 'text-slate-300 hover:text-white hover:bg-white/10' }}">
                        <i
                            class="fa-solid fa-tags text-sm w-5 text-center {{ request()->routeIs('categories.*') ? 'text-inv-mint' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span class="ml-3 text-xs whitespace-nowrap" x-show="!isCollapsed">Kategori Barang</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('suppliers.index') }}" title="Supplier"
                        class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('suppliers.*') ? 'bg-inv-teal/20 text-inv-mint font-semibold border-l-4 border-inv-mint' : 'text-slate-300 hover:text-white hover:bg-white/10' }}">
                        <i
                            class="fa-solid fa-truck-ramp-box text-sm w-5 text-center {{ request()->routeIs('suppliers.*') ? 'text-inv-mint' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span class="ml-3 text-xs whitespace-nowrap" x-show="!isCollapsed">Data Supplier</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- SISTEM -->
        <div x-data="{ open: {{ request()->routeIs('users.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full flex items-center justify-between px-2 py-1 text-inv-teal/80 hover:text-inv-mint">
                <span class="text-[10px] font-bold uppercase tracking-widest" x-show="!isCollapsed">Sistem</span>
                <i class="fa-solid fa-chevron-down text-[9px] transition-transform duration-300" x-show="!isCollapsed"
                    :class="open ? 'rotate-0' : '-rotate-90'"></i>
            </button>

            <ul x-show="open || isCollapsed" x-collapse class="space-y-1 mt-1">
                <li>
                    <a href="{{ route('users.index') }}" title="Manajemen Pengguna"
                        class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('users.*') ? 'bg-inv-teal/20 text-inv-mint font-semibold border-l-4 border-inv-mint' : 'text-slate-300 hover:text-white hover:bg-white/10' }}">
                        <i
                            class="fa-solid fa-users-gear text-sm w-5 text-center {{ request()->routeIs('users.*') ? 'text-inv-mint' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span class="ml-3 text-xs whitespace-nowrap" x-show="!isCollapsed">Pengguna</span>
                    </a>
                </li>
            </ul>
        </div>

    </nav>

    <!-- 3. Footer Logout -->
    <div class="p-3 border-t border-white/10">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" title="Logout"
                class="w-full flex items-center justify-center lg:justify-start px-3 py-2.5 bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white rounded-xl transition-all duration-300 group">
                <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
                <span class="ml-3 font-semibold text-xs whitespace-nowrap" x-show="!isCollapsed">Keluar Sistem</span>
            </button>
        </form>
    </div>
</aside>
