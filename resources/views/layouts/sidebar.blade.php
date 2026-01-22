<aside x-cloak x-show="window.innerWidth >= 1024 ? true : isSideOpen"
    x-transition:enter="transition transform ease-out duration-300" x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0" x-transition:leave="transition transform ease-in duration-300"
    x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
    class="fixed left-0 lg:left-6 top-0 lg:top-6 bottom-0 lg:bottom-6 w-72 bg-white/95 backdrop-blur-md lg:rounded-[2.5rem] flex flex-col z-50 font-fredoka shadow-2xl border-r lg:border border-white/20"
    @click.away="if(window.innerWidth < 1024) isSideOpen = false">

    <div class="lg:hidden absolute right-4 top-4">
        <button @click="isSideOpen = false" class="text-gray-400 hover:text-blue-600">
            <i class="fa-solid fa-circle-xmark text-2xl"></i>
        </button>
    </div>

    <div class="p-8 mb-2">
        <div class="bg-blue-50/50 px-6 py-4 rounded-2xl flex items-center justify-center border border-blue-100/50">
            <img src="{{ asset('img/logo-arindama.png') }}" alt="Arindama Logo" class="w-40 h-auto object-contain">
        </div>
    </div>

    <nav class="flex-1 px-6 space-y-8 overflow-y-auto pb-10 custom-scrollbar">

        <div>
            <p class="px-4 mb-4 text-[10px] font-bold text-blue-900/40 uppercase tracking-[0.2em] font-domine">Main Menu
            </p>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all {{ request()->routeIs('dashboard') ? 'text-blue-600 bg-blue-50 shadow-sm' : 'text-gray-400 hover:text-blue-600 hover:bg-blue-50' }}">
                        <i class="fa-solid fa-house w-6 text-lg"></i>
                        <span class="ml-3 font-bold text-sm">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('activity-log.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all {{ request()->routeIs('activity-log.*') ? 'text-blue-600 bg-blue-50 shadow-sm' : 'text-gray-400 hover:text-blue-600 hover:bg-blue-50' }}">
                        <i class="fa-solid fa-clock-rotate-left w-6 text-lg"></i>
                        <span class="ml-3 font-bold text-sm">Activity Log</span>
                    </a>
                </li>
            </ul>
        </div>

        <div>
            <p class="px-4 mb-4 text-[10px] font-bold text-blue-900/40 uppercase tracking-[0.2em] font-domine">Inventory
            </p>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('stock-in.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all {{ request()->routeIs('stock-in.*') ? 'text-blue-600 bg-blue-50 shadow-sm' : 'text-gray-400 hover:text-blue-600 hover:bg-blue-50' }}">
                        <i class="fa-solid fa-file-import w-6 text-lg"></i>
                        <span class="ml-3 font-bold text-sm">Stock In</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('stock-out.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all {{ request()->routeIs('stock-out.*') ? 'text-blue-600 bg-blue-50 shadow-sm' : 'text-gray-400 hover:text-blue-600 hover:bg-blue-50' }}">
                        <i class="fa-solid fa-file-export w-6 text-lg"></i>
                        <span class="ml-3 font-bold text-sm">Stock Out</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('loans.index')   }}"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all {{ request()->routeIs('loans.*') ? 'text-blue-600 bg-blue-50 shadow-sm' : 'text-gray-400 hover:text-blue-600 hover:bg-blue-50' }}">
                        <i class="fa-solid fa-file-export w-6 text-lg"></i>
                        <span class="ml-3 font-bold text-sm">Peminjaman Barang</span>
                    </a>
                </li>
                <!-- <li>
                    <a href="#"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all {{ request()->routeIs('cart.*') ? 'text-blue-600 bg-blue-50 shadow-sm' : 'text-gray-400 hover:text-blue-600 hover:bg-blue-50' }}">
                        <i class="fa-solid fa-cart-shopping w-6 text-lg"></i>
                        <span class="ml-3 font-bold text-sm">Cart Request</span>
                    </a>
                </li> -->
            </ul>
        </div>

        <div>
            <p class="px-4 mb-4 text-[10px] font-bold text-blue-900/40 uppercase tracking-[0.2em] font-domine">Master
                Data</p>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('products.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all {{ request()->routeIs('products.*') ? 'text-blue-600 bg-blue-50 shadow-sm' : 'text-gray-400 hover:text-blue-600 hover:bg-blue-50' }}">
                        <i class="fa-solid fa-box w-6 text-lg"></i>
                        <span class="ml-3 font-bold text-sm">Products</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('categories.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all {{ request()->routeIs('categories.*') ? 'text-blue-600 bg-blue-50 shadow-sm' : 'text-gray-400 hover:text-blue-600 hover:bg-blue-50' }}">
                        <i class="fa-solid fa-tags w-6 text-lg"></i>
                        <span class="ml-3 font-bold text-sm">Categories</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('suppliers.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all {{ request()->routeIs('suppliers.*') ? 'text-blue-600 bg-blue-50 shadow-sm' : 'text-gray-400 hover:text-blue-600 hover:bg-blue-50' }}">
                        <i class="fa-solid fa-building w-6 text-lg"></i>
                        <span class="ml-3 font-bold text-sm">Suppliers</span>
                    </a>
                </li>
            </ul>
        </div>

    </nav>

    <div class="p-6 border-t border-gray-50">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center px-4 py-4 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-2xl group transition-all">
                <i class="fa-solid fa-right-from-bracket w-6 text-lg"></i>
                <span class="ml-3 font-bold text-sm">Logout</span>
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
        background: rgba(37, 99, 235, 0.1);
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(37, 99, 235, 0.3);
    }

    /* Firefox */
    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: rgba(37, 99, 235, 0.1) transparent;
    }
</style>