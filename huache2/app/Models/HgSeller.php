<?php namespace App\Models;

/**
 * 卖家店铺模型
 */
use Illuminate\Database\Eloquent\Model;
class HgSeller extends Model {

    protected $table = 'seller';

    protected $primaryKey = 'seller_id';

    protected $guarded = ['seller_id'];

    public $timestamps = false;
    
    // 取得卖家的信息
    public static function getProxy($seller_id)
    {
    	$seller = self::leftJoin('member','seller.member_id','=','member.member_id')->where('seller.seller_id','=',$seller_id)->first();
    	return $seller;
    }
    //经销商登录检测；
    public static function checkLoginBySellerName($seller_name){
    	$seller = self::leftJoin('member','seller.member_id','=','member.member_id')->where('seller.seller_name','=',$seller_name)->first();
    	return $seller;
    }
    
    //经销商登录错误，手机号码、手机验证码登录；
    public static function checkLoginByPhone($phone){
    	$seller = self::leftJoin('member','seller.member_id','=','member.member_id')->where('member.member_mobile','=',$phone)->first();
    	return $seller;
    }
}
