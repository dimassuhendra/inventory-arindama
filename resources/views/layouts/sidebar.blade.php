<aside
    class="fixed left-0 top-0 h-screen w-72 bg-white border-r border-gray-100 flex flex-col z-50 font-fredoka shadow-sm">

    <div class="p-8 mb-4">
        <div class="bg-blue-50 px-6 py-4 rounded-2xl flex items-center justify-center border border-blue-100">
            <img src="{{ asset('img/logo-arindama.png') }}" alt="Arindama Logo" class="w-40 h-auto object-contain">
        </div>
    </div>

    <nav class="flex-1 px-6 space-y-8 overflow-y-auto pb-10 custom-scrollbar">

        <div>
            <p class="px-4 mb-4 text-xs font-bold text-gray-400 uppercase tracking-[0.2em] font-domine">Main Menu</p>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all {{ request()->routeIs('dashboard') ? 'text-blue-600 bg-blue-50 shadow-sm shadow-blue-100' : 'text-gray-500 hover:text-blue-600 hover:bg-blue-50' }}">
                        <i class="fa-solid fa-house w-6 text-lg"></i>
                        <span class="ml-3 font-semibold">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all {{ request()->routeIs('activity.*') ? 'text-blue-600 bg-blue-50 shadow-sm shadow-blue-100' : 'text-gray-500 hover:text-blue-600 hover:bg-blue-50' }}">
                        <i class="fa-solid fa-clock-rotate-left w-6 text-lg"></i>
                        <span class="ml-3 font-semibold">Activity Log</span>
                    </a>
                </li>
            </ul>
        </div>

        <div>
            <p class="px-4 mb-4 text-xs font-bold text-gray-400 uppercase tracking-[0.2em] font-domine">Inventory</p>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('stock-in.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all {{ request()->routeIs('stock-in.*') ? 'text-blue-600 bg-blue-50 shadow-sm shadow-blue-100' : 'text-gray-500 hover:text-blue-600 hover:bg-blue-50' }}">
                        <i class="fa-solid fa-file-import w-6 text-lg"></i>
                        <span class="ml-3 font-semibold">Stock In</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('stock-out.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all {{ request()->routeIs('stock-out.*') ? 'text-blue-600 bg-blue-50 shadow-sm shadow-blue-100' : 'text-gray-500 hover:text-blue-600 hover:bg-blue-50' }}">
                        <i class="fa-solid fa-file-export w-6 text-lg"></i>
                        <span class="ml-3 font-semibold">Stock Out</span>
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all {{ request()->routeIs('carts.*') ? 'text-blue-600 bg-blue-50 shadow-sm shadow-blue-100' : 'text-gray-500 hover:text-blue-600 hover:bg-blue-50' }}">
                        <i class="fa-solid fa-cart-shopping w-6 text-lg"></i>
                        <span class="ml-3 font-semibold">Carts Request</span>
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all {{ request()->routeIs('orders.*') ? 'text-blue-600 bg-blue-50 shadow-sm shadow-blue-100' : 'text-gray-500 hover:text-blue-600 hover:bg-blue-50' }}">
                        <i class="fa-solid fa-truck-fast w-6 text-lg"></i>
                        <span class="ml-3 font-semibold">Orders Log</span>
                    </a>
                </li>
            </ul>
        </div>

        <div>
            <p class="px-4 mb-4 text-xs font-bold text-gray-400 uppercase tracking-[0.2em] font-domine">Master Data</p>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('products.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all {{ request()->routeIs('products.*') ? 'text-blue-600 bg-blue-50 shadow-sm shadow-blue-100' : 'text-gray-500 hover:text-blue-600 hover:bg-blue-50' }}">
                        <i class="fa-solid fa-box w-6 text-lg"></i>
                        <span class="ml-3 font-semibold">Products</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('categories.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all {{ request()->routeIs('categories.*') ? 'text-blue-600 bg-blue-50 shadow-sm shadow-blue-100' : 'text-gray-500 hover:text-blue-600 hover:bg-blue-50' }}">
                        <i class="fa-solid fa-tags w-6 text-lg"></i>
                        <span class="ml-3 font-semibold">Categories</span>
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all {{ request()->routeIs('suppliers.*') ? 'text-blue-600 bg-blue-50 shadow-sm shadow-blue-100' : 'text-gray-500 hover:text-blue-600 hover:bg-blue-50' }}">
                        <i class="fa-solid fa-building w-6 text-lg"></i>
                        <span class="ml-3 font-semibold">Suppliers</span>
                    </a>
                </li>
            </ul>
        </div>

        <div>
            <p class="px-4 mb-4 text-xs font-bold text-gray-400 uppercase tracking-[0.2em] font-domine">Asset & Fleet
            </p>
            <ul class="space-y-2">
                <li>
                    <a href="#"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all {{ request()->routeIs('vehicles.*') ? 'text-blue-600 bg-blue-50 shadow-sm shadow-blue-100' : 'text-gray-500 hover:text-blue-600 hover:bg-blue-50' }}">
                        <i class="fa-solid fa-car w-6 text-lg"></i>
                        <span class="ml-3 font-semibold">Vehicles</span>
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="flex items-center px-4 py-3 rounded-xl group transition-all {{ request()->routeIs('rents.*') ? 'text-blue-600 bg-blue-50 shadow-sm shadow-blue-100' : 'text-gray-500 hover:text-blue-600 hover:bg-blue-50' }}">
                        <i class="fa-solid fa-key w-6 text-lg"></i>
                        <span class="ml-3 font-semibold">Rents Log</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="pt-4 border-t border-gray-50">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full flex items-center px-4 py-3 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-xl group transition-all">
                    <i class="fa-solid fa-right-from-bracket w-6 text-lg"></i>
                    <span class="ml-3 font-semibold">Logout</span>
                </button>
            </form>
        </div>
    </nav>
</aside>

<style>
    /* Desain Scrollbar untuk Sidebar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 5px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #155dfc;
        /* Blue-100 */
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #155dfc;
        /* Blue-500 */
    }

    /* Firefox */
    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: #dbeafe transparent;
    }
</style>