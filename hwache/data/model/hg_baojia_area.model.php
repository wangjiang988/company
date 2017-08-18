<?php
/**
 * 订单模型
 */

defined('InHG') or exit('Access Invalid!');

class hg_baojia_areaModel extends Model{

    public function __construct(){
        parent::__construct('hg_baojia_area');
    }

    public function getBaojiaArea($bj_id) {
        $r = array();
        $area_names='';
        // $area_name=
        $areas=$this->where(array('bj_id' =>$bj_id , ))->select();
        if($areas[0]['country']==1){

        	return '全国';
        }else{
        	foreach ($areas as $key => $value) {
                $p=Model('area')->where(array('area_id' => $value['province'], ))->field('area_name')->find();
        		$a=Model('area')->where(array('area_id' => $value['city'], ))->field('area_name')->find();
                $r[$p['area_name']][]=$a['area_name'];
        		
        	}
           foreach ($r as $key => $value) {
                $area_names.=' '.$key.'：'.implode(',',$value);
            } 
        }
        return ltrim($area_names,' ');
    }

}
