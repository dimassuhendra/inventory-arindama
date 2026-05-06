<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProductInputSheet implements WithHeadings, WithTitle
{
    public function headings(): array
    {
        return [
            'Nama Produk',
            'Nama Kategori',
            'Nama Supplier',
            'Deskripsi',
            'Satuan Unit',
            'Stok Awal'
        ];
    }

    public function title(): string
    {
        return '1. Input Data Produk'; // Nama Sheet Pertama
    }
}
