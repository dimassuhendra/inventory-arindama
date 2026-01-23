<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Arindama Inventory</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Acme&family=DynaPuff:wght@400..700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { dynapuff: ['DynaPuff'], acme: ['Acme'] },
                    colors: { arindama: '#476EAE' }
                }
            }
        }
    </script>
    <style>
        body {
            background-attachment: fixed;
        }

        [x-cloak] {
            display: none !important;
        }

        .bg-gradient-blue {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            background-attachment: fixed;
        }
    </style>

</head>

<body class="font-dynapuff bg-gradient-blue min-h-screen" x-data="{ isSideOpen: false }">
    <div class="flex">
        @include('layouts.sidebar')

        <div class="flex-1 lg:ml-80 flex flex-col min-h-screen relative z-10 w-full">
            @include('layouts.navbar')

            <main class="px-4 lg:px-8 pb-10 flex-1">
                @yield('content')
            </main>

            @include('layouts.footer')
        </div>
    </div>

    <div x-show="isSideOpen" @click="isSideOpen = false"
        class="fixed inset-0 bg-black/50 z-40 lg:hidden backdrop-blur-sm"
        x-transition:enter="transition opacity ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition opacity ease-in duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>

    <style>
        /* Samakan dengan Login */
        .bg-gradient-blue {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            background-attachment: fixed;
        }
    </style>
</body>

</html>