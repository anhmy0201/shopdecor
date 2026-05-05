<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.custom');
        $settings = [];
        if (Storage::disk('local')->exists('settings.json')) {
            $settings = json_decode(Storage::disk('local')->get('settings.json'), true) ?? [];
        }
        View::share('settings', $settings);
    }
}