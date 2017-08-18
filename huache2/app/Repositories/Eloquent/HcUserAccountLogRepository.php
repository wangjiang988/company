<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\HcUserAccountLogRepositoryInterface;
use App\Models\HcUserAccountLog;
use Mockery\Expectation;

/**
 * The HcUserAccount repository.
 */
class HcUserAccountLogRepository extends Repository implements HcUserAccountLogRepositoryInterface {

    /**
     * EloquentChannelRepository constructor.
     *
     * @param Channel $model
     */
    public function __construct(HcUserAccountLog $model)
    {
        $this->model=$model;
    }

    /**
     * @param array $data
     * ['user_id','money','type','pay_type','item_id','item','remark','order_id']
     * @return mixed
     */
    public function addLog(array $data)
    {
        //$fields = ['user_id','money','type','pay_type','item_id','item','remark','order_id','status','money_type'];
        if(!isset($data['user_id'])){
            throw new \Exception('user_id:用户id不存在');
        }
        if(!isset($data['type'])){
            throw new \Exception('type:流水类型不存在');
        }
        if(!isset($data['pay_type'])){
            throw new \Exception('pay_type:支付方式不存在');
        }
        if(!isset($data['status'])){
            throw new \Exception('status:操作状态不存在');
        }
        if(!isset($data['money_type'])){
            throw new \Exception('money_type:资金状态不存在');
        }else if(!in_array($data['money_type'],['+','-'])){
            throw new \Exception('money_type:资金状态错误，必须为“+，-”');
        }
        //插入交易日志
        $logData = [
            'user_id'          => $data['user_id'],
            'item_id'          => isset($data['item_id']) ? $data['item_id'] : 0,
            'item'             => isset($data['item']) ? $data['item'] : 0,
            'remark'           => isset($data['remark']) ? $data['remark'] : '',
            'money'            => isset($data['money']) ? $data['money'] : 0,
            'credit_avaliable' => isset($data['credit_avaliable']) ? $data['credit_avaliable'] : 0,
            'type'             => $data['type'],
            'pay_type'         => $data['pay_type'],
            'order_id'         => isset($data['order_id']) ? $data['order_id'] : 0,
            'status'           => $data['status'],
            'wichdraw_end_at'  => isset($data['wichdraw_end_at']) ? $data['wichdraw_end_at'] : '',
            'money_type'       => $data['money_type'],
            'is_freeze'        => intval($data['is_freeze']),
            'freeze_remark'    => isset($data['freeze_remark']) ? $data['freeze_remark'] : ""
        ];
        return $this->model->create($logData);
    }
}