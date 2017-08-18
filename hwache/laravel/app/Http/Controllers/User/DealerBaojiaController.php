<?php
/**
 *
 *经销商用户中心-报价管理
 *
 */
namespace App\Http\Controllers\User;

use DB;
use Illuminate\Support\Facades\Response;
use Validator;
use Illuminate\Http\Request;
use Input;
use App\Com\Hwache\User\User;
use App\Http\Controllers\Controller;
use App\Models\HgSeller;
use Hash;
use App\Models\HgLock;
use App\Models\HgUser;
use App\Core\Contracts\Sms\Sms;
use Session;
use Mail;
use Crypt;
use App\Models\HgBaojia;
use App\Models\HgDealer;
use App\Models\HgDailiDealer;
use App\Models\HgWaiter;
use App\Models\HgBaoXian;
use App\Models\HgDealerBaoXian;
use App\Models\HgFields;
use App\Models\HgZengpin;
use App\Models\HgStandard;
use App\Models\HgDealerOtherPrice;
use App\Models\HgCarInfo;
use App\Models\HgXzj;
use App\Models\HgXzjDaili;
use App\Models\HgBaojiaZengpin;
use App\Models\HgBaojiaArea;
use App\Models\HgGoodsClass;
use App\Models\HgGoodsClassStaple;
use App\Models\HgBaojiaBaoxianChesunPrice;
use App\Models\HgBaojiaBaoxianRenyuanPrice;
use App\Models\HgBaojiaBaoxianDaoqiangPrice;
use App\Models\HgBaojiaBaoxianBoliPrice;
use App\Models\HgBaojiaBaoxianHuahenPrice;
use App\Models\HgBaojiaBaoxianSanzhePrice;
use App\Models\HgBaojiaXzj;
use App\Models\HgBaojiaField;
use App\Models\HgAnnex;
use App\Models\Area;
use Illuminate\PHPExcel\PHPExcel\PHPExcel_IOFactory;

class DealerBaojiaController extends Controller
{
    /**
     * 请求依赖
     * @var Request
     */
    private $request;

    /**
     * 用户中心模块依赖
     * @var User
     */
    private $user;

    /**
     * 经销商代理的报价统计
     * @var 数组
     */
    private $baojiaCount;

    /**
     * 构造函数，初始化内部依赖变量
     * @param Request $request
     * @param User $user
     */
    public function __construct(Request $request, User $user)
    {
        $this->middleware('auth.seller');
        $this->request = $request;
        $this->user = $user;

    }

    /**
     * 获取报价信息
     * @param 类型 $type （edit/show/delete）
     * @param 报价 $id
     * @param 步骤 $step
     */
    public function getBaojiaInfo($type, $id, $step)
    {
        //HgBaojia::realDeleteBaojia(251);
        if (!in_array($type, array('edit', 'view'))) {
            die('非法操作');
        }
        $view = array();

        if ($id == 0 && $step == 1) {//新增报价
            $view['flag'] = 'baojia-new';
            $area = config('area');
            $view['area'] = $area;
            $provinceByFirstLetter = array();
            foreach ($area[0] as $k => $v) {
                $provinceByFirstLetter[$v['first_letter']][] = $v;
            }
            ksort($provinceByFirstLetter);
            $view['provinceByFirstLetter'] = $provinceByFirstLetter;
            $dl_id = session('user.member_id');//代理ID
            $dealerList = HgDailiDealer::getDealerByDaili($dl_id, 2);
            $view['dealerList'] = $dealerList;
            $view['sale_area'] = array();

            $gps_city_id = HgSeller::where('member_id', session('user.member_id'))->value('seller_city_id');
            $gps_city = Area::where('area_id', $gps_city_id)->value('area_name');
            $view['gps_city'] = $gps_city;//定位功能

            $paifangArray = HgFields::where('name', 'paifang')
                ->where('model', 'carmodel')
                ->first();
            $view['paifangList'] = !empty($paifangArray->setting) ? unserialize($paifangArray->setting) : array(
                1 => '国四',
                0 => '国五'
            );

            return view('dealer.ucenter.baojia_add_step1', $view);
        } elseif ($id > 0) {
            $baojia = HgBaojia::getBaojiaInfo($id);
            if (empty($baojia)) {
                die('该报价不存在');
            }
            if ($type == 'edit' && $baojia->bj_step == 99) {
                die('已经生成的报价不允许修改');//线上一定开启，否则报价一旦被修改，原始数据无法获取
            }
            $baojia = $baojia->toArray();
            $view['baojia'] = $baojia;
            $view['baojia']['bj_id'] = $id;
            if ($type == 'view') {//菜单栏状态赋值
                if ($baojia['bj_status'] == 1) {
                    if ($baojia['bj_start_time'] > time()) {
                        $view['flag'] = 'baojia-to-be-effective';//等待生效
                    } else {
                        $view['flag'] = 'baojia-online';//正在报价
                    }
                } elseif ($baojia['bj_status'] == 2) {
                    $view['flag'] = 'baojia-suspensive';//暂停报价
                } else {
                    $view['flag'] = 'baojia-useless';//失效报价
                }

            } else {
                $view['flag'] = 'baojia-new';//新建报价
            }

            if ($step > $baojia['bj_step']) {//实际报价步骤 小于等于参数报价，以报价实际步骤为准
                $url = '/dealer/baojia/edit/' . $id . '/' . $baojia['bj_step'];
                return redirect($url);//线上需要开启 判断跳转
            }

            if ($step == 1) {//修改
                $area = config('area');
                $view['area'] = $area;
                $provinceByFirstLetter = array();
                foreach ($area[0] as $k => $v) {
                    $provinceByFirstLetter[$v['first_letter']][] = $v;
                }
                ksort($provinceByFirstLetter);
                $view['provinceByFirstLetter'] = $provinceByFirstLetter;
                $sale_area = array();


                $view['brandArr'] = explode("&gt;", $baojia['gc_name']);

                //获取车辆的排放标准数据（数组）
                $paifangArray = HgFields::where('name', 'paifang')
                    ->where('model', 'carmodel')
                    ->first();
                $view['paifangList'] = !empty($paifangArray->setting) ? unserialize($paifangArray->setting) : array(
                    1 => '国四',
                    0 => '国五'
                );

                //获取报价的排放标准数值
                $baojiaPaifang = DB::table('hg_baojia_fields')
                    ->where('bj_id', $id)
                    ->where('name', 'paifang')
                    ->first();
                $view['baojiaPaifang'] = !empty($baojiaPaifang) ? unserialize($baojiaPaifang->value) : '0';

                //获取车辆属性
                $car = DB::table('hg_car_info')->where('gc_id', '=', $baojia['brand_id'])->get();
                $car_info = array();
                if (count($car) > 0) {
                    foreach ($car as $k => $v) {
                        $car_info[$v->name] = unserialize($v->value);
                    }
                }
                if (!isset($car_info['seat_num'])) {
                    $car_info['seat_num'] = '';
                }
                $car_info['official_url'] = HgGoodsClass::where('gc_id', $baojia['brand_id'])->value('official_url');
                $car_info['vehicle_model'] = HgGoodsClass::where('gc_id', $baojia['brand_id'])->value('vehicle_model');
                $view['car_info'] = $car_info;
                $dealerAddress = '';
                $dealer_info = DB::table('hg_daili_dealer')
                    ->join('hg_dealer', 'hg_daili_dealer.d_id', '=', 'hg_dealer.d_id')
                    ->join('goods_class', 'hg_daili_dealer.dl_brand_id', '=', 'goods_class.gc_id')
                    ->where('hg_daili_dealer.id', $baojia['daili_dealer_id'])
                    ->select('hg_dealer.*', 'hg_daili_dealer.d_shortname', 'hg_daili_dealer.dl_brand_id',
                        'hg_daili_dealer.id as daili_dealer_id', 'goods_class.gc_name')
                    ->first();
                //$dealer_info = HgDealer::where('d_id',$baojia['dealer_id'])->first();
                if (isset($dealer_info->d_sheng) && isset($dealer_info->d_shi)) {
                    $dealerAddress = @$area[0][$dealer_info->d_sheng]['name'] . @$area[$dealer_info->d_sheng][$dealer_info->d_shi]['name'];
                    $dealerName = $dealer_info->d_name . '(' . $dealer_info->gc_name . ')';
                } else {
                    $dealerAddress = '';
                    $dealerName = '';
                }
                $view['dealerAddress'] = $dealerAddress;

                $view['dealerName'] = $dealerName;
                $myArea = array();
                $myAreaStr = '';
                $baojiaArea = HgBaojiaArea::where('bj_id', $id)->get();
                if (count($baojiaArea) > 0) {
                    $baojiaArea = $baojiaArea->toArray();
                    foreach ($baojiaArea as $k => $v) {
                        $v['area_name'] = @$area[$v['province']][$v['city']]['name'];
                        $myArea[$v['province']][] = $v;
                        $sale_area[] = $v['city'];
                    }
                    foreach ($myArea as $k => $v) {
                        if (count($v) == count($area[$k])) {
                            $myAreaStr .= $area[0][$k]['name'] . ';';
                        } else {
                            $tmpArray = array();
                            foreach ($v as $k1 => $v1) {
                                $tmpArray[] = @$area[$k][$v1['city']]['name'];
                            }
                            $myAreaStr .= @$area[0][$k]['name'] . '(' . implode(',', $tmpArray) . ');';
                        }
                    }

                }
                $view['myAreaStr'] = $myAreaStr;
                $view['sale_area'] = $sale_area;
                if (count($sale_area) > 0) {
                    $gps_city_id = current($sale_area);
                    $gps_city = Area::where('area_id', $gps_city_id)->value('area_name');
                } else {
                    $gps_city = '苏州';
                }
                $view['gps_city'] = $gps_city;

                //print_r($view['gps_city']);exit;
                //编辑 修改 查看
                if ($type == 'view') {
                    return view('dealer.ucenter.baojia_view_step1', $view);
                } else {
                    return view('dealer.ucenter.baojia_edit_step1', $view);
                }
            } elseif ($step == 2) {
                //车辆属性
                $carinfoArray = HgCarInfo::getCarmodelFields($baojia['brand_id']);
                $view['carinfoArray'] = $carinfoArray;

                //赠品列表 不关联车型 全部读取
                $zengpinList = HgZengpin::where('status', 1)->get();
                $zengpinList = count($zengpinList) > 0 ? $zengpinList->toArray() : array();

                $view['zengpinList'] = $zengpinList;
                //print_r($zengpinList);exit;
                //选装件列表
                $xzj = HgXzjDaili::join('goods_class_staple', 'hg_xzj_daili.staple_id', '=',
                    'goods_class_staple.staple_id')
                    ->leftJoin('hg_xzj_list', 'hg_xzj_list.id', '=', 'hg_xzj_daili.xzj_list_id')
                    ->where('hg_xzj_daili.car_brand', $baojia['brand_id'])
                    ->where('hg_xzj_daili.xzj_yc', 1)
                    ->where('hg_xzj_daili.xzj_front', 1)
                    ->where('hg_xzj_daili.dealer_id', $baojia['dealer_id'])
                    ->where('hg_xzj_daili.daili_dealer_id', $baojia['daili_dealer_id'])
                    ->where('goods_class_staple.status', 0)
                    ->where('hg_xzj_daili.member_id', session('user.member_id'))
                    ->select('hg_xzj_list.xzj_title',
                        'hg_xzj_list.xzj_model',
                        'hg_xzj_list.id as xzj_id',
                        'hg_xzj_daili.xzj_cs_serial',
                        'hg_xzj_list.xzj_guide_price',
                        'hg_xzj_list.xzj_max_num'
                    )
                    ->get();
                $view['xzj'] = count($xzj) > 0 ? $xzj->toArray() : array();
                $bjXzj = array();
                $bj_xzj = HgBaojiaXzj::leftJoin('hg_xzj_list', 'hg_baojia_xzj.xzj_id', '=', 'hg_xzj_list.id')
                    ->leftJoin('hg_xzj_daili', 'hg_xzj_daili.id', '=', 'hg_baojia_xzj.m_id')
                    ->where('hg_baojia_xzj.bj_id', $id)
                    ->where('hg_baojia_xzj.is_install', 1)
                    ->select("hg_baojia_xzj.*", "hg_xzj_list.xzj_title", "hg_xzj_list.xzj_model",
                        'hg_xzj_list.xzj_guide_price', 'hg_xzj_daili.xzj_cs_serial')
                    ->get();
                if (count($bj_xzj) > 0) {
                    $bj_xzj = $bj_xzj->toArray();
                    foreach ($bj_xzj as $k => $v) {
                        $bjXzj[$v['xzj_id']] = $v;
                    }
                }
                $view['bj_xzj'] = $bjXzj;
                $baojiaZengpinList = array();
                $baojiaZengpin = HgBaojiaZengpin::join('hg_zengpin', 'hg_baojia_zengpin.zengpin_id', '=',
                    'hg_zengpin.id')
                    ->where('hg_baojia_zengpin.bj_id', $id)
                    ->select('hg_zengpin.title as zp_title', 'hg_baojia_zengpin.id', 'hg_baojia_zengpin.zengpin_id',
                        'hg_baojia_zengpin.is_install', 'hg_baojia_zengpin.num')
                    ->get();
                if (count($baojiaZengpin) > 0) {
                    $baojiaZengpinList = $baojiaZengpin->toArray();
                }
                $view['baojiaZengpinList'] = $baojiaZengpinList;

                if ($baojia['bj_step'] == 2 && $baojia['bj_copy_id'] == 0) {//新增
                    $view['currentyear'] = date('Y');
                    $view['currentmonth'] = date('n');
                    //代理常用车型里面的选装件 选择
                    $dailiXzj = HgXzjDaili::where('car_brand', $baojia['brand_id'])
                        ->where('dealer_id', $baojia['dealer_id'])
                        ->where('member_id', session('user.member_id'))
                        ->get();
                    $xzjDaili = array();
                    if (count($dailiXzj) > 0) {
                        $dailiXzj = $dailiXzj->toArray();
                        foreach ($dailiXzj as $k => $v) {
                            $xzjDaili[$v['xzj_list_id']] = $v;
                        }
                    }
                    $view['dailixzj'] = $xzjDaili;

                    return view('dealer.ucenter.baojia_add_step2', $view);
                } else {
                    if (!empty($baojia['bj_producetime'])) {
                        $produce_date = explode('-', $baojia['bj_producetime']);
                        $view['currentyear'] = $produce_date[0];
                        $view['currentmonth'] = intval($produce_date[1]);
                    } else {
                        $view['currentyear'] = date("Y");
                        $view['currentmonth'] = date("m");
                    }

                    //非现车  内饰存储hg_baojia,字段bj_temp_internal_color中,当报价进入到第六步时，会拆分报价
                    //现车    内饰存储hg_baojia_field,
                    //现车非现车，车身颜色存储在hg_baojia_field
                    $baojiaCarAttr = HgBaojiaField::getCarFieldsByBjid($id);

                    $view['carAttr'] = $baojiaCarAttr;
                    $view['bj_chuchang'] = @explode('-', $baojia['bj_producetime']);
                    if ($baojia['bj_is_xianche'] == 0) {
                        if ($baojia['bj_step'] == 99) {
                            $interColor = $carinfoArray['interior_color'][$baojiaCarAttr['interior_color']];
                        } else {
                            $interColor = '';
                            $tmp = unserialize($baojia['bj_temp_internal_color']);
                            if ($tmp && count($tmp) > 0) {
                                $tmpArr = array();
                                foreach ($tmp as $k => $v) {
                                    $tmpArr[] = $carinfoArray['interior_color'][$v];
                                }
                                $interColor = implode('、', $tmpArr);
                            }
                        }
                        $view['interColor'] = $interColor;
                    } else {//非现车改为现车时，重置车辆内饰值
                        if (!isset($view['carAttr']['interior_color'])) {
                            $tmp = unserialize($baojia['bj_temp_internal_color']);
                            if (count($tmp) > 1) {
                                $view['carAttr']['interior_color'] = 0;
                            } else {
                                $view['carAttr']['interior_color'] = current($tmp);
                            }
                        }
                    }
                    $bj_zengping = HgBaojiaZengpin::getBaojiaZengpin($id);
                    $view['bj_zengpin'] = count($bj_zengping) > 0 ? $bj_zengping->toArray() : array();
                    //编辑 修改 查看
                    if ($type == 'view') {
                        return view('dealer.ucenter.baojia_view_step2', $view);
                    } else {
                        return view('dealer.ucenter.baojia_edit_step2', $view);
                    }
                }
            } elseif ($step == 3) {
                //原厂组装件
                $view['xzj'] = HgDailiDealer::frequentCarXzjList($baojia['dealer_id'], session('user.member_id'),
                    $baojia['brand_id']);

                if ($type == 'view' || ($type == 'edit' && $baojia['bj_step'] > 3) || $baojia['bj_copy_id'] > 0) {//查看或者修改
                    $bj_xzj = array();
                    $bjXzj = HgBaojiaXzj::getBaojiaXzj($id);
                    if (count($bjXzj) > 0) {
                        foreach ($bjXzj as $k => $v) {
                            $bj_xzj[$v->m_id] = $v->toArray();
                        }
                    }
                    $view['bj_xzj'] = $bj_xzj;
                }
                //编辑 修改 查看
                if ($type == 'view') {//查看
                    return view('dealer.ucenter.baojia_view_step3', $view);
                } elseif ($type == 'edit') {
                    if ($baojia['bj_step'] == 3 && $baojia['bj_copy_id'] == 0) {//新增
                        return view('dealer.ucenter.baojia_add_step3', $view);
                    } else {//编辑
                        return view('dealer.ucenter.baojia_edit_step3', $view);
                    }
                }
            } elseif ($step == 4) {
                $seat_num = DB::table('hg_car_info')
                    ->where('gc_id', '=', $baojia['brand_id'])
                    ->where('model', 'carmodel')
                    ->where('name', 'seat_num')
                    ->value('value');
                $seat_num = !empty($seat_num) ? unserialize($seat_num) : 5;
                if ($seat_num >= 6) {
                    $baoxianType = array(0 => 2, 1 => 4);
                } else {
                    $baoxianType = array(0 => 1, 1 => 3);
                }
                $view['baoxianType'] = $baoxianType;
                $view['seat_num'] = $seat_num;
                $baoxianList = array();
                $baoxianListTmp = HgDealerBaoXian::leftJoin('hg_baoxian', 'hg_dealer_baoxian.co_id', '=',
                    'hg_baoxian.bx_id')
                    ->where('m_id', session('user.member_id'))
                    ->where('dealer_id', $baojia['dealer_id'])
                    ->where('daili_dealer_id', $baojia['daili_dealer_id'])
                    ->select('hg_baoxian.*')
                    ->get();
                if (count($baoxianListTmp) > 0) {
                    $baoxianListTmp = $baoxianListTmp->toArray();
                    foreach ($baoxianListTmp as $v) {
                        $baoxianList[$v['bx_id']] = $v;
                    }
                }
                $view['baoxianList'] = $baoxianList;
                if ($type == 'view' || ($type == 'edit' && $baojia['bj_step'] > 4) || $baojia['bj_copy_id'] > 0) {//如果是查看或者修改 获取保险的数据
                    $chesun[0] = HgBaojiaBaoxianChesunPrice::getPrice($id, $baoxianType[0]);
                    $chesun[1] = HgBaojiaBaoxianChesunPrice::getPrice($id, $baoxianType[1]);
                    $view['bx']['chesun'] = $chesun;

                    $daoqiang[0] = HgBaojiaBaoxianDaoqiangPrice::getPrice($id, $baoxianType[0]);
                    $daoqiang[1] = HgBaojiaBaoxianDaoqiangPrice::getPrice($id, $baoxianType[1]);
                    $view['bx']['daoqiang'] = $daoqiang;

                    $sanzhe[0] = HgBaojiaBaoxianSanzhePrice::getAllPrice($id, $baoxianType[0]);
                    $sanzhe[1] = HgBaojiaBaoxianSanzhePrice::getAllPrice($id, $baoxianType[1]);
                    $view['bx']['sanzhe'] = $sanzhe;

                    $renyuan[0] = HgBaojiaBaoxianRenyuanPrice::getAllPriceByGrade($id, $baoxianType[0]);
                    $renyuan[1] = HgBaojiaBaoxianRenyuanPrice::getAllPriceByGrade($id, $baoxianType[1]);
                    $view['bx']['renyuan'] = $renyuan;

                    $boli[0] = HgBaojiaBaoxianBoliPrice::getAllPriceByGrade($id, $baoxianType[0]);
                    $boli[1] = HgBaojiaBaoxianBoliPrice::getAllPriceByGrade($id, $baoxianType[1]);
                    $view['bx']['boli'] = $boli;

                    $huahen[0] = HgBaojiaBaoxianHuahenPrice::getAllPriceByGrade($id, $baoxianType[0]);
                    $huahen[1] = HgBaojiaBaoxianHuahenPrice::getAllPriceByGrade($id, $baoxianType[1]);
                    $view['bx']['huahen'] = $huahen;
                } else {//新建 初始值是经销商代理的常用信息
                    $dealer_info = HgDailiDealer::where('dl_id', session('user.member_id'))
                        ->where('d_id', $baojia['dealer_id'])
                        ->where('id', $baojia['daili_dealer_id'])
                        ->first();
                    $view['dealerInfo'] = !empty($dealer_info) ? $dealer_info->toArray() : array();
                }
                //编辑 修改 查看
                if ($type == 'view') {//查看
                    return view('dealer.ucenter.baojia_view_step4', $view);
                } elseif ($type == 'edit') {
                    if ($baojia['bj_step'] == 4 && $baojia['bj_copy_id'] == 0) {//新增
                        return view('dealer.ucenter.baojia_add_step4', $view);
                    } else {//编辑
                        return view('dealer.ucenter.baojia_edit_step4', $view);
                    }

                }
            } elseif ($step == 5) {
                //其他费用
                $otherPirce = DB::table('hg_baojia_other_price')
                    ->where('bj_id', $id)
                    ->get();
                if (count($otherPirce) > 0) {
                    foreach ($otherPirce as $v) {
                        $view['otherPrice'][] = get_object_vars($v);
                    }
                } else {
                    $view['otherPrice'] = array();
                }
                $allOtherPrice = array();
                $allOtherPriceStr = '';
                $tmpAllPrice = HgFields::getFieldsName('other_price');  //获取杂费信息
                if (count($tmpAllPrice) > 0) {
                    $view['allOtherPrice'] = $tmpAllPrice->toArray();
//    				foreach($tmpAllPrice as $v){
//    					$allOtherPrice[] = $v;
//    					$allOtherPriceStr.="<li data-value='".$v['id']."'><a><span>".$v['title']."</span></a></li>";
//    				}
                }
//    			$view['allOtherPrice'] = $allOtherPrice;
//    			$view['allOtherPriceStr'] = $allOtherPriceStr;
                //编辑 修改 查看
                if ($type == 'view' || ($type == 'edit' && $baojia['bj_step'] > 5) || $baojia['bj_copy_id'] > 0) {//查看或者修改
                    $expandInfo = DB::table("hg_baojia_expand_info")
                        ->where('bj_id', $id)
                        ->first();
                    $expandInfo = !empty($expandInfo) ? get_object_vars($expandInfo) : array();
                    $view['expandInfo'] = $expandInfo;
                } else {//新建
                    $dealer_info = HgStandard::getNorm(session('user.member_id'), $baojia['daili_dealer_id']);
                    $view['dealerInfo'] = !empty($dealer_info) ? $dealer_info->toArray() : array();
                }
                if ($type == 'view') {//查看
                    return view('dealer.ucenter.baojia_view_step5', $view);
                } elseif ($type == 'edit') {
                    if ($baojia['bj_step'] == 5 && $baojia['bj_copy_id'] == 0) {//新增
                        return view('dealer.ucenter.baojia_add_step5', $view);
                    } else {//编辑
                        return view('dealer.ucenter.baojia_edit_step5', $view);
                    }

                }
            } elseif ($step == 6) {
                //获取节能补贴值
                $jnbt = DB::table('hg_car_info')->where('gc_id', '=', $baojia['brand_id'])->where('name',
                    'butie')->value('value');
                $jnbt = !empty($jnbt) ? unserialize($jnbt) : 0;
                $view['jnbt'] = $jnbt;
                //编辑 修改 查看
                if ($type == 'view' || ($type == 'edit' && $baojia['bj_step'] > 6) || $baojia['bj_copy_id'] > 0) {//查看或者修改
                    $expandInfo = DB::table("hg_baojia_expand_info")
                        ->where('bj_id', $id)
                        ->first();
                    $expandInfo = !empty($expandInfo) ? get_object_vars($expandInfo) : array();
                    $view['expandInfo'] = $expandInfo;
                } else {//新建
                    $dealer_info = HgDailiDealer::leftJoin('hg_dealer_standard', 'hg_daili_dealer.id', '=',
                        'hg_dealer_standard.daili_id')
                        ->where('dl_id', session('user.member_id'))
                        ->where('d_id', $baojia['dealer_id'])
                        ->where('hg_daili_dealer.id', $baojia['daili_dealer_id'])
                        ->first();
                    $view['dealerInfo'] = !empty($dealer_info) ? $dealer_info->toArray() : array();
                }
                $suicheInfo = array(
                    '随车工具' => array(),
                    '文件资料' => array()
                );
                $suiche1 = HgAnnex::where('c_id', $baojia['brand_id'])->where('public', 0);
                $suiche = HgAnnex::where('public', 1)->union($suiche1)->get();
                if (count($suiche) > 0) {
                    foreach ($suiche as $v) {
                        $suicheInfo[$v->type][] = $v->title;
                    }
                }
                $view['suicheInfo'] = $suicheInfo;

                if ($type == 'view') {//查看
                    return view('dealer.ucenter.baojia_view_step6', $view);
                } elseif ($type == 'edit') {
                    if ($baojia['bj_step'] == 6 && $baojia['bj_copy_id'] == 0) {//新增
                        return view('dealer.ucenter.baojia_add_step6', $view);
                    } else {//编辑
                        return view('dealer.ucenter.baojia_edit_step6', $view);
                    }

                }
            }

        }
    }

    public function postBaojia($type, $id, $step)
    {
        if ($id == 0) {
            if ($step == 1) {//新增报价 数据整理
                $data = array(
                    'dealer_name'            => $this->request->input('dealer_name'),
                    'dealer_id'              => $this->request->input('dealer_id'),
                    'daili_dealer_id'        => $this->request->input('daili_dealer_id'),
                    'brand_id'               => $this->request->input('car-chexing-id'),
                    'car_staple_id'          => $this->request->input('car-staple-id'),
                    'bj_is_xianche'          => $this->request->input('bj_is_xianche'),
                    'bj_dealer_internal_id'  => $this->request->input('bj_dealer_internal_id'),
                    'series'                 => $this->request->input('hfbrand-chexi'),
                    'gc_name'                => $this->request->input('hfbrand') . ' &gt;' . $this->request->input('hfbrand-chexi') . ' &gt;' . $this->request->input('hfbrand-chexing'),
                    'area'                   => $this->request->input('sale_area'),
                    'bj_lckp_price'          => $this->request->input('bj_lckp_price'),
                    'bj_doposit_price'       => $this->request->input('bj_doposit_price'),
                    'bj_agent_service_price' => $this->request->input('bj_agent_service_price'),
                    'bj_earnest_price'       => config("money.earnest"),
                    'bj_car_guarantee'       => 800,//需要系统计算
                    'bj_price'               => $this->request->input('bj_lckp_price') + $this->request->input('bj_agent_service_price'),
                    'member_id'              => session('user.member_id'),
                    'paifang'                => !empty($this->request->input('paifang')) ? $this->request->input('paifang') : 0,
                );
                return HgBaojia::editBaojia(0, 1, $data);
            }
        } elseif ($id >= 0) {
            $baojiaInfo = HgBaojia::getBaojiaInfo($id);
            if (!$baojiaInfo) {
                return array('error_code' => 1, 'message' => '报价不存在');
            }
            $baojiaInfo = $baojiaInfo->toArray();
            if ($step == 1) {//编辑保存第一步  车型价格
                $data = array(
                    'bj_is_xianche'          => $this->request->input('bj_is_xianche'),
                    'bj_dealer_internal_id'  => $this->request->input('bj_dealer_internal_id'),
                    'area'                   => $this->request->input('sale_area'),
                    'bj_lckp_price'          => $this->request->input('bj_lckp_price'),
                    'bj_doposit_price'       => $this->request->input('bj_doposit_price'),
                    'bj_agent_service_price' => $this->request->input('bj_agent_service_price'),
                    'bj_earnest_price'       => config("money.earnest"),
                    'bj_car_guarantee'       => 800,//需要系统计算
                    'bj_price'               => $this->request->input('bj_lckp_price') + $this->request->input('bj_agent_service_price'),
                    'member_id'              => session('user.member_id'),
                    'paifang'                => !empty($this->request->input('paifang')) ? $this->request->input('paifang') : 0,
                );
                return HgBaojia::editBaojia($id, 1, $data);
            } elseif ($step == 2) {//编辑保存第二步  车况说明
                if ($baojiaInfo['bj_is_xianche'] == 1) {
                    $year = intval(rtrim($this->request->input('produce-year'), '年'));
                    $month = intval(rtrim($this->request->input('produce-month'), '月'));
                    $month = $month < 10 ? "0" . $month : $month;
                    $data = array(
                        'bj_producetime' => $year . '-' . $month,
                        'bj_licheng'     => $this->request->input('bj_licheng'),
                        'body_color'     => $this->request->input('body_color'),
                        'interior_color' => $this->request->input('interior_color'),
                        'xzj'            => $this->request->input('xzj'),
                        'xzj_num'        => $this->request->input('xzj_num'),
                    );
                } else {
                    $data = array(
                        'bj_jc_period'   => $this->request->input('bj_jc_period'),
                        'body_color'     => $this->request->input('body_color'),
                        'interior_color' => $this->request->input('interior_color'),
                        'xzj'            => array(),
                    );
                }
                return HgBaojia::editBaojia($id, 2, $data);

            } elseif ($step == 3) {//编辑保存第三步  选装精品
                $data = array(
                    'xzj'           => $this->request->input('xzj'),
                    'bj_xzj_zhekou' => $this->request->input('bj_xzj_zhekou'),
                );
                return HgBaojia::editBaojia($id, 3, $data);
            } elseif ($step == 4) {//编辑保存第四步  首年保险
                $data = array(
                    'bj_baoxian'       => $this->request->input('bj_baoxian'),
                    'baoxian_discount' => 100,//保险折扣
                    'bj_bx_select'     => $this->request->input('bj_bx_select'),
                    'chesun'           => $this->request->input('chesun'),
                    'daoqiang'         => $this->request->input('daoqiang'),
                    'sanzhe'           => $this->request->input('sanzhe'),
                    'renyuan'          => $this->request->input('renyuan'),
                    'huahen'           => $this->request->input('huahen'),
                    'boli'             => $this->request->input('boli'),
                    'bjmp'             => $this->request->input('bjmp'),
                );
                return HgBaojia::editBaojia($id, 4, $data);
            } elseif ($step == 5) {//编辑保存第五步  收费标准
                $data = array(
                    'bj_shangpai'                     => $this->request->input('bj_shangpai'),
                    'bj_shangpai_price'               => $this->request->input('bj_shangpai_price'),
                    'bj_license_plate_break_contract' => $this->request->input('bj_license_plate_break_contract'),
                    'bj_linpai'                       => $this->request->input('bj_linpai'),
                    'bj_linpai_price'                 => $this->request->input('bj_linpai_price'),
                    'xyk_status'                      => $this->request->input('xyk_status'),
                    'xyk_number'                      => $this->request->input('xyk_number'),
                    'xyk_per_num'                     => $this->request->input('xyk_per_num'),
                    'xyk_yuan_num'                    => $this->request->input('xyk_yuan_num'),
                    'jjk_status'                      => $this->request->input('jjk_status'),
                    'jjk_number'                      => $this->request->input('jjk_number'),
                    'jjk_per_num'                     => $this->request->input('jjk_per_num'),
                    'jjk_yuan_num'                    => $this->request->input('jjk_yuan_num'),
                );
                //print_r($data);exit;
                return HgBaojia::editBaojia($id, 5, $data);
            } elseif ($step == 6) {//编辑保存第六步 其他事项
                $data = array(
                    'bt_status'     => $this->request->input('bt_status'),
                    'bj_zf_butie'   => !empty($this->request->input('bj_zf_butie')) ? 1 : 0,
                    'bj_cj_butie'   => !empty($this->request->input('bj_cj_butie')) ? 1 : 0,
                    'bt_work_day'   => $this->request->input('bt_work_day'),
                    'bt_work_month' => $this->request->input('bt_work_month'),
                    'bj_start_time' => strtotime($this->request->input('start_time_1') . ' ' . $this->request->input('start_time_2') . ':00'),
                    'bj_end_time'   => strtotime($this->request->input('end_time_1') . ' ' . $this->request->input('end_time_2') . ':00'),
                    'bj_num'        => $this->request->input('bj_num'),
                );
                if ($this->request->input('fulltime') == 1) {
                    $data['bj_start_time'] = time();
                    $data['bj_end_time'] = strtotime('2099-12-31');
                }
                return HgBaojia::editBaojia($id, 6, $data);

            }
        }

    }

    /**
     * 获取报价
     * @param  $type 报价列表类型
     *            unfinshed(新建未完成)
     *            to-be-effective（等待生效）
     *            online（在线报价）
     *            suspensive（暂停的报价）
     *            uesless（失效报价）
     */
    public function getBaojiaList($type)
    {

        $member_id = session('user.member_id');
        $view['flag'] = 'baojia-unfinished';
        $bj_brand = $this->request->input('brand');
        $cars = $this->request->input('cars');
        $standard = $this->request->input('standard');
        $bj_dealer = $this->request->input('dealer');
        //搜索后显示的价格
        $pid = $this->request->input('pid');
        if (!empty($pid)) {
            $view['price'] = HgCarInfo::getCarmodelFields($pid);
        }

        //读取未完成报价数据
        if ($type == 'unfinished') {
            $brand_id = $this->request->input('brand_id');
            $id = 1;        //id为1正常状态
            $view['lists'] = HgBaojia::getBaojiaCountByType($member_id);
            $quest = HgBaojia::getBrand($member_id, $id);
            $view['brands'] = $quest->where('bj_step', '<>', '99')->get();
            $result = HgBaojia::getDealer($member_id, $id);
            $view['dealers'] = $result->where('bj_step', '<>', '99')->get();

            if ($this->request->ajax()) {
                $quest = DB::table('hg_baojia')->where('m_id', $member_id)->where('bj_step', '<>',
                    '99')->where('bj_status', '1');
                if (!empty($bj_brand)) {
                    $quest->where('gc_name', 'like', $bj_brand . '%');
                }
                if (!empty($cars)) {
                    $quest->where('gc_series', $cars);
                }
                if (!empty($standard)) {
                    $quest->where('gc_name', 'like', '%' . $standard . '%');
                }
                if (!empty($bj_dealer)) {
                    $quest->where('dealer_name', 'like', $bj_dealer . '%');
                }
                $view['lists'] = $quest->select('bj_update_time', 'dealer_name', 'gc_name', 'bj_body_color', 'bj_id',
                    'bj_step')
                    ->orderBy('bj_update_time', 'desc')
                    ->paginate(10);
                $view['type'] = 'search';
                return Response::json(View('dealer.ucenter.baojia_unfinished_table', $view)->render());
            }

            return view('dealer.ucenter.baojia_unfinished', $view);
        }


        //等待报价
        if ($type == 'effective') {
            $view['flag'] = 'baojia-to-be-effective';
            $view['start_time'] = strtotime($this->request->input('start_time'));
            $view['end_time'] = strtotime($this->request->input('end_time')."+1day");
            if ($this->request->ajax()) {
                $quest = DB::table('hg_baojia')
                    ->where('m_id', $member_id)
                    ->where('bj_step', '99')
                    ->where('bj_status', '1')
                    ->where('bj_start_time', '>', time());
                if (!empty($bj_brand)) {
                    $quest->where('gc_name', 'like', $bj_brand . '%');
                }
                if (!empty($cars)) {
                    $quest->where('gc_series', $cars);
                }
                if (!empty($standard)) {
                    $quest->where('gc_name', 'like', '%' . $standard . '%');
                }
                if (!empty($bj_dealer)) {
                    $quest->where('dealer_name', 'like', $bj_dealer . '%');
                }
                if (!empty($view['start_time'])) {
                    $quest->where('bj_start_time', '>', $view['start_time']);
                }
                if (!empty($view['end_time'])) {
                    $quest->where('bj_end_time', '<', $view['end_time']);
                }
                $view['lists'] = $quest->paginate(10);
                $view['type'] = 'search';
                return Response::json(View('dealer.ucenter.baojia_effective_table', $view)->render());
            }

            $id = 1;        //id为1正常状态
            $view['lists'] = HgBaojia::getWaiting($member_id);
            $quest = HgBaojia::getBrand($member_id, $id);
            $view['brands'] = $quest->where('bj_step', '99')
                ->where('bj_start_time', '>', time())
                ->get();

            $result = HgBaojia::getDealer($member_id, $id);
            $view['dealers'] = $result->where('bj_step', '99')
                ->where('bj_start_time', '>', time())
                ->get();

            return view('dealer.ucenter.baojia_effective', $view);
        }
        if ($type == 'online') {
            $view['flag'] = 'baojia-online';
            $view['xianche'] = $this->request->input('xianche');
            $id = 1;         //id为1正常状态
            $view['lists'] = HgBaojia::getOnline($member_id);
            $result = HgBaojia::getDealer($member_id, $id);
            $view['dealers'] = $result->where('bj_step', '99')
                ->where('bj_start_time', '<=', time())
                ->where('bj_end_time', '>', time())
                ->get();

            $quest = HgBaojia::getBrand($member_id, $id);
            $view['brands'] = $quest->where('bj_step', '99')
                ->where('bj_start_time', '<=', time())
                ->where('bj_end_time', '>', time())
                ->get();

            if ($this->request->ajax()) {
                $quest = DB::table('hg_baojia')
                    ->leftjoin('hg_baojia_price', 'hg_baojia.bj_id', '=', 'hg_baojia_price.bj_id')
                    ->where('m_id', $member_id)
                    ->where('bj_step', '99')
                    ->where('bj_status', '1')
                    ->where('bj_start_time', '<=', time())
                    ->where('bj_end_time', '>', time());
                if (!empty($bj_brand)) {
                    $quest->where('gc_name', 'like', $bj_brand . '%');
                }
                if (!empty($cars)) {
                    $quest->where('gc_series', $cars);
                }
                if (!empty($standard)) {
                    $quest->where('gc_name', 'like', '%' . $standard . '%');
                }
                if (!empty($bj_dealer)) {
                    $quest->where('dealer_name', 'like', $bj_dealer . '%');
                }
                if (isset($view['xianche']) && $view['xianche'] != '') {
                    $quest->where('bj_is_xianche', $view['xianche']);
                }
                if ($this->request->has('state')) {
                    $state = $this->request->input('state');
                    $bj_end_time = '4102329600';
                    if ($state == 1) {
                        $quest->where('bj_end_time', $bj_end_time);
                    } else {
                        $quest->where('bj_end_time', '<>', $bj_end_time);
                    }

                }
                $view['lists'] = $quest->select('hg_baojia.bj_serial', 'gc_name', 'bj_reason', 'hg_baojia.bj_id',
                    'bj_end_time', 'hg_baojia_price.bj_lckp_price')
                    ->paginate(10);
                $view['type'] = 'search';
                return Response::json(View('dealer.ucenter.baojia_online_table', $view)->render());
            }

            return view('dealer.ucenter.baojia_online', $view);
        }

        if ($type == 'suspensive') {
            $view['flag'] = 'baojia-suspensive';
            $id = 2;         //id为2暂停状态
            $view['xianche'] = $this->request->input('xianche');
            $view['state'] = $this->request->input('state');
            $view['lists'] = HgBaojia::getSuspensive($member_id);
            $result = HgBaojia::getDealer($member_id, $id);
            $view['dealers'] = $result->where('bj_step', '99')
               // ->where('bj_start_time', '<=', time())
                ->where('bj_end_time', '>', time())
                ->get();

            $quest = HgBaojia::getBrand($member_id, $id);
            $view['brands'] = $quest->where('bj_step', '99')
               // ->where('bj_start_time', '<=', time())
                ->where('bj_end_time', '>', time())
                ->get();

            if ($this->request->ajax()) {
                $quest = DB::table('hg_baojia')
                    ->leftjoin('hg_baojia_price', 'hg_baojia.bj_id', '=', 'hg_baojia_price.bj_id')
                    ->where('m_id', $member_id)
                    ->where('bj_step', '99')
                    ->where('bj_status', '2')
                  //  ->where('bj_start_time', '<=', time())
                    ->where('bj_end_time', '>', time());
                if (!empty($bj_brand)) {
                    $quest->where('gc_name', 'like', $bj_brand . '%');
                }
                if (!empty($cars)) {
                    $quest->where('gc_series', $cars);
                }
                if (!empty($standard)) {
                    $quest->where('gc_name', 'like', '%' . $standard . '%');
                }
                if (!empty($bj_dealer)) {
                    $quest->where('dealer_name', 'like', $bj_dealer . '%');
                }
                if (isset($view['xianche']) && $view['xianche'] != '') {
                    $quest->where('bj_is_xianche', '=', $view['xianche']);
                }
                if (!empty($view['state'])) {

                }
                $view['lists'] = $quest->select('hg_baojia.bj_serial', 'gc_name', 'bj_reason', 'hg_baojia.bj_id',
                    'bj_end_time', 'hg_baojia_price.bj_lckp_price')
                    ->paginate(10);
                $view['type'] = 'search';
                return Response::json(View('dealer.ucenter.baojia_suspensive_table', $view)->render());
            }

            return view('dealer.ucenter.baojia_suspensive', $view);
        }

        //失效报价
        if ($type == 'useless') {
            $dl_id = session('user.member_id');//代理ID
            $dealerList = HgDailiDealer::getDealerByDaili($dl_id, 2);
            $view['dealerList'] = $dealerList;

            $seachParams = [];
            $start = strtotime("-1 years");
            $end = time();
            if ($this->request->has('brand_id')) {
                $seachParams['brand_id'] = $this->request->input('brand_id');
            }
            if ($this->request->has('car_body_color')) {
                $seachParams['bj_body_color'] = $this->request->input('car_body_color');
            }
            if ($this->request->has('timeout_reason')) {
                $seachParams['bj_reason'] = $this->request->input('timeout_reason');
            }
            if ($this->request->has('time_circle')) {
                $time_circle = $this->request->has('time_circle');
                switch ($time_circle) {
                    case '一周内':
                        $start = strtotime("-7 days");
                        break;
                    case '一个月内':
                        $start = strtotime("-1 months");
                        break;
                    case '三个月内':
                        $start = strtotime("-3 months");
                        break;
                    case '三个月外':
                        $end = strtotime("-3 months");
                        break;
                }
            }

            $view['list'] = HgBaojia::getUselessBaojiaList($seachParams, $member_id, $start, $end);
            $view['flag'] = 'baojia-useless';

            if ($this->request->ajax()) {
                return Response::json(View('dealer.ucenter.baojia_useless_table', $view)->render());
            }

            return view('dealer.ucenter.baojia_useless', $view);
        }
    }


    /**
     * 获取数据
     * @param unknown $type
     */
    public function ajaxGetData($type)
    {
        $member_id = session('user.member_id');
        if ($type == 'get-brand-by-dealer') {//根据经销商 获取对应的品牌列表
            $dealer_id = $this->request->input('dealer_id');
        } elseif ($type == 'get-chexi-by-dealer') {//根据经销商 获取对应品牌的车型列表
            $brand_id = $this->request->input('brand_id');
            $dealer_id = $this->request->input('dealer_id');
            $daili_dealer_id = $this->request->input('daili_dealer_id');
            $goods_class = array();
            $tmp = DB::table('goods_class_staple')
                ->join('goods_class', 'goods_class_staple.gc_id_2', '=', 'goods_class.gc_id')
                ->select('goods_class.gc_id', 'goods_class.gc_name')
                ->where('goods_class_staple.gc_id_1', '=', $brand_id)
                ->where('goods_class_staple.status', '=', '0')
                ->where('goods_class_staple.dealer_id', $dealer_id)
                ->where('goods_class_staple.daili_dealer_id', $daili_dealer_id)
                ->where('goods_class_staple.member_id', session('user.member_id'))
                ->groupBy('goods_class_staple.gc_id_2')
                ->get();
            foreach ($tmp as $k => $v) {
                $goods_class[] = $v;
            }
            return json_encode($goods_class);
        } elseif ($type == 'get-chexing-by-dealer') {//根据经销商 获取对应品牌车系的车型列表
            $brand_id = $this->request->input('brand_id');
            $dealer_id = $this->request->input('dealer_id');
            $daili_dealer_id = $this->request->input('daili_dealer_id');
            $goods_class = array();
            $tmp = DB::table('goods_class_staple')
                ->join('goods_class', 'goods_class_staple.gc_id_3', '=', 'goods_class.gc_id')
                ->select('goods_class.gc_id', 'goods_class.gc_name', 'goods_class_staple.staple_id')
                ->where('goods_class_staple.gc_id_2', '=', $brand_id)
                ->where('goods_class_staple.status', '=', 0)
                ->where('goods_class_staple.dealer_id', $dealer_id)
                ->where('goods_class_staple.daili_dealer_id', $daili_dealer_id)
                ->where('goods_class_staple.member_id', session('user.member_id'))
                ->groupBy('goods_class_staple.gc_id_3')
                ->get();
            foreach ($tmp as $k => $v) {
                $goods_class[] = $v;
            }
            return json_encode($goods_class);
        } elseif ($type == 'get-carinfo-by-brand-id') {//获取车型的 属性
            $brand_id = $this->request->input('brand_id');
            $row = DB::table('goods_class')->where('gc_id', '=', $brand_id)->first();
            $car_info = array();
            if (!empty($row)) {
                $row = get_object_vars($row);
                $car = DB::table('hg_car_info')
                    ->where('gc_id', '=', $brand_id)
                    ->whereIn('name', array('zhidaojia', 'seat_num'))
                    ->get();
                $car_info = $row;
                if (count($car) > 0) {
                    foreach ($car as $k => $v) {
                        $car_info[$v->name] = unserialize($v->value);
                    }
                }
            }
            return json_encode($car_info);
        } elseif ($type == 'relation') {
            $types = $this->request->input('bj_type');
            $brand_id = trim($this->request->input('brand_id'));
            $types == 'suspensive' ? $id = 2 : $id = 1;
            if ($types == 'unfinished') {
                $quest = HgBaojia::getCareries($member_id, $brand_id, $id);
                $data = $quest->where('bj_step', '<>', '99')->get();
            }

            if ($types == 'effective') {
                $quest = HgBaojia::getCareries($member_id, $brand_id, $id);
                $data = $quest->where('bj_step', '99')
                    ->where('bj_start_time', '>', time())
                    ->get();
            }

            if ($types == 'online' || $types == 'suspensive') {
                $quest = HgBaojia::getCareries($member_id, $brand_id, $id);
                $data = $quest->where('bj_step', '99')
                    ->where('bj_start_time', '<=', time())
                    ->where('bj_end_time', '>', time())
                    ->get();
            }


            return json_encode($data);

        } elseif ($type == 'specifica') {
            $types = $this->request->input('bj_type');
            $parent_id = $this->request->input('parent_id');
            if ($types == 'unfinished') {
                $result = HgBaojia::where('m_id', $member_id)
                    ->where('bj_step', '<>', '99')
                    ->where('bj_status', '1')
                    ->select('brand_id')
                    ->get();
                $data = DB::table('goods_class')
                    ->where('gc_parent_id', $parent_id)
                    ->whereIn('gc_id', $result)
                    ->select('gc_name', 'gc_id')
                    ->get();
            }

            if ($types == 'effective') {
                $result = HgBaojia::where('m_id', $member_id)
                    ->where('bj_step', '99')
                    ->where('bj_status', '1')
                    ->where('bj_start_time', '>', time())
                    ->select('brand_id')
                    ->get();
                $data = DB::table('goods_class')
                    ->where('gc_parent_id', $parent_id)
                    ->whereIn('gc_id', $result)
                    ->select('gc_name', 'gc_id')
                    ->get();
            }

            if ($types == 'online' || $types == 'suspensive') {
                $types == 'suspensive' ? $id = 2 : $id = 1;
                $result = HgBaojia::where('m_id', $member_id)
                    ->where('bj_step', '99')
                    ->where('bj_status', $id)
                    ->where('bj_start_time', '<=', time())
                    ->where('bj_end_time', '>', time())
                    ->select('brand_id')
                    ->get();
                $data = DB::table('goods_class')
                    ->where('gc_parent_id', $parent_id)
                    ->whereIn('gc_id', $result)
                    ->select('gc_name', 'gc_id')
                    ->get();
            }


            return json_encode($data);

        } elseif ($type == 'get-internal-id') {//获取报价的内部编号
            $brand_id = $this->request->input('brand_id');
            $dealer_id = $this->request->input('dealer_id');
            $internal_id = $this->request->input('$internal_id');
            $member_id = session('user.member_id');
            $row = HgBaojia::where('m_id', $member_id)
                ->where('dealer_id', $dealer_id)
                ->where('brand_id', $brand_id)
                ->where('bj_status', 1)
                ->where('bj_dealer_internal_id', '!=', '')
                ->select('bj_id', 'bj_dealer_internal_id')
                ->get();
            $value = array();
            if (count($row) > 0) {
                foreach ($row as $v) {
                    $tmp = array('name' => $v['bj_dealer_internal_id'], 'id' => $v['bj_id']);
                    $value[] = $tmp;
                }
            }
            echo json_encode($value);
        } elseif ($type == 'get-car-color') {
            $dealer_id = $this->request->input('dealer_id');
            $brand_id = $this->request->input('brand_id');
            $colors = HgBaojia::getCarColorByCarSeries($dealer_id, $brand_id);
            return response()->json($colors);
        } else {
        }
    }

    /**
     * 异步提交数据
     * @param  $type
     * @param
     * @return multitype:number string
     */
    public function ajaxSubmit($type)
    {
        $member_id = session('user.member_id');
        $bj_id = $this->request->input('bj_id');
        if ($type == 'import-insurance') {//保险导入功能

            exit;
        } elseif ($type == 'edit-zengpin') {//删除赠品 新增 或者修改
            $bj_id = $this->request->input('bj_id');
            $id = $this->request->input('id');
            $zengpin_id = $this->request->input('zengpin_id');
            $num = $this->request->input('num');
            $is_install = empty($this->request->input('is_install')) ? '0' : $this->request->input('is_install');

            //检查重复
            if ($id == 0) {
                $c = HgBaojiaZengpin::where('bj_id', $bj_id)
                    ->where('zengpin_id', $zengpin_id)
                    ->count();
            } else {
                $c = HgBaojiaZengpin::where('bj_id', $bj_id)
                    ->where('zengpin_id', $zengpin_id)
                    ->where('id', '!=', $id)
                    ->count();
            }
            if ($c >= 1) {
                return json_encode(array('error_code' => 1, 'message' => '您添加的项目已经存在，不能重复~'));
            } else {
                $data = array(
                    'bj_id'      => $bj_id,
                    'zengpin_id' => $zengpin_id,
                    'num'        => $num,
                    'is_install' => $is_install,
                );
                $orin_id = $id;
                if ($id == 0) {
                    $e = HgBaojiaZengpin::insertGetId($data);
                } else {
                    $e = HgBaojiaZengpin::where('id', $id)->update($data);
                }
                if ($e === false) {
                    return json_encode(array('error_code' => 1, 'message' => '抱歉！提交失败，请重新提交~'));
                } else {
                    $data = HgBaojiaZengpin::join('hg_zengpin', 'hg_baojia_zengpin.zengpin_id', '=', 'hg_zengpin.id')
                        ->where('hg_baojia_zengpin.bj_id', $bj_id)
                        ->select('hg_zengpin.title as zp_title', 'hg_baojia_zengpin.id', 'hg_baojia_zengpin.zengpin_id',
                            'hg_baojia_zengpin.is_install', 'hg_baojia_zengpin.num')
                        ->get();
                    $data = count($data) > 0 ? $data->toArray() : array();
                    if ($orin_id == 0) {
                        $message = '您已成功提交工单！工单内容经平台审核通过后方可以使用。';
                    } else {
                        $message = '恭喜，修改成功！';
                    }
                    return json_encode(array('error_code' => 0, 'message' => $message, 'data' => $data));
                }

            }

        } elseif ($type == 'delete-zengpin') {//删除赠品
            $id = $this->request->input('id');
            $bj_id = $this->request->input('bj_id');
            $e = HgBaojiaZengpin::where('id', $id)->where('bj_id', $bj_id)->delete();
            if ($e === false) {
                return json_encode(array('error_code' => 1, 'message' => '抱歉！删除失败，请重新再试~'));
            } else {
                return json_encode(array('error_code' => 0, 'message' => '赠品删除成功'));
            }
        } elseif ($type == 'apply-new-zengpin') {//申请新增 赠品
            $title = $this->request->input('title');
            //去重检查
            $c = HgZengpin::where('title', $title)->count();

            $e = DB::table('hg_dealer_project_get')->insertGetId(array(
                'project_name' => $title,
                'type'         => 2,
                'dl_id'        => session('user.member_id')
            ));
            if ($e === false) {
                return json_encode(array('error_code' => 1, 'message' => '抱歉！提交失败，请重新提交~'));
            } else {
                return json_encode(array('error_code' => 0, 'message' => '您已成功提交工单！工单内容经平台审核通过后方可以使用。'));
            }

        } elseif ($type == 'edit-other-price') {//新增 编辑 其他费用
            $bj_id = $this->request->input('bj_id');
            $id = $this->request->input('id');
            $other_id = $this->request->input('other_id');
            $other_name = $this->request->input('other_name');
            $other_price = $this->request->input('other_price');
            if ($id == 0) {//判重
                $check = DB::table('hg_baojia_other_price')
                    ->where('bj_id', $bj_id)
                    ->where('other_id', $other_id)
                    ->count();
            } else {
                $check = DB::table('hg_baojia_other_price')
                    ->where('bj_id', $bj_id)
                    ->where('other_id', $other_id)
                    ->where('id', '!=', $id)
                    ->count();
            }
            $data = array(
                'bj_id'       => $bj_id,
                'other_id'    => $other_id,
                'other_name'  => $other_name,
                'other_price' => $other_price,
            );
            if ($check >= 1) {
                return json_encode(array('error_code' => 1, 'message' => '您添加的项目已经存在，不能重复~'));
            }
            if ($id == 0) {
                $e = DB::table('hg_baojia_other_price')->insertGetId($data);
                $id = $e;
            } else {
                $e = DB::table('hg_baojia_other_price')
                    ->where('bj_id', $bj_id)
                    ->where('id', $id)
                    ->update($data);
            }
            if ($e === false) {
                return json_encode(array('error_code' => 1, 'message' => '抱歉！修改失败，请重新尝试~'));
            } else {
                return json_encode(array('error_code' => 0, 'message' => '恭喜，修改成功！', 'id' => $id));
            }

        } elseif ($type == 'delete-other-price') {//删除其他费用
            $id = $this->request->input('id');
            $bj_id = $this->request->input('bj_id');
            $e = DB::table('hg_baojia_other_price')
                ->where('bj_id', $bj_id)
                ->where('id', $id)
                ->delete();
            if ($e === false) {
                return json_encode(array('error_code' => 1, 'message' => '抱歉！删除失败，请重新再试~'));
            } else {
                return json_encode(array('error_code' => 0, 'message' => '其他费用提交成功'));
            }
        } elseif ($type == 'apply-new-other-price') {//新其他费用 提交等待后台添加
            $title = $this->request->input('title');

            $e = DB::table('hg_dealer_project_get')
                ->insertGetId(array('project_name' => $title, 'type' => 3, 'dl_id' => session('user.member_id')));
            if ($e === false) {
                return json_encode(array('error_code' => 1, 'message' => '抱歉！提交失败，请重新提交~'));
            } else {
                return json_encode(array('error_code' => 0, 'message' => '您已成功提交工单！工单内容经平台审核通过后方可以使用。'));
            }

        } elseif ($type == 'delete-baojia') {
            $result = HgBaojia::where(['m_id' => $member_id, 'bj_id' => $bj_id])->update(['bj_status' => 3]);
            if ($result == 1) {
                return array('error_code' => 0);    //js 中0为成功
            } else {
                return array('error_code' => 1, 'message' => '删除失败');
            }
        } elseif ($type == 'suspensive-baojia') {
            $result = HgBaojia::where(['m_id' => $member_id, 'bj_id' => $bj_id])->update([
                'bj_status' => 0,
                'bj_reason' => '主动终止',
                'bj_is_public' => 0
            ]);
            if ($result == 1) {
                return array('error_code' => 0);    //js 中0为成功
            } else {
                return array('error_code' => 1, 'message' => '终止失败');
            }
        } elseif ($type == 'stop-baojia') {
            $result = HgBaojia::where(['m_id' => $member_id, 'bj_id' => $bj_id])->update([
                'bj_status' => 2,
                'bj_reason' => '主动暂停'
            ]);
            if ($result == 1) {
                return array('error_code' => 0);    //js 中0为成功
            } else {
                return array('error_code' => 1, 'message' => '暂停失败');
            }
        } elseif ($type == 'renew-baojia') {
            $data = HgBaojia::where(['m_id' => $member_id, 'bj_id' => $bj_id])->first();
            if ($data['bj_reason'] == '主动暂停') {
                $result = HgBaojia::where(['m_id' => $member_id, 'bj_id' => $bj_id])
                    ->update(['bj_status' => 1]);
                if ($result == 1) {
                    return array('error_code' => 0);    //js 中0为成功
                } else {
                    return array('error_code' => 1, 'message' => '恢复失败');
                }
            } else {
                return array('error_code' => 1, 'message' => '此报价不符合条件');
            }
        } elseif ($type == 'find-price') {
            $price = HgCarInfo::getCarmodelFields($bj_id);
            return json_encode($price);
        } elseif ($type == 'shelves-all-baojia') {
            $conditions = ['bj_reason' => '主动暂停', 'bj_status' => '2'];
            $status = ['bj_status' => '1'];
            $result = HgBaojia::allOperation($member_id, $conditions, $status);
            if ($result) {
                return array('error_code' => 0);    //js 中0为成功
            } else {
                return array('error_code' => 1, 'message' => '操作失败');
            }
        } elseif ($type == 'ceaseves-all-baojia') {
            //一键暂停所有的报价
            $conditions = ['bj_status' => '1'];
            $status = ['bj_status' => '2', 'bj_reason' => '主动暂停'];
            $result = HgBaojia::allOperation($member_id, $conditions, $status);
            if ($result) {
                return array('error_code' => 0);    //js 中0为成功
            } else {
                return array('error_code' => 1, 'message' => '操作失败');
            }
        } elseif ($type == 'copy-baojia') {
            $ret = HgBaojia::copyBaojia($bj_id, $member_id);
            return response()->json($ret);
        } else {
            $e['error_code'] = 1;
            $e['message'] = '操作失败';
        }

        if ($e['error_code'] == 1) {
            return array('error_code' => 1, 'message' => $e['message']);
        } else {
            return array('error_code' => 0, 'message' => $e['message']);
        }
    }
}
