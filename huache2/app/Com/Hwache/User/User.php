<?php
/**
 * 用户中心模块
 *
 * 用户注册，登陆，资料设置等
 *
 * @author    李扬(Andy) <php360@qq.com>
 * @link      技安后院 http://www.moqifei.com
 * @copyright 苏州华车网路科技有限公司 http://www.hwache.com
 */
namespace App\Com\Hwache\User;

use DB;
use Hash;
use Mail;
use Crypt;
use App\Core\Contracts\Sms\Sms;

class User
{
    /**
     * 短信接口私有变量名
     * @var Sms
     */
    private $sms;

    /**
     * session会员数据key
     * @var string
     */
    public $userKey = 'user';

    /**
     * 构造函数
     * @param Sms $sms
     */
    public function __construct(Sms $sms)
    {
        $this->sms = $sms;
    }

    /**
     * 检测手机号是否已经注册
     * @param string $mobile 手机号码
     * @return mixed
     */
    public function checkMobile($mobile)
    {
        return DB::table('member')->where('member_mobile', $mobile)->count();
    }

    /**
     * 发送手机注册验证码
     * @param string $mobile 手机号码
     * @param int $code 验证码(4位整数)
     * @return bool 发送状态
     */
    public function sendRegMobileCode($mobile, $code)
    {
        return $this->sms->send(
            $mobile,
            json_encode([
                'code'    => $code,
                'product' => '华车',
            ]),
            [
                'sms_free_sign_name' => 'reg',
                'sms_template_code'  => 'reg',
            ]
        );
    }

    /**
     * 检测邮箱是否已经注册
     * @param string $email 邮箱
     * @return mixed
     */
    public function checkEmail($email)
    {
        return DB::table('member')->where('member_email', $email)->count();
    }

    /**
     * 发送邮件验证链接
     * @param $email
     * @return mixed
     */
    public function sendEmail($email,$do='reg')
    {
        $data = [
            'email' => $email,
            'time'  => time(),
        ];
		if($do=='reg'){
			$url = route('user.reg.verify_email', Crypt::encrypt($data));
			$subject = trans('common.verify.email');
			$tpl = '_layout.email';
		}elseif($do=='member_verify_email'){
			$data['verify'] = 1;
			$subject = trans('common.verify.email');
			$tpl = '_layout.email_verify';
			$url = url('/user/memberSafe/verify_email/'. Crypt::encrypt($data));
		}elseif($do=='change_phone_by_email'){
			$data['verify'] = 1;
			$subject = trans('common.verify.email_send_to_change_phone');
			$tpl = '_layout.email_send_to_change_phone';
			$url = url('/user/memberSafe/verify_email_to_change_phone/'. Crypt::encrypt($data));
		}elseif($do=='send_email_to_change_passwd'){
			$data['verify'] = 1;
			$subject = trans('common.verify.email_send_to_change_passwd');
			$tpl = '_layout.email_send_to_change_passwd';
			$url = url('/user/memberSafe/verify_change_passwd/'. Crypt::encrypt($data));
		}
		//邮件发送模板
        return Mail::send(
            $tpl,
            [
                'name'  => $email,
                'email' => $email,
                'url'   => $url,
            	'subject'=>$subject,
            ],
            function($m) use ($email,$subject)
            {
                $m->to($email)->subject('['.trans('common.www_title').'] '.$subject);
            }
        );
    }

    /**
     * 验证邮箱
     * @param $verify
     * @return array
     */
    public function verifyEmail($verify,$do='reg')
    {
        $data = Crypt::decrypt($verify);

        if ((config('mail.email_limit_time') * 60 + $data['time']) >= time()) {
            if($do=='member_verify_email' || $do=='change_phone_by_email' || $do == 'send_email_to_change_passwd'){
	        	return [
	                'success' => true,
	                'email'   => $data['email'],
	        		'verify'=>$data['verify'],
	            ];
            }else{
            	return [
            			'success' => true,
            			'email'   => $data['email'],
            	];
            }
        }

        return ['success' => false];
    }

    /**
     * 添加用户信息并返回主键ID
     * @param array $data
     * @return mixed
     */
    public function addData(array $data)
    {
        $member_id = DB::table('member')->insertGetId($data);
        //add by jerry
        $verifyData = array("member_id"=>$member_id,"mobile_verify"=>2);
        DB::table('member_verify')->insertGetId($verifyData);
        return $member_id;
    }

    /**
     * 登录系统
     * @param string $account 帐号(手机/邮箱)
     * @param string $password 密码
     * @return mixed
     */
    public function login($account, $password)
    {
        $member = DB::table('member');

        if (filter_var($account, FILTER_VALIDATE_EMAIL)) {
            // Email
            $member = $member->where('member_email', $account)->first();
        } else {
            // Mobile
            $member = $member->where('member_mobile', $account)->first();
        }

        // validator password
        if (!empty($member) && Hash::check($password, $member->member_passwd)) {
            return $member;
        }

        return false;
    }

    /**
     * 退出系统
     */
    public function logout()
    {
        // 原先会员系统
        session_unset();
        session_destroy();
        // 新会员系统
        session()->flush();
    }

    /**
     * 保存用户信息session
     * @param $data
     */
    public function saveUserSession($data)
    {
        // 原会员系统
        $_SESSION = $data;
        // 会员系统
        session()->put($this->userKey, $data);
    }

    /**
     * 检测用户登陆状态
     * @return bool
     */
    public function checkUserStatus()
    {	if($this->userKey==''){
    		$this->userKey = 'user';
    	}
        if (session($this->userKey.'.is_login') && session($this->userKey.'.user_type')==1) {
            return true;
        }

        return false;
    }
    
    /**
     * 检测用户登陆状态
     * @return bool
     */
    public function checkDealerStatus()
    {    	
    	if (session($this->userKey.'.is_login') && session($this->userKey.'.user_type')==2) {
    		return true;
    	}
    
    	return false;
    }
    

    /**
     * 更新登陆用户的上次登陆信息，并增加登陆次数
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function updateUserInfo($id, array $data)
    {
        return DB::table('member')->where('member_id', $id)->increment('member_login_num', 1, $data);
    }

}
