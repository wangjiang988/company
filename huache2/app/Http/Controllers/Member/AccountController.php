<?php

namespace App\Http\Controllers\Member;

use App\Models\SendSmsLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserResetMail;
use Illuminate\Support\Facades\Cache;

class AccountController extends Controller
{
    const NAV_STR = 'safe';
    const SMS_TYPE = 'update_pass';
    protected $SmS;

    const SMS_PWD_RESET_SUCCESS_CODE = '78560077';//修改密码成功
    const SMS_ADD_NEW_EMSIL_SMS_COEE = '78650062';//新增绑定邮箱
    public function __construct()
    {
        $this->middleware('auth',['except' => 'pwdEnd']);
        $this->SmS = new SendSmsLog();
    }
    /**
     * 安全中心首页
     * @param Request $request
     * @return type
     */
    public function getIndex(Request $request)
    {
        $userInfo = User::getUserHomeInfo($request->user()->id);
        $num = 2;
        if($userInfo->email)
            $num++;

        if($userInfo->is_id_verify ==1)
            $num++;
        $userInfo->authNum = $num;
        $data=['title'=>'安全管理','nav'=>self::NAV_STR,'user'=>$userInfo];
        return view('HomeV2.User.Auth.index')->with($data);
    }
    /**
     * 手机找回密码-验证手机
     * @param Request $request
     * @return type
     */
    public function getMobile(Request $request){
        $userInfo = User::getUserHomeInfo($request->user()->id);
        $data = ['title'=>'修改密码-验证手机','nav'=>self::NAV_STR,'user'=>$userInfo];
        return view('HomeV2.User.Auth.mobile')->with($data);
    }
    /**
     * 邮箱找回密码-验证邮箱
     * @param Request $request
     * @return type
     */
    public function getEmail(Request $request){
        $userInfo = User::getUserHomeInfo($request->user()->id);
        $data = ['title'=>'修改密码-验证邮箱','nav'=>self::NAV_STR,'user'=>$userInfo];
        return view('HomeV2.User.Auth.email')->with($data);
    }
    /**
     * 修改密码
     * @param Request $request
     * @return type
     */
    public function pwdReset(Request $request){
        if($request->isMethod('post')){
            $old_passowrd = $request->input('old_password');
            $password     = $request->input('password');
            //验证旧密码
            $db_password = User::where(['id'=>$request->user()->id])->value('password');
            $isPassword  = Hash::check($old_passowrd,$db_password);
            if(!$isPassword){
                $num = setCookieRedis('get',['key'=>$request->user()->id,'prefix'=>'account','getData'=>'num']);
                $clickNum = is_null($num) ? 1 : $num+1;
                setRedis($clickNum , $request->user()->id , 'account');
                return setJsonMsg(2, '旧密码错误！',['count'=>$clickNum]);
            }
            $data = [
                'password'       => bcrypt($password),
                'remember_token' => Str::random(60)
            ];
             //修改密码并退出登录
            $isPwd = User::where(['id'=>$request->user()->id])->update($data);
            if($isPwd){
                //发送短信
                $this->SmS ->sendSms($request->user()->phone,self::SMS_PWD_RESET_SUCCESS_CODE);
                Auth::guard()->logout();
                $request->session()->flush();
                $request->session()->regenerate();
                return setJsonMsg(1,'密码重置成功！');
            }
            return setJsonMsg(0,'密码重置失败！');
        }else{
            //判断是否通过短信或者邮箱找回验证
            $type = $request->get('type','phone');
            $value = ($type=='phone') ? $request->user()->phone : $request->user()->email ;
            $isVerfiy = $this->isVerfiySend($request->user()->id,$value,$type);
            if(!$isVerfiy){
               return redirect()->route('account.pwdEnd',['success'=>'verifiy']);
            }
            $userInfo = User::getUserHomeInfo($request->user()->id);
            $data = ['title'=>'修改密码-验证旧密码','nav'=>'safe','user'=>$userInfo,'type'=>$type];
            return view('HomeV2.User.Auth.password-reset')->with($data);
        }
    }
    /**
     * 发送邮件
     * @param Request $request
     * @return type
     */
    public function sendEmail(Request $request){
        if($request->isMethod('post')){
            $this->validate($request, ['email' => 'required|email']);
        }else{
           $isEmail = is_email($request->get('email'));
           if(!$isEmail){
               return setJsonMsg(0,'邮箱格式错误');
           }
        }
        $email = $request->get('email');
        $email_unique = $request->input('email-unique');
        //验证email是否唯一
        if(intval($email_unique) ==1){
            $isUserEmail = \App\User::where(['email'=>$email])->count();
            if($isUserEmail >0){
                return setJsonMsg(2,'邮箱已存在');
            }
        }
        $isDate = 1;
        $cacheName = 'userCacheEMail'.$request->user()->id;
        if(Cache::has($cacheName)){
            $data = Cache::get($cacheName);
            if($email == $data['email']){
                $this_date = time();
                $sendDate = isset($data['sendDate']) ? $data['sendDate'] : Carbon::now()->toDateTimeString();
                $date   = strtotime($sendDate);
                $isDate = (int) ($this_date-$date > 600);
            }else{
                $isDate = 1;
            }
        }
        if($isDate){
            Mail::to($email,'华车')->send(new UserResetMail($request->user(),$email));
            return setJsonMsg(1, '邮件发送成功',['isDate'=>$isDate]);
        }else{
            return setJsonMsg(0, '邮件发送失败！',['isDate'=>$isDate]);
        }
    }
    /**
     * 验证邮箱密码
     * @param Request $request
     * @return type
     */
    public function checkEmail(Request $request){
        if($request->isMethod('post')){
            $code = $request->input('code');
            $email = $request->user()->email;
            $isVerifiy = emailCacheCheck(['user_id'=>$request->user()->id,'code'=>$code,'email'=>$email,'type'=>'check']);
            return ($isVerifiy) ? setJsonMsg(1,'验证通过') : setJsonMsg(0,'验证码错误或过期');
        }else{
            $userInfo = User::getUserHomeInfo($request->user()->id);
            $data = ['title'=>'修改密码-验证邮箱','nav'=>'safe','user'=>$userInfo,'type'=>'email'];
            return view('HomeV2.User.Auth.checkmail')->with($data);
        }
    }

    /**
     * 找回密码结束
     * @param Request $request
     */
    public function pwdEnd(Request $request){
        $success = $request->get('success');
        switch($success){
           case 'ok':
               $temp = 'HomeV2.User.Auth.password-success';
               $title = "密码修改成功";
               break;
           case 'error':
               $temp = 'HomeV2.User.Auth.password-error';
               $title = '密码修改失败';
               break;
           case 'verifiy'://verifiy
               $temp = 'HomeV2.User.Auth.password-vefify';
               $title = '验证失败';
               break;
           default:
               $title ="";
               $temp = 'HomeV2.User.Auth.password-error';
        }
        return view($temp)->with(['nav'=>self::NAV_STR,'title'=>$title]);
    }
    /**
     * 验证是否验证
     * @param type $value
     * @param type $type
     * @return type
     */
    private function isVerfiySend($user_id,$value,$type='phone',$key='reset',$template_code='78585087'){
        switch($type){
            case 'phone':
                $res = $this->SmS->isVerifySms($value,$template_code);
                break;
            case 'email':
                $res = emailCacheCheck(['user_id'=>$user_id,'email'=>$value,'type'=>'verifiy'],$key);
                break;
            default:
                $res = false;
        }
        return $res;
    }
    /**
     * 添加邮箱-验证手机
     * @param Request $request
     * @return type
     */
    public function emailAdd(Request $request){
        $userInfo = User::getUserHomeInfo($request->user()->id);
        $data = ['title'=>'添加邮箱-验证手机','nav'=>self::NAV_STR,'user'=>$userInfo];
        return view('HomeV2.User.Account.mail-add-checkmobile')->with($data);
    }
    /**
     * 手机验证通过-新增邮箱
     * @param Request $request
     * @return type
     */
    public function verifySuccessAddEmail(Request $request){
        $userInfo = User::getUserHomeInfo($request->user()->id);
        //判断手机号是否验证
        $code = self::SMS_ADD_NEW_EMSIL_SMS_COEE;
        $isVerify = $this->isVerfiySend($request->user()->id, $request->user()->phone,'phone','reset',$code);
        if(!$isVerify){
            return redirect()->route('email.success-addEmail');
        }
        $data = ['title'=>'添加邮箱','nav'=>self::NAV_STR,'user'=>$userInfo];
        return view('HomeV2.User.Account.mail-add')->with($data);
    }
    /**
     *
     * @param Request $request
     * @return type
     */
    public function SendMailOk(Request $request){
        $cacheS   = emailCacheCheck(['user_id'=>$request->user()->id,'type'=>'get']);
        $userInfo = User::getUserHomeInfo($request->user()->id);
        $userInfo->email = $cacheS['email'];
        $data = ['title'=>'邮箱发送成功','nav'=>self::NAV_STR,'user'=>$userInfo];
        return view('HomeV2.User.Account.mail-add-send-ok')->with($data);
    }
    /**
     * 保存邮箱
     * @param Request $request
     * @return type
     */
    public function saveEmail(Request $request){
        if($request->isMethod('post')){
            $cacheS = emailCacheCheck(['user_id'=>$request->user()->id,'type'=>'get']);
            $email = $cacheS['email'];
            //验证邮箱
            $code = $request->input('code');
            $isVerifiy = emailCacheCheck(['user_id'=>$request->user()->id,'code'=>$code,'email'=>$email,'type'=>'check']);
            if(!$isVerifiy){
                return setJsonMsg(0,'验证码错误或过期');
            }

            $res = User::map(['id'=>$request->user()->id])->update(['email'=>$email]);
            if($res){
                //清除邮件发送设置
                Cache::forget('userCacheEMail'.$request->user()->id);
            }
            return ($res) ? setJsonMsg(1,'邮件设置成功') : setJsonMsg(0,'邮件设置失败') ;
        }
    }
    /**
     * 邮件添加成功
     * @return type
     */
    public function addEmailSuccess(Request $request){
        $userInfo = User::getUserHomeInfo($request->user()->id);
        $data = ['title'=>'添加邮箱成功','nav'=>self::NAV_STR,'user'=>$userInfo];
        return view('HomeV2.User.Account.mail-add-success')->with($data);
    }
    /**
     * 修改邮箱-验证手机
     * @param Request $request
     * @return type
     */
    public function upEmailSeep1(Request $request){
        $userInfo = User::getUserHomeInfo($request->user()->id);
        $data = ['title'=>'修改邮箱-验证手机','nav'=>self::NAV_STR,'user'=>$userInfo];
        return view('HomeV2.User.Account.mail-up-check')->with($data);
    }
    /**
     * 修改密码验证手机成功-显示发送email页面
     * @param Request $request
     */
    public function upEmailSeep2(Request $request){
        $userInfo = User::getUserHomeInfo($request->user()->id);
        $data = ['title'=>'修改邮箱-发送邮件','nav'=>self::NAV_STR,'user'=>$userInfo];
        return view('HomeV2.User.Account.mail-up')->with($data);
    }
    /**
     * 验证邮箱
     * @param Request $request
     * @return type
     */
     public function upEmailSeep3(Request $request){
        $userInfo = User::getUserHomeInfo($request->user()->id);
        $cacheName = 'userCacheEMail'.$request->user()->id;
        if(Cache::has($cacheName)){
            $data = Cache::get($cacheName);
            $this_date = time();
            $date = strtotime($data['sendDate']);            
            $userInfo->isDate = intval($this_date-$date > 600);
        }
        $data = ['title'=>'修改邮箱-验证邮箱','nav'=>self::NAV_STR,'user'=>$userInfo];
        return view('HomeV2.User.Account.mail-up-seep3')->with($data);
    }
    /**
     * 邮件验证通过添加新密码
     * @param Request $request
     * @return type
     */
     public function upEmailSeep4(Request $request){
         //判断邮件是否验证
        $isVerify = $this->isVerfiySend($request->user()->id, $request->user()->email,'email');
        if(!$isVerify){
            return redirect()->route('account.pwdEnd',['success'=>'verifiy']);
        }
        $userInfo = User::getUserHomeInfo($request->user()->id);
        $data = ['title'=>'修改邮箱-添加新邮件','nav'=>self::NAV_STR,'user'=>$userInfo];
        return view('HomeV2.User.Account.mail-up-seep4')->with($data);
    }
    /**
     * 新邮件发送成功验证
     * @param Request $request
     * @return type
     */
     public function upEmailSeep5(Request $request){
        $cacheS   = emailCacheCheck(['user_id'=>$request->user()->id,'type'=>'get']);
        $userInfo = User::getUserHomeInfo($request->user()->id);
        $userInfo->email = $cacheS['email'];
        $data = ['title'=>'邮箱发送成功-验证新邮箱','nav'=>self::NAV_STR,'user'=>$userInfo];
        return view('HomeV2.User.Account.mail-up-seep5')->with($data);
    }

    public function upEmailSeep6(Request $request){
        $userInfo = User::getUserHomeInfo($request->user()->id);
        $data = ['title'=>'修改邮箱-发送邮件','nav'=>self::NAV_STR,'user'=>$userInfo];
        return view('HomeV2.User.Account.mail-up-seep6')->with($data);
    }
    /**
     * 修改手机
     * @param Request $request
     * @return type
     */
    public function upMobileSeep1(Request $request){
        $userInfo = User::getUserHomeInfo($request->user()->id);
        $data = ['title'=>'修改邮箱-发送邮件','nav'=>self::NAV_STR,'user'=>$userInfo];
        return view('HomeV2.User.Account.mobile-up-seep1')->with($data);
    }
    public function upMobileSeep2(Request $request){
        $userInfo = User::getUserHomeInfo($request->user()->id);
        $data = ['title'=>'修改邮箱-发送邮件','nav'=>self::NAV_STR,'user'=>$userInfo];
        return view('HomeV2.User.Account.mobile-up-seep2')->with($data);
    }
    public function upMobileSeep3(Request $request){
        $userInfo = User::getUserHomeInfo($request->user()->id);
        $data = ['title'=>'修改邮箱-发送邮件','nav'=>self::NAV_STR,'user'=>$userInfo];
        return view('HomeV2.User.Account.mobile-up-seep3')->with($data);
    }
    /**
     * 新手机
     * @param Request $request
     * @return type
     */
    public function upMobileSeep4(Request $request){
        $user_id = $request->user()->id;
        if($request->isMethod('post')){
            $this->validate($request, $this->rules(),['手机号码格式错误！','验证码不能为空！','短信模板必须！']);
            $phone = $request->input('phone');
            //判断手机号是否存在
            $isPhone = User::where(['phone'=>$phone])->count();
            if($isPhone){
                return setJsonMsg(2,'手机号已存在');
            }
            $code           = $request->input('code');
            $template_code  = $request->input('template_code');
            //验证手机密码
            $max   = $request->input('max',env('SEND_SMS_MAX'));
            $isValidation = $this->SmS -> VerifySms($phone,$template_code,$code);
            if(!$isValidation){
                $sendCount = $this->SmS ->isDayCheckMsg($phone, $template_code);
                $push = ['count' => $max - $sendCount];
                return setJsonMsg(0,'短信验证失败！',$push);
            }else{
                //更新手机号
                $res = User::map(['id'=>$user_id])->update(['phone'=>$phone]);
                return ($res) ? setJsonMsg(1,'手机修改成功！') : setJsonMsg(4,'手机修改失败！');
            }
        }else{
            $userInfo = User::getUserHomeInfo($user_id);
            $data = ['title'=>'修改邮箱-发送邮件','nav'=>self::NAV_STR,'user'=>$userInfo];
            return view('HomeV2.User.Account.mobile-up-seep4')->with($data);
        }
    }

    private function rules(){
       return [
           'phone'=>'required|mobile',
           'code'=>'required|numeric|length:6',
           'template_code'=>'required|numeric'
       ];
    }
    /**
     * 修改手机成功
     * @param Request $request
     * @return type
     */
    public function upMobileSeep5(Request $request){
        $userInfo = User::getUserHomeInfo($request->user()->id);
        $data = ['title'=>'修改邮箱-发送邮件','nav'=>self::NAV_STR,'user'=>$userInfo];
        return view('HomeV2.User.Account.mobile-up-seep5')->with($data);
    }
    /**
     * 修改手机失败
     * @param Request $request
     * @return type
     */
    public function upMobileSeep6(Request $request){
        $userInfo = User::getUserHomeInfo($request->user()->id);
        $data = ['title'=>'修改邮箱-发送邮件','nav'=>self::NAV_STR,'user'=>$userInfo];
        return view('HomeV2.User.Account.mobile-up-seep6')->with($data);
    }
}
