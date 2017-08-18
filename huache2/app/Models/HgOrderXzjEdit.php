<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DB;

class HgOrderXzjEdit extends Model
{
    protected $table = 'hc_order_xzj_edit';

    public $timestamps = false;

    public function orderXzjs()
    {
        return $this->hasMany(HgOrderXzj::class,'order_id','order_id');
    }

    /**
     * @param $order_id
     * @param array $data
     */
    public static function setEditData($order_id, array $data)
    {
        if (count($data)) {
            foreach ($data as $key => $value) {
                $result = HgOrderXzj::select(DB::raw('sum(xzj_num) as same_sum,xzj_id,xzj_list_id'))
                    ->where('order_id', $order_id)
                    ->where('xzj_id', $value['id'])
                    ->groupBy('xzj_id')
                    ->first();
                if ($value['num'] != $result->same_sum) {
                    $arr[] = [
                        'xzj_list_id' => $result->xzj_list_id,
                        'old_num' => $result->same_sum,
                        'xzj_id' => $value['id'],
                        'edit_num' => $value['num'],
                        'order_id' => $order_id,
                        'created_at' => Carbon::now()
                    ];
                }
            }
        }
        self::insert($arr);
    }


    /**
     * @param $order_id
     * @return mixed
     */
    public static function diffXzjEdit($order_id)
    {
        return self::join('hc_order_xzj', function ($join) {
            $join->on('hc_order_xzj_edit.xzj_id', '=', 'hc_order_xzj.xzj_id');
        })
            ->select(DB::raw('sum(xzj_num) as same_num,
            car_hc_order_xzj_edit.xzj_id,xzj_title,
        xzj_model,xzj_brand,xzj_type,edit_num,car_hc_order_xzj_edit.created_at,is_install,car_hc_order_xzj_edit.old_num'))
            ->where('hc_order_xzj.order_id', $order_id)
            ->where('hc_order_xzj_edit.order_id', $order_id)
            ->where('hc_order_xzj_edit.is_install', '0')
            ->groupBy('hc_order_xzj.xzj_id')
            ->get();
    }

    /**
     * @param $order_id
     * @return mixed
     */
    public static function getXzjEditLog($order_id)
    {
        return self::join('hg_xzj_daili', 'hg_xzj_daili.id', '=', 'hc_order_xzj_edit.xzj_id')
            ->leftjoin('hg_xzj_list', 'hg_xzj_daili.xzj_list_id', '=', 'hg_xzj_list.id')
            ->select('hg_xzj_list.xzj_title', 'hg_xzj_list.xzj_brand', 'hc_order_xzj_edit.*')
            ->where('order_id', $order_id)
            ->get();
    }


    /**
     * @param $order_id
     * @return mixed
     */
    public static function getDealerXzj($order_id)
    {
        $shares = DB::table('hc_order_xzj_edit')
            ->join('hg_xzj_daili', 'hc_order_xzj_edit.xzj_id', '=', 'hg_xzj_daili.id')
            ->join('hc_order_xzj', 'hc_order_xzj_edit.xzj_id', '=', 'hc_order_xzj.xzj_id')
            ->select(DB::raw('sum(xzj_num) as same_num,
            car_hc_order_xzj_edit.xzj_id,car_hc_order_xzj.xzj_title,
        car_hc_order_xzj.xzj_model,car_hc_order_xzj.xzj_brand,xzj_type,edit_num,car_hg_xzj_daili.xzj_cs_serial,car_hg_xzj_daili.xzj_has_num,car_hc_order_xzj.xzj_fee,car_hc_order_xzj.xzj_guide_price'))
            ->where('hc_order_xzj_edit.order_id', $order_id)
            ->where('hc_order_xzj.order_id', $order_id)
            ->where('hc_order_xzj_edit.is_install',0)
            ->groupBy('hc_order_xzj.xzj_id')
            ->get();
        return $shares;
    }

    public function batchUpdate($conditions_field, $values_field, $conditions_xzj, $values)
    {
        $table = 'car_' . $this->getTable();
        $sql   = 'update ' . $table . ' set '. $values_field .' = case ' .$conditions_field;
        foreach ($conditions_xzj as $key => $condition) {
            $sql .= ' when ' . $condition . ' then ?';
        }
        $sql .= ' end where id in (' . implode(',', $conditions_xzj) . ')';
        return \DB::update($sql, $values);
    }

}
