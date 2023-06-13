<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
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
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });

    Route::get('settings', function() {
        return \App\Models\Tenant\Setting::all();
    });

    Route::get('access', function(\Illuminate\Http\Request $request) {
        if ($token = $request->get('token'))
        {
            $userID = (string) Str::of(decrypt($token))->remove('auth:', '');
            auth()->loginUsingId($userID);
            return redirect('/info');
        }
        return abort(406);
    });

    Route::middleware(['authenancy'])->group(function() {
        Route::get('/info', function () {
            return 'INFO ['. tenant('id') .']';
        });
    });
});

