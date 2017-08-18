<?php
/**
 * 用户中心公共处理类
 */

namespace App\Http\Controllers\Member;

use App\Com\Hwache\User\Freeze;
use App\Http\Controllers\Controller;
use App\Models\SendSmsLog;
use App\User;
use Illuminate\Http\Request;
class PublicController extends Controller
{
    //use AuthenticatesUsers;
    protected $user;
    protected $msgLog;

    const REG_MSG         = 'reg';//注册短信
    const PWD_MSG         = 'status';//找回密码短信
    const LOGIN_MSG       = 'login';//登录确认验证码
    const SEND_MAIL_MAX   = 5;

    //短信模板code
    const REG_CODE    ='78605066';

    public function __construct()
    {
        $this->user   = new User();
        $this->msgLog = new SendSmsLog();
    }

    /**
     * 判断用户账号号是否存在
     */
    public function getCheckUser(Request $request){
        static $checkMail = 0;
        $phone = $request->get('phone','');
        $template_code = $request->get('template_code','');

        if(!is_phone($phone) && !is_email($phone)){
            return setJsonMsg(0,'验证对象格式错误！');
        }else{
            $checkData = is_phone($phone) ? ['phone'=>$phone,'status'=>1] : ['email'=>$phone,'status'=>1] ;
            $isUser    = User::checkUser($checkData);
            if(is_phone($phone)){
                $sendCount = ($template_code) ? $this->msgLog->isDayCheckMsg($phone,$template_code) : 0;
                $push = ['send_count' => $sendCount];
                $error_code = 0;
                if($sendCount == 5) $error_code = 2000;
                return ($isUser) ? setJsonMsg(2 , '手机号已存在！' , $push,$error_code) : setJsonMsg(1,'手机号可以使用！', $push,$error_code);
            }else{
                $checkMail ++;
                $push = ['send_count' => 5];//self::SEND_MAIL_MAX - $checkMail
                return ($isUser) ? setJsonMsg(2 , '手机号已存在！' , $push) : setJsonMsg(1,'手机号可以使用！', $push);
            }
        }
    }
    /**
     * 发送短信
     */
    public function sendSms(\App\Http\Requests\SetSession $request)
    {
        $phone         = $request->get('phone','');
        if(!is_phone($phone)){
            return setJsonMsg(0,'手机号码格式错误！',null,'5000');
        }
        $type          = $request->get('type','');
        $template_code = $request->get('template_code');
        $option        = $this->msgLog ->getOption($request);
        $isCode        = $request->get('code',0);
        if(!$template_code){
            return setJsonMsg(0,'短信模板编号丢失');
        }
        $max     = $request->input('max',env('SEND_SMS_MAX'));
        $endTime = $request->input('endtime');
        //检查当天的发送总次数
        $isTotalSms =  $this->msgLog -> checkTodayTotalSms($phone,$isCode, $template_code);
        if(!is_null($isTotalSms)){
            return $isTotalSms;
        }
       if($max && $endTime) {
            if($type == self::REG_MSG){
                $isFreeze  = Freeze::isRegisterFreeze($phone,$max);
                if($isFreeze){
                    return $isFreeze;
                }else{
                    $regFreeze = Freeze::getFreezeFind($phone,'reg_dj');
                    empty($regFreeze->status) ? Freeze::upRegisterFreeze($phone,1) : Freeze::userFreeze($phone,1,'reg_dj',$max);
                }
                $isNewFreeze  = Freeze::isRegisterFreeze($phone,$max);
                if($isNewFreeze){
                    return $isNewFreeze;
                }
            }else {
                $sessionName = $phone . $template_code . $max;
                //判断是否发送冻结
                $isSmsFreeze = $request->checkSessionFreeze($sessionName, $max+1, $endTime, Freeze::setRegisterErrorCode(2));
                if (!is_null($isSmsFreeze)) {
                    return $isSmsFreeze;
                }
                $reultSend = $request->getData($sessionName);
                $sendNum   =  $reultSend['click'];
            }
        }

        if($this->msgLog ->isDayCheckMsg($phone, $template_code) >= $max){
            return setJsonMsg(0,'短信发送超出限制！',['count'=>$max],'5000');
        }

        $isMsg   = $this->msgLog->sendSms($phone, $template_code ,$option);
        $backNum = $this->msgLog ->isDayCheckMsg($phone, $template_code);
        $sendNum = isset($sendNum) ? $sendNum : $backNum;
        if($type == self::REG_MSG){//
            $sendNum = $sendNum % 6;
        }
        if($isMsg){
            return setJsonMsg(1,'短信发送成功！',['count'=>$sendNum],'4000');
        }else{
            return setJsonMsg(0,'短信发送失败！',['count'=>$sendNum],'4000');
        }
    }
    /** 检测短信
     * @param Request $request
     */
    public function checkSms(Request $request)
    {
        $phone = $request->get('phone','');
        $code  = $request->get('code','');
        if(empty($phone) || empty($code)){
            return setJsonMsg(0,'手机号或者验证码不能为空！');
        }
        $template_code = $request->get('template_code');
        if(!$template_code){
            return setJsonMsg(0,'短信模板编号丢失');exit;
        }
        $max   = $request->get('max',env('SEND_SMS_MAX'));
        $isValidation = $this->msgLog -> VerifySms($phone,$template_code,$code);
        if(!$isValidation){
            $sendCount = $this->msgLog->isDayCheckMsg($phone, $template_code);
            $push = ['count' => $max - $sendCount , 'click'=> $sendCount];
            return setJsonMsg(0,'短信验证失败！',$push);
        }else{
            return setJsonMsg(1,'验证通过！');
        }
    }
    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()    {
        return [
            'phone' => 'required|numeric|min:11',
            'code'  => 'required|numeric|max:6',
        ];
    }

    /**
     * 成功页面
     * @param Request $request
     * @return type
     */
    public function getSuccess(Request $request){
        $type = $request->get('type','reg');
        if($type == 'reg')
         return view('auth.success');
        else
         return view('auth.login_success');
    }
}
