<?php

namespace NathanHeffley\Analytics;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AnalyticsServiceProvider extends ServiceProvider
{
    public function boot(Router $router)
    {
        $router->aliasMiddleware(
            'pageview',
            'NathanHeffley\Analytics\Http\Middleware\Pageview'
        );

        $this->publishes([
            __DIR__ . '/../config/analytics.php' => config_path('analytics.php'),
        ]);

        Route::group([
            'namespace' => 'NathanHeffley\Analytics\Http\Controllers',
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'analytics');

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/analytics.php', 'analytics'
        );

        $this->app->bind('analytics', 'NathanHeffley\Analytics\Analytics');
    }
}
