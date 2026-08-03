<header class="mx-4 lg:mx-8 mt-4 mb-4">
    <!-- Navbar Melayang Berlatar Belakang Tint Soft Slate (Bukan Putih #ffffff) -->
    <div
        class="bg-slate-200/70 backdrop-blur-md rounded-2xl shadow-sm border border-slate-300/80 px-4 lg:px-6 py-2.5 flex items-center justify-between">

        <!-- KIRI: Toggle Mobile & Breadcrumb Header -->
        <div class="flex items-center gap-3">
            <!-- Mobile Toggle -->
            <button @click="isMobileOpen = true"
                class="lg:hidden bg-inv-teal text-white p-2 rounded-xl shadow-sm hover:bg-inv-hover transition-colors">
                <i class="fa-solid fa-bars-staggered text-sm"></i>
            </button>

            <!-- Page Title Info -->
            <div class="flex flex-col">
                <div class="flex items-center gap-1.5 text-[11px] text-slate-500 font-medium">
                    <a href="{{ route('dashboard') }}" class="hover:text-inv-teal transition-colors">Mybolo</a>
                    <i class="fa-solid fa-chevron-right text-[8px] text-slate-400"></i>
                    <span class="text-slate-700 font-semibold">Inventory Platform</span>
                </div>
                <h1 class="text-base lg:text-lg font-serif font-bold text-slate-800 leading-tight">
                    {{ $pageTitle ?? 'Dashboard Overview' }}
                </h1>
            </div>
        </div>

        <!-- KANAN: User Profile Capsule Button -->
        <div class="flex items-center gap-3" x-data="{ openProfile: false }">

            <!-- Quick System Active Pill -->
            <div
                class="hidden sm:flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 text-[11px] font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>System Active</span>
            </div>

            <div class="h-6 w-[1px] bg-slate-300 hidden sm:block"></div>

            <!-- Profile Pill Button -->
            <div class="relative">
                <button @click="openProfile = !openProfile"
                    class="flex items-center gap-2.5 p-1 pl-3 rounded-full bg-slate-100/90 border border-slate-300 hover:bg-slate-200/80 shadow-sm transition-all">

                    <div class="hidden md:flex flex-col text-right">
                        <span class="text-xs font-bold text-slate-800 leading-tight">{{ Auth::user()->name }}</span>
                        <span class="text-[9px] font-bold text-inv-teal uppercase tracking-wider">
                            {{ Auth::user()->roles->first()->name ?? 'Administrator' }}
                        </span>
                    </div>

                    <!-- Avatar -->
                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-inv-teal to-inv-mint p-0.5">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=00a8b5&color=ffffff&bold=true"
                            alt="Avatar" class="w-full h-full rounded-full object-cover">
                    </div>

                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 mr-2 transition-transform duration-300"
                        :class="openProfile ? 'rotate-180' : 'rotate-0'"></i>
                </button>

                <!-- Profile Dropdown Card -->
                <div x-show="openProfile" @click.away="openProfile = false" x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    class="absolute right-0 mt-2 w-48 bg-slate-100 backdrop-blur-md rounded-2xl shadow-xl border border-slate-300 p-2 z-50">

                    <div class="px-3 py-2 border-b border-slate-200">
                        <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-slate-400 truncate">{{ Auth::user()->email }}</p>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="mt-1">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center px-3 py-2 text-xs font-medium text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                            <i class="fa-solid fa-power-off mr-2 text-red-500"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</header>
