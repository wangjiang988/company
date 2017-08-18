<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HcUserAccountLog extends Model
{
    protected $table = 'hc_user_account_log';

    protected $primaryKey = 'ua_log_id';

    //protected $fillable = ['ua_log_id'];

    protected $guarded = ['ua_log_id'];

    public static function getChargeLimitOnline($user_id)
    {
        //查找最后一次支付诚意金的记录
        $pay_deposit_latest_log = HcUserAccountLog::where(['user_id' => $user_id, 'type' => 3, 'pay_type' => 0])->orderBy('ua_log_id', 'desc')->first();

        $charge_log_start = $pay_deposit_latest_log ? $pay_deposit_latest_log->created_at : "2017-01-01";
        $charge_logs_sum = HcUserAccountLog::where(['user_id' => $user_id, 'type' => 1, 'pay_type' => 1])
            ->whereBetween('created_at', array($charge_log_start, date('Y-m-d H:i:s')))
            ->get()
            ->sum('money');
        return $charge_logs_sum ?: 0;
    }
}
