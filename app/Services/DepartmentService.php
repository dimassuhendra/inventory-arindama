<?php

namespace App\Services;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentService
{
    public function getDepartmentPageData(Request $request): array
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = Department::withCount(['pics', 'products']);

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
            'departments'        => $departments,
            'total_departments'  => Department::count(),
        ];
    }

    public function createDepartment(array $data): Department
    {
        return Department::create($data);
    }

    public function updateDepartment(Department $department, array $data): Department
    {
        $department->update($data);
        return $department;
    }

    public function deleteDepartment(Department $department): bool
    {
        if ($department->pics()->count() > 0 || $department->products()->count() > 0) {
            throw new \Exception('Departemen tidak bisa dihapus karena masih terikat dengan data PIC atau Aset.');
        }

        return $department->delete();
    }
}
