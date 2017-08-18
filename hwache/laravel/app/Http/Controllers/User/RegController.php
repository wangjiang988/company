<?php
/**
 * 用户注册控制器
 *
 * 手机注册，邮箱注册，密码重设等
 *
 * @package   User
 * @author    李扬(Andy) <php360@qq.com>
 * @link      技安后院 http://www.moqifei.com
 * @copyright 苏州华车网路科技有限公司 http://www.hwache.com
 */
namespace App\Http\Controllers\User;

use Cache;
use Illuminate\Http\Request;
use App\Com\Hwache\User\User;
use App\Http\Controllers\Controller;

class RegController extends Controller
{
    /**
     * 请求依赖
     * @var Request
     */
    private $request;

    /**
     * 用户中心模块依赖
     * @var User
     */
    private $user;

    /**
     * 构造函数，初始化内部依赖变量
     * @param Request $request
     * @param User $user
     */
    public function __construct(Request $request, User $user)
    {
        $this->request = $request;
        $this->user    = $user;

        // 网站标题
        view()->share('title', trans('user.reg_title').' | '.trans('common.www_title'));
    }

    /**
     * 通过手机注册
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getFillMobile()
    {
        return view('user.reg.fill_mobile');
    }

    /**
     * Ajax发送手机的验证码
     * @return array|null
     */
    public function postSendMobileCode()
    {
        $mobile = $this->request->input('mobile');

        if ($this->request->ajax()) {
            // 检测手机号码是否已经注册
            if ($this->user->checkMobile($mobile)) {
                return [
                    'error_code' => 1,
                    'success'    => false,
                    'error_msg'  => trans('user.reg_mobile_exists'),
                ];
            }

            // 发送手机验证码
            $code = get_rand();
            if ($this->user->sendRegMobileCode($mobile, $code)) {
                // 先删除之前的缓存
                Cache::forget($mobile);
                // 缓存新的验证码，有效期10分钟
                Cache::put($mobile, $code, config('sms.sms_time_limit'));
                // 保存数据
                session([
                    'reg'    => [
                        'from'     => 'mobile', // 记录来源，设置密码的时候检测来源
                        'reg_name' => $mobile, // 手机号
                    ],
                ]);

                return [
                    'error_code' => 0,
                    'success'    => true,
                    'error_msg'  => trans('user.reg_mobile_code_ok'),
                ];
            } else {
                // 发送验证码失败
                if (session()->has('reg')) {
                    session()->forget('reg');
                }

                return [
                    'error_code' => 1,
                    'success'    => false,
                    'error_msg'  => trans('user.reg_mobile_code_false'),
                ];
            }
        }

        return '';
    }

    /**
     * 获取短信验证码
     * @return array
     */
    public function getMobileCode()
    {
        if ($this->request->ajax()) {
            $code   = $this->request->input('code');
            $mobile = $this->request->input('mobile');

            if (Cache::has($mobile) && Cache::get($mobile) == $code) {
                return ['success' => true];
            } else {
                return ['success' => false];
            }
        }

        return ['success' => false];
    }

    /**
     * 邮箱注册帐号
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getFillEmail()
    {
        return view('user.reg.fill_email');
    }

    /**
     * post发送邮箱验证链接
     * @return array|bool
     */
    public function postSendEmailLink()
    {
        $email = $this->request->input('email');

        if ($this->request->ajax()) {
            // 检测邮箱是否已经注册
            if ($this->user->checkEmail($email)) {
                return [
                    'error_code' => 1,
                    'success'    => false,
                    'error_msg'  => trans('user.reg_email_exists'),
                ];
            }

            if ($this->user->sendEmail($email)) {
                // 保存数据
                session([
                    'reg'    => [
                        'from'     => 'email', // 记录来源，设置密码的时候检测来源
                        'reg_name' => $email, // 邮箱账号
                    ],
                ]);

                return [
                    'error_code' => 0,
                    'success'    => true,
                    'error_msg'  => trans('user.reg_email_link_ok'),
                ];
            }

            return [
                'error_code' => 1,
                'success'    => false,
                'error_msg'  => trans('user.reg_email_link_false'),
            ];
        }

        return null;
    }

    /**
     * 验证邮箱
     * @param $verify
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getVerifyEmail($verify)
    {
        $data = $this->user->verifyEmail($verify);

        if ($data['success']) {
            // 保存数据
            session([
                'reg'    => [
                    'from'     => 'email', // 记录来源，设置密码的时候检测来源
                    'reg_name' => $data['email'], // 邮箱账号
                ],
            ]);

            return redirect()->route('user.reg.set_pwd');
        }

        return view('user.reg.verify_email');
    }

    /**
     * 已经发送邮件
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEnterEmailSend()
    {
        return view('user.reg.enter_email_send', [
            'email' => session('reg.reg_name'),
        ]);
    }

    /**
     * 设置用户密码
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSetPwd()
    {
        return view('user.reg.set_pwd');
    }

    /**
     * 保存用户信息，并登陆
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function postSaveInfo()
    {
        if (
            (strlen($this->request->input('pwd')) >= 6) &&
            ($this->request->input('pwd') == $this->request->input('pwd2'))
        ) {
            $time = time();
            // 保存到数据库数据
            $data = [
                'member_time'   => $time,
                'member_name'   => 'HC'.$time,
                'member_passwd' => bcrypt($this->request->input('pwd')),
                'member_login_time' => $time,
                'member_login_ip'   => $this->request->ip(),
            ];
            // 检测来源(mobile|email)
            $from = session('reg.from');
            // 根据不同来源处理
            if ('mobile' == $from) {
                $data['member_mobile'] = session('reg.reg_name');
            } elseif ('email' == $from) {
                $data['member_email'] = session('reg.reg_name');
            }
            // 添加数据
            if ($id = $this->user->addData($data)) {
                // 调整data数据
                unset($data['member_passwd']);
                $data['is_login'] = 1;
                $data['member_id'] = $id;

                // 保存登陆信息至session中
                $this->user->saveUserSession($data);

                return redirect()->route('user.reg.ok');
            }

            return null;
        }

        return null;
    }

    /**
     * 注册完成
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getOk()
    {
        if ($this->user->checkUserStatus()) {
            return view('user.reg.get_ok');
        }

        return redirect()->route('user.login');
    }

    public function getResetPwd($step = 'step1')
    {
        switch($step) {
            case 'step1':
                break;
        }
    }
}
