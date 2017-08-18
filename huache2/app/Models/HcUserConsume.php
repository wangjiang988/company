<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HcUserConsume extends Model
{
    protected $table = 'hc_user_consume';

    protected $primaryKey = 'cid';

    protected $guarded = ['cid'];
}
