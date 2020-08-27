<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class LoggerMiddleWare
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
        $route = $request->route()->uri;
        $method = $request->method();
        $date = date('Y-m-d H:i:s');
        $name = $request->user->name;
        $email =  $request->user->email;
        
        return $next($request);
    }
}
