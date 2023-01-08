<?php

declare(strict_types=1);

namespace Shawnreid\LaravelQuickbooks\Providers;

use Illuminate\Support\ServiceProvider;

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

            $this->publishes([
                __DIR__ . '/../../database/migrations/create_quickbooks_tokens_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_quickbooks_tokens_table.php'),
            ], 'migrations');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/config.php', 'laravel-quickbooks');
    }
}
