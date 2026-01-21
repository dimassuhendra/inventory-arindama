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
        Schema::create('cart_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique(); // Contoh: REQ-2023-001
            $table->foreignId('user_id')->constrained();
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
            $table->text('purpose')->nullable(); // Keperluan peminjaman/permintaan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_requests');
    }
};
