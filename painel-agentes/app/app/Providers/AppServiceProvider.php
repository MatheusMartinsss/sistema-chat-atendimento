<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;
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
        Http::macro('api', function () {
            $token = session('token'); 
            return Http::baseUrl(config('services.api.url')) // coloque sua URL da API no config/services.php
                ->acceptJson()
                ->asJson()
                ->when($token, fn($http) => $http->withToken($token));
        });
    }
}
