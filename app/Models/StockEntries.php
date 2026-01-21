<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockEntries extends Model
{
    use HasFactory;

    protected $table = 'stock_entries';

    protected $fillable = [
        'product_id',
        'supplier_id',
        'user_id',
        'quantity',
        'entry_date'
    ];

    protected $casts = [
        'entry_date' => 'date',
        'quantity' => 'double',
    ];

    // Relasi ke Produk
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    // Relasi ke Supplier (PENTING: Agar tidak error saat memanggil $entry->supplier->name)
    public function supplier()
    {
        return $this->belongsTo(Suppliers::class, 'supplier_id');
    }

    // Relasi ke User/Petugas (PENTING: Agar tidak error saat memanggil $entry->user->name)
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
}