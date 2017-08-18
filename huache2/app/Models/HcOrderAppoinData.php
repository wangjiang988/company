<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class HcOrderAppoinData extends Model
{
    public $table = 'hc_order_appoint_car';

    public $primaryKey = 'order_id';

    public $guarded = [];

    //倒计时天数
    public function setDays()
    {
        switch ($this->attributes['is_feeback']) {
            case 1:
                return Carbon::now()
                    ->diffInDays(
                        Carbon::parse($this->attributes['member_data']), false
                    );
                break;
            case 2:
                return Carbon::now()
                    ->diffInDays(
                        Carbon::parse($this->attributes['seller_data']), false
                    );
                break;
            default:
                return Carbon::now()
                    ->diffInDays(
                        Carbon::parse($this->attributes['default_data']), false
                    );

        }
    }

    public function setFilesAttribute($value)
    {
        $this->attributes['files'] = serialize($value);
    }

    public function getFilesAttribute($value)
    {
        $files = unserialize($value);
        $file = [];
        if ($files) {
            $file['status'] = $files['status'];
            unset($files['status']);
            $file['data'] = array_filter(array_flatten($files));
            return $file;
        }

    }

    public function appoinWaiter()
    {
        return $this->hasOne(HgWaiter::class,'id','waiter_id');
    }



}
