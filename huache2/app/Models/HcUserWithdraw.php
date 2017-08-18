<?php
/**
 * 支付宝提现记录
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HcUserWithdraw extends Model
{
    protected $table = 'hc_user_withdraw';

    protected $primaryKey = 'uw_id';

    protected $guarded = ['uw_id'];

    public function recharge()
    {
        return $this->belongsTo(HcUserRecharge::class, 'ur_id', 'ur_id');
    }

    public function user_log()
    {
        return $this->belongsTo(HcUserAccountLog::class, 'ulog_id', 'ua_log_id');
    }

    public function line()
    {
        return $this->belongsTo(HcUserWithdrawLine::class, 'line_id', 'uwl_id');
    }
}
