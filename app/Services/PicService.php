<?php

namespace App\Services;

use App\Models\Pic;
use App\Models\Department;
use App\Models\Company;
use App\Models\Products;
use Illuminate\Http\Request;

class PicService
{
    public function getPicPageData(Request $request): array
    {
        $search       = $request->input('search');
        $companyName  = $request->input('company_name');
        $departmentId = $request->input('department_id');
        $perPage      = $request->input('per_page', 10);

        $query = Pic::with(['department.companies'])->withCount('products');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($companyName) {
            $query->where('company_name', $companyName);
        }

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        $limit = $perPage === 'all' ? 10000 : (int) $perPage;
        $pics  = $query->latest()->paginate($limit)->appends($request->all());

        // Mengambil departemen beserta relasi perusahaan pivot-nya
        $departments = Department::with('companies')->orderBy('name', 'asc')->get();
        $companies   = Company::orderBy('name', 'asc')->get();

        return [
            'pics'               => $pics,
            'departments'        => $departments,
            'companies'          => $companies,
            'total_pics'         => Pic::count(),
            'pics_with_assets'   => Pic::has('products')->count(),
            'total_assets_assigned' => Products::whereNotNull('pic_id')->count(),
        ];
    }

    public function createPic(array $data): Pic
    {
        return Pic::create($data);
    }

    public function updatePic(Pic $pic, array $data): Pic
    {
        $pic->update($data);
        return $pic;
    }

    public function deletePic(Pic $pic): bool
    {
        if ($pic->products()->count() > 0) {
            throw new \Exception('PIC tidak dapat dihapus karena masih memegang aset aktif.');
        }

        return $pic->delete();
    }
}
