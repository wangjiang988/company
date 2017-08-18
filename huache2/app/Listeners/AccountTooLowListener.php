<?php

namespace App\Listeners;

use App\Events\AccountTooLowEvent;
use App\Models\HcDailiAccount;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
class AccountTooLowListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AccountTooLowEvent  $event
     * @return void
     */
    public function handle(AccountTooLowEvent $event)
    {
        $daili_account     =  $event->daili_account;
        $old_daili_account =  $event->old_daili_account;
        Log::info('账户ID：'.$daili_account->d_id);
        switch ($event->action)
        {
            case  "too_low": //用户可用余额太少
                $this->_do_if_need_suspend_baojia($daili_account, $old_daili_account); //检查账号，查看是否需要下架所有产品
                break;
            default:;
        }
    }

    //检查账号，查看是否需要下架所有产品
    //1.账户余额小于0，且授信额度也降低到0  暂时下架所有报价
    //2.账户余额小于0,且授信额度》=0  ，但是小于零的状态已经超过72小时，暂时下架所有报价
    private function _do_if_need_suspend_baojia(HcDailiAccount $daili_account, HcDailiAccount $old_daili_account)
    {
        Log::info('修改前账户余额：'.$old_daili_account->avaliable_deposit);
        Log::info('修改后账户余额：'.$daili_account->avaliable_deposit);
        //余额大于0
        if($daili_account->avaliable_deposit  >0 ) return true;

        //余额小于0,但是保存前的余额大于0  记录到0时间
        if($old_daili_account->avaliable_deposit > 0) {
            $update = ['down_to_zero_time'=>Carbon::now()->toDateTimeString()];
            HcDailiAccount::where('d_id',$daili_account->d_id)->update($update);
        }

        //condition 1
        $now_left_money                   =    (float) $daili_account->avaliable_deposit;
        $credit_line                      =    (float) $daili_account->credit_line;
        if($now_left_money+$credit_line <= 0)  //condition 1  //下架所有报价
            HcDailiAccount::suspend_all_baojia_by_account($daili_account);
        else{
            //condition 2
            $down_to_zero_timestamp   =  strtotime($daili_account->down_to_zero_time);
            $now_timestamp            =  time();
            if( $now_timestamp-$down_to_zero_timestamp >= 60*60**72)
            {   //下架所有报价
                HcDailiAccount::suspend_all_baojia_by_account($daili_account);
            }
        }

    }
}
