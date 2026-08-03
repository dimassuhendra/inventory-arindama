<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} | Mybolo Inventory</title>

    <!-- Font Outfit & Domine -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Domine:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Tailwind, Alpine.js, FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Tailwind Config Tema Baru -->
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
                            page: '#f1f5f9',
                            /* Soft Slate/Ice (Bukan #ffffff) */
                            dark: '#081d34' /* Deep Navy Sidebar */
                        }
                    },
                    fontFamily: {
                        sans: ['"Outfit"', 'sans-serif'],
                        serif: ['"Domine"', 'serif']
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(0, 168, 181, 0.3);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 168, 181, 0.6);
        }
    </style>
</head>

<body class="font-sans bg-inv-page h-full text-slate-800 antialiased select-none" x-data="{ isCollapsed: false, isMobileOpen: false }">

    <div class="flex h-full min-h-screen overflow-hidden">

        <!-- Sidebar Buka-Tutup (Collapsible) -->
        @include('layouts.sidebar')

        <!-- Main Workspace Area -->
        <div class="flex-1 flex flex-col h-full min-w-0 overflow-y-auto custom-scrollbar transition-all duration-300">

            <!-- Navbar Melayang -->
            @include('layouts.navbar')

            <!-- Main Content Container -->
            <main class="flex-1 px-4 lg:px-8 pb-8 pt-2">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="mt-auto px-8 py-4 text-center text-xs text-slate-400 border-t border-slate-200/60">
                &copy; {{ date('Y') }} <span class="font-bold text-inv-teal">Mybolo Inventory</span>. Enterprise
                Resource Management.
            </footer>
        </div>
    </div>

    <!-- Backdrop Mobile -->
    <div x-show="isMobileOpen" @click="isMobileOpen = false" x-cloak
        class="fixed inset-0 bg-inv-dark/60 z-40 lg:hidden backdrop-blur-sm"
        x-transition:enter="transition opacity ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition opacity ease-in duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>
</body>

</html>
