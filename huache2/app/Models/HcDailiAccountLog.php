<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HcDailiAccountLog extends Model
{
    protected $table = 'hc_daili_account_log';

    protected $primaryKey = 'da_log_id';

    protected $guarded = ['da_log_id'];
}
