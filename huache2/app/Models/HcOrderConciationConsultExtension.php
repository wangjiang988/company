<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class HcOrderConciationConsultExtension extends Model
{
    protected $table = 'hc_order_conciation_consult_extension';

    public $timestamps = false;

    public static function updateTmie($cond)
    {
        static::where($cond)->update(['updated_at' => Carbon::now()]);
    }
}
