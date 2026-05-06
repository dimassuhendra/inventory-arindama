<?php

namespace App\Exports\Sheets;

use App\Models\Category;
use App\Models\Suppliers;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReferenceSheet implements FromCollection, WithHeadings, WithTitle
{
    public function headings(): array
    {
        return ['Daftar Kategori Tersedia', 'Daftar Supplier Tersedia'];
    }

    public function collection()
    {
        // Ambil data langsung dari database
        $categories = Category::pluck('name')->toArray();
        $suppliers = Suppliers::pluck('name')->toArray();

        $maxCount = max(count($categories), count($suppliers));
        $data = [];

        // Sejajarkan data kategori dan supplier agar tampil bersebelahan di Excel
        for ($i = 0; $i < $maxCount; $i++) {
            $data[] = [
                'kategori' => $categories[$i] ?? '',
                'supplier' => $suppliers[$i] ?? '',
            ];
        }

        return collect($data);
    }

    public function title(): string
    {
        return '2. Referensi Data (Kamus)'; // Nama Sheet Kedua
    }
}
