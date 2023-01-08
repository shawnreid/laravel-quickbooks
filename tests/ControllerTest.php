<?php

namespace Shawnreid\LaravelQuickbooks\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Shawnreid\LaravelQuickbooks\Models\TestUserModel;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
*/
class ControllerTest extends TestCase
{
    use RefreshDatabase;

    private TestUserModel $user;

    public function setUp(): void
    {
        parent::setup();

        $this->user = TestUserModel::factory()->hasQuickbooksToken()->create();
    }

    public function test_authenticated_user_can_access_index_page(): void
    {
        $this->actingAs($this->user)
            ->get(route('quickbooks.index'))
            ->assertOk();
    }

    public function test_guest_cannot_to_access_index_page(): void
    {
        $this->get(route('quickbooks.index'))
            ->assertRedirect('login');
    }

    public function test_authenticated_user_can_redirect_to_oauth_login(): void
    {
        $this->actingAs($this->user)
            ->post(route('quickbooks.store'), [
                'id' => 1
            ])->assertRedirectContains('intuit');
    }

    public function test_guest_cannot_redirect_to_oauth_login(): void
    {
        $this->post(route('quickbooks.store'), [
            'id' => 1
        ])->assertRedirect('login');
    }

    public function test_authenticated_user_can_refresh_token(): void
    {
        $externalMock = $this->mock('overload:QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper');

        $externalMock->shouldReceive('refreshToken')
            ->once()
            ->andReturn($this->createAccessToken(now()->addMinutes(1)));

        $this->actingAs($this->user)
            ->put(route('quickbooks.update', 1))
            ->assertRedirect(route('quickbooks.index'));
    }

    public function test_guest_cannot_refresh_token(): void
    {
        $this->put(route('quickbooks.update', 1))
            ->assertRedirect('login');
    }

    public function test_authenticated_user_can_delete_token(): void
    {
        $this->actingAs($this->user)
            ->delete(route('quickbooks.destroy', 1))
            ->assertRedirect(route('quickbooks.index'));
    }

    public function test_guest_cannot_delete_token(): void
    {
        $this->delete(route('quickbooks.destroy', 1))
            ->assertRedirect('login');
    }

    public function test_token_url_has_required_info_creates_token(): void
    {
        session()->put('_quickbooksId', $this->user->id);

        $externalMock = $this->mock('overload:QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper');

        $externalMock->shouldReceive('exchangeAuthorizationCodeForToken')
            ->once()
            ->andReturn($this->createAccessToken(now()->addMinutes(1)));

        $this->actingAs($this->user)
            ->get(route('quickbooks.token', [
                'code'    => 1,
                'realmId' => 1,
            ]))
            ->assertRedirect(route('quickbooks.index'));
    }

    public function test_token_url_missing_required_info_redirects(): void
    {
        $this->get(route('quickbooks.token'))
            ->assertRedirect('login');
    }
}
