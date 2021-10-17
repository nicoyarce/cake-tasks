<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\PropiedadesGrafico;

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
        config(['propiedades' => PropiedadesGrafico::all()]);
        View::share('propiedades', PropiedadesGrafico::all());
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
