<?php namespace App\Models;

/**
 * 操作日志 记录
 *
 * 
 */

use Illuminate\Database\Eloquent\Model;


class HgLog extends Model {

    protected $table = 'hg_log';

    public $timestamps = false;
    
    /**
     * 
     * @param 操作者ID $member_id
     * @param 操作者类型 $type 1会员 2经销商 3系统管理者
     * @param 模块名称 $module_name
     * @param 日志内容 $note
     */
    public static function addLog($member_id,$type,$module_name,$note){
    	$data = array(
    			"member_id"=>$member_id,
    			'type'=>$type,
    			'module_name'=>$module_name,
    			'note'=>$note,
    			'log_date'=>date('Y-m-d H:i:s'),
    			);
    	return self::insertGetId($data);
    }
    
}
