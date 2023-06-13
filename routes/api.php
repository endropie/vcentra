<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('/login', 'App\Http\ApiControllers\AuthController@login');
    Route::post('/register', 'App\Http\ApiControllers\AuthController@register');
    Route::post('/register-login', 'App\Http\ApiControllers\AuthController@registerAndLogin');
    Route::middleware('auth:sanctum')->get('/user', 'App\Http\ApiControllers\AuthController@user');

});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/access-tenants', function () {
        return auth()->user()->access_tenants;
    });
});
