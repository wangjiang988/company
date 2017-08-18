<?php
/**
 * 地区模型
 */
defined('InHG') or exit('Access Invalid!');

class areaModel extends Model{

    public function __construct() {
        parent::__construct('area');
    }

    public function getAreaList($condition = array(),$fields = '*', $group = '') {
        if(is_array($condition))
            $condition['not_mainland'] = 0;
        return $this->where($condition)->field($fields)->limit(false)->group($group)->select();
    }

    public function getAreaTran($condition = array(),$fields = '*', $page = '') {
        if(is_array($condition))
            $condition['not_mainland'] = 0;
        return $this->where($condition)->field($fields)->page($page)->select();
    }

    public function getAreaFullNameById($area_id)
    {
        $area_name  = '';
        $area  =  $this->getDataByWhere(['area_id'=>$area_id],'area_id,area_name,area_parent_id');
        if($area['area_parent_id']){
            $area_name  = $this->getAreaFullNameById($area['area_parent_id']).$area_name;
        }
        return $area_name.$area['area_name'];
    }

}