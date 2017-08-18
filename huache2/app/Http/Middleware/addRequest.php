<?php

namespace App\Http\Middleware;

use Closure;

class addRequest
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
        $request->merge(['ipAddress'=>getIpAddress()]);
        //视图间共享数据
        view()->share('ipAddress',getIpAddress());
        return $next($request);
    }
}
