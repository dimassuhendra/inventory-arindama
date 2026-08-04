<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
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

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:2048',
        ], [
            'file_excel.required' => 'File Excel tidak boleh kosong!',
            'file_excel.mimes' => 'Format file harus .xlsx, .xls, atau .csv',
            'file_excel.max' => 'Ukuran file maksimal 2MB!',
        ]);

        try {
            Excel::import(new ProductsImport(), $request->file('file_excel'));
            return redirect()->back()->with('success', 'Data produk dari Excel berhasil diimpor!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor file: Pastikan ID Kategori valid dan format Excel sesuai template.');
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
