<?php

namespace App\Listeners;

use App\Events\CheckBaojiaEvent;
use App\Models\HgBaojia;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class CheckBaojiaListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CheckBaojiaEvent  $event
     * @return void
     * 使用      event(new CheckBaojiaEvent($baojia,'rollback'));
     */
    public function handle(CheckBaojiaEvent $event)
    {
        //检查报价被下单，是否需要下架处理
        $baojia = $event->baojia;
        switch ($event->operation)
        {
            case 'ordered':
                $left_num  =   $baojia->bj_num-1;
                if($left_num < 0)//数据量不够了 直接下架
                {
                    $this->_takeoff($baojia);
                }else{
                    $baojia->bj_num =  $left_num;
                    $baojia->bj_final_reason =  '已被订购';
                    if( $left_num <= 0 ) //下架处理
                    {
                        $this->_takeoff($baojia);
                    }else{
                        $baojia->save();
                    }
                }
                break;
            case 'rollback':
               //需要下架判断
               if($baojia->bj_status == 4)//已下架报价处理
               {
                   if($baojia->bj_end_time>time() && $baojia->bj_step='99')
                   {
                       //未超过报价时间 ，恢复报价
                       $this->_rollback($baojia);
                   }else{
                        //超过报价时间，+1，但不恢复报价
                       $baojia->bj_num += 1;
                       $baojia->save();
                   }
               }else{  //未下架报价处理
                       $baojia->bj_num += 1;
                       $baojia->save();
               }
               break;
            default:;
        }
    }

    /**
     * 下架处理
     * @param HgBaojia $baojia
     */
    private function _takeoff(HgBaojia $baojia)
    {
        Log::info('CheckBaojiaEvent  检查该报价('.$baojia->bj_serial.'): 数量('.$baojia->bj_num.')');
//        $baojia->bj_status = 4;
//        $baojia->save();
        //同时下架对应的相同车架号的车辆报价
        $baojia->down_baojia();
        Log::info('CheckBaojiaEvent 报价下架完毕');
    }

    /**
     * 恢复报价
     */
    private function _rollback(HgBaojia $baojia)
    {
        Log::info('CheckBaojiaEvent 恢复报价中...');
        $baojia->bj_num += 1;
        $baojia->bj_status = 1;
        $baojia->save();
        Log::info('CheckBaojiaEvent 报价恢复成功');
    }
}
