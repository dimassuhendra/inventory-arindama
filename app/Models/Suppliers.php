<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suppliers extends Model
{
    use HasFactory;

    // Nama tabel secara default adalah suppliers (jamak), 
    // jadi kita tidak perlu mendefinisikannya secara manual kecuali namanya berbeda.

    protected $fillable = [
        'name',
        'telp',
        'address',
    ];

    /**
     * Relasi ke Model StockEntries
     * Satu supplier bisa mengirim barang berkali-kali (Barang Masuk)
     */
    public function stockEntries()
    {
        return $this->hasMany(StockEntries::class, 'supplier_id');
    }
}