<?php

namespace App\Exports\Sheets;

use App\Models\Products;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProductEditSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    public function collection()
    {
        // Tarik semua data produk beserta relasinya
        return Products::with(['category', 'supplier'])->get();
    }

    public function headings(): array
    {
        return [
            'ID Produk (JANGAN DIUBAH)',
            'Nama Produk',
            'Kategori',
            'Supplier',
            'Deskripsi',
            'Satuan Unit',
            'Stok'
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->category->name ?? '',
            $product->supplier->name ?? '',
            $product->description,
            $product->unit,
            $product->quantity,
        ];
    }

    public function title(): string
    {
        return '1. Form Sinkronisasi'; // Nama Sheet Pertama
    }
}
