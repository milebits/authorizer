<?php

namespace Milebits\Authorizer\Providers;

use Illuminate\Support\ServiceProvider;
use Milebits\Authorizer\Console\Commands\InstallPermissions;

class AuthorizerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->mergeConfigFrom(__DIR__ . '/../../config/authorizer.php', 'authoriser');
        if (!$this->app->runningInConsole()) return;
        $this->commands([InstallPermissions::class]);
    }
}
