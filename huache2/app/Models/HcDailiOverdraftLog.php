<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HcDailiOverdraftLog extends Model
{
    protected $table = 'hc_daili_overdraft_log';

    protected $primaryKey = 'do_log_id';

    protected $guarded = ['do_log_id'];
}
