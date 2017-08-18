<?php namespace App\Models;

/**
 * 修改的车辆信息模型
 *
 * 
 */

use Illuminate\Database\Eloquent\Model;

use Cache;

class HgEditInfo extends Model {

    protected $table = 'hg_editinfo';

    protected $guarded = [];

    const LISTS = [
        'carinfo','xzj','zengpin','shangpai_file'
        ];

    public $timestamps = false;
    // 取得订单的修改过的信息
    public static function getEditInfo($order_num,$status=false)
    {
        if ($status) {
            $data = self::where('order_sn','=',$order_num)->where('status','=',$status)->orderBy('createat','desc')->first();
        }else{
            $data = self::where('order_sn','=',$order_num)->orderBy('createat','desc')->first();
        }
        if (!empty($data)) {
        $view['editcarinfo'] = unserialize($data->carinfo);
        $view['editxzj'] = unserialize($data->xzj);
        $view['editzengpin'] = unserialize($data->zengpin);
        $view['ziliao'] = unserialize($data->shangpai_file);
        $view['editCarModel'] = "Y";
        } else {
            $view['editCarModel'] = "N";
            $view['editcarinfo'] = false;
            $view['editxzj'] = false;
            $view['editzengpin'] = false;
            $view['ziliao'] = false;
        }
        return $view;
    }
    public static function insertNewModifyLog($param){
    	return self::insert($param);
    }


    //取出特需文件
    public static function getFeedfile($order_sn, $status = null)
    {
        $files = self::select('shangpai_file')->where('order_sn', $order_sn)
            ->where(function ($query) use ($status) {
                if ($status) {
                    $query->where('status', $status);
                }
            })->first();
        if ( ! is_null($files)) {
            $result = unserialize($files->shangpai_file);
        } else {
            $result = [];
        }
        return $result;
    }

    /**
     * @param $order_su
     *  取出特需修改文件
     * @return array
     */
    public static function getEditfile($order_su)
    {
        $data = static::where('order_sn', '=', $order_su)
            ->latest('createat')
            ->first(static::LISTS);
        if ( ! is_null($data)) {
            $result = $data->toArray();
            foreach ($result as $index => $item) {
                if ( ! empty($item)) {
                    $result[$index] = unserialize($item);
                }
            }
        } else {
            $result = [];
        }
        return $result;
    }

    public static function getComment($order_sn)
    {
        $data = static::where('order_sn', '=', $order_sn)
            ->latest('createat')
            ->first();
        if ( ! is_null($data)) {
            $result = $data->toArray();
            foreach ($result as $index => $item) {
                if ( ! empty($item) && in_array($index,static::LISTS)) {
                    $result[$index] = unserialize($item);
                }
            }
        } else {
            $result = [];
        }
        return $result;
    }
    
}
