<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(["auth"])->get('/check', function (Request $request) {

    $auth = auth('tokenance');
    $user = $auth->user();

    /** @var App\Extensions\AuthTenancy\Guards\TokenTenanceGuard $auth */
    /** @var Endropie\LumenAuthToken\Support\TokenUser $user */

    return response()->json([
        "user" => $user->toArray(),
        "tenance" => $auth->tenance(),
    ]);
});

Route::prefix('auth')->group(function () {
    Route::post('/login', 'App\Http\ApiControllers\AuthController@login');
    Route::post('/register', 'App\Http\ApiControllers\AuthController@register');
    Route::post('/register-login', 'App\Http\ApiControllers\AuthController@registerAndLogin');
    Route::middleware('auth:api')->get('/user', 'App\Http\ApiControllers\AuthController@user');
});
