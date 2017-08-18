<?php namespace App\Http\Controllers\Front;

/**
 * 找回密码
 */

use App\Http\Controllers\Controller;

use Redirect;
use Request;
use App\Models\HgUser;
use Hash;
use Session;
use Input;

use App\Core\Contracts\Sms\Sms;

class PasswordController extends Controller {

    public function __construct()
    {
        /**
         * 检查会员是否登陆
         * 没有登陆跳转到登陆页面
         */

    }

    // 选择找回密码方式
    public function getpwd()
    {
       return view('user.pwd')->withTitle('选择找回密码方式');
    }

    // 通过手机号找回密码
    public function getpwdbyphone()
    {
       return view('user.pwd_phone1')->withTitle('通过手机找回密码');
    }

    // 发送手机验证码
    public function sendMobileCode(Sms $sms, $tel){
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

    public function getpwdbyphone2()
    {
       return view('user.pwd_phone2')->withTitle('通过手机找回密码');
    }

    public function getpwdbyphone3()
    {
        if(!session('reg_mobile')) exit("来路不明");
        $affectedRows = HgUser::where('member_mobile', '=', session('reg_mobile'))->update(['member_passwd' => bcrypt(Request::input('pwd'))]);
        if(!$affectedRows) exit('设置密码出错');
        Session::forget('reg_mobile');
        Session::forget('reg_code');
       return view('user.pwd_phone3')->withTitle('通过手机找回密码');
    }

    // 通过email找回密码
    public function getpwdbyemail()
    {
       return view('user.pwd_email1')->withTitle('通过邮箱找回密码');
    }

    public function getpwdbyemail2($email,$c)
    {

         // 检测邮箱是否注册过
        $user=HgUser::where('member_email', '=', $email)->first();
        if(!$user){
            return "邮箱地址错误";
        }

        if (md5(substr($email,3))!==$c) {
            exit("请从邮箱里面点击链接");
        }
       return view('user.pwd_email2')->withTitle('通过邮箱找回密码')->withEmail($email);
    }

    public function getpwdbyemail3()
    {
       $affectedRows = HgUser::where('member_email', '=', Request::input('email'))->update(['member_passwd' => bcrypt(Request::input('pwd'))]);
        if(!$affectedRows) eixt('设置密码出错');
       return view('user.pwd_email3')->withTitle('通过邮箱找回密码');
    }

    // 发送邮件
    public function sendEmail($email)
    {
        // 检测邮箱是否注册过
        $user=HgUser::where('member_email', '=', $email)->first();
        if(!$user){
            return json_encode(array('error_code'=>1,'error_msg'=>'邮箱未被注册过'));
        }

       // 产生一个hash吗同email一起发到邮箱作为设置密码的链接参数
        $c=md5(substr($email, 3));
        // 用接口发送
        return json_encode(array('error_code'=>0,'error_msg'=>'验证邮件已发送到邮箱'));
    }

    // 发送邮件成功提示页面
    public function sendEmailSuccess(){
        return view('user.sm_success')->withTitle('通过邮箱找回密码');
    }

}
