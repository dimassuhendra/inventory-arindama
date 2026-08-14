<?php

namespace App\Services;

use App\Models\Pic;
use App\Models\Department;
use Illuminate\Http\Request;

class PicService
{
    public function getPicPageData(Request $request): array
    {
        $search = $request->input('search');
        $departmentId = $request->input('department_id');
        $perPage = $request->input('per_page', 10);

        $query = Pic::with(['department'])->withCount('products');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        $limit = $perPage === 'all' ? 10000 : (int) $perPage;
        $pics = $query->latest()->paginate($limit)->appends($request->all());

        return [
            'pics'        => $pics,
            'departments' => Department::orderBy('name', 'asc')->get(),
            'total_pics'  => Pic::count(),
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
