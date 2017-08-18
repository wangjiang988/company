<?php
/**
 * 保险计算价格公式函数库
 */

defined('InHG') or exit('Access Invalid!');


/**
 * 车损险价格计算公式
 * 基础保费+新车购置价×费率
 * @param  int      $base       基础保费
 * @param  int      $carprice   裸车价格
 * @param  float    $rate       费率
 * @return float                车损险价格
 */
function getChesunPrice($base, $carprice, $rate) {
    return $base + $carprice * ($rate / 100);
}

/**
 * 盗抢险价格计算公式
 * 基础保费+新车购置价×费率
 * @param  int      $base       基础保费
 * @param  int      $carprice   裸车价格
 * @param  float    $rate       费率
 * @return float                盗抢险价格
 */
function getDaoqiangPrice($base, $carprice, $rate) {
    return $base + $carprice * ($rate / 100);
}

/**
 * 第三者责任险
 * 固定值，按赔付额度查找确定
 * @param   integer $bxid   保险ID
 * @param   integer $type   车座位类别(1,2,3,4)
 * @return  integer         保险的价格
 */
function getSanzhePrice($bxid, $type) {
    $v  = Model('hg_dealer_baoxian_sanzhe')
        ->field('base,price')
        ->where('type='.$type.' AND baoxian_id='.$bxid)
        ->select();
    return $v;
}

/**
 * 玻璃单独破碎险
 * 新车购置价×费率
 * @param  integer $carprice  裸车价格
 * @param  string  $guobie    国别
 * @param  float   $rate      费率
 * @return integer            玻璃险价格
 */
function get_boliPrice($carprice, $guobie, $rate) {
    if($rate != 'rate2') {
        $rate = 'rate1';
    }
    return $carprice * $_ENV['bx']['boli'][$rate];
}

/**
 * 自燃损失险
 * @param   integer $carprice  裸车价格
 * @return  integer            自燃损失险价格
 */
function get_ziransunshiPrice($carprice) {
    return $carprice * $_ENV['bx']['ziransunshi']['rate'];
}

/**
 * 不计免赔特约险
 * @param   integer $carprice     裸车价格
 * @param   integer $chesunprice  车损险价格
 * @param   integer $sanzheprice  第三者责任险价格
 * @return  integer               不计免赔特约险价格
 */
function get_bujimianPrice($carprice, $chesunprice, $sanzheprice) {
    return ($chesunprice + $sanzheprice) * $_ENV['bx']['bujimian']['rate'];
}

/**
 * 无过免责任险
 * @param   integer $sanzheprice  第三者责任险价格
 * @return  integer               无过免责任险价格
 */
function get_wuguomianzePrice($sanzheprice) {
    return $sanzheprice * $_ENV['bx']['wuguomianze']['rate'];
}

/**
 * 车上人员责任险
 * @param  integer $seat 车上人员保险数量
 * @return integer         车上人员责任险价格
 */
function get_renyuanPrice($seat = 1) {
    return $_ENV['bx']['renyuan']['base'] * $seat;
}

/**
 * 车身划痕险
 * @param  integer $base  赔付额度
 * @return  integer         价格
 */
function get_huahenPrice($base = 20000) {
    if(!array_key_exists($base, $_ENV['bx']['huahen'])) {
        $base = 20000;
    }
    return $_ENV['bx']['huahen'][$base];
}

function test() {
    return 'echo';
}

