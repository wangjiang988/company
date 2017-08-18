<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

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
     * 邮件找回页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showMailForm(){
        $pssswordSet = $this->redisCookie;
        if(is_null($pssswordSet)){
            return redirect()->route('member.showResetForm');exit;
        }
        $isName       = intval($pssswordSet['isName']);
        $data['name'] = $pssswordSet['name'];
        $view = 'auth.pwds.mail_error';
        //账号存在
        if($isName){
            //用户账号存在并且状态正常【发送短信】
            if($pssswordSet['status'] == 1) {
                $view = 'auth.pwds.mail_seep';
            }
            //账号异常
            $data['error_code']    = 1;
            $data['error_status']  = 2;
        }else{
            $data['error_code']    = 2;
        }
        return view($view,['data'=>$data,'title'=>'邮箱找回密码']);
    }
    /**
     * 查收邮件
     */
    public function checkMail(Request $request){
        $mails = $this->redisCookie;
        $data['email'] = $mails['name'];
        return view('auth.pwd.email_success',['data'=>$data,'title'=>'查收邮箱']);
    }
}
