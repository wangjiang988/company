<?php

namespace App\Http\Controllers\Front;

use App\Models\HcOrderConciationConsultExtension;
use App\Models\HcOrderInfo;
use App\Models\HgBaojiaXzj;
use App\Models\HgCarInfo;
use App\Models\HgDailiDealer;
use App\Models\HgOrder;
use App\Models\HgOrderXzjGift;
use App\Models\SendSmsLog;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HgBaojia;
use DB;
use Auth;
use Carbon\Carbon;
use App\Services\ActionOrderTrait;

class CartController extends Controller
{
    use ActionOrderTrait;

    protected $order_id;
    protected $request;
    protected $sendMsg;
    protected $smsList = [
        'payment' => 78580069,
        'jiaoche_time' => 78560074,
    ];

    public function __construct(Request $request,SendSmsLog $sendMsg)
    {
        $this->request = $request;
        $this->sendMsg = $sendMsg;
    }

    //订单生成
    public function oneIndex($data, Request $request)
    {
        //判断是否华车工作时间下单
        if (!check_job_time()) {
            return view('HomeV2.User.Earnest.buy_fail');
        }

        //判断报价车源数量
        $secret = $this->CheckSource($data, 'HwaChe@cn');
        $baojia = HgBaojia::getBaojiaInfo($secret['id']);
        if (!$baojia || $baojia->bj_num <= 0) {
            return view('HomeV2.User.Earnest.pay_fail');
        }

        //判断是否商家工作时间下单
        if (!check_daili_worktime($baojia['daili_dealer_id'])) {
            return view('HomeV2.User.Earnest.pay_fail');
        }

        $shangpai_id = $secret['shangpai_id'];
        //我已取得牌照指标为1
        $attr['license_tag'] = $request->input('license_tag', 0);
        //客户提交文件
        $special_file = $request->input('special_file', 0);
        if ($special_file) {
            $attr['file_comment'] = implode('、', $request->input('file'));

        }
        //上牌相关,呈现不同页面效果,子状态
        $shangpai_status = $request->input('shangpai_status');
        if ($secret['shangpai_id'] == 4) {
            $attr['is_arrangement'] = 1;
        }
        if (($shangpai_status == 1 || in_array($shangpai_id, [1, 3])) && $special_file) {
            $attr['show_status'] = 3;
        } else {
            $attr['show_status'] = 0;    //普通状态
        }
        //修改版的上牌身份
        $attr['shangpai_status'] = ($secret['shangpai_id'] == 4) ? $shangpai_status : $secret['shangpai_id'];

        //上牌地区
        $area_id = $request->input('area');

        //买车担保金
        $bj = $baojia->toArray();
        $shi = $request->input('shi');
        $attr['cart_color'] = $bj['bj_body_color'];
        $attr['car_brand'] = strstr($bj['gc_name'],' &gt;',True);
        $client_sponsion_price = ($area_id == $shi) ? $bj['client_sponsion_price_low'] : $bj['client_sponsion_price_high'];
        // 保存到数据库购物车中
        $addData = [
            'user_id'         => Auth::id(),
            'seller_id'       => $bj['m_id'],
            'dealer_id'       => $bj['dealer_id'],
            'daili_dealer_id' => $bj['daili_dealer_id'],
            'order_sn'        => $this->get_order_sn(),
            'bj_id'           => $secret['id'],
            'brand_id'        => $bj['brand_id'],
            'gc_name'         => $bj['gc_name'],
            'dealer_name'     => preg_replace("/\(.*\)/", '', $bj['dealer_name']),
            'shangpai_price'  => $bj['agent_numberplate_price'],
            'sponsion_price'  => $client_sponsion_price,
            'hwache_price'    => $bj['hwache_price'],
            'shangpai_area'   => $area_id,//上牌地区
            'special_file'    => $special_file,
            'created_at'      => Carbon::now(),
            'updated_at'      => Carbon::now(),
            'is_xianche'      => $bj['bj_is_xianche'],
            'order_status'    => config('orders.order_place'), // 订单状态：1
            'order_state'     => 100
        ];
        // 开启事务保存数据
        DB::transaction(function () use ($addData, $attr) {
            $order = HgOrder::create($addData);
            if (!is_null($order)) {
                $this->order_id = $order->id;

                // 添加订单价格表
                $order->orderAttr()->create($attr);
                $this->setCarinfo($order);

                // 添加订单详细数据
                $order->addLog('member', $order->order_status, '100', '开始下单');
            }
        });
        // 刷新token
        rebuild_token();

        //跳转支付诚意金页面
        return redirect()->route('pay.earnest',['id'=>$this->order_id]);
    }

    //客户的主体部分
    public function showOrder($id)
    {
        $user_id = Auth::id();
        $order = HgOrder::getOrder($id, $user_id,'member');
        $member = 'Member';
        return $this->getAnalys($order, $member);
    }

    /*********************诚意预约--用户同意修改*******************/
    public function storeStatus(Request $request, $type)
    {
        $order = $this->check(
            config('orders.order_earnest'),
            [
                config('orders.order_earnest_eidt3'),
                config('orders.order_earnest_confirm')
            ]
        );
        if ($request->input('status')) {
            $order->order_status = config('orders.order_doposit');
            $order->order_state = config('orders.order_earnest_backok');
            $order->save();
            $order->addLog('member',
                $order->order_status,
                $order->order_state,
                trans('orders.log.memberacceptedit')
            );
            //用户同意修改
        $this->sendMsg->sendSms(
            $order->orderMember->member_mobile,
            78725074,
            ['order' => $order->order_sn]
        );
            rebuild_token();
            return redirect()->back();
        }
    }


    //选择用户要办理的内容
    public function storeAccept(Request $request)
    {
        $order = $this->check(
            config('orders.order_earnest'),
            [
                config('orders.order_earnest_eidt1'),
                config('orders.order_earnest_edit2')
            ]
        );
        $ziliao = $request->input('ziliao', '');
        //如果有特需文件被选中
        if (is_array($ziliao)) {
            $files = [];
            foreach ($ziliao as $item) {
                $files[] = explode('|', $item['title']);
            }
            $data['new_file_comment'] = serialize($files);
            $data['new_file_days'] = max(array_column($files, 1));
            $order->orderAttr()->update($data);
        }
        //如果客户状态是特需有修改就还是主状态为2
        DB::transaction(function () use ($order) {
            if ($order->order_state == config('orders.order_earnest_edit2')) {
                $order->order_state = config('orders.order_earnest_eidt3');
            } else {
                $order->order_status = config('orders.order_doposit');
                $order->order_state = config('orders.order_earnest_backok');
            }
            $order->save();
            $order->addLog('member',
                $order->order_status,
                $order->order_state,
                trans('orders.log.memberfieledit')
            );
        });
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *  终止订单,退还诚意金
     */
    public function endOrder(Request $request)
    {
        //2022 接受特需,不接受修改
        //2031 不接受特需且有修改
        //2032 不接受特需 无修改
        //2021 普通反馈,(放弃订单)
        $order = $this->check(
            config('orders.order_earnest'),
            [
                config('orders.order_earnest_eidt3'),
                config('orders.order_earnest_edit2'),
                config('orders.order_earnest_eidt1'),
                config('orders.order_earnest_confirm')
            ]
        );
        //不接受特需而终止的
        if (in_array($order->order_state, [
            config('orders.order_earnest_edit2'),
            config('orders.order_earnest_eidt1')
        ])) {
            $order->order_state = config('orders.order_seller_earnest_end');
            $msg_code = 78590070;
        } else {
            //不接受修改文件而终止
            $order->order_state = config('orders.order_seller_time_end');
            $msg_code = 78530071;
        }
        $order->save();
        $order->addLog('member',
            $order->order_status,
            $order->order_state,
            trans('orders.log.memberdontacceptover')
        );
        //添加通知短信
        $this->sendMsg->sendSms(
            $order->orderMember->member_mobile,
            $msg_code,
            ['order' => $order->order_sn]
        );
        rebuild_token();
        return redirect()->back();
    }


//---------------------------------------------------担保金-----------------------------\\
    //支付担保金,一次性支付完成
    public function storeSecurity(Request $request)
    {
        //判断来源,203
        //主状态改为3,子状态为300 or 301 =>存在特别文件办理、或非原厂选装精品
        //支付逻辑,不管支付具体过程
        //主状态改为3,子状态改为300,
        //判断好交车时间
        $order = $this->check(
            config('orders.order_doposit'),
            config('orders.order_earnest_backok')
        );
        $count = count(HgDailiDealer::getOptionLists($order->daili_dealer_id,$order->brand_id));
        DB::transaction(function () use ($order, $count) {
            try {
                if ($order->orderAttr->new_file_comment || $count) {
                    $order->order_state
                        = config('orders.order_doposit_wait_pay');
                } else {
                    $order->order_state
                        = config('orders.order_doposit_wait_pay2');
                }
                $order->save();
                if ($order->orderBaojia->bj_is_xianche) {
                    $times = Carbon::now()->addDay(15
                        + $order->orderAttr->new_file_days);
                } else {
                    $times = Carbon::now()
                        ->addMonth($order->orderBaojia->bj_jc_period)
                        ->addDay($order->orderAttr->new_file_days);
                }
                $order->orderinfo()->update(['car_astrict'=>$times]);
                $date = date('Y-m-d', strtotime($times)) . ' 23:59:59';
                $order->orderDate()->create(['jiaoche_at' => $date]);
                $order->addLog('member',
                    $order->order_status,
                    $order->order_state,
                    trans('orders.log.securityintojiaxinbao')
                );
            } catch (QueryException $e) {
                 $e->getMessage();
            }
            //添加通知短信
            $this->sendMsg->sendSms(
                $order->orderMember->member_mobile,
                $this->smsList['payment'],
                [
                    'order' => $order->order_sn,
                    'day'   => date('Y年m月d日', strtotime($times))
                ]
            );
        });
        return redirect()->back();
    }

    //客户接受修改或终止订单
    public function storeContinue($id, $type)
    {
        $order = $this->check(3, 303);
        if ($type == 'send') {
            //接受修改,等待发出交车邀请 301
            $order->order_state = config('orders.order_doposit_wait_pay2');
            $order->save();
            $order->addLog('member',
                $order->order_status,
                $order->order_state,
                trans('orders.log.memberacceptcartime')
            );

        }
        if ($type == 'stop') {
            //终止订单
            $order->order_state = config('orders.order_doposit_not_edit');
            $order->save();
            $order->addLog('member',
                $order->order_status,
                $order->order_state,
                '用户不同意修改主动终止'
            );
        }
        return redirect()->back();
    }

    //------------预约交车部分
    //答复交车部分
    public function storeReply($id)
    {
        $array = [
            1 => '上牌地本市户籍居民',
            3 => '国内其他非限牌城市户籍居民',
            5 => '中国军人',
        ];
        $order = $this->check(4, 400);
        $active = $this->request->input('car_purpose');
        $judge = $this->request->input('default_data');
        DB::transaction(function () use ($order, $active, $array,$judge) {
            if ($active == 1) {  //公司自用车
                $order->orderAppoint()
                    ->Create($this->request->except('_token','ipAddress'));
            } else {  //无
                $order->orderAppoint()->Create($this->request->except('_token',
                    'identity_type','ipAddress'));
            }

            if ( ! $active) {
                $type = $this->request->input('identity_type');
                if (strpos($type, '|')) {
                    $type = explode('|', $type)[1];
                } else {
                    $type = $array[$type];
                }
                $identity_type = getIdentity_id($type);
                $order->orderAppoint()
                    ->update(['identity_type' => $identity_type]);
            }
            if ( ! $judge) {
                $order->update([
                    'order_state' => config('orders.order_jiaoche_sent_notify')
                ]);
                $order->orderinfo()
                    ->update([
                        'car_jiaoche_at'  => $this->request->input('member_data'),
                        'car_jiaoche_day' => $this->request->input('member_day')
                    ]);
                $langue = trans('orders.log.memberfeedbacktime');
                //添加通知短信(客户重新提交交车时间)
                $this->sendMsg->sendSms(
                    $order->orderMember->member_mobile,
                    $this->smsList['jiaoche_time'],
                    ['order' => $order->order_sn]
                );
            } else {
                $order->update([
                    'order_state' => config('orders.order_jiaoche_ok')
                ]);
                $order->orderinfo()
                    ->update([
                        'car_jiaoche_at'  => $judge,
                        'car_jiaoche_day' => $this->request->input('default_day')
                    ]);
                $langue = trans('orders.log.memberacceptcartime');
                //添加通知短信(客户同意售方交车时间)
                $this->sendMsg->sendSms(
                    $order->orderMember->member_mobile,
                    78580070,
                    [
                        'order' => $order->order_sn,
                        'time'  => date('Y年m月d日',strtotime($judge)),
                        'money' => 0,
                    ]
                );
            }

            $order->addLog('member',
                $order->order_status,
                $order->order_state,
                $langue
            );

        });
        return response()->json(['code' => '1', 'message' => 'success']);
    }


    //403 再次确认
    public function storeAgain($id)
    {
        $order = $this->check(4, 403);
        $order->orderAppoint()
            ->update(['is_feeback' => 2]);
        $order->update([
            'order_state' => config('orders.order_jiaoche_ok')//确认就ok了
        ]);
        //发送短信通知
        $this->sendMsg->sendSms(
            $order->orderMember->member_mobile,
            78580070,
            [
                'order' => $order->order_sn,
                'time'  => date('Y年m月d日',strtotime($order->orderAppoint->seller_data)),
                'money' => $order->orderAppoint->out_price,
            ]
        );
        $order->addLog('member',
            $order->order_status,
            $order->order_state,
           trans('orders.log.memberagreedcartime')
        );
        return response()->json(['code' => '1', 'message' => 'success']);
    }

    //-----------交车之后部分-确认交车-------------------
    public function storeDeal($id)
    {
        $order = $this->check(
            [
                config('orders.order_jiaoche'),
                config('orders.order_deal')
            ],
            [
                config('orders.order_jiaoche_confirm'),
                config('orders.order_jiaoche_seller')
            ]
        );
        try {
            if ($order->order_status == config('orders.order_deal')) {
                $order->order_state = config('orders.order_jiaoche_all');
            } else {
                $order->order_status = config('orders.order_deal');
                $order->order_state = config('orders.order_jiaoche_member');
            }
            $order->save();
            //发送短信通知
            $msg_code = ($order->order_state
                == config('orders.order_jiaoche_member')) ? 78610078 : 78630083;
            $this->sendMsg->sendSms(
                $order->orderMember->member_mobile,
                $msg_code,
                ['order' => $order->order_sn]
            );

            $order->addLog('member',
                $order->order_status,
                $order->order_state,
                trans('orders.log.memberagreedinthechetime')
            );
        } catch (Exception $e) {
            dd('数据处理失败');
        }
    }

    //交车完成,退还担保金部分
    public function storeRefundPrice($id)
    {
        //Todo 退还部分担保金
        $order = $this->check(config('orders.order_deal'),
            config('orders.order_jiaoche_all'));
        $code = intval($this->request->input('code'));
        $phone = Auth::user()->phone;
        if ($this->sendMsg->VerifySms($phone, '78715078', $code)) {
            $order->order_state = config('orders.order_settlement_security');
            $order->save();
            $data = [
                'code'    => '1',
                'message' => 'success',
            ];
        } else {
            $data = [
                'code'    => '0',
                'message' => 'error',
            ];
        }
        //短信发送通知
        $this->sendMsg->sendSms(
            $order->orderMember->member_mobile,
            78760082,
            ['order' => $order->order_sn]
        );
        $order->addLog('member',
            $order->order_status,
            $order->order_state,
            trans('orders.log.memberagreedsettlement')
        );
        return response()->json($data);
    }

    //客户评价
    public function storeComment(HgOrder $order)
    {
        $data = [ $this->request->except('_token') ];
        $data[0]['buy_id'] = Auth::user()->id;
        DB::transaction(function () use ($order, $data) {
            $order->orderComment()->Create($data[0]);
            $order->addLog('member',
                $order->order_status,
                $order->order_state,
                trans('orders.log.memberevaluate')
            );
        });
        return redirect()->back();
    }

    //-------------------------------协商交车-------------------------
    public function storeNegotiation(HgOrder $order)
    {
        $code = intval($this->request->input('code'));
        $phone = Auth::user()->phone;
        if ( ! $this->sendMsg->VerifySms($phone, '78545072', $code)) {
            return response()->json(['code' => 0, 'message' => 'errors']);
        }
        //一系列的协商处理
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
        if ($order->order_state == $sell_state) {
            $order->order_state = $main_state;
        } else {
            $order->order_state = $member_state;
        }
        DB::transaction(function () use ($order) {
            $order->save();
            HcOrderConciationConsultExtension::updateTmie([
                'consult_id' => $this->request->input('consult_id'),
                'user_type'  => 1
            ]);
            $order->addLog('member',
                $order->order_status,
                $order->order_state,
                trans('orders.log.memberaffirmnegotiate')
            );
        });
        //短信通知协商终止
        $this->sendMsg->sendSms(
            $order->orderMember->member_mobile,
            78720060,
            ['order' => $order->order_sn]
        );
        return response()->json(['code' => 1, 'message' => 'success']);
    }


    //检查是否本车
    public function check($status, $state)
    {
        $id = $this->request->id;
        $user_id = Auth::id();
        return HgOrder::checkOrder($id, $user_id, $status, $state,'member');
    }

    //获取验证码
    public function getCode()
    {
        $phone = Auth::user()->phone;
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

    /*
     * /**
     * @param $urls
     * @param $key
     * @return int
     */
    public function CheckSource($urls, $key)
    {
        $urls = explode(',', hc_decrypt($urls, $key));
        if (count($urls) == 2 && (int)($urls[1])) {
            $dedata['id'] = (int)($urls[0]);
            $dedata['shangpai_id'] = (int)($urls[1]);
            return $dedata;
        }
        abort(500);
    }


    /**
     * 检测订单号是否重复
     * @param null $sn
     * @return null|string
     */
    private function get_order_sn($sn = null)
    {
        if (empty($sn)) {
            $sn = generate_sn();
        }
        $check_sn = DB::table('hg_cart')
            ->where('order_num', $sn)
            ->count();
        if ($check_sn == 0) {
            return $sn;
        } else {
            $this->_get_order_sn();
        }
    }

    //基础数据存储
    public function setCarinfo($order)
    {
        try {
            $baojia = HgBaojia::find($order->bj_id);
            $car_info = HgCarInfo::getInteriorColor($order->bj_id,
                $order->brand_id);
            $originals = HgBaojiaXzj::getXzjType($order->bj_id);
            $data = [
                'body_color'     => $baojia['bj_body_color'],
                'interior_color' => $car_info['nside_color'],
                'mileage'        => $baojia['bj_licheng'],
                'car_seating'    => $car_info['seat_num'],
                'year_month'     => $baojia['bj_producetime'],
                'car_guobie'     => $car_info['guobie']
            ];
            $order->orderinfo()->create($data);
        } catch (\Illuminate\Database\QueryException $e) {
            return $e->getMessage();
        }
    }
}
