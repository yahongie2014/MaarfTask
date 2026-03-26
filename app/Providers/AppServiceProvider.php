<?php

namespace App\Providers;

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
        if (request()->has('lang')) {
            $lang = request()->get('lang');
            if (in_array($lang, ['en', 'ar'])) {
                session(['locale' => $lang]);
            }
        }
        
        app()->setLocale(session('locale', config('app.locale')));
    }
}
