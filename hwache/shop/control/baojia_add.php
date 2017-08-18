<?php
/**
 * 发布报价
 */
defined('InHG') or exit ('Access Invalid!');
class baojia_addControl extends BaseSellerControl {

    public function __construct() {
        parent::__construct();
        Language::read('member_store_goods_index');
    }

    public function indexOp() {
        $this->add_step_oneOp();
    }

    /**
     * 添加商品
     */
    public function add_step_oneOp() {
        // 实例化商品分类模型
        $model_goodsclass = Model('goods_class');
        // 商品分类
        $goods_class = $model_goodsclass->getGoodsClass($_SESSION['store_id']);

        // 常用商品分类
        $model_staple = Model('goods_class_staple');
        $param_array = array();
        $param_array['member_id'] = $_SESSION['member_id'];
        $staple_array = $model_staple->getStapleList($param_array);

        Tpl::output('staple_array', $staple_array);
        Tpl::output('goods_class', $goods_class);
        Tpl::showpage('baojia_add.step1');
    }

    /**
     * 添加商品
     */
    public function add_step_twoOp() {
        // 报价第二步,填写报价基本信息
        /**
         * ++++++++++++++++++++++++++++++++++++++++++++++++++
         * 车型分类
         */
        $gc_id = intval($_GET['class_id']);

        // 验证商品分类是否存在且商品分类是否为最后一级
        $data = H('goods_class') ? H('goods_class') : H('goods_class', true);
        if (!isset($data[$gc_id]) || isset($data[$gc_id]['child']) || isset($data[$gc_id]['childchild'])) {
            showDialog(L('store_goods_index_again_choose_category1'));
        }

        // 实例化商品分类模型
        $model_goodsclass = Model('goods_class');
        // 更新常用分类信息
        $goods_class = $model_goodsclass->getGoodsClassLineForTag($gc_id);
        Tpl::output('goods_class', $goods_class);
        Model('goods_class_staple')->autoIncrementStaple($goods_class, $_SESSION['member_id']);

        /**
         * ++++++++++++++++++++++++++++++++++++++++++++++++++
         * 车型自定义字段信息读取和保存的内容读取
         */
        $m = Model('fields');
        $map = array(
            'model' => 'carmodel',
            'is_add'=> 1
        );
        // 查询自定义字段信息
        $carmodel_fields = $m->where($map)
                         ->field('*')
                         ->order('sort ASC')
                         ->select();
        // 查询自定义字段保存的数据信息
        $map =  array(
            'model' => 'carmodel',
            'gc_id' => $gc_id
        );
        $fields_data = Model('hg_car_info')->where($map)
                       ->field('name,value')
                       ->select();
        if (!empty($fields_data)) {
            $data = array();
            foreach ($fields_data as $k => $v) {
                $data[$v['name']] = $v['value'];
            }
            $fields_data = $data;
            unset($data);
        }
        // 用于判断系统固有字段配合js做前端数据处理
        Tpl::output('fields_data', $fields_data);
        /**
         * ---------------------------------------
         * 注意查询出来的数据后期需要添加缓存
         * @author 技安 <php360@qq.com>
         * ---------------------------------------
         */
        if (!empty($carmodel_fields)) {
            foreach ($carmodel_fields as $k => $v) {
                $carmodel_fields[$k]['value'] = unserialize($fields_data[$v['name']]);
            }
        }
        // Tpl模板输出
        Tpl::output('carmodel_fields', $carmodel_fields);

        /**
         * ++++++++++++++++++++++++++++++++++++++++++++++++++
         * 读取配置文件
         */
        $c = require_once(BASE_DATA_PATH.'/config/base.ini.php');
        // 交车周期
        Tpl::output('jcPeriod', $c['jcPeriod']);
        // 付款方式
        Tpl::output('payType', $c['payType']);
        // 模板输出
        Tpl::showpage('baojia_add.step2');
    }

    /**
     * 保存第二步发布的报价基本信息
     */
    public function save_baojiaOp() {
        if (chksubmit()) {
            // 保存数据
            $data = array();
            $data['m_id']           = $_SESSION['member_id'];
            // 报价唯一编号,当前时间
            $t = explode(' ', microtime());
            $data['bj_serial']      = $t['1'] . ltrim($t['0'], '0.');

            $data['brand_id']       = trim($_POST['cate_id']);
            $data['gc_name']        = trim($_POST['cate_name']); // 车型分类
            $data['bj_num']         = empty($_POST['num']) ? intval($_POST['num']) : 1;
            $data['bj_producetime'] = $_POST['chuchang_time'] ? $_POST['chuchang_time'] : 0;
            $data['bj_jc_period']   = $_POST['jc_period'] ? $_POST['jc_period'] : 0;
            $data['bj_licheng']     = $_POST['licheng'];
            $data['bj_butie']       = $_POST['butie_price'];
            $data['bj_start_time']  = strtotime($_POST['start_time']);
            $data['bj_end_time']    = strtotime($_POST['end_time']);
            $data['bj_pay_type']    = $_POST['pay_type'];
            $data['bj_zf_butie']    = $_POST['zf_butie'];
            $data['bj_cj_butie']    = $_POST['cj_butie'];
            $data['bj_step']        = 1;
            $data['bj_is_pass']     = 0;
            $data['bj_status']      = 1;
            $data['pub_time']      = time();

            // 插入报价基本表
            $r = Model('hg_baojia')->insert($data);

            if($r) {

                // 消费者上牌违约赔偿金
                $licensePlateBreakContract = intval($_POST['plate_break_contract']);

                // 根据经销商代理手动输入的价格计算车价，保证金等
                require(BASE_CORE_PATH.'/framework/libraries/price.php');
                $price = new Price(
                    $_POST['lckp_price'],
                    $_POST['agent_service_price'],
                    C('hwacheServicePrice'),
                    $_POST['doposit_price'],
                    $licensePlateBreakContract
                );

                // 获取系统默认资金数额——用于得到当前需要支付的诚意金
                $hg_money = Model('hg_money')->getListSetting();

                // 价格表数据
                $dataPrice = array(
                    'bj_id'                 => $r, // 报价ID
                    'bj_lckp_price'         => $_POST['lckp_price'], // 裸车开票价格
                    'bj_doposit_price'      => $_POST['doposit_price'], // 消费者保证金
                    'bj_agent_service_price'=> $_POST['agent_service_price'], // 经销商代理服务费(净收入)
                    'bj_earnest_price'      => $hg_money['chengyijin'], // 诚意金
                    'bj_car_guarantee'      => $price->getCarGuarantee(), // 订车担保金(自动计算)
                    'bj_license_plate_break_contract'   => $licensePlateBreakContract, // 消费者上牌违约赔偿金
                    'bj_price'              => $price->getCarPrice(), // 车价格包含服务费
                );
                $rP = Model('hg_baojia_price')->insert($dataPrice);

                // 插入字段列表
                $carmodel = $_POST['carmodel'];
                $dataArr = array();
                foreach ($carmodel as $k => $v) {
                    $dataArr[]  = array(
                        'bj_id' => $r,
                        'name'  => $k,
                        'value' => serialize($v)
                    );
                }
                Model('hg_baojia_fields')->insertAll($dataArr);

                // 记录日志
                $this->recordSellerLog('发布报价,ID为:'.$r);
                redirect(urlShop('baojia_add', 'add_step_three', array('bjid' => $r)));
            } else {
                showMessage('添加失败,请重新添加', getReferer(), 'html', 'error');
            }
        }
    }

    /**
     * 第三步  现在修改成选择经销商
     * +++++++++++++++++++++++++++++++
     * 原来是:添加颜色图片
     */
    public function add_step_threeOp() {
        if (chksubmit()) {
            $bjid = intval($_POST['bjid']);

            try {
                // 开启事务
                Model()->beginTransaction();

                // 获取经销商ID和name
                $d = array(
                    'dealer_id' => intval($_POST['d_id']),
                    'dealer_name' => trim($_POST['d_name']),
                    'bj_step' => 2, // 报价发布状态
                );
                if (!empty($_POST['quanguo'])) {
                    $d['bj_nationwide'] = 1;
                }
                // 更新报价基本表经销商信息
                $r = Model('hg_baojia')->where(array('bj_id'=>$bjid))->update($d);
                if(!$r) {
                    throw new Exception("更新经销商信息失败", 1);
                }

                /**
                 * ------------------------------------------------------------
                 * 可售区域
                 */
                // 是否全国
                if (!empty($_POST['quanguo'])) {
                    $d = array(
                        'bj_id'     => $bjid,
                        'country'   => 1
                    );
                    $r = Model('hg_baojia_area')->insert($d);
                } else {
                    // 省和市
                    $d = array();
                    foreach ($_POST['area'] as $key => $value) {
                        foreach ($value['s'] as $k => $v) {
                                $d[] = array(
                                    'bj_id'    => $bjid,
                                    'province' => $key,
                                    'city'     => $v
                                );
                            }
                    }
                    $r = Model('hg_baojia_area')->insertAll($d);
                }
                if(!$r) {
                    throw new Exception("添加可售区域失败", 1);
                }

                /**
                 * ------------------------------------------------------------
                 * 商业保险
                 */
                if (!empty($_POST['baoxian'])) {
                    $d = array('bj_baoxian'=>1,'bj_baoxian_discount'=>$_POST['baoxian_discount'],'bj_bx_select'=>intval($_POST['baoxianselect']));
                    $r = Model('hg_baojia')->where(array('bj_id'=>$bjid))->update($d);
                    if(!$r) {
                        throw new Exception("更新商业保险信息失败", 1);
                    }
                }
                // 牌照指标代办
                if (!empty($_POST['zhibiao'])) {
                    $d = array('bj_zhibiao'=>1);
                    $r = Model('hg_baojia')->where(array('bj_id'=>$bjid))->update($d);
                    if(!$r) {
                        throw new Exception("更新牌照指标代办信息失败", 1);
                    }
                }

                // 保险类型选择
                $bx_select = intval($_POST['baoxianselect']);
                // 选择保险
                if (!empty($bx_select)) {
                    // 车座位类别
                    $type = intval($_POST['type']);
                    $seat_type = array(
                        0      => array(
                            'gr'    => 2,
                            'gs'    => 4
                        ),
                        1      => array(
                            'gr'    => 1,
                            'gs'    => 3
                        )
                    );
                    // 车损险
                    $d = array();
                    foreach ($_POST['chesun'] as $k => $v) {
                        $d[] = array(
                            'bj_id'     => $bjid,
                            'type'      => $seat_type[$type][$k],
                            'price'     => $v,
                            'discount_price'=>$v*$_POST['baoxian_discount']/100,
                            'bjm_price' => $_POST['bujimian']['chesun'][$k],
                            'bjm_discount_price' => $_POST['bujimian']['chesun'][$k]*$_POST['baoxian_discount']/100
                        );
                    }
                    $r = Model('hg_baojia_baoxian_chesun_price')->insertAll($d);
                    if(!$r) {
                        throw new Exception("添加车损险价格失败", 1);
                    }
                    // 盗抢险
                    $d = array();
                    foreach ($_POST['daoqiang'] as $k => $v) {
                        $d[] = array(
                            'bj_id'     => $bjid,
                            'type'      => $seat_type[$type][$k],
                            'price'     => $v,
                            'discount_price'=>$v*$_POST['baoxian_discount']/100,
                            'bjm_price' => $_POST['bujimian']['daoqiang'][$k],
                            'bjm_discount_price' => $_POST['bujimian']['daoqiang'][$k]*$_POST['baoxian_discount']/100
                        );
                    }
                    $r = Model('hg_baojia_baoxian_daoqiang_price')->insertAll($d);
                    if(!$r) {
                        throw new Exception("添加盗抢险价格失败", 1);
                    }
                    // 三者险
                    $d = array();
                    foreach ($_POST['sanzhe'] as $key => $value) {
                        foreach ($value as $k => $v) {
                            $d[] = array(
                                'bj_id'     => $bjid,
                                'type'      => $seat_type[$type][$k],
                                'compensate'=> $key,
                                'price'     => $v,
                                'discount_price'=>$v*$_POST['baoxian_discount']/100,
                                'bjm_price' => $_POST['bujimian']['sanzhe'][$key][$k],
                                'bjm_discount_price' => $_POST['bujimian']['sanzhe'][$k]*$_POST['baoxian_discount']/100
                            );
                        }
                    }
                    $r = Model('hg_baojia_baoxian_sanzhe_price')->insertAll($d);
                    if(!$r) {
                        throw new Exception("添加三者险价格失败", 1);
                    }
                    // 车上人员责任险
                    $d = array();
                    foreach ($_POST['renyuan'] as $key => $value) {
                        foreach ($value as $k => $v) {
                            foreach ($v as $_k => $_v) {
                                $d[] = array(
                                    'bj_id'     => $bjid,
                                    'type'      => $seat_type[$type][$_k],
                                    'staff'     => $key,
                                    'compensate'=> $k,
                                    'price'     => $_v,
                                    'discount_price'=>$_v*$_POST['baoxian_discount']/100,
                                    'bjm_price' => $_POST['bujimian']['renyuan'][$key][$k][$_k],
                                    'bjm_discount_price' => $_POST['bujimian']['renyuan'][$key][$k][$_k]*$_POST['baoxian_discount']/100,
                                );
                            }
                        }
                    }
                    $r = Model('hg_baojia_baoxian_renyuan_price')->insertAll($d);
                    if(!$r) {
                        throw new Exception("添加车上人员责任险价格失败", 1);
                    }
                    // 玻璃单独破碎险
                    $d = array();
                    foreach ($_POST['boli'] as $key => $value) {
                        foreach ($value as $k => $v) {
                            $d[] = array(
                                'bj_id'     => $bjid,
                                'type'      => $seat_type[$type][$k],
                                'state'     => $key,
                                'price'     => $v,
                                'discount_price'=>$v*$_POST['baoxian_discount']/100,
                            );
                        }
                    }
                    $r = Model('hg_baojia_baoxian_boli_price')->insertAll($d);
                    if(!$r) {
                        throw new Exception("添加玻璃单独破碎险价格失败", 1);
                    }
                    // 自燃损失险
                    $d = array();
                    foreach ($_POST['ziran'] as $k => $v) {
                        $d[] = array(
                            'bj_id'     => $bjid,
                            'type'      => $seat_type[$type][$k],
                            'price'     => $v,
                            'discount_price'=>$v*$_POST['baoxian_discount']/100,
                        );
                    }
                    $r = Model('hg_baojia_baoxian_ziran_price')->insertAll($d);
                    if(!$r) {
                        throw new Exception("添加自燃损失险价格失败", 1);
                    }
                    // 车身划痕损失险
                    $d = array();
                    foreach ($_POST['huahen'] as $key => $value) {
                        foreach ($value as $k => $v) {
                            $d[] = array(
                                'bj_id'     => $bjid,
                                'type'      => $seat_type[$type][$k],
                                'compensate'=> $key,
                                'price'     => $v,
                                'discount_price'=>$v*$_POST['baoxian_discount']/100,
                                'bjm_price' => $_POST['bujimian']['huahen'][$key][$k],
                                'bjm_discount_price' => $_POST['bujimian']['huahen'][$key][$k]*$_POST['baoxian_discount']/100,
                            );
                        }
                    }
                    $r = Model('hg_baojia_baoxian_huahen_price')->insertAll($d);
                    if(!$r) {
                        throw new Exception("添加车身划痕损失险价格失败", 1);
                    }
                }

                // 办理保险提供证件
                $cert = array(
                    'gr'    => array(
                        'grbx_sfz'      => '个人身份证',
                        'grbx_jsz'      => '个人驾驶证',
                        'grbx_dbr'      => '个人代办人身份证',
                        'grbx_sqs'      => '个人授权书',
                        'grbx_qsgx'     => '个人亲属关系证明',
                    ),
                    'gs'    => array(
                        'gsbx_yyzj'     => '公司营业执照',
                        'gsbx_zzjg'     => '公司组织机构代码证',
                        'gsbx_swdj'     => '公司税务登记证',
                        'gsbx_frsfz'    => '公司法人身份证',
                        'gsbx_wts'      => '公司委托书',
                    ),
                );
                $data = array(
                    'gr' => array(),
                    'gs' => array(),
                );
                foreach ($cert as $key => $value) {
                    foreach ($value as $k => $v) {
                        $str = $v.':';
                        if (!empty($_POST[$k]['yj'])) {
                            $str .= '(原件),';
                        }
                        if (!empty($_POST[$k]['fyj'])) {
                            $str .= '(复印件),';
                        }
                        if (!empty($_POST[$k]['yj']) ||
                            !empty($_POST[$k]['fyj'])) {
                            $str .= intval($_POST[$k]['num']).'张';
                            $data[$key][] = $str;
                        }
                        $data['s'][$key][$k]['yj'] = $_POST[$k]['yj'];
                        $data['s'][$key][$k]['fyj'] = $_POST[$k]['fyj'];
                        $data['s'][$key][$k]['num'] = $_POST[$k]['num'];
                    }
                }
                $data['gs'][] = '公章';
                $d = array(
                    'bj_id'             => $bjid,
                    'model'             => 'baoxian',
                    'serialize_data'    => serialize($data),
                );
                $r = Model('hg_baojia_more')->insert($d);
                if(!$r) {
                    throw new Exception("添加保险证书失败", 1);
                }


                /**
                 * ------------------------------------------------------------
                 * 上牌
                 */
                if (!empty($_POST['shangpai'])) {
                    $d = array('bj_shangpai'=>1);
                    $r = Model('hg_baojia')->where(array('bj_id'=>$bjid))->update($d);
                    if(!$r) {
                        throw new Exception("更新上牌信息失败", 1);
                    }
                }
                // 上牌价格
                $sp_price = intval($_POST['shangpai_price']);
                $d = array(
                    'bj_shangpai_price' => $sp_price,
                );
                $r = Model('hg_baojia_price')->where(array('bj_id'=>$bjid))->update($d);
                if(!$r) {
                    throw new Exception("添加上牌价格失败", 1);
                }
                // 牌照指标代办价格
                // $zhibiao_price = intval($_POST['zhibiao_price']);
                // $d = array(
                //     'bj_zhibiao_price' => $zhibiao_price,
                // );
                // $r = Model('hg_baojia_price')->where(array('bj_id'=>$bjid))->update($d);
                // if(!$r) {
                //     throw new Exception("添加牌照指标代办价格失败", 1);
                // }
                // 办理上牌提供证件
                $cert = array(
                    'gr'    => array(
                        'grsp_sfz'      => '个人身份证',
                        'grsp_jsz'      => '个人驾驶证',
                        'grsp_dbr'      => '个人代办人身份证',
                        'grsp_sqs'      => '个人授权书',
                        'grsp_qsgx'     => '个人亲属关系证明',
                    ),
                    'gs'    => array(
                        'gssp_yyzj'     => '公司营业执照',
                        'gssp_zzjg'     => '公司组织机构代码证',
                        'gssp_swdj'     => '公司税务登记证',
                        'gssp_frsfz'    => '公司法人身份证',
                        'gssp_wts'      => '公司委托书',
                    ),
                );
                $data = array(
                    'gr' => array(),
                    'gs' => array(),
                );
                // TODO 需要重新调整为保存数组而不是字符串
                // 现在是保存字符串，不方便修改功能
                foreach ($cert as $key => $value) {
                    foreach ($value as $k => $v) {
                        $str = $v.':';
                        if (!empty($_POST[$k]['yj'])) {
                            $str .= '(原件),';
                        }
                        if (!empty($_POST[$k]['fyj'])) {
                            $str .= '(复印件),';
                        }
                        if (!empty($_POST[$k]['yj']) ||
                            !empty($_POST[$k]['fyj'])) {
                            $str .= intval($_POST[$k]['num']).'张';
                            $data[$key][] = $str;
                        }
                        $data['s'][$key][$k]['yj'] = $_POST[$k]['yj'];
                        $data['s'][$key][$k]['fyj'] = $_POST[$k]['fyj'];
                        $data['s'][$key][$k]['num'] = $_POST[$k]['num'];
                    }
                }
                $data['gs'][] = '公章';
                $d = array(
                    'bj_id'             => $bjid,
                    'model'             => 'shangpai',
                    'serialize_data'    => serialize($data),
                );
                $r = Model('hg_baojia_more')->insert($d);
                if(!$r) {
                    throw new Exception("添加上牌证书失败", 1);
                }

                /**
                 * ------------------------------------------------------------
                 * 上临牌
                 */
                if (!empty($_POST['linpai'])) {
                    $d = array('bj_linpai'=>1);
                    $r = Model('hg_baojia')->where(array('bj_id'=>$bjid))->update($d);
                    if(!$r) {
                        throw new Exception("更新上临牌信息失败", 1);
                    }
                    // 上临牌价格
                    $sp_price = intval($_POST['linpai_price']);
                    $d = array(
                        'bj_linpai_price' => $sp_price,
                    );
                    $r = Model('hg_baojia_price')->where(array('bj_id'=>$bjid))->update($d);
                    if(!$r) {
                        throw new Exception("添加上临牌价格失败", 1);
                    }
                    // 办理上牌提供证件
                    $cert = array(
                        'gr'    => array(
                            'grsp_sfz'      => '个人身份证',
                            'grsp_jsz'      => '个人驾驶证',
                            'grsp_dbr'      => '个人代办人身份证',
                            'grsp_sqs'      => '个人授权书',
                            'grsp_qsgx'     => '个人亲属关系证明',
                        ),
                        'gs'    => array(
                            'gssp_yyzj'     => '公司营业执照',
                            'gssp_zzjg'     => '公司组织机构代码证',
                            'gssp_swdj'     => '公司税务登记证',
                            'gssp_frsfz'    => '公司法人身份证',
                            'gssp_wts'      => '公司委托书',
                        ),
                    );
                    $data = array(
                        'gr' => array(),
                        'gs' => array(),
                    );
                    foreach ($cert as $key => $value) {
                        foreach ($value as $k => $v) {
                            $str = $v.':';
                            if (!empty($_POST[$k]['yj'])) {
                                $str .= '(原件),';
                            }
                            if (!empty($_POST[$k]['fyj'])) {
                                $str .= '(复印件),';
                            }
                            if (!empty($_POST[$k]['yj']) ||
                                !empty($_POST[$k]['fyj'])) {
                                $str .= intval($_POST[$k]['num']).'张';
                                $data[$key][] = $str;
                            }
                            $data['s'][$key][$k]['yj'] = $_POST[$k]['yj'];
                            $data['s'][$key][$k]['fyj'] = $_POST[$k]['fyj'];
                            $data['s'][$key][$k]['num'] = $_POST[$k]['num'];
                        }
                    }
                    $data['gs'][] = '公章';
                    $d = array(
                        'bj_id'             => $bjid,
                        'model'             => 'linpai',
                        'serialize_data'    => serialize($data),
                    );
                    $r = Model('hg_baojia_more')->insert($d);
                    if(!$r) {
                        throw new Exception("添加上临牌证书失败", 1);
                    }
                }

                /**
                 * ------------------------------------------------------------
                 * 牌照费用
                 */
                if (!empty($_POST['paizhao_price'])) {
                    $paizhao_price = intval($_POST['paizhao_price']);
                    $d = array(
                        'bj_paizhao_price' => $paizhao_price,
                    );
                    $r = Model('hg_baojia_price')->where(array('bj_id'=>$bjid))->update($d);
                    if(!$r) {
                        throw new Exception("添加牌照费用失败", 1);
                    }
                }
                // 其他费用
                if (!empty($_POST['other_price'])) {
                    
                    $d = array(
                        'bj_id'             => $bjid,
                        'model'             => 'other_price',
                        'serialize_data'    => serialize($_POST['other_price']),
                    );
                    $other_price = 0;
                    foreach($_POST['other_price'] as $k=>$v){
                    	$other_price += round($v,2);
                    }
                    if($other_price>0){
                    	$dd =  array(
                        		'bj_other_price' => $other_price,
                    			);
                    	$rd = Model('hg_baojia_price')->where(array('bj_id'=>$bjid))->update($dd);
                    }
                    
                    $r = Model('hg_baojia_more')->insert($d);
                    if(!$r) {
                        throw new Exception("添加其他费用失败", 1);
                    }
                }
                /**
                 * ------------------------------------------------------------
                 * 信用卡和借记卡
                 */
                switch ($_POST['xinyongka']) {
                    case '1':
                        $xyk='免费不限次数';
                        break;
                    case '2':
                        $xyk='免费'.$_POST['xykm2'].'次。超出次数的收费：刷卡金额的'.$_POST['xykr2'].' %,每次'.$_POST['xykq2'].'元(封顶)';
                        break;
                    case '3':
                        $xyk='免费'.$_POST['xykm3'].'次。超出次数的收费：刷卡金额的'.$_POST['xykr3'];
                        break;
                    case '4':
                        $xyk='免费'.$_POST['xykm4'].'次。超出次数的收费：每次'.$_POST['xykq4'].'元(封顶)';
                        break;  
                }
                switch ($_POST['jiejika']) {
                    case '1':
                        $jjk='免费不限次数';
                        break;
                    case '2':
                        $jjk='免费'.$_POST['jjkm2'].'次。超出次数的收费：刷卡金额的'.$_POST['jjkr2'].' %,每次'.$_POST['jjkq2'].'元(封顶)';
                        break;
                    case '3':
                        $jjk='免费'.$_POST['jjkm3'].'次。超出次数的收费：刷卡金额的'.$_POST['jjkr3'];
                        break;
                    case '4':
                        $jjk='免费'.$_POST['jjkm4'].'次。超出次数的收费：每次'.$_POST['jjkq4'].'元(封顶)';
                        break;  
                }
                $data = array(
                    'xyk' => $xyk,
                    
                    'jjk' => $jjk,
                    
                );
                $d = array(
                    'bj_id'             => $bjid,
                    'model'             => 'ka',
                    'serialize_data'    => serialize($data),
                );
                $r = Model('hg_baojia_more')->insert($d);
                if(!$r) {
                    throw new Exception("添加刷卡信息失败", 1);
                }
                // 交车时交付文件资料
                if (!empty($_POST['wenjian'])) {
                        $d = array(
                            'bj_id'             => $bjid,
                            'model'             => 'wenjian',
                            'serialize_data'    => serialize($_POST['wenjian']),
                        );
                    $r = Model('hg_baojia_more')->insert($d);
                    if(!$r) {
                        throw new Exception("添加交付文件失败", 1);
                    }
                }
                // 交车时交付随车工具
                if (!empty($_POST['gongju'])) {
                        $d = array(
                            'bj_id'             => $bjid,
                            'model'             => 'gongju',
                            'serialize_data'    => serialize($_POST['gongju']),
                        );
                    $r = Model('hg_baojia_more')->insert($d);
                    if(!$r) {
                        throw new Exception("添加交付随车工具失败", 1);
                    }
                }
                /**
                 * ------------------------------------------------------------
                 * 国家补贴方式
                 */
                if (!empty($_POST['butieradio'])) {
                    // 载入基本配置文件
                    $c = require_once(BASE_DATA_PATH.'/config/base.ini.php');
                    $butie = intval($_POST['butieradio']);
                    $data = $c['butieType'][$butie];
                    if ($butie == 2) {
                        $data['num'] = intval($_POST['butieday']);
                    } else if ($butie == 3) {
                        $data['num'] = intval($_POST['butiemonth']);
                    }
                    $d = array(
                        'bj_id'             => $bjid,
                        'model'             => 'butie',
                        'serialize_data'    => serialize($data),
                    );
                    $r = Model('hg_baojia_more')->insert($d);
                    if(!$r) {
                        throw new Exception("添加补贴方式失败", 1);
                    }
                }
                // 赠品或服务
                if (!empty($_POST['zengpin'])) {
                    foreach ($_POST['zengpin'] as $key => $value) {
                        $d = array(
                        'bj_id'             => $bjid,
                        'zengpin_id'             => $key,
                        'is_install'=>$value['is_install'],
                        'num'=>$value['num'],
                        );
                        $r = Model('hg_baojia_zengpin')->insert($d);
                        if(!$r) {
                            throw new Exception("添加赠品失败", 1);
                        }
                    }
                }
               
                //提交事务
                Model()->commit();

                showMessage('添加经销商信息成功', urlShop('baojia_add', 'add_step_four', array('bjid'=>$bjid)));

            } catch (Exception $e) {
                //回滚事务
                Model()->rollback();
                showMessage($e->getMessage());

            }

        } else {
            $bjid = intval($_GET['bjid']);

            // Get Baojia Status
            $step = Model('hg_baojia')
                ->field('bj_step,brand_id')
                ->where(array('bj_id'=>$bjid,'m_id'=>$_SESSION['member_id']))
                ->find();
            if (empty($step) ||
                intval($step['bj_step']) != 1) {
                showMessage('This Baojia Is Added');
                exit;
            }

            // 报价ID
            Tpl::output('bjid', $bjid);

            /**
             * 读入已经添加的经销商
             */
            $model = Model();
            // 查询条件
            $where = 'hg_daili_dealer.dl_id='.$_SESSION['member_id'];
            $on = 'hg_dealer.d_id=hg_daili_dealer.d_id';
            $field = 'hg_dealer.d_id,hg_dealer.d_name';
            $model->table('hg_daili_dealer,hg_dealer')->field($field);
            $dealer_list  = $model->join('inner')->on($on)->where($where)->select();
            Tpl::output('dealer_list', $dealer_list);

            /**
             * 可售区域地区查询
             */
            $area = F('area2');
            if (empty($area)) {
                $m = Model('area');
                // 排除海外地区
                $area = $m->field('area_id,area_name')->where('area_parent_id=0 AND area_id <> 35')->select();
                if (!empty($area)) {
                    foreach ($area as $k => $v) {
                        $area[$k]['child'] = $m->field('area_id,area_name')->where('area_parent_id='.$v['area_id'])->select();
                    }
                }
                F('area2', $area);
            }
            Tpl::output('area', $area);

            /**
             * 保险设定
             * 查询代理设置的保险公司的费率
             */
            $bx_setting = Model('hg_dealer_baoxian')
                        ->field('id,title')
                        ->where('m_id='.$_SESSION['member_id'].' AND is_enable=1 and is_set=1')
                        ->select();
            Tpl::output('bx_setting', $bx_setting);

            /**
             * 是否可享受国家车型补贴
             * 根据报价第二步完善车辆基本信息来设定
             */
            $carInfo = Model('hg_baojia_fields')
                     ->where('bj_id='.$bjid.' AND name="butie"')
                     ->field('value')
                     ->find();
            $butie = intval(unserialize($carInfo['value']));

            Tpl::output('butie', $butie);
            // 此车型的赠品或服务
            $zengpin=Model('hg_zengpin')->where('brand_id='.$step['brand_id'])->select();
            Tpl::output('zengpin', $zengpin);
            // 其他费用
            $other=Model('hg_fields')->where(array('model' =>'other_price' , ))->select();
            Tpl::output('other', $other);
            // 刷卡费率
            $shuaka=Model('hg_shuaka')->where(array('member_id' =>$_SESSION['member_id'] , ))->find();
            Tpl::output('shuaka', $shuaka);
            // 随车资料
            $suiche=Model('hg_annex')->where(array('member_id' =>$_SESSION['member_id'] ,'c_id'=>$step['brand_id'] ))->select();
            Tpl::output('suiche', $suiche);
            /**
             * 加载模板
             */
            Tpl::showpage('baojia_add.step3');
        }
    }

    /**
     * ajax 选择经销商的信息
     */
    public function ajaxgetdealerOp() {
        $id = isset($_GET['id'])
            ? intval($_GET['id'])
            : 0;
        if(empty($id)) {
            echo json_encode(array('code'=>0,'msg'=>'经销商参数错误！'));
            exit;
        }
        // 获取指定id经销商的数据信息
        $condition = array(
            'd_id' => $id,
        );
        $model_dealer = Model('hg_dealer');
        $dealer_info = $model_dealer->getDealerInfo($condition);
        // var_dump($dealer_info);
        $data = array(
            'baoxian'   => intval($dealer_info['d_baoxian']),
            'shangpai'  => intval($dealer_info['d_shangpai']),
            'linpai'    => intval($dealer_info['d_linpai']),
            'jcfee'     => intval($dealer_info['d_jcfee']),
            'jcfree'    => intval($dealer_info['d_jcfree']),
            'sheng'     => intval($dealer_info['d_sheng']),
            'shi'       => intval($dealer_info['d_shi'])
        );
        $data   = array(
            'code'  => 1,
            'msg'   => 'OK',
            'data'  => $data,
        );
        echo json_encode($data);

    }

    /**
     * Ajax获取车型的保险价格
     */
    public function ajaxgetbxOp() {
        // 报价ID
        $bjid = intval($_GET['bjid']);

        // 保险ID
        $bxid = intval($_GET['bxid']);

        // 根据报价ID查询车型ID
        $carInfo = Model('hg_baojia')
                 ->field('brand_id')
                 ->where('bj_id='.$bjid)
                 ->find();
        // 车型ID
        $gcid = $carInfo['brand_id'];
        unset($carInfo);
        // 查询车型的国别和座位数
        $carInfo = Model('hg_car_info')
                 ->field('name,value')
                 ->where(array(
                        'gc_id' => $gcid,
                        'model' => 'carmodel',
                        'name'  => 'seat_num'
                    ))
                 ->find();
        $seat = intval(unserialize($carInfo['value']));
        // foreach ($carInfo as $k => $v) {
        //     if ($v['name'] == 'seat_num') {
        //         $seat = intval(unserialize($v['value']));
        //         break;
        //     }
        //     if ($v['name'] == 'guobie') {
        //         $guobie = intval(unserialize($v['value']));
        //     }
        // }
        unset($carInfo);
        // if (!empty($seat) && !empty($guobie)) {
        // }
        // 获取该报价的裸车开票价
        $lckp_price = Model('hg_baojia_price')
                    ->field('bj_lckp_price')
                    ->where('bj_id='.$bjid)
                    ->find();
        $price = $lckp_price['bj_lckp_price'];

        require_once(BASE_CORE_PATH.'/framework/libraries/baoxian.php');
        $param = array(
            'bxid'  => $bxid,
            'price' => $price,
            'seat'  => $seat
            // 'guobie'=> $guobie
        );
        $bx = new baoxian($param);
        $bxprice = $bx->getBxPrice();
        echo json_encode($bxprice);
    }

    /**
     * 保存商品颜色图片
     */
    public function save_imageOp(){
        if (chksubmit()) {
            $common_id = intval($_POST['commonid']);
            if ($common_id <= 0 || empty($_POST['img'])) {
                showMessage(L('wrong_argument'));
            }
            $model_goods = Model('goods');
            // 保存
            $insert_array = array();
            foreach ($_POST['img'] as $key => $value) {
                foreach ($value as $k => $v) {
                    // 商品默认主图
                    $update_array = array();        // 更新商品主图
                    $update_where = array();
                    if ($k == 0 || $v['default'] == 1) {
                        $update_array['goods_image']    = $v['name'];
                        $update_where['goods_commonid'] = $common_id;
                        $update_where['color_id']       = $key;
                        // 更新商品主图
                        $model_goods->editGoods($update_array, $update_where);
                    }
                    if ($v['name'] == '') {
                        continue;
                    }
                    $tmp_insert = array();
                    $tmp_insert['goods_commonid']   = $common_id;
                    $tmp_insert['store_id']         = $_SESSION['store_id'];
                    $tmp_insert['color_id']         = $key;
                    $tmp_insert['goods_image']      = $v['name'];
                    $tmp_insert['goods_image_sort'] = ($v['default'] == 1) ? 0 : intval($v['sort']);
                    $tmp_insert['is_default']       = $v['default'];
                    $insert_array[] = $tmp_insert;
                }
            }
            $rs = $model_goods->addGoodsAll($insert_array, 'goods_images');
            if ($rs) {
                redirect(urlShop('store_goods_add', 'add_step_four', array('commonid' => $common_id)));
            } else {
                showMessage(L('nc_common_save_fail'));
            }
        }
    }

    /**
     * 商品发布第四步
     */
    public function add_step_fourOp() {
        if (chksubmit()) {
            $bjid=$_POST['bjid'];

            // 选装件折扣率
            $bj_xzj_zhekou=$_POST['bj_xzj_zhekou']?$_POST['bj_xzj_zhekou']:100;

          	//  原厂选装件数据插入
            if(count($_POST['xzj'])>=1){
            	
	            foreach ($_POST['xzj'] as $k=> $v) {
					if($v =="on" ){
		                $data = array(
		                    'bj_id'=>$bjid,
		                    'xzj_id'=>$k,
		                    'm_id'=>$_POST['m_id'][$k],
		                    'num'=>$_POST['num'][$k],
		                    'guide_price'=>$_POST['guide_price'][$k],
		                    'is_install'=>1,
		                );
	
	                	$id=Model('hg_baojia_xzj')->insert($data);
					}
	            }
            }
            // 非原厂数据插入
            /*if(count($_POST['fycxzj'])>=1){
                
                foreach ($_POST['fycxzj'] as $k=> $v) {
                    if($v =="on" ){
                        $data = array(
                            'bj_id'=>$bjid,
                            'xzj_id'=>$k,
                            'm_id'=>$_POST['m_id'][$k],
                            'fee'=>$_POST['fee'][$k],
                            'num'=>$_POST['num'][$k],
                            'guide_price'=>$_POST['guide_price'][$k],
                            'is_install'=>1,
                        );
    
                        $id=Model('hg_baojia_xzj')->insert($data);
                        // 插入赠品数据库
                        $data2 = array(
                            'bj_id'=>$bjid,
                            'num'=>$_POST['num'][$k],
                            'zp_title'=>$_POST['xzj_title'][$k],
                            'is_install'=>1,
                        );
                        $id=Model()->table('hg_baojia_zengpin')->insert($data2);
                    }
                }
            }*/

            /*//  原厂选装件非前置数据更新（安装费 折后总价）
            if(count($_POST['xzj']['yc_not_front'])>=1){
            	foreach ($_POST['xzj']['yc_not_front'] as $k=> $v) {
            
            		if($v =="on" ){            			
            			Model()->execute(sprintf('UPDATE `car_hg_xzj_daili` SET xzj_fee=%.2f,xzj_price=%.2f,xzj_has_num=%d WHERE id=%d',
            							$_POST['fee'][$k],
            							$_POST['price'][$k],
            							$_POST['num'][$k],
            							$k            							
            						));
            		}
            	}
            }*/
            //  非原厂选装件数据更新（安装费 折后总价）
            /*if(count($_POST['xzj']['fyc_not_front'])>=1){
            	foreach ($_POST['xzj']['fyc_not_front'] as $k=> $v) {
            		if($v =="on" ){            		     
            			Model()->execute(sprintf('UPDATE `car_hg_xzj_daili` SET xzj_fee=%.2f,xzj_price=%.2f,xzj_has_num=%d WHERE id=%d',
					            		$_POST['fee'][$k],
					            		$_POST['price'][$k],
					            		$_POST['num'][$k],
					            		$k          		 
					            		));
            		}
            	}
            }*/
            Model()->execute('UPDATE `car_hg_baojia` SET bj_step=99,bj_xzj_zhekou='.$bj_xzj_zhekou.' WHERE bj_id='.$bjid);
            
            showMessage('选装件选择完成','index.php?act=store_goods_online&op=index');
        } else {
        	
            // 报价ID
            $bjid = intval($_GET['bjid']);
            Tpl::output('bjid', $bjid);

            // Get Baojia Status
            $step = Model('hg_baojia')
                ->field('bj_step,gc_name')
                ->where(array('bj_id'=>$bjid,'m_id'=>$_SESSION['member_id']))
                ->find();
            if (empty($step) ||
                intval($step['bj_step']) != 2) {
                showMessage('This Baojia Is Added');
                exit;
            }
            // 车型名称
            Tpl::output('car_brand', $step['gc_name']);

            /**
             * 读取报价经销商和车型数据
             */
            $bj = Model('hg_baojia')
                ->field('dealer_name,brand_id,m_id,dealer_id')
                ->where(array('bj_id'=>$bjid))
                ->find();
            if (!empty($bj)) {
                Tpl::output('dealer_name', $bj['dealer_name']);
                // Tpl::output('m_id', $bj['m_id']);
                Tpl::output('dealer_id', $bj['dealer_id']);
            } else {
                showMessage('该订单无经销商');
                exit;
            }
            // 查询该车型是否有选装件
            $m = Model('xzj');
            $r = $m->getXzjCarCount($bj['brand_id']);
            if (empty($r)) {
                Model()->execute('UPDATE `car_hg_baojia` SET bj_step=99 WHERE bj_id='.$bjid);
            
            showMessage('报价完成','index.php?act=store_goods_online&op=index');
                // redirect(
                //     urlShop(
                //         'baojia_add',
                //         'add_step_five',
                //         array('bjid' => $bjid)
                //     )
                // );
                exit;
            }
            
            // 查询对应车型的选装件
            $map = array(
                'car_brand' => $bj['brand_id'],
                'dealer_id' =>$bj['dealer_id'] ,
                'member_id'=>$_SESSION['member_id'],
            );
            // $r=Model('hg_baojia')->getXzjList('hg_xzj_daili_main.car_brand='.$bj['brand_id'].' and hg_xzj_daili_main.member_id='.$_SESSION['member_id'].' and hg_xzj_daili_main.dealer_id='.$bj['dealer_id']);
            $r=Model()->table('hg_xzj_daili')->where($map)->select();
            //$r = $m->getXzjList('hg_xzj_list', $map);
            // 整理数据,把必须前装的分离出来
            /*$data = array(
                'front' => array(),
                'ycNotFront' => array(),
            	'fycXzjs' => array(),
                'other' => array(),
            );
            foreach ($r as $key => $value) {
            	if ($value['xzj_front'] == 1 && $value['xzj_yc']==1) {
            		$data['front'][] = $r[$key];
            	}elseif($value['xzj_front'] == 0 && $value['xzj_yc']==1){
            		$data['ycNotFront'][] = $r[$key];
            	}elseif($value['xzj_yc']==0){
            		$data['fycXzjs'][] = $r[$key];
            	}else{
            		$data['other'][] = $r[$key];
            	}           	
            }*/
            // 分离原厂和非原厂
            $data = array(
                'ycxzj' => array(),
                
                'fycXzjs' => array(),
                
            );
            foreach ($r as $key => $value) {
                if ($value['xzj_yc']==1) {
                    $data['ycxzj'][] = $r[$key];
                }else{
                    $data['fycXzjs'][] = $r[$key];
                }               
            }
			//print_r($data);
            Tpl::output('xzj_list', $data);

            Tpl::showpage('baojia_add.step4');
        }
    }

    /**
     * 上传图片
     */
    public function image_uploadOp() {
        // 判断图片数量是否超限
        $model_album = Model('album');
        $album_limit = $this->store_grade['sg_album_limit'];
        $album_count = $model_album->getCount(array('store_id' => $_SESSION['store_id']));
        if ($album_count >= $album_limit) {
            $error = L('store_goods_album_climit');
            if (strtoupper(CHARSET) == 'GBK') {
                $error = Language::getUTF8($error);
            }
            exit(json_encode(array('error' => $error)));
        }
        $class_info = $model_album->getOne(array('store_id' => $_SESSION['store_id'], 'is_default' => 1), 'album_class');
        // 上传图片
        $upload = new UploadFile();
        $upload->set('default_dir', ATTACH_GOODS . DS . $_SESSION ['store_id'] . DS . $upload->getSysSetPath());
        $upload->set('max_size', C('image_max_filesize'));
        $upload->set('thumb_width', GOODS_IMAGES_WIDTH);
        $upload->set('thumb_height', GOODS_IMAGES_HEIGHT);
        $upload->set('thumb_ext', GOODS_IMAGES_EXT);
        $upload->set('fprefix', $_SESSION['store_id']);
        $upload->set('allow_type', array('gif', 'jpg', 'jpeg', 'png'));
        $result = $upload->upfile($_POST['name']);
        if (!$result) {
            if (strtoupper(CHARSET) == 'GBK') {
                $upload->error = Language::getUTF8($upload->error);
            }
            $output = array();
            $output['error'] = $upload->error;
            $output = json_encode($output);
            exit($output);
        }

        $img_path = $upload->getSysSetPath() . $upload->file_name;

        // 取得图像大小
        list($width, $height, $type, $attr) = getimagesize(UPLOAD_SITE_URL . '/' . ATTACH_GOODS . '/' . $_SESSION['store_id'] . DS . $img_path);

        // 存入相册
        $image = explode('.', $_FILES[$_POST['name']]["name"]);
        $insert_array = array();
        $insert_array['apic_name'] = $image['0'];
        $insert_array['apic_tag'] = '';
        $insert_array['aclass_id'] = $class_info['aclass_id'];
        $insert_array['apic_cover'] = $img_path;
        $insert_array['apic_size'] = intval($_FILES[$_POST['name']]['size']);
        $insert_array['apic_spec'] = $width . 'x' . $height;
        $insert_array['upload_time'] = TIMESTAMP;
        $insert_array['store_id'] = $_SESSION['store_id'];
        $model_album->addPic($insert_array);

        $data = array ();
        $data ['thumb_name'] = cthumb($upload->getSysSetPath() . $upload->thumb_image, 240, $_SESSION['store_id']);
        $data ['name']      = $img_path;

        // 整理为json格式
        $output = json_encode($data);
        echo $output;
        exit();
    }

    /**
     * ajax获取商品分类的子级数据
     */
    public function ajax_goods_classOp() {
        $gc_id = intval($_GET['gc_id']);
        $deep = intval($_GET['deep']);
        if ($gc_id <= 0 || $deep <= 0 || $deep >= 4) {
            exit();
        }
        $model_goodsclass = Model('goods_class');
        $list = $model_goodsclass->getGoodsClass($_SESSION['store_id'], $gc_id, $deep);
        if (empty($list)) {
            exit();
        }
        /**
         * 转码
         */
        if (strtoupper ( CHARSET ) == 'GBK') {
            $list = Language::getUTF8 ( $list );
        }
        echo json_encode($list);
    }
    /**
     * ajax删除常用分类
     */
    public function ajax_stapledelOp() {
        Language::read ( 'member_store_goods_index' );
        $staple_id = intval($_GET ['staple_id']);
        if ($staple_id < 1) {
            echo json_encode ( array (
                'done' => false,
                'msg' => Language::get ( 'wrong_argument' )
            ) );
            die ();
        }
        /**
         * 实例化模型
         */
        $model_staple = Model('goods_class_staple');

        $result = $model_staple->delStaple(array('staple_id' => $staple_id, 'member_id' => $_SESSION['member_id']));
        if ($result) {
            echo json_encode ( array (
                'done' => true
            ) );
            die ();
        } else {
            echo json_encode ( array (
                'done' => false,
                'msg' => ''
            ) );
            die ();
        }
    }
    /**
     * ajax选择常用商品分类
     */
    public function ajax_show_commOp() {
        $staple_id = intval($_GET['stapleid']);

        /**
         * 查询相应的商品分类id
         */
        $model_staple = Model('goods_class_staple');
        $staple_info = $model_staple->getStapleInfo(array('staple_id' => intval($staple_id), 'gc_id_1,gc_id_2,gc_id_3'));
        if (empty ( $staple_info ) || ! is_array ( $staple_info )) {
            echo json_encode ( array (
                'done' => false,
                'msg' => ''
            ) );
            die ();
        }

        $list_array = array ();
        $list_array['gc_id'] = 0;
        $list_array['type_id'] = $staple_info['type_id'];
        $list_array['done'] = true;
        $list_array['one'] = '';
        $list_array['two'] = '';
        $list_array['three'] = '';

        $gc_id_1 = intval ( $staple_info['gc_id_1'] );
        $gc_id_2 = intval ( $staple_info['gc_id_2'] );
        $gc_id_3 = intval ( $staple_info['gc_id_3'] );

        /**
         * 查询同级分类列表
         */
        $model_goods_class = Model ( 'goods_class' );
        // 1级
        if ($gc_id_1 > 0) {
            $list_array['gc_id'] = $gc_id_1;
            $class_list = $model_goods_class->getGoodsClass($_SESSION['store_id']);
            if (empty ( $class_list ) || ! is_array ( $class_list )) {
                echo json_encode ( array (
                    'done' => false,
                    'msg' => ''
                ) );
                die ();
            }
            foreach ( $class_list as $val ) {
                if ($val ['gc_id'] == $gc_id_1) {
                    $list_array ['one'] .= '<li class="" onclick="selClass($(this));" data-param="{gcid:' . $val ['gc_id'] . ', deep:1, tid:' . $val ['type_id'] . '}" nctype="selClass"> <a class="classDivClick" href="javascript:void(0)"><span class="has_leaf"><i class="icon-double-angle-right"></i>' . $val ['gc_name'] . '</span></a> </li>';
                } else {
                    $list_array ['one'] .= '<li class="" onclick="selClass($(this));" data-param="{gcid:' . $val ['gc_id'] . ', deep:1, tid:' . $val ['type_id'] . '}" nctype="selClass"> <a class="" href="javascript:void(0)"><span class="has_leaf"><i class="icon-double-angle-right"></i>' . $val ['gc_name'] . '</span></a> </li>';
                }
            }
        }
        // 2级
        if ($gc_id_2 > 0) {
            $list_array['gc_id'] = $gc_id_2;
            $class_list = $model_goods_class->getGoodsClass($_SESSION['store_id'], $gc_id_1, 2);
            if (empty ( $class_list ) || ! is_array ( $class_list )) {
                echo json_encode ( array (
                        'done' => false,
                        'msg' => ''
                ) );
                die ();
            }
            foreach ( $class_list as $val ) {
                if ($val ['gc_id'] == $gc_id_2) {
                    $list_array ['two'] .= '<li class="" onclick="selClass($(this));" data-param="{gcid:' . $val ['gc_id'] . ', deep:2, tid:' . $val ['type_id'] . '}" nctype="selClass"> <a class="classDivClick" href="javascript:void(0)"><span class="has_leaf"><i class="icon-double-angle-right"></i>' . $val ['gc_name'] . '</span></a> </li>';
                } else {
                    $list_array ['two'] .= '<li class="" onclick="selClass($(this));" data-param="{gcid:' . $val ['gc_id'] . ', deep:2, tid:' . $val ['type_id'] . '}" nctype="selClass"> <a class="" href="javascript:void(0)"><span class="has_leaf"><i class="icon-double-angle-right"></i>' . $val ['gc_name'] . '</span></a> </li>';
                }
            }
        }
        // 3级
        if ($gc_id_3 > 0) {
            $list_array['gc_id'] = $gc_id_3;
            $class_list = $model_goods_class->getGoodsClass($_SESSION['store_id'], $gc_id_2, 3);
            if (empty ( $class_list ) || ! is_array ( $class_list )) {
                echo json_encode ( array (
                        'done' => false,
                        'msg' => ''
                ) );
                die ();
            }
            foreach ( $class_list as $val ) {
                if ($val ['gc_id'] == $gc_id_3) {
                    $list_array ['three'] .= '<li class="" onclick="selClass($(this));" data-param="{gcid:' . $val ['gc_id'] . ', deep:3, tid:' . $val ['type_id'] . '}" nctype="selClass"> <a class="classDivClick" href="javascript:void(0)"><span class="has_leaf"><i class="icon-double-angle-right"></i>' . $val ['gc_name'] . '</span></a> </li>';
                } else {
                    $list_array ['three'] .= '<li class="" onclick="selClass($(this));" data-param="{gcid:' . $val ['gc_id'] . ', deep:3, tid:' . $val ['type_id'] . '}" nctype="selClass"> <a class="" href="javascript:void(0)"><span class="has_leaf"><i class="icon-double-angle-right"></i>' . $val ['gc_name'] . '</span></a> </li>';
                }
            }
        }
        // 转码
        if (strtoupper ( CHARSET ) == 'GBK') {
            $list_array = Language::getUTF8 ( $list_array );
        }
        echo json_encode ( $list_array );
        die ();
    }
    /**
     * AJAX添加商品规格值
     */
    public function ajax_add_specOp() {
        $name = trim($_GET['name']);
        $gc_id = intval($_GET['gc_id']);
        $sp_id = intval($_GET['sp_id']);
        if ($name == '' || $gc_id <= 0 || $sp_id <= 0) {
            echo json_encode(array('done' => false));die();
        }
        $insert = array(
            'sp_value_name' => $name,
            'sp_id' => $sp_id,
            'gc_id' => $gc_id,
            'store_id' => $_SESSION['store_id'],
            'sp_value_color' => null,
            'sp_value_sort' => 0,
        );
        $value_id = Model('spec')->addSpecValue($insert);
        if ($value_id) {
            echo json_encode(array('done' => true, 'value_id' => $value_id));die();
        } else {
            echo json_encode(array('done' => false));die();
        }
    }
}
