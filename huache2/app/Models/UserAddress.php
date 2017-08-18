<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Common;

class UserAddress extends Model
{
    use Common;
    protected $table ='user_address';    
    protected $fillable = ['user_id','province','city','district','address','is_default','activated'];//可修改字段
    protected $primaryKey = 'address_id';
    protected $guarded = ['_token','_url'];//过滤post参数
    public $timestamps = false;
    //
}
