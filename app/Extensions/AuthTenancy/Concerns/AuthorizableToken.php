<?php

namespace App\Extensions\AuthTenancy\Concerns;

use Illuminate\Support\Carbon;
use Endropie\LumenAuthToken\Support\JWT;

trait AuthorizableToken
{
	public function getJwtId(): string
	{
		return $this->getKey();
	}

	public function getJwtValidFromTime(): ?Carbon
	{
		return null;
	}

	public function getJwtValidUntilTime(): ?Carbon
	{
		$expiration = config('jwt.expiration');

		return $expiration ? app(Carbon::class)->now()->addMinutes($expiration) : null;
	}

	public function getJwtCustomClaims(): array
	{
		return [];
	}

	public function createToken(array $config = []): string
	{
		return JWT::encodeToken($this, $config);
	}

	public function createTokenTenancy($tenant, array $config = []): string
	{
        $config['claims'] = array_merge($this->getJwtCustomClaims(), ["tnn" => $tenant]);
		return JWT::encodeToken($this, $config);
	}
}
