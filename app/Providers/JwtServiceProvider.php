<?php

namespace App\Providers;

use App\Services\AuthToken\AuthToken;
use App\Services\AuthToken\JwtTokenService;
use Illuminate\Support\ServiceProvider;

class JwtServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AuthToken::class, function ($app) {
            return new JwtTokenService();
        });
    }
}
