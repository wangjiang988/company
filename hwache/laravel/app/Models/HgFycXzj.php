<?php namespace App\Models;

/**
 * 非原厂选装件模型
 */
use Illuminate\Database\Eloquent\Model;
use DB;
class HgFycXzj extends Model {

    protected $table = 'hg_fyc_xzj';

    public $timestamps = false;
    // 取得订单的非原厂选装件
    public static function getByOrder($order_num)
    {
    	return self::leftJoin('hg_xzj_daili', 'hg_fyc_xzj.mid', '=', 'hg_xzj_daili.id')->leftJoin('hg_xzj_list','hg_xzj_daili.xzj_list_id','=','hg_xzj_list.id')->select('hg_fyc_xzj.mid','hg_xzj_daili.xzj_max_num','hg_fyc_xzj.discount_price','hg_fyc_xzj.order_num','hg_xzj_daili.xzj_has_num','hg_xzj_daili.xzj_title','hg_xzj_daili.xzj_model','hg_xzj_daili.xzj_brand','hg_xzj_list.xzj_notice','hg_xzj_daili.xzj_guide_price')->where('order_num','=',$order_num)->get();
    }
}
