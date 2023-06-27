<?php

declare(strict_types=1);

use App\Http\Middleware\InitializeTenancyByDomain;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
])->group(function () {

    Route:: middleware('tenance.auth')->get('/tenance_token', function (\Illuminate\Http\Request $request) {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $token = $user->createTokenTenancy(tenant('id'));
        return [
            "type" => "Bearer",
            "token" => $token,
        ];
    });

    Route::middleware(['tenance.auth'])->group(function() {
        Route::get('/app-1', function () {
            return response((Http::accept('*/*')->get(env('HOST_APP1')))->body());
        });
        Route::get('/app-2', function () {
            return response((Http::accept('*/*')->get(env('HOST_APP2')))->body());
        });
        Route::get('/app-3', function () {
            return response((Http::accept('*/*')->get(env('HOST_APP3')))->body());
        });
        Route::get('/app-4', function () {
            return response((Http::accept('*/*')->get(env('HOST_APP4')))->body());
        });
    });
});
