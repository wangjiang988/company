<?php namespace App\Http\Middleware;

use Closure;

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
        /**
         * Start PHP self Session
         */
        ini_set('session.save_handler', 'redis');
        ini_set(
            'session.save_path',
            'tcp://'.config('database.redis.default.host')
                .':'.config('database.redis.default.port')
        );
        ini_set('session.cookie_domain', config('session.domain'));
        session_start();

        $prefix = config('session.prefix');
        if (!isset($_SESSION[$prefix])) {
            $_SESSION[$prefix] = '';
        }

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

        /**
         * require the mall config file
         */
        $_ENV['_CONF'] = require_once BP . 'lylaconfig.php';

        return $next($request);
    }

}
