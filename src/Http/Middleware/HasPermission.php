<?php

namespace Milebits\Authorizer\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Milebits\Authorizer\Concerns\Authorizer;

class HasPermission
{
    /**
     * Handle an incoming request.
     *
     *
     * @param Request $request
     * @param Closure $next
     * @param string $permission
     * @return RedirectResponse|mixed
     */
    public function handle(Request $request, Closure $next, string $permission)
    {
        if ((Auth::user() instanceof Authorizer))
            if (!Auth::user()->hasPermission($permission))
                return back();
        return $next($request);
    }
}
