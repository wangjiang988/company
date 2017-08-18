<?php
/**
 * Created by PhpStorm.
 * User: fanwuyang
 * Date: 2016/7/20
 * Time: 15:33
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 经销商工作日数据模型
 *
 */

class HgDelaerWorkday extends Model
{
    /**
     * 根据代理和经销商获得工作日的情况
     * @param  $dealer_id
     * @param  $dl_id
     * @return array:
     */
    protected $table = 'hg_daili_dealer_workday';

    public $timestamps = false;

    public static function getWorkTime($dl_id,$dealer_id)
    {
        return self::where('daili_id',$dl_id)
                ->join('hg_daili_dealer','hg_daili_dealer_workday.daili_dealer_id','=','hg_daili_dealer.id')
                ->where('hg_daili_dealer.dl_status','<>',3)
                ->where('dealer_id',$dealer_id)
                ->first();
    }


}