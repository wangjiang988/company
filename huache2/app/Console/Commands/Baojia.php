<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

use App\Models\HgBaojia;
use Illuminate\Support\Facades\Log;

class Baojia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'baojia:handle {--action=} {--dealer_id=} {--m_id=}';

    public $baojia_eloquent; //报价模型
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '手动上架报价。  参考命令 baojia:handle --action=up(down) --dealer_id=75 --m_id=105';

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
        $now          =  Carbon::now()->toDateTimeString();
        $action       =   $this->option('action');
        $dealer_id    = empty($this->option('dealer_id') ) ? 0 :  $this->option('dealer_id') ;
        $m_id         = empty($this->option('m_id') ) ? 0 :  $this->option('m_id') ;
        $baojia_model =   $this->baojia_eloquent;
        switch ($action)
        {
            case "up":
                $update = [
                    'bj_status_change_code' =>  'hwache_baojia_start',
                    'bj_status_change_time' =>  $now,
                    'bj_status'    =>  1,
                    'bj_reason'    =>  "baojia:handle action=up 时间:{$now} 报价手动上架",
                ];
                $query = $baojia_model::where('bj_step', 99)
                    ->where('bj_status', 2);
//                    ->where('bj_status_change_code','hwache_baojia_end');
                if($dealer_id)
                    $query->where('dealer_id',$dealer_id);
                if($m_id)
                    $query->where('m_id',$m_id);
                $query->update($update);
                break;
            case "down":
                $update = [
                    'bj_status_change_code' =>  'hwache_baojia_end',
                    'bj_status_change_time' =>  $now,
                    'bj_status'    =>  2,
                    'bj_reason'    =>  "管理员 手动下架",
                ];
                $query = $baojia_model::where('bj_step', 99)
                    ->where('bj_status', 1)
                    ->where('bj_status_change_code','hwache_baojia_start');
                if($dealer_id)
                    $query->where('dealer_id',$dealer_id);
                $query->update($update);
                break;
            default:;
        }
    }

}
