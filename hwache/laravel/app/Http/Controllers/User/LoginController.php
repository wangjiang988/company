<?php
/**
 * 用户登陆控制器
 *
 * 手机登陆，邮箱登陆等
 *
 * @package   User
 * @author    李扬(Andy) <php360@qq.com>
 * @link      技安后院 http://www.moqifei.com
 * @copyright 苏州华车网路科技有限公司 http://www.hwache.com
 */
namespace App\Http\Controllers\User;

use DB;
use Validator;
use Illuminate\Http\Request;
use App\Com\Hwache\User\User;
use App\Http\Controllers\Controller;

class LoginController extends Controller
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
        view()->share('title', trans('user.login_title').' | '.trans('common.www_title'));
    }

    /**
     * Login Page Show
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getLogin()
    {
        if ($redirectUrl = $this->request->input('redirect')) {
            session(['redirectUrl' => urldecode($redirectUrl)]);
        }

        return view('user.login.login');
    }

    /**
     * AJAX && POST Commit Data
     * @return array
     */
    public function postlogin()
    {
        if ($this->request->ajax()) {
            $validator = Validator::make($this->request->all(), [
                'name' => 'required',
                'pwd'  => 'required',
            ]);

            if ($validator->fails()) {
                return [
                    'code' => 0,
                    'msg' => trans('user.name_pwd_not_empty'),
                ];
            }

            // 验证帐号和密码
            $member = $this->user->login($this->request->input('name'), $this->request->input('pwd'));
            if ($member) {
                $data = [
                    'member_old_login_time' => $member->member_login_time,
                    'member_old_login_ip'   => $member->member_login_ip,
                    'member_login_time'     => time(),
                    'member_login_ip'       => $this->request->ip(),
                ];
                // 更新局部信息
                $this->user->updateUserInfo($member->member_id, $data);

                $data['is_login']    = 1;
                $data['user_type']    = 1;
                $data['member_id']   = $member->member_id;
                $data['member_name'] = $member->member_name;
                $data['member_mobile'] = $member->member_mobile;

                // 保存session
                $this->user->saveUserSession($data);

                return [
                    'code'    => 1,
                    'nextUrl' => route('user.ucenter'),
                ];
            }else{
                return [
                    'code' => 0,
                    'msg'  => trans('user.validator_fails'),
                ];
            }
        }

        return [
            'code' => 0,
            'msg'  => 'Invalid Parameters',
        ];
    }

    /**
     * 登出
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getLogout()
    {
        $this->user->logout();

        return redirect()->route('/');
    }

}
