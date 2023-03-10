<?php

namespace Shawnreid\LaravelQuickbooks\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Shawnreid\LaravelQuickbooks\Models\QuickbooksToken;
use Shawnreid\LaravelQuickbooks\Models\TestUserModel;

class TokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_access_token_returns_true_if_date_greater_than_today(): void
    {
        $token = QuickbooksToken::factory()->create();

        $this->assertTrue($token->validAccessToken);
    }

    public function test_valid_access_token_returns_false_if_date_less_than_today(): void
    {
        $token = QuickbooksToken::factory()->create([
            'access_token_expires_at' => now()->subDays(1)
        ]);

        $this->assertFalse($token->validAccessToken);
    }

    public function test_valid_refresh_token_returns_true_if_date_greater_than_today(): void
    {
        $token = QuickbooksToken::factory()->create();

        $this->assertTrue($token->validRefreshToken);
    }

    public function test_valid_refresh_token_returns_false_if_date_less_than_today(): void
    {
        $token = QuickbooksToken::factory()->create([
            'refresh_token_expires_at' => now()->subDays(1)
        ]);

        $this->assertFalse($token->validRefreshToken);
    }

    public function test_parent_relationship_returns_valid_model(): void
    {
        $user = TestUserModel::factory()->hasQuickbooksToken()->create();

        $this->assertNotNull($user->quickbooksToken->parent);
    }
}
