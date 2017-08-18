<?php

namespace App\Com\Hwache\User;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\UserFreeze;
use App\User;
use App\Http\Requests\SetSession;
use App\Models\SendSmsLog;

use Illuminate\Support\Facades\Mail;
use App\Mail\UserResetMail;

/**
 * 登录注册的冻结管理
 * Class Freeze
 * @package App\Com\Hwache\User
 */
class Freeze
{
    const LOGIN_VALIDITY_TIME    = 1200;//暂时登录冻结时长默认为20分钟
    const PWDRESET_VALIDITY_TIME = 1800;//重置密码冻结默认时长未30分钟
    const LOGIN_FREEZE           = 'dj';//登录冻结
    const REGISTER_FREEZE        = 'reg_dj';//注册冻结
    const PWD_RESET_FREEZE       = 'pwd_dj';//找回密码冻结

    const REG_MSG            = '78605066';//注册短信
    const PWD_MSG            = '78575070';//找回密码短信
    const LOGIN_MSG          = '78555066';//登录冻结20分钟
    const LOGIN_DAY_MSG      = '78740070';// 登录冻结一天
    const PWD_RESET_CONFIRM  = '78575070';// 找回密码确认
    const PWD_RESET_MOBILE_FREEZE  = '78725076';// 找回密码手机冻结30分钟
    const PWD_RESET_EMAIL_FREEZE   = '78760083';// 找回密码邮箱冻结30分钟

    /**
     * 发送冻结短信
     * @param $name
     * @param string $type
     * @param bool $toDay
     * @return array|bool
     */
    public static function sendSms($name,$type=self::LOGIN_FREEZE,$toDay=false)
    {
        $user = User::where(['email'=>$name])->orWhere(['phone'=>$name])->first();
        $option = null;
        switch($type){
            case self::LOGIN_FREEZE:
                $code = $toDay ? self::LOGIN_DAY_MSG : self::LOGIN_MSG;
                break;
            case self::PWD_RESET_FREEZE:
                $code   =  is_phone($name) ? self::PWD_RESET_MOBILE_FREEZE : self::PWD_RESET_EMAIL_FREEZE;
                $option = is_email($name) ? ['mail'=>$name] : null;
                break;
        };
        return (new SendSmsLog())->sendSms($user->phone,$code,$option);
    }

    /**
     * todo 发送冻结邮件(需要提供邮件模板)
     */
    public static function sendEmail(User $user,$email=null)
    {
        return Mail::to($email,'华车')->send(new UserResetMail($user,$email));
    }
    /**
     * @param $phone        账号名称（手机号、邮箱）
     * @param $request      sessionRequest 对象
     * @param int $max      最大值
     * @param int $minute   多少分钟内有效
     * @param string $suffix  session后缀名
     * @param string $errorMsg 错误提示
     * @return array
     */
    public static function passwordResetFreeze($phone,SetSession $request,$max=10,$minute=20,$suffix='',$errorMsg='短信验证失败！',$errorCode='4000',$sessionAdd=true)
    {
        $click = 0;
        //第一步，判断用户状态、防骚扰状态、冻结状态
        $isPwdRreeze = self::isPwdResetFreeze($phone);
        if($minute >0){
            //第二步，如果找密异常直接抛出错误
            if($isPwdRreeze >0){
                $_errorCode = self::setPwdResetErrorCode($isPwdRreeze);
                return setJsonMsg(0,$errorMsg,['count' => $max , 'isFreeze'=>$isPwdRreeze],$_errorCode);
            }
        }
        if($sessionAdd){
            $addTime = ($minute >0) ? Carbon::now()->addMinute($minute)->toDateTimeString() : $request->getEndDay();
            //第三步，如果找密正常|记录找回密码次数如果30分钟内
            $isCheckMax = $request->checkSessionFreeze($phone.$suffix,$max,$addTime,$errorCode);
            if(!is_null($isCheckMax)){
                self::sendSms($phone,self::PWD_RESET_FREEZE);//发送冻结短信
                self::setPwdResetFreeze($phone);//冻结找回密码功能30分钟
            }
        }

        $sessionData = $request->getData($phone.$suffix);
        $click = is_null($sessionData) ? 1 : $sessionData['click'];
        //如果没有错误 session 次数大于等于max 清空session
        if(empty($isPwdRreeze)){
            if($click >= $max){
                $request->delData($phone.$suffix);
            }
        }
        return setJsonMsg(0,$errorMsg,['count' => $click  , 'isFreeze'=>$isPwdRreeze],$errorCode);
    }

    /**
     * 查看冻结状态
     * @param $name
     * @return mixed
     */
    public static function getPwdResetStatus($name,$type=self::PWD_RESET_FREEZE)
    {
        return (new UserFreeze())->getStatus($name,0,$type);
    }
    /**
     * 查看找回密码的冻结状态
     * @param $name
     * @return bool
     */
    public static function isPwdResetFreeze($name)
    {
        //查看用户是否存在
        $map    = is_phone($name) ? ['phone' => $name , 'status'=>1] : ['email' => $name,'status'=>1];
        $isUser = User::checkUser($map);
        if(!$isUser){
            return 1;
        }
        //查看用户是否设置防骚扰
        $isFSR = UserFreeze::where(['value'=>$name,'status'=>1,'activated'=>1,'type'=>'fr'])->first();
        if($isFSR){
            return 2;
        }
        //查看找回密码的冻结状态
        $isStatus = (new UserFreeze())->getStatus($name,0,self::PWD_RESET_FREEZE);
        if($isStatus){
            return 3;
        }
        return 0;
    }

    /**
     * 设置找回密码冻结
     * @param $name
     */
    public static function setPwdResetFreeze($name)
    {
        $isPwdFreeze = self::getFreezeFind($name,self::PWD_RESET_FREEZE);
        if(is_null($isPwdFreeze)){
            self::insertFreeze($name,self::PWD_RESET_FREEZE);
        }else{
            self::updateFreeze(self::PWD_RESET_FREEZE,$name,1,1);
        }
    }
    /**
     * 获取当天用户的冻结状态
     * @param $mobile
     * @param int $isMobile
     * @return mixed
     */
    public static function getLogTodayFreeze($mobile,$isMobile=0,$type=self::LOGIN_FREEZE,$max=20)
    {
        return DB::table('user_freeze')
            ->where(['value' => $mobile , 'is_mobile' => $isMobile, 'type' => $type , 'status'=>1])
            ->where('click_num','>=',$max)
            ->whereRaw("FROM_UNIXTIME(created_at,'%Y-%m-%d') = CURDATE()")
            ->count();
    }
    /**
     * 添加冻结记录
     * @param $name
     * @param string $type
     * @return mixed
     */
    public static function insertFreeze($name,$type=self::LOGIN_FREEZE,$num=1)
    {
        $isMobile = intval(is_phone($name));
        $isEmail  = intval(is_email($name));
        $where    = $isMobile ? ['phone'=>$name] : ['email' => $name] ;

        $user_id = ($type==self::LOGIN_FREEZE) ? DB::table('users')->where($where)->value('id') : 0;
        switch($type){
            case self::LOGIN_FREEZE:
                $remark = '当日登录冻结';
                $validity_time = self::LOGIN_VALIDITY_TIME;
                break;
            case self::REGISTER_FREEZE:
                $remark = '当日注册冻结';
                $validity_time = strtotime(date('Y-m-d').' 23:59:59') - time();
                break;
            case self::PWD_RESET_FREEZE:
                $remark = '当日找密码冻结';
                $validity_time = self::PWDRESET_VALIDITY_TIME;
                break;
        }
        $data=[
            'user_id'       => $user_id,
            'value'         => $name,
            'name'          => $remark,
            'type'          => $type,
            'is_mobile'     => $isMobile,
            'is_email'      => $isEmail,
            'status'        => 1,
            'validity_time' => $validity_time,
            'activated'     => 1,
            'click_num'     => $num,
            'created_at'    => Carbon::now()->toDateTimeString(),
            'updated_at'    => Carbon::now()->toDateTimeString()
        ];
        return DB::table('user_freeze')->insertGetId($data);
    }
    /**
     * 获取用户当前的冻结详情
     * @param $name
     * @param string $type
     * @return mixed
     */
    public static function getFreezeFind($name,$type=self::LOGIN_FREEZE)
    {
        $isMobile = intval(is_phone($name));
        return DB::table('user_freeze')->where(['value' => $name, 'is_mobile' => $isMobile, 'type' => $type])->first();
    }

    /**
     * 修改登录冻结状态或数据
     * @param $name
     * @param int $status
     * @param int $num
     * @return mixed
     */
    public static function upLoginFreeze($name,$status=1,$num=0)
    {
        $data = [];
        if($num ==19){
            $data['validity_time'] = strtotime(date('Y-m-d').' 23:59:59') - time();
        }
        return self::updateFreeze(self::LOGIN_FREEZE,$name,$status,$num,$data);
    }

    /**
     * 通用修改方法
     * @param $name
     * @param int $status
     * @param int $num
     * @param array $data
     * @return mixed
     */
    public static function updateFreeze($type,$name,$status=1,$num=0,$data=array())
    {
        $isMobile = intval(is_phone($name));
        $data['updated_at']    = ($status==1) ? \Carbon\Carbon::now()->toDateTimeString() : \Carbon\Carbon::now()->subHour(1)->toDateTimeString();
        $data['click_num']     = ($status==1) ? DB::raw("click_num+1") : $num;
        $data['status']        = $status;
        return DB::table('user_freeze')
            ->where(['value' => $name, 'is_mobile' => $isMobile, 'type' => $type])
            ->update($data);
    }
    /**
     * 修改注册冻结状态
     * @param $name
     * @param $num
     */
    public static function upRegisterFreeze($name,$status=1,$num=0)
    {
        return self::updateFreeze(self::REGISTER_FREEZE,$name,$status,$num);
    }
    /**
     * 用户登录冻结
     * @param $name
     * @param int $num
     * @return bool|mixed
     */
    public static function userFreeze($name,$num=0,$type=self::LOGIN_FREEZE,$max=20){
        $isMobile = intval(is_phone($name));
        //检查冻结账号
        $isFreeze = DB::table('user_freeze')
            ->where(['value'=>$name,'is_mobile'=>$isMobile,'type'=>$type])
            ->count();
        if($isFreeze) {
            //检查是否有当天冻结
            $isTodayFreeze = self::getLogTodayFreeze($name,$isMobile,$type,$max);
            if(!$isTodayFreeze){
                if($type == self::LOGIN_FREEZE)
                    $res = self::upLoginFreeze($name,1,$num);
                else
                    $res = self::upRegisterFreeze($name,1);
            }else
                $res = true;
        }else{
            $res = self::insertFreeze($name,$type);
        }
        return $res;
    }

    /**
     * 查看登录是否冻结
     * @param $LoginFreeze
     * @param $name
     * @return array|bool
     */
    public static function isLoginFreeze($LoginFreeze,$name)
    {
        $freeze = false;
        if(isset($LoginFreeze) && $LoginFreeze->status ==1){
            if ($LoginFreeze->click_num % 10 == 0 || $LoginFreeze->click_num >=20) {
                if((time() < strtotime($LoginFreeze->updated_at)+self::LOGIN_VALIDITY_TIME) && $LoginFreeze->click_num==10){
                    $freeze = true;
                }
                if($LoginFreeze->click_num >= 20){
                    if(Carbon::now()->toDateString() == Carbon::parse($LoginFreeze->updated_at)->toDateString()){
                        $freeze = true;
                    }else{//如果时间过期删除冻结
                        self::upLoginFreeze($name,0);
                    }
                }
            }
        }
        if($freeze){
            $_errorCode = self::setLoginErrorCode($LoginFreeze->click_num,$LoginFreeze->updated_at);
            return setJsonMsg(0,'用户名或密码错误.....！',['code'=>$LoginFreeze->click_num,'time'=>$LoginFreeze->updated_at],$_errorCode);
        }
        return $freeze;
    }

    /**
     * 判断注册是否冻结
     * @param $phone
     * @param int $max
     * @return array|bool
     */
    public static function isRegisterFreeze($phone,$max=5)
    {
        $registerFreeze = self::getFreezeFind($phone,self::REGISTER_FREEZE);
        if(is_null($registerFreeze)){
            self::insertFreeze($phone,self::REGISTER_FREEZE,0);
            return false;
        }
        $freeze = self::checkRegisterFreeze($phone,$max);
        if($freeze){
            return setJsonMsg(0,'短信发送次数超出！',
                ['code'=>$registerFreeze->click_num,'time'=>$registerFreeze->updated_at]
                ,self::setRegisterErrorCode(2));
        }
        return $freeze;
    }

    /** 检测注册冻结
     * @param $phone
     * @param $max
     * @return bool
     */
    public static function checkRegisterFreeze($phone,$max=5)
    {
        $freeze = false;
        $registerFreeze = self::getFreezeFind($phone,self::REGISTER_FREEZE);
        if($registerFreeze && $registerFreeze->status ==1){
            if(Carbon::now()->toDateString() == Carbon::parse($registerFreeze->updated_at)->toDateString()){
                if($registerFreeze->click_num >= $max){
                    $freeze = true;
                }
            }else{//如果时间过期删除冻结
                self::upRegisterFreeze($phone,0);
            }
        }
        return $freeze;
    }

    /**
     * 设置登录冻结的错误码
     * @param int $errorNum
     * @param null $dataTime
     * @return int|string
     */
    public static function setLoginErrorCode($errorNum=0,$dataTime=null){
        if(empty($errorNum)){
            return $errorNum;
        }
        switch($errorNum){
            case 10:
                if(time() >= strtotime($dataTime)+self::LOGIN_VALIDITY_TIME){
                    $errorCode = '1000';
                }else{
                    $errorCode = '1010';
                }
                break;
            case 20:
                $errorCode = '1020';
                break;
            case 8:
            case 9:
            case 18:
            case 19:
                $errorCode = '300'.(10 - ($errorNum % 10));
                break;
            default:
                $errorCode = ($errorNum % 10 >2) ? '1003' : '1000';
        }
        return $errorCode;
    }

    /**
     * 找回密码的错误编码
     * @param int $status
     * @return mixed
     */
    public static function setPwdResetErrorCode($status=0)
    {
        $codeError = [
            0 => '0000',//正常
            1 => '1000',//用户不存在
            2 => '2000',//找回密码设置防骚扰
            3 => '3000',//找回密码功能冻结30分钟
            4 => '4000'//验证码错误
        ];
        return $codeError[$status];
    }
    /**
     * 找回密码的错误编码
     * @param int $status
     * @return mixed
     */
    public static function setRegisterErrorCode($status=0)
    {
        $codeError = [
            0 => '0000',//正常
            1 => '1000',//用户已存在
            2 => '2000',//注册手机号当日冻结
            4 => '4000'//验证码错误
        ];
        return $codeError[$status];
    }
}
