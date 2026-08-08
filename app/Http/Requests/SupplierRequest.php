<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('supplier') ?? $this->route('id');

        return [
            'name' => 'required|string|max:255|unique:suppliers,name,' . $id,
            'telp' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama supplier / perusahaan wajib diisi.',
            'name.unique' => 'Nama supplier ini sudah terdaftar.',
            'telp.max' => 'Nomor telepon maksimal 30 karakter.',
        ];
    }
}
