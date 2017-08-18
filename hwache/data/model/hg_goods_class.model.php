<?php
/**
 * 第三级别车型扩展表模型
 */
defined('InHG') or exit('Access Invalid!');

class hg_goods_classModel extends Model{
    public function __construct(){
        parent::__construct('hg_goods_class');
    }

    /**
     * 类别详细
     *
     * @param   array   $condition  条件
     * $param   string  $field  字段
     * @return  array   返回一维数组
     */
    public function getGoodsClassInfo($condition, $field = '*') {
        $result = $this->field($field)->where($condition)->find();
        return $result;
    }


}