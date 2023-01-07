<?php

namespace Shawnreid\LaravelQuickbooks\Tests\Unit;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2AccessToken;
use QuickBooksOnline\API\DataService\DataService;
use Shawnreid\LaravelQuickbooks\QuickbooksClient;
use Shawnreid\LaravelQuickbooks\Tests\TestCase;
use Shawnreid\LaravelQuickbooks\TestUserModel;

/**
    * @runTestsInSeparateProcesses
    * @preserveGlobalState disabled
*/
class ClientTest extends TestCase
{
    use RefreshDatabase;

    private QuickbooksClient  $client;
    private OAuth2AccessToken $accessToken;

    public function setUp(): void
    {
        parent::setup();

        $this->client = new QuickbooksClient();
    }

    public function createAccessToken(Carbon $now): OAuth2AccessToken
    {
        return new OAuth2AccessToken(1, 1, 1, 1, $now->timestamp, $now->addDays(7)->timestamp);
    }

    public function test_get_data_service_can_be_constructed(): void
    {
        $this->assertInstanceOf(DataService::class, $this->client->confgiureDataService());
    }

    public function test_authorization_url_is_returned(): void
    {
        $this->assertNotNull($this->client->getAuthorizationUrl());
    }

    public function test_parse_token_is_returned(): void
    {
        $parsedToken = $this->client->parseToken($this->createAccessToken(now()));

        $this->assertNotNull($parsedToken['access_token_expires_at']->timestamp);
    }

    public function test_refresh_token_updates_database(): void
    {
        $user = TestUserModel::factory()->hasQuickbooksToken()->create();

        $externalMock = $this->mock('overload:QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper');

        $externalMock->shouldReceive('refreshToken')
            ->once()
            ->andReturn($this->createAccessToken(now()->addMinutes(1)));

        $user->quickbooks();

        $this->assertNotEquals($user->quickbooksToken, $user->fresh()->quickbooksToken);
    }
}
