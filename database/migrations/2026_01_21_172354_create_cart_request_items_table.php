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
        Schema::create('cart_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->double('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_request_items');
    }
};
