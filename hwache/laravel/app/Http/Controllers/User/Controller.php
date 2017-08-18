<?php
/**
 * 会员中心总控制器
 *
 * 所有会员中心除了注册和登录之外均需要继承本控制器
 *
 * @package   User
 * @author    李扬(Andy) <php360@qq.com>
 * @link      技安后院 http://www.moqifei.com
 * @copyright 苏州华车网路科技有限公司 http://www.hwache.com
 */
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        // 验证会员是否登录
        $this->middleware('user.status');

        if (empty($_SESSION['is_login'])) {
            $_SESSION = session('member');
        }
    }

}

