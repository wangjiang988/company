<?php namespace App\Models;

/**
 * 客户提交特殊文件模型
 *
 * 
 */

use Illuminate\Database\Eloquent\Model;


class HgTellUs extends Model {

    protected $table = 'hg_tellus';

    public $timestamps = false;
    
    /**
     * 获取用户提交的特殊文件
     * @param  $member_id
     */
    public static function getAllFileByUser($member_id){
    	return self::where('member_id','=',$member_id)->get();
    }
    
    /**
     * 获取用户提交的特殊文件
     * @param  $member_id
     */
    public static function getOneFileByUser($id,$member_id){
    	return self::where('member_id','=',$member_id)
			    	->where('id','=',$id)
			    	->first();
    }
    
}
