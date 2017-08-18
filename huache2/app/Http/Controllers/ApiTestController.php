<?php

namespace App\Http\Controllers;

use App\Models\HcDailiAccount;
use App\Models\SendSmsLog;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Com\Hwache\Jiaxinbao\Account;
use Carbon\Carbon;
use App\Models\HgBaojia;
class ApiTestController extends Controller
{
    //use AuthenticatesUsers;
    protected $user,$jxb;
    protected $msgLog;
    public function __construct(Account $jxb)
    {
        $this->user   = new User();
        $this->msgLog = new SendSmsLog();
        $this->jxb = $jxb;
    }
    /**
     * 判断用户账号号是否存在
     */
    public function getCheckUser(Request $request,$phone=null){
        static $checkMail = 0;
        $phone = is_null($phone) ? $request->input('phone','') : $phone;
        $template_code = $request->get('template_code');
        if(!is_phone($phone) && !is_email($phone)){
            return setJsonMsg(0,'验证对象格式错误！');
        }else{
            $checkData = is_phone($phone) ? ['phone'=>$phone] : ['email'=>$phone] ;
            $isUser = User::checkUser($checkData);
            if(is_phone($phone)){
                $sendCount = $this->msgLog->isDayCheckMsg($phone, $template_code);
                $push = ['send_count' => env('SEND_SMS_MAX') - $sendCount];
                return ($isUser) ? setJsonMsg(2 , '手机号已存在！' , $push) : setJsonMsg(1,'手机号可以使用！', $push);
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
        $max           = $request->input('max',env('SEND_SMS_MAX'));
        $template_code = $request->get('template_code');
        $option        = $this->msgLog ->getOption($request);
        if(!$template_code){
            return setJsonMsg(0,'短信模板编号丢失');
        }
        if(!is_phone($phone)){
            return setJsonMsg(0,'手机号码格式错误！');exit;
        }else {
            //检测发送次数
            $isSend = $this->msgLog->isDayCheckMsg($phone, $template_code);
            if($isSend >= $max){
                return setJsonMsg(0,'您今天的验证次数已超过上限！',['count'=>$isSend+1]);
            }else{
                $isMsg = $this->msgLog->sendSms($phone, $template_code ,$option);
                if($isMsg){
                    return setJsonMsg(1,'短信发送成功！',['count'=>$isSend+1]);
                }else{
                    return setJsonMsg(0,'短信发送失败！',['count'=>$isSend+1]);
                }
            }
        }
    }

    /**
     * 后台调用jxb 操作接口
     * @param Request $request
     */
    public function jxbArbitrate(Request $request)
    {
        $order_id = $request->get('order_id',0);
        $orderId = (int) $order_id;
        if($orderId){
            $res = $this->jxb ->removeUserJxb($orderId);
            if ($res) {
                return setJsonMsg(1, '加信宝操作成功！');exit;
            } else {
                return setJsonMsg(0, '加信宝操作失败！');exit;
            }
        }
    }

    public function test(){
//        $hc_daili_account = HcDailiAccount::where('d_id',105)->first();
////        $hc_daili_account->credit_line       =  '0';
//        $hc_daili_account->avaliable_deposit = -200;
//        $hc_daili_account->save();   
    }
}
