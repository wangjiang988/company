<?php
/**
 * 保险功能
 */
defined ( 'InHG' ) or exit ( 'Access Invalid!' );
class baoxianControl extends BaseSellerControl {
    public function __construct() {
        require BASE_CORE_PATH . '/framework/function/baoxian.php';
    }

    public function indexOp() {
        //
    }

    /**
     * Ajax 获取保险价格
     */
    public function ajax_get_baoxianOp() {
        $type = array (
                        'chesun',
                        'daoqiang',
                        'sanzhe',
                        'boli',
                        'ziransunshi',
                        'bujimian',
                        'wuguomianze',
                        'renyuan',
                        'huahen'
        );
        $t = $_GET ['t'];
        if (! in_array ( $t, $type )) {
            echo json_encode ( array (
                            'code' => 0,
                            'msg' => '保险参数错误'
            ) );
            die ();
        }
        // 获取各类型保险价格
        $r = 0;
        switch ($t) {
            case 'chesun' :
                // 获取基础保费
                $base = intval ( $_GET ['b'] );
                $price = intval ( $_GET ['p'] );
                $r = get_chesun_price ( $base, $price );
                break;
            case 'daoqiang' :
                // 获取基础保费
                $base = intval ( $_GET ['b'] );
                $price = intval ( $_GET ['p'] );
                $r = get_daoqiang_price ( $base, $price );
                break;
        }
        echo json_encode ( array (
                        'code' => 1,
                        'msg' => 'OK',
                        'data' => $r
        ) );
    }
}
