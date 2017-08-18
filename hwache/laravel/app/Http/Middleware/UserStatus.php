<?php
/**
 * 检测商城系统会员登录状态中间件
 * 需要手动引用该中间件
 * $this->middleware('user.status');
 *
 * @author  李扬(Andy) <php360@qq.com>
 * @link    http://www.moqifei.com
 * @company 苏州华车网络科技有限公司 http://www.hwache.com
 */
namespace App\Http\Middleware;

use Closure;
use Request;

class UserStatus
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
        if (!app('App\Com\Hwache\User\User')->checkUserStatus()) {
            // 会员没有登陆，跳转登陆
            session()->reflash();
            return redirect()->route('user.login', ['redirect' => urlencode($request->url())]);
        }

        return $next($request);
    }

}
