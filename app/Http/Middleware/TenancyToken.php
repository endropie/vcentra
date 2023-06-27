<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenancyToken
{
    /**
     * Set this property if you want to customize the on-fail behavior.
     *
     * @var callable|null
     */
    public static $abortRequest;

    public function handle(Request $request, Closure $next)
    {
        /** @var \Endropie\LumenAuthToken\Guards\TokenGuard $auth */
        $auth = auth('token');

        /** @var \App\Models\User $user */
        $user = $auth->user();
        if (!$user) return abort(401);


        $tenantID = $auth->getTokenPayload()->tnn;
        if (!$tenantID) return abort(403);

        tenancy()->initialize($tenantID);

        return $next($request);
    }
}
