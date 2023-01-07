<?php

namespace Shawnreid\LaravelQuickbooks;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TestUserModel extends Model
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
