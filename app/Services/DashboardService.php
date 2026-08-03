<?php

namespace App\Services;

use App\Models\Carts;
use App\Models\Category;
use App\Models\Products;
use App\Models\Suppliers;
use App\Models\Production;
use App\Models\StockExits;
use App\Models\StockEntries;
use App\Models\CartRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getDashboardData(): array
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->month;
        $thisYear = Carbon::now()->year;

        // 1. Stat Cards Calculations
        $totalProducts = Products::count();
        $lowStockCount = Products::where('quantity', '<=', 5)->count();

        // Stock Health Rate %
        $stockHealthRate = $totalProducts > 0
            ? round((($totalProducts - $lowStockCount) / $totalProducts) * 100)
            : 100;

        // Monthly Movement (Total In vs Total Out Month)
        $monthlyEntriesQty = StockEntries::whereMonth('entry_date', $thisMonth)
            ->whereYear('entry_date', $thisYear)
            ->sum('quantity');

        $monthlyExitsQty = StockExits::whereMonth('exit_date', $thisMonth)
            ->whereYear('exit_date', $thisYear)
            ->sum('quantity');

        // Overdue Loans Count
        $overdueLoansCount = Production::where('status', 'borrowed')
            ->whereDate('return_date', '<', $today)
            ->count();

        // Pending Cart Requests
        $pendingCartRequestsCount = CartRequest::where('status', 'pending')->count();

        // 2. Chart 1 Data: Line Chart Arus Barang (6 Bulan Terakhir)
        $monthlyFlowData = $this->getMonthlyFlowChartData();

        // 3. Chart 2 Data: Donut Chart Distribusi Kategori Top 5
        $categoryDistributionData = $this->getCategoryDistributionChartData();

        // 4. Chart 3 Data: Bar Chart Top 5 Fast Moving Products
        $fastMovingProductsData = $this->getFastMovingProductsData();

        return [
            // Core Stat Cards
            'total_products' => $totalProducts,
            'total_suppliers' => Suppliers::count(),
            'total_categories' => Category::count(),
            'low_stock' => $lowStockCount,
            'total_borrowed_units' => Production::where('status', 'borrowed')->sum('quantity'),
            'total_borrowed_types' => Production::where('status', 'borrowed')->distinct('product_id')->count('product_id'),

            // New Analisys Cards Data
            'stock_health_rate' => $stockHealthRate,
            'monthly_entries_qty' => $monthlyEntriesQty,
            'monthly_exits_qty' => $monthlyExitsQty,
            'overdue_loans_count' => $overdueLoansCount,
            'pending_cart_requests' => $pendingCartRequestsCount,

            // Lists
            'upcomingReturns' => Production::with('product')
                ->where('status', 'borrowed')
                ->whereDate('return_date', Carbon::tomorrow())
                ->get(),
            'recent_entries' => StockEntries::with('product')->latest()->take(5)->get(),
            'recent_exits' => StockExits::with('product')->latest()->take(5)->get(),

            // Charts
            'monthlyFlowChart' => $monthlyFlowData,
            'categoryDistributionChart' => $categoryDistributionData,
            'fastMovingProductsChart' => $fastMovingProductsData,
        ];
    }

    private function getMonthlyFlowChartData(): array
    {
        $months = [];
        $entries = [];
        $exits = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->translatedFormat('M Y');

            $entryTotal = StockEntries::whereMonth('entry_date', $date->month)
                ->whereYear('entry_date', $date->year)
                ->sum('quantity');

            $exitTotal = StockExits::whereMonth('exit_date', $date->month)
                ->whereYear('exit_date', $date->year)
                ->sum('quantity');

            $months[] = $monthName;
            $entries[] = (float) $entryTotal;
            $exits[] = (float) $exitTotal;
        }

        return [
            'categories' => $months,
            'entries' => $entries,
            'exits' => $exits,
        ];
    }

    private function getCategoryDistributionChartData(): array
    {
        $categories = Category::withCount('products')
            ->orderByDesc('products_count')
            ->take(5)
            ->get();

        return [
            'labels' => $categories->pluck('name')->toArray(),
            'series' => $categories->pluck('products_count')->toArray(),
        ];
    }

    private function getFastMovingProductsData(): array
    {
        $topExits = StockExits::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product')
            ->take(5)
            ->get();

        $names = [];
        $totals = [];

        foreach ($topExits as $item) {
            $names[] = $item->product->name ?? 'Unknown';
            $totals[] = (float) $item->total_qty;
        }

        return [
            'labels' => $names,
            'series' => $totals,
        ];
    }
}
