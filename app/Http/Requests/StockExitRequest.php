<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockExitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'quantity' => 'required|numeric|min:0.01',
            'exit_date' => 'required|date',
            'description' => 'nullable|string|max:500',
        ];

        if ($this->isMethod('post')) {
            $rules['product_id'] = 'required|exists:products,id';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Silakan pilih barang terlebih dahulu.',
            'product_id.exists' => 'Barang yang dipilih tidak valid.',
            'quantity.required' => 'Jumlah barang keluar wajib diisi.',
            'quantity.numeric' => 'Jumlah barang harus berupa angka.',
            'quantity.min' => 'Jumlah barang keluar minimal 0.01.',
            'exit_date.required' => 'Tanggal pengeluaran wajib diisi.',
            'exit_date.date' => 'Format tanggal tidak valid.',
        ];
    }
}
