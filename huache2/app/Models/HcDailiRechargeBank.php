<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HcDailiRechargeBank extends Model
{
    protected $table = 'hc_daili_recharge_bank';

    protected $primaryKey = 'drb_id';

    protected $guarded = ['drb_id'];
}
