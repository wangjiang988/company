<?php

namespace App\Console\Commands;

use App\Models\HcDailiAccount;
use App\Models\HgBaojia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;

class MinuteBaojiaMap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'baojia:map';

    public $baojia_eloquent; //报价模型
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '对报价进行扫描，修改报价状态';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(HgBaojia $baojia)
    {
        $this->baojia_eloquent = $baojia;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo "scanning table hg_baojia ....\n";
        $start = msectime();
        //下架所有超时报价
//        $this->_take_off_out_time_baojia();
        //余额不足下架管理
        $this->_take_off_out_no_money_baojia();
        //上架所有余额充足的报价
        $this->_onsale_enough_money_baojia();
        $end= msectime();
        $spend_second = $end-$start;
        echo "execute time:{$spend_second}second....\n";
        echo "done....\n";
    }

    //下架所有超时的报价
    private function _take_off_out_time_baojia()
    {

        //TODO  后期加上日志功能
        //TODO  代理商资金池可提现余额
        

//        Log::info('artisan baojia:map 正在下架所有超时报价,需更新数据量:'.$count);
        $num = HgBaojia::where('bj_end_time','<',Carbon::now()->toDateTimeString())
            ->where('bj_status', 1)
            ->where('bj_step', 99)
            ->update(['bj_status'=>4, 'bj_reason'=>'过时失效']);
//        Log::info('artisan baojia:map 下架所有超时报价完成,已更新数据量:'.$num);
    }

    //下架所有余额不足售方报价
    private function _take_off_out_no_money_baojia()
    {
        //查询余额不足的售方账户
        $daili_account_list   =  HcDailiAccount::get_no_money_daili_account();
        $needs_id_array = [];
        if($daili_account_list->count() > 0)
        {
            foreach ($daili_account_list as $account)
            {
                $needs_id_array[] = $account->d_id;
            }
        }
        if($needs_id_array)
        {
            $ret = HcDailiAccount::suspend_all_baojia_by_account_id_array($needs_id_array);
//            Log::info('artisan baojia:map 根据余额情况上下架报价处理结果:'.$ret['msg']);
        }
    }
    //上架所有售方余额充足账户的报价
    private function _onsale_enough_money_baojia()
    {
        $daili_account_list = HcDailiAccount::get_enongh_money_but_suspend_baojia_account();
        Log::info('artisan baojia:map 需要上架的售方id:'.\GuzzleHttp\json_encode($daili_account_list));

        if(empty($daili_account_list)) return false;
        if(is_array($daili_account_list) && count($daili_account_list)>0)
        {
            //上架这几个售方的报价
             $this->_up_baojia($daili_account_list);
        }
    }

    //根据daili_account ID list进行上架操作
    private function _up_baojia($daili_account_list, $page=1)
    {
        $list = HgBaojia::with(['work_time'])
            ->whereIn('m_id', $daili_account_list)
            ->where('bj_step', 99)
            ->where('bj_status',2)
            ->where('bj_status_change_code','hwache_baojia_money_end')
            ->orderBy('bj_id')
            ->simplePaginate(200 , ['*'], 'page', $page);
        //处理报价
        if ($list->count()>0) {
            $ids_suspend = [];
            $ids_up      = [];
            $now         = Carbon::now()->toDateTimeString();
            foreach ($list as $baojia)
            {
                if(HgBaojia::check_rest_time($baojia))  //休息时间
                {
                    $ids_suspend[] = $baojia->bj_id;
                }else{
                    $ids_up[] = $baojia->bj_id;
                }
            }

            if(!empty($ids_suspend)){
                $update = [
                    'bj_status_change_code' => 'hwache_baojia_end',
                    'bj_status_change_time' => $now,
                    'bj_status'             => 2,
                    'bj_reason'             => '非销售时间'
                ];
                HgBaojia::whereIn('bj_id', $ids_suspend)->update($update);
            }
            if(!empty($ids_up))
            {
                $update = [
                    'bj_status_change_code' => 'hwache_baojia_start',
                    'bj_status_change_time' => $now,
                    'bj_status'             => 1,
                    'bj_reason'             => ''
                ];

                HgBaojia::whereIn('bj_id', $ids_up)->update($update);
            }
        }
        //递归
        if ($list->hasMorePages()) {
            $this->_up_baojia($daili_account_list, $list->currentPage() + 1);
        }else{
            HcDailiAccount::whereIn('d_id',$daili_account_list)->update(['down_to_zero_time' => '0000-00-00']);
        }
    }
}
