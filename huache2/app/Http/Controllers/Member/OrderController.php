<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\HcUserOrderRepository;
use Carbon\Carbon;

class OrderController extends Controller
{
    protected $repostitory;
    const NAV_TAB = 'myOrder';

    public function __construct()
    {
        $this->middleware('auth');
        $this->repostitory = new HcUserOrderRepository();
    }

    /**
     * 订单列表
     * @param Request $request
     * @return $this
     */
    public function getIndex(Request $request)
    {
        $map['user_id'] = $request->user()->id;
        $data['title'] = '我的订单';
        $data['nav'] = self::NAV_TAB;
        if ($request->has('time') || $request->has('brand')) {
            $times['type'] = $request->input('time');
            $times['time'] = Carbon::now()->addMonths(-3);
            $brand = $request->input('brand');
            $data['list'] = $this->repostitory->getSearchList($map, $times,
                $brand);
        } else {
            $data['list'] = $this->repostitory->getSearchList($map);
        }
        $data['brand'] = $this->repostitory->groupBrand($map);
        return view('HomeV2.User.user_order_lists')->with($data);
    }
    /**
     * 订单详情
     * @param Request $request
     * @param $order_sn
     * @return $this
     */
    public function showDetail(Request $request,$order_sn){
        $findObj = $this->repostitory ->getFind($order_sn);
        $data['find'] = $findObj->first();
        $data['title'] = '我的订单';
        $data['nav']   = self::NAV_TAB;
        dd("没有UI及页面，以后在测试！！！");
        return view('HomeV2.User.Order.detail')->with(compact('data'));
    }

    /* $a = Carbon::today()->toDateString();
       $b = Carbon::now()->addDay()->toDateString();
       $c = Carbon::now()->subDay()->getWeekStartsAt();*/
    /*$userBank = \App\Models\Users::find($request->user()->id)->userBankList();
    dd($userBank->get()->toArray());*/
    /*$map['user_id']  = ;
    $map['order_sn'] = $order_sn;*/
    /*$userAddress = \App\Models\Users::find($request->user()->id)->userAddress();
    dd($userAddress->first()->toArray());*/
}
