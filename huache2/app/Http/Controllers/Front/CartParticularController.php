<?php

namespace App\Http\Controllers\Front;

use App\Models\HcVehicleToolsFiles;
use App\Models\HgEditInfo;
use App\Models\HgOrder;
use Auth;

class CartParticularController extends BaseParticularController
{
    /**
     * @param $order_id
     *
     * @return $this
     */
    public function index($order_id)
    {
        $order = HgOrder::getOrder($order_id,Auth::id(),'member');
        $arr_baojia = $this->baojia->getBaojiaData($order->bj_id);
        //交车工具随车文件
        $tools_files = HcVehicleToolsFiles::getAnnex($order->brand_id);
        //已订购原厂与非原厂
        $order->load('orderXzj');
        //特需文件
        $files = HgEditInfo::getFeedfile($order->order_sn,201);
        //原厂选装件
        $rpos = $this->getEditInfo($order);
        //对比修改
        $arr_diff = $this->getAllEditInfo($order->order_sn);
        return view('cart.Member_order_particulars',compact('order','arr_baojia'))->with(compact('tools_files','files','rpos','arr_diff'));
    }
}
