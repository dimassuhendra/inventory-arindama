<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Services\SupplierService;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    protected SupplierService $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    public function index(Request $request)
    {
        $data = $this->supplierService->getSupplierPageData($request);
        $data['pageTitle'] = 'Master Supplier';

        return view('suppliers', $data);
    }

    public function store(SupplierRequest $request)
    {
        try {
            $this->supplierService->createSupplier($request->validated());
            return redirect()->back()->with('success', 'Supplier berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function update(SupplierRequest $request, $id)
    {
        try {
            $this->supplierService->updateSupplier((int)$id, $request->validated());
            return redirect()->back()->with('success', 'Data supplier berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->supplierService->deleteSupplier((int)$id);
            return redirect()->back()->with('success', 'Supplier berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
