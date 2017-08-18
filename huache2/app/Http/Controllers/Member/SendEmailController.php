<?php
namespace App\Http\Controllers\Member;
use App\Models\UserFreeze;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Com\Hwache\User\Freeze;
use Illuminate\Support\Facades\Cache;

class SendEmailController extends Controller
{
    //
    use SendsPasswordResetEmails;
    protected $redisCookie;
    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo           = '/';//密码修改成功后跳转的页面
    protected $answerCacheMail      = 'AnswerResetMailCache';
    const PWD_EMAIL_TEMP            = '';//找回密码邮件模板类型
    const REDIS_SESSION_PASSWORD    = 'PASSWORD:LifeTime';//找回密码的生命周期存储
    const REDIS_RETSET_KEY          = 'EMAILL:Retset';//重置密码存储
    const REDIS_ERROR_LOCK_TIME     = 'EMAILL:ErrorLock';//密码验证错误锁定时间存储
    const PASSWORD_VERIFY_CODE_TIME = 1200;//20*60;//邮件验证的生命周期 20分钟
    const PASSWORD_RESET_TIMES      = 300;//5*60;//重置密码的生命周期 5分钟
    const PADDWORD_LOCK_TIME        = 1800;//30*60;//密码输入错误的锁定时间30分钟
    const ERROR_MAX_SEEP            = 10;//验证总次数的步长
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        //存储状态
        $this->redisCookie = setCookieRedis('get',['prefix'=>self::REDIS_SESSION_PASSWORD]);
    }

    /**
     * 邮件找回页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showMailForm(){
        $pssswordSet = $this->redisCookie;
        if(is_null($pssswordSet)){
            return redirect()->route('pwd.showResetForm');
        }
        //判断邮箱是否设置了防骚扰
        $emailFreeze = Freeze::isPwdResetFreeze($pssswordSet['name']);
        if($emailFreeze){
            return redirect()->route('mail.isNull');
        }
        $data['name'] = $pssswordSet['name'];
        $view = 'auth.pwds.mail_seep';
        return view($view,['data'=>$data,'title'=>'确认邮箱']);
    }

    public function isEmailNull()
    {
        $pssswordSet = $this->redisCookie;
        if(is_null($pssswordSet)){
            return redirect()->route('pwd.showResetForm');exit;
        }
        $isName       = intval($pssswordSet['isName']);
        $data['name'] = $email = $pssswordSet['name'];
        $data['error_code']    = 2;
        $view = 'auth.pwds.mail_error';
        //账号存在
        if($isName){
            $emailFreeze = Freeze::isPwdResetFreeze($email);
            if($emailFreeze ==3){
                $users = \App\User::where(['email'=>$email])->first();
                $data['phone'] = ($users) ? $users->phone : '';

                $view = 'auth.pwds.email_freeze';
            }else{
                //账号异常
                $data['error_code']    = 1;
                $data['error_status']  = $emailFreeze;
            }
        }
        return view($view,['data'=>$data,'title'=>'邮箱找回密码']);
    }
    /**
     * 查收邮件
     */
    public function checkMail(\App\Http\Requests\SetSession $request){
        $mails = $this->redisCookie;
        if(is_null($mails)){
            return redirect()->route('pwd.showResetForm');
        }
        $data['email'] = $mails['name'];
        $users = \App\User::where($data)->first();
        $data['phone'] = ($users) ? $users->phone : '';
        //保存成功的时间，用来判断邮件时效信
        $dataTime   = setCookieRedis('get',['key'=>$data['email'],'prefix'=>self::REDIS_ERROR_LOCK_TIME]);
        $dataResult = getTimeOut($dataTime['end_date'],$data);
        return view('auth.pwds.email_success',['data'=>$dataResult,'title'=>'查收邮箱','sendMail'=>route('mail.mailForm')]);
    }
    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * ajax 重新发送邮件
     * @param \App\Http\Requests\SetSession $request
     * @return array|null
     */
    public function ajaxSendEmail(\App\Http\Requests\SetSession $request)
    {
        $email = $request->input('email');
        $this->validate($request, ['email' => 'required|email']);
        //重新发送邮件，判断次数
        if($request->ajax()){
            $max     = $request->input('max',1);
            $endTime = $request->input('endtime');
            if($max && $endTime) {
                $sessionName = $email . 'sendEmail' . $max;
                //判断是否发送冻结
                $isEmailFreeze = $request->checkSessionFreeze($sessionName,$max,$endTime);
                if (!is_null($isEmailFreeze)) {
                    return $isEmailFreeze;
                }
            }
            $response = $this->broker()->sendResetLink(
                $request->only('email')
            );
            if($response){
                return setJsonMsg(1,'邮件发送成功！');
            }
        }
    }
    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(\App\Http\Requests\SetSession $request)
    {
        $email = $request->input('email');
        $this->validate($request, ['email' => 'required|email']);
        //判断绑定邮箱
        $isUser = \App\User::where(['email'=>$email,'status'=>1])->count();
        if(empty($isUser)){
            return redirect()->route('mail.isNull');
        }else{
            //判断邮箱是否设置了防骚扰
            $emailFreeze = Freeze::isPwdResetFreeze($email);
            if($emailFreeze){
                return redirect()->route('mail.isNull');
            }
        }
        //清空缓存
        setRedis(null,$request->get('email'),self::REDIS_ERROR_LOCK_TIME,true);
        $data['start_date'] = Carbon::now()->toDateTimeString();
        $data['end_date']   = Carbon::now()->addMinute(20)->toDateTimeString();
        //发送邮件
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );
        if($response){
            //保存成功的时间，用来判断邮件时效信
            setRedis($data,$email,self::REDIS_ERROR_LOCK_TIME);
            return redirect()->route('mail.mailSuccess');
        }else{
            //邮件发送失败！
            return redirect()->route('mail.isNull');
        }
    }

    /**
     * 邮件充值密码
     * @param \App\Http\Requests\SetSession $request
     * @return $this
     */
    public function mailResetForm(\App\Http\Requests\SetSession $request)
    {
        $mails = setCookieRedis('get',['prefix'=>self::REDIS_SESSION_PASSWORD]);
        if(is_null($mails)){
            return redirect()->route('pwd.pwdOver');
        }
        $email = $mails['name'];
        $isFreeze = Freeze::isPwdResetFreeze($email);
        //判断冻结状态
        if($isFreeze >0){
            return redirect()->route('pwd.pwdOver');
        }
        $sessionData = $request->getData($email.'EmailReset');
        $data = getTimeOut($this->getResetSession($email),['token' => $sessionData['token'], 'email' => $sessionData['email']]);
        return view('auth.pwds.mail_reset')->with($data);
    }

    /**
     * 设置找回密码倒计时
     * @param $name
     * @return mixed
     */
    private function getResetSession($name){
        $sessionName = md5(get_client_ip().$name);
        if(!Cache::has($sessionName)){
            $data = Carbon::now()->addMinute(5)->toDateTimeString();
            Cache::put($sessionName,$data,7);
        }else{
            $data = Cache::get($sessionName);
        }
        return $data;
    }
}
