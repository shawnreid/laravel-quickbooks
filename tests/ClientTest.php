<?php

namespace Shawnreid\LaravelQuickbooks\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2AccessToken;
use QuickBooksOnline\API\DataService\DataService;
use Shawnreid\LaravelQuickbooks\QuickbooksAction;
use Shawnreid\LaravelQuickbooks\QuickbooksClient;
use Shawnreid\LaravelQuickbooks\Models\TestUserModel;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
*/
class ClientTest extends TestCase
{
    use RefreshDatabase;

    private QuickbooksClient  $client;
    private OAuth2AccessToken $accessToken;
    private TestUserModel     $noUserToken;
    private TestUserModel     $hasUserToken;

    public function setUp(): void
    {
        parent::setup();

        $this->client       = new QuickbooksClient();
        $this->noUserToken  = TestUserModel::factory()->create();
        $this->hasUserToken = TestUserModel::factory()->hasQuickbooksToken()->create();
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

    public function test_get_data_service_updates_database_record_and_returns_object(): void
    {
        $user = $this->hasUserToken;

        $externalMock = $this->mock('overload:QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper');

        $externalMock->shouldReceive('refreshToken')
            ->once()
            ->andReturn($this->createAccessToken(now()->addMinutes(1)));

        $result = $user->quickbooks();

        $this->assertNotEquals($user->quickbooksToken, $user->fresh()->quickbooksToken);
        $this->assertInstanceOf(QuickbooksAction::class, $result);
    }

    public function test_create_token_creates_database_record(): void
    {
        $user = $this->noUserToken;

        $this->assertNull($user->quickbooksToken);

        $externalMock = $this->mock('overload:QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper');

        $request = new Request([
            'code'    => 1,
            'realmId' => 1
        ]);

        session()->put('_quickbooksId', $user->id);

        $externalMock->shouldReceive('exchangeAuthorizationCodeForToken')
            ->once()
            ->andReturn($this->createAccessToken(now()->addMinutes(1)));

        $this->client->createToken($request);

        $this->assertNotNull($user->fresh()->quickbooksToken());
    }

    public function test_error_thrown_if_no_quickbooks_token_exists(): void
    {
        $user = $this->noUserToken;

        $this->expectError(\Error::class);

        $this->client->getDataService($user);
    }
}
