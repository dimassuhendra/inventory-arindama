<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Identifikasi & Klasifikasi
            'company_name'                 => 'required|in:PT Agung Putra Nirantara Mandiri,PT Kirana Baskara Kuwara,PT Lancar Anja Kuwaga,PT Praguwa Wahyu Astama,PT Teknologi Arindama Andra,General',
            'category_id'                  => 'required|exists:categories,id',
            'sub_category_id'              => 'nullable|exists:sub_categories,id',
            'name'                         => 'required|string|max:255',
            'brand_model'                  => 'nullable|string|max:255',
            'serial_number'                => 'nullable|string|max:255',

            // Pengadaan & Finansial
            'supplier_id'                  => 'nullable|exists:suppliers,id',
            'po_invoice_number'            => 'nullable|string|max:255',
            'purchase_date'                => 'nullable|date',
            'first_used_at'                => 'nullable|date',
            'purchase_cost'                => 'nullable|numeric|min:0',
            'residual_value'               => 'nullable|numeric|min:0',
            'useful_life_years'            => 'nullable|integer|min:0',

            // Penempatan & PIC
            'department_id'                => 'nullable|exists:departments,id',
            'location'                     => 'nullable|string|max:255',
            'pic_id'                       => 'nullable|exists:pics,id',

            // Pemeliharaan & Status
            'condition'                    => 'required|in:Sangat Baik,Baik,Rusak Ringan,Rusak Berat',
            'asset_status'                 => 'required|in:Aktif Digunakan,Tersimpan Gudang,Dalam Perawatan,Dipinjamkan,Dihentikan/Afkir',
            'last_maintenance_date'        => 'nullable|date',
            'maintenance_frequency_days'   => 'nullable|integer|min:0',

            // Umum
            'quantity'                     => 'nullable|numeric|min:0',
            'unit'                         => 'required|string|max:50',
            'description'                  => 'nullable|string',
            'image'                        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'company_name.required' => 'Nama perusahaan wajib dipilih.',
            'category_id.required'  => 'Kategori utama wajib dipilih.',
            'name.required'         => 'Nama aset wajib diisi.',
            'unit.required'         => 'Satuan aset wajib diisi.',
            'condition.required'    => 'Kondisi aset wajib dipilih.',
            'asset_status.required' => 'Status aset wajib dipilih.',
            'image.image'           => 'File harus berupa gambar.',
            'image.max'             => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
