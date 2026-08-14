<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use App\Services\DepartmentService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    protected $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    public function index(Request $request)
    {
        $data = $this->departmentService->getDepartmentPageData($request);
        return view('departments', $data);
    }

    public function store(DepartmentRequest $request)
    {
        try {
            $this->departmentService->createDepartment($request->validated());
            return redirect()->back()->with('success', 'Departemen berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(DepartmentRequest $request, Department $department)
    {
        try {
            $this->departmentService->updateDepartment($department, $request->validated());
            return redirect()->back()->with('success', 'Departemen berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Department $department)
    {
        try {
            $this->departmentService->deleteDepartment($department);
            return redirect()->back()->with('success', 'Departemen berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
