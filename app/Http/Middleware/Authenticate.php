<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class Authenticate extends Middleware
{
    use ApiResponser;

    protected function unauthenticated($request, array $guards)
    {
        abort(401, 'Unauthenticated');
    }
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
}
