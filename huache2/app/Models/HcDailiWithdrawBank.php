<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HcDailiWithdrawBank extends Model
{
    protected $table = 'hc_daili_withdraw_bank';

    protected $primaryKey = 'dwb_id';

    protected $guarded = ['dwb_id'];
}
