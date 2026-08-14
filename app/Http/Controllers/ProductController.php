<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;
use App\Imports\ProductImport;
use App\Exports\ProductTemplateExport;
use App\Exports\ProductsBulkEditExport;
use App\Imports\ProductsBulkEditImport;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $data = $this->productService->getProductPageData($request);
        $data['pageTitle'] = 'Master Produk';

        return view('products', $data);
    }

    public function store(ProductRequest $request)
    {
        try {
            $this->productService->createProduct($request->validated(), $request);
            return redirect()->back()->with('success', 'Produk berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function update(ProductRequest $request, $id)
    {
        try {
            $this->productService->updateProduct((int)$id, $request->validated(), $request);
            return redirect()->back()->with('success', 'Data produk berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->productService->deleteProduct((int)$id);
            return redirect()->back()->with('success', 'Produk berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function export()
    {
        return Excel::download(new ProductsExport(), 'Laporan-Stok-Arindama-' . date('d-M-Y') . '.xlsx');
    }

    public function template()
    {
        return Excel::download(new ProductTemplateExport(), 'Template-Import-Produk.xlsx');
    }

    public function sync(Request $request)
    {
        try {
            $companyName = $request->input('company_name');


            return redirect()->back()->with('success', 'Sinkronisasi data aset berhasil dijalankan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal sinkronisasi: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new ProductTemplateExport, 'Template_Import_Aset_Mybolo.xlsx');
    }

    // Method untuk Eksekusi File Import
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new ProductImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data aset berhasil di-import secara masal!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }

    public function exportForEdit()
    {
        return Excel::download(new ProductsBulkEditExport(), 'Sinkronisasi-Data-Produk.xlsx');
    }

    public function importEdit(Request $request)
    {
        $request->validate([
            'file_excel_edit' => 'required|mimes:xlsx,xls,csv|max:2048',
        ], [
            'file_excel_edit.required' => 'File Excel tidak boleh kosong!',
        ]);

        try {
            Excel::import(new ProductsBulkEditImport(), $request->file('file_excel_edit'));
            return redirect()->back()->with('success', 'Pembaruan massal produk berhasil diproses!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data: Pastikan kolom ID Produk tidak dihapus.');
        }
    }

    public function publicShow($slug)
    {
        $data = $this->productService->getPublicProductDetail($slug);
        return view('products_public', $data);
    }
}
