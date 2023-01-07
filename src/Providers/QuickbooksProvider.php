<?php

declare(strict_types=1);

namespace Shawnreid\LaravelQuickbooks\Providers;

use Illuminate\Support\ServiceProvider;
use Shawnreid\LaravelQuickbooks\Quickbooks;

class QuickbooksProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'laravel-quickbooks');
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/config.php' => config_path('laravel-quickbooks.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../../resources/views' => resource_path('views/vendor/laravel-quickbooks'),
            ], 'views');

            if (! class_exists('CreateQuickBooksTokensTable')) {
                $this->publishes([
                  __DIR__ . '/../../database/migrations/create_quickbooks_tokens_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_quickbooks_tokens_table.php'),
                  // you can add any number of migrations here
                ], 'migrations');
            }
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../../config/config.php', 'laravel-quickbooks');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-quickbooks', function () {
            return new Quickbooks();
        });
    }
}
