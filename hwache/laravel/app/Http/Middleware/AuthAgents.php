<?php namespace App\Http\Middleware;

/**
 * 检测代理登录状态中间件
 * 需要手动引用该中间件
 * $this->middleware('auth.agents');
 *
 * @author  Andy <php360@qq.com>
 * @link    http://www.hwache.com
 * @company 苏州华车网络科技有限公司
 */

use Closure, Request;
use Session;
class AuthAgents {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!check_login_agents()) {
            // 会员没有登陆，跳转登陆
            Session::reflash();
            return redirect($_ENV['_CONF']['config']['shop_site_url'].'/index.php?act=seller_login');
            exit;
        }

        return $next($request);
    }

}
