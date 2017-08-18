<?php namespace App\Http\Controllers\Orders;

use App\Models\HgBaojia;
use App\Models\HgBaojiaXzj;
use App\Models\HgCarInfo;
use App\Models\HgEditInfo;
use App\Services\NegotiateAtrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

//诚意金相关
class SincerityController
{
    use NegotiateAtrait;
    //Member 部分
    //第一步付完499；201
    public function getMemberDetails($order)
    {
//        $this->setTiemOut(
//            $order,
//            config('orders.order_earnest_backok'),
//            config('orders.order_doposit')
//        );
        return view('cart.Sellerconfirmed')->with(compact('order'));
    }

    //售方无特殊文件直接 反馈修改 202
    public function getMemberFeedback($order)
    {
//        $this->setTiemOut($order,config('orders.order_seller_time_end'),config('orders.order_earnest'));   //超时默认自动终止订单
        $result = $this->editFiles($order,201);
        return view('cart.Sellerfeedback',compact('result'))->with(compact('order'));
    }

    //有特殊文件进行了反馈 无修改
    public function getMemberFeedfile($order)
    {
        $result = $this->editFiles($order, 201);
//        $this->setTiemOut($order, config('orders.order_seller_earnest_end'),
//            config('orders.order_earnest'));
        return view('cart.Sellerfeedfile', compact('result'))
            ->with(compact('order'));
    }

    //客户接受了特殊文件
    public function getMemberAcceptfile($order)
    {
        $result = $this->editFiles($order,201);
        return view('cart.Sellacceptfile', compact('result'))->with(compact('order'));
    }


    //特需有修改
    public function getMemberSpecialEdit($order)
    {
       return $this->getMemberFeedfile($order);
    }

    //诚意金售方主动终止
    public function getMemberTakeend($order)
    {
        return view('cart.Shelltakeend')->with(compact('order'));
    }

    //不接受特需而终止
    public function getMemberSpecialEnd($order)
    {
        return view('cart.Sellerfeedfile_end')->with(compact('order'));
    }




    //--------------seller-------------
    //201显示内容
    public function getSellDetails($order)
    {
//        $this->setTiemOut(
//            $order,
//            config('orders.order_earnest_backok'),
//            config('orders.order_doposit')
//        );
        $view = $this->getData($order->bj_id,$order->brand_id);
        return view('dealer.orders.order_details',$view)->with(compact('order'));
    }

    //有修改,待客户确认修改
    public function getSellFeedback($order)
    {
        $view = $this->getData($order->bj_id,$order->brand_id);
        $view['car'] = HgEditInfo::getEditInfo($order->order_sn, 201);
        return view('dealer.orders.order_details_update', $view)->with(compact('order'));
    }

    //客户不接受
    public function getMemberTakeRefuse($order)
    {
        return view('cart.Sellertakend0')->with(compact('order'));
    }


    //特需部分
    //特需无修改
    public function getSellFeedfile($order)
    {
        $view = $this->getData($order->bj_id,$order->brand_id);
        $view['car'] = HgEditInfo::getFeedfile($order->order_sn, 201);
        return view('dealer.orders.order_details_feedfile', $view)->with(compact('order'));
    }

    public function getSellSpecialEdit($order)
    {
        $view = $this->getData($order->bj_id,$order->brand_id);
        $view['car'] = HgEditInfo::getEditInfo($order->order_sn, 201);
        return view('dealer.orders.order_details_update', $view)->with(compact('order'));
    }

    //有特需有修改 ,等待客户操作2023
    public function getSellAcceptfile($order)
    {
       return $this->getSellFeedback($order);
    }

    //售方主动终止
    public function getSellTakeend($order)
    {
        $view = $this->getData($order->bj_id, $order->brand_id);
        return view('dealer.orders.order_details_end', $view)
            ->with(compact('order'));
    }

    //客户不接受修改,终止订单 205
    public function getSellTakeRefuse($order)
    {
        $view = $this->getData($order->bj_id,$order->brand_id);
        return view('dealer.orders.order_details_member_end',$view)->with(compact('order'));

    }

    //诚意金 客户不接受特需修改而终止
    public function getSellSpecialEnd($order)
    {
        $view = $this->getData($order->bj_id,$order->brand_id);
        return view('dealer.orders.order_details_feedfile_end',$view)->with(compact('order'));
    }


    /**
     * 时间操时方法
     * @param $order
     * @param $state
     * @param null $status
     * @return mixed
     */
//    public function setTiemOut($order, $state, $status = null)
//    {
//        if (Carbon::now() > $order->rockon_time) {
//            $update = isset($status)
//                ? ['order_state' => $state, 'order_status' => $status]
//                :
//                ['order_state' => $state];
//            $order->update($update);
//            $order->addLog('Member',
//                $order->order_status,
//                $order->order_state,
//                '倒计时超时,自动终止'
//            );
//            return redirect()->back();
//        }
//    }

    public function editFiles($order,$status)
    {
        $result['car'] = HgEditInfo::getEditInfo($order->order_sn, $status);
        $result['car_info'] = HgCarInfo::getInteriorColor($order->bj_id, $order->brand_id);
        return $result;
    }

    //数据读取
    public function getData($bj_id,$brand_id)
    {
        $view['baojia'] = App(HgBaojia::class)->getBaojiaData($bj_id);
        $view['car_info'] = HgCarInfo::getInteriorColor($bj_id, $brand_id);
        $view['originals'] = HgBaojiaXzj::getXzjType($bj_id);
        return $view;
    }
}