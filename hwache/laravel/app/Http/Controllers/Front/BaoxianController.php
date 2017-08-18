<?php namespace App\Http\Controllers\Front;

/**
 * 保险参数选择及返回数据
 */

use App\Http\Controllers\Controller;
use App\Com\Hwache\Order\Order;
use App\Com\Hwache\Order\Pay;
use Input;
use Request;
use App\Models\HgCart;
use App\Models\HgBaoJia;
use App\Models\HgBaojiaPrice;
use App\Models\HgMoney;
use App\Models\HgBaojiaBaoxianChesunPrice;
use App\Models\HgBaojiaBaoxianDaoqiangPrice;
use App\Models\HgBaojiaBaoxianSanzhePrice;
use App\Models\HgBaojiaBaoxianRenyuanPrice;
use App\Models\HgBaojiaBaoxianBoliPrice;
use App\Models\HgBaojiaBaoxianHuahenPrice;
use App\Models\HgDealerBaoxianZiran;
use App\Models\HgDealerBaoxianSanzhe;
use App\Models\HgDealerBaoxianRenyuan;
use App\Models\HgDealerBaoxianHuahen;
use App\Models\HgDealerBaoxianDaoqiang;
use App\Models\HgDealerBaoxianChesun;
use App\Models\HgDealerBaoxianBoli;
use App\Models\HgDealerBaoXianBuJiMian;
use App\Models\HgGoodsClass;
use App\Models\HgCartLog;
use App\Models\HgCarInfo;
use Carbon\Carbon;
use Session;
use Cache;


class BaoxianController extends Controller {

    /**
     * 检测会员登录
     * @param Order $order
     * @param Alipay $alipay
     */
    public function __construct(Request $request, Order $order)
    {
        /**
         * 检查会员是否登陆
         * 没有登陆跳转到登陆页面
         */
        // 跨域
        header('content-type:application:json;charset=utf8');
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:POST');
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        // 跳转URL
        session()->flash(
            'backurl',
            $_ENV['_CONF']['config']['shop_site_url'].'/index.php?act=m_index'
        );
        $this->middleware(
            'user.status',
            ['except' => ['postAlipayNotify']]
        );
        $this->request = $request;

        $this->order   = $order;

    }

    public function index()
    {
        //订单号，是否推荐组合
        $order_num=Request::inuput('order_num');
        $is_default=Request::inuput('is_default');
        $cacheName='baoxian'.$order_num;
        $data = array();
        $total=0.00;
        $order = $this->order->getOrder($order_num, $_SESSION['member_id'], true);
        // 根据订单的报价id取得报价的保险
        $baojia=HgBaojia::getBaojiaInfo($order->bj_id)->toArray();
        // 保险id,保险折扣率
        $baoxian_id=$baojia['bj_bx_select'];
        $baoxian_discount=$baojia['bj_baoxian_discount'];
        // 车型信息
        $carmodelInfo = HgCarInfo::getCarmodelFields($baojia['brand_id']);
        // 保险的类别
        $baoxian_type=getBaoxianType($order->buytype,$carmodelInfo['seat_num']);
        
        if ($is_default) {
            // 推荐组合
            $chesun =HgBaojiaBaoxianChesunPrice::getPrice($baojia['bj_id'],$baoxian_type);
            $daoqiang=HgBaojiaBaoxianDaoqiangPrice::getPrice($baojia['bj_id'],$baoxian_type);
            $sanzhe =HgBaojiaBaoxianSanzhePrice::getPrice($baojia['bj_id'],$baoxian_type);
            $renyuan =HgBaojiaBaoxianRenyuanPrice::getPrice($baojia['bj_id'],$baoxian_type); 
            $boli =HgBaojiaBaoxianBoliPrice::getPrice($baojia['bj_id'],$baoxian_type);
            $huahen =HgBaojiaBaoxianHuahenPrice::getPrice($baojia['bj_id'],$baoxian_type);
            
            $data['chesun']=$chesun;
            $data['daoqiang']=$daoqiang;
            $data['sanzhe']=$sanzhe;
            $data['renyuan']=$renyuan;
            $data['boli']=$boli;
            $data['huahen']=$huahen;
            $data['total']=$chesun['discount_price']+$daoqiang['discount_price']+$sanzhe['discount_price']+$renyuan['discount_price']+$boli['discount_price']+$huahen['discount_price']+$chesun['bjm_discount_price']+$daoqiang['bjm_discount_price']+$sanzhe['bjm_discount_price']+$renyuan['bjm_discount_price']+$huahen['bjm_discount_price'];
            // 写入缓存
            if (! config('app.debug')) {
                Cache::put($cacheName, $data, config('app.cache_time'));
            }
            return json_encode($data);

        }else{
            // 全部额度
            if (Request::inuput('chesun')) {
                $chesun =HgBaojiaBaoxianChesunPrice::getPrice($baojia['bj_id'],$baoxian_type);
                $data['chesun']=$chesun;
                $total+=$chesun['discount_price'];
                if (Request::inuput('bjm_chesun')) {
                    $total+=$chesun['bjm_discount_price'];
                }
                
            }
            if (Request::inuput('daoqiang')) {
                $daoqiang=HgBaojiaBaoxianDaoqiangPrice::getPrice($baojia['bj_id'],$baoxian_type);
                $data['daoqiang']=$daoqiang;
                $total+=$daoqiang['discount_price'];
                if (Request::inuput('bjm_daoqiang')) {
                    $total+=$daoqiang['bjm_discount_price'];
                }
            }
            if (Request::inuput('sanzhe') && Request::inuput('sanzhe_com')) {
                $sanzhe =HgBaojiaBaoxianSanzhePrice::getPrice($baojia['bj_id'],$baoxian_type,Request::inuput('sanzhe_com'));
                $data['sanzhe']=$sanzhe;
                $total+=$sanzhe['discount_price'];
                
                if (Request::inuput('bjm_sanzhe')) {
                    $total+=$sanzhe['bjm_discount_price'];
                }
            }
            if (Request::inuput('sj_com')) {
                $renyuan_sj =HgBaojiaBaoxianRenyuanPrice::getPrice($baojia['bj_id'],$baoxian_type,['staff' => 'sj','compensate' => Request::inuput('sj_com')]);

                $data['renyuan_sj']=$renyuan_sj;
                $total+=$renyuan_sj['discount_price'];
                if (Request::inuput('bjm_renyuan')) {
                    $total+=$renyuan_sj['bjm_discount_price'];
                }
                
            }
            if (Request::inuput('ck_com') && Request::inuput('seat')) {
                $renyuan_ck =HgBaojiaBaoxianRenyuanPrice::getPrice($baojia['bj_id'],$baoxian_type,['staff' => 'ck','compensate' => Request::inuput('sj_com')]);
                $renyuan_ck['price']=$renyuan_ck['price']*Request::inuput('seat');
                $renyuan_ck['discount_price']=$renyuan_ck['discount_price']*Request::inuput('seat');
                $renyuan_ck['seat']=Request::inuput('seat');
                $data['renyuan_ck']=$renyuan_ck;
                $total+=$renyuan_ck['discount_price'];

                if (Request::inuput('bjm_renyuan')) {
                    $total+=$renyuan_ck['bjm_discount_price']*Request::inuput('seat');
                }
                

            }
            if (Request::inuput('boli_jk')) {
                $boli =HgBaojiaBaoxianBoliPrice::getPrice($baojia['bj_id'],$baoxian_type,['state' => 'jk',]);
                $data['boli']=$boli;
                $total+=$boli['discount_price'];
            }
            if (Request::inuput('boli_gc')) {
                $boli =HgBaojiaBaoxianBoliPrice::getPrice($baojia['bj_id'],$baoxian_type,['state' => 'gc',]);
                $data['boli']=$boli;
                $total+=$boli['discount_price'];
            }
            if (Request::inuput('huahen_com')) {
                $huahen =HgBaojiaBaoxianHuahenPrice::getPrice($baojia['bj_id'],$baoxian_type,['compensate' => Request::inuput('huahen_com'),]);
                $data['huahen']=$huahen;
                $total+=$huahen['discount_price'];
                if (Request::inuput('bjm_huahen')) {
                    $total+=$huahen['bjm_discount_price'];
                }
                
            }
            $date['total']=$total;
            // 写入缓存
            if (! config('app.debug')) {
                Cache::put($cacheName, $data, config('app.cache_time'));
            }
            return json_encode($data);
        }
        
  
    }

    
}
