<?php namespace App\Http\Controllers\Front;

/**
 * 支付诚意金页面
 *
 * @author Andy <php360@qq.com>
 */

use App\Http\Controllers\Controller;

use App\Models\HgBaojia;
use Redirect;
use Request;
use Session;
class BuyController extends Controller {

    public function __construct()
    {
        $ref=session('_previous');
        Session::flash('backurl', $ref['url']);

        /**
         * 检查会员是否登陆
         * 没有登陆跳转到登陆页面
         */
        $this->middleware(
            'user.status',
            ['only' => ['index']]
        );
    }

    /**
     * 报价的唯一序列号不是主键 bj_serial
     *
     * @param $id
     * @return \Illuminate\View\View
     */
    public function index()
    {   
        if (Request::Input('wenjian')) {
            $ziliao=trim(implode(',',Request::Input('ziliao')),',');
        }else{
            $ziliao='';
        }
        $id=Request::Input('bj_serial');
        // 查询是否存在该报价
        $bj = HgBaojia::getBaojiaInfo($id, true);
        if (empty($bj) || !is_object($bj)) {
            // TODO 结果为空做一个提示
            exit('没有该报价哦!!');
        } else {
            $bj = $bj->toArray();
        }

        // 不允许购买自己销售车辆
        if ($bj['m_id'] == $_SESSION['member_id']) {
            // TODO 是卖家自己销售的车型，不予购买处理
            exit('不允许购买自己销售车辆');
        }
        // 添加至SESSION
        sess('cart', $bj);
        sess('ziliao',$ziliao);
return redirect(route('cartOne'));
        // return view('buy.index', $view);
    }

}
