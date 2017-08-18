<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\HcDailiAccountLogRepositoryInterface;
use App\Models\HcDailiAccountLog;

/**
 * The HcUserAccount repository.
 */
class HcDailiAccountLogRepository extends Repository implements HcDailiAccountLogRepositoryInterface {

    /**
     * EloquentChannelRepository constructor.
     *
     * @param Channel $model
     */
    public function __construct(HcDailiAccountLog $model)
    {
        $this->model=$model;
    }


    /**
     * 添加交易日志
     * @param array $data
     * @return mixed
     */
    public function addLog(array $data)
    {
        if(!isset($data['d_id'])){
            throw new \Exception('d_id:代理商id不存在');
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
        $freeze_status = intval($data['freeze_status']);
        //d_id,money,item,remark,credit_avaiable,type,pay_type,order_id,freeze_status,freeze_time,status
        $logData = [
            'd_id'             => $data['d_id'],
            'money'            => isset($data['money']) ? $data['money'] : 0,
            'item_id'          => isset($data['item_id']) ? $data['item_id'] : 0,
            'item'             => isset($data['item']) ? $data['item'] : 0,
            'remark'           => isset($data['remark']) ? $data['remark'] : '',
            'credit_avaiable' => isset($data['credit_avaiable']) ? $data['credit_avaiable'] : 0,
            'type'             => $data['type'],
            'pay_type'         => $data['pay_type'],
            'order_id'         => isset($data['order_id']) ? $data['order_id'] : 0,
            'freeze_status'    => $freeze_status,
            'freeze_time'      => isset($data['freeze_time']) ? $data['freeze_time'] : '',
            'money_type'       => $data['money_type'],
            'status'           => $data['status']
        ];
        return $this->model->create($logData);
    }
}