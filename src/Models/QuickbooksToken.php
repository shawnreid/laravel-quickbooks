<?php

namespace Shawnreid\LaravelQuickbooks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Shawnreid\LaravelQuickbooks\Factories\QuickbooksTokenFactory;

/**
 * @property string $access_token_expires_at
 * @property string $refresh_token_expires_at
 */
class QuickbooksToken extends Model
{
    use HasFactory;

    /**
     * Return factory
     *
     * @return QuickbooksTokenFactory
     */
    protected static function newFactory(): QuickbooksTokenFactory
    {
        return QuickbooksTokenFactory::new();
    }

    /**
     * Table
     *
     * @var string
     */
    protected $table = 'quickbooks_tokens';

    /**
     * Fillable attributes
     *
     * @var array<string>
     */
    protected $fillable = [
        'realm_id',
        'access_token',
        'access_token_expires_at',
        'refresh_token',
        'refresh_token_expires_at',
    ];

    /**
     * Return parent model
     *
     * @return MorphTo
     */
    public function parent(): MorphTo
    {
        return $this->morphTo('model');
    }

    /**
     * Check if access token is still valid
     *
     * @return bool
     */
    public function getValidAccessTokenAttribute(): bool
    {
        return Carbon::now()->lt($this->access_token_expires_at);
    }

    /**
     * Check if refresh token is still valid
     *
     * @return bool
     */
    public function getValidRefreshTokenAttribute(): bool
    {
        return Carbon::now()->lt($this->refresh_token_expires_at);
    }
}
