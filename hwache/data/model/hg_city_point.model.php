<?php
/**
 * 报价模型管理
 */
defined('InHG') or exit('Access Invalid!');
class hg_city_pointModel extends Model {

    private $table = 'hg_city_point';

    public function __construct(){
        parent::__construct($this->table);
    }

    /**
     * 经销商列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function pointGetList($condition = array(), $field = '*', $page = 0, $order = 'id desc') {
       return $this->field($field)->where($condition)->page($page)->order($order)->select();
    }

    /**
     * 添加城市参考坐标
     *
     * @param   array $param 经销商信息
     * @return  array 数组格式的返回结果
     */
    public function pointAdd($param) {
        if(empty($param)) {
            return false;
        }
        $result = Db::insert($this->table, $param);
        if($result) {
            return Db::getLastId();
        } else {
            return false;
        }
    }

    public function pointGetCount($condition) {
        return $this->where($condition)->count();
    }

    /**
     * 获取城市参考点详细信息
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getPointInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }

}
