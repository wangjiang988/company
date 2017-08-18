<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Com\Hwache\Vouchers\DJQ;

class Vouchers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DJQ:send';//加信宝定时发布

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '定时发布代金券';

    protected $djq;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DJQ $djq)
    {
        parent::__construct();
        $this->djq = $djq;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo "scanning table hc_vouchers ....\n";
        //下架所有超时报价
        $this->addReleaseVouchers();
        echo "done....\n";
    }

    public function addReleaseVouchers()
    {
        //查询定时投放的列表
        $releaseList = $this->djq ->getReleaseTfList();
        if(!is_null($releaseList)){
            foreach($releaseList as $k =>$v){
                $thisHour = Carbon::now()->format('Y-m-d H');
                if(empty(floatval($v->fixe_hour_time))){
                    $isTime = ($v->fixed_start_time == Carbon::now()->toDateString());
                }else{
                    $sqlHour = Carbon::parse($v->fixed_start_time.$v->fixe_hour_time)->format('Y-m-d H');
                    $isTime = (strtotime($sqlHour) == strtotime($thisHour));
                }
                if($isTime){
                    echo "push time ".$v->fixed_start_time.$v->fixe_hour_time." ....\n";
                    $find = $this->djq ->getReleaseFind($v->id);
                    $this->djq ->startRelease($find);
                }
            }
        }
    }
}
