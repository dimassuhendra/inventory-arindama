<?php

namespace App\Exports\Sheets;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Suppliers;
use App\Models\Department;
use App\Models\Pic;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReferenceSheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    public function headings(): array
    {
        return [
            'Perusahaan Tersedia',
            'Kategori Tersedia',
            'Subkategori (Kategori)',
            'Vendor / Supplier',
            'Departemen (Perusahaan)',
            'PIC / Pengguna (Perusahaan)',
            'Status Aset Acuan',
            'Kondisi Acuan',
        ];
    }

    public function collection()
    {
        $companies = ['Perusahaan A', 'Perusahaan B', 'Perusahaan C', 'Perusahaan D', 'Perusahaan E', 'General'];

        $categories = Category::pluck('name')->toArray();

        $subCategories = SubCategory::with('category')->get()->map(function ($sub) {
            return $sub->name . ' (' . ($sub->category->name ?? '-') . ')';
        })->toArray();

        $suppliers = Suppliers::pluck('name')->toArray();

        $departments = Department::get()->map(function ($dept) {
            return $dept->name . ' (' . ($dept->company_name ?? 'General') . ')';
        })->toArray();

        $pics = Pic::get()->map(function ($p) {
            return $p->name . ' (' . ($p->company_name ?? 'General') . ')';
        })->toArray();

        $statuses = ['Aktif Digunakan', 'Tersimpan Gudang', 'Dalam Perawatan', 'Dipinjamkan', 'Dihentikan/Afkir'];
        $conditions = ['Sangat Baik', 'Baik', 'Rusak Ringan', 'Rusak Berat'];

        $maxCount = max(
            count($companies),
            count($categories),
            count($subCategories),
            count($suppliers),
            count($departments),
            count($pics),
            count($statuses),
            count($conditions)
        );

        $data = [];

        for ($i = 0; $i < $maxCount; $i++) {
            $data[] = [
                'company'     => $companies[$i] ?? '',
                'category'    => $categories[$i] ?? '',
                'subcategory' => $subCategories[$i] ?? '',
                'supplier'    => $suppliers[$i] ?? '',
                'department'  => $departments[$i] ?? '',
                'pic'         => $pics[$i] ?? '',
                'status'      => $statuses[$i] ?? '',
                'condition'   => $conditions[$i] ?? '',
            ];
        }

        return collect($data);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => '0C66C8'], // Blue accent
                ],
            ],
        ];
    }

    public function title(): string
    {
        return '2. Referensi Data (Kamus)';
    }
}
