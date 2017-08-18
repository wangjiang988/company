<?php

namespace App\Console\Commands;

use App\Models\HgOrder;
use App\Models\HgOrderXzj;
use App\Models\HgOrderXzjEdit;
use App\Models\HgXzj;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class XzjCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xzj:timeout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Xzj Selection Timeout operation';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $orderXzjEdit;
    protected $xzj;
    protected $order;

    public function __construct(
        HgOrderXzjEdit $hgOrderXzjEdit,
        HgXzj $xzj,
        HgOrder $order
    ) {
        $this->orderXzjEdit = $hgOrderXzjEdit;
        $this->xzj = $xzj;
        $this->order = $order;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $orders = $this->order->getOrderJp();
        if (empty($orders))
            return false;
        $data = $this->getValue($orders);
        //批量更新四张表
        DB::transaction(function () use ($data, $orders) {
            //更新xzj_list_id 数量, order_xzj_edit 状态
            $this->setUpdateData($data);
            //order_xzj 数量减少
            $this->setDecreaseXzj($data);
            //订单表
            $this->order->whereIn('id', $orders)->update(['xzjp_steps' => 0]);
        });
        //短信队列,后续....
    }

    public function getValue($arr_order)
    {
        $data = [];
        $this->orderXzjEdit->whereIn('order_id', $arr_order)
            ->where('is_install', 0)
            ->chunk(10, function ($xzjEdits) use (&$data) {
                foreach ($xzjEdits as $xzjEdit) {
                    $data[] = [
                        'id'          => $xzjEdit->id,
                        'xzj_id'      => $xzjEdit->xzj_id,
                        'order_id'    => $xzjEdit->order_id,
                        'xzj_list_id' => $xzjEdit->xzj_list_id,
                        'num'         => $xzjEdit->old_num - $xzjEdit->edit_num,
                        'is_install'  => 1
                    ];
                }
            });
        return $data;
    }

    public function setUpdateData($data)
    {
            $result = [
                'id'          => array_pluck($data, 'id'),
                'xzj_id'      => array_pluck($data, 'xzj_id'),
                'order'       => array_pluck($data, 'order_id'),
                'xzj_list_id' => array_pluck($data, 'xzj_list_id'),
                'num'         => array_pluck($data, 'num'),
                'is_install'  => array_pluck($data, 'is_install')
            ];
        //更新xzj_list_id表数量
        $this->xzj->batchUpdate('id','xzj_max_num',$result['xzj_list_id'],$result['num']);
        //更新order_xzj_edit 状态
        $this->orderXzjEdit->batchUpdate('id', 'is_install', $result['id'],
            $result['is_install']);
    }

    //不得已而为之
    public function setDecreaseXzj($datas)
    {
        foreach ($datas as $data) {
            HgOrderXzj::Ancestry($data['order_id'], $data['xzj_id'], $data['num']);
        }
    }
}
