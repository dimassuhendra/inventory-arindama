<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Orders;
use App\Models\Products;
use App\Models\Users;
use App\Models\Suppliers;
use App\Models\StockExits;
use App\Models\Carts;
use App\Models\Production;
use App\Models\StockEntries;
use App\Observers\LogObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // DAFTARKAN OBSERVER DI SINI
        Products::observe(LogObserver::class);
        Orders::observe(LogObserver::class);
        Category::observe(LogObserver::class);
        Suppliers::observe(LogObserver::class);
        Users::observe(LogObserver::class);
        Carts::observe(LogObserver::class);
        Production::observe(LogObserver::class);
        StockExits::observe(LogObserver::class);
        StockEntries::observe(LogObserver::class);
    }
}