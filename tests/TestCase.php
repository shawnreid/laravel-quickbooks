<?php

namespace Shawnreid\LaravelQuickbooks\Tests;

use Carbon\Carbon;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2AccessToken;
use Shawnreid\LaravelQuickbooks\Models\TestUserModel;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function defineRoutes($router)
    {
        $router->get('login', function () {
            return true;
        })->name('login');
    }

    protected function getPackageProviders($app): array
    {
        return [
            \Shawnreid\LaravelQuickbooks\Providers\QuickbooksProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        require_once(__DIR__ . '/../database/migrations/create_quickbooks_tokens_table.php.stub');
        require_once(__DIR__ . '/../database/migrations/create_test_users_table.php.stub');

        (new \CreateQuickBooksTokensTable())->up();
        (new \CreateUsersTable())->up();

        $app['config']->set('laravel-quickbooks.connection', [
            'base_url'      => 'Development',
            'client_id'     => 'CLIENT_ID',
            'client_secret' => 'CLIENT_SECRET',
            'redirect_url'  => 'REDIRECT_URL',
        ]);

        $app['config']->set('laravel-quickbooks.relation.model', TestUserModel::class);
        $app['config']->set('laravel-quickbooks.logging.enabled', true);
        $app['config']->set('laravel-quickbooks.middleware.authenticated', 'auth');
    }

    public function createAccessToken(Carbon $now): OAuth2AccessToken
    {
        return new OAuth2AccessToken(1, 1, 1, 1, $now->timestamp, $now->addDays(7)->timestamp);
    }
}
