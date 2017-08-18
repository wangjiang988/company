<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HcVoucherLog extends Model
{
    protected $table = 'hc_voucher_log';

    protected $fillable = ['vid','order_id','consume_money','balance','created_at'];

    public $timestamps = false;

    function belongsToVoucher()
    {
        return $this->belongsTo('App\Models\HcVoucher', 'vid', 'id');
    }
}
