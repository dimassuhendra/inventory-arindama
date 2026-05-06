@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-2xl lg:text-3xl font-bold text-primary font-serif">Stock Out (Keluar)</h2>
                <p class="text-xs text-secondary font-sans uppercase tracking-widest mt-2 font-semibold">Pengeluaran
                    Inventaris</p>
            </div>
            <button onclick="document.getElementById('modalStockOut').classList.remove('hidden')"
                class="bg-red-500 hover:bg-red-600 text-white px-6 py-3.5 rounded-xl shadow-lg shadow-red-500/20 transition-all flex items-center justify-center gap-3 font-bold text-sm tracking-wide">
                <i class="fa-solid fa-minus"></i> Catat Barang Keluar
            </button>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-red-50/50 border-b border-red-100">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold text-red-800 uppercase tracking-widest">Tanggal</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-red-800 uppercase tracking-widest">Produk</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-red-800 uppercase tracking-widest text-center">
                            Jumlah</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-red-800 uppercase tracking-widest">Petugas</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-red-800 uppercase tracking-widest text-center">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 font-sans">
                    @forelse($exits as $exit)
                        <tr class="hover:bg-red-50/20 transition duration-200">
                            <td class="px-6 py-4 text-xs font-medium text-gray-600">
                                {{ date('d/m/Y', strtotime($exit->exit_date)) }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-800">{{ $exit->product->name }}</span>
                                <p class="text-[10px] text-gray-400 mt-0.5 line-clamp-1 italic">
                                    {{ $exit->description ?: 'Tanpa keterangan' }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="bg-red-100 text-red-600 border border-red-200 px-3 py-1.5 rounded-full text-[11px] font-bold tracking-wide">
                                    -{{ $exit->quantity }} <span class="font-medium">{{ $exit->product->unit }}</span>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500">
                                {{ $exit->user->name ?? 'System' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <form action="{{ route('stock-out.destroy', $exit->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus record ini? Stok akan dikembalikan ke gudang.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Batalkan Pengeluaran"
                                            class="w-9 h-9 rounded-xl bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-600 transition flex items-center justify-center border border-gray-200 hover:border-red-200 shadow-sm">
                                            <i class="fa-solid fa-rotate-left text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic text-sm">Belum ada riwayat
                                pengeluaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-gray-50">{{ $exits->links() }}</div>
        </div>
    </div>

    <!-- Modal Form -->
    <div id="modalStockOut"
        class="fixed inset-0 bg-primary/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div
            class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all border border-red-200">
            <div class="bg-red-500 p-6 text-white flex justify-between items-center">
                <h3 class="font-bold text-lg font-serif tracking-wide">INPUT BARANG KELUAR</h3>
                <button onclick="document.getElementById('modalStockOut').classList.add('hidden')"
                    class="text-white/80 hover:text-white transition transform hover:rotate-90">
                    <i class="fa-solid fa-circle-xmark text-2xl"></i>
                </button>
            </div>

            <form action="{{ route('stock-out.store') }}" method="POST" class="p-8 space-y-5 font-sans">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Pilih
                        Barang</label>
                    <select name="product_id" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">
                        <option value="">-- Pilih Produk Tersedia --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} (Sisa: {{ $product->quantity }}
                                {{ $product->unit }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Jumlah
                            Keluar</label>
                        <input type="number" name="quantity" step="0.01" min="0.01" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Tanggal</label>
                        <input type="date" name="exit_date" value="{{ date('Y-m-d') }}" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Keterangan /
                        Tujuan</label>
                    <textarea name="description" rows="2" placeholder="Misal: Untuk kebutuhan maintenance gedung A"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition"></textarea>
                </div>

                <button type="submit"
                    class="w-full bg-red-500 text-white font-bold py-4 rounded-xl shadow-lg shadow-red-500/30 hover:bg-red-600 transition-all uppercase text-xs tracking-widest mt-4">
                    Konfirmasi Pengeluaran
                </button>
            </form>
        </div>
    </div>

    <!-- Scripts remain identical -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false
            });
        @endif
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: "{{ session('error') }}"
            });
        @endif
    </script>
@endsection
