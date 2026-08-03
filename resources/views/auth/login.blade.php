<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | Mybolo Inventory</title>

    <!-- Font Outfit & Domine -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Domine:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Tailwind, Alpine.js, SweetAlert2, FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Tailwind Config Sesuai Gradasi Logo -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        inv: {
                            primary: '#0c66c8',
                            hover: '#08488f',
                            teal: '#00a8b5',
                            mint: '#2dd4bf',
                            box: '#2563eb',
                            page: '#f8fafc',
                            text: '#0f172a',
                            muted: '#64748b',
                        }
                    },
                    fontFamily: {
                        sans: ['"Outfit"', 'sans-serif'],
                        serif: ['"Domine"', 'serif'],
                    }
                }
            }
        }
    </script>

    <style>
        /* Animation untuk efek floating lembut */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        .animate-float {
            animation: float 5s ease-in-out infinite;
        }
    </style>
</head>

<body class="h-full bg-inv-page font-sans text-inv-text overflow-hidden antialiased select-none">

    <!-- Main Container Full Viewport -->
    <div class="flex flex-col lg:flex-row h-full w-full min-h-screen">

        <!-- SISI KIRI: Branding, Hero Identity & Workspace Direct Grid (58% Desktop) -->
        <div class="w-full lg:w-[58%] bg-white p-6 lg:p-12 flex flex-col justify-between relative overflow-hidden">

            <!-- Background Particles JS -->
            <canvas id="particleCanvas" class="absolute inset-0 pointer-events-none opacity-40 z-0"></canvas>

            <!-- Dekorasi Subtle SVG Hero Pattern -->
            <div class="absolute -top-12 -right-12 text-inv-teal/10 animate-float pointer-events-none z-0">
                <i class="fa-solid fa-boxes-stacked text-9xl"></i>
            </div>

            <!-- 1. Header Area: Logo Utama Perusahaan -->
            <div class="flex items-center gap-3 relative z-10">
                <img src="{{ asset('img/logo-new.png') }}" alt="Logo Perusahaan" class="h-9 w-auto object-contain">
                <div class="h-5 w-[1.5px] bg-slate-200"></div>
                <span class="text-[11px] font-bold text-inv-muted tracking-widest uppercase">Mybolo Asset & Inventory System</span>
            </div>

            <!-- 2. Hero Content: Identity Web Inventory -->
            <div class="my-auto py-2 max-w-lg mx-auto w-full flex flex-col items-center text-center relative z-10">
                <div class="relative mb-3 group">
                    <div
                        class="absolute -inset-2 bg-gradient-to-tr from-inv-mint via-inv-teal to-inv-box rounded-full blur-md opacity-30 group-hover:opacity-60 transition duration-500">
                    </div>
                    <div class="relative p-2">
                        <img src="{{ asset('img/Inventory.png') }}" alt="Mybolo Inventory"
                            class="w-28 h-28 lg:w-36 lg:h-36 object-contain filter drop-shadow-md">
                    </div>
                </div>

                <h1 class="text-3xl lg:text-4xl font-serif font-bold text-slate-800 tracking-tight mb-2">
                    Mybolo <span
                        class="bg-gradient-to-r from-inv-teal via-inv-primary to-inv-box bg-clip-text text-transparent">Inventory</span>
                </h1>

                <p class="text-inv-muted text-xs lg:text-sm leading-relaxed max-w-sm">
                    Platform manajemen aset dan kontrol stok barang terpadu secara akurat, cepat, dan transparan.
                </p>
            </div>

            <!-- 3. Footer Area: Mybolo Workspace Direct Grid (1x3) -->
            <div class="w-full pt-4 border-t border-slate-100 relative z-10">
                <div class="flex items-center gap-2 mb-3 px-1">
                    <span class="w-2 h-2 rounded-full bg-gradient-to-r from-inv-mint to-inv-teal animate-pulse"></span>
                    <p class="text-[10px] font-bold text-inv-muted uppercase tracking-[0.15em]">Mybolo Workspace</p>
                </div>

                <!-- Grid 3 Kolom Langsung (Direct Show) -->
                <div class="grid grid-cols-3 gap-3">

                    <!-- Card 1: Cloud -->
                    <a href="https://cloud.mybolo.id" target="_blank"
                        class="flex flex-col items-center justify-center p-3 rounded-2xl border border-slate-100 bg-slate-50/60 hover:bg-white hover:border-inv-teal/40 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 group">
                        <img src="{{ asset('img/Cloud.png') }}" alt="Cloud Mybolo"
                            class="w-12 h-12 lg:w-14 lg:h-14 object-contain mb-2 group-hover:scale-110 transition-transform">
                        <span class="text-xs font-semibold text-slate-700 group-hover:text-inv-teal">Cloud Mybolo</span>
                    </a>

                    <!-- Card 2: KPI -->
                    <a href="https://kpi.mybolo.id/" target="_blank"
                        class="flex flex-col items-center justify-center p-3 rounded-2xl border border-slate-100 bg-slate-50/60 hover:bg-white hover:border-inv-teal/40 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 group">
                        <img src="{{ asset('img/KPI.png') }}" alt="KPI Dashboard"
                            class="w-12 h-12 lg:w-14 lg:h-14 object-contain mb-2 group-hover:scale-110 transition-transform">
                        <span class="text-xs font-semibold text-slate-700 group-hover:text-inv-teal">KPI
                            Dashboard</span>
                    </a>

                    <!-- Card 3: Ticketing -->
                    <a href="https://ticket.mybolo.id/login" target="_blank"
                        class="flex flex-col items-center justify-center p-3 rounded-2xl border border-slate-100 bg-slate-50/60 hover:bg-white hover:border-inv-teal/40 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 group">
                        <img src="{{ asset('img/Ticketing.png') }}" alt="Mybolo Ticketing"
                            class="w-12 h-12 lg:w-14 lg:h-14 object-contain mb-2 group-hover:scale-110 transition-transform">
                        <span class="text-xs font-semibold text-slate-700 group-hover:text-inv-teal">Mybolo Ticketing</span>
                    </a>

                </div>
            </div>

        </div>

        <!-- SISI KANAN: Form Login dengan Gradasi Sesuai Warna Logo (42% Desktop) -->
        <div
            class="w-full lg:w-[42%] bg-gradient-to-br from-[#00a8b5] via-[#0c66c8] to-[#2563eb] p-8 lg:p-14 text-white flex flex-col justify-center relative shadow-2xl overflow-hidden">

            <!-- Pattern Overlay Subtle -->
            <div
                class="absolute inset-0 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:20px_20px] opacity-10 pointer-events-none">
            </div>
            <div
                class="absolute -bottom-20 -right-20 w-80 h-80 bg-inv-mint/20 rounded-full blur-3xl pointer-events-none">
            </div>

            <div class="max-w-sm mx-auto w-full relative z-10">
                <div class="mb-8">
                    <h2 class="text-3xl lg:text-4xl font-serif font-bold text-white tracking-wide mb-2">Selamat Datang!</h2>
                    <p class="text-emerald-100/80 text-sm font-light">Gunakan akun terdaftar untuk masuk ke sistem</p>
                </div>

                <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Input Email / Username -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-white/90 tracking-wider uppercase">Email /
                            Username</label>
                        <div
                            class="relative flex items-center rounded-xl bg-black/15 border border-white/20 focus-within:bg-white focus-within:border-white transition-all duration-300 group">
                            <i
                                class="fas fa-user-circle pl-4 text-white/70 group-focus-within:text-inv-primary transition-colors"></i>
                            <input type="email" name="email" placeholder="Username / Email"
                                class="w-full bg-transparent px-3 py-3.5 outline-none placeholder:text-white/50 text-white focus:text-slate-800 font-sans text-sm"
                                required>
                        </div>
                    </div>

                    <!-- Input Password dengan Toggle Show/Hide -->
                    <div class="space-y-1.5" x-data="{ show: false }">
                        <label class="text-[11px] font-bold text-white/90 tracking-wider uppercase">Password</label>
                        <div
                            class="relative flex items-center rounded-xl bg-black/15 border border-white/20 focus-within:bg-white focus-within:border-white transition-all duration-300 group">
                            <i
                                class="fas fa-key pl-4 text-white/70 group-focus-within:text-inv-primary transition-colors"></i>
                            <input :type="show ? 'text' : 'password'" name="password" placeholder="Password"
                                class="w-full bg-transparent px-3 py-3.5 outline-none placeholder:text-white/50 text-white focus:text-slate-800 font-sans text-sm"
                                required>
                            <button type="button" @click="show = !show"
                                class="pr-4 text-white/70 group-focus-within:text-slate-500 hover:text-white transition-colors">
                                <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tombol Login -->
                    <div class="pt-3">
                        <button type="submit"
                            class="w-full rounded-xl bg-gradient-to-r from-inv-mint to-teal-300 hover:from-white hover:to-white text-slate-900 px-6 py-4 font-serif font-bold tracking-widest text-base shadow-xl shadow-black/10 transition-all duration-300 active:scale-[0.98]">
                            MASUK
                        </button>
                    </div>
                </form>

                <p class="mt-12 text-center text-xs text-white/60 font-light">
                    &copy; {{ date('Y') }} Mybolo Platform. All rights reserved.
                </p>
            </div>
        </div>

    </div>

    <!-- Script JS Particles -->
    <script>
        const canvas = document.getElementById('particleCanvas');
        const ctx = canvas.getContext('2d');

        function resizeCanvas() {
            canvas.width = canvas.parentElement.clientWidth;
            canvas.height = canvas.parentElement.clientHeight;
        }
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        const particles = Array.from({
            length: 22
        }, () => ({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            radius: Math.random() * 2 + 1,
            dx: (Math.random() - 0.5) * 0.5,
            dy: (Math.random() - 0.5) * 0.5,
            color: Math.random() > 0.5 ? '#00a8b5' : '#2dd4bf'
        }));

        function animateParticles() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => {
                p.x += p.dx;
                p.y += p.dy;

                if (p.x < 0 || p.x > canvas.width) p.dx *= -1;
                if (p.y < 0 || p.y > canvas.height) p.dy *= -1;

                ctx.beginPath();
                ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                ctx.fillStyle = p.color;
                ctx.globalAlpha = 0.35;
                ctx.fill();
            });
            requestAnimationFrame(animateParticles);
        }
        animateParticles();
    </script>

    <!-- Alert SweetAlert2 untuk Error Login -->
    @if (session()->has('loginError'))
        <script>
            Swal.fire({
                icon: 'error',
                title: '<span class="font-serif font-bold text-xl text-slate-800">Login Gagal!</span>',
                html: '<p class="font-sans text-slate-600 text-sm">{{ session('loginError') }}</p>',
                background: '#ffffff',
                showConfirmButton: true,
                confirmButtonText: 'Coba Lagi',
                confirmButtonColor: '#00a8b5',
                customClass: {
                    popup: 'rounded-2xl shadow-2xl',
                    confirmButton: 'px-6 py-2.5 rounded-xl font-serif font-semibold text-sm tracking-wide'
                }
            });
        </script>
    @endif

</body>

</html>
