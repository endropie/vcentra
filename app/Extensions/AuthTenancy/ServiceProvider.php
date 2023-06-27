<?php

namespace App\Extensions\AuthTenancy;

use App\Extensions\AuthTenancy\Guards\TokenTenanceGuard;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Endropie\LumenAuthToken\Guards\JWTGuard;
use Endropie\LumenAuthToken\Guards\TokenGuard;
use Endropie\LumenAuthToken\Providers\TokenProvider;
use Endropie\LumenAuthToken\Support\TokenUser;

class ServiceProvider extends BaseServiceProvider
{
	public function boot(): void
	{
		$this->extendAuthGuard();
	}

	public function register(): void
	{
        //
	}

	private function extendAuthGuard(): void
	{
		$this->app->auth->extend('jwt', function ($app, $name, array $config) {

			if ($config['provider'] == 'token') {
				return new TokenGuard(new TokenProvider(TokenUser::class));
			}

            /** @var Illuminate\Auth\CreatesUserProviders $auth */
            $auth = auth();
			return new JWTGuard($auth->createUserProvider($config['provider']));
		});

		$this->app->auth->extend('jwt-token', function () {
            return new TokenGuard(new TokenProvider(TokenUser::class));
		});

		$this->app->auth->extend('jwt-token-tenance', function () {
            return new TokenTenanceGuard(new TokenProvider(TokenUser::class));
		});
	}
}
