<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Mybolo Inventory</title>

    <!-- Font Sesuai Permintaan (Outfit & Domine) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Domine:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Tailwind, Alpine.js, SweetAlert, FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Konfigurasi Tailwind -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#346739',
                        /* Hijau Tua */
                        secondary: '#79AE6F',
                        /* Hijau Sedang */
                        accent: '#9FCB98',
                        /* Hijau Muda */
                        background: '#F2EDC2' /* Krem */
                    },
                    fontFamily: {
                        sans: ['"Outfit"', 'sans-serif'],
                        serif: ['"Domine"', 'serif'],
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 flex min-h-screen items-center justify-center p-4">

    <div class="flex flex-col lg:flex-row w-full max-w-5xl overflow-hidden rounded-3xl bg-white shadow-2xl">

        <!-- SISI KIRI (Informasi & Workspace Slider) -->
        <div class="flex w-full lg:w-1/2 flex-col items-center bg-white p-8 lg:p-12 relative">

            <div class="flex-1 flex flex-col items-center justify-center w-full mt-4 lg:mt-0">
                <div class="w-40 h-40 lg:w-56 lg:h-56 flex items-center justify-center mb-4 lg:mb-6">
                    <!-- CSS Mask untuk mewarnai Logo PNG -->
                    <div class="w-full h-full bg-primary"
                        style="-webkit-mask-image: url('{{ asset('img/logo-new.png') }}'); -webkit-mask-size: contain; -webkit-mask-repeat: no-repeat; -webkit-mask-position: center; mask-image: url('{{ asset('img/logo-new.png') }}'); mask-size: contain; mask-repeat: no-repeat; mask-position: center;">
                    </div>
                </div>
                <h2 class="text-2xl lg:text-3xl text-primary font-serif font-bold text-center tracking-wide">Mybolo
                    Inventory</h2>

                <!-- Subtitle Copywriting -->
                <p
                    class="text-secondary opacity-90 font-sans text-sm lg:text-base mt-3 mb-8 text-center px-2 leading-relaxed">
                    Platform terpadu untuk mengelola aset dan memantau stok barang dengan cepat, akurat, dan efisien.
                </p>
            </div>

            <!-- Slider Workspace Mybolo -->
            <div class="w-full mt-10 lg:mt-auto pt-5 border-t border-gray-100" x-data="{ slide: 0, maxSlide: 1 }">

                <div class="flex items-center justify-between mb-4 px-1">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-[0.15em] mb-0 font-sans">Mybolo
                        Workspace</p>

                    <div class="flex gap-1">
                        <button type="button" @click="slide = Math.max(0, slide - 1)"
                            :class="slide === 0 ? 'text-gray-300 cursor-not-allowed' : 'text-primary hover:bg-gray-50'"
                            class="w-7 h-7 rounded-full flex items-center justify-center transition-colors">
                            <i class="fas fa-chevron-left text-[10px]"></i>
                        </button>
                        <button type="button" @click="slide = Math.min(maxSlide, slide + 1)"
                            :class="slide === maxSlide ? 'text-gray-300 cursor-not-allowed' : 'text-primary hover:bg-gray-50'"
                            class="w-7 h-7 rounded-full flex items-center justify-center transition-colors">
                            <i class="fas fa-chevron-right text-[10px]"></i>
                        </button>
                    </div>
                </div>

                <div class="overflow-hidden w-full relative font-sans">
                    <div class="flex transition-transform duration-500 ease-out"
                        :style="`transform: translateX(-${slide * 100}%)`">

                        <!-- Slide 1 -->
                        <div class="w-full flex-shrink-0 grid grid-cols-2 gap-3">
                            <a href="https://cloud.mybolo.id"
                                class="flex flex-col items-center justify-center p-3 rounded-xl border border-gray-100 hover:border-primary hover:bg-primary/5 transition-all group">
                                <i
                                    class="fas fa-cloud text-primary opacity-70 group-hover:opacity-100 text-xl mb-2 group-hover:scale-110 transition-transform"></i>
                                <span class="text-xs text-gray-600 font-medium text-center">Cloud Mybolo</span>
                            </a>
                            <a href="https://kpi.mybolo.id/"
                                class="flex flex-col items-center justify-center p-3 rounded-xl border border-gray-100 hover:border-primary hover:bg-primary/5 transition-all group">
                                <i
                                    class="fas fa-chart-pie text-primary opacity-70 group-hover:opacity-100 text-xl mb-2 group-hover:scale-110 transition-transform"></i>
                                <span class="text-xs text-gray-600 font-medium text-center">KPI Dashboard</span>
                            </a>
                        </div>

                        <!-- Slide 2 -->
                        <div class="w-full flex-shrink-0 grid grid-cols-2 gap-3">
                            <a href="https://ticket.mybolo.id/login"
                                class="flex flex-col items-center justify-center p-3 rounded-xl border border-gray-100 hover:border-primary hover:bg-primary/5 transition-all group">
                                <i
                                    class="fas fa-headset text-primary opacity-70 group-hover:opacity-100 text-xl mb-2 group-hover:scale-110 transition-transform"></i>
                                <span class="text-xs text-gray-600 font-medium text-center">Mybolo Ticketing</span>
                            </a>

                            <div
                                class="flex flex-col items-center justify-center p-3 rounded-xl border border-gray-100 bg-gray-50/70 cursor-not-allowed opacity-80">
                                <i class="fas fa-cubes text-gray-400 text-xl mb-1"></i>
                                <span class="text-xs text-gray-500 font-medium text-center">Segera Hadir</span>
                                <span
                                    class="text-[9px] bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full mt-1.5 font-header tracking-wide">Coming
                                    Soon</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SISI KANAN (Form Login) -->
        <div class="w-full lg:w-1/2 bg-primary p-8 lg:p-12 text-background relative flex flex-col justify-center">
            <h1 class="mb-2 text-4xl lg:text-5xl font-serif font-bold text-background tracking-wide">Welcome!</h1>
            <p class="mb-8 font-light text-background/80 font-sans text-lg">Silakan login untuk masuk ke sistem</p>

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <!-- Input Username / Email -->
                <div class="mb-5 relative group">
                    <div
                        class="flex items-center rounded-xl bg-black/10 border border-background/30 px-4 py-3 focus-within:bg-background focus-within:text-primary transition-all duration-300">
                        <i class="fas fa-user-circle mr-3 text-background/70 group-focus-within:text-primary"></i>
                        <input type="email" name="email" placeholder="Username / Email"
                            class="w-full bg-transparent outline-none placeholder:text-background/50 text-background focus:text-primary font-sans"
                            required>
                    </div>
                </div>

                <!-- Input Password -->
                <div class="mb-8 relative group">
                    <div
                        class="flex items-center rounded-xl bg-black/10 border border-background/30 px-4 py-3 focus-within:bg-background focus-within:text-primary transition-all duration-300">
                        <i class="fas fa-key mr-3 text-background/70 group-focus-within:text-primary"></i>
                        <input type="password" name="password" placeholder="Password"
                            class="w-full bg-transparent outline-none placeholder:text-background/50 text-background focus:text-primary font-sans"
                            required>
                    </div>
                </div>

                <div class="flex flex-col mt-4">
                    <!-- Tombol Login -->
                    <button type="submit"
                        class="w-full rounded-xl bg-secondary text-background px-6 py-4 font-serif font-bold tracking-widest text-xl shadow-lg hover:bg-accent hover:text-primary transition-all duration-300 active:scale-95">
                        LOGIN
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Alert SweetAlert2 untuk Error Login -->
    @if (session()->has('loginError'))
        <script>
            Swal.fire({
                icon: 'error',
                title: '<span class="font-serif font-bold text-2xl text-red-600">Login Gagal!</span>',
                html: '<p class="font-sans text-gray-600">{{ session('loginError') }}</p>',
                background: '#ffffff',
                showConfirmButton: true,
                confirmButtonText: 'Coba Lagi',
                confirmButtonColor: '#346739',
                /* Warna Primary */
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                },
                customClass: {
                    popup: 'rounded-3xl shadow-2xl',
                    confirmButton: 'px-8 py-2 rounded-xl font-serif font-bold tracking-widest uppercase'
                },
                backdrop: `
                    rgba(52, 103, 57, 0.4) 
                    left top
                    no-repeat
                `
            });
        </script>
    @endif

</body>

</html>
