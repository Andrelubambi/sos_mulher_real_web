<?php

namespace App\Providers;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Models\Grupo;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ChatService::class);
        $this->app->singleton(SosService::class);
        $this->app->singleton(GrupoService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
{
    if (env('APP_ENV') === 'production') {
        URL::forceScheme('https'); 
    }

    View::composer('*', function ($view) {
        $grupos = Grupo::all(); // pega todos os grupos
        $view->with('grupos', $grupos);
    });
}
}
