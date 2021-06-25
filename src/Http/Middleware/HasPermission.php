<?php

namespace Milebits\Authorizer\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Milebits\Authorizer\Concerns\Authorizer;
use function hasTrait;

/**
 * Class HasPermission
 * @package Milebits\Authorizer\Http\Middleware
 */
class HasPermission
{
    /**
     * Handle an incoming request.
     *
     *
     * @param Request $request
     * @param Closure $next
     * @param string $permission
     * @return Response|RedirectResponse|null
     */
    public function handle(Request $request, Closure $next, string $permission): Response|RedirectResponse|null
    {
        if (hasTrait($this->user(), Authorizer::class))
            if (!$this->user()->hasPermission($permission))
                return back();
        return $next($request);
    }

    /**
     * @return Model|Authorizer|Authenticatable|null
     */
    public function user(): Model|Authorizer|Authenticatable|null
    {
        return Auth::user();
    }
}
