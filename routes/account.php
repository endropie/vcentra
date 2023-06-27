<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::group(['domain' => config('system.domain_account')], function() {
    Route::get('/', function () {
        // return ("OK => ". json_encode(auth()->user()));
        return response((Http::accept('*/*')->get(config('system.apps.account.host')))->body());
        // return redirect()->route('dashboard');
    });

    Route::middleware('auth')->get('/account_token', function (\Illuminate\Http\Request $request) {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $token = $user->createToken();
        return [
            "type" => "Bearer",
            "token" => $token,
        ];
    });

    Route::get('/dashboard', function () {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $token = encrypt("auth:$user->id");
        $tenants = $user->access_tenants->map(fn($e) => array_merge($e->toArray(), ['redirect_link' => $e->domains->first()->getHostUrl() ?? null]));

        return Inertia::render('Dashboard', [
            "tenants" => $tenants,
            "token" => $token,
        ]);
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });


    require __DIR__.'/auth.php';
});

