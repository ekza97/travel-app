<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Schema\Builder;
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
        Builder::defaultStringLength(191);
        Blade::directive('currency', function ($expression) {
        return "Rp. <?php echo number_format($expression, 0, ',', '.'); ?>";});
}
}