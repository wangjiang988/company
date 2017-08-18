<?php

namespace App\Http\Controllers\Dealer;

use App\Models\HcVehicleToolsFiles;
use App\Models\HgAnnex;
use App\Models\HgEditInfo;
use App\Models\HgOrder;
use App\Http\Controllers\Front\BaseParticularController;


class DealerParticularController extends BaseParticularController
{
    /**
     * @param $order_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($order_id)
    {
        $order = HgOrder::getOrder($order_id, session('user.member_id'),
            'seller');
        //获取报价相关数据
        $arr_baojia = $this->baojia->getBaojiaData($order->bj_id);
        //随车工具
        $arr_annexs = HgAnnex::getAnnex($order->brand_id);
        //原厂与赠品
        $rpos = $this->getEditInfo($order);
        //交车文件and随车工具
        $tools_files = HcVehicleToolsFiles::getAnnex($order->brand_id);
        //特需文件
        $files = $this->getSpecialFiles($order);
        $arr_diff = $this->getAllEditInfo($order->order_sn);
        return view('dealer.orders.order_particulars',
            compact('order', 'arr_baojia', 'arr_annexs', 'rpos'))
            ->with(compact('tools_files','files','arr_diff'));
    }
}
