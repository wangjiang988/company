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
                ->select('hg_daili_dealer_workday.id as work_time_id','hg_daili_dealer_workday.*','hg_daili_dealer.*')
                ->first();
    }


    /**
     * 经销商
     */
    public function  dealer()
    {
        return $this->belongsTo(HgDealer::class,'dealer_id','d_id');
    }

    /**
     * 代理商
     */
    public function  daili()
    {
        return $this->belongsTo(HgUser::class,'daili_id','member_id');
    }


    /**
     * 检查报价对象是否在工作时间内
     * @param $id
     *
     */
    public function _check_rest_time(HgDelaerWorkday $work_time){
        $now        =   Carbon::now();
        $today      =   Carbon::today();
        $hour       = $now->hour;

        $hwache_am_start = 9;
        $hwache_am_end   = 12;
        $hwache_pm_start = 13;
        $hwache_pm_end   = 17;

        $is_rest_time  = true;
        if(!$work_time){  //没有工作时间记录  使用华车时间

            if(($hour>=$hwache_am_start && $hour<=$hwache_am_end) || ($hour>=$hwache_pm_start && $hour<=$hwache_pm_end))
            {
                $is_rest_time  = false;
            }else{
                $is_rest_time  = true;
            }
        }else{
            //查看是否工作日
            $is_rest_day1 = $today->between(Carbon::parse($work_time['rest_1_start']),Carbon::parse($work_time['rest_1_end']));
            $is_rest_day2 = $today->between(Carbon::parse($work_time['rest_2_start']),Carbon::parse($work_time['rest_2_end']));

            if($is_rest_day1 || $is_rest_day2)  return true;

            $day_of_week        =  $today->dayOfWeek;
            $work_time_array  = $work_time->toArray();
            if(!$work_time_array['day_'.$day_of_week])  return true;  //非工作日

            //查看是否工作时段
            if(($hour>=$work_time->am_start && $hour<=$work_time->am_end) || ($hour>=$work_time->pm_start && $hour<=$work_time->pm_end))
            {
                $is_rest_time  = false;
            }else{
                $is_rest_time  = true;
            }

        }
        return $is_rest_time;
    }




}