<?php namespace App\Models;

/**
 * 车型搜索属性
 *
 */

use Illuminate\Database\Eloquent\Model;

use Cache;

class HgCarAtt extends Model {

    protected $table = 'hg_search_att';

    public $timestamps = false;

    /**
     * 获取搜索属性
     */
    public static function getAtt() {
       if (Cache::has('Att'))
		{
		    return Cache::get('Att');
		}else
		{
			$att=self::all();
			Cache::put('Att', $att,1000);
			return $att;
		}
 
    }

}
