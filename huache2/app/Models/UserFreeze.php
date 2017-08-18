<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Common;
use Illuminate\Support\Facades\DB;

class UserFreeze extends Model
{
    use Common;
    //
    protected $table = 'user_freeze';
    protected $primaryKey = 'id';
    protected $fillable =['user_id','value','name','type','is_mobile','is_email','status','activated','created_at','updated_at','click_num','validity_time'];
    protected $guarded = ['id','_token','_url'];
    public $timestamps = false;

    public function delFreeze($value,$frType='fr')
    {
        $user_id = $this->getUserId($value);
        $type = is_phone($value) ? 'mobile' : 'email';
        if($type =='mobile'){
            $map['is_mobile'] = 1;
        }else{
            $map['is_email'] = 1;
        }
        $isCheck = self::map(['user_id'=>$user_id,'value'=>$value,'type'=>$frType])->count();
        if($isCheck){
            $data['status']     = 0;
            $data['click_num']  = 0;
            $data['created_at'] = 0;
            $data['updated_at'] = 0;
            $where= ['user_id'=>$user_id,'value'=>$value,'type'=>$frType];
            self::map($where)->update($data);
        }
        return true;
    }

    /** 获取用户手机
     * @param $value
     * @return mixed
     */
    private function getUserId($value){
        $type = is_phone($value) ? 'mobile' : 'email';
        if($type =='mobile'){
            $map['phone'] = $value;
        }else{
            $map['email'] = $value;
        }
        return \App\User::map($map)->value('id');
    }
    /**
     *  设置防骚扰
     */
    public function SetFreeze($value,$_type='mobile',$status=0,$validity_time=1800){
        $user_id    = $this->getUserId($value);
        $name       = ($_type =='mobile') ? '设置手机防骚扰':'设置邮箱防骚扰';
        $type       = 'fr';
        $is_mobile  = ($_type) ? 1 : 0;
        $is_email   = ($_type =='email') ? 1 : 0;
        $status     = intval($status);
        $activated  = 1;
        $where = ['user_id'=>$user_id,'value'=>$value,'type'=>$type];
        //查看是否存在
        $isCheck = self::map($where)->count();
        if($isCheck){
            $find = self::map($where)->first();
            $oldCreateTime = date('Ymd',strtotime($find->created_at));
            $thisDayTime   = date('Ymd',time());
            if($oldCreateTime != $thisDayTime){
                $data['created_at'] = date('Y-m-d H:i:s',time());
            }
            $data['updated_at'] = date('Y-m-d H:i:s',time());
            $data['status'] = $status;
            if($status !==1){
                $data['click_num'] = DB::raw('(click_num+1)');
            }
            //UNIX_TIMESTAMP(updated_at)
            $where= ['user_id'=>$user_id,'value'=>$value,'type'=>$type];
            $result = self::map($where)->update($data);
            $res = ($result >=0);
        }else{
            $this->user_id    = $user_id;
            $this->value      = $value;
            $this->name       = $name;
            $this->type       = $type;
            $this->is_mobile  = $is_mobile;
            $this->is_email   = $is_email;
            $this->status     = $status;
            $this->activated  = $activated;
            $this->created_at = date('Y-m-d H:i:s');
            $this->updated_at = date('Y-m-d H:i:s');
            $this->validity_time = $validity_time;
            $this->click_num = 1;
            $res = $this->save();
        }
        return $res;
    }
    /**
     * 查看防骚扰状态
     * @param $user_id
     * @param $value
     * @param string $frType
     * @return mixed
     */
    public function getStatus($value,$user_id=0,$frType='fr'){
        $map = ['value'=>$value,'type'=>$frType];
        if($frType =='fr'){
            $map['activated'] = 1;
        }
        $type = is_phone($value) ? 'mobile' : 'email';
        if($type =='mobile'){
            $map['is_mobile'] = 1;
        }else{
            $map['is_email'] = 1;
        }
        if($user_id >0){
            $map['user_id'] = $user_id;
        }
        $stringWhere = "FROM_UNIXTIME(UNIX_TIMESTAMP(updated_at),'%Y-%m-%d')='".date('Y-m-d')."'";
        $stringWhere .= " AND (UNIX_TIMESTAMP(updated_at)+validity_time) >= UNIX_TIMESTAMP()";
        $result = self::where($map)->whereRaw($stringWhere)->first(['status']);
        // $result = self::map($map)->value('status')->toSql();
        return is_null($result) ? 0 : intval($result->status);
    }

    /**
     * @param $value
     * @param string $frType
     */
    public function setDayData($value,$frType='fr'){
        $user_id = $this->getUserId($value);
        $map = ['value'=>$value,'type'=>$frType];
        $type = is_phone($value) ? 'mobile' : 'email';
        if($type =='mobile'){
            $map['is_mobile'] = 1;
        }else{
            $map['is_email'] = 1;
        }
        $map['user_id'] = $user_id;
        $stringWhere = "FROM_UNIXTIME(UNIX_TIMESTAMP(created_at), '%Y-%m-%d')='".date('Y-m-d')."'";
        $stringWhere .= " AND (UNIX_TIMESTAMP(updated_at)+validity_time) >= UNIX_TIMESTAMP()";
        $map['_string'] = $stringWhere;
        $dayCount = self::map($map)->count();
        if(empty($dayCount)){
            $data['created_at'] = date('Y-m-d H:i:s',time());
            $data['updated_at'] = date('Y-m-d H:i:s',time());
            $data['status']     = 0;
            $data['click_num']  = 0;
            //UNIX_TIMESTAMP(updated_at)
            $where= ['user_id'=>$user_id,'value'=>$value,'type'=>$frType];
            self::map($where)->update($data);
        }
    }
    /**
     * @param $value
     * @param int $user_id
     * @param string $frType
     */
    public function getStartTime($value,$user_id=0,$frType='fr'){
        $map = ['value'=>$value,'type'=>$frType];
        $type = is_phone($value) ? 'mobile' : 'email';
        if($type =='mobile'){
            $map['is_mobile'] = 1;
        }else{
            $map['is_email'] = 1;
        }
        if($user_id >0){
            $map['user_id'] = $user_id;
        }
        $map['_string'] = "FROM_UNIXTIME(UNIX_TIMESTAMP(created_at), '%Y-%m-%d')='".date('Y-m-d')."'";
        return self::map($map)->value('updated_at');
    }
    /** 获取当天次数
     * @param $value
     * @param int $user_id
     * @param string $frType
     * @return int
     */
    public function getClickNum($value,$max=10,$frType='fr'){
        $user_id = $this->getUserId($value);
        $map = ['value'=>$value,'type'=>$frType,'status'=>0];
        $type = is_phone($value) ? 'mobile' : 'email';
        if($type =='mobile'){
            $map['is_mobile'] = 1;
        }else{
            $map['is_email'] = 1;
        }
        if($user_id >0){
            $map['user_id'] = $user_id;
        }
        $stringWhere = "FROM_UNIXTIME(UNIX_TIMESTAMP(created_at), '%Y-%m-%d')='".date('Y-m-d')."'";
        $stringWhere .= " AND (UNIX_TIMESTAMP(updated_at)+validity_time) >= UNIX_TIMESTAMP()";
        $map['_string'] = $stringWhere;
        $result = self::map($map)->value('click_num');
        return intval($result);
    }
}
