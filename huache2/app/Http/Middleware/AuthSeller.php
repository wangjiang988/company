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

class AuthSeller {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!app('App\Com\Hwache\User\User')->checkDealerStatus()) {
        	// 会员没有登陆，跳转登陆
        	session()->reflash();
            $request->session()->put('redirect', urlencode($request->url()));
        	return redirect()->route('dealer.login', ['redirect' => urlencode($request->url())]);
        }
        
        return $next($request);
    }

}
