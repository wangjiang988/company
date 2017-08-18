<?php namespace App\Models;

/**
 * 用户模型
 */
use Illuminate\Database\Eloquent\Model;
class HgLock extends Model {

    protected $table = 'lock';

    public $timestamps = false;
    
    
    /**
	 * 设置值
	 *
	 * @param mixed $key
	 * @param mixed $value
	 * @param int $ttl 默认五分钟
	 * @return bool
	 */
    public static function set($key, $value,  $ttl = 300){
    	$info = self::where(array('pid'=>$key))->first();
    	if ($info){
    		self::where(array('pid'=>$key))->update(array('pvalue'=>$value,'expiretime'=>time()+$ttl));
    	}else{
    		self::insert(array('pid'=>$key,'pvalue'=>$value,'expiretime'=>time()+$ttl));
    	}
    }
    /**
     * 取得值
     *
     * @param mixed $key
     * @param mixed $type
     * @return bool
     */
    public static function get($key){
    	$info = self::where(array('pid'=>$key))->first();
    	if ($info){
    		if($info->expiretime < time()){
    			self::rm($key);
    			return null;
    		}else{
    			return $info->pvalue;;
    		}
    	}else{
    		return null;
    	}
    }
    
    /**
     * 删除值
     *
     * @param mixed $key
     * @param mixed $type
     * @return bool
     */
    public static function rm($key){
    	return self::where(array('pid'=>$key))->delete();
    }
    /**
     * 
     * @param number $type 锁定类型
     * @param string $ip 锁定IP
     * @return string
     */
    public static function getPid($type,$ip,$member_id){
    	$typeKey=array('login'=>'1','reg'=>'2','forget'=>'3');
    	return sprintf("%u",ip2long($ip)).$typeKey[$type].$member_id;
    }
}
