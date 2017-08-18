<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Str;
use App\User;

class MobilePwdResetController extends Controller
{
    use ResetsPasswords;
    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo          = '/';//密码修改成功后跳转的页面
    protected $msgLog;
    const PWD_MSG                   = 'status';//找回密码短信模板类型
    const CACHE_PWD                 = 'change_pwd';// 修改密码验证码模板类型
    const REDIS_SESSION_PASSWORD    = 'PASSWORD:LifeTime';//找回密码的生命周期存储
    const REDIS_SEND_SMS_KEY        = 'PASSWORD:SendSmsTime';//发送短信倒计时存储
    const REDIS_VERIFY_KEY          = 'PASSWORD:VerifyCode';//验证短信存储
    const REDIS_RETSET_KEY          = 'PASSWORD:Retset';//重置密码存储
    const REDIS_ERROR_LOCK_TIME     = 'PASSWORD:ErrorLock';//密码验证错误锁定时间存储

    const REDIS_MAIL_ERROR_LOCK_TIME = 'EMAILL:ErrorLock';//密码验证错误锁定时间存储
    const PASSWORD_VERIFY_CODE_TIME = 20*60;//验证短信的生命周期 20分钟
    const PASSWORD_RESET_TIMES      = 5*60;//重置密码的生命周期 5分钟
    const PADDWORD_LOCK_TIME        = 30*60;//密码输入错误的锁定时间30分钟
    const ERROR_MAX_SEEP            = 10;//验证总次数的步长
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request){
        $this->validate($request, $this->rules(), $this->validationErrorMessages());

        $phone    = $request->get('phone');
        $password = $request->get('password');
        $data = [
            'password' => bcrypt($password),
            'remember_token' => Str::random(60)
        ];
        $isPwd = User::where('phone','=',$phone)->update($data);
        if($isPwd){
            setRedis(null,$phone,self::REDIS_SESSION_PASSWORD,true);
            setRedis(null,$phone,self::REDIS_RETSET_KEY,true);
            setRedis(null,$phone,self::REDIS_SEND_SMS_KEY,true);
            setRedis(null,$phone,self::REDIS_VERIFY_KEY,true);
            return setJsonMsg(1,'密码重置成功！');
        }
        return setJsonMsg(0,'密码重置失败！');
    }
    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'phone' => 'required|mobile',
            'password' => 'required|min:6',
        ];
    }
    /**
     * Get the password reset credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only(
            'phone', 'password', 'password_confirmation'
        );
    }
}
