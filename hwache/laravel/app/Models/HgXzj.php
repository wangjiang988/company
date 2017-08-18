<?php namespace App\Models;

/**
 * 选装件模型
 */
use Illuminate\Database\Eloquent\Model;
use DB;
class HgXzj extends Model {

    protected $table = 'hg_xzj_list';

    public $timestamps = false;

    /*
	*取得经销商添加的选装件
	*@dealer_id  经销商的id
	*@member_id  代理id
	*@brand_id   车型id
    */
	public static function getDealerXzj($brand_id,$dealer_id,$member_id)
	{
		// 先取得car_hg_xzj_daili_main表中的id
		$m_id=DB::table('hg_xzj_daili_main')->where('member_id',$member_id)->where('dealer_id',$dealer_id)->where('car_brand',$brand_id)->pluck('id');
		$list=DB::table('hg_xzj_daili')
		->leftJoin('hg_xzj_list', 'hg_xzj_daili.xzj_list_id', '=', 'hg_xzj_list.id')->where('hg_xzj_daili.main_id','=',$m_id)
        ->get();
        return $list;
	}
	
	/*
	 *取得经销商添加原厂非前置的选装件
	 *@dealer_id  经销商的id
	 *@member_id  代理id
	 *@brand_id   车型id
	 */
	public static function getDealerYcNotFrontXzj($brand_id,$dealer_id,$member_id)
	{
		// 先取得car_hg_xzj_daili_main表中的id
		$m_id=DB::table('hg_xzj_daili_main')->where('member_id',$member_id)->where('dealer_id',$dealer_id)->where('car_brand',$brand_id)->pluck('id');
		$list=DB::table('hg_xzj_daili')
		->leftJoin('hg_xzj_list', 'hg_xzj_daili.xzj_list_id', '=', 'hg_xzj_list.id')
		->where('hg_xzj_daili.main_id','=',$m_id)
		->where('hg_xzj_list.xzj_yc',1)
		->where('hg_xzj_list.xzj_front',0)
		->get();
		return $list;
	}
}
