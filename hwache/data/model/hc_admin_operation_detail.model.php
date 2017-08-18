<?php
/**
 * 操作记录表Model
 * @author wangjiang
 */
defined('InHG') or exit('Access Invalid!');
class hc_admin_operation_detailModel extends Model
{
    public function __construct()
    {
        parent::__construct('hc_admin_operation_detail');
    }


//    /**
//     * 获取list
//     */
//    public function getListByWhere($where)
//    {
//
//        $ret = $this->where($where)->order('created_at desc')-> select();
//        return $ret;
//    }

    /**
     * 连接 hc_admin_operation 查询
     */

    public function getDetailListByWhere($where)
    {

        $ret = $this->where($where)->order('created_at desc')-> select();
        if($ret){
            foreach ($ret as $k => $item)
            {
                $op =Model('hc_admin_operation')->getById($item['op_id']);
                $item['operation'] = $op;
                $ret[$k]= $item;
            }
        }
        return $ret;
    }

}