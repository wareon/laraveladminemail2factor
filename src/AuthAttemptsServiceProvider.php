<?php

namespace Wareon\LaravelAdminEmail2Factor;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Wareon\LaravelAdminEmail2Factor\Http\Middleware\AuthAdminEmailTwoFactor;

class AuthAttemptsServiceProvider extends ServiceProvider
{
    /**
     * @param AuthEmailTwoFactor $extension
     */
    public function boot(AuthEmailTwoFactor $extension, Router $router, Kernel $kernel)
    {
        if (!AuthEmailTwoFactor::boot()) {
            return;
        }

        // Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Disabled
        if (!AuthEmailTwoFactor::config('enable')) {
            return;
        }

        // Register middleware
        $router->aliasMiddleware('admin.auth.2fa.email', AuthAdminEmailTwoFactor::class);
        $router->pushMiddlewareToGroup('admin', 'admin.auth.2fa.email');

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, AuthEmailTwoFactor::$group);
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/wareon/laraveladminemail2factor')],
                AuthEmailTwoFactor::$group
            );
        }

        $this->app->booted(function () {
            AuthEmailTwoFactor::routes(__DIR__ . '/../routes/web.php');
        });
    }
}
