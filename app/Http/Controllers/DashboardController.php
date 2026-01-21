<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Suppliers;
use App\Models\Categories;
use App\Models\Carts;
use App\Models\StockEntries;
use App\Models\StockExits;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_products' => Products::count(),
            'total_suppliers' => Suppliers::count(),
            'total_categories' => Categories::count(),
            'low_stock' => Products::where('quantity', '<=', 5)->count(),
            'pending_carts' => Carts::count(),
            'recent_entries' => StockEntries::with('product')->latest()->take(5)->get(),
            'recent_exits' => StockExits::with('product')->latest()->take(5)->get(),
        ];

        return view('dashboard', $data);
    }
}