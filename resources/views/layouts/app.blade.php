<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Mybolo Inventory</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Domine:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Outfit"', 'sans-serif'],
                        serif: ['"Domine"', 'serif']
                    },
                    colors: {
                        primary: '#346739',
                        /* Hijau Tua */
                        secondary: '#79AE6F',
                        /* Hijau Sedang */
                        accent: '#9FCB98',
                        /* Hijau Muda */
                        background: '#F2EDC2' /* Krem */
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-sans bg-background min-h-screen text-gray-800" x-data="{ isSideOpen: false }">
    <div class="flex">
        @include('layouts.sidebar')

        <div class="flex-1 lg:ml-80 flex flex-col min-h-screen relative z-10 w-full">
            @include('layouts.navbar')

            <main class="px-4 lg:px-8 pb-10 flex-1">
                <!-- Area konten Anda nantinya disarankan menggunakan container warna putih (bg-white) agar kontras dengan bg krem -->
                @yield('content')
            </main>

            @include('layouts.footer')
        </div>
    </div>

    <!-- Overlay Mobile Sidebar -->
    <div x-show="isSideOpen" @click="isSideOpen = false"
        class="fixed inset-0 bg-primary/60 z-40 lg:hidden backdrop-blur-sm"
        x-transition:enter="transition opacity ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition opacity ease-in duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>
</body>

</html>
