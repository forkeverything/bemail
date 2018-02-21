<?php

namespace App\Payments\Providers;

use App\Payments\Accountants\LaravelCashierAccountant;
use App\Payments\Contracts\Accountant;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(Accountant::class, LaravelCashierAccountant::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
