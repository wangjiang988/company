<?php namespace App\Models;

/**
 * 用户模型
 */
use Illuminate\Database\Eloquent\Model;
class HgUser extends Model {

    protected $table = 'member';

    public $timestamps = false;
    // 取得用户信息
    public static function getMember($member_id,$verify=null)
    {
        if($verify=='Y'){
            return self::leftJoin('member_verify', 'member.member_id', '=', 'member_verify.member_id')
                        ->where('member.member_id','=',$member_id)->first();
        }else{
            return self::where('member_id','=',$member_id)->first();
        }

    }
}
