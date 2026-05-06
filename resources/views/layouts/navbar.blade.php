<header class="mx-4 lg:mx-8 mt-4 lg:mt-6 mb-6 z-20">
    <div
        class="bg-white rounded-2xl shadow-[0_4px_20px_rgb(52,103,57,0.05)] border border-accent/30 px-6 py-4 flex items-center justify-between transition-all">

        <!-- Judul Halaman -->
        <div class="flex items-center gap-4">
            <button @click="isSideOpen = true"
                class="lg:hidden bg-secondary text-white p-2.5 rounded-xl shadow-md shadow-secondary/30 hover:bg-primary transition-colors">
                <i class="fa-solid fa-bars-staggered text-sm"></i>
            </button>

            <a href="{{ route('dashboard') }}" class="hidden sm:block">
                <div
                    class="bg-accent/20 text-primary p-3 rounded-xl hover:bg-secondary hover:text-white transition-colors duration-300">
                    <i class="fa-solid fa-house-chimney text-sm"></i>
                </div>
            </a>
            <div>
                <h1 class="text-xl font-bold text-primary leading-none">Dashboard</h1>
                <p class="text-[11px] text-secondary uppercase tracking-widest mt-1.5 font-semibold">Management Overview
                </p>
            </div>
        </div>

        <!-- Profil Pengguna -->
        <div class="flex items-center gap-5 lg:gap-6">
            <div class="hidden sm:flex items-center gap-3 pr-5 lg:pr-6 border-r border-accent/40 text-right">
                <div>
                    <p class="text-sm font-bold text-primary leading-none">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-secondary font-bold uppercase mt-1">
                        {{ Auth::user()->role ?? 'Administrator' }}
                    </p>
                </div>
                <!-- Avatar -->
                <div
                    class="w-10 h-10 rounded-full border-2 border-accent shadow-sm overflow-hidden bg-background flex-shrink-0">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=346739&color=F2EDC2&bold=true"
                        class="w-full h-full object-cover">
                </div>
            </div>

            <!-- Tombol Power Off -->
            <form action="{{ route('logout') }}" method="POST" class="flex items-center">
                @csrf
                <button type="submit"
                    class="text-secondary hover:text-white bg-accent/20 hover:bg-red-500 p-2.5 rounded-full transition-all duration-300 transform hover:rotate-90">
                    <i class="fa-solid fa-power-off text-[1.1rem]"></i>
                </button>
            </form>
        </div>
    </div>
</header>
