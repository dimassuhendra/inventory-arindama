<header class="mx-4 lg:mx-8 mt-4 mb-4 relative z-50">
    <!-- Navbar Melayang Berlatar Belakang Tint Soft Slate -->
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
            <div class="flex flex-col justify-center" x-data="clockWidget()" x-init="startClock()">
                <p x-text="dateString" class="text-xs lg:text-sm font-medium text-slate-500 leading-none"></p>

                <div class="flex items-baseline gap-2 mt-1">
                    <h1 x-text="timeString"
                        class="text-xl lg:text-2xl font-serif font-bold text-slate-800 tracking-tight font-mono leading-none">
                    </h1>
                </div>
            </div>
        </div>

        <!-- KANAN: User Profile Capsule Button -->
        <div class="flex items-center gap-3" x-data="{ openProfile: false }">
            <div class="h-6 w-[1px] bg-slate-300 hidden sm:block"></div>

            <!-- Profile Pill Button -->
            <div class="relative">
                <button @click="openProfile = !openProfile"
                    class="flex items-center gap-2.5 p-1 pl-3 rounded-full bg-slate-100/90 border border-slate-300 hover:bg-slate-200/80 shadow-sm transition-all cursor-pointer">

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

                <!-- Profile Dropdown Card (z-[100] & Shadow-2xl agar selalu tampil di atas konten/chart) -->
                <div x-show="openProfile" @click.away="openProfile = false" x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    class="absolute right-0 mt-2 w-52 bg-slate-100/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-slate-300/80 p-2 z-[100]">

                    <div class="px-3 py-2 border-b border-slate-200">
                        <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-slate-500 truncate">{{ Auth::user()->email }}</p>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="mt-1">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center px-3 py-2 text-xs font-medium text-red-600 hover:bg-red-500/10 rounded-xl transition-colors cursor-pointer">
                            <i class="fa-solid fa-power-off mr-2 text-red-500"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</header>

<script>
    function clockWidget() {
        return {
            dateString: '',
            timeString: '',
            startClock() {
                const update = () => {
                    const now = new Date();

                    // Format Hari & Tanggal (contoh: Selasa, 4 Agustus 2026)
                    const optionsDate = {
                        weekday: 'long',
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric',
                        timeZone: 'Asia/Jakarta'
                    };
                    this.dateString = new Intl.DateTimeFormat('id-ID', optionsDate).format(now);

                    // Format Jam:Menit:Detik (contoh: 14:05:22)
                    const optionsTime = {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: false,
                        timeZone: 'Asia/Jakarta'
                    };
                    this.timeString = new Intl.DateTimeFormat('id-ID', optionsTime).format(now).replace(/\./g, ':');
                };

                update();
                setInterval(update, 1000); // Update setiap detik
            }
        }
    }
</script>
