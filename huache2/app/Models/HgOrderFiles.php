<?php namespace App\Models;

/**
 * 订单中客户需要提供的文件资料模型
 *
 */

use Illuminate\Database\Eloquent\Model;
use Cache;

class HgOrderFiles extends Model {

    protected $table = 'hg_order_files';
    protected $primaryKey = 'id';

    public static function getOrderFiles($order_num,$isself=1)
    {
        $cacheName = 'orderfile'.$order_num;
        if(!Cache::has($cacheName)) {
            $cacheData = self::where('order_num','=',$order_num)->where('isself','=',$isself)->where('num','>',0)->get()->toArray();
            if(!empty($cacheData) && !config('app.debug')) {
                Cache::put($cacheName, $cacheData, config('app.cache_time'));
            }
        }else{

            $cacheData = Cache::get($cacheName);
        }
        return $cacheData;
    }
    // 取得某个项目需要的文件资料
    public static function getFile($order_num,$cateid,$isself=1)
    {
      $cacheName = 'ordertoubaofile'.$cateid.$order_num;
        if(!Cache::has($cacheName)) {
            $cacheData = self::where('order_num','=',$order_num)->where('isself','=',$isself)->where('num','>',0)->where('cate_id','=',$cateid)->get()->toArray();
            if(!empty($cacheData) && !config('app.debug')) {
                Cache::put($cacheName, $cacheData, config('app.cache_time'));
            }
        }else{

            $cacheData = Cache::get($cacheName);
        }
        return $cacheData;
    }
    // 按场合显示文件,$order_num 订单号，cateids  要查询的cateid
    public static function getByCate($order_num,$ids=array())
    {
       $files1 = array();
       $cate=self::select('cate_id','cate')->where('order_num','=',$order_num)->distinct()->get();
       if (empty($ids) || !is_array($ids)) {
          foreach ($cate as $key => $value) {
               $files1[$value->cate]=HgOrderFiles::where('order_num','=',$order_num)->where('cate_id','=',$value->cate_id)->where('isself','=',1)->get();
           }
       }else{
          foreach ($cate as $key => $value) {
               $files1[$value->cate]=HgOrderFiles::where('cate_id','=',$value->cate_id)->whereIn('id',$ids)->get();
           }
       }
       return array_filter($files1);
    }
    // 按文件名显示,$order_num 订单号，cateids  要查询的cateid
    public static function getByName($order_num,$ids=array())
    {
       $files2 = array();
       if (empty($ids) || !is_array($ids)) {
          $filenames=HgOrderFiles::select('title')->where('isself','=',1)->where('order_num','=',$order_num)->distinct()->get();
          foreach ($filenames as $key => $value) {
            // 文件数量
               $file_count=HgOrderFiles::where('order_num','=',$order_num)->where('isself','=',1)->where('title','=',$value->title)->sum('num');
               $files2[$key]['title']=$value->title;
               $files2[$key]['num']=$file_count;
               // 文件场合
               $cate=HgOrderFiles::where('order_num','=',$order_num)->where('isself','=',1)->where('title','=',$value->title)->pluck('cate')->toArray();
               
               $files2[$key]['cate']=implode(',',$cate);
           }

       }else{
          $filenames=self::select('title')->whereIn('id',$ids)->distinct()->get();
            foreach ($filenames as $key => $value) {
            // 文件数量
               $file_count=HgOrderFiles::where('title','=',$value->title)->whereIn('id',$ids)->sum('num');
               $files2[$key]['title']=$value->title;
               $files2[$key]['num']=$file_count;
               // 文件场合
               $cate=HgOrderFiles::where('title','=',$value->title)->whereIn('id',$ids)->pluck('cate')->toArray();
               
               $files2[$key]['cate']=implode(',',$cate);
           }

       }

       
       return $files2;
    }
    // 按id查询
    public static function getByids($ids)
    {
      if (!is_array($ids)) return array();
      return self::whereIn('id',$ids)->get();
    }
}
