<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HcCartEvaluate extends Model
{
    protected $table = 'hc_cart_evaluate';

    protected $fillable = ['hwache_service','seller_service','evaluate','buy_id'];
}
