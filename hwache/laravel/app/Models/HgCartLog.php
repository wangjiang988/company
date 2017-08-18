<?php namespace App\Models;

/**
 * 订单价格表模型
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;

class HgCartLog extends Model {

    protected $table = 'hg_cart_log';

    protected $primaryKey = 'id';

    public $timestamps = false;

    /**
     * 根据查询条件查询订单信息
     * @param $query
     * @param array $map
     * @return mixed
     */
    public function scopeGetOrderLog($query, array $map)
    {
        return $query->orderBy('time', 'desc')->where($map)->get();
    }
    // 记录日志
    public static function putLog($cart_id,$user_id,$cart_status,$action,$msg,$timeout=0,$msg_time=NULL)
    {
         return  self::insertGetId(['cart_id' => $cart_id, 'user_id' => $user_id,'cart_status'=>$cart_status,'action'=>$action,'msg'=>$msg,'timeout'=>$timeout,'msg_time'=>$msg_time]); 

    }
    // 取得诚意金进入时间
    public static function get_earnest_time($cart_id){
    	//支付诚意金新的log
    	
    	return self::where('cart_id','=',$cart_id)->where('action','=','user/money/earnest')->pluck('time');
    	//return self::where('cart_id','=',$cart_id)->where('action','=','PayController/getEarnest')->pluck('time');
    }
    // 取得客户付完诚意金代理反馈订单的时间
    public static function get_feedback_time($cart_id){
    	return self::where('cart_id','=',$cart_id)->where('action','=','IndexController/feedBack')->pluck('time');
    }
    // 取得客户付完诚意金代理反馈订单提议修改的时间
    public static function get_feedback_edit_time($cart_id)
    {
        return self::where('cart_id','=',$cart_id)->where('action','=','IndexController/feedBack')->where('cart_status','=',202)->pluck('time');
    }
    // 客户接受特需文件
    public static function get_acceptfile_time($cart_id)
    {
        return self::where('cart_id','=',$cart_id)->where('action','=','CartController/editCar')->where('cart_status','=',2021)->pluck('time');
    }
    // 代理反馈诚意金ok
    public static function get_feedback_ok_time($cart_id)
    {
         return self::where('cart_id','=',$cart_id)->where('action','=','IndexController/feedBack')->where('cart_status','=',203)->pluck('time');
    }
    // 客户不接受特需文件而终止订单时间
    public static function get_shop2_time($cart_id)
    {
        return self::where('cart_id','=',$cart_id)->where('action','=','CartController/editCar')->where('cart_status','=',1003)->pluck('time');
    }
    // 代理反馈特需并修改订单，客户不接受修改时间
    public static function get_shop3_time($cart_id)
    {
        return self::where('cart_id','=',$cart_id)->where('action','=','CartController/editCar')->where('cart_status','=',1004)->pluck('time');
    }
    // 客户接受特别文件安排时间
    public static function get_acceptall_time($cart_id)
    {
        return self::where('cart_id','=',$cart_id)->where('action','=','CartController/editCar')->where('cart_status','=',2023)->pluck('time');
    }
    // 取得客户付完诚意金，代理终止订单时间
    public static function get_stop1_time($cart_id)
    {
        return self::where('cart_id','=',$cart_id)->where('action','=','IndexController/feedBack')->where('cart_status','=',1001)->pluck('time');
    }
    // 取得担保金进入时间
    public static function get_deposit_time($cart_id){
    	return self::where('cart_id','=',$cart_id)->where('action','=','user/money/doposit')->pluck('time');
    }
    // 收到担保金响应订单时间
    public static function get_response_time($cart_id){
    	return self::where('cart_id','=',$cart_id)->where('action','=','IndexController/feedBackResponse')->pluck('time');
    }
    // 反馈担保金ok代理提出修改订单时间
    public static function get_pdiedit_time($cart_id){
        return self::where('cart_id','=',$cart_id)->where('action','=','IndexController/responseOk')->where('cart_status','=',302)->pluck('time');
    }
    // 反馈担保金ok代理终止订单
    public static function get_stop2_time($cart_id){
        return self::where('cart_id','=',$cart_id)->where('action','=','IndexController/responseOk')->where('cart_status','=',1005)->first();
    }
    // 经销商代理发出交车通知时间
    public static function get_pdinotice_time($cart_id)
    {
        return self::where('cart_id','=',$cart_id)->where('action','=','IndexController/PdiNotice')->pluck('time');
    }
    // 客户确认交车通知
    public static function get_yuyueok_time($cart_id)
    {
        return self::where('cart_id','=',$cart_id)->where('action','=','CartController/getYuyue')->pluck('time');
    }
    // 客户提车信息提交时间
    public static function get_tiche_time($cart_id)
    {
        return self::where('cart_id','=',$cart_id)->where('action','=','CartController/ticheInfo')->pluck('time');
    }
    // 代理提车信息提交时间
    public static function get_dealer_tiche_time($cart_id)
    {
        return self::where('cart_id','=',$cart_id)->where('action','=','PdiController/ticheInfo')->pluck('time');
    }
    
    //获取订单所有的流程进度记录
    public static function getDealerLogByStatus($cart_id,$cart_status)
    {
    	return self::where('cart_id','=',$cart_id)->where('cart_status','>',200)->orderBy('time', 'desc')->get();
    	//return self::where('cart_id','=',$cart_id)->where('cart_status','<=',$cart_status)->orderBy('time', 'desc')->get();
    }
    
}
