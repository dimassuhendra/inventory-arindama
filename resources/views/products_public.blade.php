<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Produk - {{ $product->name }}</title>

    <!-- Google Fonts: Outfit & Domine -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Domine:wght@400..700&family=Outfit:wght@100..900&display=swap"
        rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Outfit"', 'sans-serif'],
                        serif: ['"Domine"', 'serif']
                    },
                    colors: {
                        inv: {
                            primary: '#0c66c8',
                            teal: '#00a8b5',
                            mint: '#2dd4bf',
                            dark: '#081d34'
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-slate-900/90 min-h-screen flex items-center justify-center p-4 font-sans">
    <div class="bg-slate-100 rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden border border-slate-300">

        <!-- Header Image & Category Badge -->
        <div class="relative h-48 bg-slate-200 overflow-hidden">
            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://ui-avatars.com/api/?name=' . urlencode($product->name) . '&background=00a8b5&color=ffffff&bold=true' }}"
                class="w-full h-full object-cover">
            <span
                class="absolute top-3 right-3 bg-slate-900/80 backdrop-blur-md text-inv-mint text-[10px] font-bold px-3 py-1 rounded-full shadow-sm">
                {{ $product->category->name }}
            </span>
        </div>

        <!-- Product Details -->
        <div class="p-5 space-y-4">
            <div>
                <h1 class="text-lg font-serif font-bold text-slate-800 leading-tight">{{ $product->name }}</h1>
                <p class="text-[10px] text-slate-400 font-mono mt-1">ID: {{ $product->slug }}</p>
            </div>

            <!-- Stock & Supplier Card -->
            <div class="grid grid-cols-2 gap-2.5">
                <div class="bg-white p-3 rounded-2xl border border-slate-200">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Stok
                        Tersedia</span>
                    <span class="text-base font-bold text-emerald-600">
                        {{ number_format($product->quantity, 0) }} {{ $product->unit }}
                    </span>
                </div>
                <div class="bg-white p-3 rounded-2xl border border-slate-200">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Supplier</span>
                    <span
                        class="text-xs font-semibold text-slate-700 block truncate mt-0.5">{{ $maskedSupplier }}</span>
                </div>
            </div>

            <!-- Usage Info Card -->
            <div class="bg-inv-teal/10 border border-inv-teal/30 p-3.5 rounded-2xl flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-inv-teal text-white rounded-xl flex items-center justify-center text-xs">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-inv-primary uppercase tracking-wider block">Mulai
                            Digunakan</span>
                        <span class="text-xs font-bold text-slate-800">
                            {{ $product->first_used_at ? \Carbon\Carbon::parse($product->first_used_at)->format('d M Y') : 'Belum Digunakan' }}
                        </span>
                    </div>
                </div>

                @if ($usageAge)
                    <div class="text-right">
                        <span class="text-[9px] font-bold text-emerald-700 uppercase tracking-wider block">Masa
                            Pakai</span>
                        <span
                            class="text-xs font-bold text-emerald-700 bg-white px-2 py-0.5 rounded-md border border-emerald-200 inline-block mt-0.5">
                            {{ $usageAge }}
                        </span>
                    </div>
                @endif
            </div>

            <!-- Description -->
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Deskripsi
                    Produk</span>
                <p class="text-xs text-slate-600 bg-white p-3 rounded-2xl border border-slate-200 leading-relaxed">
                    {{ $product->description }}
                </p>
            </div>
        </div>

        <!-- Footer dengan Logo -->
        <div class="p-3 bg-slate-200/80 border-t border-slate-300 flex items-center justify-center gap-2">
            <img src="{{ asset('img/Inventory.png') }}" alt="Logo" class="h-5 w-auto object-contain">
            <p class="text-[10px] font-bold text-slate-500">Mybolo Asset & Inventory System</p>
        </div>
    </div>
</body>

</html>
