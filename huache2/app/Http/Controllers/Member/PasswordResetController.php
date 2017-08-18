<?php
namespace App\Http\Controllers\Member;

use App\Com\Hwache\User\Freeze;
use App\Models\SendSmsLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use App\Com\Auth\AnswerQuestions;
use App\Models\HgOrder;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo           = '/';//密码修改成功后跳转的页面
    protected $msgLog;
    const PWD_TEMPLATE_CODE         = '78575070';//找回密码短信模板类型
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


    use ResetsPasswords,AnswerQuestions;
    //use ResetPhonePasswords;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->msgLog = new SendSmsLog();
    }
    /**
     * 找回密码
     * @param Request $request
     * @return type
     */
    public function getPasswrod(\App\Http\Requests\SetSession $request){
        if($request->isMethod('post')){
            $phone = $request->get('phone','');
            $valicode = $request->get('code','');
            if(!checkSeccode($valicode)){
                return setJsonMsg(0,'验证码错误！');exit;
            }
            if(is_phone($phone) || is_email($phone)){
                $setUrl = is_phone($phone) ? route('pwd.showPassword') : route('mail.showMail') ;
                //删除密码找回
                if(is_email($phone)){
                    $request-> delData($phone.'sendEmail2');//清空发送次数限制
                    $request-> delData($phone.'EmailReset');//清空找回密码链接数据
                    $request-> delData($phone.'AnswerEmail');//清空密码找回错误
                    Freeze::passwordResetFreeze($phone,$request,3,0,'PasswordReset','找回密码三轮失败，功能冻结30分钟！',3000);
                }else{
                    //清空短信发送限制
                    $sessionName = $phone . '785750702';
                    $request -> delData($sessionName);
                    $request -> delData($phone.'AnswerMobile');//清空密码找回错误
                }
                $isFreeze = Freeze::isPwdResetFreeze($phone);
                $isStatus = ($isFreeze > 0) ? 0 : 1;
                //获取用户是否设置了防骚扰
                setRedis(['name'=>$phone,'isName'=>($isFreeze != 1),'status'=>$isStatus],null,self::REDIS_SESSION_PASSWORD);
                return setJsonMsg(1 , '成功' , ['url'=>$setUrl]);
            }else{
                return setJsonMsg(0,'手机号或着邮箱格式错误！');
            }
        }else{
            setRedis(null,null,self::REDIS_SESSION_PASSWORD,true);
            return view('auth.pwds.password',['title'=>'找回密码']);
        }        
    }

    /**
     * 超时三轮冻结
     * @param \App\Http\Requests\SetSession $request
     */
    public function timeOutFreeze(\App\Http\Requests\SetSession $request)
    {
        $phone = $request->get('phone');
        Freeze::passwordResetFreeze($phone,$request,3,0,'PasswordReset','找回密码三轮失败，功能冻结30分钟！',3000);
    }
    /**
     * 判断输入并根据条件显示页面
     * @param type $type
     * @return type
     */
    public function showPassword(\App\Http\Requests\SetSession $request){
        $pssswordSet  = setCookieRedis('get',['prefix'=>self::REDIS_SESSION_PASSWORD]);
        $isName       = intval($pssswordSet['isName']);
        $data['name'] = $pssswordSet['name'];
        //账号存在
        if($isName){
            $isDj = Freeze::isPwdResetFreeze($data['name']);
            if($isDj > 0){//账号异常
                $data['error_code']    = 1;
                $data['error_status']  = $isDj;
                $email = User::where(['phone'=>$pssswordSet['name']])->value('email');
                if($email){
                    //判断邮箱是否冻结
                    $isFreezeEmail = Freeze::isPwdResetFreeze($email);
                    $email = $isFreezeEmail ? '' : changeEmail($email);
                }
                $data['email'] = $email;
            }else{
                //用户账号存在并且状态正常【发送短信】
                //清除之前的短信设置
                setRedis(null,$pssswordSet['name'],self::REDIS_SEND_SMS_KEY,true);
                setRedis(null,$pssswordSet['name'],self::REDIS_VERIFY_KEY,true);
                //发送验证短信
                $resSendSms = $this->sendSms($pssswordSet['name']);
                if($resSendSms['success'] ==1){
                    //记录发送次数
                    $sessionName = $pssswordSet['name'] . '785750702';
                    $endTime =  Carbon::now()->addMinute(30)->toDateTimeString();
                    $request->checkSessionFreeze($sessionName, 2, $endTime, Freeze::setRegisterErrorCode(2));
                    return redirect()->route('pwd.pwdSendSms');
                }else{
                    return redirect()->route('pwd.showResetForm');
                }
            }
        }else{ 
           $data['error_code']    = 2;
        }
        return view('auth.pwds.phone_seep',['data'=>$data])->with(['title'=>'找回密码']);
    }
    /**
     * 短信发送成功
     */
    public function sendSuccess(\App\Http\Requests\SetSession $request){
        $pssswordSet = setCookieRedis('get',['prefix'=>self::REDIS_SESSION_PASSWORD]);
        if(is_null($pssswordSet)){
            return redirect()->route('pwd.pwdOver');
        }

        $code = $this->verifPassword($pssswordSet['name'],'click');
        if($code['error_code'] ==1){
            $typeStr = is_phone($pssswordSet['name']) ? 'mobile' : 'email';
            $view = ($typeStr =='mobile') ? 'auth.pwds.phone_seep' : 'auth.pwds.mail_seep';
            return view($view,['data'=>$code,'title'=>'验证短信错误']);
        }else{
            //获取短信发送成功的设置
            $sendData = setCookieRedis('get',['key'=>$pssswordSet['name'],'prefix'=>self::REDIS_SEND_SMS_KEY]);

            $email = User::where(['phone'=>$pssswordSet['name']])->value('email');
            $resultData = $this->getTimeOut($sendData['end_date'],['name'=>$pssswordSet['name']]);
            $resultData['email'] = $email ? changeEmail($email) : $email;

            $sendCode = $request->getData($pssswordSet['name'].'checkSmsCode');
            $isFreeze = Freeze::isPwdResetFreeze($pssswordSet['name']);
            $resultData['count']     = is_null($sendCode) ? 0 :$sendCode['click'];
            $resultData['errorCode'] = Freeze::setPwdResetErrorCode($isFreeze);
            $view = 'auth.pwds.verify_mobile';
            return view($view,['data'=>$resultData,'title'=>'验证短信']);
        }
    }

    /**
     * 验证手机短信
     */
    public function getResetCode(\App\Http\Requests\SetSession $request){
        $phone = $request->get('phone','');
        $code  = $request->get('code','');
        $template_code = $request->get('template_code');
        if(!$template_code){
            return setJsonMsg(0,'短信模板编号丢失');exit;
        }
        $isValidation = $this->msgLog -> VerifySms($phone,$template_code,$code);
        if(!$isValidation){
            return Freeze::passwordResetFreeze($phone,$request,10,20,'checkSmsCode');
        }else{
            //消除短信发送设置
            $request->delData($phone.'checkSmsCode');
            setRedis(null,$phone,self::REDIS_SEND_SMS_KEY,true);
            setRedis(null,$phone,self::REDIS_VERIFY_KEY,true);
            return setJsonMsg(1,'验证通过！');
        }
    }
    /**
     * 重置密码
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getResetForm(Request $request){
        //判断非法请求
        $pssswordSet = setCookieRedis('get',['prefix'=>self::REDIS_SESSION_PASSWORD]);
        if(is_null($pssswordSet)){
           return redirect()->route('pwd.pwdOver');exit;
        }
        //判断短信是否验证通过
        $isVerifySms = $this->msgLog->isVerifySms($pssswordSet['name'],self::PWD_TEMPLATE_CODE);
        if(empty($isVerifySms)){
            return redirect()->route('pwd.pwdOver');exit;
        }
        $passwrod_reset_time = setCookieRedis('get',['key'=>$pssswordSet['name'],'prefix'=>self::REDIS_RETSET_KEY]);
        if(is_null($passwrod_reset_time)){
            $time = time();
            $RdData['start_date'] = date('Y-m-d H:i:s',$time);
            $RdData['end_date']   = date('Y-m-d H:i:s',$time + self::PASSWORD_RESET_TIMES);
            setRedis($RdData,$pssswordSet['name'],self::REDIS_RETSET_KEY);
            $passwrod_reset_time = setCookieRedis('get',['key'=>$pssswordSet['name'],'prefix'=>self::REDIS_RETSET_KEY]);
        }
        $resultData =  $this->getTimeOut($passwrod_reset_time['end_date'],['phone'=>$pssswordSet['name']]);
        return view('auth.pwds.retset',['data'=>$resultData]);
    }
    /**
     * 密码错误页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function passwordOver(){
        $pssswordSet  = setCookieRedis('get',['prefix'=>self::REDIS_SESSION_PASSWORD]);
        if(!is_null($pssswordSet)){
            //判断三轮手机找密冻结
            if(is_phone($pssswordSet['name'])){
                $mobileFreeze = Freeze::isPwdResetFreeze($pssswordSet['name']);
                if($mobileFreeze > 0){
                    return redirect()->route('pwd.showPassword');
                }
            }
        }
        return view('auth.pwds.error');
    }
    /**
     * 密码重置成功
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPasswrodSuccess(){
        return view('auth.pwds.success');
    }
    /**
     * 验证找回密码的状态
     * @param $key
     * @param string $type
     * @return array|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function verifPassword($key,$type='all'){
        $type = 'click';
        switch($type) {
            case 'all'://验证整体超时

                break;
            case 'click'://验证密码输入错误
                $code = $this->getVerifCount($key);
                if(empty($code)){
                    return ['error_code' => 0, 'error_status' => 0];
                }
                if (($code % 10) == 0) {
                    //获取输入验证码错误的时间
                    $times = (new \App\Models\UserFreeze())->getStartTime($key);
                    if(time() - strtotime($times) < self::PADDWORD_LOCK_TIME){
                        return ['error_code' => 1, 'error_status' => 3];
                    }
                } else{
                    return ['error_code' => 0, 'error_status' => 0];
                }
                break;
        }
    }
    /**
     * 获取验证次数
     */
    private function getVerifCount($phone){
        $count = (new \App\Models\UserFreeze())->getClickNum($phone);
        //$count = setCookieRedis('get',['key'=>$phone,'prefix'=>self::REDIS_VERIFY_KEY,'getData'=>'num']);
        return intval($count);
    }
    /**
     * 获取倒计时数据
     */
    private function getTimeOut($endDate,$data=null,$startKey='start_date',$endKey='end_date'){
        $diyData[$startKey]  = date('Y-m-d H:i:s');
        $diyData[$endKey]    = $endDate;
        $diyData['thisData'] = ($diyData['end_date'] > $diyData[$startKey]) ?'true':'false';
        if(is_null($data)){
            return $diyData;
        }else{
            return is_array($data) ? array_merge($data , $diyData) : $diyData ;
        }
    }
    /**
     * 发送短信
     */
    private function sendSms($phone,$template_code='78575070')
    {
        //检测发送次数
        $isSend = $this->msgLog->isDayCheckMsg($phone, $template_code);
        if($isSend >= env('SEND_SMS_MAX') && env('APP_ENV') != 'local'){
            return setJsonMsg(0,'发送次数超过上限！',null,'send_max_error');
        }else{            
            $isSms = $this->msgLog ->sendSms($phone,$template_code,SendSmsLog::getCodeOption());
            if($isSms['error_code'] == 1){
                return setJsonMsg(0,'短信发送失败！',null,'send_sms_error');
            }else{
                $RdData['time']       = time();
                $RdData['start_date'] = date('Y-m-d H:i:s',$RdData['time']);
                $RdData['end_date']   = date('Y-m-d H:i:s',$RdData['time'] + self::PASSWORD_VERIFY_CODE_TIME);
                setRedis($RdData,$phone,self::REDIS_SEND_SMS_KEY);
                return setJsonMsg(1,'短信发送成功！');
            }
        }
    }

    public function testResetForm(){
        return view('auth.passwords.reset');
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(\App\Http\Requests\SetSession $request, $token = null)
    {
        $mails = setCookieRedis('get',['prefix'=>self::REDIS_SESSION_PASSWORD]);
        $requsetMail = $request->email;
        $email = (empty($requsetMail) || is_null($requsetMail)) ? $mails['name'] : $requsetMail;
        //保存邮箱内容用来下个页面读取数据
        $dataResult = getTimeOut(Carbon::now()->addMinute(10)->toDateTimeString(),['token' => $token, 'email' => $email]);
        //保存邮件找回密码数据
        $request->setData($email.'EmailReset',$dataResult);
        return redirect()->route('pwd.emailAnswer') ;
    }
    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        $this->validate($request, $this->rules(), $this->validationErrorMessages());
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );
        $response == Password::PASSWORD_RESET ? $this->sendResetResponse($response) : $this->sendResetFailedResponse($request, $response);

        if($response == 'passwords.reset'){
            setRedis(null,null,self::REDIS_SESSION_PASSWORD,true);
            setRedis(null,$request->email,self::REDIS_MAIL_ERROR_LOCK_TIME,true);
            return setJsonMsg(1,'密码重置成功！');
        }else{
            $ErrorArray =['passwords.token' => 'TOKEN 失效！','passwords.user'=>'用户错误！'];
            return setJsonMsg(0,'密码重置失败！'.$ErrorArray[$response]);
            //return setJsonMsg(0,$response);
        }
    }

    /**
     * Get the password reset credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        if($request->has('password_confirmation')){
            $res = $request->only(
                'email', 'password', 'password_confirmation', 'token'
            );
        }else{
            $res = $request->only(
                'email', 'password', 'token'
            );
            $res['password_confirmation'] = $request->get('password');
        }
       return $res;
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->forceFill([
            'password' => bcrypt($password),
            'remember_token' => Str::random(60),
        ])->save();
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
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];
    }
    /**
     * 获取用户订单
     * @param $map
     * @return mixed
     */
    protected function getUserOrder($map){
        $userId = User::getUserFind($map,'id',true);
        return HgOrder::getUserOrder($userId);
    }
}

