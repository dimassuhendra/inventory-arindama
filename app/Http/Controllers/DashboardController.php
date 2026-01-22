<?php
namespace App\Http\Controllers;

use App\Models\Carts;
use App\Models\Category;
use App\Models\Products;
use App\Models\Suppliers;
use App\Models\Production; // Pastikan model Production di-import
use App\Models\StockExits;
use App\Models\StockEntries;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_products' => Products::count(),
            'total_suppliers' => Suppliers::count(),
            'total_categories' => Category::count(),
            'low_stock' => Products::where('quantity', '<=', 5)->count(),
            'pending_carts' => Carts::count(),
            'total_borrowed_units' => Production::where('status', 'borrowed')->sum('quantity'),
            'total_borrowed_types' => Production::where('status', 'borrowed')->distinct('product_id')->count('product_id'),
            'upcomingReturns' => Production::with('product')
                ->where('status', 'borrowed')
                ->whereDate('return_date', Carbon::tomorrow())
                ->get(),

            'recent_entries' => StockEntries::with('product')->latest()->take(5)->get(),
            'recent_exits' => StockExits::with('product')->latest()->take(5)->get(),
        ];

        return view('dashboard', $data);
    }
}