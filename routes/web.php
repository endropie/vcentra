<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

$assetFn = function ($name, $fileURL) {
    $url = config("system.apps.$name.host") ."/$fileURL";
    $response = Http::get($url);
    $file = explode('.', $fileURL);

    switch (end($file)) {
        case 'js': return response($response)->header('Content-Type', 'text/javascript');
        case 'css': return response($response)->header('Content-Type', 'text/css');
        default:
        if (in_array(end($file), ['ico', 'png', 'jpg', 'jpeg'])) {
            return response($response)->header('Content-Type', 'image/webp,image/avif,image/apng');
        }

    }
    return response($response);
};

Route::get('/vbuild/{name}/{fileURL}', $assetFn)->where('fileURL', '.*');

require __DIR__.'/account.php';
