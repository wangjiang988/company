<?php namespace App\Models;

/**
 * 代理选择的选装件模型
 */
use Illuminate\Database\Eloquent\Model;
use DB;
class HgXzjDaili extends Model {

    protected $table = 'hg_xzj_daili';

    public $timestamps = false;

    /*
	*取得经销商添加的选装件
	*@dealer_id  经销商的id
	*@member_id  代理id
	*@brand_id   车型id
    */
	public static function getDealerXzj($brand_id,$dealer_id,$member_id)
	{
		return self::where('car_brand','=',$brand_id)->where('dealer_id','=',$dealer_id)->where('member_id','=',$member_id)->get();
		
	}
	// 原厂
	public static function getDealerXzjYc($brand_id,$dealer_id,$member_id)
	{
		return self::where('car_brand','=',$brand_id)->where('dealer_id','=',$dealer_id)->where('member_id','=',$member_id)->where('xzj_yc','=',1)->get();
		
	}
	// 非原厂
	public static function getDealerXzjFyc($brand_id,$dealer_id,$member_id)
	{
		return self::where('car_brand','=',$brand_id)->where('dealer_id','=',$dealer_id)->where('member_id','=',$member_id)->where('xzj_yc','=',0)->get();
		
	}
	// 去除已经安装的原厂选装件，供客户选择
	public static function getOtherXzj($brand_id,$dealer_id,$member_id,$bj_id)
	{
		$dailixzjs=self::where('car_brand','=',$brand_id)->where('dealer_id','=',$dealer_id)->where('member_id','=',$member_id)->where('xzj_yc','=',1)->where('xzj_front','=',0)->get();

		// 报价中已安装的原厂选装件
		$installxzjs=HgBaojiaXzj::getBaojiaXzj($bj_id);
		
		foreach ($installxzjs as $key => $value) {
            $ids[]=$value->m_id;//要去除的id
        }
        foreach ($dailixzjs as $key => $value) {
            if(in_array($value['id'], $ids)) unset($dailixzjs[$key]);
        }
        return $dailixzjs;
	}


    public static function getDealerXzjDatas(
        $brand_id,
        $daili_dealer_id,
        $staple_id,
        array $xzj_ids
    ) {
        return self::where('xzj_yc', 1)
            // ->where("xzj_front",1)
            ->where('car_brand', $brand_id)
            ->whereIn('xzj_list_id', $xzj_ids)
            ->where('daili_dealer_id', $daili_dealer_id)
            ->where('staple_id', $staple_id)
            ->select('xzj_list_id', 'xzj_fee', 'xzj_guide_price', 'id')
            ->get();
    }
}
