<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; // Import Schema


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191); // Add this line
    }
}
