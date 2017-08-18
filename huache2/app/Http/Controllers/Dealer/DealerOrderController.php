<?php

namespace App\Http\Controllers\Dealer;

use App\Models\HcOrderConciationConsultExtension;
use App\Models\HgBaojia;
use App\Models\HgDealer;
use App\Models\HgEditInfo;
use App\Models\HgOrder;
use App\Models\HgOrderAttr;
use App\Models\HgOrderSheller;
use App\Models\HgUser;
use App\Models\SendSmsLog;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use App\Http\Requests\OrderQuest;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use App\Services\ActionOrderTrait;
use Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DealerOrderController extends Controller
{
    use ActionOrderTrait;

    protected $scope = ['actives', 'finishs'];

    protected $hgBaojia, $request, $order, $orderAttr, $sendMsg;

    public function __construct(
        HgBaojia $hgBaojia,
        Request $request,
        HgOrder $order,
        HgOrderAttr $orderAttr,
        SendSmsLog $sendMsg
    ) {
        $this->hgBaojia = $hgBaojia;
        $this->request = $request;
        $this->order = $order;
        $this->orderAttr = $orderAttr;
        $this->sendMsg = $sendMsg;
        $this->middleware('auth.seller');
    }

    //订单菜单列表
    public function index($type)
    {
        if ( ! in_array($type, $this->scope)) {
            throw new NotFoundHttpException('操作错误!');
        }
        $field = $this->order->lists;
        $brand = $this->orderAttr->getBrand($type, session('user.member_id'), $field);
        $dealers = app(HgDealer::class)->getDealer($type, session('user.member_id'), $field);
        $view['lists'] = HgOrder::Lists(session('user.member_id'), $type)->with('orderStatus')->latest()->paginate(10);
        $view['flag'] = 'order_list_' . $type;
        return view('dealer.orders.order_manage_' . $type, $view)->with(compact('brand', 'dealers'));
    }

    public function getCarRange()
    {
        $data = $this->request->except('_token');
        $result = $this->order->getCarinfo(
            session('user.member_id'), $data['active'], $data['type'], $data['name']
        );
        $datas['place'] = $data['type'] == 3 ? 2 :1;
        $index = $data['type'] == 2 ? 1:2;
        foreach ($result as $ind=>$item){
            $datas['list'][$ind]['gc_name'] = explode(' &gt;',$item->gc_name)[$index];
            $datas['list'][$ind]['brand'] = $item->brand_id;
        }
        return response()->json($datas);
    }


    /**
     * @param $type
     *  订单搜索区域
     * @return \Illuminate\Http\JsonResponse
     */
    public function serchResults($type)
    {
        $map = $this->request->all();
        $map['seller_id'] = session('user.member_id');
        unset($map['page']);
        if ($map['gc_name']) {
            $map['gc_name'] = ['like', '%' . $this->request->input('gc_name')];
        }
        //补充搜索条件
        if ( ! empty($map['feedback'])) {
            $arr_state = $this->getFeedback($map['feedback'], $map['order_state']);
        } else {
            $arr_state = ( ! empty($map['order_state'])) ? $this->stateMap($map['order_state']) : '';
        }
        unset($map['feedback']);
        unset($map['order_state']);
        if ( ! empty($map['seller_remark'])) {
            $map['seller_remark'] = ['like', '%' . $map['seller_remark'] . '%'];
        }

        if ($this->request->ajax()) {
            if ($type == 'active'){
                $lists = $this->order->getOrderSerach(array_filter($map), 'active',[],$arr_state);
                return response()->json(View('dealer.orders._layout.active_content',
                    compact('lists'))->render());
            }else{
                $times = [$map['str_time'], $map['end_time']];
                unset($map['str_time']);
                unset($map['end_time']);
                $lists = $this->order->getOrderSerach(array_filter($map), 'finishs', $times);
                return response()->json(View('dealer.orders._layout.finishs_content',
                    compact('lists'))->render());
            }
        }
    }


    //经销商订单的主体部分
    public function showOrder($id)
    {
        $order = HgOrder::getOrder($id, session('user.member_id'),'seller');
        $member = 'Sell';
        return $this->getAnalys($order, $member);
    }


    //store
    //联系方式和非原厂的保存
    public function storeNonFactory(OrderQuest $request)
    {
        //$status 3,$state 300
        $order = $this->check(
            config('orders.order_doposit'),
            config('orders.order_doposit_wait_pay')
        );
        $attr = $order->orderAttr;
        try {
            $order->order_state = config('orders.order_doposit_wait_pay2');
            $order->save();
            $order->addLog('seller',
                $order->order_status,
                $order->order_state,
                trans('orders.log.sellersubmittime')
            );
            $request->performUpdate($attr);
        } catch (InternalErrorException $exception) {
            $exception->getMessage();
        }
        return redirect()->back();
    }

    //发出交车邀请
    public function storeJaoche()
    {
        //保存交车时间
        //更改主状态为4 交车邀请
        //  子状态为已经 order_jiaoche_wait
        $order = $this->check(
            config('orders.order_doposit'),
            [
                config('orders.order_doposit_wait_pay2'),
                config('orders.order_doposit_admin')
            ]
        );
        if ($this->request->has('date')) {
            $order->orderDate->update(
                [
                    'jiaoche_times' => serialize($this->request->input('date'))
                ]);
            $order->order_state = config('orders.order_jiaoche_wait');
            $order->order_status = config('orders.order_jiaoche');
            $order->save();
            $order->addLog('seller',
                $order->order_status,
                $order->order_state,
                trans('orders.log.sellersubmitcartime')
            );
            //短信通知发送
            $this->sendMsg->sendSms(
                $order->orderUsers->phone,
                '78715085',
                ['order'=>$order->order_sn]
            );
        }
        return response()->json(['msg' => 'success']);
    }

    //主动终止订单
    public function storeStop($id)
    {
        //检验短信验证码
        //更改状态(主状态为0,子状态为 307) order_doposit_taskend
        $order = $this->check(3, 301);
        $phone = $order->orderMember->member_mobile;
        $code = $this->request->input('code');
        //验证短信验证码
        if ($this->sendMsg->VerifySms($phone,'78570085',$code)) {
            $order->order_state = config('orders.order_doposit_taskend');
            $order->save();
            $order->addLog('seller',
                $order->order_status,
                $order->order_state,
                '主动终止订单'
            );
            //短信通知发送
            $this->sendMsg->sendSms(
                $order->orderUsers->phone,
                '78630086',
                ['order'=>$order->order_sn]
            );
            return response()->json(['msg' => 'success', 'error_code' => '200']);
        }
        return response()->json(['msg' => 'error', 'error_code' => '0']);
    }


    //交车 有修改还得待用户确认
    public function editJaoche($id)
    {
        //保存用户提交的文件
        //将子状态改为order_doposit_not_confirm
        $order = $this->check(3, 301);
        $phone = $order->orderMember->member_mobile;
        $code = $this->request->input('code');
        //验证短信验证码
        if ($this->sendMsg->VerifySms($phone,'78570085',$code)) {
            $carinfo = $this->request->input('carinfo');
            $data['carinfo'] = serialize($carinfo);
            $data['xzj'] = serialize($this->request->input('xzj'));
            $data['zengpin'] = serialize($this->request->input('zengpin'));
            $data['status'] = config('orders.order_doposit_wait_pay2');
            $data['order_sn'] = $order->order_sn;
            $editinfo = $this->setCarinfo($carinfo);
            DB::transaction(function () use ($data, $order,$editinfo) {
                HgEditInfo::create($data);
                $order->order_state = config('orders.order_doposit_not_confirm');
                $order->save();
                $order->orderinfo()->update($editinfo);
                $order->addLog('seller',
                    $order->order_status,
                    $order->order_state,
                    '交车修改文件'
                );
            });
            //短信通知发送
            $this->sendMsg->sendSms(
                $order->orderUsers->phone,
                '78690081',
                ['order'=>$order->order_sn]
            );
            return response()->json(['msg' => 'success', 'error_code' => 200]);
        }

        return response()->json(['msg' => 'error', 'error_code' => 0]);
    }

/***********************************诚意预约相关部分***********************/
    /*
     * 售方反馈特需文件，无修改，等待客户确认
     * 状态2012 => 2032
     */
    public function storeSpecial($id)
    {
        $order = $this->check(
            config('orders.order_earnest'),
            config('orders.order_earnest_not_confirm_file')
        );
        $ziliao = $this->request->input('ziliao');
        DB::transaction(function () use ($order, $ziliao) {
            $data = [
                'status'        => 201,
                'shangpai_file' => serialize($ziliao)
            ];
            $order->orderEditinfo()->create($data);
            $order->rockon_time = Carbon::now()->addHours(24);
            $order->order_state = config('orders.order_earnest_eidt1');
            $order->save();
            $order->addLog('seller',
                $order->order_status,
                $order->order_state,
                trans('orders.log.sellerfeedbackfile')
            );
            //反馈特需文件,不修改
            $this->sendMsg->sendSms(
                $order->orderUsers->phone,
                '78715084',
                ['order'=>$order->order_sn]
            );
        });
        return redirect()->back();
    }


    //特需既修改.

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($id)
    {
        $order = $this->check(
            config('orders.order_earnest'),
            [
                config('orders.order_earnest_not_confirm'),
                config('orders.order_earnest_not_confirm_file')
            ]);
        $status = $this->request->has('state');
        //直接同意反馈
        if ( ! $status) {
            // 没有修改直接同意反馈,跳到支付担保金页面
            $order->order_status = config('orders.order_doposit');
            $order->order_state = config('orders.order_earnest_backok');
            $order->save();
            //记录日志
            $order->addLog('seller',
                $order->order_status,
                $order->order_state,
                trans('orders.log.feedbackorder')
            );
            //短信通知
            $this->sendMsg->sendSms(
                $order->orderUsers->phone,
                78610080,
                ['order'=>$order->order_sn]
            );
            return redirect()->back();
        }

       //...有修改数据保存修改数据
        try {
            $has_file = $this->request->has('ziliao');
            $carinfo = $this->request->intersect([
                'body_color',
                'interior_color',
                'year_month',
                'mileage',
                'paifang',
                'cycle'
            ]);
            $order->orderinfo()->update($carinfo);
            if ( ! HgEditInfo::insert([
                'carinfo' => serialize($carinfo),
                'xzj' => serialize($this->request->input('xzj')),
                'zengpin' => serialize($this->request->input('zhengpin')),
                'status' => 201,
                'shangpai_file' => serialize($this->request->input('ziliao')),
                'order_sn' => $order['order_sn']
            ])
            ) {
                dd('保存修改信息时出错');
            }
            if ($has_file) {
                $order->rockon_time = Carbon::now()->addHours(24);
                $order->order_state = config('orders.order_earnest_edit2');
                $msg_code = 78615084;
            } else {
                $order->rockon_time = Carbon::now()->addMinutes(20);
                $order->order_state = config('orders.order_earnest_confirm');
                $msg_code = 78560079;
            }
            $order->save();
            $order->addLog('seller',
                $order->order_status,
                $order->order_state,
                trans('orders.log.sellereditorder')
            );
        } catch (QueryException $e) {
            $e->getMessage();
        }
        //反馈文件,有修改
        $this->sendMsg->sendSms(
            $order->orderUsers->phone,
            $msg_code,
            ['order'=>$order->order_sn]
        );
        return redirect()->back();
    }


    // 售方主动终止
    public function setEndOreder($id)
    {
        $order = $this->check(
            config('orders.order_earnest'),
            [
                config('orders.order_earnest_not_confirm_file'),
                config('orders.order_earnest_not_confirm')
            ]
        );
        if ($order->order_state == config('orders.order_earnest_not_confirm_file')) {
            $msg_code = 78700068;
        } else {
            $msg_code = 78565076;
        }
        $this->sendMsg->sendSms(
            $order->orderUsers->phone,
            $msg_code,
            ['order'=>$order->order_sn]
        );
        $order->order_state = config('orders.order_earnest_dealer_end');
        $order->save();
        $order->addLog('seller',
            $order->order_status,
            $order->order_state,
            trans('orders.log.sellerendorder')
        );
        return redirect()->back();
    }


    //---------预约交车-----
    //402 售方反馈时间 402 / 403
    public function storeAppoint($id)
    {
        $order = $this->check(
            config('orders.order_jiaoche'),
            config('orders.order_jiaoche_sent_notify')
        );
        DB::transaction(function () use ($order) {
            $order->orderAppoint()->update($this->request->intersect([
                'is_feeback',
                'out_price',
                'seller_data',
                'seller_day'
            ]));
            if ($this->request->input('is_feeback')) {
                $data = [
                    'order_state' => config('orders.order_jiaoche_ok'),
                ];
                $log = trans('orders.log.selleragreedtime');
                //短信通知发送(售方同意客户交车时间)
                $this->sendMsg->sendSms(
                    $order->orderUsers->phone,
                    '78685092',
                    [
                        'order'=>$order->order_sn,
                        'money' => $this->request->input('out_price')
                    ]
                );
            } else {
                $data = [
                    'order_state' => config('orders.order_jiaoche_no'),
                ];
                $order->orderinfo()->update([
                    'car_jiaoche_at'  => $this->request->input('seller_data'),
                    'car_jiaoche_day' => $this->request->input('seller_day')
                ]);
                $log = trans('orders.log.sellerinthecartime');
                //短信通知发送(售方再次提出交车时间)
                $this->sendMsg->sendSms(
                    $order->orderUsers->phone,
                    '78585095',
                    ['order'=>$order->order_sn]
                );
            }
            $order->update($data);
            $order->addLog('seller',
                $order->order_status,
                $order->order_state,
                $log
            );
        });
        return response()->json(['code' => 'success']);
    }

    // 预约交车文件资料/服务专员 的选择
    public function storeDelivery()
    {
        $order = $this->check(
            config('orders.order_jiaoche'),
            config('orders.order_jiaoche_ok')
        );
        DB::transaction(function () use ($order) {
            $order->orderAppoint->files = $this->request->input('files');
            $order->orderAppoint->waiter_id = $this->request->input('waiter_id');
            $order->orderAppoint->save();
            $order->update([
                'order_state' => config('orders.order_jiaoche_confirm')
            ]);
            $order->addLog('seller',
                $order->order_status,
                $order->order_state,
                trans('orders.log.sellersupplementtime')
            );
        });
        //短信通知发送(售方完成预约专员)
        $this->sendMsg->sendSms(
            $order->orderUsers->phone,
            '78710076',
            [
                'order' => $order->order_sn
            ]
        );
        return redirect()->back();

    }

    //售方确认交车
    public function storeDeal($id)
    {
        $order = $this->check(
            [
                config('orders.order_jiaoche'),
                config('orders.order_deal')
            ],
            [
                config('orders.order_jiaoche_confirm'),
                config('orders.order_jiaoche_member')
            ]
        );
        try {
            if ($order->order_status == config('orders.order_jiaoche')) {
                //主状态为5
                $order->order_status = config('orders.order_deal');
                $order->order_state = config('orders.order_jiaoche_seller');
                $msg_code = 78565078;
            } else {
                $order->order_state = config('orders.order_jiaoche_all');
                $msg_code = 78750084;
            }
            $order->save();
            $order->addLog('seller',
                $order->order_status,
                $order->order_state,
                trans('orders.log.selleraffirmincartime')
            );
            //短信通知发送(售方完成预约专员)
            $this->sendMsg->sendSms(
                $order->orderUsers->phone,
                $msg_code
            );
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    //--------------协商部分
    public function storeConsult(HgOrder $order)
    {
        //用户是否已经确认
        switch ($order->order_status) {
            case config('orders.order_earnest'):
                $member_state = config('orders.order_sincerity_member_result');
                $sell_state = config('orders.order_sincerity_seller_result');
                $main_state = config('orders.order_sincerity_all_result');
                break;
            case config('orders.order_doposit'):
                $member_state = config('orders.order_doposit_member_result');
                $sell_state = config('orders.order_doposit_seller_result');
                $main_state = config('orders.order_doposit_all_result');
                break;
            case config('orders.order_jiaoche'):
                $member_state = config('orders.order_jiaoche_member_result');
                $sell_state = config('orders.order_jiaoche_seller_result');
                $main_state = config('orders.order_jiaoche_all_result');
                break;
        }
        if ($order->order_state == $member_state) {
            $order->order_state = $main_state;
            //短信通知发送(客户已同意协商)
            $this->sendMsg->sendSms(
                $order->orderUsers->phone,
                $msg_code = 78795073,
                [
                    'order' => $order->order_sn
                ]
            );
        } else {
            $order->order_state = $sell_state;
        }
        DB::transaction(function () use ($order) {
            $order->save();
            HcOrderConciationConsultExtension::updateTmie([
                'consult_id' => $this->request->input('consult_id'),
                'user_type'  => 2
            ]);
            $order->addLog('seller',
                $order->order_status,
                $order->order_state,
                trans('orders.log.selleraffirmnegotiate')
            );
        });
        return redirect()->back();
    }



    //检查是否本车
    public function check($status, $state)
    {
        $id = $this->request->id;
        return HgOrder::checkOrder($id, session('user.member_id'), $status, $state,'seller');
    }


    //获取短信验证码
    public function getCode()
    {
        $phone = HgUser::find(session('user.member_id'))->member_mobile;
        $template_code = $this->request->input('template_code');
        $order_sn = $this->request->input('order');
        $this->sendMsg->sendSms(
            $phone,
            $template_code,
            [
                'code'  => random(6, 1),
                'order' => $order_sn
            ]
        );
        return response()->json(['msg'=>'success','error_code'=>200]);
    }

    public function setCarinfo($carinfo)
    {
        if ($carinfo['body']['body_color']) {
            $data['body_color'] = $carinfo['body']['body_color'];
        }
        if ($carinfo['inter']['interior_color']) {
            $data['interior_color'] = $carinfo['inter']['interior_color'];
        }
        if ($carinfo['licheng']['licheng']) {
            $data['mileage'] = $carinfo['licheng']['licheng'];
        }
        if ($carinfo['jiaoche']['jiaoche_at']) {
            $data['car_astrict'] = $carinfo['jiaoche']['jiaoche_at'];
        }
        if ($carinfo['year']['year']) {
            $temp['year'] = $carinfo['year']['year'];
        } else {
            $temp['year'] = $carinfo['year']['old_year'];
        }
        if ($carinfo['month']['month']) {
            $temp['month'] = $carinfo['month']['month'];
        } else {
            $temp['month'] = $carinfo['month']['old_month'];
        }
        $data['year_month'] = $temp['year'] . '-' . $temp['month'];

        return $data;

    }


    //---------------------搜索组件----------------------
    //开始预约,等待反馈,准备交车等状态
    public function stateMap($key)
    {
        $map = [
            'feedback' => [2011, 2012, 2021, 2022, 2031, 2032, 623, 622],
            'ready'    => [300, 301, 303, 309, 633, 632],
            'reserva'  => [400, 401, 403, 402, 404, 643, 642],
            'delivery' => [501, 502]
        ];
        if ( ! array_key_exists($key, $map)) {
            return [];
        }
        return $map[$key];
    }

    //待反馈,无需反馈
    public function getFeedback($type, $key = null)
    {
        $map = [
            'rend'   => [
                'feedback' => [2011, 2012, 623],
                'ready'    => [301, 309, 633],
                'reserva'  => [401, 402, 404, 643]
            ],
            'norend' => [
                'feedback' => [2021, 2022, 2031, 2032, 622],
                'ready'    => [300, 303, 632],
                'reserva'  => [400, 403, 642],
                'delivery' => [501, 502]
            ]
        ];
        if ( ! $key) {
            return array_collapse($map[$type]);
        }

        if ( ! array_key_exists($key, $map[$type])) {
            return [];
        }
        return $map[$type][$key];
    }




}
