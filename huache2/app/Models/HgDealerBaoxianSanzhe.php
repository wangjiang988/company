<?php namespace App\Models;

/**
 * 代理三者险数据模型
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;

use Cache;

class HgDealerBaoxianSanzhe extends Model {

    protected $table = 'hg_dealer_baoxian_sanzhe';

    public $timestamps = false;

    use Common;
    // 取得该模型的报价费率
    public static function getPriceRate($bxid) 
    {
       return self::where('baoxian_id','=',$bxid)->get();
    }

}
