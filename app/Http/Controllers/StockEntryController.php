<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockEntryRequest;
use App\Services\StockEntryService;
use Illuminate\Http\Request;

use App\Models\Category;

class StockEntryController extends Controller
{
    protected StockEntryService $stockEntryService;

    public function __construct(StockEntryService $stockEntryService)
    {
        $this->stockEntryService = $stockEntryService;
    }

    public function index(Request $request)
    {
        $data = $this->stockEntryService->getStockEntryPageData($request);

        $data['categories'] = Category::orderBy('name', 'asc')->get();
        $data['pageTitle'] = 'Stock In (Barang Masuk)';

        return view('stock-in', $data);
    }

    public function store(StockEntryRequest $request)
    {
        try {
            $this->stockEntryService->createStockEntry($request->validated());
            return redirect()->back()->with('success', 'Stok berhasil masuk dan tercatat!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function update(StockEntryRequest $request, $id)
    {
        try {
            $this->stockEntryService->updateStockEntry((int)$id, $request->validated());
            return redirect()->back()->with('success', 'Riwayat stok berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Update gagal: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->stockEntryService->deleteStockEntry((int)$id);
            return redirect()->back()->with('success', 'Riwayat berhasil dihapus dan stok dikurangi.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Hapus gagal: ' . $e->getMessage());
        }
    }
}
