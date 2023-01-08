<?php

declare(strict_types=1);

namespace Shawnreid\LaravelQuickbooks\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Shawnreid\LaravelQuickbooks\QuickbooksClient;
use Shawnreid\LaravelQuickbooks\Models\QuickbooksToken;
use Illuminate\Contracts\View\View;

class TokenController extends Controller
{
    /**
     * List connections
     *
     * @return View
     */
    public function index(QuickbooksToken $token): View
    {
        $model = config('laravel-quickbooks.relation.model');

        return view('laravel-quickbooks::tokens', [
            'models' => (new $model())->pluck(
                config('laravel-quickbooks.relation.value'),
                config('laravel-quickbooks.relation.key')
            ),
            'tokens' => $token->select([
                'id',
                'model_type',
                'model_id',
                'realm_id',
                'access_token_expires_at',
                'refresh_token_expires_at',
                'created_at',
            ])->get(),
        ]);
    }

    /**
     * Create new connection
     *
     * @param  Request  $request
     * @param  QuickbooksClient  $quickbooks
     * @return RedirectResponse
     */
    public function store(Request $request, QuickbooksClient $quickbooks): RedirectResponse
    {
        $request->session()->put('_quickbooksId', $request->id);

        return redirect($quickbooks->getAuthorizationUrl());
    }

    /**
     * Refresh token
     *
     * @param  QuickbooksToken  $token
     * @return RedirectResponse
     */
    public function update(QuickbooksToken $token): RedirectResponse
    {
        $token->parent->quickbooks();

        return redirect(route('quickbooks.index'));
    }

    /**
     * Destroy token
     *
     * @param  QuickbooksToken  $token
     * @return RedirectResponse
     */
    public function destroy(QuickbooksToken $token): RedirectResponse
    {
        $token->delete();

        return redirect(route('quickbooks.index'));
    }

    /**
     * Quickbooks callback
     *
     * @param  Request  $request
     * @param  QuickbooksClient  $quickbooks
     * @return RedirectResponse
     */
    public function callback(Request $request, QuickbooksClient $quickbooks): RedirectResponse
    {
        if (!$request->code || !$request->realmId) {
            return redirect('login');
        }

        $quickbooks->createToken($request);

        return redirect(route('quickbooks.index'));
    }
}
