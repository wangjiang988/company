<?php namespace App\Models;

/**
 * 交车信息相不相符模型
 *
 * 
 */

use Illuminate\Database\Eloquent\Model;

use Cache;

class HgVerify extends Model {

    protected $table = 'hg_verify';

    public $timestamps = false;
    
}
