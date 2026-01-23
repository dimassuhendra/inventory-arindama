<header class="mx-8 mt-6 mb-6">
    <div
        class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl px-8 py-4 border border-white/20 flex items-center justify-between">

        <div class="flex items-center gap-4">
			<!-- <div class="p-1">
				<div class="bg-blue-50/50 px-6 py-4 rounded-2xl flex items-center justify-center border border-blue-100/50">
					<img src="{{ asset('img/logo-arindama.png') }}" alt="Arindama Logo" class="w-40 h-auto object-contain">
				</div>
			</div> -->
		
            <button @click="isSideOpen = true" class="lg:hidden bg-blue-600 text-white p-2.5 rounded-xl shadow-lg">
                <i class="fa-solid fa-bars-staggered text-sm"></i>
            </button>

            <div class="bg-blue-600 text-white p-2.5 rounded-xl shadow-lg shadow-blue-200">
                <i class="fa-solid fa-house-chimney text-sm"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-blue-900 leading-none font-dynapuff">Dashboard</h1>
                <p class="text-[10px] text-blue-400 font-acme uppercase tracking-widest mt-1 font-bold">Management
                    Overview</p>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <div class="hidden sm:flex items-center gap-3 pr-6 border-r border-gray-100 text-right">
                <div>
                    <p class="text-sm font-bold text-blue-900 leading-none font-dynapuff">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-blue-500 font-bold uppercase mt-1 italic">
                        {{ Auth::user()->role ?? 'Administrator' }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl border-2 border-blue-100 shadow-sm overflow-hidden bg-white">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=2563eb&color=fff"
                        class="w-full h-full">
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-red-500 transition-all transform hover:scale-110">
                    <i class="fa-solid fa-power-off text-xl"></i>
                </button>
            </form>
        </div>
    </div>
</header>