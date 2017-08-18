<?php
/**
 * 用户验证类
 */
namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

use App\Com\Hwache\User\Freeze;

class AuthController extends Controller {
    use RegistersUsers;

    protected  $redirectTo = '/';
    protected  $username = 'phone';
    const REDIS_KEY = 'login:click';
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'phone'    => 'required|numeric|unique:users',
            'password' => 'required|min:6',//confirmed|
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $saveData['phone']    = $data['phone'];
        $saveData['password'] = bcrypt($data['password']);
        return \App\Models\Users::createTable($saveData);
        //return $user->save();
    }

    /**
     * @param int $to
     */
    private function getDayRemaining($to=0){
        $remainging = strtotime(date('Y-m-d',time()).' 24:00:00');
        return ($remainging - time()) - $to;
    }
    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('auth.register')->with(['title'=>'用户注册']);
    }
    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request){
        $this->validator($request->all());
        $phone = $request->input('phone','');
        $code  = $request->input('code','');
        $template_code  = $request->input('template_code','78605066');
        $password = $request->input('password','');
        //判断注册冻结
        $registerFreeze = Freeze::checkRegisterFreeze($phone);
        if($registerFreeze){
            return setJsonMsg(0,'您的手机号今日注册冻结!',null,Freeze::setRegisterErrorCode(2));
        }
        //验证短信
        $isValidation = (new \App\Models\SendSmsLog())->VerifySms($phone,$template_code,$code);
        if(!$isValidation){
            //记录验证次数
            Freeze::userFreeze($phone,0,'reg_dj',10);
            $sendCount = (new \App\Models\SendSmsLog())->isDayCheckMsg($phone, $template_code);
            $push = ['count' => $sendCount];
            return setJsonMsg(0,'短信验证失败！',$push,Freeze::setRegisterErrorCode(4));
        }else{
            //注册用户
            event(new Registered($user = $this->create($request->all())));
            $request->session()->regenerate();
            if(!is_null($user)){
                //生成用户资金账号表
                \App\Models\HcUserAccount::create(['user_id'=>$user->id]);
                //生成用户附属信息表
                \App\Models\UserExtension::create(['user_id'=>$user->id]);
                $loginData = ['phone'=>$phone,'password'=>$password];
                Auth::attempt($loginData,true);
                //清理注册冻结
                Freeze::upRegisterFreeze($phone,0);
                return setJsonMsg(1,'注册成功！',null,Freeze::setRegisterErrorCode(0));
            }
            return setJsonMsg(0,'注册失败！');
        }
    }
}