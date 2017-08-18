<?php namespace App\Com\Hwache\Baoxian;
/**
 * 保险功能模块
 */

use App\Models\HgCart;
use App\Models\HgCartAttr;
use App\Models\HgCartPrice;
use App\Models\HgCartUser;
use App\Models\HgCartLog;
use App\Models\HgMoney;
use App\Models\HgBaojiaPrice;
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
use Cache;
use Input;
use App\Http\Controllers\Controller;
use App\Com\Hwache\Order\Order;

class Baoxin
{
    /**
     * 根据订单号获取该订单的保险信息
     * @param $order_num
     * 
     * @return array
     */
    public function getBaoxianById($order_num)
    {
        
        $id = HgCart::getIdByOrdernum($order_num);
        

    }


}
