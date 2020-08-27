<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class AuthMiddleWare
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
        $accessToken = $request->input('accessToken');
        if (empty($accessToken)) {
            return response(['error' => ['message' => 'Incorrect token']], 403);
        }

        $user = User::where('api_token', $accessToken)->first();
        if (is_null($user)) {
            return response(['error' => ['message' => 'User not found. Check token']], 403);
        }

        $request->user = $user;

        return $next($request);
    }
}
