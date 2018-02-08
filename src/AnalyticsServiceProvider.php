<?php

namespace NathanHeffley\Analytics;

use Illuminate\Support\ServiceProvider;

class AnalyticsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/analytics.php' => config_path('analytics.php'),
        ]);

        $this->loadMigrationsFrom(__DIR__.'/migrations');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/analytics.php', 'analytics'
        );

        $this->app->bind(Pageview::class, 'Pageview');
    }
}