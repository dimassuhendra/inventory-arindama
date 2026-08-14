<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductInputSheet implements WithHeadings, WithTitle, WithStyles
{
    public function headings(): array
    {
        return [
            'Perusahaan',             // A
            'Nama Aset',               // B
            'Nama Kategori',           // C
            'Nama Subkategori',        // D
            'Merek / Model',           // E
            'Nomor Seri (SN)',         // F
            'Nama Vendor / Supplier',  // G
            'No PO / Invoice',         // H
            'Tgl Pembelian (YYYY-MM-DD)', // I
            'Biaya Perolehan (Rp)',    // J
            'Nilai Residu (Rp)',       // K
            'Umur Manfaat (Tahun)',    // L
            'Nama Departemen',         // M
            'Lokasi Ruangan',          // N
            'Nama PIC',                // O
            'Kondisi',                 // P
            'Status Aset',             // Q
            'Tgl Perawatan Terakhir (YYYY-MM-DD)', // R
            'Frekuensi Perawatan (Hari)',          // S
            'Jumlah Stok',             // T
            'Satuan Unit',             // U
            'Mulai Digunakan (YYYY-MM-DD)',        // V
            'Deskripsi / Catatan',     // W
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => '00A8B5'], // Warna Mybolo Teal
                ],
            ],
        ];
    }

    public function title(): string
    {
        return '1. Input Data Aset';
    }
}
