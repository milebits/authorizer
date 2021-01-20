<?php


namespace Milebits\Authorizer\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Milebits\Authorizer\Concerns\Authorizer;

class HasRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if ((Auth::user() instanceof Authorizer))
            if (!Auth::user()->hasRole($role))
                return back();
        return $next($request);
    }
}
