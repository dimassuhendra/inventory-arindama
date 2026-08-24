<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Services\CategoryService;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $category = $this->route('category') ?? $this->route('id');
        $id = is_object($category) ? $category->id : $category;
        $user = Auth::user();

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')
                    ->ignore($id)
                    ->where(function ($query) use ($user) {
                        if (CategoryService::isSuperAdmin()) {
                            return $query->where('categories.user_id', $user->id);
                        }

                        $userRoleNames = $user ? $user->getRoleNames()->toArray() : [];

                        return $query->where(function ($q) use ($user, $userRoleNames) {
                            // Cek milik user itu sendiri
                            $q->where('categories.user_id', $user->id);

                            // Cek role user di kolom JSON allowed_roles
                            foreach ($userRoleNames as $role) {
                                $q->orWhereJsonContains('categories.allowed_roles', $role);
                            }
                        });
                    }),
            ],
            'parent_id'     => 'nullable|exists:categories,id',
            'allowed_roles' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Nama kategori wajib diisi.',
            'name.unique'      => 'Nama kategori ini sudah terdaftar di lingkungan role Anda.',
            'parent_id.exists' => 'Kategori Induk tidak ditemukan.',
        ];
    }
}
