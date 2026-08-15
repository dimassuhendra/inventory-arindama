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
        // 1. Buat Tabel Master Companies (jika belum ada di database)
        if (!Schema::hasTable('companies')) {
            Schema::create('companies', function (Blueprint $table) {
                $table->id();
                $table->string('code')->nullable()->unique();
                $table->string('name')->unique();
                $table->timestamps();
            });
        }

        // 2. Modifikasi Tabel Departments (Menghapus kolom company_name)
        Schema::table('departments', function (Blueprint $table) {
            if (Schema::hasColumn('departments', 'company_name')) {
                $table->dropColumn('company_name');
            }

            // Memastikan nama departemen unik secara global
            $table->string('name')->unique()->change();
        });

        // 3. Buat Tabel Pivot company_department
        if (!Schema::hasTable('company_department')) {
            Schema::create('company_department', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
                $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
                $table->timestamps();

                // Mencegah duplikasi jembatan relasi
                $table->unique(['company_id', 'department_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_department');

        Schema::table('departments', function (Blueprint $table) {
            $table->string('company_name')->default('General')->after('id');
            $table->dropUnique(['name']);
        });

        Schema::dropIfExists('companies');
    }
};
