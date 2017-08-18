<?php
/**
 * 车型列表控制器
 */

defined('InHG') or exit('Access Invalid!');

class listControl extends BaseSellerControl {

    public function __contruct() {
        praent::__contruct();
    }

    /**
     * 车型列表首页
     */
    public function indexOp() {
        // 加载当前城市参考点,通过上牌归属地查找
        $sheng = 10; // 江苏省
        $shi = 166; // 苏州市
        $model_city_point = Model('hg_city_point');
        $map = array(
            'sheng' => $sheng,
            'shi'   => $shi,
        );
        $pointInfo = $model_city_point->getPointInfo($map);

        // 加载云图类
        require_once(BASE_CORE_PATH.'/framework/libraries/yuntu.php');
        $yuntu = new yuntu();
        $r = $yuntu->get_around(array('center'=>$pointInfo['point'],'radius'=>50000));
        if ($r['status'] && $r['count']>0) {
            foreach ($r['datas'] as $k => $v) {
                echo $v['_name'].':距离'.$v['_distance'].'米<br />';
            }
        }
    }

}