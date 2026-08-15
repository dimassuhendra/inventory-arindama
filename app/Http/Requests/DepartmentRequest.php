<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('department') ? $this->route('department')->id : null;

        return [
            'name'          => 'required|string|max:255|unique:departments,name,' . $id,
            'code'          => 'nullable|string|max:20|unique:departments,code,' . $id,
            'description'   => 'nullable|string',
            'company_ids'   => 'required|array|min:1',
            'company_ids.*' => 'exists:companies,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'Nama departemen wajib diisi.',
            'name.unique'         => 'Nama departemen ini sudah terdaftar.',
            'code.unique'         => 'Kode departemen ini sudah digunakan.',
            'company_ids.required' => 'Pilih minimal satu perusahaan yang menggunakan departemen ini.',
            'company_ids.min'     => 'Pilih minimal satu perusahaan.',
        ];
    }
}
