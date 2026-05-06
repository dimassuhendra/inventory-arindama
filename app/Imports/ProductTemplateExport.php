<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['Nama Produk', 'ID Kategori', 'ID Supplier', 'Deskripsi', 'Satuan Unit', 'Stok Awal'];
    }

    public function array(): array
    {
        // Ini adalah contoh data yang akan muncul di baris pertama template
        return [['Kertas HVS A4', 1, 1, 'Kertas ukuran A4 80gsm', 'Rim', 50], ['Tinta Epson Hitam', 1, 2, 'Tinta botol original', 'Botol', 10]];
    }
}
