<?php

namespace Shawnreid\LaravelQuickbooks\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            \Shawnreid\LaravelQuickbooks\Providers\QuickbooksProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        include_once __DIR__ . '/../database/migrations/create_quickbooks_tokens_table.php.stub';
        include_once __DIR__ . '/../database/migrations/create_test_users_table.php.stub';

        (new \CreateQuickBooksTokensTable())->up();
        (new \CreateUsersTable())->up();

        $app['config']->set('laravel-quickbooks.connection', [
            'base_url'      => 'Development',
            'client_id'     => 'CLIENT_ID',
            'client_secret' => 'CLIENT_SECRET',
            'redirect_url'  => 'REDIRECT_URL',
        ]);
    }
}
