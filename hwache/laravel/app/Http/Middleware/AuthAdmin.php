<?php namespace App\Http\Middleware;

/**
 * 检测管理员登录状态中间件
 * 需要手动引用该中间件
 * $this->middleware('auth.admin');
 *
 * @author  Andy <php360@qq.com>
 * @link    http://www.hwache.com
 * @company 苏州华车网络科技有限公司
 */

use Closure, Request;
use Session;
class AuthAdmin {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!check_login_admin()) {
            // 管理没有登陆，跳转登陆
            Session::reflash();
            return redirect($_ENV['_CONF']['config']['admin_site_url'].'/index.php?act=login');
            exit;
        }

        return $next($request);
    }

}
