<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\User;
use DB;

class HgOrder extends Model
{
    protected $table = 'hc_order';

    // public $timestamps = false;

    protected $guarded = [];

    public $lists = [ 290, 2911, 2912, 624,627, 628, 392, 637, 638, 391, 390,394, 634, 644, 647, 648, 506, 507, 99, 621, 631, 641];

    public static function getOrder($id, $user_id, $type)
    {
        return static::where(function ($query) use ($type, $user_id) {
            if ($type == 'seller') {
                $query->where('seller_id', $user_id);
            } elseif ($type == 'member') {
                $query->where('user_id', $user_id);
            }
        })
            // ->with('orderDealer','orderAttr','orderScope','orderGoodsClass','orderInfoprice')
            ->findOrFail($id);
    }

    /**
     * @param $id      订单id
     * @param $user_id 用户id
     * @param $status  订单主状态
     * @param $state   订单子状态
     *
     * @return mixed  用户类型
     */
    public static function checkOrder($id, $user_id, $status, $state, $type)
    {
        return static::where(function ($query) use ($type, $user_id) {
            if ($type == 'seller') {
                $query->where('seller_id', $user_id);
            } elseif ($type == 'member') {
                $query->where('user_id', $user_id);
            }
        })->where(function ($query) use ($state) {
            if ($state) {
                if (is_array($state)) {
                    $query->whereIn('order_state', $state);
                } else {
                    $query->where('order_state', $state);
                }
            }
        })->where(function ($query) use ($status) {
            if (is_array($status)) {
                $query->whereIn('order_status', $status);
            } else {
                $query->where('order_status', $status);
            }
        })->findOrFail($id);
    }

    /**
     * @param      $query
     * @param      $seller_id
     * @param null $conditions
     *
     * @return mixed
     */
    public function scopeLists($query, $seller_id, $type)
    {
        return $query->where('seller_id', $seller_id)->where(function ($query
        ) use ($type) {
            if ($type == 'actives') {
                $query->whereNotIn('order_state', $this->lists)
                    ->where('order_status','<>',1)
                    ->where('order_state','<>',200);
            } else {
                $query->whereIn('order_state', $this->lists);
            }
        });
    }

    /*
     *
     */
    public static function get_success_order_num_by_bjId($bj_id)
    {
        return self::where('bj_id', $bj_id)
            ->where('order_status', '>=', 2)
            ->count();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function orderAttr()
    {
        return $this->hasOne('App\Models\HgOrderAttr', 'order_id');
    }

    /**
     * @param $query
     * @param $order_sn
     * 根据订单号查订单信息
     *
     * @return mixed
     */
    public function scopeFindOrder($query, $order_sn)
    {
        return $query->where('order_sn', $order_sn);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function orderDealer()
    {
        return $this->hasOne('App\Models\HgDealer', 'd_id', 'dealer_id');
    }

    /**
     * @return mixed
     * 定义销售范围
     */
    public function orderAreas()
    {
        return $this->hasMany('App\Models\HgBaojiaArea', 'bj_id', 'bj_id')
            ->select('city');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderServer()
    {
        return $this->hasMany('App\Models\HgBaojiaZengpin', 'bj_id', 'bj_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderColors()
    {
        return $this->hasMany('App\Models\HgCarInfo', 'gc_id', 'brand_id')
            ->select('name', 'value')
            ->whereIn('name', ['interior_color', 'body_color']);

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function orderBaojia()
    {
        return $this->hasOne('App\Models\HgBaojia', 'bj_id', 'bj_id');
    }

    /**
     * @param       $order_sn
     * @param array $data
     *
     * @return mixed
     */
    public static function updateStatus($order_sn, array $data)
    {
        return static::whereOrderSn($order_sn)->update($data);
    }


    public function orderPrice()
    {
        return $this->hasOne(HgPrice::class, 'id', 'bj_id');
    }

    /**
     * @param $seller_id
     * 订单总数统计
     *
     * @return mixed
     */
    public function getCount($seller_id)
    {
        $field = implode(",", $this->lists);
        return DB::select("SELECT count(IF (order_state in ({$field}),id,NULL)) AS finishs_sum,count(IF (order_state not in ({$field}) and (order_state <> 200) and (order_status <> 1),id,NULL)) AS actives_sum FROM car_hc_order where seller_id = :seller_id", ['seller_id' => $seller_id]);
    }

    /**
     * @param $value
     *
     * @return bool|string
     */
    public function getCreatedAtAttribute($value)
    {
        return date('Y年m月d日', strtotime($value));
    }

    /**
     * @param $value
     *
     * @return false|string
     * Y-m-d H:i:s 格式时间
     */
    public function getCreateTime($value)
    {
        return date('Y-m-d H:i:s', strtotime($this->attributes[$value]));
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function getHwachePriceAttribute($value)
    {
        return number_format($value, 2);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function orderScope()
    {
        return $this->hasOne(HgScope::class, 'dealer_id', 'daili_dealer_id')
            ->select('dealer_id', 'province1_name', 'province2_name',
                'province3_name', 'area1_name', 'area2_name',
                'area3_name');
    }


    /**
     * @return mixed
     * 指导价
     */
    public function orderInfoprice()
    {
        return $this->hasOne(HgCarInfo::class, 'gc_id', 'brand_id')
            ->select('gc_id', 'name', 'value')
            ->where('name', 'zhidaojia');
    }

    /**
     * @return mixed
     */
    public function orderGoodsClass()
    {
        return $this->hasOne(HgGoodsClass::class, 'gc_id', 'brand_id')
            ->select('gc_id', 'vehicle_model');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function orderUsers()
    {
       // return $this->hasOne(User::class, 'id', 'user_id');
        return $this->belongsTo(User::class,'user_id','id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function orderDate()
    {
        return $this->hasOne(HgOrderDate::class, 'order_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderEditinfo()
    {
        return $this->hasMany(HgEditInfo::class, 'order_sn', 'order_sn');
    }

    public function orderDealerWorkday()
    {
        return $this->hasOne(HgDealerWorkDay::class, 'daili_dealer_id',
            'daili_dealer_id');
    }

    public function orderMember()
    {
        return $this->belongsTo(HgUser::class, 'seller_id', 'member_id');
    }

    public function orderXzj()
    {
        return $this->hasMany(HgOrderXzj::class, 'order_id');
    }

    public function orderXzjEdit()
    {
        return $this->hasMany(HgOrderXzjEdit::class, 'order_id');
    }

    public function getXzjNum($xzj_id)
    {
        $data = HgOrderXzj::select(DB::raw('sum(xzj_num) as sum,xzj_id'))
            ->where('xzj_id', $xzj_id)
            ->first();
        if (is_null($data->sum)) {
            $select_num = 0;
        } else {
            $select_num = $data->sum;
        }
        return $select_num;
    }

    public function orderAppoint()
    {
        return $this->hasOne(HcOrderAppoinData::class, 'order_id');
    }


    public function orderWaiter()
    {
        return $this->hasMany(HgWaiter::class, 'agent_id', 'seller_id')
            ->where('dealer_id', $this->dealer_id);
    }

    public function orderuserextion()
    {
        return $this->hasOne(UserExtension::class, 'user_id', 'user_id');
    }

    public function orderComment()
    {
        return $this->hasOne(HcCartEvaluate::class, 'order_id', 'id');
    }

    public function getCarinfo($seller, $state, $type, $name = null)
    {
        return self::select('gc_name', 'bj_id', 'brand_id')
            ->where('seller_id', $seller)
            ->where(function ($query) use ($state) {
                if ($state == 'active') {
                    $query->whereNotIn('order_state', $this->lists)
                        ->where('order_status','<>',1)
                        ->where('order_state','<>',200);
                } else {
                    $query->whereIn('order_state', $this->lists);
                }
            })->where(function ($query) use ($type, $name) {
                if ($type == 2) {
                    $query->whereRaw("substring_index(gc_name, ' &gt;', 1) LIKE '$name'");
                }
                if ($type == 3) {
                    $query->whereRaw("substring_index(substring_index(gc_name, ' &gt;', 2),' &gt;',-1) LIKE '$name'");
                }
            })
            ->groupBy('brand_id')
            ->get();
    }


    public function getOrderSerach($map, $type, $times = [], $state=[])
    {
        return self::leftjoin('hc_order_attr', 'hc_order_attr.order_id', '=', 'hc_order.id')
        ->leftjoin('hg_dealer', 'hg_dealer.d_id', '=',
            'hc_order.dealer_id')
            ->leftjoin('users', 'users.id', '=', 'hc_order.user_id')
            ->leftjoin('hc_order_progress_status', 'hc_order.order_state', '=',
                'hc_order_progress_status.sub_status')
            ->where(function ($query) use ($type, $times, $state) {
                if ($type == 'active') {
                    if ($state) {
                        $query->whereIn('order_state', $state);
                    } else {
                        $query->whereNotIn('order_state', $this->lists);
                    }
                    $query->where('order_status', '<>', 1)
                        ->where('order_state', '<>', 200);
                } else {
                    $query->where(function ($query) use ($times) {
                        $query->whereIn('order_state', $this->lists);
                    });
                    if (array_filter($times)) {
                        $query->whereBetween('hc_order.updated_at', $times);
                    }
                }
            })->where(function ($query) use ($map) {
                foreach ($map as $key => $value) {
                    if (is_array($value)) {
                        $query->where($key, $value[0], $value[1]);
                    } elseif($key == 'car_series') {
                        $query->whereRaw("substring_index(substring_index(gc_name, ' &gt;', 2),' &gt;',-1) LIKE '$value'");
                    } else {
                        $query->where($key, $value);
                    }
                }
            })->paginate(10);
    }

    public function orderinfo()
    {
        return $this->hasOne(HcOrderInfo::class, 'id');
    }

    public function payDepositLog()
    {
        return $this->hasOne(HcPayDeposit::class, 'order_id');
    }

    public function orderLog()
    {
        return $this->hasMany(HgCartLog::class, 'order_id');
    }

    public function addLog($type, $status, $state, $msg)
    {
        $data = [
            'order_status' => $status,
            'order_state'  => $state,
            'msg'          => $msg,
            'ip'           => \Request::ip()
        ];
        if ($type === 'member') {
            $data['user_id'] = \Auth::id();
            $data['user_type'] = 'member';
        } else {
            $data['user_id'] = session('user.member_id');
            $data['user_type'] = 'selller';
        }
        $this->orderLog()->Create($data);
    }

    public function orderXzjGift()
    {
        return $this->hasMany(HgOrderXzjGift::class, 'order_id');
    }

    public function orderOtherPrice()
    {
        return $this->hasMany(HgBaojiaOtherPrice::class, 'bj_id', 'bj_id');
    }

    public function orderConciliation()
    {
        return $this->hasManyThrough(HcOrderConciliationConsult::class,
            HcOrderConciliation::class, 'order_id', 'ocid', 'id');
    }

    public function orderConciliaArbitrate()
    {
        return $this->hasManyThrough(HcOrderConciliationArbitrate::class,
            HcOrderConciliation::class, 'order_id', 'ocid', 'id');
    }

    //取一条结算数据
    public function limitConciliation($type=null)
    {
        if ($type == 2) {
            return $this->orderConciliation()->latest()->first();
        } else {
            return $this->orderConciliaArbitrate()->where(function ($query) use ($type) {
                if ($type) {
                    $query->where('arbitrate_result', $type);
                }
            })->latest()->first();
        }

    }

    public function orderZhidaojia()
    {
        return $this->hasOne(HgCarInfo::class, 'gc_id', 'brand_id')
            ->where('name', 'zhidaojia');
    }

    public function orderjiaxinbao()
    {
        return $this->hasMany(HcOrderJiaXinBao::class, 'order_id');
    }

    public function orderAccount()
    {
        return $this->hasMany(HcAccountLog::class, 'order_id');
    }

    public function orderStatus()
    {
        return $this->hasOne(HcOrderProgressStatu::class, 'sub_status',
            'order_state');
    }

    public function area()
    {
        return $this->belongsTo(Area::class,'shangpai_area','area_id');
    }

    public function Verify()
    {
       return $this->belongsTo(UserExtension::class,'user_id','user_id');
    }

    public function isVerify()
    {
        if ( ! is_null($this->Verify()->first())) {
            return ($this->Verify->is_id_verify == 1) ? true : false;
        }
        return false;
    }

    public function getOrderJp()
    {
        $orders = static::where('xzjp_steps', 1)
            ->whereIn('order_status', [3, 4])
            ->where('xzjp_updated_at', '<=', Carbon::now())
            ->pluck('id');
        if ( ! $orders->isEmpty()) {
            $result = $orders->toArray();
        } else {
            $result = [];
        }
        return $result;
    }

    public function batchUpdate($field, $values_one, $values_two, $conditions, $values)
    {
        $table = 'car_' . $this->getTable();
        $sql   = 'update ' . $table . ' set '. $values_one .' = case ' .$field;
        foreach ($conditions as $key => $condition) {
            $sql .= ' when ' . $condition . ' then ?';
        }
        $sql .= ' end,' . $values_two . ' = Case ' .$field;
        foreach ($conditions as $key => $condition) {
            $sql .= ' when ' . $condition . ' then ?';
        }
        $sql .= ' end where id in (' . implode(',', $conditions) . ')';
        return \DB::update($sql, $values);
    }

}
