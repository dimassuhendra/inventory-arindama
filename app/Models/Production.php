<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $fillable = [
        'product_id',
        'loan_code',
        'borrower_name',
        'borrower_contact',
        'quantity',
        'loan_date',
        'return_date',
        'actual_return_date',
        'status'
    ];

    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}