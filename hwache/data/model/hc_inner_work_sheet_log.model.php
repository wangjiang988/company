<?php
/**
 * 圈子模型
 */
defined('InHG') or exit('Access Invalid!');

class hc_inner_work_sheet_logModel extends Model {
    public function __construct(){
        parent::__construct('hc_inner_work_sheet_log');
    }

     //生成一个新的处理log
     public function generate_new_log($inner_work_sheet_id, $info)
     {
        if(!is_numeric($inner_work_sheet_id)) return false;
        $dept  = Model('hc_admin_dept') -> where(['id'=>$info['dept_id']])->find();
        $dept_name  = isset($dept['name'])?$dept['name']:'';
        $data = [
            'inner_work_sheet_id' =>  $inner_work_sheet_id,
            'creator_dept'        =>  $dept_name,
            'creator_id'          =>  $info['id'],
            'creator'             =>  $dept_name.$info['name'],
        ];
        return $this->insert($data);
     }

}
