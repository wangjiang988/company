<?php

namespace App\Com\Auth;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserResetMail;
use App\Com\Hwache\User\Freeze;

trait AnswerQuestions
{
    protected $redisCache       = 'PASSWORD:LifeTime';//找回密码的生命周期存储
    protected $AnswerCache      = 'AnswerSession';
    protected $MaxTime          = 300;//回答问题5分钟
    protected $answerCacheMail  = 'AnswerResetMailCache';
    protected $redisErrorLockTime     = 'EMAILL:ErrorLock';//密码验证错误锁定时间存储
    protected $passwordVerifyCodeTime = 1200;//邮件验证的生命周期 20分钟

    /**
     * 邮箱找回密码-》回答问题
     * @param Request $request
     * @return $this
     */
    public function showEmailAnswerForm(Request $request)
    {
        $psswordSet  = setCookieRedis('get',['prefix'=>$this->redisCache]);
        if(is_null($psswordSet)){
            return redirect()->route('pwd.pwdOver');
        }
        $isOrder      = \App\Models\Users::isCheckOrder(['email'=>$psswordSet['name']]);
        $isUserVerify = \App\Models\Users::isIdVerify("email='{$psswordSet['name']}'");
        $data['isOrder'] = $isOrder;
        $data['isUserVerify'] = $isUserVerify;
        $data['title'] = '找回密码-回答问题';
        $data['name']  = $psswordSet['name'];
        $answerSes     = $this->getAnswerSession($psswordSet['name']);
        $data['start_date'] = Carbon::now()->toDateTimeString();
        $data['end_date']   = $answerSes['end_date'];

        return view('auth.answer.email')->with($data);
    }

    /**
     * 手机找回密码-》回答问题
     * @return $this
     */
    public function showMoblieAnswerForm(Request $request)
    {
        $psswordSet = setCookieRedis('get',['prefix'=>$this->redisCache]);
        if(is_null($psswordSet)){
            return redirect()->route('pwd.pwdOver');
        }
        $isOrder = \App\Models\Users::isCheckOrder(['phone'  => $psswordSet['name']]);
        $isUserVerify = \App\Models\Users::isIdVerify("phone='{$psswordSet['name']}'");

        if(!$isUserVerify && !$isOrder){
            return redirect()->route('pwd.getReset');exit;
        }
        $data['isOrder'] = $isOrder;
        $data['isUserVerify'] = $isUserVerify;
        $data['title'] = '找回密码-回答问题';
        $data['name']  = $psswordSet['name'];
        $answerSes     = $this->getAnswerSession($psswordSet['name']);
        $data['start_date'] = date('Y-m-d H:i:s');
        $data['end_date']   = $answerSes['end_date'];
        return view('auth.answer.mobile')->with($data);
    }

    /**
     * @param Request $request
     */
    public function verifyMobileAnswer(\App\Http\Requests\SetSession $request){
        if($request->isMethod('post')){
            $type = $request->input('type');
            $name = $request->input('name');

            if($request->has('phone_four')){
                $phone_four = $request->input('phone_four');
                $map = "phone='{$name}' and SUBSTR(car_users.phone,-4,4) ='{$phone_four}'";
                $isPhone = \App\Models\Users::checkUsersAnswer($map);
                if(!$isPhone){
                    return Freeze::passwordResetFreeze($name,$request,10,5,'AnswerMobile','手机验证错误！',1000);
                }
            }
            if($request->has('brand_id')){
                //验证买过的车
                $brand_id = $request->input('brand_id');//车型id
                $verifyUserOrder = \App\Models\Users::isCheckOrder([$type=>$name,'order.brand_id'=>$brand_id]);
                $isOrder = $verifyUserOrder;
                if(!$isOrder){
                    return Freeze::passwordResetFreeze($name,$request,10,5,'AnswerMobile','订单验证错误！',2000);
                    //return setJsonMsg(2,'订单验证错误！');exit;
                }
            }
            if($request->has('id_cart')){
                $idCart = $request->input('id_cart');
                //验证身份证后四位
                $map = "phone='{$name}' and SUBSTR(car_ue.id_cart,-4,4)='{$idCart}'";
                $isIdCart = \App\Models\Users::checkUsersAnswer($map);
                if(!$isIdCart){
                    return Freeze::passwordResetFreeze($name,$request,10,5,'AnswerMobile','身份证验证错误！',3000);
                    //return setJsonMsg(3,'身份证验证错误！');exit;
                }
            }
            //验证通过
            $this->resetPasswordCahe();
            return setJsonMsg(1,'验证通过！');exit;
        }
    }
    /**
     * @param Request $request
     */
    public function verifyMailAnswer(\App\Http\Requests\SetSession $request){
        if($request->isMethod('post')){
            $email = $request->input('email');

            if($request->has('phone_four')){
                $phone_four = $request->input('phone_four');
                $map = "email='{$email}' and SUBSTR(car_users.phone,-4,4) ='{$phone_four}'";
                $isPhone = \App\Models\Users::checkUsersAnswer($map);
                if(!$isPhone){
                    return Freeze::passwordResetFreeze($email,$request,10,5,'AnswerEmail','手机验证错误！',1000);
                }
            }
            if($request->has('brand_id')){
                //验证买过的车
                $brand_id = $request->input('brand_id');//车型id
                $verifyUserOrder = \App\Models\Users::isCheckOrder(['email'=>$email,'order.brand_id'=>$brand_id]);
                $isOrder = $verifyUserOrder;
                if(!$isOrder){
                    return Freeze::passwordResetFreeze($email,$request,10,5,'AnswerEmail','订单验证错误！',2000);
                }
            }
            if($request->has('id_cart')){
                $idCart = $request->input('id_cart');
                //验证身份证后四位
                $map = "email='{$email}' and SUBSTR(car_ue.id_cart,-4,4)='{$idCart}'";
                $isIdCart = \App\Models\Users::checkUsersAnswer($map);
                if(!$isIdCart){
                    return Freeze::passwordResetFreeze($email,$request,10,5,'AnswerEmail','身份证验证错误！',3000);
                }
            }
            //验证通过
            $this->resetPasswordCahe();
            $isSendMail = $this->sendResetEmail($request);
            if($isSendMail){
                return setJsonMsg(1,'验证通过！');
            }else{
                return setJsonMsg(4,'邮件发送失败！');
            }
        }
    }
    /**
     * 重置找回密码时间
     */
    private function resetPasswordCahe(){
        $redisCache = setCookieRedis('get',['prefix'=>$this->redisCache]);
        $name   = $redisCache['name'];
        $isName = $redisCache['isName'];
        $status = $redisCache['status'];
        //重置
        setRedis(null,null,$this->redisCache,true);
        setRedis(['name'=>$name,'isName'=>$isName,'status'=>$status],null,$this->redisCache);
    }

    /** 设置回答问题时间
     * @param $name
     * @return mixed
     */
    private function getAnswerSession($name){
        $sessionName = sha1(get_client_ip().$name);
        if(!Cache::has($sessionName)){
            $data = ['name'=>$name,'end_date'=>Carbon::now()->addMinute(5)->toDateTimeString()];
            Cache::put($sessionName,$data,7);
        }else{
            $data = Cache::get($sessionName);
        }
        return $data;
    }

    /** 删除session
     * @param $request
     * @param $name
     */
    private function delAnswerSession($name){
        $sessionName = sha1(get_client_ip().$name);
        Cache::forget($sessionName);
    }
    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetEmail(Request $request)
    {
        $email = $request->input('email');
        $token = $request->input('_token');
        $this->validate($request, ['email' => 'required|email']);
        $user = \App\User::where('email','=',$email)->first();
        Mail::to($email,'华车')->send(new UserResetMail($user,$email,$token));
        //清空缓存
        setRedis(null,$email,$this->redisErrorLockTime,true);
        $data['end_date']   = date('Y-m-d H:i:s',time()+$this->passwordVerifyCodeTime);
        //保存成功的时间，用来判断邮件时效信
        setRedis($data,$email,$this->redisErrorLockTime);
        return true;
    }
}