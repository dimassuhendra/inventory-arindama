<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - {{ $product->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-xl max-w-md w-full overflow-hidden border border-gray-100">
        <!-- Header Image / Badge -->
        <div class="relative h-48 bg-gray-200">
            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://ui-avatars.com/api/?name=' . urlencode($product->name) . '&background=F2EDC2&color=346739&bold=true' }}"
                class="w-full h-full object-cover">
            <span
                class="absolute top-4 right-4 bg-white/90 backdrop-blur-md text-emerald-800 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">
                {{ $product->category->name }}
            </span>
        </div>

        <!-- Content -->
        <div class="p-6 space-y-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $product->name }}</h1>
                <p class="text-xs text-gray-400 font-mono mt-1">ID: {{ $product->slug }}</p>
            </div>

            <div class="grid grid-cols-2 gap-3 pt-2">
                <div class="bg-gray-50 p-3 rounded-2xl border border-gray-100">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Stok
                        Tersedia</span>
                    <span class="text-lg font-bold text-emerald-700">{{ number_format($product->quantity, 0) }}
                        {{ $product->unit }}</span>
                </div>
                <div class="bg-gray-50 p-3 rounded-2xl border border-gray-100">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Supplier</span>
                    <span class="text-sm font-semibold text-gray-700 block truncate">{{ $maskedSupplier }}</span>
                </div>
            </div>

            <div
                class="bg-emerald-50/60 border border-emerald-100 p-4 rounded-2xl flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-emerald-500 text-white rounded-xl flex items-center justify-center shadow-sm flex-shrink-0">
                        <i class="fa-solid fa-calendar-check text-base"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider block">Mulai
                            Digunakan</span>
                        <span class="text-xs font-bold text-gray-800">
                            @if ($product->first_used_at)
                                {{ \Carbon\Carbon::parse($product->first_used_at)->format('d M Y') }}
                            @else
                                Belum Digunakan
                            @endif
                        </span>
                    </div>
                </div>

                @if ($usageAge)
                    <div class="text-right flex-shrink-0">
                        <span class="text-[9px] font-bold text-emerald-700 uppercase tracking-wider block">Masa
                            Pakai</span>
                        <span
                            class="text-xs font-bold text-emerald-700 bg-white px-2.5 py-1 rounded-lg border border-emerald-200 inline-block mt-0.5 shadow-xs">
                            {{ $usageAge }}
                        </span>
                    </div>
                @endif
            </div>

            <div class="pt-2">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Deskripsi</span>
                <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-2xl border border-gray-100 leading-relaxed">
                    {{ $product->description }}
                </p>
            </div>
        </div>

        <div class="p-4 bg-gray-50 border-t border-gray-100 text-center">
            <p class="text-[10px] text-gray-400 font-medium">Mybolo Inventory</p>
        </div>
    </div>
</body>

</html>
