<?php

namespace Shawnreid\LaravelQuickbooks\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Shawnreid\LaravelQuickbooks\Factories\TestUserModelFactory;
use Shawnreid\LaravelQuickbooks\Quickbooks;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TestUserModel extends Authenticatable
{
    use HasFactory;
    use Quickbooks;

    public $table = 'test_users';

    /**
     * Return factory
     *
     * @return TestUserModelFactory
     */
    protected static function newFactory(): TestUserModelFactory
    {
        return TestUserModelFactory::new();
    }
}
