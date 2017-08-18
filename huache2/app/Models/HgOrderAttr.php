<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HgOrderAttr extends Model
{
    protected $table = 'hc_order_attr';
    protected $primaryKey = 'order_id';
    protected $fillable = [
        'order_id',
        'shenfen',
        'file_comment',
        'buytype',
        'license_tag',
        'cart_color',
        'show_status',
        'shangpai_status',
        'created_at',
        'new_file_comment',
        'or_contact',
        'non_xzj_list',
        'car_brand'
    ];

    public function attrOrder()
    {
        return $this->belongsTo(HgOrder::class,'order_id');
    }

    public function getNewFileCommentAttribute($value)
    {
        return ($value)?implode('、',array_pluck(unserialize($value),0)):'';
    }


    //所有订单品牌列表
    public function getBrand($type, $seller, $field)
    {
        return self::whereHas('attrOrder', function ($query) use ($type, $seller, $field) {
            $query->where('seller_id',$seller);
                if ($type == 'actives') {
                    $query->whereNotIn('order_state', $field)
                        ->where('order_status','<>',1)
                        ->where('order_state','<>',200);
                } else {
                    $query->whereIn('order_state', $field);
                }
            })->groupBy('car_brand')->get(['car_brand']);
    }

}
