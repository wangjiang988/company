<?php
/**
 * 经销商登陆控制器
 *
 * 手机登陆，邮箱登陆等
 *
 */
namespace App\Http\Controllers\Dealer;

use DB;
use Validator;
use Illuminate\Http\Request;
use App\Com\Hwache\User\User;
use App\Http\Controllers\Controller;
use App\Models\HgSeller;
use Hash;
use App\Models\HgLock;
use App\Models\HgUser;
use App\Core\Contracts\Sms\Sms;
use Session;

class DealerLoginController extends Controller
{
    /**
     * 请求依赖
     * @var Request
     */
    private $request;

    /**
     * 用户中心模块依赖
     * @var User
     */
    private $user;

    /**
     * 构造函数，初始化内部依赖变量
     * @param Request $request
     * @param User $user
     */
    public function __construct(Request $request, User $user)
    {
        $this->request = $request;
        $this->user    = $user;

    	
    }

    /**
     * Login Page Show
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getLogin()
    {
    	if(session('user.is_login') && session('user.user_type')==2){
    		return redirect()->route('dealer.ucenter');
    	}else{
    		$view['title'] = '经销商代理-登录';
    		return view('dealer.login.login',$view);
    	}
    }

    /**
     * AJAX && POST Commit Data
     * @return array
     */
    public function postlogin(Request $request)
    {
        //if ($this->request->ajax()) {
        $code = $this->request->input('code');//验证码
        if ( ! checkSeccode($code)) {
            return [
                'code' => 0,
                'msg'  => '验证码输入不正确',
            ];
        }
        $validator = Validator::make($this->request->all(), [
            'name' => 'required',
            'pwd'  => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'code' => 0,
                'msg'  => trans('user.name_pwd_not_empty'),
            ];
        }
        $seller_name = $this->request->input('name');
        $passwd = $this->request->input('pwd');
        $member = HgSeller::checkLoginBySellerName($seller_name);
        // 验证帐号和密码
        if ($member) {
            $pid = HgLock::getPid('login', $this->request->ip(),
                $member->member_id);
            $lock = HgLock::get($pid);
            if ($lock >= 3) {//检查锁定情况
                return [
                    'code'  => 4,
                    'msg'   => '用户登录密码三次错误，账号被锁定',
                    'phone' => $member['member_mobile'],
                ];
            }
            if (Hash::check($passwd, $member->member_passwd)) {
                if ($member->status != 1) {
                    return [
                        'code' => 5,
                        'msg'  => '你的账户已被冻结或未审核,请联系客服!',
                    ];
                }
                HgLock::rm($pid);//删除锁定
                $data = [
                    'member_old_login_time' => $member->member_login_time,
                    'member_old_login_ip'   => $member->member_login_ip,
                    'member_login_time'     => time(),
                    'member_login_ip'       => $this->request->ip(),
                ];
                // 更新局部信息
                HgUser::where(array('member_id' => $member->seller_id))
                    ->update($data);

                $data['is_login'] = 1;
                $data['user_type'] = 2;//2为经销商
                $data['member_id'] = $member->member_id;
                $data['seller_id'] = $member->seller_id;
                $data['seller_name'] = $member->seller_name;
                $data['seller_mobile'] = $member->member_mobile;
                $data['store_id'] = $member->store_id;

                // 保存session
                $this->user->saveUserSession($data);

                if ($request->session()->has('redirect')) {
                    return [
                        'code'    => 1,
                        'nextUrl' => urldecode($request->session()
                            ->pull('redirect'))
                    ];
                }

                return [
                    'code'    => 1,
                    'nextUrl' => route('dealer.ucenter'),
                ];
            } else {
                $num = $lock == null ? 0 : $lock;
                HgLock::set($pid, $lock + 1);//密码错误 锁定值加1
                if ($num == 2) {
                    return [
                        'code' => 4,
                        'msg'  => '登录账号或者密码错误',
                    ];
                } else {
                    return [
                        'code' => 0,
                        'msg'  => '登录账号或者密码错误',
                    ];
                }

            }

        }

        return [
            'code' => 0,
            'msg'  => trans('user.validator_fails'),
        ];
        //}

        return [
            'code' => 0,
            'msg'  => 'Invalid Parameters',
        ];
    }

    /**
     * 登出
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getLogout()
    {
		session()->flush();
		
		return redirect()->route('dealer.login');
    }

    // 发送手机验证码
    public function sendMobileCode(Sms $sms){
    	$tel = $this->request->input('phone');
    	$type = $this->request->input('type');
    	$type = !empty($type)?$type:1;//1 为登录验证，2为修改密码验证,3为修改手机号码验证,4新手机号码验证,5修改邮箱验证
    	// 检测手机号是否注册过
    	$user = HgUser::where('member_mobile', '=', $tel)->count();
    	if($type ==4){//新手机号码检测
    		if(session('login_mobile')==$tel){
    			return json_encode(array('error_code'=>1,'error_msg'=>'修改号码和原始号码一致'));
    		}
    		if ($user) {
    			return json_encode(array('error_code'=>1,'error_msg'=>'此手机号对应的用户已经存在'));
    		}
    	}else{
	    	if (!$user) {
	    		return json_encode(array('error_code'=>1,'error_msg'=>'此手机号对应的用户不存在'));
	    	}
    	}

    	$overtime = true;
    	//限制  一分钟内只能发生一条短信
    	if(!empty(session('login_code_time_'.$type)) ){
    		if(session('login_code_time_'.$type)+60 < time()){
    			$overtime = true;
    		}else{
    			$overtime = false;
    			$errStr = '一分钟内只能发生一条短信';
    		}

    	}

    	if($overtime === false ){//用户不存在 或者一分钟内重复发送
    		//return json_encode(array('error_code'=>1,'error_msg'=>$errStr));
    	
    	}
    	
    	/**
    	 * 发送短信，并把验证码保存在session中
    	 */
    	// 随机验证码
    	$code = get_rand();
    	$code = '1234';
    	$result = true;
    	// 发送短信
    	/**
    	$result = $sms->send(
    			$tel,
    			json_encode([
    					'code'    => $code,
    					'product' => '华车',
    			]),
    			[
    					'sms_free_sign_name' => 'login',
    					'sms_template_code'  => 'login',
    			]
    	);
    	**/
    	if ($result) {
    		Session::put('login_mobile', $tel);
    		// 用接口发手机验证码
    		Session::put('login_code', $code);
    		Session::put('login_code_time_'.$type, time());
    		
    		return json_encode(array('error_code'=>0,'error_msg'=>'验证码已经发送到手机'));
    	}
    
    	return json_encode(array('error_code'=>1,'error_msg'=>'短信发送失败，请等待重试'));
    }
    
    // 通过手机和验证码登录
    public function postLoginByPhone(Request $request){
    	$tel = $this->request->input('phone');
    	$tel = !empty($tel)?$tel:session('login_mobile');
    	$code = $_POST['code'];
    	$type = 1;
    	if(session('login_code_time_'.$type)+600 > time() ){
    		return json_encode(array('error_code'=>0,'error_msg'=>'验证码失效'));
    	}
    	if(session('login_code') !=$code){
    		return json_encode(array('error_code'=>2,'error_msg'=>'短信验证码输入有误'));
    	}else{
    		$member = HgSeller::checkLoginByPhone($tel);
    		$pid = HgLock::getPid('login',$this->request->ip(),$member->member_id);
    		HgLock::rm($pid);//删除锁定
    		$data = [
    				'member_old_login_time' => $member->member_login_time,
    				'member_old_login_ip'   => $member->member_login_ip,
    				'member_login_time'     => time(),
    				'member_login_ip'       => $this->request->ip(),
    		];
    		// 更新局部信息
    		HgUser::where(array('member_id'=>$member->seller_id))->update($data);
    		
    		$data['is_login']    = 1;
    		$data['user_type']    = 2;//2为经销商
    		$data['member_id']   = $member->member_id;
    		$data['seller_id']   = $member->seller_id;
    		$data['seller_name'] = $member->seller_name;
    		$data['seller_mobile'] = $member->member_mobile;
    		
    		// 保存session
    		$this->user->saveUserSession($data);
    	}
    	return json_encode(array('error_code'=>1,'error_msg'=>'验证成功'));
    }
    
    
    

}
