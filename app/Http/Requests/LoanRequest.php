<?php

namespace App\Http\Requests;

use App\Models\Products;
use Illuminate\Foundation\Http\FormRequest;

class LoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->input('product_id');
        $product = $productId ? Products::find($productId) : null;
        $maxStock = $product ? $product->quantity : 1;

        return [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:' . $maxStock,
            'borrower_name' => 'required|string|max:255',
            'borrower_contact' => 'required|string|max:50',
            'loan_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:loan_date',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Silakan pilih barang yang akan dipinjam.',
            'quantity.required' => 'Jumlah barang wajib diisi.',
            'quantity.max' => 'Jumlah pinjam melebihi stok barang yang tersedia.',
            'borrower_name.required' => 'Nama peminjam wajib diisi.',
            'borrower_contact.required' => 'Kontak / WhatsApp peminjam wajib diisi.',
            'return_date.after_or_equal' => 'Tanggal kembali tidak boleh lebih awal dari tanggal pinjam.',
        ];
    }
}
