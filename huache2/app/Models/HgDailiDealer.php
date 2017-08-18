<?php namespace App\Models;

/**
 * 经销商模型
 */
use Illuminate\Database\Eloquent\Model;
use Cache;
use Exception;
use DB;
use App\Models\HgBaojia;

class HgDailiDealer extends Model
{

    protected $table = 'hg_daili_dealer';

    public $timestamps = false;

    public static function getDailiDealerInfo($id)
    {
        $cacheName = 'DailiDealer' . $id;
        if (!Cache::has($cacheName)) {
            $cacheData = [];
            $d = self::where('d_id', $id)->get();
            if (!empty($d)) {
                foreach ($d as $key => $value) {
                    $cacheData = $value;
                }

            }
            //Cache::put($cacheName,$cacheData);
        } else {
            $cacheData = Cache::get($cacheName);
        }
        return $cacheData;

    }

    /**
     * 经销商常用车型列表
     * @param array $data
     */
    public static function frequentCarList($daili_id, $dealer_id)
    {
        return DB::table("goods_class_staple")
            ->where('member_id', $daili_id)
            ->where('dealer_id', $dealer_id)
            ->get();
    }

    /**
     * 经销商常用车型新增
     * @param array $data
     */
    public static function frequentCarAdd($data)
    {
        return DB::table("goods_class_staple")->insertGetId($data);
    }

    /**
     * 经销商常用车型 选装件 列表获取
     * @param  $daili_id 代理ID
     * @param  $dealer_id 经销商ID
     * @param  $brand_id 车型ID
     */
    public static function frequentCarXzjList($dealer_id, $daili_id, $brand_id)
    {
        //原厂选装件，直接根据车型查询 并关联代理的选择
        $data['yc'] = DB::table("hg_xzj_list")
            ->leftJoin('hg_xzj_daili', 'hg_xzj_list.id', '=', 'hg_xzj_daili.xzj_list_id')
            ->join('goods_class_staple', 'goods_class_staple.staple_id', '=', 'hg_xzj_daili.staple_id')
            ->where('goods_class_staple.status', '<>', 2)
            ->where('hg_xzj_list.car_brand', $brand_id)
            ->where('hg_xzj_list.xzj_yc', 1)
            ->where('hg_xzj_list.xzj_front', 1)
            ->where('hg_xzj_daili.dealer_id', $dealer_id)
            ->where('hg_xzj_daili.member_id', $daili_id)
            ->select('hg_xzj_list.*', 'hg_xzj_list.id as xzj_id', 'hg_xzj_daili.xzj_cs_serial', 'hg_xzj_daili.id',
                'hg_xzj_daili.xzj_has_num', 'hg_xzj_daili.xzj_fee')
            ->get();
        //非原厂选装件
        $data['fyc'] = DB::table("hg_xzj_list")
            ->leftjoin('hg_xzj_daili', 'hg_xzj_list.id', '=', 'hg_xzj_daili.xzj_list_id')
            ->join('goods_class_staple', 'goods_class_staple.staple_id', '=', 'hg_xzj_daili.staple_id')
            ->where('goods_class_staple.status', '<>', 2)
            ->where('hg_xzj_daili.member_id', $daili_id)
            ->where('hg_xzj_daili.dealer_id', $dealer_id)
            ->where('hg_xzj_daili.car_brand', $brand_id)
            ->where('hg_xzj_list.xzj_yc', 1)
            ->where('hg_xzj_list.xzj_front', 0)
            ->select('hg_xzj_list.*', 'hg_xzj_list.id as xzj_id', 'hg_xzj_daili.xzj_cs_serial', 'hg_xzj_daili.id',
                'hg_xzj_daili.xzj_has_num', 'hg_xzj_daili.xzj_fee')
            ->get();
        return $data;
    }

    /**
     * 原厂选装件 保存
     * @param 代理id $daili_id
     * @param 经销商id $dealer_id
     * @param 车型id $brand_id
     * @param 数据 $data 数据格式为
     *            1、前装数据格式为 array($xzj_id=>array($id,$xzj_cs_serial,$front=1))
     *            2、后装数据是为 array($xzj_id=>array($id,$xzj_cs_serial,$xzj_fee,$xzj_has_num,$front=0))
     *
     */

    public static function frequentCarYcXzjSave($daili_id, $dealer_id, $brand_id, $front, $data)
    {
        if (is_array($data) && count($data) > 0) {
            DB::transaction(function () use ($daili_id, $dealer_id, $brand_id, $front, $data) {
                foreach ($data as $k => $v) {
                    $row = array();
                    $addData = array();
                    $row = DB::table('hg_xzj_list')->where('id', $k)->first();
                    if (!$row) {
                        continue;
                    }
                    if ($v['front'] == 1) {//前装
                        $addData = array(
                            'xzj_cs_serial'   => $v['xzj_cs_serial'],
                            'xzj_list_id'     => $k,
                            'xzj_max_num'     => $row->xzj_max_num,
                            'car_brand'       => $row->car_brand,
                            'xzj_title'       => $row->xzj_title,
                            'xzj_yc'          => $row->xzj_yc,
                            'xzj_front'       => $row->xzj_front,
                            'xzj_model'       => $row->xzj_model,
                            'xzj_guide_price' => $row->xzj_guide_price,
                            'member_id'       => $daili_id,
                            'dealer_id'       => $dealer_id,
                        );
                    } else {
                        $addData = array(
                            'xzj_cs_serial'   => $v['xzj_cs_serial'],
                            'xzj_has_num'     => $v['xzj_has_num'],
                            'xzj_fee'         => $v['xzj_fee'],
                            'xzj_list_id'     => $k,
                            'xzj_max_num'     => $row->xzj_max_num,
                            'car_brand'       => $row->car_brand,
                            'xzj_title'       => $row->xzj_title,
                            'xzj_yc'          => $row->xzj_yc,
                            'xzj_front'       => $row->xzj_front,
                            'xzj_model'       => $row->xzj_model,
                            'xzj_guide_price' => $row->xzj_guide_price,
                            'member_id'       => $daili_id,
                            'dealer_id'       => $dealer_id,
                        );
                    }

                    if ($v['id'] > 0) {
                        $e = DB::table("hg_xzj_daili")->where('id', $v['id'])->update($addData);
                    } else {
                        $e = DB::table("hg_xzj_daili")->insertGetId($addData);
                    }
                    if (!$e) {//出错 全部回滚
                        return array('error_code' => 1, 'error_msg' => '数据可保存失败');
                    } else {
                        continue;
                    }
                }
                return array('error_code' => 0, 'msg' => '');
            });
        } else {
            return array('error_code' => 1, 'msg' => '没有数据可保存');
        }
    }

    /**
     * 经销商常用车型 非原厂选装件新增
     * @param array $data
     * $data所需字段如下：
     * id 选装件ID (id=0新增，id>0 更新)
     * car_brand,
     * xzj_title,
     * xzj_brand,
     * xzj_model,
     * xzj_cs_serial,
     * xzj_has_num,
     * xzj_guide_price
     */
    public static function frequentCarFycXzjSave($daili_id, $dealer_id, $brand_id, $data)
    {
        $data['member_id'] = $daili_id;
        $data['dealer_id'] = $dealer_id;
        $data['car_brand'] = $brand_id;
        return DB::transaction(function () use ($data) {
            try {
                $addData = array(
                    'car_brand'       => $data['car_brand'],
                    'xzj_title'       => $data['xzj_title'],
                    'xzj_yc'          => 0,
                    'xzj_front'       => 0,
                    'xzj_brand'       => $data['xzj_brand'],
                    'xzj_model'       => $data['xzj_model'],
                    'cs_serial'       => $data['xzj_cs_serial'],
                    'xzj_max_num'     => $data['xzj_max_num'],
                    //'xzj_has_num'    =>$data['xzj_has_num'],
                    'xzj_guide_price' => $data['xzj_guide_price'],
                    'member_id'       => $data['member_id'],
                    'dealer_id'       => $data['dealer_id'],
                    'staple_id'       => $data['staple_id'],
                    'daili_dealer_id' => $data['daili_dealer_id']
                );
                if ($data['id'] == 0) {//新增
                    $xzj_id = DB::table("hg_xzj_list")->insertGetId($addData);
                    if (!$xzj_id) {
                        throw new Exception('选装件基础数据添加失败');
                    }
                } else {//更新
                    $e1 = DB::table("hg_xzj_list")->where('id', $data['id'])->update($addData);
                    if (!$e1) {
                        throw new Exception('选装件基础数据更新失败');
                    }
                    $xzj_id = $data['id'];
                }

                $addDataXzjDaili = array(
                    'xzj_list_id'     => $xzj_id,
                    'xzj_has_num'     => $data['xzj_has_num'],
                    'car_brand'       => $data['car_brand'],
                    'xzj_title'       => $data['xzj_title'],
                    'xzj_yc'          => 0,
                    'xzj_front'       => 0,
                    'xzj_model'       => $data['xzj_model'],
                    'xzj_brand'       => $data['xzj_brand'],
                    'xzj_max_num'     => $data['xzj_max_num'],
                    'xzj_guide_price' => $data['xzj_guide_price'],
                    'member_id'       => $data['member_id'],
                    'dealer_id'       => $data['dealer_id'],
                    'xzj_cs_serial'   => $data['xzj_cs_serial'],
                    'staple_id'       => $data['staple_id'],
                    'daili_dealer_id' => $data['daili_dealer_id']
                );
                if ($data['id'] == 0) {//新增
                    $id = DB::table("hg_xzj_daili")->insertGetId($addDataXzjDaili);
                    if (!$id) {
                        throw new Exception('基础数据向经销商代理数据表添加失败');
                    }
                } else {//更新
                    $e1 = DB::table("hg_xzj_daili")->where('xzj_list_id', $data['id'])->update($addDataXzjDaili);
                    if (!$e1) {
                        throw new Exception('选装件基础数据更新失败');
                    }
                    if (empty($id)) {
                        $id = $e1;
                    }
                }
            } catch (Exception $e) {
                return array('error_code' => 1, 'msg' => $e->getMessage());
            }
            return array('error_code' => 0, 'msg' => 'success', 'id' => $xzj_id);
        });
    }

    /**
     *
     * @param 选装件ID $xzj_id
     * @param 代理id $daili_id
     * @param 经销商id $dealer_id
     * @param 车型id $brand_id
     * @return multitype:number string
     */
    public static function frequentCarXzjDelete($xzj_id, $daili_id, $dealer_id, $brand_id)
    {
        return DB::transaction(function () use ($xzj_id, $daili_id, $dealer_id, $brand_id) {
            //hg_xzj_list 表中删除
            $e1 = DB::table('hg_xzj_list')
                ->where('id', $xzj_id)
                ->where('member_id', $daili_id)
                ->where("dealer_id", $dealer_id)
                ->where('car_brand', $brand_id)
                ->update(['status' => 2]);
            //hg_xzj_daili 表中删除
            $e2 = DB::table('hg_xzj_daili')
                ->where('xzj_list_id', $xzj_id)
                ->where('member_id', $daili_id)
                ->where("dealer_id", $dealer_id)
                ->where('car_brand', $brand_id)
                ->update(['status' => 2]);
            if (!$e1 || !$e2) {
                return array('error_code' => 1, 'msg' => '删除失败');
            } else {
                return array('error_code' => 0, 'msg' => '删除成功');
            }

        });
    }


    /**
     * 经销商常用车型 选装件 列表获取
     * @param  $daili_id 代理ID
     * @param  $dealer_id 经销商ID
     * @param  $brand_id 车型ID
     */

    public static function getOptionList($daili_id, $dealer_id, $brand_id)
    {
        return DB::table('hg_xzj_daili')
            ->join('hg_xzj_list', 'hg_xzj_list.id', '=', 'hg_xzj_daili.xzj_list_id')
            ->join('goods_class_staple', 'hg_xzj_daili.staple_id', '=', 'goods_class_staple.staple_id')
            ->where('goods_class_staple.status', '<>', '2')
            ->where('hg_xzj_daili.member_id', $daili_id)
            ->where('hg_xzj_daili.car_brand', $brand_id)
            ->where('hg_xzj_daili.dealer_id', $dealer_id)
            ->where('hg_xzj_daili.status', 0)
            ->where('hg_xzj_list.xzj_yc', 0)
            ->where('hg_xzj_list.status', '<>', 2)
            ->where('hg_xzj_daili.status', '<>', 2)
            ->get();
    }


    /**
     * @param $daili_dealer_id
     * @param $brand_id
     * @return mixed
     * 非原厂选装件(news)
     */
    public static function getOptionLists($daili_dealer_id, $brand_id)
    {
        return DB::table('hg_xzj_daili')
            ->select('hg_xzj_daili.id as xzj_daili_id','hg_xzj_list.*','hg_xzj_daili.xzj_has_num','hg_xzj_list.xzj_max_num','hg_xzj_daili.xzj_fee')
            ->join('hg_xzj_list', 'hg_xzj_list.id', '=', 'hg_xzj_daili.xzj_list_id')
            ->join('goods_class_staple', 'hg_xzj_daili.staple_id', '=', 'goods_class_staple.staple_id')
            ->where('goods_class_staple.status', '<>', '2')
            ->where('hg_xzj_daili.daili_dealer_id', $daili_dealer_id)
            ->where('hg_xzj_daili.car_brand', $brand_id)
            ->where('hg_xzj_daili.status', 0)
            ->where('hg_xzj_list.xzj_yc', 0)
            ->where('hg_xzj_list.status', '<>', 2)
            ->where('hg_xzj_daili.status', '<>', 2)
            ->get();
    }


    /**
     * @param $daili_dealer_id
     * @param $brand_id
     * @return mixed
     */
    public static function getOptionAlls($daili_dealer_id, $brand_id)
    {
        return DB::table('hg_xzj_daili')
            ->join('hg_xzj_list', 'hg_xzj_list.id', '=', 'hg_xzj_daili.xzj_list_id')
            ->join('goods_class_staple', 'hg_xzj_daili.staple_id', '=', 'goods_class_staple.staple_id')
            ->where('goods_class_staple.status', '<>', '2')
            ->where('hg_xzj_daili.car_brand', $brand_id)
            ->where('hg_xzj_daili.status', 0)
            ->where('hg_xzj_list.xzj_yc', 0)
            ->where('hg_xzj_list.status', '<>', 2)
            ->where('hg_xzj_daili.status', '<>', 2)
            ->orderBy('hg_xzj_daili.daili_dealer_id',$daili_dealer_id,'asc')
            ->get();
    }


    /**
     *
     * @param $daili_dealer_id
     * @param $brand_id
     * @return mixed
     */
    public static function getOrderOption(array $condition)
    {
        return DB::table('hg_xzj_daili')
            ->select('hg_xzj_daili.id as xzj_daili_id','hg_xzj_list.*','hg_xzj_daili.xzj_has_num','hg_xzj_list.xzj_max_num','hg_xzj_daili.xzj_fee')
            ->join('hg_xzj_list', 'hg_xzj_list.id', '=', 'hg_xzj_daili.xzj_list_id')
            ->whereIn('hg_xzj_daili.id',$condition)
            ->get();
    }

   //判断是否有非原厂
    public static function getCarOption($brand_id)
    {
        return DB::table('hg_xzj_daili')
            ->join('hg_xzj_list', 'hg_xzj_list.id', '=', 'hg_xzj_daili.xzj_list_id')
            ->join('goods_class_staple', 'hg_xzj_daili.staple_id', '=', 'goods_class_staple.staple_id')
            ->where('goods_class_staple.status', '<>', '2')
            ->where('hg_xzj_daili.car_brand', $brand_id)
            ->where('hg_xzj_daili.status', 0)
            ->where('hg_xzj_list.xzj_yc', 0)
            ->where('hg_xzj_list.status', '<>', 2)
            ->where('hg_xzj_daili.status', '<>', 2)
            ->count();
    }


    /**
     * @param $yc_list
     * @param $brand_id
     * 根据车型列出新选装件
     */
    public static function getNewXzjList($notIn_list, $brand_id, $type='yc')
    {
        $xzj_front  = 1;
        if($type == 'fyc') $xzj_front = 0;
        $id_list = [];
        foreach ($notIn_list as $yc)
        {
            $id_list[] = $yc->xzj_id;
        }
        $data = DB::table("hg_xzj_list")
            ->where('xzj_yc', 1)
            ->where('xzj_front', $xzj_front)
            ->where('car_brand', $brand_id)
            ->whereNotIn('id', $id_list)
            ->get();

        return $data;
    }



    //根据车型列出车的信息
    public static function gtListCarXzj($brand_id, $staple_id)
    {
        //前装
        $data['yc'] = DB::table("hg_xzj_list")
            ->where('xzj_yc', 1)
            ->where('xzj_front', 1)
            ->where('car_brand', $brand_id)
            ->get();

        //后装
        $data['fyc'] = DB::table('hg_xzj_list')
            ->where('xzj_yc', 1)
            ->where('xzj_front', 0)
            ->where('car_brand', $brand_id)
            ->get();
        //非原厂
        $data['xzjp'] = DB::table('hg_xzj_list')
            ->leftjoin('hg_xzj_daili', 'hg_xzj_list.id', '=', 'hg_xzj_daili.xzj_list_id')
            ->where('hg_xzj_list.xzj_yc', 0)
            ->where('hg_xzj_daili.staple_id', $staple_id)
            ->where('hg_xzj_list.car_brand', $brand_id)
            ->where('hg_xzj_daili.status', 0)
            ->get();

        return $data;

    }

    /**
     * 查看经销商下的代理列表
     * @param $dl_id
     * @return array
     */
    public static function getDealer($dl_id)
    {
        $data = self::leftjoin('goods_class', 'hg_daili_dealer.dl_brand_id', '=', 'goods_class.gc_id')
            ->where('hg_daili_dealer.dl_step', '10')
            ->whereIn('hg_daili_dealer.dl_status', [1, 2, 4])
            ->where('dl_id', $dl_id)
            ->select('hg_daili_dealer.d_shortname', 'goods_class.detail_img', 'hg_daili_dealer.id',
                'hg_daili_dealer.dl_brand_id', 'hg_daili_dealer.dl_status', 'hg_daili_dealer.d_id')
            ->get();
        if (count($data) > 0) {
            $data = $data->toArray();
        } else {
            $data = array();
        }
        return $data;
    }

    /**
     * 经销软删除
     * @param $daili_id 代理id
     * @param $dealer_id  经销商id
     * @return array
     */
    public static function deleteDealer($daili_id, $dealer_id)
    {
        $result = HgBaojia::leftjoin('hg_daili_dealer', 'hg_daili_dealer.id', '=', 'hg_baojia.daili_dealer_id')
            ->where('m_id', $daili_id)
            ->where('hg_daili_dealer.d_id', $dealer_id)
            ->whereNotIn('bj_status', [0, 3])
            ->count();
        if ($result > 0) {
            return array('error_code' => 0, 'msg' => '已有报价存在,不能删除!');
        } else {
            return self::updateDealer($daili_id, $dealer_id);
        }
    }

    /**
     * 经销商状态更新
     * @param $daili_id
     * @param $dealer_id
     * @return array
     */
    public static function updateDealer($daili_id, $dealer_id)
    {
        $data = self::where('dl_id', $daili_id)
            ->where('d_id', $dealer_id)
            ->update(['dl_status' => 3]);
        if (!$data) {
            return array('error_code' => 0, 'msg' => '删除失败');
        } else {
            return array('error_code' => 1, 'msg' => '删除成功');
        }
    }

    //竞争分析
    public static function getAnalysis($dealer_id, $dl_id)
    {
        $data['one'] = DB::table('hg_daili_dealer')
            ->join('hg_dealer', 'hg_daili_dealer.dl_competitor_dealer_id_1', '=', 'hg_dealer.d_id')
            ->where('hg_daili_dealer.dl_status', '<>', 3)
            ->where('hg_daili_dealer.d_id', $dealer_id)
            ->where('hg_daili_dealer.dl_id', $dl_id)
            ->first();
        $data['two'] = DB::table('hg_daili_dealer')
            ->join('hg_dealer', 'hg_daili_dealer.dl_competitor_dealer_id_2', '=', 'hg_dealer.d_id')
            ->where('hg_daili_dealer.dl_status', '<>', 3)
            ->where('hg_daili_dealer.d_id', $dealer_id)
            ->where('hg_daili_dealer.dl_id', $dl_id)
            ->first();
        return $data;
    }


    //递归获取下级名称
    public static function Ancestry($data, $pid)
    {
        static $ancestry = array();

        foreach ($data as $key => $value) {
            if ($value['gc_id'] == $pid) {
                self::Ancestry($data, $value['gc_parent_id']);
                $ancestry[$value['gc_id']] = $value['gc_name'];
            }
        }
        return $ancestry;
    }

    //判断经销商是否重复添加
    public static function repDealer($d_id, $dl_id)
    {
        return self::where('d_id', $d_id)->where('dl_id', $dl_id)->where('dl_status', '<>', 3)->get();

    }

    /**
     * 服务专员的读取
     * @param $dl_id 代理id
     * @param $dealer_id  经销商id
     * @return array
     */
    public static function getHgWaiter($dl_id, $dealer_id)
    {
        return self::join('hg_waiter', 'hg_daili_dealer.id', '=', 'hg_waiter.daili_dealer_id')
            ->where('hg_daili_dealer.dl_status', '<>', 3)
            ->where('hg_waiter.dealer_id', $dealer_id)
            ->where('hg_waiter.agent_id', $dl_id)
            ->get();
    }

    /**
     * 查询hgDiliDealer表中的id,关联使用
     * @param $daili_id
     * @param $dealer_id
     * @return mixed
     */
    public static function getDailiDealerId($daili_id, $dealer_id)
    {
        return self::where('dl_id', $daili_id)
            ->where('d_id', $dealer_id)
            ->where('dl_status', '<>', 3)
            ->pluck('id')
            ->first();
    }

    /**
     * 根据代理获取代理下面的经销商列表
     * @param 代理id $dl_id
     * @param 代理状态 $status 0为所有，其他的直接筛选
     * @return multitype:unknown
     */
    public static function getDealerByDaili($dl_id, $status = 0)
    {
        if ($status == 0) {//
            $data = DB::table('hg_daili_dealer')
                ->join('hg_dealer', 'hg_daili_dealer.d_id', '=', 'hg_dealer.d_id')
                ->join('goods_class', 'hg_daili_dealer.dl_brand_id', '=', 'goods_class.gc_id')
                ->where('hg_daili_dealer.dl_id', $dl_id)
                ->select('hg_dealer.*', 'hg_daili_dealer.d_shortname', 'hg_daili_dealer.dl_brand_id',
                    'hg_daili_dealer.id as daili_dealer_id', 'goods_class.gc_name')
                ->get();
        } else {
            $data = DB::table('hg_daili_dealer')
                ->join('hg_dealer', 'hg_daili_dealer.d_id', '=', 'hg_dealer.d_id')
                ->join('goods_class', 'hg_daili_dealer.dl_brand_id', '=', 'goods_class.gc_id')
                ->where('hg_daili_dealer.dl_id', $dl_id)
                ->where('hg_daili_dealer.dl_status', $status)
                ->select('hg_dealer.*', 'hg_daili_dealer.d_shortname', 'hg_daili_dealer.dl_brand_id',
                    'hg_daili_dealer.id as daili_dealer_id', 'goods_class.gc_name')
                ->get();
        }

        $dealerList = array();
        $area = config('area');
        $carbrand = config('car.0');
        if (count($data) > 0) {
            foreach ($data as $k => $v) {
                $v = get_object_vars($v);
                $v['place'] = $area[0][$v['d_sheng']]['name'] . '(' . $area[$v['d_sheng']][$v['d_shi']]['name'] . ')';
                $v['car_brand'] = @$carbrand[$v['dl_brand_id']]['gc_name'];
                $dealerList[] = $v;
            }
        }
        return $dealerList;
    }

    /**
     * 删除经销商 add by jerry
     * @param 代理经销商id $daili_dealer_id
     * @param 经销商id $dealer_id
     * @param 会员id $member_id
     * return
     */
    public static function realDeleteDealer($daili_dealer_id, $dealer_id, $member_id)
    {
        $daili_dealer = DB::table('hg_daili_dealer')
            ->where('id', $daili_dealer_id)
            ->where('dl_id', $member_id)
            ->where('d_id', $dealer_id)
            ->where('dl_status', 1)
            ->where('dl_step', '<', 10)
            ->get();
        if (!$daili_dealer) {//检查是否存在
            return array('error_code' => 1, 'message' => '抱歉，经销商删除失败！');
        }

        DB::beginTransaction();
        try {
            //删除主表
            $e1 = DB::table('hg_daili_dealer')
                ->where('id', $daili_dealer_id)
                ->where('dl_id', $member_id)
                ->where('d_id', $dealer_id)
                ->where('dl_status', 1)
                ->where('dl_step', '<', 10)
                ->delete();
            //删除工作日表
            $e2 = DB::table('hg_daili_dealer_workday')
                ->where('daili_dealer_id', $daili_dealer_id)
                ->delete();
            //删除服务专员
            $e3 = DB::table('hg_waiter')
                ->where('daili_dealer_id', $daili_dealer_id)
                ->delete();
            //删除保险
            $e4 = DB::table('hg_dealer_baoxian')
                ->where('daili_dealer_id', $daili_dealer_id)
                ->delete();
            //删除赠品
            $e5 = DB::table('hg_dealer_zengpin')
                ->where('daili_dealer_id', $daili_dealer_id)
                ->delete();
            //删除其他费用
            $e6 = DB::table('hg_dealer_other_price')
                ->where('daili_dealer_id', $daili_dealer_id)
                ->delete();
            //删除刷卡标准 补贴
            $e6 = DB::table('hg_dealer_standard')
                ->where('daili_id', $daili_dealer_id)
                ->delete();
        } catch (Exception $e) {
            DB::rollback();
            return array('error_code' => 1, 'message' => '抱歉，经销商删除失败！');
        }
        DB::commit();
        return array('error_code' => 0, 'message' => '经销商已删除成功~');
    }

}
