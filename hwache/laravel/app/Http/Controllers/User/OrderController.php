<?php
/**
 * 订单中心控制器
 *
 * 会员资料，订单管理，财务中心，实名认证等等
 *
 * @package   User
 * @author    李扬(Andy) <php360@qq.com>
 * @link      技安后院 http://www.moqifei.com
 * @copyright 苏州华车网路科技有限公司 http://www.hwache.com
 */
namespace App\Http\Controllers\User;

class OrderController extends Controller
{
    /**
     * 用户订单中心
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function getUserOrder()
    {
        return redirect($_ENV['_CONF']['config']['shop_site_url'].'/index.php?act=m_order');
    }
}