<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HcVoucher extends Model
{
    protected $table = 'hc_vouchers';

    public function belongsToVouchergroup()
    {
        return $this->belongsto('App\Models\HcVoucherGroup', 'group_id', 'id');
    }

    public function group()
    {
        return $this->belongsto('App\Models\HcVoucherGroup', 'group_id', 'id');
    }

    public function hasOneLog()
    {
        return $this->hasOne('App\Models\HcVoucherLog', 'vid', 'id');
    }

    public static  function get_vouchers_by_status($user_id, $where)
    {
        if(isset($where['group_type_id']) && $where['group_type_id']>=0){
            $list =  self::with('group')->where('user_id',$user_id)
                ->leftJoin('hc_vouchers_group','hc_vouchers.group_id','=','hc_vouchers_group.id')
                ->select('hc_vouchers.*')
                ->where('hc_vouchers.status', $where['status'])
                ->where('hc_vouchers_group.type', $where['group_type_id'])
                ->paginate(10);
        }else{
            $list =  self::with('group')->where('user_id',$user_id)
                ->select('hc_vouchers.*')
                ->where('hc_vouchers.status', $where['status'])
                ->paginate(10);
        }
        return $list;
    }

    /**
     * @param $user_id
     * @param  $where 代金券
     * 根据用户id，代金券状态 获取代金券
     */
    public function get_vouchers_by_user_id($user_id,$where = [])
    {
        $list =  self::get_vouchers_by_status($user_id, $where);
        //对数据进行封装处理
        if($list->items())
        {
            foreach ($list->items() as $item)
            {

                $item->typeName = $this->_get_group_type_name($item->group->type);
                if($item->group->brand_id == 0)
                    $item->category   = "全品类";
                else{
                    $brand = HgGoodsClass::getCarBase($item->group->brand_id);
                    if($brand)
                        $item->category   = '仅可购买'. $brand.'商品';
                    else{
                        $item->category   = '未知品类';
                    }
                }
                $item->life_start_time =  Carbon::parse($item->life_start_time)->toDateString();
                $item->life_end_time   =  Carbon::parse($item->life_end_time)->toDateString();
            }
        }

        return $list;
    }

    /**
     * 查询用户可用的优惠券列表和默认优惠券
     * @param $user_id
     * @param int $pay_type 0诚意金，1担保金
     * @return array
     */
    public static function getVoucherByUserId($user_id, $pay_type = 0)
    {
        //todo 车型、城市过滤条件未使用
        $list = DB::table('hc_vouchers')
            ->join('hc_vouchers_group', 'group_id', '=', 'hc_vouchers_group.id')
            ->join('hc_vouchers_release', 'release_id', '=', 'hc_vouchers_release.id')
            ->where(['hc_vouchers.user_id' => $user_id, 'hc_vouchers.status' => 1])
            ->select('hc_vouchers.*', 'hc_vouchers_group.type', 'hc_vouchers_group.brand_id', 'hc_vouchers_group.series_id', 'hc_vouchers_group.model_id', 'hc_vouchers_release.province', 'hc_vouchers_release.city')
            ->orderBy('money', 'desc')
            ->get();

        //可用优惠券列表
        $vouchers = $list->where('use', $pay_type);
        $vouchers_list = $vouchers_default = [];
        if ($list) {
            foreach ($vouchers as $k => $e) {
                $vouchers_list[] = [
                    'id' => $e->id,
                    'name' => '￥' . number_format($e->money, 2) . ' 券编码尾号：' . substr($e->voucher_sn, -4),
                    'array' => ["￥" . number_format($e->money, 2), $e->type ? "通用券" : "品类券", date("Y-m-d", strtotime($e->life_end_time))]
                ];
                if ($k == 0) {
                    $vouchers_default = [
                        'id' => $e->id, 'price' => "￥" . number_format($e->money, 2),
                        'type' => $e->type ? "通用券" : "品类券",
                        'time' => date("Y-m-d", strtotime($e->life_end_time)),
                        'sn' => substr($e->voucher_sn, -4)
                    ];
                }
            }
        }

        //可用优惠券最大金额
        $vouchers_max_price = $vouchers->max('money') ?: 0;

        return [
            'voucher' => $vouchers_list,
            'vouchers_default' => $vouchers_default,
            'vouchers_max_price' => $vouchers_max_price
        ];
    }

    //根据代金券组的类型 翻成文字
    private function _get_group_type_name($type = 0){
        if($type == 0 )
            return  '通用券';
        elseif($type == 1 ){
            return '品类卷';
        }else{
            return '未知类型';
        }
    }

}

