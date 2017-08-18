<?php

namespace App\Http\Controllers\Orders;


use App\Http\Controllers\Controller;
use App\Models\HgAgentFiles;
use App\Models\HgAnnex;
use App\Models\HgBaojia;
use App\Models\HgBaojiaXzj;
use App\Models\HgCarInfo;
use App\Models\HgEditInfo;
use App\Models\HgOrderXzj;
use App\Models\HgOrderXzjEdit;
use App\Services\NegotiateAtrait;
use Carbon\Carbon;

class SubscribeController extends Controller
{
    use NegotiateAtrait;
    //order.config 400
    public function getMemberPrepara($order)
    {
        $arr_jiaoche = $this->setJiaoche($order);
        $orderData = $order->orderDate;
        $car_times = $orderData->jiaoche_times;
        $arr_diff = $this->diffArray($arr_jiaoche,$car_times);
        $type_times = $this->getLatelyTiem($car_times, $orderData->jiaoche_at);
            foreach ($car_times as $index => $item) {
                $car_times[$index]['selected'] = !1;
            }
        $type_times = array_merge($arr_diff,$type_times);
        return view('cart.Member_appointemt_affirm', compact('order', 'car_times', 'type_times'));
    }

    //401
    public function getMemberFeedback($order)
    {
        return view('cart.Member_appointemt_feedback', compact('order'));
    }

    //402
    public function getMemberFormation($order)
    {
        return view('cart.Member_appointemt_formation',compact('order'));
    }

    //403 Member_appointemt_answer
    public function getMemberAnswer($order)
    {
        return view('cart.Member_appointemt_answer',compact('order'));
    }

    //404
    public function getMemberFinish($order)
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




    public function getSellPrepara($order)
    {
        $view = $this->getData($order->bj_id, $order->brand_id);
        $edit_data = HgEditInfo::getEditInfo($order->order_sn, 201);
        return view('dealer.orders.order_appointemt_show', $view, $edit_data)->with(compact('order'));
    }


    public function getSellFeedback($order)
    {
        $view = $this->getData($order->bj_id, $order->brand_id);
        $edit_data = HgEditInfo::getEditInfo($order->order_sn, 201);
        $car_times = $order->orderDate->jiaoche_times;
        return view('dealer.orders.order_appointemt_feedback',$view, $edit_data)->with(compact('order','car_times'));
    }


    public function getSellAnswer($order)
    {
        $view = $this->getData($order->bj_id, $order->brand_id);
        $edit_data = HgEditInfo::getEditInfo($order->order_sn, 201);
        $car_times = $order->orderDate->jiaoche_times;
        return view('dealer.orders.order_appointemt_answer',$view, $edit_data)->with(compact('order','car_times'));
    }

    public function getSellFormation($order)
    {
        $view = $this->getData($order->bj_id, $order->brand_id);
        $edit_data = HgEditInfo::getEditInfo($order->order_sn, 201);
        $car_times = $order->orderDate->jiaoche_times;
        $agent_files = HgAgentFiles::getAgentFileList(
            $order->orderAppoint->car_purpose,
            $order->daili_dealer_id,
            $order->orderAppoint->identity_type
        );
        return view('dealer.orders.order_appointemt_delivery', $view,
            $edit_data)->with(compact('order', 'car_times', 'agent_files'));
    }


    public function getSellFinish($order)
    {
        $view = $this->getData($order->bj_id, $order->brand_id);
        $edit_data = HgEditInfo::getEditInfo($order->order_sn, 201);
        $annx = HgAnnex::getAnnex($order->brand_id);
        $orderxzj = new HgOrderXzj;
        $xzj_lists = $orderxzj->getOrderXzjLists($order->id);
        return view('dealer.orders.order_appointemt_finish',$view, $edit_data)->with(compact('order','annx','xzj_lists'));
    }



    public function getData($bj_id, $brand_id)
    {
        $view['baojia'] = App(HgBaojia::class)->getBaojiaData($bj_id);
        $view['car_info'] = HgCarInfo::getInteriorColor($bj_id, $brand_id);
        $view['originals'] = HgBaojiaXzj::getXzjType($bj_id);
        return $view;
    }


    public function getLatelyTiem($times, $jiaoche_time)
    {
        //过滤选定的时间为上午或者下午
        $array = array_where($times, function ($key, $value) {
            return $key['select'] > 1;
        });
        //计算是否在节假日范围内的
        if (Carbon::createFromDate(null, 10, 1)
                ->between(
                    Carbon::parse($jiaoche_time),
                    Carbon::parse($jiaoche_time)->addDays(7)
                ) ||
            Carbon::createFromDate(null, 10, 1)
                ->between(
                    Carbon::parse($jiaoche_time),
                    Carbon::parse($jiaoche_time)->addDays(7)
                )
        ) {
            $jiaoche_end_time = Carbon::parse($jiaoche_time)->addDays(15);
        } else {
            $jiaoche_end_time = Carbon::parse($jiaoche_time)->addDays(7);
        }
        $jiaoche_time = Carbon::parse($jiaoche_time)->addDay();
        while (Carbon::parse($jiaoche_time) <= $jiaoche_end_time) {
            $day = Carbon::parse($jiaoche_time)->day;
            $month = Carbon::parse($jiaoche_time)->month;
            $week = Carbon::parse($jiaoche_time)->dayOfWeek;
            $year = Carbon::parse($jiaoche_time)->year;
            $data[] = [
                'month' => $month,
                'day' => $day,
                'week' => $week,
                'year' => $year
            ];
            $jiaoche_time = Carbon::parse($jiaoche_time)->addDay();
        }
        return $result = array_merge($array, $data);
    }

    //交车时间逻辑部分
    public function setJiaoche($order)
    {
        //特需文件时间
        $file_days = $order->orderAttr->new_file_days;
        $date = Carbon::parse($order->orderDate->jiaoche_at);
        $end_date = $date->addDays($file_days)->toDateTimeString();
        if ($order->orderBaojia->bj_is_xianche) {
            $start_date = $date->addDays(-14)->toDateTimeString();
        } else {
            $start_date = (Carbon::now()->addDay(15) > $end_date) ?
                $date->addDays(-15)->toDateTimeString() :
                $start_date = Carbon::now()->toDateTimeString();
        }
        while ($start_date <= $end_date) {
            $year = Carbon::parse($start_date)->year;
            $day = Carbon::parse($start_date)->day;
            $month = Carbon::parse($start_date)->month;
            $week = Carbon::parse($start_date)->dayOfWeek;
            $now_day = Carbon::now()->day;
            $now_month = Carbon::now()->month;
            $type = $month > $now_month || ($months = $now_month && $day > $now_day) ? false : true;
            $data[] = [
                'month'       => $month,
                'day'         => $day,
                'week'        => $week,
                'year'        => $year,
            ];

            $start_date = Carbon::parse($start_date)->addDays(1);
        }
        return $data;
    }

    /**
     * @param $array1
     * @param $array2
     * 数组比较合并
     * @return array
     */
    public function diffArray($array1, $array2)
    {
        foreach ($array1 as $key => $value) {
            $arr[$key] = implode(',', $value);
        }
        foreach ($array2 as $key => $value) {
            unset($value['select']);
            $arr1[$key] = implode(',', $value);
        }
        $arr_diff = array_diff($arr, $arr1);
        $arr_com = [];
        if (is_array($arr_diff)) {
            foreach ($arr_diff as $key => $value) {
                $arr_meg = explode(',', $value);
                $arr_com[$key]['month'] = $arr_meg[0];
                $arr_com[$key]['day'] = $arr_meg[1];
                $arr_com[$key]['week'] = $arr_meg[2];
                $arr_com[$key]['year'] = $arr_meg[3];
            }
        }
        return $arr_com;
    }


}