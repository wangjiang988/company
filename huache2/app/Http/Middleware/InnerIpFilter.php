<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InnerIpFilter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!$this->_is_inner_ip($request))
        {
            return json([
                "status"  => false,
                'code'    => 403,
                'msg'     => '非内网访问权限',
                'data'    => [],
            ]);
        }

        return $next($request);
    }


    /**
     * 检查是否内网IP
     */
    private function _is_inner_ip(Request $request)
    {
        $ip     = $request->server('REMOTE_ADDR');
        //验证ip是否是内网ip，如果是的话返回false，否则返回ip
        $result = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
        if($result ===  false)  return true;
        return false;
    }
}
