<?php

namespace App\Http\Middleware;

use Closure;

class JobTime
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
        /**
         * 判断是否在工作时间内
         */
        if(!check_job_time()){
            return redirect(route('/'));
            exit;
        }
        return $next($request);
    }
}
