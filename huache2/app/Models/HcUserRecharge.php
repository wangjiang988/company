<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HcUserRecharge extends Model
{
    protected $table = 'hc_user_recharge';

    protected $primaryKey = 'ur_id';

    protected $guarded = ['ur_id'];

    public function  consume()
    {
        return $this->hasOne(HcUserConsume::class,'ur_id','ur_id');
    }

    public function  line()
    {
        return $this->belongsTo(HcUserWithdrawLine::class,'uwl_id','uwl_id');
    }
    public function  user_bank()
    {
        return $this->hasOne(HcUserBank::class,'id','bank_id');
    }
}
