<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\ProductEditSheet;
use App\Exports\Sheets\ReferenceSheet; // Kita panggil kembali sheet kamus yang sudah ada!

class ProductsBulkEditExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new ProductEditSheet(), // Sheet berisi daftar barang yang siap diedit
            new ReferenceSheet(),   // Sheet berisi daftar kategori & supplier (Kamus)
        ];
    }
}
