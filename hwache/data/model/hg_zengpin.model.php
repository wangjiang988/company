<?php
/**
 * 订单模型
 */

defined('InHG') or exit('Access Invalid!');

class hg_zengpinModel extends Model{

    public function __construct(){
        parent::__construct('hg_zengpin');
    }

    /**
     * @param $condition
     * @param null $page
     * @param string $order
     * @param string $field
     * @param string $limit
     * @return mixed
     */
    public function getZengPinList($condition, $page = null, $field = '*', $order = '', $limit = '')
    {
        return $this->table('hg_zengpin')
            ->field($field)
            ->where($condition)
            ->order($order)
            ->limit($limit)
            ->page($page)
            ->select();
    }

}
