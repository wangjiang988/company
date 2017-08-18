<?php namespace App\Models;

/**
 * 代理保险不计免赔险模型
 */
use Illuminate\Database\Eloquent\Model;
use Cache;
class HgDealerBaoXianBuJiMian extends Model {

    protected $table = 'hg_dealer_baoxian_bujimian';

    public $timestamps = false;
    // 取得代理对应的不计免赔险费率
    public static function getBuJiMianRate($type=1,$baoxian_id,$baoxian_type) {
    	return self::where('type','=',$type)->where('baoxian_id','=',$baoxian_id)->where('baoxian_type','=',$baoxian_type)->pluck('rate');

    }
}
