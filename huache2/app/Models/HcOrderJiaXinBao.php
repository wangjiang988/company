<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HcOrderJiaXinBao extends Model
{
    protected $table ='hc_order_jiaxinbao_detail';//

    protected $guarded = ['id'];

    public static function hasSeller($seller_id)
    {
        return self::where('role',2)
            ->where('is_del',0)
            ->where('user_id',$seller_id)
            ->get();
    }

    public static function haseMember($user_id)
    {
        return self::where('role',1)
            ->where('is_del',0)
            ->where('user_id',$user_id)
            ->get();
    }
}
