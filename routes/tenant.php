<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
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
    Route::middleware(['authenancy'])->get('/', function () {
        return view('tenance');
    });

    Route::get('settings', function() {
        return \App\Models\Tenant\Setting::all();
    });

    Route::get('access', function(\Illuminate\Http\Request $request) {
        if ($token = $request->get('token'))
        {
            $userID = (string) Str::of(decrypt($token))->remove('auth:', '');
            auth()->loginUsingId($userID);
            return redirect("/". $request->get('app') ."/#/");
        }
        return abort(406);
    });

    Route::middleware(['authenancy'])->group(function() {
        Route::get('/app-1', function () {
            return response((Http::accept('*/*')->get(env('HOST_APP1')))->body());
        });
        Route::get('/app-2', function () {
            return response((Http::accept('*/*')->get(env('HOST_APP2')))->body());
        });
        Route::get('/app-3', function () {
            return response((Http::accept('*/*')->get(env('HOST_APP3')))->body());
        });
    });
});

