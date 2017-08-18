<?php

namespace App\Http\Controllers\Orders;

use App\Models\HgAgentFiles;
use App\Models\HgAnnex;
use App\Models\HgBaojia;
use App\Models\HgBaojiaXzj;
use App\Models\HgCarInfo;
use App\Models\HgCartLog;
use App\Models\HgEditInfo;
use App\Models\HgOrderXzj;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DealsController extends Controller
{
    //405
    public function getMemberAffirm($order)
    {
        return view('cart.Member_appointemt_member',compact('order'));
    }

    public function getMemberConfirm($order)
    {
        $files = $order->orderAppoint->files;
        $pay_status = $files['status'];
        if ($files['data']) {
            $file_lists = HgAgentFiles::getFileLists($files['data']);
        } else {
            $file_lists = collect();
        }
        return view('cart.Member_appointemt_finish',compact('order','pay_status'))->with(compact('file_lists'));
    }

    public function getMemberPriceSettle($order)
    {
        return view('cart.Member_settlement_confirma',compact('order'));
    }

    public function getSellAffirm($order)
    {
        return $this->getSellFinish($order);
    }

    public function getMemberAppraise($order)
    {
        $count = $order->orderComment()->count();
        if ($count) {
            return $this->getMemberRecord($order);
        }
        return view('cart.Member_settlement_appraise', compact('order'));
    }


    public function getMemberHave($order)
    {
        return $this->getMemberAppraise($order);
    }

    public function getMemberIncome($order)
    {
        return $this->getMemberAppraise($order);
    }



    //客户先确认交车
    public function getSellFinish($order)
    {
        return $this->getSellConfirm($order);
    }


    // 售方先确认页面
    public function getSellConfirm($order)
    {
        $view = $this->getData($order->bj_id, $order->brand_id);
        $edit_data = HgEditInfo::getEditInfo($order->order_sn, 201);
        $annx = HgAnnex::getAnnex($order->brand_id);
        $orderxzj = new HgOrderXzj;
        $xzj_lists = $orderxzj->getOrderXzjLists($order->id);
        return view('dealer.orders.order_appointemt_finish',$view, $edit_data)->with(compact('order','annx','xzj_lists'));
    }

    //双方同意交车
    public function getSellPriceSettle($order)
    {
        return view('dealer.orders.order_settlement_jiaxinbao',compact('order'));
    }


    //结算开始第一步
    public function getSellAppraise($order)
    {
        return view('dealer.orders.order_settlement_settl', compact('order'));
    }

    //结算第二步
    public function getSellHave($order)
    {
        return view('dealer.orders.order_settlement_yetsettl',compact('order'));
    }

    //结算最后一步
    public function getSellIncome($order)
    {
        return view('dealer.orders.order_settlement_incomesettl',compact('order'));
    }

    public function getData($bj_id, $brand_id)
    {
        $view['baojia'] = App(HgBaojia::class)->getBaojiaData($bj_id);
        $view['car_info'] = HgCarInfo::getInteriorColor($bj_id, $brand_id);
        $view['originals'] = HgBaojiaXzj::getXzjType($bj_id);
        return $view;
    }

    public function getMemberRecord($order)
    {
        $arr_times = HgCartLog::orderTimeList($order->id);
        return view('cart.Member_settlement_history',compact('order','arr_times'));
    }



}
