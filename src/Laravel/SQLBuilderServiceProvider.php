<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder\Laravel;

use Illuminate\Support\ServiceProvider;
use SelcukMart\SQLBuilder\SQLBuilder;

class SQLBuilderServiceProvider extends ServiceProvider
{
    /**
     * Register services
     */
    public function register(): void
    {
        $this->app->singleton(SQLBuilder::class, function ($app) {
            return new SQLBuilder();
        });

        $this->app->alias(SQLBuilder::class, 'sqlbuilder');
    }

    /**
     * Bootstrap services
     */
    public function boot(): void
    {
        // Config publishing
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/config/sqlbuilder.php' => config_path('sqlbuilder.php'),
            ], 'sqlbuilder-config');
        }
    }

    /**
     * Get the services provided by the provider
     */
    public function provides(): array
    {
        return [SQLBuilder::class, 'sqlbuilder'];
    }
}
