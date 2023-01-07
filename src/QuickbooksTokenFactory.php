<?php

namespace Shawnreid\LaravelQuickbooks;

use Illuminate\Database\Eloquent\Factories\Factory;

class QuickbooksTokenFactory extends Factory
{
    protected $model = QuickbooksToken::class;

    public function definition()
    {
        return [
            'model_type'               => 'App\Models\User',
            'model_id'                 => 1,
            'realm_id'                 => 9999999999999999999,
            'access_token'             => 'secret',
            'refresh_token'            => 'secret',
            'access_token_expires_at'  => now()->addDays(7),
            'refresh_token_expires_at' => now()->addDays(14),
            'created_at'               => now(),
            'updated_at'               => now()
        ];
    }
}
