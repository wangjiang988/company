<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HcVoucherGroup extends Model
{
    protected $table = 'hc_vouchers_group';

    public function hasManyVoucher()
    {
        return $this->hasMany('App\Models\HcVoucher', 'group_id', 'id');
    }
}
