<?php namespace App\Http\Middleware;

use Closure;
//use Illuminate\Support\Facades\Auth;
class LoadPhpSelfSession
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
        //Auth::User();
        // 首页地区写入session中
        if (! session()->has('area') || ! session('area.area_id')) {
            $addr = GetIpLookup();
            if (!$addr['city']) {
                $addr = GetIpLookup();
                $city=$addr['city'];
            } else {
                $city=$addr['city'];
            }
            $area = \App\Models\Area::where('area_name','like','%'.$city.'%')->first()->toArray();
            session(['area' => $area]);
        }
        return $next($request);
    }

}
