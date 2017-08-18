<?php
/**
 * 订单模型
 */

defined('InHG') or exit('Access Invalid!');

class hg_baojia_fieldsModel extends Model{

    public function __construct(){
        parent::__construct('hg_baojia_fields');
    }

    public function getBaojiaFields($bjid) {
        $r = array();
        $result = $this->field('name,value')
            ->where(array('bj_id'=>$bjid))
            ->select();
        if (!empty($result)) {
            foreach ($result as $k => $v) {
                $r[$v['name']] = unserialize($v['value']);
            }
        }

        return $r;
    }

}
