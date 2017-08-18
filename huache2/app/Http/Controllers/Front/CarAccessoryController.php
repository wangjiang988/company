<?php

namespace App\Http\Controllers\Front;

use App\Models\HgBaojiaXzj;
use App\Models\HgOrder;
use App\Models\HgOrderXzj;
use App\Models\HgOrderXzjEdit;
use App\Models\SendSmsLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

class CarAccessoryController extends Controller
{
    protected $orderxzj;
    protected $sendMsg;
    protected $request;

    public function __construct(HgOrderXzj $orderxzj,SendSmsLog $sendMsg,Request $request)
    {
        $this->orderxzj = $orderxzj;
        $this->sendMsg = $sendMsg;
        $this->request = $request;
    }

    /**
     * @param $id
     * @return $this
     */
    public function showParts($id)
    {
        //现车出现后装/非现车24前出现前装,24小时后出现后装
        //非原厂都出现
        $order = $this->checkOrder($id);
        $originals = $this->getOrderOption($order->is_xianche,
            $order->bj_id, $order->updated_at);
        $nonoriginals = HgBaojiaXzj::getNonXzj($order->id);
        return view('cart.Member_option_parts_show', compact('order'))
            ->with(compact('originals', 'nonoriginals'));
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeParts($id, Request $request)
    {
        $order = $this->checkOrder($id);
        $data = $request->input('xzj', []);
        foreach ($data as $key => $item) {
            if ($item['num'] == 0) {
                unset($data[$key]);
            }
        }
        DB::beginTransaction();
        try {
            $result = $this->orderxzj->copyData($data, $order->id);
            $order->orderXzj()->insert($result);
            $this->orderxzj->setReduceNum($data);
            $order->addLog('member',
                $order->order_status,
                $order->order_state,
                trans('orders.log.originalfactory')
            );
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            // return $e->getMessage();
            return redirect()->back();
        }
        app(SendSmsLog::class)->sendSms(
            $order->orderMember->member_mobile, 78720060,
            [
                'order' => $order->order_sn,
                'time'  => date('Y年m月d日', time())
            ]
        );
        return redirect()->route('parts.list', ['id' => $id]);
    }


    public function listParts($id)
    {
        $order = $this->checkOrder($id);
        if ($order->orderXzj->isEmpty()) {
            echo '应该是跳转购买页面吧';
        }
        return view('cart.Member_option_parts_list', compact('order'));
    }


    /**
     * 发起协商
     * @param $id
     */
    public function getNegotia($id)
    {
        //判断是协商第几步;
        $order = $this->checkOrder($id);
        if ($order->xzjp_steps == 1) {
            $datas = HgOrderXzjEdit::diffXzjEdit($order->id);
        } else {
            $datas = $this->orderxzj->getGroupXzj($order->id);
        }
        return view('cart.Member_option_parts_negotia',
            compact('order', 'datas'));
    }

    /**
     * 查看协商记录
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getNegotiaLog($id)
    {
        $order = $this->checkOrder($id);
        $result = HgOrderXzjEdit::getXzjEditLog($order->id);
        return view('cart.Member_option_parts_log', compact('order', 'result'));
    }


    public function editNegotia($id, Request $request)
    {
        $order = $this->checkOrder($id);
        DB::transaction(function () use ($order, $request) {
            HgOrderXzjEdit::setEditData($order->id, $request->input('xzj'));
            $updated_at = Carbon::parse('tomorrow')->addHours(17);
            $order->update(['xzjp_steps' => 1, 'xzjp_updated_at' => $updated_at]);
            $order->addLog('member',
                $order->order_status,
                $order->order_state,
                trans('orders.log.membersponsor')
            );
        });
        app(SendSmsLog::class)->sendSms(
            $order->orderMember->member_mobile, 78730081,
            [
                'order' => $order->order_sn,
                'time'  => date('Y年m月d日', strtotime($order->xzjp_updated_at))
            ]
        );
        return redirect()->route('parts.list', ['id' => $id]);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function checkOrder($id)
    {
        //订单id,用户id,订单主状态,订单子状态,用户类型
        return HgOrder::checkOrder($id, Auth::user()->id, [3,4], 0, 'member');
    }

    /**
     * 根据交车条件读取选装件
     * @param $is_xianche
     * @param $bj_id
     * @param $updated_at
     * @return array
     */
    public function getOrderOption($is_xianche, $bj_id, $updated_at)
    {
        $days = Carbon::parse($updated_at)->addDay();
        if (!$is_xianche && $days > Carbon::now()) {
            $result = HgBaojiaXzj::getBaojiaxzxzj($bj_id, 1);
        } else {
            $result = HgBaojiaXzj::getBaojiaxzxzj($bj_id);
        }
        return $result;
    }

    //-------------发送验证验证码------------
    public function getCode()
    {
        $phone = Auth::user()->phone;
        $template_code = $this->request->input('template_code');
        $this->sendMsg->sendSms(
            $phone,
            $template_code,
            [ 'code'  => random(6, 1)]
        );
        return response()->json(['msg'=>'success','error_code'=>200]);
    }

    public function checkCode(Request $request)
    {
        $phone = Auth::user()->phone;
        $code = $request->input('code');
        if ($this->sendMsg->VerifySms($phone, false, $code)) {
            $data = [
                'code' => '1',
                'message' => 'success',
            ];
        } else {
            $data = [
                'code' => '0',
                'message' => 'error',
            ];
        }
        return response()->json($data);
    }

}
