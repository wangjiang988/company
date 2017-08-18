<?php

namespace App\Http\Middleware;

use Closure;

use App\User;

class CheckUserBank
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
        //先判断用户实名认证
        $userInfo = User::getUserHomeInfo($request->user()->id);
        if(is_null($userInfo->is_id_verify) || $userInfo->is_id_verify!=1){
            if(!$request->ajax()){
                return redirect()->route('auth.showIdCart');exit;
            } else{
                return setJsonMsg (0,'用户没有实名认证不能添加银行卡');exit;
            }
        }
        //判断用户银行卡认证
        $userBank = User::findOrFail($request->user()->id)->UserBank()->where('is_verify',1)->first();
        if(is_null($userBank)){
            if(!$request->ajax()){
                return redirect()->route('user.bank');exit;
            } else{
                return setJsonMsg (0,'用户没有绑定银行卡银行卡');exit;
            }
        }
        return $next($request);
    }
}
