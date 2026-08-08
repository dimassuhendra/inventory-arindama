<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoanRequest;
use App\Services\LoanService;
use Illuminate\Http\Request;

class ProductLoanController extends Controller
{
    protected LoanService $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    public function index(Request $request)
    {
        $data = $this->loanService->getLoanPageData($request);
        $data['pageTitle'] = 'Peminjaman Aset';

        return view('loan', $data);
    }

    public function store(LoanRequest $request)
    {
        try {
            $this->loanService->createLoan($request->validated());
            return redirect()->back()->with('success', 'Transaksi peminjaman berhasil dicatat dan stok telah dipotong.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mencatat peminjaman: ' . $e->getMessage());
        }
    }

    public function returnItem(Request $request, $id)
    {
        try {
            $returnNotes = $request->input('return_notes');
            $this->loanService->returnLoanItem((int)$id, $returnNotes);
            return redirect()->back()->with('success', 'Barang telah dikembalikan dan stok berhasil dipulihkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
    }
}
