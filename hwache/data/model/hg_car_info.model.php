<?php
/**
 * 车型信息
 */

defined('InHG') or exit('Access Invalid!');

class hg_car_infoModel extends Model{

    public function __construct(){
        parent::__construct('hg_car_info');
    }

    public function getCarInfo($carId, $model='carmodel') {
        $r = array();
        $result = $this->field('name,value')
            ->where(array(
                'gc_id' => $carId,
                'model' => $model,))
            ->select();
        if (!empty($result)) {
            foreach ($result as $k => $v) {
                $r[$v['name']] = unserialize($v['value']);
            }
        }
        return $r;
    }

}
