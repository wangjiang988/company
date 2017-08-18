<?php

namespace App\Http\Controllers\Member;

use App\User;
use Illuminate\Http\Request;;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;

use App\Com\Hwache\User\Freeze;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    const LOGIN_VALIDITY_TIME = 1200;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $guard  ='api';

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function username($name){
        return is_phone($name) ? 'phone' : 'email';
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm(Request $request)
    {
        //todo 目前没有单独的登录页面跳转到首页
        return redirect('/');
        return view('auth.login')->with(['title'=>'用户登录']);
    }

    /**
     * 登录前的冻结判断
     * @param Request $request
     * @return array
     */
    public function getLoginFreeze(Request $request)
    {
        if($request->ajax()){
            $name = $request->get('name');
            if(is_null($name) || empty($name)){
                return setJsonMsg(0,'空数据！',null,'404');
            }
            //判断用户账号
            if(!is_phone($name) && !is_email($name)){
                $isUser = false;
                $msg = '账号格式错误！';
            }else{
                $checkData = is_phone($name) ? ['phone'=>$name,'status'=>1] : ['email'=>$name,'status'=>1] ;
                $isUser    = User::where($checkData)->first();
                $msg = "用户不存在！";
            }
            if(!$isUser){
                return setJsonMsg(0,$msg,null,1000);
            }
            $LoginFreeze   = Freeze::getFreezeFind($name);
            $dataTime     = '';
            $clickNum     = 0;
            $errorCode    = '0000';
            if($LoginFreeze){
                $isLoginFreeze = Freeze::isLoginFreeze($LoginFreeze,$name);
                $dataTime     = $LoginFreeze->updated_at;
                $clickNum     = $LoginFreeze->click_num;
                $errorCode    = Freeze::setLoginErrorCode($clickNum,$dataTime);
            }else{
                $isLoginFreeze = false;
            }
            $msg = ($isLoginFreeze) ? '该手机号登录有冻结数据！' : '该手机号登录无冻结数据！' ;
            $status = intval(($isLoginFreeze==true));
            return setJsonMsg($status,$msg,['code'=>$clickNum,'time'=>$dataTime],$errorCode);
        }
    }
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $name        = $request->input('name','');
        $password    = $request->input('password','');

        //判断用户账号
        if(!is_phone($name) && !is_email($name)){
            $isUser = false;
            $msg = '账号格式错误！';
        }else{
            $checkData = is_phone($name) ? ['phone'=>$name,'status'=>1] : ['email'=>$name,'status'=>1] ;
            $isUser    = User::where($checkData)->first();
            $msg = "用户不存在！";
        }
        if(!$isUser){
            return setJsonMsg(0,$msg,null,1000);
        }
        $LoginFreeze = Freeze::getFreezeFind($name);
        $sendNum = ($LoginFreeze) ? $LoginFreeze->click_num : 0;
        $valicode = $request->input('code','');
        if(!is_null($valicode) || ($sendNum % 10) >2){
            if(!checkSeccode($valicode)){
                return setJsonMsg(2,'验证码错误！',['code'=>$sendNum],'2000');
            }
        }
        if(is_phone($name) || is_email($name)){
            $loginData = is_phone($name) ? ['phone' => $name , 'password' => $password] : ['email' => $name , 'password' => $password];
        }else{
            return setJsonMsg(0,'用户名格式错误！');
        }
        //账号状态是可用状态
        $loginData['status'] = 1;
        $request->session()->regenerate();

        if($LoginFreeze){
            $isFreeze     = Freeze::isLoginFreeze($LoginFreeze,$name);
            if($isFreeze){
                return $isFreeze;
            }
        }
        if (Auth::attempt($loginData,true))
        {
            //获取token
            //wang_jiang
            try {
                if (! $token = Auth::guard($this->guard)->attempt($loginData)) {
                    return response()->json(['error' => 'invalid_credentials'], 401);
                }
            } catch (JWTException $e) {
                return response()->json(['error' => 'could_not_create_token'], 500);
            }
            //结束获取token

            //登录成功清空登录限制
            Freeze::upLoginFreeze($name,0);
            //修改登录次数及更新时间
            $data = ['login_num' => DB::raw('login_num+1'),'updated_at'=>date('Y-m-d H:i:s',time())];
            $where = is_phone($name) ?['phone' => $name] : ['email'=>$name];
            \App\User::map($where)->update($data);

            if($request->ajax()){
                return setJsonMsg(1,'登录成功！',['code'=>0,'token'=>$token]);
            }else{
                return redirect('member/');
            }
        }else{
            //用户冻结状态判断及修改
            if($LoginFreeze){
                if(empty($LoginFreeze->status)){
                    Freeze::upLoginFreeze($name,1);
                }else{
                    $dataTime     = $LoginFreeze->updated_at;
                    $clickNum     = $LoginFreeze->click_num;
                    if ($clickNum % 10 == 0) {
                        if(time() >= strtotime($dataTime)+self::LOGIN_VALIDITY_TIME){
                            Freeze::userFreeze($name,$clickNum);
                        }
                    }else{
                        Freeze::userFreeze($name,$clickNum);
                    }
                }
                $LoginFreeze  = Freeze::getFreezeFind($name);
                $dataTime     = $LoginFreeze->updated_at;
                $clickNum     = $LoginFreeze->click_num;
                if($clickNum % 10 == 0){
                    //发送冻结短信，及邮件
                    $toDay = ($clickNum>=20);
                    Freeze::sendSms($name,'dj',$toDay);
                }
            }else{
                Freeze::userFreeze($name);
                $clickNum = 1;
                $dataTime = \Carbon\Carbon::now()->toDateTimeString();
            }
            $errorCode = Freeze::setLoginErrorCode($clickNum,$dataTime);
            return setJsonMsg(0,'用户名或密码错误！',['code'=>$clickNum,'time'=>$dataTime],$errorCode);
        }
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->regenerate();
        return redirect('/');
    }
}
