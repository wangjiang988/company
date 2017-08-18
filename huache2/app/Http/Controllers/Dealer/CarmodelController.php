<?php
/**
 * Created by PhpStorm.
 * Date: 2016/7/7
 * Time: 14:29
 */

namespace App\Http\Controllers\Dealer;

use App\Models\HcVehicleToolsFiles;
use App\Models\HgBaojia;
use App\Models\HgBaojiaField;
use Illuminate\Http\Request;
use App\Models\HgDailiDealer;
use App\Models\HgGoodsClassStaple;
use App\Models\HgCarInfo;
use DB;
use App\Models\HgAnnex;
use App\Models\HgBaoXian;
use App\Models\HgGoodsClass;
use App\Http\Controllers\Controller;

class CarmodelController extends Controller
{
    private $request;

    public function __construct(Request $request)
    {
        $this->middleware('auth.seller');
        $this->request = $request;
    }


    public function getCarmodel($id)
    {
        $dl_id = session('user.member_id');
        $view['dealer_id'] = $id;
        $view['daili_dealer_id'] = HgDailiDealer::getDailiDealerId($dl_id, $id);
        $view['flag'] = 'carmodel' . $view['daili_dealer_id'];
        $view['title'] = '常用车型';
        //搜索
        $gc_name = $this->request->get('search');
        $gc_car_id = $this->request->get('id');
        //获取经销商id
        $daili = HgDailiDealer::where('d_id', $id)->where('dl_id', $dl_id)->where('dl_status', '2')->first();
        if (!empty($daili)) {
            $daili = $daili->toArray();
            //获取车辆信息
            $brand_id = $daili['dl_brand_id'];
            $view['car_brand'] = config('car')[0][$brand_id];  //获取经销商的品牌
            if (!empty($view['car_brand'])) {
                $gc_id = $view['car_brand']['gc_id'];
            } else {
                die('车型未知');
            }
            //获取车型
            $goods_class = config('car')[$gc_id];
            $view['goods_class'] = $goods_class;
            $view['goods_name'] = $gc_name;
            if (!empty($gc_name) && !empty($gc_car_id)) {
                $carmodel = HgGoodsClassStaple::getCarSearch($daili['dl_id'], $daili['d_id'], $gc_name);
            } else {
                $carmodel = HgGoodsClassStaple::getModels($daili['dl_id'], $daili['d_id']);
            }
        } else {
            die('还没有经销商或审核中');
        }
        //判断是否存在车辆数据,并获取每个车源的锁定数量
        //add wangjiang
        if (count($carmodel)) {
            $carmodel = $carmodel->toArray();
            foreach ($carmodel as $k=>$car)
            {
                $locking_count  = HgBaojia::where('m_id',$dl_id)
                    ->where('brand_id', $car['gc_id_3'])
                    ->where('bj_dealer_internal_id','!=','')
                    ->whereIn('bj_status',['1','2','6'])
                    ->distinct('bj_dealer_internal_id')
                    ->count('bj_dealer_internal_id');

                $carmodel[$k]['locking_count'] = $locking_count;
            }
            $view['carmodel'] = $carmodel;
        }
        //end
        $series = HgGoodsClassStaple::getCarseries($id, $dl_id);
        if (!empty($series)) {
            $view['series'] = $series;
        } else {
            $view['series'] = array();
        }

        return view("dealer.ucenter.dealer_list_carmodel", $view);
    }

    //查看和修改,删除报价
    public function editCarmodel($type, $dealer_id, $staple_id = 0)
    {
        $daili_id = session('user.member_id');
        $view['dealer_id'] = $dealer_id;
        $view['daili_dealer_id'] = HgDailiDealer::getDailiDealerId($daili_id, $view['dealer_id']);
        $car_id = HgDailiDealer::where('id', $view['daili_dealer_id'])->pluck('dl_brand_id')->first();
        $view['flag'] = 'carmodel' . $view['daili_dealer_id'];
        $allCar = config('car')[0];
        $view['car_brand'] = $allCar[$car_id];     //获取品牌
        //判断经销商是否存在
        $dealer_has = HgDailiDealer::where('d_id', $dealer_id)->where('dl_id', $daili_id)->where('dl_status',
            '2')->first();
        if (empty($dealer_has)) {
            die('经销商不存在');
        }

        //读取车型价格等信息
        if ($type != 'add') {
            $data = HgGoodsClassStaple::getOneModels(session('user.member_id'), $dealer_id, $staple_id);
            $data['vehicle_model'] = HgGoodsClass::where('gc_id', $data['gc_id_3'])->value('vehicle_model');
            if (!empty($data)) {
                $view['data'] = $data->toArray();
            } else {
                die('未找到数据');
            }

            //原厂前装组件
            $view['yc'] = HgDailiDealer::frequentCarXzjList($dealer_id, $daili_id, $view['data']['gc_id_3'])['yc'];
            //根据车型列出新选装件
            // wangjiang 2017-7-7 16:33
            $view['new_yc'] = HgDailiDealer::getNewXzjList( $view['yc'], $view['data']['gc_id_3']);
            //后装
            $view['fyc'] = HgDailiDealer::frequentCarXzjList($dealer_id, $daili_id, $view['data']['gc_id_3'])['fyc'];

            // wangjiang 2017-7-7 16:33
            $view['new_fyc'] = HgDailiDealer::getNewXzjList($view['fyc'], $view['data']['gc_id_3'],'fyc');

            //随车工具
           $view['general'] = HcVehicleToolsFiles::getAnnex($view['data']['gc_id_3']);
            //非原厂选装件
            $view['xzjp'] = HgDailiDealer::getOptionList($daili_id, $dealer_id, $view['data']['gc_id_3']);
        }
        //查看车型
        if ($type == 'check') {
            $template = 'check';
        }
        //修改车型
        if ($type == 'edit') {
            $template = 'edit';
        }
        if ($type == 'add') {
            $template = 'add';
            $allCar = config('car');
            $tmpe = $allCar[$car_id];
            $goods_class = array();
            foreach ($tmpe as $k => $v) {
                $goods_class[] = $v;
            }
            //根据品牌来获取车系
            $view['goods_class'] = $goods_class;
        }
        if (isset($data) && ( ! is_null($data->goodclass))) {
            $detail_img = $data->goodclass->detail_img;
        } else {
            $detail_img = '';
        }
        return view('dealer.ucenter.dealer_' . $template . '_carmodel', $view)->with(compact('detail_img'));
    }


    public function ajaxCarmodel($type)
    {
        $brand_id = $this->request->input('gc_id_3');     //对应的品牌id
        $daili_id = session('user.member_id');             //代理id
        $dealer_id = $this->request->input('dealer_id');   //经销商id
        $daili_dealer_id = HgDailiDealer::getDailiDealerId($daili_id, $dealer_id);
        //删除选装件
        if ($type == 'del') {
            $xzj_id = $this->request->input('id');             //选装件id
            $data = HgDailiDealer::frequentCarXzjDelete($xzj_id, $daili_id, $dealer_id, $brand_id);
            return $data;
        }

        //删除常用车型
        if ($type == 'del_carmodel') {
            $staple_id = $this->request->input('staple_id');
            return HgGoodsClassStaple::delStaple($daili_id, $staple_id);
        }

        //添加或修改非原厂选装
        if ($type == 'add' || $type == 'edit') {
            $data = [];
            $type == 'add' ? $data['id'] = 0 : $data['id'] = $this->request->input('id');
            $data['daili_dealer_id'] = HgDailiDealer::getDailiDealerId(session('user.member_id'), $dealer_id);
            $data['staple_id'] = $this->request->input('staple_id');
            $data['car_brand'] = $brand_id;
            $data['xzj_title'] = $this->request->input('title');
            $data['xzj_brand'] = $this->request->input('brand');
            $data['xzj_model'] = $this->request->input('model');
            $data['xzj_cs_serial'] = $this->request->input('serial');
            $data['xzj_has_num'] = $this->request->input('has_num');
            $data['xzj_max_num'] = $this->request->input('max_num');
            $data['xzj_guide_price'] = $this->request->input('price');
            $data['member_id'] = $daili_id;
            $data['dealer_id'] = $dealer_id;
            return HgDailiDealer::frequentCarFycXzjSave($daili_id, $dealer_id, $brand_id, $data);
        }

        //车规格列表
        if ($type == 'list') {
            $id = $this->request->input('id');
            $allCar = config('car');
            $tmpe = $allCar[$id];
            $goods_class = array();
            foreach ($tmpe as $k => $v) {
                $goods_class[] = $v;
            }
            return json_encode($goods_class);
        }

        //根据车规格列出所有的车辆信息
        if ($type == 'listAll') {
            //获取厂家指导价
            $view['price'] = HgCarInfo::getCarmodelPrices($brand_id);
            $checkExit = DB::table('goods_class_staple')
                ->where('gc_id_3', $brand_id)
                ->where('dealer_id', $dealer_id)
                ->where('member_id', $daili_id)
                ->where('status', '<>', 2)
                ->where('daili_dealer_id', $daili_dealer_id)
                ->count();
            if ($checkExit >= 1) {
                $view['check'] = 'true';
            }
            return json_encode($view);
        }
    }

    //添加车型
    public function AddCarmodel($id)
    {
        $dealer_id = $id;
        $dl_id = session('user.member_id');
        $daili_dealer_id = HgDailiDealer::getDailiDealerId($dl_id, $dealer_id);
        $gc_id_3 = $this->request->input('gc_id_3');
        //判断是否重复添加常用车型
        $checkExit = DB::table('goods_class_staple')
            ->where('gc_id_3', $gc_id_3)
            ->where('dealer_id', $dealer_id)
            ->where('member_id', $dl_id)
            ->where('status', '<>', 2)
            ->where('daili_dealer_id', $daili_dealer_id)
            ->get();
        if (count($checkExit) >= 1) {
            return [
                'error_code == 0'
            ];
        }
        //添加数据到车型表中
        $goods_class = HgGoodsClass::get()->toArray();
        $class = HgDailiDealer::Ancestry($goods_class, $gc_id_3);
//        $staple_name = implode(">",$class);
        $arr = [
            'gc_id_1'         => array_keys($class)[0],
            'gc_id_2'         => array_keys($class)[1],
            'gc_id_3'         => $gc_id_3,
            'staple_name'     => $class[$gc_id_3],
            'dealer_id'       => $dealer_id,
            'member_id'       => $dl_id,
            'daili_dealer_id' => $daili_dealer_id
        ];
        $staple_id = DB::table('goods_class_staple')->insertGetId($arr);
        //列出车辆的前后(非原厂)信息
        foreach ($class as $value) {
            $temp[] = $value;
        }
        $data = [
            'car_brand'       => $temp['0'],
            'car_series'      => $temp['1'],
            'car_standard'    => $temp['2'],
            'staple_id'       => $staple_id,
            'dealer_id'       => $dealer_id,
            'gc_id_3'         => $gc_id_3,
            'flag'            => 'carmodel' . $daili_dealer_id,
            'daili_dealer_id' => $daili_dealer_id
        ];
        $data['vehicle_model'] = HgGoodsClass::where('gc_id', $gc_id_3)->value('vehicle_model');
        $data['xzj'] = HgDailiDealer::gtListCarXzj($gc_id_3, $staple_id);
        $data['price'] = HgCarInfo::getCarmodelFields($gc_id_3)['zhidaojia'];
        $data['general'] = (HcVehicleToolsFiles::getAnnex($gc_id_3));
        $detail_img = HgGoodsClassStaple::where('staple_id',$staple_id)->first()->goodclass->detail_img;
        return view('dealer.ucenter.dealer_lock_carmodel', $data)->with(compact('detail_img'));
    }


    //修改信息部分
    public function editCarMess($id)
    {
        $dealer_id = $id;
        $dl_id = session('user.member_id');
        $staple_id = $this->request->input('staple_id');
        $qz_cs_serial = $this->request->input('qz_cs_serial');
        $qza_cs_serial = $this->request->input('qza_cs_serial'); //前装 新增
        if (!empty($this->request->input('xzj_has_num'))) {
            $xzj_has_num = array_map('trim', $this->request->input('xzj_has_num'));
        } else {
            $xzj_has_num = array();
        }
        $xzj_fee = $this->request->input('xzj_fee');
        $hz_cs_serial = $this->request->input('hz_cs_serial');


        //新增 后装
        $xzja_fee = $this->request->input('xzja_fee');
        $hza_cs_serial = $this->request->input('hza_cs_serial');
        if (!empty($this->request->input('xzja_has_num'))) {
            $xzja_has_num = array_map('trim', $this->request->input('xzja_has_num'));
        } else {
            $xzja_has_num = array();
        }

        $car_brand       = $this->request->input('car_brand');
        $daili_dealer_id = HgDailiDealer::getDailiDealerId($dl_id, $dealer_id);
        $type            = $this->request->input('type');

        $qz_cs_serial = collect($qz_cs_serial)->map(function ($qz_cs_serial, $Index) {
            return [
                'xzj_list_id'   => $Index,
                'xzj_has_num'   => 0,
                'xzj_yc'        => 1,
                'xzj_front'     => 1,
                'xzj_fee'       => 0,
                'xzj_cs_serial' => $qz_cs_serial
            ];
        });
        $qz_cs_serial = $qz_cs_serial->toArray();

        //修改时，有新增的前装选装件
        if($qza_cs_serial){
            $qza_cs_serial = collect($qza_cs_serial)->map(function ($qza_cs_serial, $Index) {
                return [
                    'xzj_list_id'   => $Index,
                    'xzj_has_num'   => 0,
                    'xzj_yc'        => 1,
                    'xzj_front'     => 1,
                    'xzj_fee'       => 0,
                    'xzj_cs_serial' => $qza_cs_serial
                ];
            });
            $qza_cs_serial = $qza_cs_serial->toArray();
        }

        $xzj_has_num = collect($xzj_has_num)->map(function ($xzj_has_number, $xzj_Index) {
            return ['xzj_list_id' => $xzj_Index, 'xzj_has_num' => $xzj_has_number, 'xzj_yc' => 1, 'xzj_front' => 0];
        });
        $xzj_has_num = $xzj_has_num->toArray();

        $xzj_fee = collect($xzj_fee)->map(function ($xzj_has_fee, $xzj_Index) {
            return ['xzj_list_id' => $xzj_Index, 'xzj_fee' => $xzj_has_fee];
        });
        $xzj_fee = $xzj_fee->toArray();

        $hz_cs_serial = collect($hz_cs_serial)->map(function ($hz_cs_serial, $xzj_Index) {
            return ['xzj_list_id' => $xzj_Index, 'xzj_cs_serial' => $hz_cs_serial];
        });
        $hz_cs_serial = $hz_cs_serial->toArray();
        foreach ($hz_cs_serial as $key => $value) {
            if (array_key_exists($key, $xzj_fee) && is_array($xzj_fee[$key])) {
                $hz_cs_serial[$key] = $xzj_has_num[$key] + $xzj_fee[$key] + $hz_cs_serial[$key];
            }
        }

        //新增 后装
        $xzja_has_num = collect($xzja_has_num)->map(function ($xzj_has_number, $xzj_Index) {
            return ['xzj_list_id' => $xzj_Index, 'xzj_has_num' => $xzj_has_number, 'xzj_yc' => 1, 'xzj_front' => 0];
        });
        $xzja_has_num = $xzja_has_num->toArray();

        $xzja_fee = collect($xzja_fee)->map(function ($xzj_has_fee, $xzj_Index) {
            return ['xzj_list_id' => $xzj_Index, 'xzj_fee' => $xzj_has_fee];
        });
        $xzja_fee = $xzja_fee->toArray();

        $hza_cs_serial = collect($hza_cs_serial)->map(function ($hz_cs_serial, $xzj_Index) {
            return ['xzj_list_id' => $xzj_Index, 'xzj_cs_serial' => $hz_cs_serial];
        });
        $hza_cs_serial = $hza_cs_serial->toArray();
        foreach ($hza_cs_serial as $key => $value) {
            if (array_key_exists($key, $xzja_fee) && is_array($xzja_fee[$key])) {
                $hza_cs_serial[$key] = $xzja_has_num[$key] + $xzja_fee[$key] + $hza_cs_serial[$key];
            }
        }

        $data = array_merge($hz_cs_serial, $qz_cs_serial);

        //新增 前后装
        $addData =  array_merge($hza_cs_serial, $qza_cs_serial);
        if ($type == 'add') {
            $data = collect($data)->map(function ($item, $key) use (
                $dealer_id,
                $daili_dealer_id,
                $dl_id,
                $car_brand,
                $staple_id
            ) {
                $item['dealer_id'] = $dealer_id;
                $item['daili_dealer_id'] = $daili_dealer_id;
                $item['member_id'] = $dl_id;
                $item['car_brand'] = $car_brand;
                $item['staple_id'] = $staple_id;
                return $item;
            });
            $data = $data->toArray();
            $result = DB::table('hg_xzj_daili')->insert($data);
        } else {
            //新增选装件
            if(isset($addData) && count($addData)>0) {
                $addData = collect($addData)->map(function ($item, $key) use (
                    $dealer_id,
                    $daili_dealer_id,
                    $dl_id,
                    $car_brand,
                    $staple_id
                )  {
                    $item['dealer_id'] = $dealer_id;
                    $item['daili_dealer_id'] = $daili_dealer_id;
                    $item['member_id'] = $dl_id;
                    $item['car_brand'] = $car_brand;
                    $item['staple_id'] = $staple_id;
                    return $item;
                });
                $addData = $addData->toArray();
                $result1 = DB::table('hg_xzj_daili')->insert($addData);
            }

            //修改选装件
            $result = collect($data)->map(function ($item, $key) {
                $data = $item;
                unset($item['xzj_list_id']);
                return DB::table('hg_xzj_daili')->where('id', $data['xzj_list_id'])->update($item);
            });
        }

        if (!empty($result)) {
            return [
                'error_code' => 1,
                'error_msg'  => $dealer_id
            ];
        } else {
            return [
                'error_code' => 0,
                'error_msg'  => '操作失败'
            ];
        }

    }

    //保险部分
    public function getSurance($id)
    {
        $dl_id = session('user.member_id');
        $view['daili_dealer_id'] = HgDailiDealer::getDailiDealerId($dl_id, $id);
        $view['flag'] = 'surance' . $view['daili_dealer_id'];
        //判断经销商存不存在
        $Check = HgDailiDealer::where('dl_id', $dl_id)->where('d_id', $id)->where('dl_status', '<>', 3)->first();
        if (!$Check) {
            die('经销商不存在');
        }
        $data = HgBaoXian::getBaoxian($dl_id, $id, $view['daili_dealer_id']);
        if (!empty($data)) {
            $view['data'] = $data;
        } else {
            $view['data'] = array();
        }
        return view('dealer.ucenter.dealer_baoxian', $view);
    }

    //车源锁定明细
    public function locking_car_staple($dealer_id, $staple_id, $brand_id)
    {

        $dl_id = session('user.member_id');
        $view['daili_dealer_id'] = HgDailiDealer::getDailiDealerId($dl_id, $dealer_id);
        $view['flag'] = 'surance' . $view['daili_dealer_id'];
      //获取车源列表
        $count = HgBaojia::where('m_id',$dl_id)
            ->where('brand_id',$brand_id)
            ->whereIn('bj_status',['1','2','6'])
            ->where('bj_dealer_internal_id','!=','')
            ->count();

        $list = HgBaojia::with(['dealer'=>function($query){
                              $query->select(['d_id','d_name']);
                        }])
//                        ->where('dealer_id',$dealer_id)
                        ->where('m_id',$dl_id)
//                        ->where('car_staple_id',$staple_id)
                       ->where('brand_id',$brand_id)
                        ->whereIn('bj_status',['1','2','6'])
                       ->where('bj_dealer_internal_id','!=','')
                        ->orderBy('dealer_id')
                       ->get(['bj_dealer_internal_id','bj_body_color','bj_id','bj_start_time','brand_id','bj_status','bj_serial','dealer_id']);
        if($list)
        {
            $list->map(function (&$item, $key) {
                $field = HgBaojiaField::getCarFieldsByBjid($item->bj_id);

                $carmodelInfo = HgCarInfo::getCarmodelFields($item->brand_id);
                foreach ($field as $key => $val) {
                    if (isset($carmodelInfo[$key]) && is_array($carmodelInfo[$key])) {
                        $carmodelInfo[$key] = $carmodelInfo[$key][$val];
                    } else {
                        $carmodelInfo[$key] = $val;
                    }
                }
                if(isset($carmodelInfo['interior_color']))  
                    $item->interior_color = $carmodelInfo['interior_color'];
//                $item->attributes['interior_color'] = $item->original['interior_color'];
            });
        }
        //查询结果分组
        $list = $list->groupBy('bj_dealer_internal_id');

        //获取车型数据
        $carmodel = HgGoodsClassStaple::getOneModels($dl_id,$dealer_id,$staple_id);
        $carmodel->parent =  HgGoodsClass::where('gc_id',$carmodel->gc_parent_id)
                                        ->select('gc_id','gc_name')
                                        ->firstOrFail();
        //反序列号厂家指导价
        $carmodel->value =  unserialize($carmodel->value);
        $view['carmodel']  = $carmodel;
        $view['list']  = $list;
        $view['count']  = $count;
        return view('dealer.ucenter.dealer_locking_car_staple', $view);
    }




}