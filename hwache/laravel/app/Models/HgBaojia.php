<?php namespace App\Models;

/**
 * 系统默认金额数据模型
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;

use Cache;
use DB;
use Exception;
use App\Com\Hwache\Baojia\Shenhe;

class HgBaojia extends Model
{

    protected $table = 'hg_baojia';
    protected $primaryKey = 'bj_id';
    public $timestamps = false;

    // 初始化查询条件
    private static $map = array(
        'bj_step'    => 99, // 报价步骤,99为完成
        'bj_is_pass' => 1, // 报价是否通过发布
        'bj_status'  => 1, // 报价状态,是否正常报价
    );

    use Common;

    /**
     * 获取报价列表，包含分页
     * @param array $map
     * @param $pageSize
     * @param $page
     * @param $cacheTime
     * @return mixed
     */
    public static function getBaojiaList(array $map, $pageSize, $page, $cacheTime)
    {
        // 查询条件缓存名称
        $tmpMap = $map;
        unset($tmpMap['bj_start_time']);
        unset($tmpMap['bj_end_time']);
        $cacheName = serialize($tmpMap) . $page;
        if (!Cache::has($cacheName)) {
            // 查询条件
            $map = array_merge($map, self::$map);
            $cacheData = self::leftJoin(
                'hg_baojia_price as bp',
                'hg_baojia.bj_id',
                '=',
                'bp.bj_id')
                ->leftJoin('hg_baojia_area as ba', 'hg_baojia.bj_id', '=', 'ba.bj_id')
                ->param($map)
                ->orderBy('bj_end_time')
                ->orderBy('bj_lckp_price')
                ->paginate($pageSize);
            if (!empty($cacheData) && !config('app.debug')) {
                Cache::put($cacheName, $cacheData, $cacheTime);
            }
        } else {
            $cacheData = Cache::get($cacheName);
        }

        return $cacheData;
    }

    /**
     * @param array $map
     * @param $cacheTime
     * @return mixed
     */
    public static function getResource(array $map, $cacheTime)
    {
        // 查询条件缓存名称
        $tmpMap = $map;
        unset($tmpMap['bj_start_time']);
        unset($tmpMap['bj_end_time']);
        $cacheName = 'count' . serialize($tmpMap);
        if (!Cache::has($cacheName)) {
            // 合并查询条件
            $map = array_merge($map, self::$map);
            $cacheData = self::leftJoin(
                'hg_baojia_price as bp',
                'hg_baojia.bj_id',
                '=',
                'bp.bj_id')
                ->leftJoin('hg_baojia_area as ba', 'hg_baojia.bj_id', '=', 'ba.bj_id')
                ->param($map);
            if (!empty($cacheData) && !config('app.debug')) {
                Cache::put($cacheName, $cacheData, $cacheTime);
            }
        } else {
            $cacheData = Cache::get($cacheName);
        }

        return $cacheData;
    }

    /**
     * 获取指定id或者serial的报价详细信息
     * 如果serial为ture，则id为bj_serial
     * @param $id
     * @param bool $serial
     */
    public static function getBaojiaInfo($id, $serial = false)
    {
        $sql = self::leftJoin('hg_baojia_price as bp', 'hg_baojia.bj_id', '=', 'bp.bj_id')
            ->leftjoin('hc_price', 'hg_baojia.bj_id', '=', 'hc_price.id')
            ->leftjoin('hc_scope', 'hg_baojia.daili_dealer_id', '=', 'hc_scope.dealer_id')
            ->leftjoin('hg_dealer', 'hg_baojia.dealer_id', '=', 'hg_dealer.d_id');

        if ($serial) {
            return $sql->where('bj_serial', '=', $id)->first();
        } else {
            return $sql->where('hg_baojia.bj_id', '=', $id)->first();
        }
    }

    /**
     * 编辑/新增报价 数据保存 add by jerry
     * @param 报价id $id
     * @param 步骤 $step
     * @param 插入或者更新的数据 $data
     */
    public static function editBaojia($bj_id, $step, $data)
    {
        if ($bj_id == 0) {//新增
            if ($step == 1) {//新增报价
                // 开启事务
                DB::beginTransaction();
                try {
                    //car_hg_baojia 基础数据插入
                    $t = explode(' ', microtime());//用户生成报价编号
                    $insertData = array(
                        'm_id'                  => $data['member_id'],//经销商代理ID
                        'bj_serial'             => $t[1] . ltrim($t['0'], '0.'),//报价编号
                        'dealer_id'             => $data['dealer_id'],//经销商ID
                        'daili_dealer_id'       => $data['daili_dealer_id'],//经销商ID
                        'dealer_name'           => $data['dealer_name'],//经销商名称
                        'bj_is_xianche'         => $data['bj_is_xianche'],//是否现车
                        'bj_dealer_internal_id' => $data['bj_dealer_internal_id'],//经销商内部编号
                        'brand_id'              => $data['brand_id'],//车型id
                        'car_staple_id'         => $data['car_staple_id'],//常用车型ID
                        'gc_name'               => $data['gc_name'],//车型名称
                        'gc_series'             => $data['series'],//车系列
                        'bj_step'               => 2,//步骤
                        'bj_is_pass'            => 0,//新建初始化审核状态
                        'bj_status'             => 1,//正常报价
                        'bj_create_time'        => date('Y-m-d H:i:s'),//报价初创时间
                    );

                    $bj_id = DB::table('hg_baojia')->insertGetId($insertData);//获取报价ID
                    if ($bj_id == false) {
                        throw new Exception('car_hg_baojia 基础插入报错');
                    }

                    //car_hg_baojia_area 销售地区数据插入
                    $area = $data['area'];
                    $areaData = array();
                    /**
                     * $area 格式  array("$city_id"=>$province_id)
                     */
                    if (is_array($area) && count($area) > 0) {
                        foreach ($area as $k => $v) {
                            $areaData[] = array(
                                'bj_id'    => $bj_id,//报价id
                                'country'  => 0,//是否全国
                                'province' => $v,//省份
                                'city'     => $k,//城市id
                            );
                        }

                        $e = DB::table('hg_baojia_area')->insert($areaData);
                        if ($e == false) {
                            throw new Exception('销售报价地区数据提交失败');
                        }

                    } else {
                        throw new Exception('销售报价地区不能为空');
                    }

                    //car_hg_baojia_price 销售报价数据插入
                    $priceData = array(
                        'bj_id'                  => $bj_id,//报价id
                        'bj_lckp_price'          => $data['bj_lckp_price'],//裸车开票价
                        'bj_doposit_price'       => $data['bj_doposit_price'],//经销商代理填写的消费者担保金
                        'bj_agent_service_price' => $data['bj_agent_service_price'],//代理服务费
                        'bj_earnest_price'       => $data['bj_earnest_price'],//诚意金
                        'bj_car_guarantee'       => $data['bj_car_guarantee'],//订车担保金(需要计算)
                        'bj_price'               => $data['bj_price'],//车价格包含服务费
                    );
                    $e = DB::table('hg_baojia_price')->insert($priceData);
                    if (!$e) {
                        throw new Exception('销售报价[价格]数据提交失败');
                    }

                    //添加扩展数据（空）
                    $e = DB::table('hg_baojia_expand_info')->insert(array('bj_id' => $bj_id));
                    if (!$e) {
                        throw new Exception('报价[hg_baojia_expand_info]扩展数据提交失败');
                    }
                    //排放标准入库数据
                    $paifangInfo = array(
                        'bj_id' => $bj_id,
                        'name'  => 'paifang',
                        'value' => serialize($data['paifang']),
                    );
                    $e = DB::table('hg_baojia_fields')->insertGetId($paifangInfo);
                    if (!$e) {
                        throw new Exception('销售报价[排放标准]数据提交失败');
                    }

                    //复制常用信息中的 赠品数据 进入报价数据中
                    $baojiaZengpin = array();
                    $zengpinList = DB::table('hg_dealer_zengpin')
                        ->join("hg_zengpin", 'hg_dealer_zengpin.zp_id', '=', 'hg_zengpin.id')
                        ->where('hg_dealer_zengpin.dealer_id', $data['dealer_id'])
                        ->where('hg_dealer_zengpin.dl_id', $data['member_id'])
                        ->where('hg_dealer_zengpin.daili_dealer_id', $data['daili_dealer_id'])
                        ->select("hg_zengpin.*", "hg_dealer_zengpin.dl_zp_num as num")
                        ->get();
                    if (count($zengpinList) > 0) {
                        foreach ($zengpinList as $k => $v) {
                            $baojiaZengpin[] = array(
                                'bj_id'      => $bj_id,
                                'zengpin_id' => $v->id,
                                'is_install' => 0,
                                'zp_title'   => $v->title,
                                'zp_price'   => $v->price,
                                'car_brand'  => $v->brand_name,
                                'num'        => $v->num,
                            );
                        }
                        $e = DB::table('hg_baojia_zengpin')->insert($baojiaZengpin);
                        if (!$e) {
                            throw new Exception('销售报价[复制赠品]数据提交失败');
                        }
                    }

                } catch (Exception $e) {

                    DB::rollback();
                    return array('error_code' => 1, 'message' => $e->getMessage(), 'bj_id' => $bj_id);
                }
                // 提交事务
                DB::commit();

                return array('error_code' => 0, 'message' => '报价添加成功', 'bj_id' => $bj_id);
            } else {
                return array('error_code' => 1, 'message' => '非法操作', 'bj_id' => $bj_id);
            }
        } elseif ($bj_id > 0) {//编辑报价 ，第1、2、3、4、5、6步
            $baojiaInfo = self::getBaojiaInfo($bj_id);
            // 开启事务
            DB::beginTransaction();
            try {
                if ($baojiaInfo == false) {
                    throw new Exception('该报价不存在');
                }
                $baojiaInfo = $baojiaInfo->toArray();
                if ($step == 1) {//修改车型价格
                    $updateData = array(
                        'bj_is_xianche'         => $data['bj_is_xianche'],//是否现车
                        'bj_dealer_internal_id' => $data['bj_dealer_internal_id'],//经销商内部编号
                    );
                    //报价步骤不需要更改
                    $e = DB::table('hg_baojia')->where('bj_id', $bj_id)->update($updateData);
                    if ($e === false) {
                        throw new Exception('数据提交失败');
                    }


                    //car_hg_baojia_area 销售地区数据插入
                    DB::table('hg_baojia_area')->where('bj_id', $bj_id)->delete();//删除老的数据

                    $area = $data['area'];
                    $areaData = array();
                    /**
                     * $area 格式  array("$city_id"=>$province_id)
                     */
                    if (is_array($area) && count($area) > 0) {
                        foreach ($area as $k => $v) {
                            $areaData[] = array(
                                'bj_id'    => $bj_id,//报价id
                                'country'  => 0,//是否全国
                                'province' => $v,//省份
                                'city'     => $k,//城市id
                            );
                        }
                        $e = DB::table('hg_baojia_area')->insert($areaData);
                        if ($e == false) {
                            throw new Exception('销售报价地区数据提交失败');
                        }
                    } else {
                        throw new Exception('销售报价地区不能为空');
                    }

                    //car_hg_baojia_price 销售报价数据插入
                    $priceData = array(
                        'bj_lckp_price'          => $data['bj_lckp_price'],//裸车开票价
                        'bj_doposit_price'       => $data['bj_doposit_price'],//经销商代理填写的消费者担保金
                        'bj_agent_service_price' => $data['bj_agent_service_price'],//代理服务费
                        'bj_earnest_price'       => $data['bj_earnest_price'],//诚意金
                        'bj_car_guarantee'       => $data['bj_car_guarantee'],//订车担保金(需要计算)
                        'bj_price'               => $data['bj_price'],//车价格包含服务费
                    );
                    $e = DB::table('hg_baojia_price')->where('bj_id', $bj_id)->update($priceData);
                    if ($e === false) {
                        throw new Exception('销售报价[价格]数据提交失败');
                    }
                    //删除排放
                    DB::table('hg_baojia_fields')
                        ->where('bj_id', $bj_id)
                        ->where('name', 'paifang')
                        ->delete();
                    //插入排放
                    $paifangInfo = array(
                        'bj_id' => $bj_id,
                        'name'  => 'paifang',
                        'value' => serialize($data['paifang']),
                    );
                    //print_r($paifangInfo);
                    $e = DB::table('hg_baojia_fields')->insertGetId($paifangInfo);
                    if (!$e) {
                        throw new Exception('销售报价[排放标准]数据提交失败');
                    }
                } elseif ($step == 2) {//修改车况说明
                    //删除车身属性 老数据
                    $delete_where = $baojiaInfo['bj_is_xianche'] == 1 ? array(
                        'body_color',
                        'interior_color'
                    ) : array('body_color');
                    $e = DB::table('hg_baojia_fields')
                        ->where('bj_id', $bj_id)
                        ->whereIn('name', $delete_where)
                        ->delete();

                    if ($e === false) {
                        throw new Exception('报价[车身颜色、内饰]老数据删除失败');
                    }

                    $carAttr = array();
                    $carAttr[] = array(
                        'bj_id' => $bj_id,
                        'name'  => 'body_color',
                        'value' => serialize($data['body_color'])
                    );
                    if ($baojiaInfo['bj_is_xianche'] == 1) {//现车  内饰提交
                        $carAttr[] = array(
                            'bj_id' => $bj_id,
                            'name'  => 'interior_color',
                            'value' => serialize($data['interior_color'])
                        );
                    }
                    $e = DB::table('hg_baojia_fields')->insert($carAttr);
                    if ($e == false) {
                        throw new Exception('报价[车身颜色、内饰、排放]数据提交失败');
                    }

                    if ($baojiaInfo['bj_is_xianche'] == 1) {
                        $carInfo = array(
                            'bj_producetime' => $data['bj_producetime'],
                            'bj_licheng'     => $data['bj_licheng'],
//                                      'bj_jc_period'=>'',
                        );
                    } else {
                        $carInfo = array(
//                                  'bj_producetime'=>'',
//                                  'bj_licheng'=>'',
                            'bj_jc_period' => $data['bj_jc_period'],
                        );
                    }
                    if ($baojiaInfo['bj_step'] == 2) {//未完成的报价，执行下一步，步骤+1
                        $carInfo['bj_step'] = 3;
                    }
                    //非现车  内饰提交存储hg_baojia,字段bj_temp_internal_color,最后一步提交时 内饰多选分拆报价

                    if ($baojiaInfo['bj_is_xianche'] == 0) {
                        $carInfo['bj_temp_internal_color'] = serialize($data['interior_color']);
                    }

                    //车身颜色
                    $tmpBodyColor = HgCarInfo::where('gc_id', $baojiaInfo['brand_id'])
                        ->where('name', 'body_color')
                        ->first();
                    if (!empty($tmpBodyColor)) {
                        $bodyColorArr = unserialize($tmpBodyColor->value);
                        $carInfo['bj_body_color'] = @$bodyColorArr[$data['body_color']];
                    } else {
                        $carInfo['bj_body_color'] = '';
                    }


                    $e = DB::table('hg_baojia')->where('bj_id', $bj_id)->update($carInfo);
                    if ($e === false) {
                        throw new Exception('数据提交失败[车况说明]');
                    }

                    //删除报价选装件老数据
                    if ($baojiaInfo['bj_is_xianche'] == 1) {//现车删除后装数据
                        $e = DB::table('hg_baojia_xzj')->where('bj_id', $bj_id)->where('is_install', 1)->delete();
                    }
                    //插入现车 前装选装件
                    if (count($data['xzj']) > 0) {//如果有选装件
                        $xzjIds = array_keys($data['xzj']);
                        $dailiXzj = HgXzjDaili::where('xzj_yc', 1)
                            ->where("xzj_front", 1)
                            ->where('car_brand', $baojiaInfo['brand_id'])
                            ->whereIn('xzj_list_id', $xzjIds)
                            ->where('dealer_id', $baojiaInfo['dealer_id'])
                            ->where('member_id', $baojiaInfo['m_id'])
                            ->where('staple_id', $baojiaInfo['car_staple_id'])
                            ->get();
                        $dailiXzj = $dailiXzj->toArray();
                        //print_r($dailiXzj);exit;
                        $bj_xzj = array();
                        foreach ($dailiXzj as $k => $v) {
                            $num = isset($data['xzj_num'][$v['xzj_list_id']]) ? $data['xzj_num'][$v['xzj_list_id']] : 1;
                            $bj_xzj[] = array(
                                'bj_id'       => $bj_id,
                                'xzj_id'      => $v['xzj_list_id'],
                                'is_install'  => 1,
                                'num'         => $num,
                                'fee'         => $v['xzj_fee'],
                                'guide_price' => $v['xzj_guide_price'],
                                'm_id'        => $v['id']
                            );
                        }
                        $e = DB::table('hg_baojia_xzj')->insert($bj_xzj);
                        if ($e == false) {
                            throw new Exception('报价【选装件前装】保存失败');
                        }
                    }
                } elseif ($step == 3) {//修改选装精品

                    //删除报价选装件老数据
                    if ($baojiaInfo['bj_is_xianche'] == 1) {//现车删除后装数据
                        $e = DB::table('hg_baojia_xzj')->where('bj_id', $bj_id)->where('is_install', 0)->delete();
                    } else {//非现车删除前装+后装数据
                        $e = DB::table('hg_baojia_xzj')->where('bj_id', $bj_id)->delete();
                    }

                    if ($e === false) {
                        throw new Exception('删除选装件老数据删除失败');
                    }

                    $xzj = $data['xzj'];//全部ID,数据格式
                    //读取客户选择的【代理常用车型选装件】数据
                    //原厂 +经销商+经销商代理筛选
                    $dailiXzj = HgXzjDaili::where('xzj_yc', 1)
                        ->where('car_brand', $baojiaInfo['brand_id'])
                        ->whereIn('xzj_list_id', $xzj)
                        ->where('dealer_id', $baojiaInfo['dealer_id'])
                        ->where('member_id', $baojiaInfo['m_id'])
                        ->where('staple_id', $baojiaInfo['car_staple_id'])
                        ->get();
                    $xzjData = array();//入库的选装件
                    if (count($dailiXzj) >= 1) {
                        $dailiXzj = $dailiXzj->toArray();
                        foreach ($dailiXzj as $k => $v) {
                            if ($v['xzj_yc'] == 1) {
                                if ($v['xzj_front'] == 1) {//前装
                                    $xzjData[] = array(
                                        'bj_id'       => $bj_id,
                                        'xzj_id'      => $v['xzj_list_id'],
                                        'is_install'  => 1,
                                        'num'         => $v['xzj_max_num'],
                                        'fee'         => '',
                                        'guide_price' => $v['xzj_guide_price'],
                                        'price'       => $v['xzj_price'],
                                        'm_id'        => $v['id'],
                                    );
                                } else {//后装
                                    $xzjData[] = array(
                                        'bj_id'       => $bj_id,
                                        'xzj_id'      => $v['xzj_list_id'],
                                        'is_install'  => 0,
                                        'num'         => $v['xzj_max_num'],
                                        'fee'         => $v['xzj_fee'],
                                        'guide_price' => $v['xzj_guide_price'],
                                        'price'       => $v['xzj_price'],
                                        'm_id'        => $v['id'],
                                    );
                                }

                            } else {
                                continue;
                            }
                        }

                        $e = DB::table('hg_baojia_xzj')->insert($xzjData);
                        if ($e == false) {
                            throw new Exception('报价【选装精品】保存失败');
                        }
                    }
                    $carInfo['bj_xzj_zhekou'] = $data['bj_xzj_zhekou'];//选装件折扣
                    if ($baojiaInfo['bj_step'] == 3) {//未完成的报价，执行下一步，步骤+1
                        $carInfo['bj_step'] = 4;
                    }
                    $e = DB::table('hg_baojia')->where('bj_id', $bj_id)->update($carInfo);
                    if ($e === false) {
                        throw new Exception('数据提交失败[选装精品]');
                    }
                } elseif ($step == 4) {//修改首年保险

                    $carInfo = array(
                        'bj_baoxian'   => $data['bj_baoxian'],
                        'bj_bx_select' => $data['bj_bx_select'],
                    );
                    if ($baojiaInfo['bj_step'] == 4) {//未完成的报价，执行下一步，步骤+1
                        $carInfo['bj_step'] = 5;
                    }

                    //更新hg_baojia表中的保险字段
                    $e = DB::table('hg_baojia')->where('bj_id', $bj_id)->update($carInfo);
                    if ($e === false) {
                        throw new Exception('数据提交失败[首年保险]');
                    }

                    //删除原来保险
                    DB::table('hg_baojia_baoxian_chesun_price')->where('bj_id', $bj_id)->delete();
                    DB::table('hg_baojia_baoxian_daoqiang_price')->where('bj_id', $bj_id)->delete();
                    DB::table('hg_baojia_baoxian_sanzhe_price')->where('bj_id', $bj_id)->delete();
                    DB::table('hg_baojia_baoxian_renyuan_price')->where('bj_id', $bj_id)->delete();
                    DB::table('hg_baojia_baoxian_boli_price')->where('bj_id', $bj_id)->delete();
                    DB::table('hg_baojia_baoxian_huahen_price')->where('bj_id', $bj_id)->delete();
                    /**
                     * $seat_num = DB::table('hg_car_info')
                     * ->where('gc_id',$data['car_brand'])
                     * ->where('model','carmodel')
                     * ->where('name','seat_num')
                     * ->value('value');
                     **/
                    //车辆类型  $type 6座以下为个人1 公司3，6座以上为个人2公司4

                    //处理车损险
                    $chesun = $data['chesun'];
                    $bjmp = $data['bjmp'];
                    $chesunData = array();
                    //格式 <input name='chesun[$type]'>
                    //不计免赔 <input name='bjmf[chesun][$type]'>
                    foreach ($chesun as $k => $v) {
                        $chesunData[] = array(
                            'bj_id'              => $bj_id,
                            'type'               => $k,
                            'price'              => $v,
                            'discount_price'     => $v * $data['baoxian_discount'] / 100,
                            'bjm_price'          => $bjmp['chesun'][$k],
                            'bjm_discount_price' => ($bjmp['chesun'][$k] * $data['baoxian_discount'] / 100),
                        );
                    }
                    $e = DB::table('hg_baojia_baoxian_chesun_price')->insert($chesunData);
                    if ($e == false) {
                        throw new Exception('保险[车损险]保存失败');
                    }
                    //处理盗抢险
                    $daoqiang = $data['daoqiang'];
                    $daoqiangData = array();
                    //格式 <input name='daoqiang[$type]'>
                    //不计免赔 <input name='bjmf[daoqiang][$type]'>
                    foreach ($daoqiang as $k => $v) {
                        $daoqiangData[] = array(
                            'bj_id'              => $bj_id,
                            'type'               => $k,
                            'price'              => $v,
                            'discount_price'     => $v * $data['baoxian_discount'] / 100,
                            'bjm_price'          => $bjmp['daoqiang'][$k],
                            'bjm_discount_price' => $bjmp['daoqiang'][$k] * $data['baoxian_discount'] / 100
                        );
                    }
                    $e = DB::table('hg_baojia_baoxian_daoqiang_price')->insert($daoqiangData);
                    if ($e == false) {
                        throw new Exception('保险[盗抢险]保存失败');
                    }
                    //处理三者险
                    $sanzhe = $data['sanzhe'];
                    //格式 <input name='sanze[$type][$compensate]'>
                    //不计免赔 <input name='bjmf[sanzhe][$type]'>
                    $sanzheData = array();
                    foreach ($sanzhe as $key => $value) {
                        foreach ($value as $k => $v) {
                            $sanzheData[] = array(
                                'bj_id'              => $bj_id,
                                'type'               => $key,
                                'compensate'         => $k,
                                'price'              => $v,
                                'discount_price'     => $v * $data['baoxian_discount'] / 100,
                                'bjm_price'          => $v * $bjmp['sanzhe'][$key] / 100,
                                'bjm_discount_price' => $v * $bjmp['sanzhe'][$key] * $data['baoxian_discount'] / 10000,
                                'bjm_percent'        => $bjmp['sanzhe'][$key],
                            );
                        }
                    }
                    $e = DB::table('hg_baojia_baoxian_sanzhe_price')->insert($sanzheData);
                    if ($e == false) {
                        throw new Exception('保险[三者险]保存失败');
                    }

                    //车上人员险
                    $renyuan = $data['renyuan'];
                    //格式 <input name='renyuan[$type][$compensate][$shenfen]'>
                    //不计免赔 <input name='bjmf[renyuan][$type]'>
                    $renyuanData = array();
                    foreach ($renyuan as $key => $value) {//
                        foreach ($value as $k => $v) {
                            foreach ($v as $k1 => $v1) {
                                $renyuanData[] = array(
                                    'bj_id'              => $bj_id,
                                    'type'               => $key,
                                    'compensate'         => $k,
                                    'staff'              => $k1,
                                    'price'              => $v1,
                                    'discount_price'     => $v1 * $bjmp['renyuan'][$key] / 100,
                                    'bjm_price'          => $v1 * $bjmp['renyuan'][$key] / 100,
                                    'bjm_discount_price' => $v1 * $bjmp['renyuan'][$key] * $data['baoxian_discount'] / 10000,
                                    'bjm_percent'        => $bjmp['renyuan'][$key],
                                );
                            }
                        }
                    }
                    $e = DB::table('hg_baojia_baoxian_renyuan_price')->insert($renyuanData);
                    if ($e == false) {
                        throw new Exception('保险[三者险]保存失败');
                    }

                    //玻璃险
                    $boli = $data['boli'];
                    //格式 <input name='boli[$type][$state]'>
                    $boliData = array();
                    // 玻璃单独破碎险
                    $d = array();
                    foreach ($boli as $key => $value) {
                        foreach ($value as $k => $v) {
                            $boliData[] = array(
                                'bj_id' => $bj_id,
                                'type'  => $key,
                                'state' => $k,
                                'price' => $v,
                            );
                        }
                    }
                    $e = DB::table('hg_baojia_baoxian_boli_price')->insert($boliData);
                    if ($e == false) {
                        throw new Exception('保险[玻璃险]保存失败');
                    }

                    // 车身划痕损失险
                    $huahen = $data['huahen'];
                    //格式 <input name='huahen[$type][$compensate]'>
                    //不计免赔 <input name='bjmf[huahen][$type]'>
                    $huahenData = array();
                    foreach ($huahen as $key => $value) {
                        foreach ($value as $k => $v) {
                            $huahenData[] = array(
                                'bj_id'              => $bj_id,
                                'type'               => $key,
                                'compensate'         => $k,
                                'price'              => $v,
                                'discount_price'     => $v * $data['baoxian_discount'] / 100,
                                'bjm_price'          => $v * $bjmp['huahen'][$key] / 100,
                                'bjm_discount_price' => $v * $bjmp['huahen'][$key] * $data['baoxian_discount'] / 10000,
                                'bjm_percent'        => $bjmp['huahen'][$key],
                            );
                        }
                    }
                    $e = DB::table('hg_baojia_baoxian_huahen_price')->insert($huahenData);
                    if ($e == false) {
                        throw new Exception('保险[划痕险]保存失败');
                    }
                    if ($baojiaInfo['bj_step'] == 4 && $baojiaInfo['bj_copy_id'] == 0) {//第一次编辑保存保险时  操作以下步骤
                        //复制常用信息管理中对应经销商的 其他费用入hg_baojia_other_price
                        $otherPrice = DB::table('hg_dealer_other_price')
                            ->where('dealer_id', $baojiaInfo['dealer_id'])
                            ->where('daili_dealer_id', $baojiaInfo['daili_dealer_id'])
                            ->where('daili_id', $baojiaInfo['m_id'])
                            ->get();
                        $otherList = array();
                        if (count($otherPrice) > 0) {
                            foreach ($otherPrice as $v) {
                                $otherList[] = array(
                                    'bj_id'       => $baojiaInfo['bj_id'],
                                    'other_id'    => $v->other_id,
                                    'other_name'  => $v->other_name,
                                    'other_price' => $v->other_price,
                                );
                            }
                            $e = DB::table("hg_baojia_other_price")->insert($otherList);
                            if ($e == false) {
                                throw new Exception('复制常用信息【其他费用】保存失败');
                            }
                        }
                    }
                } elseif ($step == 5) {//修改收费标准
                    //car_hg_baojia  shangpai  linpai
                    $carInfo = array(
                        'bj_shangpai' => $data['bj_shangpai'],
                        'bj_linpai'   => $data['bj_linpai'],
                    );
                    if ($baojiaInfo['bj_step'] == 5) {//未完成的报价，执行下一步，步骤+1
                        $carInfo['bj_step'] = 6;
                    }
                    $e = DB::table('hg_baojia')->where('bj_id', $bj_id)->update($carInfo);
                    if ($e === false) {
                        throw new Exception('收费标准[基础数据]保存失败');
                    }


                    $price = array(
                        'bj_shangpai_price'               => $data['bj_shangpai_price'],
                        'bj_license_plate_break_contract' => $data['bj_license_plate_break_contract'],
                    );

                    $price['bj_linpai_price'] = $data['bj_linpai_price'];


                    $otherPriceTotal = 0;
                    //其他费用合计
                    $otherPriceTotal = DB::table('hg_baojia_other_price')->where('bj_id',
                        $baojiaInfo['bj_id'])->sum('other_price');
                    $otherPriceTotal = round($otherPriceTotal, 2);
                    $price['bj_other_price'] = $otherPriceTotal;
                    $e = DB::table('hg_baojia_price')->where('bj_id', $bj_id)->update($price);
                    if ($e === false) {
                        throw new Exception('收费标准[上牌费用]保存失败【hg_baojia_price】');
                    }

                    //刷卡标准处理
                    $shuka = array(
                        'xyk_status'   => $data['xyk_status'],
                        'xyk_number'   => $data['xyk_number'],
                        'xyk_per_num'  => $data['xyk_per_num'],
                        'xyk_yuan_num' => $data['xyk_yuan_num'],
                        'jjk_status'   => $data['jjk_status'],
                        'jjk_number'   => $data['jjk_number'],
                        'jjk_per_num'  => $data['jjk_per_num'],
                        'jjk_yuan_num' => $data['jjk_yuan_num'],
                    );

                    $e = DB::table('hg_baojia_expand_info')
                        ->where('bj_id', $bj_id)
                        ->update($shuka);
                    if ($e === false) {
                        throw new Exception('收费标准【刷卡标准】保存失败');
                    }
                } elseif ($step == 6) {//修改其他事项

                    $interior_color = $baojiaInfo['bj_temp_internal_color'];//内饰颜色
                    $carInfo = array(
                        //'bj_butie'=>$data['bj_butie'],
                        'bj_zf_butie'            => $data['bj_zf_butie'],
                        'bj_cj_butie'            => $data['bj_cj_butie'],
                        'bj_num'                 => $data['bj_num'],
                        'bj_start_time'          => $data['bj_start_time'],//时间戳
                        'bj_end_time'            => $data['bj_end_time'],//时间戳
                        'bj_temp_internal_color' => '',

                    );
                    if ($baojiaInfo['bj_step'] == 6) {//未完成的报价，执行下一步，步骤+1
                        $carInfo['bj_step'] = 99;
                    }

                    //报价信息更新
                    $e = DB::table('hg_baojia')->where('bj_id', $bj_id)->update($carInfo);
                    if ($e === false) {
                        throw new Exception('数据提交失败[其他事项]');
                    }

                    //补贴描述
                    $butieInfo = array(
                        'bt_status'     => $data['bt_status'],
                        'bt_work_day'   => $data['bt_work_day'],
                        'bt_work_month' => $data['bt_work_month'],
                    );
                    //更新补贴数据
                    $e = DB::table('hg_baojia_expand_info')
                        ->where('bj_id', $bj_id)
                        ->update($butieInfo);
                    if ($e === false) {
                        throw new Exception('数据提交失败[补贴备注]【hg_baojia_more】');
                    }

                    //判断非现车，如果内饰多选 拆分多条一模一样的报价，只有报价编号和内饰颜色不同
                    if ($baojiaInfo['bj_is_xianche'] == 0) {

                        $interior_color = !empty($interior_color) ? unserialize($interior_color) : array();
                        $num = count($interior_color);
                        DB::table('hg_baojia_fields')
                            ->where('bj_id', $bj_id)
                            ->where('name', 'interior_color')
                            ->delete();
                        $carAttr = array(
                            'bj_id' => $bj_id,
                            'name'  => 'interior_color',
                            'value' => serialize(current($interior_color))
                        );
                        $e = DB::table('hg_baojia_fields')->insert($carAttr);
                        if ($e == false) {
                            throw new Exception('数据提交失败[非现车内饰]【hg_baojia_field】');
                        }

                        //更新非现车中的数据 临时内饰
                        $bj_temp_internal_color[] = current($interior_color);
                        $e = DB::table('hg_baojia')
                            ->where('bj_id', $bj_id)
                            ->update(array('bj_temp_internal_color' => serialize($bj_temp_internal_color)));
                        if ($e === false) {
                            throw new Exception('更新非现车内饰【hg_baojia】【bj_temp_internal_color】失败');
                        }

                    }
                } else {
                    return array('error_code' => 1, 'message' => '非法操作', 'bj_id' => $bj_id);
                }

            } catch (Exception $e) {
                DB::rollback();
                return array('error_code' => 1, 'message' => $e->getMessage(), 'bj_id' => $bj_id);
            }
            // 提交事务
            DB::commit();

            /**
             * 插入系统审核结果
             *
             * @author 技安
             */
            if ($step == 6) {
                // 生成报价，调用价格公式接口
                $accounting = app('App\\Com\\Hwache\\Baojia\\Accounting');
                $hcPrice = $accounting->getPriceAll($bj_id);
                $accounting->savePrice($bj_id, $hcPrice['hcPrice']);
                (new Shenhe())->verifyBaojia($bj_id);
            }
            /**
             * End
             */

            return array('error_code' => 0, 'message' => '数据保存成功', 'bj_id' => $bj_id);
        } else {
            return array('error_code' => 1, 'message' => '非法操作', 'bj_id' => $bj_id);
        }
    }

    /**
     * 复制报价 add by jerry
     * @param 报价 $bj_id
     * @param 经销商member_id $member_id 防止操作其他经销商报价
     * return
     */
    public static function copyBaojia($bj_id, $member_id)
    {
        $bjInfo = self::findOrFail($bj_id)->toArray();
        $bj_step = $bjInfo['bj_step'];

        //插入数据太频繁，怀疑是误操作，阻止该请求
        if (strtotime($bjInfo['bj_create_time']) > time() - 3) {
            return array('error_code' => 1, 'message' => "复制报价过于频繁", 'bj_id' => $bj_id);
        }

        unset($bjInfo['bj_id']);
        unset($bjInfo['bj_update_time']);

        DB::beginTransaction();
        try {
            //car_hg_baojia 基础数据插入
            $bjInsertData = $bjInfo;

            $bjInsertData['m_id'] = $member_id;//发布会员id
            $bjInsertData['bj_serial'] = self::generateBaojiaSerial();//报价编号
            $bjInsertData['bj_start_time'] = $bjInsertData['bj_start_time'] > time() ? $bjInsertData['bj_start_time'] : time();//起始时间
            $bjInsertData['bj_end_time'] = $bjInsertData['bj_end_time'] > time() ? $bjInsertData['bj_end_time'] : time();//结束时间
            $bjInsertData['bj_copy_id'] = $bj_id;
            $bjInsertData['bj_step'] = $bj_step == 99 ? 6 : $bj_step;
            $bjInsertData['bj_create_time'] = date("Y-m-d H:i:s");
            $bjInsertData['bj_status'] = 1;
            $bjInsertData['bj_reason'] = "";
            $bjInsertData['bj_start_time'] = "";
            $bjInsertData['bj_end_time'] = "";
            $bjInsertData['bj_is_public'] = 0;
            $bjInsertData['bj_is_pass'] = 0;

            $insert_bj_id = DB::table('hg_baojia')->insertGetId($bjInsertData);//获取报价ID
            if ($insert_bj_id == false) {
                throw new Exception('car_hg_baojia 基础插入报错');
            }

            //car_hg_baojia_area 销售地区数据插入
            $areaData = HgBaojiaArea::where('bj_id', '=', $bj_id)->firstOrFail()->toArray();
            unset($areaData['id']);
            $areaData['bj_id'] = $insert_bj_id;
            $e = DB::table('hg_baojia_area')->insert($areaData);
            if ($e == false) {
                throw new Exception('销售报价地区数据提交失败');
            }

            //car_hg_baojia_price 销售报价数据插入
            $priceData = HgBaojiaPrice::where('bj_id', '=', $bj_id)->firstOrFail()->toArray();
            $priceData['bj_id'] = $insert_bj_id;
            $e = DB::table('hg_baojia_price')->insert($priceData);
            if (!$e) {
                throw new Exception('销售报价[价格]数据提交失败');
            }

            //hg_baojia_expand_info 扩展数据插入
            $expandInfo = DB::table('hg_baojia_expand_info')->where('bj_id', '=', $bj_id)->first();
            unset($expandInfo->id);
            $expandInfo->bj_id = $insert_bj_id;
            $expandInfo = get_object_vars($expandInfo);
            $e = DB::table('hg_baojia_expand_info')->insert($expandInfo);
            if (!$e) {
                throw new Exception('报价[hg_baojia_expand_info]扩展数据提交失败');
            }

            //hg_baojia_fields 颜色、排放等补充字段数据插入
            $BaojiaFields = HgBaojiaField::where('bj_id', '=', $bj_id)->get()->toArray();
            if ($BaojiaFields) {
                foreach ($BaojiaFields as $field) {
                    unset($field['id']);
                    $field['bj_id'] = $insert_bj_id;
                    $insertFieldnData[] = $field;
                }
                $e = DB::table('hg_baojia_fields')->insert($insertFieldnData);
                if (!$e) {
                    throw new Exception('销售报价[颜色排放等补充信息]数据提交失败');
                }
            }

            //hg_baojia_zengpin 赠品数据插入
            $baojiaZengpin = DB::table('hg_baojia_zengpin')->where('bj_id', '=', $bj_id)->get();
            if ($baojiaZengpin) {
                foreach ($baojiaZengpin as $zengpin) {
                    unset($zengpin->id);
                    $zengpin->bj_id = $insert_bj_id;
                    $insertZengpinData[] = get_object_vars($zengpin);
                }
                $e = DB::table('hg_baojia_zengpin')->insert($insertZengpinData);
                if (!$e) {
                    throw new Exception('销售报价[复制赠品]数据提交失败');
                }
            }

            $step = $bjInfo['bj_step'];
            if ($step > 1) {
                //选装件
                $xzjData = HgBaojiaXzj::where('bj_id', '=', $bj_id)->get()->toArray();
                if ($xzjData) {
                    foreach ($xzjData as $xzj) {
                        unset($xzj['id']);
                        $xzj['bj_id'] = $insert_bj_id;
                        $insertXzjData[] = $xzj;
                    }
                    $e = DB::table('hg_baojia_xzj')->insert($insertXzjData);
                    if ($e == false) {
                        throw new Exception('报价【选装精品】保存失败');
                    }
                }
            }

            if ($step > 4) {
                //车损险
                $chesunData = DB::table('hg_baojia_baoxian_chesun_price')->where('bj_id', '=', $bj_id)->get();
                if ($chesunData) {
                    foreach ($chesunData as $chesun) {
                        unset($chesun->id);
                        $chesun->bj_id = $insert_bj_id;
                        $insertChesungData[] = get_object_vars($chesun);
                    }
                    $e = DB::table('hg_baojia_baoxian_chesun_price')->insert($insertChesungData);
                    if ($e == false) {
                        throw new Exception('保险[车损险]保存失败');
                    }
                }

                //盗抢险
                $daoqiangData = DB::table('hg_baojia_baoxian_daoqiang_price')->where('bj_id', '=', $bj_id)->get();
                if ($daoqiangData) {
                    foreach ($daoqiangData as $daoqiang) {
                        unset($daoqiang->id);
                        $daoqiang->bj_id = $insert_bj_id;
                        $insertDaoqiangData[] = get_object_vars($daoqiang);
                    }
                    $e = DB::table('hg_baojia_baoxian_daoqiang_price')->insert($insertDaoqiangData);
                    if ($e == false) {
                        throw new Exception('保险[盗抢险]保存失败');
                    }
                }

                //三者险
                $sanzheData = DB::table('hg_baojia_baoxian_sanzhe_price')->where('bj_id', '=', $bj_id)->get();
                if ($sanzheData) {
                    foreach ($sanzheData as $sanzhe) {
                        unset($sanzhe->id);
                        $sanzhe->bj_id = $insert_bj_id;
                        $insertSanzheData[] = get_object_vars($sanzhe);
                    }
                    $e = DB::table('hg_baojia_baoxian_sanzhe_price')->insert($insertSanzheData);
                    if ($e == false) {
                        throw new Exception('保险[三者险]保存失败');
                    }
                }

                //车上人员险
                $renyuanData = DB::table('hg_baojia_baoxian_renyuan_price')->where('bj_id', '=', $bj_id)->get();
                if ($renyuanData) {
                    foreach ($renyuanData as $renyuan) {
                        unset($renyuan->id);
                        $renyuan->bj_id = $insert_bj_id;
                        $insertRenyuanData[] = get_object_vars($renyuan);
                    }
                    $e = DB::table('hg_baojia_baoxian_renyuan_price')->insert($insertRenyuanData);
                    if ($e == false) {
                        throw new Exception('保险[三者险]保存失败');
                    }
                }


                //玻璃险
                $boliData = DB::table('hg_baojia_baoxian_boli_price')->where('bj_id', '=', $bj_id)->get();
                if ($boliData) {
                    foreach ($boliData as $boli) {
                        unset($boli->id);
                        $boli->bj_id = $insert_bj_id;
                        $insertBoliData[] = get_object_vars($boli);
                    }
                    $e = DB::table('hg_baojia_baoxian_boli_price')->insert($insertBoliData);
                    if ($e == false) {
                        throw new Exception('保险[玻璃险]保存失败');
                    }
                }

                //车身划痕损失险
                $huahenData = DB::table('hg_baojia_baoxian_huahen_price')->where('bj_id', '=', $bj_id)->get();
                if ($huahenData) {
                    foreach ($huahenData as $huahen) {
                        unset($huahen->id);
                        $huahen->bj_id = $insert_bj_id;
                        $insertHuahenData[] = get_object_vars($huahen);
                    }
                    $e = DB::table('hg_baojia_baoxian_huahen_price')->insert($insertHuahenData);
                    if ($e == false) {
                        throw new Exception('保险[划痕险]保存失败');
                    }
                }

                //其他费用
                $otherPriceData = DB::table('hg_baojia_other_price')->where('bj_id', '=', $bj_id)->get();
                if ($otherPriceData) {
                    foreach ($otherPriceData as $otherPrice) {
                        unset($otherPrice->id);
                        $otherPrice->bj_id = $insert_bj_id;
                        $insertOtherPriceData[] = get_object_vars($otherPrice);
                    }
                    $e = DB::table('hg_baojia_other_price')->insert($insertOtherPriceData);
                    if ($e == false) {
                        throw new Exception('复制常用信息【其他费用】保存失败');
                    }
                }
            }
        } catch (Exception $e) {
            DB::rollback();
            return array('error_code' => 1, 'message' => $e->getMessage(), 'bj_id' => $bj_id);
        }

        // 提交事务
        DB::commit();
        return array('error_code' => 0, 'message' => '报价添加成功', 'bj_id' => $insert_bj_id);
    }

    /**批处理报价状态 删除、暂停、终止、恢复 add by jerry
     * 报价删除【假删】包含更新 bj_status=0（终止，即为失效报价） 和bj_status=3（假删，经销商处找不到）
     * @param 报价ID 数组格式 $bj_id_array
     * @param 经销商member_id $member_id 防止操作其他经销商报价
     * $status 0|3
     */
    public static function UpdateBaojiaStatus($bj_id_array, $member_id, $status = 0, $reason = '')
    {

    }


    /**
     * 报价数据真删，用户复制未完成的报价撤销 或者其他用处  add by jerry
     * @param 报价ID 数组格式  $bj_id_array
     * @return multitype:number 0|1 |multitype:number string
     */
    public static function realDeleteBaojia($bj_id_array)
    {

    }

    /**
     * 报价的数量统计
     * @param $member_id
     * @return mixed
     */
    public static function getBaojiaCount($member_id)
    {
        $count['unfinished'] = DB::table('hg_baojia')
            ->where('m_id', $member_id)
            ->where('bj_step', '<>', '99')
            ->where('bj_status', '1')
            ->count();

        $count['effective'] = DB::table('hg_baojia')
            ->where(['m_id' => $member_id, 'bj_step' => 99, 'bj_status' => 1])
            ->where('bj_start_time', '>', time())
            ->count();

        $count['online'] = DB::table('hg_baojia')
            ->where(['m_id' => $member_id, 'bj_step' => 99, 'bj_status' => 1])
            ->where('bj_start_time', '<=', time())
            ->where('bj_end_time', '>', time())
            ->count();

        $count['suspensive'] = DB::table('hg_baojia')
            ->where(['m_id' => $member_id, 'bj_step' => 99, 'bj_status' => 2])
            //  ->where('bj_start_time','<=',time())
            ->where('bj_end_time', '>', time())
            ->count();

        $count['useless'] = DB::table('hg_baojia')
            ->where(['m_id' => $member_id, 'bj_step' => 99])
            ->where(function ($query) {
                $query->where('bj_end_time', '<', time())
                    ->orWhere('bj_status', '=', 0);
            })
            ->count();
        return $count;


    }


    /**
     * 统计经销商代理的报价 数据  add by jerry
     * @param 经销商id $member_id
     * @return 数据
     */

    public static function getBaojiaCountByType($member_id)
    {
        $list = DB::table('hg_baojia')
            ->where('m_id', $member_id)
            ->where('bj_step', '<>', '99')
            ->where('bj_status', '1')
            ->select('hg_baojia.*')
            ->orderBy('bj_update_time', 'desc')
            ->paginate(10);

        return $list;

    }

    /**
     * 公共模型(报价搜索区域品牌)
     * @param $member_id
     * @return mixed
     */
    public static function getBrand($member_id, $id)
    {
        $quest = DB::table('hg_baojia')
            ->select('d.gc_name', 'd.gc_id')
            ->join('goods_class As b', 'hg_baojia.brand_id', '=', 'b.gc_id')
            ->join('goods_class As c', 'c.gc_id', '=', 'b.gc_parent_id')
            ->join('goods_class As d', 'c.gc_parent_id', '=', 'd.gc_id')
            ->groupBy('c.gc_parent_id')
            ->where(['m_id' => $member_id, 'bj_status' => $id]);

        return $quest;
    }

    /**
     * 公共模型(报价搜索区域之车系)
     * @param $member_id
     * @param $brand_id
     * @return mixed
     */
    public static function getCareries($member_id, $brand_id, $id)
    {
        $quest = DB::table('hg_baojia')
            ->select('c.gc_name', 'c.gc_id')
            ->join('goods_class As b', 'hg_baojia.brand_id', '=', 'b.gc_id')
            ->join('goods_class As c', 'b.gc_parent_id', '=', 'c.gc_id')
            ->where('m_id', $member_id)
            ->where('bj_status', $id)
            ->where('c.gc_parent_id', $brand_id)
            ->groupBy('c.gc_name');

        return $quest;
    }

    /**
     * 公共模型之经销商
     * @param $member_id
     * @return mixed
     */
    public static function getDealer($member_id, $id)
    {
        $quest = DB::table('hg_baojia')
            ->where('m_id', $member_id)
            ->where('bj_status', $id)
            ->select('dealer_name')
            ->groupBy('dealer_name');
        return $quest;
    }

    /**
     * 等待报价模型
     * @param 经销商id $member_id
     * @return 数据
     */
    public static function getWaiting($member_id)
    {

        $list = self::where(['m_id' => $member_id, 'bj_step' => 99, 'bj_status' => 1])
            ->where('bj_start_time', '>', time())
            ->orderBy('bj_serial', 'asc')
            ->paginate(10);

        return $list;
    }

    /**
     * 正在报价模型
     * @param $member_id
     * @return mixed
     */
    public static function getOnline($member_id)
    {
        $list = self::leftjoin('hg_baojia_price', 'hg_baojia.bj_id', '=', 'hg_baojia_price.bj_id')
            ->where(['m_id' => $member_id, 'bj_step' => 99, 'bj_status' => 1])
            ->where('bj_start_time', '<=', time())
            ->where('bj_end_time', '>', time())
            ->select('hg_baojia.bj_serial', 'gc_name', 'hg_baojia.bj_id', 'bj_end_time',
                'hg_baojia_price.bj_lckp_price')
            ->orderBy('bj_serial', 'asc')
            ->paginate(10);

        return $list;
    }

    public static function getSuspensive($member_id)
    {
        $list = self::leftjoin('hg_baojia_price', 'hg_baojia.bj_id', '=', 'hg_baojia_price.bj_id')
            ->where(['m_id' => $member_id, 'bj_step' => 99, 'bj_status' => 2])
            // ->where('bj_start_time','<=',time())
            ->where('bj_end_time', '>', time())
            ->select('hg_baojia.bj_serial', 'gc_name', 'bj_reason', 'hg_baojia.bj_id', 'bj_end_time',
                'hg_baojia_price.bj_lckp_price')
            ->orderBy('bj_serial', 'asc')
            ->paginate(10);

        return $list;
    }


    /**
     * 批量操作的方法
     * @param $member_id
     * @param array $conditions
     * @param array $status
     * @return mixed
     */
    public static function allOperation($member_id, array $conditions, array $status)
    {
        $result = self::where('bj_start_time', '<=', time())
            ->where('bj_end_time', '>', time())
            ->where('bj_step', 99)
            ->where('m_id', $member_id)
            ->where($conditions)
            ->update($status);
        return $result;

    }

    /**
     * 生成报价编号
     * @return string 报价编号
     */
    public static function generateBaojiaSerial()
    {
        $t = explode(' ', microtime());
        return $t[1] . ltrim($t['0'], '0.');
    }

    /**
     * 查询某系列车的颜色
     *
     */
    public static function getCarColorByCarSeries($dealer_id, $brand_id)
    {
        return DB::table('hg_baojia')
            ->where('dealer_id', '=', $dealer_id)
            ->where('brand_id', '=', $brand_id)
            ->where('bj_body_color', '<>', '')
            ->distinct('bj_body_color')
            ->lists('bj_body_color');
    }

    /**
     * @param $seachParams
     * @param $member_id
     * @param $start
     * @param $end
     * @return mixed
     */
    public static function getUselessBaojiaList($seachParams, $member_id, $start, $end)
    {

        $seachParams = array_merge($seachParams, ['m_id' => $member_id, 'bj_step' => 99]);
        $list = self::where($seachParams)
            ->where('bj_start_time', '>', $start)
            ->where('bj_start_time', '<', $end)
            ->where(function ($query) {
                $query->where('bj_end_time', '<', time())
                    ->orWhere('bj_status', '=', 0);
            })
            ->select('bj_id', 'bj_serial', 'gc_name', 'bj_update_time', 'bj_reason')
            ->orderBy('bj_update_time', 'desc')
            ->paginate(10);

        return $list;
    }


    /**
     * @param $id
     * @return mixed
     * 获取详情相关模型数据
     */
    public function getBaojiaData($id)
    {
        $result = self::leftjoin('hc_price', 'hg_baojia.bj_id', '=', 'hc_price.id')
            ->leftjoin('hg_dealer', 'hg_baojia.dealer_id', '=', 'hg_dealer.d_id')
            ->leftjoin('goods_class', 'hg_baojia.brand_id', '=', 'goods_class.gc_id')
            ->leftjoin('hc_scope', 'hg_baojia.daili_dealer_id', '=', 'hc_scope.dealer_id')
            ->leftjoin('hg_baojia_expand_info', 'hg_baojia.bj_id', '=', 'hg_baojia_expand_info.bj_id')
            ->find($id);
        if (count($result)) {
            return $result = $result->toArray();
        }else{
            die('报价不存在!!!');
        }
    }

    /**
     * @param $id
     * @return mixed
     * 查询报价所在地区和保险类型
     */
    public function getAreaBaoxian($id)
    {
        return HgBaojia::join('hg_dealer', 'hg_baojia.dealer_id', '=', 'hg_dealer.d_id')
            ->join('hg_baoxian', 'hg_baojia.bj_bx_select', '=', 'hg_baoxian.bx_id')
            ->select('hg_dealer.d_shi', 'hg_baoxian.bx_is_quanguo')
            ->find($id)
            ->toArray();
    }

}
