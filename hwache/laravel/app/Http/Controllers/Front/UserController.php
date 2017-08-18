<?php namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\HgSeller;
use Validator;
use App\Http\Requests;
use Request;
use Redirect, Input;
use App\Models\HgUser;
use App\Models\HgCart;
use App\Models\HgUserXzj;
use Hash;
use Session;
use Config;

use App\Core\Contracts\Sms\Sms;

class UserController extends Controller {

    /**
     * ajax登陆
     * @param Request $request
     * @return string
     */
    public function getLogin(Request $request)
    {
        Session::reflash();
        $backurl=session('backurl') ? session('backurl') : $_ENV['_CONF']['config']['shop_site_url'];
        // 跨域
        // header('content-type:application:json;charset=utf8');
        // header('Access-Control-Allow-Origin:*');
        // header('Access-Control-Allow-Methods:POST');
        // header('Access-Control-Allow-Headers:x-requested-with,content-type');
        $v = Validator::make(Request::all(), [
            'loginname' => 'required|max:255',
            'loginpwd' => 'required',
        ]);

        if ($v->fails())
        {

            return json_encode(array('error_code'=>3,'error_msg'=>'账号和密码必填','backurl'=>$backurl));
        }
        $member_name = Request::input('loginname');
        $member_passwd=Request::input('loginpwd');
        $user=HgUser::where('member_mobile', '=', $member_name)->orWhere('member_email','=',$member_name)->first();
        if(!$user){
            return json_encode(array('error_code'=>1,'error_msg'=>'手机号或邮箱错误','backurl'=>$backurl));
        }

        if (!(Hash::check($member_passwd, $user['member_passwd'])))
        {
            return json_encode(array('error_code'=>1,'error_msg'=>'密码错误','backurl'=>$backurl));
        }
        if(Request::input('logincode')){
            if(strtoupper(Request::input('logincode'))!=$_SESSION['codes'])
            {

                return json_encode(array('error_code'=>2,'error_msg'=>'验证码错误','backurl'=>$backurl));
            }
        }
        // return redirect('http://user.123.com/index.php?act=login&op=index');
        $_SESSION['is_login']   = 1;
        $_SESSION['member_id']  = $user->member_id;
        $_SESSION['member_name']= $user->member_name;
        $_SESSION['member_email']= $user->member_email;
        // $_SESSION['member_mobile']= $user->member_mobile;
        $_SESSION['is_buy']     = $user->is_buy;
        $_SESSION['avatar']     = $user->member_avatar;
        $seller_info = HgSeller::where('member_id','=',$_SESSION['member_id'])->first();
        $_SESSION['store_id'] = $seller_info['store_id'];
        if (trim($user->member_qqopenid)){
            $_SESSION['openid']     = $user->member_qqopenid;
        }
        if (trim($user->member_sinaopenid)){
            $_SESSION['slast_key']['uid'] = $user->member_sinaopenid;
        }
         return json_encode(array('error_code'=>0,'error_msg'=>'登录成功','backurl'=>$backurl));//用戶名錯誤
    }
    // 页面登录
    public function userLogin() {
        Session::reflash();
        return view('user.login');
    }

    // 注册
    public function regeister(){
        return view('user.regeister');
    }

    // 登出
    public static function logout(){
        session_unset();
        session_destroy();
        return Redirect::back();
    }
    // 新增
    public function store(Request $request)
    {
        $this->validate($request, [
            'member_name' => 'required|unique:member|max:255',
            'member_passwd' => 'required',
        ]);

        $user = new HgUser;
        $user->member_name = Input::get('member_name');
        $user->member_passwd = bcrypt(Input::get('member_passwd'));

        if ($user->save()) {
            return Redirect::to('index');
        } else {
            return Redirect::back()->withInput()->withErrors('保存失败！');
        }

    }
    // 手机号码注册
    public function regbyphone(){

        return view('user.reg_phone')->withTitle('通过手机号注册账号');
    }
    public function regbyphone2(){
        if(!session('reg_mobile')) exit("来路不明");
        // 将上一步手机号保存到数据库
        $user = new HgUser;
        $user->member_mobile = session('reg_mobile');
        $user->save();
        $insertedId = $user->id;
        return view('user.reg_phone2')->withTitle('通过手机号注册账号');
    }
    public function regbyphone3(){
        if(!session('reg_mobile')) exit("来路不明");
        $affectedRows = HgUser::where('member_mobile', '=', session('reg_mobile'))->update(['member_passwd' => bcrypt(Request::input('pwd'))]);
        if(!$affectedRows) eixt('设置密码出错');
        Session::forget('reg_mobile');
        Session::forget('reg_code');
        return view('user.reg_phone3')->withTitle('通过手机号注册账号');
    }

    public function regbyemail(){

        return view('user.reg_email')->withTitle('通过邮箱注册账号');
    }
    public function regbyemail2(){
        return view('user.reg_email2')->withTitle('通过邮箱注册账号');
    }
    public function regbyemail3($email,$pwd){
        $affectedRows = HgUser::where('member_email', '=', $email)->first();

        if ($affectedRows && Hash::check($pwd, $affectedRows['member_passwd'])) {
            return view('user.reg_email3')->withTitle('通过邮箱注册账号');
        }else{
            return '请查看邮箱信息';
        }

    }
    public function reg_sendemail($email){
        // 检测邮箱是否注册过
        $user=HgUser::where('member_email', '=', $email)->first();
        if($user){
            return json_encode(array('error_code'=>1,'error_msg'=>'邮箱已经注册过了'));
        }
        // 用邮件接口发送注册邮件
        $user = new HgUser;
        $user->member_email = $email;
        $user->save();
        return json_encode(array('error_code'=>0,'error_msg'=>'邮箱可以注册'));
    }
    // 发送验证码
    public static function sendCode(Sms $sms, $tel){
//        // 检测手机号是否注册过
//        $user=HgUser::where('member_mobile', '=', $tel)->first();
//        if($user){
//            return json_encode(array('error_code'=>1,'error_msg'=>'手机号已经注册过了'));
//        }
//        Session::put('reg_mobile', $tel);
//
//        // 用接口发手机验证码
//        Session::put('reg_code', '12345');
//        return json_encode(array('error_code'=>0,'error_msg'=>'手机号没有注册过'));
        // 检测手机号是否注册过
        $user = HgUser::where('member_mobile', '=', $tel)->count();
        if ($user) {
            return json_encode(array('error_code'=>1,'error_msg'=>'此手机号已注册'));
        }

        /**
         * 发送短信，并把验证码保存在session中
         */
        // 随机验证码
        $code = get_rand();
        // 发送短信
        $result = $sms->send(
            $tel,
            json_encode([
                'code'    => $code,
                'product' => '华车',
            ]),
            [
                'sms_free_sign_name' => 'reg',
                'sms_template_code'  => 'reg',
            ]
        );

        if ($result) {
            Session::put('reg_mobile', $tel);
            // 用接口发手机验证码
            Session::put('reg_code', $code);
            return json_encode(array('error_code'=>0,'error_msg'=>'验证码已经发送到手机'));
        }

        return json_encode(array('error_code'=>1,'error_msg'=>'短信发送失败，请等待重试'));

    }
    // 发送验证码
    /**
     * 
     * @param Sms $sms
     * @param string $tel
     * @param number $type 1修改用户手机号码 ，第一次或者直接发送验证码  2，新的手机号码
     * @return string
     */
    public static function sendCodeToChangePhone(Sms $sms, $tel,$type=1){
    	$user = HgUser::where('member_mobile', '=', $tel)->count();
    	/**
    	 * 发送短信，并把验证码保存在session中
    	 */
    	$do = true;
    	if($type==1){//输入验证 原先的号码
    		if(!$user){//此号码没有对应的用户存在
    			$do = false;
    			$errStr = '不存在此手机号码对应的用户';
    		}
    		
    	}elseif($type==2){//输入新的手机号码
    		if($user){//新号码已经有用户使用
    			$do = false;
    			$errStr = '此号码已经存在对应的用户，请更换正确的号码';
    		}
    	}
    	
    	//限制  一分钟内只能发生一条短信
    	if(!empty(session('reg_code_time_'.$type)) ){
    		if(session('reg_code_time_'.$type)+60<time()){
    			$do = true;
    		}else{
    			$do = false;
    			$errStr = '一分钟内只能发生一条短信';
    		}
    	
    	}
    	
    	if($do === false ){//用户不存在 或者一分钟内重复发送
    			return json_encode(array('error_code'=>1,'error_msg'=>$errStr));
    		
    	}
    	
    	// 随机验证码
    	$code = get_rand();
    	// 发送短信
    	$result = $sms->send(
    			$tel,
    			json_encode([
    					'code'    => $code,
    					'product' => '华车',
    			]),
    			[
    					'sms_free_sign_name' => 'change_info',
    					'sms_template_code'  => 'change_info',
    			]
    	);
    	if ($result) {
    		Session::put('change_phone_status', $type);
    		// 用接口发手机验证码
    		Session::put('reg_code', $code);
    		Session::put('reg_code_time_'.$type, time());
    		return json_encode(array('error_code'=>0,'error_msg'=>'验证码已经发送到手机'));
    	}
    	
    	return json_encode(array('error_code'=>1,'error_msg'=>'短信发送失败，请等待重试'));
    }
    // 取得验证码
    public static function getCode(){

        return session('reg_code');
    }
    //客户实时查看订单情况
    public static function getMyOrder($order_num)
    {
        $orderstatus=HgCart::getOrderStat($order_num)->toArray();
        if(!$orderstatus['cart_status']) exit('此订单已取消');
        $status=Config::get('orderstatus');
        if($orderstatus['cart_status']<=5 || $orderstatus['cart_status']==1000){//交车完成前的步骤 代理和客户的状态是一对一的
        	$order_status = $orderstatus['cart_sub_status'];
        }else{
        	$order_status = $orderstatus['end_user_status'];
        }
        if(isset($status[$order_status])){
        	$action = $status[$order_status]['user'];
        	if(substr($action,-1)=='/'){
        		return redirect($action.$order_num);
        	}else{
        		return redirect($action);
        	}
        }else{
        	return '订单状态不确定';
        }
        
    }
    //经销商代理实时查看订单情况
    public static function getMyOrderDaiLi($order_num)
    {
    $orderstatus=HgCart::getOrderStat($order_num)->toArray();
        if(!$orderstatus['cart_status']) exit('此订单已取消');
        $status=Config::get('orderstatus');
        if($orderstatus['cart_status']<=5 || $orderstatus['cart_status']==1000){//交车完成前的步骤 代理和客户的状态是一对一的
        	$order_status = $orderstatus['cart_sub_status'];
        }else{
        	$order_status = $orderstatus['end_pdi_status'];
        }
        if(isset($status[$order_status])){
        	$action = $status[$order_status]['dealer'];
        	if(substr($action,-1)=='/'){
        		return redirect($action.$order_num);
        	}else{
        		return redirect($action);
        	}
        }else{
        	return '订单状态不确定';
        }
    }
    // 保存用户选择的选装件
    public function saveUserXzj()
    {
        $str=(Request::all());
        if (isset($str['fyc'])) {
            foreach ($str['fyc'] as $key => $value) {
                if(HgUserXzj::isInsert($value['id'],$value['order_num'])) continue;
                $map = array(
                            'id'=>$value['id'],
                            'member_id'=>$_SESSION['member_id'] ,
                            'xzj_name'=>$value['name'] ,
                            'xzj_model'=>$value['xinghao'] ,
                            'guide_price'=>$value['zhidaojia'] ,
                            'fee'=>$value['anzhuangfei'] ,
                            'discount_price'=>$value['danjia'] ,
                            'num'=>$value['shuliang'] ,
                            'price'=>$value['price'] ,
                            'xzj_brand'=>$value['pingpai'] ,
                            'is_yc'=>0 ,
                            'order_num'=>$value['order_num'] ,
                    );
                HgUserXzj::insert($map);
            }
        }
        if (isset($str['yc'])) {
            foreach ($str['yc'] as $key => $value) {
                if(HgUserXzj::isInsert($value['id'],$value['order_num'])) continue;
                $map = array(
                            'id'=>$value['id'],
                            'member_id'=>$_SESSION['member_id'] ,
                            'xzj_name'=>$value['name'] ,
                            'xzj_model'=>$value['xinghao'] ,
                            'guide_price'=>$value['zhidaojia'] ,
                            'fee'=>$value['anzhuangfei'] ,
                            'discount_price'=>$value['danjia'] ,
                            'num'=>$value['shuliang'] ,
                            'price'=>$value['price'] ,
                            'xzj_brand'=>$value['pingpai'] ,
                            'is_yc'=>0 ,
                            'order_num'=>$value['order_num'] ,
                    );
                HgUserXzj::insert($map);
            }
        }

        return 1;
    }
}
