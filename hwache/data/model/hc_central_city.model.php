<?php
/**
 *
 * 中心城市模型
 */
defined('InHG') or exit('Access Invalid!');

class hc_central_cityModel extends Model
{
    public function __construct()
    {
        parent::__construct('hc_central_city');
    }

    public function getCitydata($condition = array(), $page = null)
    {

        return $this->table('hc_central_city,area')
            ->join('left')
            ->on('hc_central_city.area_city_id=area.area_id')
            ->where($condition)
            ->order('hc_central_city.id asc')
            ->field('hc_central_city.*,area_name')
            ->page($page)
            ->select();
    }

}