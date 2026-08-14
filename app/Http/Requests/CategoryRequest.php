<?php

namespace App\Http\Requests;

use App\Services\CategoryService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Opsi Tambahan: Otomatis sisipkan role user jika bukan Super Admin
     * sebelum proses validasi berjalan.
     */
    protected function prepareForValidation(): void
    {
        $user = Auth::user();

        // Jika user biasa (bukan Super Admin) dan sedang membuat data baru (POST)
        if (!CategoryService::isSuperAdmin() && $user && $this->isMethod('post')) {
            $userRoles = $user->roles->pluck('name')->toArray();

            $this->merge([
                'allowed_roles' => !empty($userRoles) ? array_values($userRoles) : null,
            ]);
        }
    }

    public function rules(): array
    {
        // Ambil ID kategori dengan aman baik berupa objek Model maupun ID integer
        $category = $this->route('category') ?? $this->route('id');
        $id = is_object($category) ? $category->id : $category;

        return [
            'name'            => 'required|string|max:255|unique:categories,name,' . $id,
            'allowed_roles'   => 'nullable|array',
            'allowed_roles.*' => 'string|exists:roles,name',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'Nama kategori wajib diisi.',
            'name.unique'         => 'Nama kategori ini sudah ada.',
            'allowed_roles.array' => 'Format role tidak valid.',
        ];
    }
}
