<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorizationTenancy
{
    /**
     * Set this property if you want to customize the on-fail behavior.
     *
     * @var callable|null
     */
    public static $abortRequest;

    public function handle(Request $request, Closure $next)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) return abort(401);

        if ($tenantID = tenant('id')) {
            if ($user->isTenantAllowed($tenantID)) return $next($request);
            return abort(403);
        }

        return $next($request);
    }
}
