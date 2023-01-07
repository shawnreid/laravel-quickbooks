<?php

use Illuminate\Support\Facades\Route;
use Shawnreid\LaravelQuickbooks\Http\Controllers\TokenController;

Route::name('quickbooks.')
    ->prefix('quickbooks')
    ->middleware(config('laravel-quickbooks.route.middleware.default'))
    ->group(function () {
        Route::resource('/', TokenController::class, [
            'only' => ['index', 'store', 'destroy', 'update']
        ])->parameters(['' => 'token'])
          ->middleware(config('laravel-quickbooks.route.middleware.authenticated'));

        Route::get('token', [TokenController::class, 'callback'])->name('token');
    });
