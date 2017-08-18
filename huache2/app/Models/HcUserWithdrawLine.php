<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HcUserWithdrawLine extends Model
{
    protected $table      = 'hc_user_withdraw_line';

    protected $primaryKey = 'uwl_id';

    protected $fillable   = ['user_id','line_type','account_code','account_name','bank_id'];

    protected $guarded    = ['uwl_id'];

    /*public function getBankIdAttribute($bank_id)
    {
        $bankAddr = UserBrank::BankAddress($bank_id);
        return '('.$bankAddr->province.$bankAddr->city.')'.$bankAddr->bank_address;
    }*/
    /**
     * 反向关联
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        //return $this->belongsToMany('App\User');
        return $this->belongsTo('App\User');
    }

    public function bank()
    {
        return $this->hasOne('App\Models\UserBrank','id','bank_id');
    }
}
