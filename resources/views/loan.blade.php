@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-2xl lg:text-3xl font-bold text-primary font-serif">Peminjaman Aset</h2>
                <p class="text-secondary text-xs font-sans uppercase tracking-widest mt-2 font-semibold">Kelola sirkulasi
                    peminjaman barang</p>
            </div>
            <button onclick="document.getElementById('modalLoan').classList.remove('hidden')"
                class="bg-primary text-white px-6 py-3.5 rounded-xl font-bold shadow-lg shadow-primary/20 hover:bg-secondary transition-all text-sm tracking-wide flex items-center justify-center gap-3">
                <i class="fa-solid fa-hand-holding-hand"></i> Tambah Peminjaman
            </button>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-accent/30">
            <table class="w-full text-left border-collapse text-sm">
                <thead class="bg-primary/5 border-b border-accent/20">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold text-primary uppercase tracking-widest">Peminjam</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-primary uppercase tracking-widest">Barang</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-primary uppercase tracking-widest">Tgl Kembali</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-primary uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-primary uppercase tracking-widest text-center">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 font-sans">
                    @forelse($loans as $loan)
                        <tr class="hover:bg-primary/5 transition-colors duration-200">
                            <td class="px-6 py-4 text-primary font-medium">
                                {{ $loan->borrower_name }} <br>
                                <span class="text-[11px] text-gray-500 font-normal"><i
                                        class="fa-solid fa-phone text-[9px] mr-1"></i>{{ $loan->borrower_contact }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-800">{{ $loan->product->name }}</span> <br>
                                <span class="text-xs text-secondary font-semibold">{{ $loan->quantity }} Unit</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $loan->status == 'borrowed' ? 'bg-amber-50 text-amber-600 border-amber-200' : 'bg-accent/20 text-primary border-accent/30' }}">
                                    {{ $loan->status == 'borrowed' ? 'Dipinjam' : 'Dikembalikan' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($loan->status == 'borrowed')
                                    <form action="{{ route('loans.return', $loan->id) }}" method="POST">
                                        @csrf
                                        <button
                                            class="bg-secondary text-white px-4 py-2 rounded-xl text-[10px] font-bold shadow-sm hover:bg-primary transition tracking-widest uppercase">
                                            Kembalikan
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-[10px] italic">
                                        <i class="fa-solid fa-check text-secondary mr-1"></i> Selesai
                                        ({{ \Carbon\Carbon::parse($loan->actual_return_date)->format('d/m') }})
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="bg-primary/5 w-16 h-16 rounded-2xl flex items-center justify-center mb-4 border border-accent/20">
                                        <i class="fa-solid fa-box-open text-2xl text-secondary"></i>
                                    </div>
                                    <p class="text-primary font-bold text-lg font-serif">Belum ada barang terpinjam</p>
                                    <p class="text-gray-400 text-xs font-sans mt-1">Semua aset tersedia atau belum ada
                                        aktivitas peminjaman.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form Peminjaman -->
    <div id="modalLoan"
        class="fixed inset-0 bg-primary/80 backdrop-blur-sm z-[60] hidden flex items-center justify-center p-4">
        <div
            class="bg-white w-full max-w-lg rounded-3xl p-8 shadow-2xl border border-accent/20 transform transition-all font-sans">
            <h3 class="text-xl font-bold text-primary mb-6 font-serif tracking-wide border-b border-gray-100 pb-4">Form
                Peminjaman Baru</h3>

            <form action="{{ route('loans.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Pilih
                            Barang</label>
                        <select name="product_id"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
                            @foreach ($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} (Stok: {{ $p->quantity }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label
                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Jumlah</label>
                        <input type="number" name="quantity"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-2">
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Nama
                            Peminjam</label>
                        <input type="text" name="borrower_name" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Kontak
                            Peminjam</label>
                        <input type="text" name="borrower_contact" required placeholder="No. HP / WA"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-2">
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Tgl
                            Pinjam</label>
                        <input type="date" name="loan_date" value="{{ date('Y-m-d') }}"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Tgl
                            Kembali</label>
                        <input type="date" name="return_date"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
                    </div>
                </div>

                <div class="flex gap-3 pt-6 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('modalLoan').classList.add('hidden')"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-500 font-bold py-3.5 rounded-xl transition text-sm">Batal</button>
                    <button type="submit"
                        class="flex-1 bg-primary hover:bg-secondary text-white font-bold py-3.5 rounded-xl shadow-lg shadow-primary/20 transition text-sm">Simpan
                        Data</button>
                </div>
            </form>
        </div>
    </div>
@endsection
