<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DB;

class HgOrderXzj extends Model
{
    protected $table = 'hc_order_xzj';

    protected $fillable = [
        'xzj_title', 'xzj_num', 'xzj_model', 'xzj_brand', 'xzj_guide_price', 'xzj_fee', 'xzj_type'
    ];

    /**
     * 复制数据到orderxzj表中
     * @param array $data
     * @param $order_id
     * @return array
     */
    public static function copyData(array $data, $order_id)
    {
        $temp = array_pluck($data, 'id');
        $result = HgDailiDealer::getOrderOption($temp);
        $types = self::where('order_id', $order_id)->get();
        $arr = [];
        if (!$result->isEmpty()) {
            foreach ($result as $key => $value) {
                if ($types->where('xzj_id', $value->xzj_daili_id)->isEmpty()) {
                    $type = 1;
                } else {
                    $type = 0;
                }
                $arr[] = [
                    'xzj_title' => $value->xzj_title,
                    'xzj_list_id' => $value->id,
                    'cs_serial' => $value->cs_serial,
                    'xzj_id' => $value->xzj_daili_id,
                    'xzj_num' => $data[$value->xzj_daili_id]['num'],
                    'xzj_model' => $value->xzj_model,
                    'xzj_brand' => $value->xzj_brand,
                    'xzj_guide_price' => $value->xzj_guide_price,
                    'xzj_fee' => $value->xzj_fee,
                    'xzj_type' => $data[$value->xzj_daili_id]['type'],
                    'order_id' => $order_id,
                    'color_type' => $type,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }
        }
        return $arr;
    }


    /**
     * @param $datas
     * 减少库存.
     */
    public function setReduceNum($datas)
    {
        foreach ($datas as $value) {
            HgXzjDaili::where('id', $value['id'])
                ->where('xzj_has_num', '>', 0)
                ->decrement('xzj_has_num', $value['num']);
        }
    }


    /**
     * @param $order_id
     * @return mixed
     */
    public function getGroupXzj($order_id)
    {
        return self::select(DB::raw('sum(xzj_num) as same_sun,xzj_id,xzj_title,
        xzj_model,xzj_brand,xzj_type'))
            ->where('order_id', $order_id)
            ->groupBy('xzj_id')
            ->get();
    }

    /**
     * @param $order_id
     * @return mixed
     * 读取客户选择的选装件列表详细信息
     */
    public function getOrderXzjLists($order_id)
    {
        return self::leftjoin('hg_xzj_daili', 'hc_order_xzj.xzj_id', '=', 'hg_xzj_daili.id')
            ->select(DB::raw('sum(xzj_num) as same_sun,car_hc_order_xzj.*,car_hg_xzj_daili.xzj_cs_serial'))
            ->where('hc_order_xzj.order_id', $order_id)
            ->groupBy('hc_order_xzj.xzj_id')
            ->get();
    }


    public function getGroupXzjFinish($order_id)
    {
        return self::select(DB::raw('sum(xzj_num) as same_sun,xzj_id,xzj_title,
        xzj_model,xzj_brand,xzj_type'))
            ->where('order_id', $order_id)
            ->where('is_install', '<>', 0)
            ->groupBy('xzj_id')
            ->get();
    }



    public static function getData($order_id, $xzj_id)
    {
        return $result = self::where('order_id', $order_id)
            ->where('xzj_id', $xzj_id)
            ->latest('created_at')
            ->first();
    }

    public static function Ancestry($order_id, $xzj_id, $num)
    {
        $result = self::getData($order_id, $xzj_id);
        while ($num > 0) {
            if ($result->xzj_num > $num) {
                $result->xzj_num = $result->xzj_num - $num;
                $result->save();
                break;
            }
            if ($result->xzj_num == $num) {
                $result->delete();
                break;
            }
            $num = $num - $result->xzj_num;
            $result->delete();
            self::Ancestry($order_id, $xzj_id, $num);
            break;
        }
    }

}
