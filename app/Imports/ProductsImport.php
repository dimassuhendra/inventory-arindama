<?php

namespace App\Imports;

use App\Models\Products;
use App\Models\Category;
use App\Models\Suppliers;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Skip jika baris nama_produk atau nama_kategori kosong
        if (empty($row['nama_produk']) || empty($row['nama_kategori'])) {
            return null;
        }

        // 1. CARI ATAU BUAT KATEGORI OTOMATIS
        $catName = trim($row['nama_kategori']);
        $category = Category::where('name', 'like', '%' . $catName . '%')->first();

        // Jika tidak ketemu, buat kategori baru
        if (!$category) {
            $category = Category::create([
                'name' => $catName,
                'slug' => Str::slug($catName) . '-' . Str::random(5),
            ]);
        }

        // 2. CARI ATAU BUAT SUPPLIER OTOMATIS
        $supplierId = null;
        if (!empty($row['nama_supplier'])) {
            $supName = trim($row['nama_supplier']);
            $supplier = Suppliers::where('name', 'like', '%' . $supName . '%')->first();

            // Jika tidak ketemu, buat supplier baru dengan data kontak default
            if (!$supplier) {
                $supplier = Suppliers::create([
                    'name' => $supName,
                    'telp' => '-', // Diisi strip karena di DB wajib ada
                    'address' => '-', // Diisi strip karena di DB wajib ada
                ]);
            }
            $supplierId = $supplier->id;
        }

        // 3. SIMPAN PRODUK
        return new Products([
            'name' => $row['nama_produk'],
            'slug' => Str::slug($row['nama_produk']) . '-' . Str::random(5),
            'category_id' => $category->id,
            'supplier_id' => $supplierId,
            'description' => $row['deskripsi'] ?? '-',
            'unit' => $row['satuan_unit'] ?? 'Pcs',
            'quantity' => $row['stok_awal'] ?? 0,
        ]);
    }
}
