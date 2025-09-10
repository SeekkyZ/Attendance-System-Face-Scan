<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class TimezoneServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Set default timezone for PHP
        date_default_timezone_set('Asia/Bangkok');
        
        // Set Carbon locale to Thai (optional)
        Carbon::setLocale('th');
    }
}
