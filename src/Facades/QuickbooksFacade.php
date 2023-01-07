<?php

namespace Shawnreid\LaravelQuickbooks\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Shawnreid\LaravelQuickbooks\Skeleton\SkeletonClass
 */
class QuickbooksFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-quickbooks';
    }
}
