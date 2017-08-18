<?php

namespace App\Http\Middleware;

use Closure;

use App\Repositories\Contracts\HcDailiAccountRepositoryInterface;

class CheckDailiAccount
{

    function __construct(HcDailiAccountRepositoryInterface $account)
    {
        $this->account = $account;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //查询账户信息
        $d_id = $request->segment(4);
        $money = $request->segment(5);
        $daili_account = $this->account->firstOrCreate(['d_id' => $d_id]);

        //todo 临时保证用户账户中的钱足够支付买车款
        $daili_account->avaliable_deposit = 200000;

        if ($daili_account->avaliable_deposit < $money) {
            //todo 账户余额不足，跳转到充值页面
            redirect()->route('daili.pay');
        }

        return $next($request);
    }
}
