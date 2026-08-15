<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentService
{
    public function getDepartmentPageData(Request $request): array
    {
        $search = $request->input('search');
        $companyId = $request->input('company_id');
        $perPage = $request->input('per_page', 10);

        $query = Department::with(['companies'])->withCount(['pics', 'products']);

        // Filter berdasar Perusahaan tertentu via Pivot
        if ($companyId) {
            $query->whereHas('companies', function ($q) use ($companyId) {
                $q->where('companies.id', $companyId);
            });
        }

        // Search Keyword
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $limit = $perPage === 'all' ? 10000 : (int) $perPage;
        $departments = $query->latest()->paginate($limit)->appends($request->all());

        return [
            'departments'       => $departments,
            'companies'         => Company::orderBy('name')->get(),
            'total_departments' => Department::count(),
        ];
    }

    public function createDepartment(array $data): Department
    {
        $department = Department::create([
            'name'        => $data['name'],
            'code'        => $data['code'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        if (isset($data['company_ids'])) {
            $department->companies()->sync($data['company_ids']);
        }

        return $department;
    }

    public function updateDepartment(Department $department, array $data): Department
    {
        $department->update([
            'name'        => $data['name'],
            'code'        => $data['code'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        if (isset($data['company_ids'])) {
            $department->companies()->sync($data['company_ids']);
        }

        return $department;
    }

    public function deleteDepartment(Department $department): bool
    {
        if ($department->pics()->count() > 0 || $department->products()->count() > 0) {
            throw new \Exception('Departemen tidak bisa dihapus karena masih terikat dengan data PIC atau Aset.');
        }

        // Hapus relasi pivot terlebih dahulu
        $department->companies()->detach();

        return $department->delete();
    }
}
