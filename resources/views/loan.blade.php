@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-white">Peminjaman Barang</h2>
                <p class="text-blue-100/70 text-xs font-acme uppercase tracking-widest mt-1">Kelola sirkulasi peminjaman
                    aset</p>
            </div>
            <button onclick="document.getElementById('modalLoan').classList.remove('hidden')"
                class="bg-white text-blue-600 px-6 py-3 rounded-2xl font-bold shadow-lg hover:scale-105 transition-all text-sm">
                + Tambah Peminjaman
            </button>
        </div>

        <div class="bg-white/90 backdrop-blur-md rounded-[1rem] shadow-xl overflow-hidden border border-white/20">
            <table class="w-full text-left border-collapse text-sm">
                <thead class="bg-blue-50/50 border-b border-blue-100">
                    <tr>
                        <th class="px-6 py-4 font-bold text-blue-900">PEMINJAM</th>
                        <th class="px-6 py-4 font-bold text-blue-900">BARANG</th>
                        <th class="px-6 py-4 font-bold text-blue-900">TGL KEMBALI</th>
                        <th class="px-6 py-4 font-bold text-blue-900">STATUS</th>
                        <th class="px-6 py-4 font-bold text-blue-900 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blue-50">
                    @forelse($loans as $loan)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="px-6 py-4 text-blue-900 font-medium">
                                {{ $loan->borrower_name }} <br>
                                <span class="text-[10px] text-gray-400 font-normal">{{ $loan->borrower_contact }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-blue-700">{{ $loan->product->name }}</span> <br>
                                <span class="text-xs text-gray-500">{{ $loan->quantity }} Unit</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-acme">
                                {{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $loan->status == 'borrowed' ? 'bg-amber-100 text-amber-600' : 'bg-green-100 text-green-600' }}">
                                    {{ $loan->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($loan->status == 'borrowed')
                                    <form action="{{ route('loans.return', $loan->id) }}" method="POST">
                                        @csrf
                                        <button
                                            class="bg-blue-600 text-white px-4 py-2 rounded-xl text-[10px] font-bold shadow-md hover:bg-blue-700 transition">KEMBALIKAN</button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-[10px] italic">Selesai pada
                                        {{ $loan->actual_return_date }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="bg-blue-50 w-16 h-16 rounded-2xl flex items-center justify-center mb-4">
                                        <i class="fa-solid fa-box-open text-2xl text-blue-200"></i>
                                    </div>
                                    <p class="text-blue-900 font-bold text-lg">Belum ada barang terpinjam</p>
                                    <p class="text-gray-400 text-xs font-acme mt-1">Semua aset tersedia atau belum ada
                                        aktivitas peminjaman.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="modalLoan"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[60] hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-8 shadow-2xl">
            <h3 class="text-xl font-bold text-blue-900 mb-6 font-dynapuff">Form Peminjaman</h3>
            <form action="{{ route('loans.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Pilih Barang</label>
                        <select name="product_id"
                            class="w-full bg-gray-50 border-none rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500">
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} (Stok: {{ $p->quantity }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Jumlah</label>
                        <input type="number" name="quantity"
                            class="w-full bg-gray-50 border-none rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Nama Peminjam</label>
                        <input type="text" name="borrower_name" required
                            class="w-full bg-gray-50 border-none rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Kontak Peminjam</label>
                        <input type="text" name="borrower_contact" required placeholder="No. HP / WA"
                            class="w-full bg-gray-50 border-none rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Tgl Pinjam</label>
                        <input type="date" name="loan_date" value="{{ date('Y-m-d') }}"
                            class="w-full bg-gray-50 border-none rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Tgl Kembali</label>
                        <input type="date" name="return_date"
                            class="w-full bg-gray-50 border-none rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="document.getElementById('modalLoan').classList.add('hidden')"
                        class="flex-1 bg-gray-100 text-gray-500 font-bold py-3 rounded-2xl">Batal</button>
                    <button type="submit"
                        class="flex-1 bg-blue-600 text-white font-bold py-3 rounded-2xl shadow-lg shadow-blue-200">Simpan
                        Data</button>
                </div>
            </form>
        </div>
    </div>
@endsection