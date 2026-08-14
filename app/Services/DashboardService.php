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
use App\Services\CategoryService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Helper internal: Ambil array ID kategori yang diizinkan untuk user saat ini
     */
    private function getAllowedCategoryIds(): array
    {
        return Category::all()->filter(function ($cat) {
            return CategoryService::canUserManage($cat);
        })->pluck('id')->toArray();
    }

    public function getDashboardData(): array
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->month;
        $thisYear = Carbon::now()->year;

        // ID Kategori yang diizinkan untuk role user saat ini
        $allowedCategoryIds = $this->getAllowedCategoryIds();

        // Query Dasar Produk yang Diizinkan
        $allowedProductsQuery = Products::whereIn('category_id', $allowedCategoryIds);

        // 1. Stat Cards Calculations (Filtered by Role Categories)
        $totalProducts = (clone $allowedProductsQuery)->count();
        $lowStockCount = (clone $allowedProductsQuery)->where('quantity', '<=', 5)->count();

        // Stock Health Rate %
        $stockHealthRate = $totalProducts > 0
            ? round((($totalProducts - $lowStockCount) / $totalProducts) * 100)
            : 100;

        // Monthly Movement (Filtered by Product Category)
        $monthlyEntriesQty = StockEntries::whereHas('product', function ($q) use ($allowedCategoryIds) {
            $q->whereIn('category_id', $allowedCategoryIds);
        })
            ->whereMonth('entry_date', $thisMonth)
            ->whereYear('entry_date', $thisYear)
            ->sum('quantity');

        $monthlyExitsQty = StockExits::whereHas('product', function ($q) use ($allowedCategoryIds) {
            $q->whereIn('category_id', $allowedCategoryIds);
        })
            ->whereMonth('exit_date', $thisMonth)
            ->whereYear('exit_date', $thisYear)
            ->sum('quantity');

        // Overdue Loans Count (Filtered by Product Category)
        $overdueLoansCount = Production::whereHas('product', function ($q) use ($allowedCategoryIds) {
            $q->whereIn('category_id', $allowedCategoryIds);
        })
            ->where('status', 'borrowed')
            ->whereDate('return_date', '<', $today)
            ->count();

        // Pending Cart Requests (Filtered by Product Category)
        $pendingCartRequestsCount = CartRequest::whereHas('items.product', function ($q) use ($allowedCategoryIds) {
            $q->whereIn('category_id', $allowedCategoryIds);
        })
            ->where('status', 'pending')
            ->count();

        // Total Borrowed Stats
        $totalBorrowedUnits = Production::whereHas('product', function ($q) use ($allowedCategoryIds) {
            $q->whereIn('category_id', $allowedCategoryIds);
        })
            ->where('status', 'borrowed')
            ->sum('quantity');

        $totalBorrowedTypes = Production::whereHas('product', function ($q) use ($allowedCategoryIds) {
            $q->whereIn('category_id', $allowedCategoryIds);
        })
            ->where('status', 'borrowed')
            ->distinct('product_id')
            ->count('product_id');

        // 2. Charts Data (Filtered)
        $monthlyFlowData = $this->getMonthlyFlowChartData($allowedCategoryIds);
        $categoryDistributionData = $this->getCategoryDistributionChartData($allowedCategoryIds);
        $fastMovingProductsData = $this->getFastMovingProductsData($allowedCategoryIds);

        return [
            // Core Stat Cards
            'total_products' => $totalProducts,
            'total_suppliers' => Suppliers::count(),
            'total_categories' => count($allowedCategoryIds),
            'low_stock' => $lowStockCount,
            'total_borrowed_units' => $totalBorrowedUnits,
            'total_borrowed_types' => $totalBorrowedTypes,

            // New Analysis Cards Data
            'stock_health_rate' => $stockHealthRate,
            'monthly_entries_qty' => $monthlyEntriesQty,
            'monthly_exits_qty' => $monthlyExitsQty,
            'overdue_loans_count' => $overdueLoansCount,
            'pending_cart_requests' => $pendingCartRequestsCount,

            // Lists (Filtered)
            'upcomingReturns' => Production::with('product')
                ->whereHas('product', function ($q) use ($allowedCategoryIds) {
                    $q->whereIn('category_id', $allowedCategoryIds);
                })
                ->where('status', 'borrowed')
                ->whereDate('return_date', Carbon::tomorrow())
                ->get(),

            'recent_entries' => StockEntries::with('product')
                ->whereHas('product', function ($q) use ($allowedCategoryIds) {
                    $q->whereIn('category_id', $allowedCategoryIds);
                })
                ->latest()
                ->take(5)
                ->get(),

            'recent_exits' => StockExits::with('product')
                ->whereHas('product', function ($q) use ($allowedCategoryIds) {
                    $q->whereIn('category_id', $allowedCategoryIds);
                })
                ->latest()
                ->take(5)
                ->get(),

            // Charts
            'monthlyFlowChart' => $monthlyFlowData,
            'categoryDistributionChart' => $categoryDistributionData,
            'fastMovingProductsChart' => $fastMovingProductsData,
        ];
    }

    private function getMonthlyFlowChartData(array $allowedCategoryIds): array
    {
        $months = [];
        $entries = [];
        $exits = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->translatedFormat('M Y');

            $entryTotal = StockEntries::whereHas('product', function ($q) use ($allowedCategoryIds) {
                $q->whereIn('category_id', $allowedCategoryIds);
            })
                ->whereMonth('entry_date', $date->month)
                ->whereYear('entry_date', $date->year)
                ->sum('quantity');

            $exitTotal = StockExits::whereHas('product', function ($q) use ($allowedCategoryIds) {
                $q->whereIn('category_id', $allowedCategoryIds);
            })
                ->whereMonth('exit_date', $date->month)
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

    private function getCategoryDistributionChartData(array $allowedCategoryIds): array
    {
        $categories = Category::whereIn('id', $allowedCategoryIds)
            ->withCount('products')
            ->orderByDesc('products_count')
            ->take(5)
            ->get();

        return [
            'labels' => $categories->pluck('name')->toArray(),
            'series' => $categories->pluck('products_count')->toArray(),
        ];
    }

    private function getFastMovingProductsData(array $allowedCategoryIds): array
    {
        $topExits = StockExits::whereHas('product', function ($q) use ($allowedCategoryIds) {
            $q->whereIn('category_id', $allowedCategoryIds);
        })
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'))
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
