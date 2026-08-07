<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'quantity' => 'required|numeric|min:0.01',
            'entry_date' => 'required|date',
        ];

        // Pada method STORE, product_id wajib diisi
        if ($this->isMethod('post')) {
            $rules['product_id'] = 'required|exists:products,id';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Silakan pilih produk terlebih dahulu.',
            'product_id.exists' => 'Produk yang dipilih tidak valid.',
            'quantity.required' => 'Jumlah stok masuk wajib diisi.',
            'quantity.numeric' => 'Jumlah stok harus berupa angka.',
            'quantity.min' => 'Jumlah stok masuk minimal 0.01.',
            'entry_date.required' => 'Tanggal masuk wajib diisi.',
            'entry_date.date' => 'Format tanggal tidak valid.',
        ];
    }
}
