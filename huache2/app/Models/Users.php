<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Common;

class Users extends Model
{
    use Common;
    //
    protected $table = 'users';

    protected $fillable = ['phone', 'password'];
    protected $guarded  = ['id','name', 'email','status','login_num'];

    public static function createTable($data){
        return self::create($data);
    }

    /**
     * 查看用户三个月内的购买记录
     * @return mixed
     */
    public static function isCheckOrder($map){
        return self::leftJoin('hc_order as order','order.user_id','=','users.id')
            ->map($map)
            ->where('order.order_status','=',99)
            ->whereRaw("DATE_SUB(CURDATE(), INTERVAL 3 MONTH) <= date(car_order.created_at)")
            ->count();
    }
    /**
     * 检查用户是否实名认证
     * @param $map
     */
    public static function isIdVerify($map){
        $result = self::leftJoin('user_extension as ue','ue.user_id','=','users.id')
            ->whereRaw($map)
            ->value('is_id_verify');
        if(is_null($result)){
            return 0;
        }else{
            return intval($result ==1);
        }
    }

    public static function checkUsersAnswer($map)
    {
        $result = self::leftJoin('user_extension as ue','ue.user_id','=','users.id')
            ->whereRaw($map)
            ->count();
        return $result;
    }
}
