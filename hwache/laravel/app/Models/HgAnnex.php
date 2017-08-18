<?php namespace App\Models;

/**
 * 随车资料模型
 *
 * 
 */

use Illuminate\Database\Eloquent\Model;

use Cache;

class HgAnnex extends Model {

    protected $table = 'hg_annex';

    public $timestamps = false;
    // 取得随车资料的标题组合成字符串输出
    public static function getTitle($array)
    {
    	if(!is_array($array) || empty($array)) return '';
    	$array=array_map("intval",$array);
    	$titles=self::whereIn('id', $array)->lists('title')->toArray();
        return implode(' , ', $titles);
    	/*$title='';
    	foreach ($titles as $key => $value) {
    		$title.=$value->title.' , ';
    	}
    	return substr($title, 0,-2);*/
    }
    // 取得随车资料的记录
    public static function getSuiche($array)
    {
    	if(!is_array($array) || empty($array)) return '';
    	$array=array_map("intval",$array);
    	return self::whereIn('id',$array)->get();
    }
    /**
     * 
     * @param 车型id $car_brand
     * @param 代理id  $daili_id
     * @param 经销商id $dealer_id
     * 
     */
    
    public static function getSuicheByCar($car_brand){
    	$list1 =  self::where('c_id',$car_brand)->where('public',0);//不通用的随车工具
    	$list2 =  self::where('public',1)->union($list1)->get();// 通用+不通用的随车工具
    	return $list2;
    }

    /**
     * @param $car_brand
     * @return mixed
     * 随车文件和工具的处理
     */
    public static function getAnnex($car_brand)
    {
        $results = self::getSuicheByCar($car_brand)->toArray();
        foreach ($results as $result) {
            if ($result['type'] == '文件资料') {
                $lists['files'][] = $result['title'];
            }
            if ($result['type'] == '随车工具') {
                $lists['tools'][] = $result['title'];
            }
        }
        return $lists;

    }
}
