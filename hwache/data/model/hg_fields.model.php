<?php
/**
 * 自定义字段模型
 */

defined('InHG') or exit('Access Invalid!');

class hg_fieldsModel extends Model{

    public function __construct(){
        parent::__construct('hg_fields');
    }

    public function getCarFields($model = 'carmodel') {
        $r = array();
        $result = $this->field('name,setting')
            ->where(array('model' => $model,'setting'=>array('neq','')))
            ->select();
        if (!empty($result)) {
            foreach ($result as $k => $_v) {
                $r[$_v['name']] = unserialize($_v['setting']);
            }
        }
        return $r;
    }

}
