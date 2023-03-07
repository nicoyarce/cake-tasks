<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*Carbon::serializeUsing(function ($carbon) {
            return $carbon->format('Y-m-d');
        });*/
        Paginator::useBootstrap();
        if (Schema::hasTable('propiedades_grafico')) {
            config(['propiedades' => DB::select('select * from propiedades_grafico')]);
            View::share('propiedades', DB::select('select * from propiedades_grafico'));
        }
        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
