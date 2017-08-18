<?php
/**
 * 保险价格计算器
 *
 * @author 技安 <php360@qq.com>
 * @link http://www.moqifei.com
 */

defined('InHG') or exit('Access Invalid!');

class baoxian {

    // 保险ID
    private $bxid;

    // 新车购置价
    private $price;

    // 座位数的类别,查询个人和企业同座位的保险
    private $type;
    private $seat;

    // 总价格
    private $countPrice;
    // 各单项价格数组
    private $pArr = array();

    // 国别
    // private $guobie;

    /**
     * 初始化数据
     * @param array $param 各基本数据
     * $param = array(
     *     'bxid' => 保险ID
     *     'price'=> 新车购置价
     *     'seat' => 座位数
     *     // 'guobie'=> 国别 // 1:进口  0:国产
     * )
     */
    public function __construct(array $param) {
        // 保险ID
        $this->bxid  = $param['bxid'];
        // 新车购置价
        $this->price = $param['price'];
        // 座位类别,分个人和公司
        // 1,2是个人;3,4是公司
        // 1,3是6座以下
        // 2,4是6~10座
        if ($param['seat'] < 6) {
            $this->type   = '1,3';
        } else {
            $this->type   = '2,4';
        }
        $this->seat = $param['seat'];
        // 车辆国别,进口和国产
        // $this->guobie = $param['guobie'];
    }

    /**
     * 获取保险的价格,这是唯一可以访问的方法
     * @return array 各个保险的价格和总价格
     */
    public function getBxPrice() {
        $r = array();
        $r['chesun']   = $this->getChesunPrice();
        $r['daoqiang'] = $this->getDaoqiangPrice();
        $r['sanzhe']   = $this->getSanzhePrice();
        $r['renyuan']  = $this->getRenyuanPrice();
        $r['boli']     = $this->getBoliPrice();
        $r['ziran']    = $this->getZiranPrice();
        $r['huahen']   = $this->getHuahenPrice();
        $r['bujimian'] = $this->getBujimianPrice(
            $r['chesun'],
            $r['daoqiang'],
            $r['sanzhe'],
            $r['renyuan'],
            $r['huahen']
        );
        // $r['p']        = $this->pArr; // 单项总价格
        // $r['p']['p']   = $this->countPrice; // 总价格
        $r['type']     = $this->type == '1,3' ? 1 : 0;
        return $r;
    }

    /**
     * 车损险价格计算公式
     * 基础保费+新车购置价×费率
     */
    private function getChesunPrice() {
        // 查询基础保费和费率
        $chesunData  = Model('hg_dealer_baoxian_chesun')
                     ->field('type,base,rate')
                     ->where(array(
                            'type'  => array('in', $this->type),
                            'baoxian_id' => $this->bxid
                            ))
                     ->select();
        // 计算保险价格(个人和公司)
        $r = array();
        // $p = 0;
        foreach ($chesunData as $k => $v) {
            $type  = intval($v['type']);
            $base  = intval($v['base']);
            $rate  = floatval($v['rate']);
            $price = round($base + $this->price * ($rate / 100), 2);
            $grgs  = $this->_getGrGs($type);
            $r[$grgs] = $price;
            // 总价格
            // $this->countPrice += $price;
            // 单项总价格
            // $p += $price;
        }
        // $this->pArr['chesun'] = $p;
        return $r;
    }

    /**
     * 盗抢险价格计算公式
     * 基础保费+新车购置价×费率
     */
    private function getDaoqiangPrice() {
        // 查询基础保费和费率
        $daoqiangData  = Model('hg_dealer_baoxian_daoqiang')
                       ->field('type,base,rate')
                       ->where(array(
                            'type'  => array('in', $this->type),
                            'baoxian_id' => $this->bxid
                            ))
                       ->select();
        // 计算保险价格(个人和公司)
        $r = array();
        // $p = 0;
        foreach ($daoqiangData as $k => $v) {
            $type  = intval($v['type']);
            $base  = intval($v['base']);
            $rate  = floatval($v['rate']);
            $price = round($base + $this->price * ($rate / 100), 2);
            $grgs  = $this->_getGrGs($type);
            $r[$grgs] = $price;
            // 总价格
            // $this->countPrice += $price;
            // 单项总价格
            // $p += $price;
        }
        // $this->pArr['daoqiang'] = $p;
        return $r;
    }

    /**
     * 第三者责任险
     * 固定值，按赔付额度查找确定
     */
    private function getSanzhePrice() {
        // 查询基础保费和费率
        $sanzheData  = Model('hg_dealer_baoxian_sanzhe')
                       ->field('type,base,price')
                       ->where(array(
                            'type'  => array('in', $this->type),
                            'baoxian_id' => $this->bxid
                            ))
                       ->select();
        // 计算保险价格(个人和公司)
        $r = array();
        // $p = 0;
        foreach ($sanzheData as $k => $v) {
            $type  = intval($v['type']);
            $base  = intval($v['base']);
            $price  = floatval($v['price']);
            $grgs  = $this->_getGrGs($type);
            $r[$base][$grgs] = $price;
            // 总价格
            // $this->countPrice += $price;
            // 单项总价格
            // $p += $price;
        }
        // $this->pArr['sanzhe'] = $p;
        return $r;
    }

    /**
     * 车上人员责任险
     * 每次事故责任限额×费率(×投保座位数)
     * 司机和乘客分别只计算一个座位
     */
    private function getRenyuanPrice() {
        // 查询基础保费和费率
        $renyuanData  = Model('hg_dealer_baoxian_renyuan')
                       ->field('type,title,base,rate')
                       ->where(array(
                            'type'  => array('in', $this->type),
                            'baoxian_id' => $this->bxid
                            ))
                       ->select();
        // 计算保险价格(个人和公司)
        $r = array();
        // $p = 0;
        foreach ($renyuanData as $k => $v) {
            $type  = intval($v['type']);
            $base  = intval($v['base']);
            $rate  = floatval($v['rate']);
            $price = $base * 10000 * ($rate / 100);
            $grgs  = $this->_getGrGs($type);
            switch ($v['title']) {
                case '司机':
                    $renyuan = 'sj';
                    break;
                case '乘客':
                    $renyuan = 'ck';
                    break;
            }
            $r[$renyuan][$base][$grgs] = $price;
            // 总价格
            // $this->countPrice += $price;
            // 单项总价格
            // $p += $price;
        }
        // $this->pArr['renyuan'] = $p;
        return $r;
    }

    /**
     * 玻璃单独破碎险
     * 新车购置价×费率
     */
    private function getBoliPrice() {
        // 查询基础保费和费率
        $boliData  = Model('hg_dealer_baoxian_boli')
                       ->field('type,title,rate')
                       ->where(array(
                            'type'  => array('in', $this->type),
                            'baoxian_id' => $this->bxid
                            ))
                       ->select();
        // 计算保险价格(个人和公司)
        $r = array();
        // $p = 0;
        foreach ($boliData as $k => $v) {
            $type  = intval($v['type']);
            $rate  = floatval($v['rate']);
            $price = $this->price * ($rate / 100);
            $grgs  = $this->_getGrGs($type);
            switch ($v['title']) {
                case '进口':
                    $guobie = 'jk';
                    break;
                case '国产':
                    $guobie = 'gc';
                    break;
            }
            $r[$guobie][$grgs] = $price;
            // 总价格
            // $this->countPrice += $price;
            // 单项总价格
            // $p += $price;
        }
        // $this->pArr['boli'] = $p;
        return $r;
    }

    /**
     * 自燃损失险
     * 新车购置价×费率
     */
    private function getZiranPrice() {
        // 查询基础保费和费率
        $ziranData  = Model('hg_dealer_baoxian_ziran')
                       ->field('type,rate')
                       ->where(array(
                            'type'  => array('in', $this->type),
                            'baoxian_id' => $this->bxid
                            ))
                       ->select();
        // 计算保险价格(个人和公司)
        $r = array();
        // $p = 0;
        foreach ($ziranData as $k => $v) {
            $type  = intval($v['type']);
            $rate  = floatval($v['rate']);
            $price = $this->price * ($rate / 100);
            $grgs  = $this->_getGrGs($type);
            $r[$grgs] = $price;
            // 总价格
            // $this->countPrice += $price;
            // 单项总价格
            // $p += $price;
        }
        // $this->pArr['ziran'] = $p;
        return $r;
    }

    /**
     * 车身划痕损失险
     * 固定值，按新车购置价和赔付额度查找确定
     */
    private function getHuahenPrice() {
        // 新车购置价查询条件生成
        if ($this->price < 300000) {
            $title = '30万以下';
        } else if ($this->price <= 500000) {
            $title = '30~50万';
        } else {
            $title = '50万以上';
        }
        // 查询基础保费和费率
        $huahenData  = Model('hg_dealer_baoxian_huahen')
                       ->field('type,peifu,price')
                       ->where(array(
                            'type'  => array('in', $this->type),
                            'baoxian_id' => $this->bxid,
                            'title' => $title
                            ))
                       ->select();
        // 计算保险价格(个人和公司)
        $r = array();
        // $p = 0;
        foreach ($huahenData as $k => $v) {
            $type  = intval($v['type']);
            $peifu = intval($v['peifu']);
            $price = floatval($v['price']);
            $grgs  = $this->_getGrGs($type);
            $r[$peifu][$grgs] = $price;
            // 总价格
            // $this->countPrice += $price;
            // 单项总价格
            // $p += $price;
        }
        // $this->pArr['huahen'] = $p;
        return $r;
    }

    /**
     * 不计免赔特约险
     * 适用本条款的险种标准保费×费率
     */
    private function getBujimianPrice($chesun, $daoqiang, $sanzhe, $renyuan, $huahen) {
        // 查询基础保费和费率
        $bujimianData  = Model('hg_dealer_baoxian_bujimian')
                       ->field('type,baoxian_type,rate')
                       ->where(array(
                            'type'  => array('in', $this->type),
                            'baoxian_id' => $this->bxid
                            ))
                       ->select();
        // 计算保险价格(个人和公司)
        $r = array();
        // $p = 0;
        foreach ($bujimianData as $key => $val) {
            $type  = intval($val['type']);
            $baoxian_type  = $val['baoxian_type'];
            $rate  = floatval($val['rate']);
            $grgs  = $this->_getGrGs($type);
            switch ($baoxian_type) {
                case 'chesun':
                    $price = round($chesun[$grgs] * ($rate / 100),2);
                    $r['chesun'][$grgs] = $price;
                    // 总价格
                    // $this->countPrice += $price;
                    // 单项总价格
                    // $p += $price;
                    break;
                case 'daoqiang':
                    $price = round($daoqiang[$grgs] * ($rate / 100),2);
                    $r['daoqiang'][$grgs] = $price;
                    // 总价格
                    // $this->countPrice += $price;
                    // 单项总价格
                    // $p += $price;
                    break;
                case 'sanzhe':
                    foreach ($sanzhe as $k => $v) {
                        $price = round($v[$grgs] * ($rate / 100),2);
                        $r['sanzhe'][$k][$grgs] = $price;
                        // 总价格
                        // $this->countPrice += $price;
                        // 单项总价格
                        // $p += $price;
                    }
                    break;
                case 'renyuan':
                    foreach ($renyuan as $k => $v) {
                        foreach ($v as $_k => $_v) {
                            $price = round($_v[$grgs] * ($rate / 100),2);
                            $r['renyuan'][$k][$_k][$grgs] = $price;
                            // 总价格
                            // $this->countPrice += $price;
                            // 单项总价格
                            // $p += $price;
                        }
                    }
                    break;
                case 'huahen':
                    foreach ($huahen as $k => $v) {
                        $price = round($v[$grgs] * ($rate / 100),2);
                        $r['huahen'][$k][$grgs] = $price;
                        // 总价格
                        // $this->countPrice += $price;
                        // 单项总价格
                        // $p += $price;
                    }
                    break;
            }
        }
        // $this->pArr['bujimian'] = $p;
        return $r;
    }

    /**
     * 返回保险类型是个人还是公司
     * @param  inter $type 个人或公司的标号
     * @return string       gr或者gs
     */
    private function _getGrGs($type) {
        switch (intval($type)) {
            case 1:
            case 2:
                $r = 'gr'; // 个人
                break;
            case 3:
            case 4:
                $r = 'gs'; // 公司
                break;
        }
        return $r;
    }

}