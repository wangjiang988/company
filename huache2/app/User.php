<?php

namespace App;

use App\Models\HcUserAccount;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use App\Models\Common;
use Illuminate\Support\Facades\DB;
use App\Notifications\Auth\MyResterPassword;
use Tymon\JWTAuth\Contracts\JWTSubject  as AuthenticatableUserContract;

class User extends Authenticatable implements AuthenticatableUserContract
{
    use Notifiable,Common;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';
    protected $fillable = ['phone', 'password'];
    protected $guarded  = ['id','name', 'email','status','login_num'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function __construct()
    {
        parent::__construct([]);
    }
    /**
     * 验证用户信息
     * @param $map
     * @return mixed
     */
    public static function checkUser($map){
        return self::where($map)->count();
    }
    /**
     * 
     * @param type $map
     * @return type
     */
    public static function getUserFind($map,$col='*',$one=false){
        if($one == false){
             return self::select(DB::raw($col))->param($map)->first();
        }else{
            return self::param($map)->value(DB::raw($col));
        }       
    }
    /** 手机号获取用户id
     * @param $phone
     * @return mixed
     */
    public static function getPhoneToId($phone)
    {
        return self::where('phone',$phone)->value('id');
    }
    /**
     * 获取登录用户的详细信息
     * @return type
     */
    public static function getUserHomeInfo($userId){        
        $userInfo = self::select('ue.call','ue.last_name','ue.first_name','ue.photo','ue.user_money','ue.is_id_verify','a.address','a.province','a.city','im.img_url','phone','email')
            ->leftJoin('user_extension as ue','ue.user_id','=','id')
            ->leftJoin('user_address as a',
                function ($join) {
                    $join->on('a.user_id', '=', 'id')->where('a.is_default', '=', 1)->where('a.activated', '=', 1);
                }
            )
            ->leftJoin('qiniu_images as im','im.img_id','=','photo')
            ->map(['id'=>$userId])
            ->first();
        return $userInfo;
    }
    /** 一对一绑定用户扩展信息
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function UserExtension()
    {
        return $this->hasOne('App\Models\UserExtension');
    }
    /**
     * 关联用户地址
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userAddress()
    {
        return $this->hasOne('App\Models\UserAddress','address_id')->where(['is_default'=>1,'activated'=>1]);
    }
    /**
     * 绑定银行卡
     */
    public function UserBank($one=true,$where=null)
    {
        if ($one) {
            $map = is_null($where) ? ['is_default'=>1] : $where;
            return $this->hasOne('App\Models\UserBrank')->where($map);//一对一绑定
        }   else {
            return $this->hasMany('App\Models\UserBrank');//一对多绑定
        }
    }
    /**
     * 用户提现线路
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function UserWithdrawLine(){
        return $this->hasMany('App\Models\HcUserWithdrawLine');//一对多绑定
    }
    /** 交易流水）
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function UserAccountLog()
    {
        return $this->hasMany('App\Models\HcUserAccountLog');
    }
    /**
     * 用户资金账户
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function UserAccount()
    {
        return $this->hasOne(HcUserAccount::class);
    }
    /**
     * 用户充值
     */
    public function UserRecharge()
    {
        return $this->hasMany(\App\Models\HcUserRecharge::class);
    }

    /**
     * 用户提现
     */
    public function UserWithdrawal()
    {
        return $this->hasMany(\App\Models\HcUserWithdraw::class);
    }
    /**
     * 用户消费记录
     */
    public function UserConsume()
    {
        return $this->hasMany(\App\Models\HcUserConsume::class);
    }

    /**
     * 查看用户的冻结、防骚扰设置
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function UserFreeze($type='fr')
    {
        return $this->hasOne(\App\Models\UserFreeze::class)->where(['type'=>$type]);
    }
    /** 用户订单列表
     * @param array $where
     * @return mixed
     */
    public function UserOrder($where = ['order_status','>=',0]){
        list($field, $condition, $value) = $where;
        return $this->hasMany('App\Models\HgOrder')
            ->select(DB::raw("car_hc_order.*,car_hb.gc_name"))
            ->leftJoin('hg_baojia as hb','hb.bj_id','hc_order.bj_id')
            ->orderBy('hc_order.created_at','desc')
            ->where($field,$condition,$value)
            ->where('hc_order.order_status','<>',1)
            ->where('hc_order.order_state','<>',200);
    }    
    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MyResterPassword($token));
    }

    //jwt
    /**
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Eloquent model method
    }
    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
