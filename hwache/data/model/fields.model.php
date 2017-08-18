<?php
/**
 * 自定义字段模型
 */
defined('InHG') or exit('Access Invalid!');

class fieldsModel extends Model {

    private $table = 'hg_fields';

    public function __construct(){
        parent::__construct($this->table);
    }

    /**
     * 自定义字段列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getFieldsList($condition = array(), $field = '*', $page = 0, $order = 'id desc') {
       return $this->field($field)->where($condition)->page($page)->order($order)->select();
    }

    /**
     * 添加自定义字段
     * @param   array $param 字段信息
     * @return  array 数组格式的返回结果
     */
    public function addFields($param) {
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

    /**
     * 修改自定义字段
     * @param   array $param 字段信息
     * @return  array 数组格式的返回结果
     */
    public function updateFields($param, $id) {
        if(isset($param['desc'])){
            $param['`desc`'] = $param['desc'];
            unset($param['desc']);
        }
        if(empty($param)) {
            return false;
        }
        $result = Db::update($this->table, $param, 'id='.$id );
        if($result) {
            return $result;
        } else {
            return false;
        }
    }

}
