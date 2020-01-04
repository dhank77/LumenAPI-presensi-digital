<?php

namespace App\Http\Middleware;

use Closure;

class UserRolesMiddleware
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
        // Pre-Middleware Action

        if($request->input('status') != 1){
            return redirect('api/login');
        }

        $response = $next($request);

        // Post-Middleware Action

        return $response;
    }
}
