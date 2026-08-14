<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('pic') ? $this->route('pic')->id : null;

        return [
            'name'          => 'required|string|max:255',
            'nip'           => 'nullable|string|max:50|unique:pics,nip,' . $id,
            'department_id' => 'required|exists:departments,id',
            'position'      => 'nullable|string|max:255',
            'company_name' => 'required|in:PT Agung Putra Nirantara Mandiri,PT Kirana Baskara Kuwara,PT Lancar Anja Kuwaga,PT Praguwa Wahyu Astama,PT Teknologi Arindama Andra,General',
            'phone'         => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'Nama PIC wajib diisi.',
            'department_id.required' => 'Departemen wajib dipilih.',
            'nip.unique'             => 'NIK/NIP ini sudah terdaftar.',
            'email.email'            => 'Format email tidak valid.',
        ];
    }
}
