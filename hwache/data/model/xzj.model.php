<?php
/**
 * 选装件模型
 */
defined('InHG') or exit('Access Invalid!');
class xzjModel extends Model{

    /**
     * 获取代理已经添加的车型对应经销商的选装件
     * @param  [type]  $table     [description]
     * @param  array   $condition [description]
     * @param  string  $field     [description]
     * @param  integer $page      [description]
     * @param  string  $order     [description]
     * @return [type]             [description]
     */
    public function getXzjList($table, $condition = array(), $field = '*', $page = 0, $order = 'id desc') {
        parent::__construct($table);
        return $this->field($field)->where($condition)->page($page)->order($order)->select();
    }

    /**
     * 获取指定表,指定条件的数量
     * @param  [type] $table     [description]
     * @param  array  $condition [description]
     * @param  string $field     [description]
     * @return [type]            [description]
     */
    public function getCount($table, $condition = array()) {
        parent::__construct($table);
        return $this->where($condition)->count();
    }

    public function getXzjOne($table, $condition = array(), $field = '*', $order = 'id desc') {
        parent::__construct($table);
        return $this->field($field)->where($condition)->order($order)->find();
    }

    public function getXzjCarCount($car_brand) {
        parent::__construct('hg_xzj_car');
        return $this->where(array('car_brand'=>$car_brand))->count();
    }


}
