<?php namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\HgMediate;
use App\Models\HgDispute;
use App\Models\HgDefend;
use App\Models\HgCart;
use DB;
use Request;
use Input;
use HgEvidence;

class AjaxController extends Controller {

    // 省市联动
    public function getCity($id){
    	$allArea = config('area');
    	$tmp = $allArea[$id];
    	$citys = array();
    	foreach($tmp as $k =>$v){
    		$v['city_id'] = $k;
    		$citys[] = $v;
    	}
        //$citys = Area::getCitys($id);
        return json_encode($citys);
    }
    /*
	*发送争议处理的和解信息
	*$order_num 订单号
	*$dispute_id  争议id
	*$member_id  会员id
    */
    public function postMediate()
    {
    	$order_num=Request::Input('order_num');
    	$member_id=Request::Input('member_id');
    	$dispute_id=Request::Input('dispute_id');
    	// 检查订单是否真实
    	$order=HgCart::GetOrderStatus($order_num);
    	if (!isset($order->id)) {
    		return 1;
    	}
    	// 检测争议是否存在
    	if (!HgDispute::isdispute($dispute_id)) {
    		return 2;
    	}
    	// 检测是否登录用户
    	if ($_SESSION['member_id']<>$member_id) {
    		return 3;
    	}
    	// 检测是否已经提交过
    	if (HgMediate::getHejie($order_num,$dispute_id,$member_id)) {
    		return 4;
    	}
    	$id=HgMediate::insert([
    			'order_num'=>$order_num,
    			'dispute_id'=>$dispute_id,
    			'member_id'=>$member_id,
    			'content2'=>$content2,
    		]);
    	if(!$id) return 5;
    	return '200';
    }
    /*
	*提交补充证据
	*$order_num 订单号
	*$dispute_id  争议id
	*$defend_id		申辩id
	*$member_id  会员id
     */
    public function postZhengju()
    {
    	$order_num=Request::Input('order_num');
    	$member_id=Request::Input('member_id');
    	$dispute_id=Request::Input('dispute_id')?Request::Input('dispute_id'):0;
    	$defend_id=Request::Input('defend_id')?Request::Input('defend_id'):0;
    	if (Request::file('zhengju')) {
            foreach (Request::file('zhengju') as $key => $value) {
                if ($value->isValid())
                {
                    $entension = $value-> getClientOriginalExtension(); 
                    if (!allowext($entension)) return 3;
                    $fileName='p'.date('YmdHms').mt_rand(1000,9999).'.'.$entension;
                    $value->move(config('app.uploaddir').'evidence', $fileName);
                }
                $ev=HgEvidence::insert(['urls'=>$fileName,'member_id'=>$member_id,'dispute_id'=>$dispute,'order_num'=>$order_num,'defend_id'=>$defend_id]);
                if(!$ev) return 2;//保存数据失败
            }
            return 200;
        }
        return 1;//没有提交文件
    }
    
    
    
}
