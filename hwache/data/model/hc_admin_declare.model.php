<?php
/**
 * 交易日志模型管理
 */
defined('InHG') or exit('Access Invalid!');
class hc_admin_declareModel extends Model {
    public function __construct(){
        parent::__construct('hc_admin_declare');
    }

    public function getList($where =[1=>1],$order ="year desc,month desc")
    {


        $list = $this->where($where)
                        ->page(10)->order($order)->select();
        return $list;
    }

    /**
     * 获取数据中的所有年月
     */

    public function getAllYears($where =[])
    {
        $list  =  $this->field('DISTINCT(year)')->where($where)->select();
        return $list;
    }

    public function getDetailById($id,$flow_type=1)
    {
        $declare =  $this->where(['id'=>$id]) ->find();
        if($declare && $declare['type'] ==10 && $declare['order_id'])
        {
            //不是order表，是全局资金流动表
            $log_model  = Model('hc_account_log');

            $log_list     = $log_model->getLogsByOrderid($declare['order_id'],$flow_type);

            if($log_list) $declare['order_logs'] = $log_list;
        }

        if($declare && in_array($declare['type'],[30,40]) && $declare['special_application_id'])
        {
            $application_model  = Model('hc_user_special_application');
            $application  =   $application_model->getById($declare['special_application_id']);
            if($application) $declare['application'] = $application;
        }

        return $declare;
    }

}

