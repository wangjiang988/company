<?php
/**
 * 判断用户是否实名认证中间键
 */
namespace App\Http\Middleware;

use Closure;
use App\User;

class IsIdCart
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
        $userInfo = User::getUserHomeInfo($request->user()->id);
        if(is_null($userInfo->is_id_verify) || $userInfo->is_id_verify!=1){
            if(!$request->ajax()){
                return redirect()->route('auth.showIdCart');exit;
            } else{
                return setJsonMsg (0,'用户没有实名认证不能添加银行卡');exit;
            }
        }
        return $next($request);
    }
}
