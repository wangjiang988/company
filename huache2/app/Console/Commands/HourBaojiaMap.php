<?php

namespace App\Console\Commands;

use App\Models\HgDelaerWorkday;
use Illuminate\Console\Command;
use Carbon\Carbon;

use App\Models\HgBaojia;
use Illuminate\Support\Facades\Log;
use DB;

class HourBaojiaMap extends Command
{
    const  RANGE  = 60;  //误差时间区间
    public $baojia_eloquent; //报价模型

    //华车时间
    public $now;
    public $now_timestamp;
    public $today;
    public $today_string;

    public $is_morning = true;

    public $hwache_am_start_time;
    public $hwache_am_start_timestamp;
    public $hwache_am_end_time;
    public $hwache_am_end_timestamp;

    public $hwache_pm_start_time;
    public $hwache_pm_start_timestamp;
    public $hwache_pm_end_time;
    public $hwache_pm_end_timestamp;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'baojia:hour_map';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每小时执处理一下报价表';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(HgBaojia $baojia)
    {
        $this->baojia_eloquent = $baojia;

        $this->now = Carbon::now()->toDateTimeString();
//        $this->now = Carbon::create(2017,7,19,17)->toDateTimeString();
        $this->now_timestamp = strtotime($this->now);

        $this->today_string     =   date("Y-m-d");
        $this->today = $today   =   Carbon::parse($this->today_string);
//        $this->today = $today  = Carbon::create(2017,7,20);

        //华车时间
        $this->hwache_am_start_time = Carbon::create($today->year, $today->month, $today->day, '9');
        $this->hwache_am_start_timestamp =  strtotime($this->hwache_am_start_time);
        $this->hwache_am_end_time        = Carbon::create($today->year, $today->month, $today->day, '12');
        $this->hwache_am_end_timestamp   =  strtotime($this->hwache_am_end_time);

        $this->hwache_pm_start_time      = Carbon::create($today->year, $today->month, $today->day, '13');
        $this->hwache_pm_start_timestamp = strtotime($this->hwache_pm_start_time);
        $this->hwache_pm_end_time        = Carbon::create($today->year, $today->month, $today->day, '17');
        $this->hwache_pm_end_timestamp   = strtotime($this->hwache_pm_end_time);

        if($this->now_timestamp>$this->hwache_am_end_timestamp)  $this->is_morning= false;

        parent::__construct();
    }

    /**\
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $start = msectime();
        echo "scanning table hg_baojia hourly....\n";
        //根据华车报价时间和代理商当日报价时间进行报价扫描
        $this->_set_status_by_work_time();
        $end= msectime();
        $spend_second = $end-$start;
        echo "done.....\n";
        echo "execute time:{$spend_second}second....\n";
        Log::info('artisan baojia:hour_map 定时上下架操作,执行时间:'.$spend_second.'秒');
    }

    //根据华车报价时间和代理商当日报价时间进行报价扫描
    //bj_status_change_code 字段说明
    //非销售时间 对应 hwache_baojia_end
    //余额不足   对应 hwache_baojia_money_end
    private function _set_status_by_work_time($page=1)
    {
        $baojia_model = $this->baojia_eloquent;

        $list = $baojia_model::with(['work_time'])
                            ->where('bj_step', 99)
                            ->where(function ($query) {
                                $query->where('bj_status', 1)
                                    ->orWhere(function ($query) {
                                        $query->where('bj_status', 2)
                                            ->where('bj_status_change_code', 'hwache_baojia_end');
                                    })
                                    ->orWhere(function ($query) {
                                        $query->where('bj_status', 6)
                                            ->where('bj_start_time', '<=', $this->now_timestamp); //30秒内的数据
                                    });
                            })
                            ->orderBy('bj_id')
                            ->simplePaginate(50, ['*'], 'page', $page);
        //处理报价
        if ($list->items()) {
            $this->_handle_baojia($list->items());
        }
        //递归
        if ($list->hasMorePages()) {
            $this->_set_status_by_work_time($list->currentPage() + 1);
        }


        //下架超时报价
        $this->_take_off_out_time_baojia();

    }


    private function _handle_baojia($list){
        if( count($list) < 1 ) return 0;

        $baojia_model  = $this->baojia_eloquent;

        $cache_ids_need_to_up = [];
        $cache_ids_need_to_down = [];

        foreach ($list as $baojia)
        {
            $work_time          =  $baojia->work_time;
            $is_rest_day   =  false;
            if($work_time){
                $is_rest_day  =  $this->_check_is_rest_day($work_time);
                $am_start_timestamp  = strtotime($this->today_string.' '.$work_time->am_start);
                $am_end_timestamp    = strtotime($this->today_string.' '.$work_time->am_end);
                $pm_start_timestamp  = strtotime($this->today_string.' '.$work_time->pm_start);
                $pm_end_timestamp    = strtotime($this->today_string.' '.$work_time->pm_end);
            }else{
                $am_start_timestamp  = $this->hwache_am_start_timestamp;
                $am_end_timestamp    = $this->hwache_am_end_timestamp;
                $pm_start_timestamp  = $this->hwache_pm_start_timestamp;
                $pm_end_timestamp    = $this->hwache_pm_end_timestamp;
            }

            //查看今天是否休息日
            //上架处理入口
            if($baojia->bj_status == 2  &&  !$is_rest_day && $baojia->bj_status_change_code=="hwache_baojia_end")
            {

                if($this->is_morning)
                {
                    $up_timestamp  =   $am_start_timestamp ;
                }else{
                    $up_timestamp  =   $pm_start_timestamp ;
                }

                if( abs($this->now_timestamp - $up_timestamp) < self::RANGE )
                {
                    $cache_ids_need_to_up[] =  $baojia->bj_id;
                }

            }

            //等待生效上架处理入口
            if($baojia->bj_status == 6  )
            {
                //工作时间上架
                if(!$is_rest_day)
                {
                    //在工作时间就上架
                    if(($this->now_timestamp > $am_start_timestamp && $this->now_timestamp < $am_end_timestamp )
                        || ($this->now_timestamp > $pm_start_timestamp && $this->now_timestamp < $pm_end_timestamp))
                    {
                        $cache_ids_need_to_up[] =  $baojia->bj_id;
                    }
                }
                //非工作时间下架
                else{
                    $cache_ids_need_to_down[] =  $baojia->bj_id;
                }

            }

            //下架处理入口
            if($baojia->bj_status == 1)
            {
                //如果是12,17点，则不论是否主动暂停，一律下架。
                if(in_array(Carbon::now()->hour, [12,17])){
                    $cache_ids_need_to_down[] =  $baojia->bj_id;
                }else{
                    //谁小用谁
                    if($this->is_morning)
                    {
                        $down_timestamp  =   $am_end_timestamp ;
                    }else{
                        $down_timestamp  =   $pm_end_timestamp ;
                    }

                    if( abs($this->now_timestamp - $down_timestamp) < self::RANGE )
                    {
                        $cache_ids_need_to_down[] =  $baojia->bj_id;
                    }
                }
            }
        }

        //批量上架
        if(count($cache_ids_need_to_up)>0){
            $update_up  = [
                'bj_status_change_code' =>  'hwache_baojia_start',
                'bj_status_change_time' =>  $this->now,
                'bj_status'    =>  1,
                'bj_reason'    =>  "baojia:hourly 时间:{$this->now} 报价上架",
            ];

            $baojia_model::whereIn('bj_id',$cache_ids_need_to_up)
                        ->update($update_up);
        }
        //批量下架
        if(count($cache_ids_need_to_down)>0)
        {
            $update_down  = [
                'bj_status_change_code' =>  'hwache_baojia_end',
                'bj_status_change_time' =>  $this->now,
                'bj_status'    =>  2,
                'bj_reason'    =>  "非销售时间",
            ];

            $baojia_model::whereIn('bj_id',$cache_ids_need_to_down)
                ->update($update_down);
        }

    }

    //检查是否是休息日
    public function _check_is_rest_day(HgDelaerWorkday $work_time)
    {
        //查看是否工作日
        $is_rest_day1 = $this->today->between(Carbon::parse($work_time['rest_1_start']),Carbon::parse($work_time['rest_1_end']));
        $is_rest_day2 = $this->today->between(Carbon::parse($work_time['rest_2_start']),Carbon::parse($work_time['rest_2_end']));

        if($is_rest_day1 || $is_rest_day2)  return true;

        $day_of_week        =   $this->today->dayOfWeek;
        $work_time_array    =   $work_time->toArray();
        if(!$work_time_array['day_'.$day_of_week])  return true;  //非工作日

        return false;
    }


     //下架所有超时的报价
    private function _take_off_out_time_baojia()
    {

        //TODO  后期加上日志功能
        //TODO  代理商资金池可提现余额
        

//        Log::info('artisan baojia:map 正在下架所有超时报价,需更新数据量:'.$count);
//        $num = HgBaojia::where('bj_end_time','<',$this->now_timestamp)
//            ->where('bj_status', 1)
//            ->where('bj_step', 99)
//            ->update(['bj_status'=>4, 'bj_reason'=>'过时失效']);
        $q = "UPDATE car_hg_baojia SET bj_status=4, bj_reason='过时失效',bj_final_reason=" .
             "(case bj_final_reason when '已被订购' then '已被订购' else '过时失效' end)" .
             " where bj_status in (1,2) and bj_step=99 and bj_end_time<".$this->now_timestamp;
        DB::update(DB::raw($q));
//        Log::info('artisan baojia:map 下架所有超时报价完成,已更新数据量:'.$num);
    }

}
