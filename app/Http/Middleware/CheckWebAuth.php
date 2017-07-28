<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Web\AuthController;
use Closure;

class CheckWebAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!AuthController::check($request))
            return redirect('/api/signin');

        return $next($request);
    }
}
