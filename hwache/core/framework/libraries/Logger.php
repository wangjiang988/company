<?php

/**
 * Created by PhpStorm.
 * User: wangjiang
 * Date: 2017/5/10
 * Time: 10:41
 */
class Logger
{

    public function __construct()
    {

    }

    /**
     * 记录操作日志
     * @param array $operation =['type'=>11,'operation'=>'ss'..] 对应car_hc_admin_operation
     * @param array $details 数组，对应多条car_hc_admin_operation_detail表记录
     */
    public static function record($operation,$details = [] )
    {
        $operation_model   =  Model('hc_admin_operation');
        if($operation)
        {
            if(!isset($operation['id']))
                $id = $operation_model->add_operation($operation);
            else
                $id = $operation['id'];
            if($details)
            {
                foreach ($details as $k =>$v)
                {
                    $details[$k]['op_id'] = $id;
                }
                $operation_model->add_details($details);
            }
            return $id;
        }else{
            return false;
        }
    }

}