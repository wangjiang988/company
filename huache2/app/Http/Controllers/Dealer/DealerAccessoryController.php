<?php

namespace App\Http\Controllers\Dealer;

use App\Models\HgOrder;
use App\Models\HgOrderXzj;
use App\Models\HgOrderXzjEdit;
use App\Models\HgXzj;
use App\Models\SendSmsLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use DB;

class DealerAccessoryController extends Controller
{

    public function show($id)
    {
        $order = $this->checkOrder($id);
        $datas = HgOrderXzjEdit::getDealerXzj($id);
        return view('dealer.orders.option_parts_main', compact('order', 'datas'));
    }


    public function store($id, Request $request)
    {
        $order = $this->checkOrder($id);
        $datas = $request->input('xzj');
        //Todo 时间倒计时操作待续(可以在用户区域进行操作)
        DB::transaction(function () use ($order, $datas, $id) {
            //对经销商提交的同意与否进行操作
            $this->feedBack($datas, $id);
            $order->update(['xzjp_steps' => 0]);
            $order->addLog('seller',
                $order->order_status,
                $order->order_state,
                trans('orders.log.sellersettime')
            );
        });
        app(SendSmsLog::class)->sendSms($order->orderUsers->phone, 78735048);
        return redirect()->route('dealer.activelist',['id'=>$order->id]);

    }


    public function checkOrder($id)
    {
        //订单id,用户id,订单主状态,订单子状态,用户类型
        return HgOrder::checkOrder($id, session('user.member_id'), [3,4], 0, 'seller');
    }

    //减少选装件流程
    public function feedBack(array $datas, $id)
    {
        $temps = [];
        $daili_xzj = [];
        if (is_array($datas)) {
            $result = HgOrderXzjEdit::where('order_id', $id)
                ->whereIn('xzj_id', array_keys($datas))
                ->where('is_install', 0)
                ->get();
            foreach ($result as $key => $item) {
                if (array_key_exists($item->xzj_id, $datas)) {
                    $temps[$key]['xzj_id'] = $item->xzj_id;
                    $temps[$key]['order_id'] = $id;
                    $temps[$key]['id'] = $item->id;
                    if ($datas[$item->xzj_id]['type'] == 'off') {
                        $temps[$key]['is_install'] = 2;
                    } else {
                        $temps[$key]['is_install'] = 1;
                        $daili_xzj[$key]['id'] = $item->xzj_id;
                        $daili_xzj[$key]['list_id'] = $item->xzj_list_id;
                        $daili_xzj[$key]['num'] = $item['old_num'] - $item['edit_num'];
                        $daili_xzj[$key]['order_id'] = $item['order_id'];
                    }
                }
            }
        }
        try {
            DB::transaction(function () use ($temps,$daili_xzj) {
                if ( ! empty($temps)) {
                    $this->feedUpdate($temps);
                }
                if ( ! empty($daili_xzj)) {
                    $this->feedUpxzj($daili_xzj);
                    //对应的选装件数量减少
                    foreach ($daili_xzj as $dl_xzj) {
                        HgOrderXzj::Ancestry($dl_xzj['order_id'], $dl_xzj['id'], $dl_xzj['num']);
                    }

                }
            });
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    //更改修改选装件状态
    public function feedUpdate(array $temps)
    {
        $orderXzj = new HgOrderXzjEdit;
        $orderXzj->batchUpdate('id', 'is_install',
            array_column($temps, 'id'), array_column($temps, 'is_install'));
    }

    //归还选装件数量
    public function feedUpxzj(array $xzj)
    {
        $xzj_list = new HgXzj;
        $xzj_list->batchUpdate('id', 'xzj_max_num', array_column($xzj, 'list_id'), array_column($xzj, 'num'));
    }

}
