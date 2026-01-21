<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siapa yang melakukannya
            $table->string('activity'); // Deskripsi singkat: "Update Stok", "Menghapus User", "Login"
            $table->string('model_type')->nullable(); // Nama model yang diubah (misal: App\Models\Product)
            $table->unsignedBigInteger('model_id')->nullable(); // ID data yang diubah
            $table->json('properties')->nullable(); // Data lama vs data baru (untuk melihat perubahan detail)
            $table->string('ip_address', 45)->nullable(); // Melacak lokasi akses
            $table->string('user_agent')->nullable(); // Melacak perangkat yang digunakan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
