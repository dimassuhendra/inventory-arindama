<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockExitRequest;
use App\Services\StockExitService;
use Illuminate\Http\Request;

class StockExitController extends Controller
{
    protected StockExitService $stockExitService;

    public function __construct(StockExitService $stockExitService)
    {
        $this->stockExitService = $stockExitService;
    }

    public function index(Request $request)
    {
        $data = $this->stockExitService->getStockExitPageData($request);
        $data['pageTitle'] = 'Stock Out (Barang Keluar)';

        return view('stock-out', $data);
    }

    public function store(StockExitRequest $request)
    {
        try {
            $this->stockExitService->createStockExit($request->validated());
            return redirect()->back()->with('success', 'Barang berhasil dikeluarkan dari stok!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function update(StockExitRequest $request, $id)
    {
        try {
            $this->stockExitService->updateStockExit((int)$id, $request->validated());
            return redirect()->back()->with('success', 'Transaksi barang keluar berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Update gagal: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->stockExitService->deleteStockExit((int)$id);
            return redirect()->back()->with('success', 'Transaksi dibatalkan dan stok telah dikembalikan ke gudang.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Batal gagal: ' . $e->getMessage());
        }
    }
}
