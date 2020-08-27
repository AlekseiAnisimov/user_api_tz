<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\Models\Log;

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
        $name = $request->user->name;
        $email =  $request->user->email;

        $log = new Log();
        $log->route = $route;
        $log->method = $method;
        $log->name = $name;
        $log->email = $email;
        $log->save();

        return $next($request);
    }
}
