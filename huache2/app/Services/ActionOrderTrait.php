<?php

namespace App\Services;

use App\Http\Controllers\Orders\NegotiateController;
use App\Models\HgOrder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Controllers\Orders\GuaranteesController;
use App\Http\Controllers\Orders\CancelController;
use App\Http\Controllers\Orders\SincerityController;
use App\Http\Controllers\Orders\SubscribeController;
use App\Http\Controllers\Orders\DealsController;
use App\Http\Controllers\Orders\OrderComplete;

trait ActionOrderTrait
{
    protected $countlist = [
        'Cancel',      //0 取消
        'Place',       //1下单
        'Sincerity',   //2诚意金
        'Security',    //3担保金
        'SubscribeCar', //4预约交车
        'Orderdeal',     //5交车以后
        'Negotiate', //协商终止
        '99' => 'Complete'  //订单完成
    ];  //0取消,1下单,2,诚意金,3担保金,4交车

    public function getAnalys(HgOrder $order, $type)
    {
        if (array_key_exists($order->order_status, $this->countlist)) {
            $method = 'get' . $this->countlist[$order->order_status];
            return $this->$method($order->order_state, $order, $type);
        }

        throw new NotFoundHttpException('该订单不存在');
    }


    //----------------------------------诚意金相关;
    public function getSincerity($state, $order, $type)
    {
        $countlist = [
            config('orders.order_earnest_not_confirm') => 'get' . $type . 'Details',     //2011
            config('orders.order_earnest_not_confirm_file') =>  'get' . $type . 'Details',  //2012
            config('orders.order_earnest_confirm')     => 'get' . $type . 'Feedback',      //202
            config('orders.order_earnest_eidt1')       => 'get' . $type . 'Feedfile',      //2021 特需无修改
            config('orders.order_earnest_eidt3')       => 'get' . $type . 'Acceptfile',       //2023
            config('orders.order_earnest_edit2')       => 'get' . $type . 'SpecialEdit',   //2022
            config('orders.order_sincerity_member_invasion') => 'get' .$type . 'Reason',
            config('orders.order_sincerity_seller_invasion') => 'get' .$type . 'Case',

            config('orders.order_sincerity_negotiation') => 'get'. $type . 'Confirm',
            config('orders.order_sincerity_member_result') => 'get'. $type . 'aAgreed',
            config('orders.order_sincerity_seller_result') => 'get'. $type . 'bAgreed',
            config('orders.order_sincerity_all_result') => 'get'. $type . 'Result',
            //售方主动终止
            config('orders.order_earnest_dealer_end') => 'get' . $type . 'Takeend',
            config('orders.order_seller_time_end')    => 'get' . $type .'TakeRefuse',
            config('orders.order_seller_earnest_end') => 'get' . $type .'SpecialEnd'
        ];
        if (array_key_exists($state, $countlist)) {
            $method = $countlist[$state];
            return app(SincerityController::class)->$method($order);
        }
        throw new NotFoundHttpException('该订单不存在');
    }

    //--------------------------------------担保金相关------------------------------
    //担保金
    public function getSecurity($state, $order, $type)
    {
        $countlist = [
            config('orders.order_earnest_backok')      => 'pay' . $type . 'Guarantee',
            config('orders.order_doposit_wait_pay')    => 'get' . $type . 'File',
            config('orders.order_doposit_wait_pay2')   => 'get' . $type . 'Await', // 无特需文件
            config('orders.order_doposit_not_confirm') => 'get' . $type . 'Confirma', //售房修改订单,待确认
            config('orders.order_doposit_admin')       => 'get' . $type . 'Admin', //平台终止倒计时
          //裁判部分
            config('orders.order_doposit_member_invasion') => 'get' .$type . 'Reason',
            config('orders.order_doposit_seller_invasion') => 'get' .$type . 'Case',
           //协商部分
            config('orders.order_doposit_negotiation') => 'get'. $type . 'Confirm',
            config('orders.order_doposit_member_result')=> 'get'. $type . 'aAgreed',
            config('orders.order_doposit_seller_result')=> 'get'. $type . 'bAgreed',
            config('orders.order_doposit_all_result')=> 'get'. $type . 'Result',
            //担保金违约
            config('orders.order_doposit_end')       => 'get' . $type .'DopositEnd',
            //售方主动终止
            config('orders.order_doposit_taskend')   => 'get' . $type . 'TaskEnd',
            //客户不接受而终止
            config('orders.order_doposit_not_edit')   => 'get' . $type . 'Refuse',
            //超时自动终止
            config('orders.order_doposit_timeout')  => 'get'.$type.'DopositTimeOut',
        ];
        if (array_key_exists($state, $countlist)) {
            $method = $countlist[$state];
            return app(GuaranteesController::class)->$method($order);
        }
        throw new NotFoundHttpException('该订单不存在');
    }

    //------------------------------------预约交车---------------------------------
    public function getSubscribeCar($state, $order, $type)
    {
        $countlist = [
            config('orders.order_jiaoche_wait') => 'get' . $type . 'Prepara',
            config('orders.order_jiaoche_sent_notify') => 'get'.$type.'Feedback',
            config('orders.order_jiaoche_no') => 'get'.$type.'Answer',
            config('orders.order_jiaoche_ok') => 'get'.$type.'Formation',
            config('orders.order_jiaoche_confirm') => 'get'.$type.'Finish',

            config('orders.order_jiaoche_member_invasion') => 'get' .$type . 'Reason',
            config('orders.order_jiaoche_seller_invasion') => 'get' .$type . 'Case',
            //裁判部分
            config('orders.order_doposit_member_invasion') => 'get' .$type . 'Reason',
            config('orders.order_doposit_seller_invasion') => 'get' .$type . 'Case',
            //协商部分
            config('orders.order_jiaoche_negotiation') => 'get'. $type . 'Confirm',
            config('orders.order_jiaoche_member_result')=> 'get'. $type . 'aAgreed',
            config('orders.order_jiaoche_seller_result')=> 'get'. $type . 'bAgreed',
            config('orders.order_jiaoche_all_result')=> 'get'. $type . 'Result',
        ];
        if (array_key_exists($state, $countlist)) {
            $method = $countlist[$state];
            return app(SubscribeController::class)->$method($order);
        }
        throw new NotFoundHttpException('该订单不存在');
    }

    //---------------------------------交车以后----------------------------------
    public function getOrderdeal($state, $order, $type)
    {
        $countlist = [
            config('orders.order_jiaoche_member') => 'get' . $type . 'Affirm',
            config('orders.order_jiaoche_seller') => 'get' . $type . 'Confirm',
            config('orders.order_jiaoche_all')     => 'get' . $type . 'PriceSettle',
            config('orders.order_settlement_security') => 'get' . $type .'Appraise',
            config('orders.order_settlement_have') => 'get' . $type .'Have',
            config('orders.order_settlement_Income') => 'get' . $type .'Income'
        ];
        if (array_key_exists($state, $countlist)) {
            $method = $countlist[$state];
            return app(DealsController::class)->$method($order);
        }
        throw new NotFoundHttpException('该订单不存在');
    }

    //------------------------------订单完成-------------------------------------
    public function getComplete($state, $order, $type)
    {
        $countlist = [
            config('orders.order_deal_appraise') => 'get' . $type . 'Appraise',
            config('orders.order_deal_record')   => 'get' . $type . 'Record',
        ];
        if (array_key_exists($state, $countlist)) {
            $method = $countlist[$state];
            return app(OrderComplete::class)->$method($order);
        }
        throw new NotFoundHttpException('该订单不存在');
    }

}