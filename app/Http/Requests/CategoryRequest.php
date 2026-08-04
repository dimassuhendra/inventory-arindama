<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('category') ? $this->route('category') : null;

        return [
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'allowed_roles' => 'nullable|array',
            'allowed_roles.*' => 'string|exists:roles,name', // Menyesuaikan tabel roles Spatie/Laravel
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori ini sudah ada.',
            'allowed_roles.array' => 'Format role tidak valid.',
        ];
    }
}
