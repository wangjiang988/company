<?php

namespace App\Listeners;

use App\Events\HcDailiAccountSavedEvent;
use App\Models\HcDailiAccount;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HcDailiAccountSavedListener
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
     * @param  HcDailiAccountSavedEvent  $event
     * @return void
     */
    public function handle(HcDailiAccountSavedEvent $event)
    {
        $hc_daili_account  = $event->hc_daili_account;
        if($hc_daili_account->isDirty(['credit_line','avaliable_deposit']))
        {
            $this->_check_account($hc_daili_account);
        }
    }

    private function _check_account(HcDailiAccount $hc_daili_account)
    {
        $old = $hc_daili_account->getOriginal();

        //余额小于0,但是保存前的余额大于0  记录到0时间
        if($old['avaliable_deposit'] > 0){
            $update = ['down_to_zero_time'=>Carbon::now()->toDateTimeString()];
            HcDailiAccount::where('d_id',$hc_daili_account->d_id)->update($update);
        }

        //condition 1
        $now_left_money                   =    (float) $hc_daili_account->avaliable_deposit;
        $credit_line                      =    (float) $hc_daili_account->credit_line;
        if($now_left_money+$credit_line <= 0)  //condition 1  //下架所有报价
            HcDailiAccount::suspend_all_baojia_by_account($hc_daili_account);
        else{
            //condition 2
            $down_to_zero_timestamp   =  strtotime($hc_daili_account->down_to_zero_time);
            $now_timestamp            =  time();
            if( $now_timestamp-$down_to_zero_timestamp >= 60*60**72)
            {   //下架所有报价
                HcDailiAccount::suspend_all_baojia_by_account($hc_daili_account);
            }
        }
    }
}
