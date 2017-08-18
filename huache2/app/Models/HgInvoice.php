<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HgInvoice extends Model
{
 	protected $table = 'invoice';

    public $timestamps = false;
    
    /**
     * $order_num
     * $ori='Y' 原始票  C重开票
     */
    // 取得订单的发票信息 不带重新开得票
    public static function getInvoiceByOrder($order_num,$ori='Y')
    {
    	if($ori=='Y'){
    		$invoice_type=0;
    	}elseif($ori=='C'){
    		$invoice_type=1;
    	}
    	return self::select("*")
    				->where('order_num','=',$order_num)
    				->where('member_id','=',session('user.member_id'))
    				->where('invoice_type','=',$invoice_type)
    				->first();
    }
    
   
    //取得用户所有订单的发票信息
    public static function getAllInvoiceByUser($member_id)
    {
    	return self::Rightjoin("hg_cart", "hg_cart.order_num","=","invoice.order_num")
				->where('hg_cart.buy_id','=',$member_id)
				->whereIn('hg_cart.calc_status',array(1,2))//判断订单是否进入结算状态
				->get();
    }
}
