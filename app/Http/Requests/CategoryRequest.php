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
        $category = $this->route('category') ?? $this->route('id');
        $id = is_object($category) ? $category->id : $category;

        return [
            'name'          => 'required|string|max:255|unique:categories,name,' . $id,
            'parent_id'     => 'nullable|exists:categories,id',
            'allowed_roles' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique'   => 'Nama kategori ini sudah terdaftar.',
            'parent_id.exists' => 'Kategori Induk tidak ditemukan.',
        ];
    }
}
