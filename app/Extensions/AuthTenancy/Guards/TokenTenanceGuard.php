<?php

namespace App\Extensions\AuthTenancy\Guards;

use Endropie\LumenAuthToken\Guards\TokenGuard;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Authenticatable;
use Exception;

class TokenTenanceGuard extends TokenGuard implements Guard
{
    protected $tenance;

    public function check()
    {
        if (is_null($this->tenance())) return false;

        return ! is_null($this->user());
    }

    protected function hasTenance()
    {
        return ! is_null($this->tenance);
    }

    public function tenance()
    {
		if ($this->hasTenance() && !app()->runningUnitTests()) {
			return $this->tenance;
		}

		try {
			$decoded = $this->getTokenPayload();
		} catch (Exception $e) {
			abort(401, $e->getMessage());
		}

		if (empty($decoded->tnn)) {
			return null;
		}

		$this->tenance = $decoded->tnn;

		return $this->tenance;
    }

	public function attempt(array $credentials = []): ?Authenticatable
	{
		$provider = $this->getProvider();

		$this->user = $provider->retrieveByCredentials($credentials);
		$this->user = $this->user && $provider->validateCredentials($this->user, $credentials) ? $this->user : null;

		return $this->user;
	}
}
