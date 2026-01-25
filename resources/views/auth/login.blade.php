<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Arindama Inventory</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=acme:wght@400;700&family=dynapuff:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        dynapuff: ['dynapuff', 'sans-serif'],
                        acme: ['acme', 'serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .bg-gradient-blue {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        }

        body {
            font-family: 'dynapuff', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-blue h-screen flex items-center justify-center p-4">

    <div
        class="bg-white rounded-3xl shadow-2xl flex flex-col md:flex-row max-w-5xl w-full min-h-[580px] overflow-hidden relative font-dynapuff">

        <div class="w-full md:w-5/12 p-10 lg:p-14 z-20 bg-white">
            <div class="mb-10">
                <h2 class="text-4xl font-bold text-blue-900 mb-2 tracking-wide">MYBOLO Inventory System</h2>
                <p class="text-gray-500 font-medium font-acme">Silahkan login untuk masuk ke sistem</p>
            </div>

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div class="relative">
                        <label class="block text-sm font-bold text-gray-700 mb-1 font-acme">Username / Email</label>
                        <input type="email" name="email"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none transition font-dynapuff"
                            placeholder="nama@email.com" required>
                    </div>

                    <div class="relative">
                        <label class="block text-sm font-bold text-gray-700 mb-1 font-acme">Password</label>
                        <input type="password" name="password"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none transition font-dynapuff"
                            placeholder="••••••••" required>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 text-white font-bold py-3.5 rounded-xl hover:bg-blue-700 transform hover:scale-[1.02] transition-all shadow-lg tracking-widest text-lg">
                        LOGIN
                    </button>
                </div>
            </form>

            <p class="mt-12 text-center text-xs text-gray-400 uppercase tracking-[0.2em] font-bold">Powered by Arindama
                Andra Tech</p>
        </div>

        <div class="hidden md:block absolute left-[41.666%] top-0 h-full w-24 z-10">
            <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 0 C 50 25 50 75 0 100 L 100 100 L 100 0 Z" fill="#eff6ff" />
            </svg>
        </div>

        <div class="hidden md:flex w-7/12 bg-blue-50 items-center justify-center p-12 relative">
            <div class="text-center z-20">
                <div class="mt-10">
                    <img src="{{ asset('img/MYBOLO.png') }}"
                        class="w-92 mx-auto drop-shadow-2xl transform -rotate-3 hover:rotate-0 transition-transform duration-500"
                        alt="Inventory Illustration">
                </div>
            </div>

            <div class="absolute top-[-30px] right-[-30px] w-64 h-64 bg-blue-200 rounded-full opacity-20"></div>
            <div class="absolute bottom-10 left-10 w-20 h-20 bg-blue-300 rounded-full opacity-10"></div>
        </div>

        @if(session()->has('loginError'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: '<span class="font-dynapuff text-2xl text-red-600">Login Gagal!</span>',
                    html: '<p class="font-acme text-gray-600">{{ session('loginError') }}</p>',
                    background: '#ffffff',
                    showConfirmButton: true,
                    confirmButtonText: 'Coba Lagi',
                    confirmButtonColor: '#2563eb',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    },
                    customClass: {
                        popup: 'rounded-3xl shadow-2xl',
                        confirmButton: 'px-8 py-2 rounded-xl font-bold tracking-wide uppercase'
                    },
                    backdrop: `
                    rgba(30, 64, 175, 0.4)
                    left top
                    no-repeat
                `
                });
            </script>
        @endif

</body>

</html>