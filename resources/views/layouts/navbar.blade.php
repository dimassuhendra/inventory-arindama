<header class="mx-8 mt-6 mb-6">
    <div
        class="bg-blue-600 backdrop-blur-md rounded-2xl shadow-lg px-8 py-4 border border-blue-500 flex items-center justify-between">

        <div class="flex items-center gap-4">
            <div class="bg-white/20 text-white p-2.5 rounded-xl shadow-inner border border-white/30">
                <i class="fa-solid fa-house-chimney text-sm"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-white leading-none font-fredoka">Dashboard</h1>
                <p class="text-[10px] text-blue-100 font-domine uppercase tracking-widest mt-1">Management Overview</p>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <div class="hidden sm:flex items-center gap-3 pr-6 border-r border-blue-500/50 text-right">
                <div>
                    <p class="text-sm font-bold text-white leading-none font-fredoka">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-blue-200 font-bold uppercase mt-1">
                        {{ Auth::user()->getRoleNames()->first() ?? 'Admin' }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl border-2 border-blue-400 shadow-sm overflow-hidden bg-white">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=fff&color=2563eb"
                        class="w-full h-full">
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-blue-100 hover:text-red-300 transition-all transform hover:scale-110">
                    <i class="fa-solid fa-power-off text-xl"></i>
                </button>
            </form>
        </div>
    </div>
</header>