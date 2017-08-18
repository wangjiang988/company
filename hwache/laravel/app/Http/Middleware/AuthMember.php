<?php namespace App\Http\Middleware;

/**
 * 检测商城系统会员登录状态中间件
 * 需要手动引用该中间件
 * $this->middleware('auth.member');
 *
 * @author  Andy <php360@qq.com>
 * @link    http://www.hwache.com
 * @company 苏州华车网络科技有限公司
 */

use Closure, Request;

class AuthMember {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!check_login() || session('user.user_type')!=1) {//非登录状态或者 不是经销商
            // 会员没有登陆，跳转登陆
            session()->reflash();
            session()->regenerate();
            return redirect(route('user.login'));
            exit;
        }

        return $next($request);
    }

}
