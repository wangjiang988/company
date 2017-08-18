<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HgDealerWorkDay extends Model
{
    protected $table = 'hg_daili_dealer_workday';

    public function scopeDays($query)
    {
        return $query->select('day_0','day_1','day_2','day_3','day_4','day_5','day_6')
            ->first()
            ->toArray();
    }
}
