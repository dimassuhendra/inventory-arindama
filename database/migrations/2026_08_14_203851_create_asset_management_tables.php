<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. TABEL SUBKATEGORI (Terikat ke Categories)
        Schema::create('sub_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // 2. TABEL DEPARTEMEN (Master Standar)
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->default('General'); 
            $table->string('code')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 3. TABEL PICS / PENGGUNA ASET (Dapat dikelola per Role)
        Schema::create('pics', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->default('General'); 
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->string('nip')->nullable();
            $table->string('name');
            $table->string('position')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });

        // 4. PENYESUAIAN TABEL PRODUCTS (Ubah ke Sistem Aset)
        Schema::table('products', function (Blueprint $table) {
            // Relasi Subkategori, Departemen & PIC
            $table->foreignId('sub_category_id')->nullable()->after('category_id')->constrained('sub_categories')->onDelete('set null');
            $table->foreignId('department_id')->nullable()->after('supplier_id')->constrained('departments')->onDelete('set null');
            $table->foreignId('pic_id')->nullable()->after('department_id')->constrained('pics')->onDelete('set null');

            // Detail Aset & Identifikasi
            $table->enum('company_name', ['Perusahaan A', 'Perusahaan B', 'Perusahaan C', 'Perusahaan D', 'Perusahaan E', 'General'])->default('General')->after('id');
            $table->string('brand_model')->nullable()->after('name'); // Merek/Model
            $table->string('serial_number')->nullable()->after('brand_model'); // Nomor Seri (SN)
            $table->string('po_invoice_number')->nullable()->after('supplier_id'); // No. PO / Invoice
            $table->date('purchase_date')->nullable()->after('po_invoice_number'); // Tanggal Pembelian

            // Finansial & Depresiasi
            $table->decimal('purchase_cost', 15, 2)->default(0)->after('purchase_date'); // Biaya Perolehan
            $table->decimal('residual_value', 15, 2)->default(0)->after('purchase_cost'); // Nilai Residu
            $table->integer('useful_life_years')->default(0)->after('residual_value'); // Umur Manfaat (Tahun)

            // Penempatan, Pemeliharaan & Status
            $table->string('location')->nullable()->after('pic_id'); // Lokasi Spesifik
            $table->enum('condition', ['Sangat Baik', 'Baik', 'Rusak Ringan', 'Rusak Berat'])->default('Sangat Baik')->after('location'); // Kondisi
            $table->enum('asset_status', ['Aktif Digunakan', 'Tersimpan Gudang', 'Dalam Perawatan', 'Dipinjamkan', 'Dihentikan/Afkir'])->default('Tersimpan Gudang')->after('condition'); // Status Aset
            $table->date('last_maintenance_date')->nullable()->after('asset_status'); // Tgl Perawatan Terakhir
            $table->integer('maintenance_frequency_days')->nullable()->after('last_maintenance_date'); // Frekuensi Perawatan (Hari)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['sub_category_id']);
            $table->dropForeign(['department_id']);
            $table->dropForeign(['pic_id']);

            $table->dropColumn([
                'company_name',
                'sub_category_id',
                'brand_model',
                'serial_number',
                'po_invoice_number',
                'purchase_date',
                'purchase_cost',
                'residual_value',
                'useful_life_years',
                'department_id',
                'pic_id',
                'location',
                'condition',
                'asset_status',
                'last_maintenance_date',
                'maintenance_frequency_days',
            ]);
        });

        Schema::dropIfExists('pics');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('sub_categories');
    }
};
