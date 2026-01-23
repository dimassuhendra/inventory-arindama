<?php

namespace App\Exports;

use App\Models\Products;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Products::with(['category', 'supplier'])->get();
    }

    // Menentukan judul kolom di Excel
    public function headings(): array
    {
        return [
            'Nama Produk',
            'Kategori',
            'Supplier',
            'Stok',
            'Satuan',
            'Deskripsi',
            'Tanggal Diperbarui',
        ];
    }

    // Menentukan data apa saja yang masuk ke kolom (Mapping)
    public function map($product): array
    {
        return [
            $product->name,
            $product->category->name ?? '-',
            $product->supplier->name ?? '-',
            $product->quantity,
            $product->unit,
            $product->description,
            $product->updated_at->format('d-m-Y'),
        ];
    }
}