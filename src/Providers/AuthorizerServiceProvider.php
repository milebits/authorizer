<?php

namespace Milebits\Authorizer\Providers;

use Illuminate\Support\ServiceProvider;

class AuthorizerServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->mergeConfigFrom(__DIR__ . '/../../config/authorizer.php', 'authoriser');
    }
}
