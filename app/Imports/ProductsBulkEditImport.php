<?php

namespace App\Imports;

use App\Models\Products;
use App\Models\Category;
use App\Models\Suppliers;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsBulkEditImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Abaikan jika ID Produk kosong
            if (empty($row['id_produk_jangan_diubah'])) {
                continue;
            }

            $product = Products::find($row['id_produk_jangan_diubah']);

            if ($product) {
                // LOGIKA AUTO-CREATE KATEGORI
                $catName = trim($row['kategori']);
                $category = null;
                if (!empty($catName)) {
                    $category = Category::where('name', 'like', '%' . $catName . '%')->first();
                    if (!$category) {
                        $category = Category::create([
                            'name' => $catName,
                            'slug' => Str::slug($catName) . '-' . Str::random(5)
                        ]);
                    }
                }

                // LOGIKA AUTO-CREATE SUPPLIER
                $supplierId = null;
                if (!empty($row['supplier'])) {
                    $supName = trim($row['supplier']);
                    $supplier = Suppliers::where('name', 'like', '%' . $supName . '%')->first();
                    if (!$supplier) {
                        $supplier = Suppliers::create([
                            'name'    => $supName,
                            'telp'    => '-',
                            'address' => '-'
                        ]);
                    }
                    $supplierId = $supplier->id;
                }

                // UPDATE DATA PRODUK
                $product->update([
                    'name'        => $row['nama_produk'] ?? $product->name,
                    'category_id' => $category ? $category->id : $product->category_id,
                    'supplier_id' => $supplierId ?? $product->supplier_id,
                    'description' => $row['deskripsi'] ?? $product->description,
                    'unit'        => $row['satuan_unit'] ?? $product->unit,
                    'quantity'    => $row['stok'] ?? $product->quantity,
                ]);
            }
        }
    }
}
