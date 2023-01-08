<?php

declare(strict_types=1);

namespace Shawnreid\LaravelQuickbooks;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Shawnreid\LaravelQuickbooks\Models\QuickbooksToken;

trait Quickbooks
{
    /**
     * Quickbooks relationship
     *
     * @return MorphOne
     */
    public function quickbooksToken(): MorphOne
    {
        return $this->morphOne(QuickbooksToken::class, 'model');
    }

    /**
     * Return Quickbooks data service and refresh token
     *
     * @return QuickbooksAction
     */
    public function quickbooks(): QuickbooksAction
    {
        return new QuickbooksAction(
            (new QuickbooksClient())->getDataService($this)
        );
    }
}
