<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HgOrderDate extends Model
{
    protected $table = 'hc_order_date';

    protected $primaryKey = 'order_id';

    protected $fillable = [
        'jiaoche_times','status','jiaoche_at'
    ];

    public $timestamps =false;

    public function getJiaocheTimesAttribute($value)
    {
        return unserialize($value);
    }

}