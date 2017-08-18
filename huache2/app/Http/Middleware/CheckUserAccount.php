<?php

namespace App\Http\Middleware;

use App\Repositories\Contracts\HcDailiAccountRepositoryInterface;
use Closure;

use App\Repositories\Contracts\HcUserAccountRepositoryInterface;
use App\Models\HgOrder;

class CheckUserAccount
{

    function __construct(HcUserAccountRepositoryInterface $user_account, HcDailiAccountRepositoryInterface $daili_account)
    {
        $this->user_account = $user_account;
        $this->daili_account = $daili_account;
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
        $user_id = $request->user()->id;
        $order_id = $request->id;

        $order = HgOrder::find($order_id);
        if (empty($order)) {
            abort('404', '订单不存在！');
        }

        $order_state = $order->order_state;
        $user_account = $this->user_account->firstOrCreate(['user_id' => $user_id]);
        $daili_account = $this->daili_account->firstOrCreate(['d_id' => $order->seller_id]);

        //todo 临时保证用户账户中的钱足够支付买车款
        $user_account->avaliable_deposit = 200000;

        //支付诚意金检查
        if ($order_state == 200) {
            //用户账户余额不足，跳转到充值页面
            if ($user_account->avaliable_deposit < $order->earnest_price) {
                redirect()->route('user.pay');
            }

            //经销商账户余额不足，跳转到网站首页
            if ($daili_account->credit_line < $order->earnest_price || $daili_account->avaliable_deposit < $order->earnest_price) {
                redirect()->route('/');
            }
        }

        //支付付担保金检查
        if ($order_state == 203 && $user_account->avaliable_deposit < $order->earnest_price) {
            //账户余额不足，跳转到充值页面
            redirect()->route('user.pay');
        }

        return $next($request);
    }
}
