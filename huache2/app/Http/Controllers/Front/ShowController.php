<?php
namespace App\Http\Controllers\Front;

/**
 * 详情页面
 *
 */

use App\Http\Controllers\Controller;
use App\Com\Hwache\Baojia\Baojia;
use App\Models\HcVehicleToolsFiles;
use App\Models\HgCarInfo;
use App\Models\HgBaojiaField;
use App\Models\HgFields;
use App\Models\HgGoodsClass;
use App\Models\HgBaojiaXzj;
use App\Models\HgBaojiaZengpin;
use App\Models\Area;
use App\Models\HgAnnex;
use DB;
use Session;
use Request;
use App\Models\HgTellUs;
use App\Models\HgBaojia;
use App\Models\Search;

class ShowController extends Controller
{

    protected $baojia;
    protected $Search;

    public function __construct(HgBaojia $baojia, Search $Search)
    {
        $this->baojia = $baojia;
        $this->Search = $Search;
        $this->middleware('auth');
    }


    /**
     * @param $urls
     * @param $buytype
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 详情页面
     */
    public function getShow($urls)
    {
        $secret = $this->CheckSource($urls, 'HwaChe@cn');
        $id = $secret['id'];
        $area_id = $secret['area_id'];
        $areas = $this->getRegions($area_id);
        $result = $this->baojia->getBaojiaData($id);
        $result['area_id'] = $area_id;
        //车辆详细
        $result['tips'] = empty($areas['tips']) ? null : $areas['tips'];
        //品牌
        $result['brand'] = config('car')[0][$result['d_brand_id']]['gc_name'];
        $car_info = HgCarInfo::getCarmodelFields($result['brand_id']);
        //排放标准
        $result['paifang'] = empty($car_info['paifang']) ? '国五' : '国四';
        //指导价
        $result['zhidaojia'] = $car_info['zhidaojia'];
        //座位数
        $result['seat_num'] = $car_info['seat_num'];
        //国别
        $result['country'] = empty($car_info['guobie']) ? '国产' : '进口';
        //车辆内饰颜色
        if ($result['bj_is_xianche']) {
            $temp = $this->getInteriorColor($id);
            $result['interior_color'] = $car_info['interior_color'][$temp['interior_color']];
        } else {
            //见鬼的序列化及空值
            if ($result['bj_temp_internal_color'] && array_filter(unserialize($result['bj_temp_internal_color']))) {
                $key = implode(unserialize($result['bj_temp_internal_color']));
                $result['interior_color'] = $car_info['interior_color'][$key];
            } else {
                $result['interior_color'] = $car_info['interior_color'][unserialize($result['value'])];
            }
        }

        //车源范围
        $data = [
            $result['province1_name'] . $result['area1_name'],
            $result['province2_name'] . $result['area2_name'],
            $result['province3_name'] . $result['area3_name']
        ];
        $result['scope'] = implode(array_filter($data), ' 或 ');
        // 担保金
        $areabaoxian = $this->baojia->getAreaBaoxian($id);
        $result['client_sponsion_price'] = ($area_id == $areabaoxian['d_shi']) ? $result['client_sponsion_price_low'] : $result['client_sponsion_price_high'];
        //地区
        $result['area_city'] = $areas['parent_name'] . $areas['name'];
        $result['area_xianpai'] = $areas['area_xianpai'];
        //保险相关
        if ($result['bj_baoxian'] && ($areabaoxian['bx_is_quanguo'] || $area_id == $areabaoxian['d_shi'])) {
            $result['is_baoxian'] = true;
        } else {
            $result['is_baoxian'] = false;
        }

        //上牌用途（个人、企业）
//        $result['baoxian_price'] = Search::getBXTotal($result['seat_num'], $id, $car_info['guobie'], $buytype);
        //其他收费
        $result['others'] = $this->Search->getOtherPrice($id)->toArray();
        $result['other_sum'] = collect($result['others'])->sum('sub_total');
        $result['gitfs'] = HgBaojiaZengpin::getBaojiaZengpin($id)->toArray();
        //随车工具
        $result['annexs'] = HcVehicleToolsFiles::getAnnex($result['brand_id']);
        //原厂选装件 <现车（判断后装），非现车（判断前装）>
        $result['originals'] = HgBaojiaXzj::getXzjType($id);
        //上牌服务
        $result['shangpai'] = $this->getShang($result['area_city'], $data, $result['bj_shangpai']);
        if ($result['shangpai'] == 4) {
            if ($result['d_shi'] == $area_id) {
                $result['shangpai_status'] = $result['bj_shangpai'] ? 2 : 3;
            } else {
                $result['shangpai_status'] = 1;
            }
        }
        //限牌城市
        $result['xianpai_citys'] = Area::getXianpaiCity($area_id)->toArray();
        $text = $id.','.$result['shangpai'];
        $result['en_data'] = hc_encrypt($text, 'HwaChe@cn');
        return view('HomeV2.show', $result);
    }


    public function tellUs()
    {
        if (!check_login()) {
            return redirect(route('userlogin'));
        }

        return view('show.tellus');
    }

    // 保存提交的文件
    public function saveTellUs()
    {
        if (!check_login()) {
            return redirect(route('userlogin'));
        }
        $data = [
            'title'     => Request::input('title'),
            'member_id' => $_SESSION['member_id'],
            'username'  => $_SESSION['member_name'],
        ];
        if (HgTellUs::insert($data)) {
            echo "提交成功谢谢配合";
        } else {
            echo "提交失败，请检查名称";
        }


    }

    public function getCarCalc(Baojia $baojia, $id, $area_id, $buytype=0)
    {

        /**
         * 车型扩展信息，仅查询setting存在值的数据
         * 一般包括补贴的选项，排放选项，国别选项等
         */
        $carFields = HgFields::getFieldsList('carmodel');
        if (empty($carFields)) {
            // TODO 没有查找到对应的车型扩展信息
            exit('没有查找到对应的车型扩展信息');
        }
        foreach ($carFields as $k => $v) {
            $carFields[$k] = unserialize($v);
        }

        // 查询是否存在该报价
        $bj = $baojia->checkBaojiaId($id);

        //车辆品牌车系车型
        $bj['brand'] = explode('&gt;', $bj['gc_name']);

        //获取品牌车型的基本信息
        $brand_info = HgGoodsClass::find($bj['brand_id'])->toArray();
        $pailiang = $brand_info['pailiang'];

        /**
         * 获取该车型本身的数据信息----车型
         * 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
         */
        $carmodelInfo = HgCarInfo::getCarmodelFields($bj['brand_id']);
        /**
         * 该报价对应车型本身信息----报价
         * 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
         */
        $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($bj['bj_id']);
        foreach ($bjCarInfo as $key => $val) {
            if (is_array($carmodelInfo[$key])) {
                $carmodelInfo[$key] = $carmodelInfo[$key][$val];
            } else {
                $carmodelInfo[$key] = $val;
            }
        }
        // 合并该报价对应车型的具体参数
        $bj = array_merge($bj, $carmodelInfo);

        // 保险价格计算
        $areabaoxian = $this->baojia->getAreaBaoxian($bj['bj_id']);
        if ($bj['bj_baoxian'] == 1 && ($areabaoxian['bx_is_quanguo'] || $area_id == $areabaoxian['d_shi'])) {//指定投保
            $shangyebaoxian['type'] = 1;
        } else {//自由投保
            $shangyebaoxian['type'] = 0;
        }
        $shangyebaoxian['price'] = Search::getBXTotal($bj['seat_num'], $bj['bj_id'], $carmodelInfo['guobie'], $buytype);

        $newData = array();

        //购置税
        $newData['car_hw_price'] = $bj['hwache_price'];//华车平台车价
        if ($pailiang == '') {
            $pailiang = 1.6;
            $gzsRate = 7.5;//空值时默认是1.6L 购置税5%
        } else {
            if ($pailiang <= 1600) {
                $gzsRate = 7.5;//1.6L以下 购置税5%
            } else {
                $gzsRate = 10;//1.6L以上 购置税10%
            }
        }
        $newData['gouzhishui'] = ($bj['hwache_price'] / 1.17) * ($gzsRate / 100);//华车平台 车架
        $newData['gzsRate'] = $gzsRate;//购置税比例

        //车船税
        $chechuanshui = 0;
        $car_area = Area::getAreaByid($area_id);
        if($car_area['car_boat_tax']){
            $car_boat_tax = explode(',', $car_area['car_boat_tax']);
            $pailiang_range = [1000, 1600, 2000, 2500, 3000, 4000];
            foreach ($pailiang_range as $k => $v) {
                if ($v >= $pailiang) {
                    $chechuanshui = $car_boat_tax[$k];
                    break;
                }
            }
        }
        $newData['chechuanshui'] = $chechuanshui;

        // 地址
        $areas = $this->getRegions($area_id);
        $city = $areas['parent_name'] . $areas['name'];
        $xianpai = $areas['area_xianpai'];

        //上牌
        $data = [
            $bj['province1_name'] . $bj['area1_name'],
            $bj['province2_name'] . $bj['area2_name'],
            $bj['province3_name'] . $bj['area3_name']
        ];
        $shangpai_type = $this->getShang($city, $data, $bj['bj_shangpai']);
        $newData['shangpai'] = array('type' => $shangpai_type, 'price' => $shangpai_type == 1 ? 0 : $bj['bj_shangpai_price']);

        //临牌
        $newData['linpai'] = array('type' => $bj['bj_linpai'], 'price' => $bj['agent_temp_numberplate_price']);

        if ($buytype == 0) {//非营业个人
            if ($bj['seat_num'] <= 6) {
                $jiaoqiangxian = 950;
            } else {
                $jiaoqiangxian = 1100;
            }
        } elseif ($buytype == 1) {//非营业企业
            if ($bj['seat_num'] <= 6) {
                $jiaoqiangxian = 1000;
            } else {
                $jiaoqiangxian = 1130;
            }
        }
        $newData['jiaoqiangxian'] = $jiaoqiangxian;

        $newData['shangyebaoxian'] = $shangyebaoxian;
        $newData['seller_other_price'] = $bj['bj_other_price'];
        $newData['butie'] = $bj['bj_butie'];
        $newData['doposit_price'] = ($area_id == $bj['d_shi']) ? $bj['client_sponsion_price_low'] : $bj['client_sponsion_price_high'];;
        $newData['pailiang'] = $pailiang;
        $newData['seat_num'] = $bj['seat_num'];
        $newData['brand'] = $bj['brand'];
        $newData['buytype'] = $buytype;
        $newData['city'] = $city;
        $newData['xianpai'] = $xianpai;
        $newData['title'] = '落地价计算器 - 华车网';

        return view('show.car_calc', $newData);
    }

    //选装精品列表
    public function jingpinList($id)
    {
        $baojia = HgBaojia::getBaojiaInfoAndScope($id)->toArray();
        $xzj = HgBaojiaXzj::getXzjType($id);

        return view('HomeV2.xzj_list', ['gc_name' => explode('&gt;', $baojia['gc_name']), 'xzj' => $xzj]);
    }

    /**
     * @param $area
     * @param $scope
     * @param $shangpai_status
     * @return int
     */
    private function getShang($area, $scope, $shangpai_status)
    {
        //1本人上牌,2指定上牌,3自选上牌.4接受安排
        $temp = implode($scope);
        if (array_search($area, $scope) === false) {
            $shangpai = 1;
        } elseif ($temp == $area) {
            $shangpai = empty($shangpai_status) ? 3 : 2;
        } else {
            $shangpai = 4;
        }
        return $shangpai;
    }

    /**
     * @param $city
     * @return bool
     */
    private function getRegions($city)
    {
        $regionList = config('area');
        $areas = $this->getRegion($city);
        $areas['parent_name'] = $regionList[0][$areas['parent_id']]['name'];
        $areas['tips'] = DB::table('area')
            ->select('tips', 'special_file')
            ->whereAreaId($areas['area_id'])
            ->first();
        return $areas;
    }

    /**
     * 读取地区缓存数据
     * @param $city
     * @return bool
     */
    public function getRegion($city)
    {
        $regionList = config('area');
        if ($regionList) {
            foreach ($regionList as $key => $regs) {
                if (array_key_exists($city, $regs)) {
                    $tmp = $regionList[$key][$city];
                    $tmp['parent_id'] = $key;
                    return $tmp;
                }
            }
        }
        return false;
    }


    /**
     * @param $urls
     * @param $key
     * @return int
     */
    public function CheckSource($urls, $key)
    {
        $urls = explode(',', hc_decrypt($urls, $key));
        if (count($urls) == 2 && (int)($urls[1]) != 1) {
            $dedata['id'] = (int)($urls[0]);
            $dedata['area_id'] = (int)($urls[1]);
            return $dedata;
        }
        die('报价不存在!!!');
    }



    /**
     * @param $id
     * @return array
     * 现车基本资料查询
     */
    public function getInteriorColor($id)
    {
        return HgBaojiaField::getCarFieldsByBjid($id);
    }
}
