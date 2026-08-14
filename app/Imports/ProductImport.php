<?php

namespace App\Imports;

use App\Models\Products;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Suppliers;
use App\Models\Department;
use App\Models\Pic;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            0 => new SingleProductSheetImport(), // Hanya membaca sheet pertama
        ];
    }
}

class SingleProductSheetImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Abaikan jika baris kosong / nama aset kosong
        if (empty($row['nama_aset'])) {
            return null;
        }

        // 1. Cek / Get Kategori & Subkategori
        $categoryName = trim($row['nama_kategori'] ?? '');
        $category = Category::where('name', 'like', "%{$categoryName}%")->first();
        if (!$category) {
            $category = Category::firstOrCreate(['name' => $categoryName ?: 'Umum']);
        }

        $subCategoryId = null;
        if (!empty($row['nama_subkategori'])) {
            $subCatName = Str::before(trim($row['nama_subkategori']), ' ('); // Bersihkan label parent
            $subCategory = SubCategory::where('name', 'like', "%{$subCatName}%")
                ->where('category_id', $category->id)->first();
            $subCategoryId = $subCategory ? $subCategory->id : null;
        }

        // 2. Cek / Get Supplier
        $supplierId = null;
        if (!empty($row['nama_vendor_supplier'])) {
            $supplier = Suppliers::firstOrCreate(['name' => trim($row['nama_vendor_supplier'])]);
            $supplierId = $supplier->id;
        }

        // 3. Cek / Get Department
        $departmentId = null;
        if (!empty($row['nama_departemen'])) {
            $deptName = Str::before(trim($row['nama_departemen']), ' (');
            $dept = Department::where('name', 'like', "%{$deptName}%")->first();
            $departmentId = $dept ? $dept->id : null;
        }

        // 4. Cek / Get PIC
        $picId = null;
        if (!empty($row['nama_pic'])) {
            $picName = Str::before(trim($row['nama_pic']), ' (');
            $pic = Pic::where('name', 'like', "%{$picName}%")->first();
            $picId = $pic ? $pic->id : null;
        }

        // Generate Slug Unik
        $slug = Str::slug($row['nama_aset']) . '-' . Str::random(5);

        return new Products([
            'company_name'                 => $row['perusahaan'] ?? 'General',
            'category_id'                  => $category->id,
            'sub_category_id'              => $subCategoryId,
            'name'                         => $row['nama_aset'],
            'slug'                         => $slug,
            'brand_model'                  => $row['merek_model'] ?? null,
            'serial_number'                => $row['nomor_seri_sn'] ?? null,
            'supplier_id'                  => $supplierId,
            'po_invoice_number'            => $row['no_po_invoice'] ?? null,
            'purchase_date'                => $this['parseDate']($row['tgl_pembelian_yyyy_mm_dd'] ?? null),
            'purchase_cost'                => (float) ($row['biaya_perolehan_rp'] ?? 0),
            'residual_value'               => (float) ($row['nilai_residu_rp'] ?? 0),
            'useful_life_years'            => (int) ($row['umur_manfaat_tahun'] ?? 0),
            'department_id'                => $departmentId,
            'location'                     => $row['lokasi_ruangan'] ?? null,
            'pic_id'                       => $picId,
            'condition'                    => $row['kondisi'] ?? 'Baik',
            'asset_status'                 => $row['status_aset'] ?? 'Tersimpan Gudang',
            'last_maintenance_date'        => $this['parseDate']($row['tgl_perawatan_terakhir_yyyy_mm_dd'] ?? null),
            'maintenance_frequency_days'   => (int) ($row['frekuensi_perawatan_hari'] ?? null),
            'quantity'                     => (float) ($row['jumlah_stok'] ?? 1),
            'unit'                         => strtoupper($row['satuan_unit'] ?? 'UNIT'),
            'first_used_at'                => $this['parseDate']($row['mulai_digunakan_yyyy_mm_dd'] ?? null),
            'description'                  => $row['deskripsi_catatan'] ?? null,
        ]);
    }

    private function parseDate($value)
    {
        if (empty($value)) return null;
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
