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
 * Class HasRole
 * @package Milebits\Authorizer\Http\Middleware
 */
class HasRole
{
    /**
     * Handles an incoming request
     *
     * @param Request $request
     * @param Closure $next
     * @param string $role
     * @return RedirectResponse|Response
     */
    public function handle(Request $request, Closure $next, string $role): Response|RedirectResponse
    {
        if (hasTrait($this->user(), Authorizer::class))
            if (!$this->user()->hasRole($role))
                return back();
        return $next($request);
    }

    /**
     * @return Model|Authorizer|Authenticatable|null
     */
    public function user(): Model|Authenticatable|Authorizer|null
    {
        return Auth::user();
    }
}
