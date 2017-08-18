<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    //表名
    protected $table = 'hc_dealer_delivery';


    protected $fillable = ['file_number', 'delivery_company_name', 'delivery_number', 'member_id', 'created_at', 'updated_at'];
}
