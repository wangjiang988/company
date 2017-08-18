<?php namespace App\Models;

/**
 * 用户模型
 */
use Illuminate\Database\Eloquent\Model;
class HgUser extends Model {

    protected $table = 'member';

    public $timestamps = false;

    protected $primaryKey = 'member_id';

    protected $guarded = ['member_id'];
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

    /**
     * 关联供应商用户表
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function MemberSeller()
    {
        return $this->hasOne(HgSeller::class , 'member_id');
    }
    /**
     * 代理商资金账号
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function DealerAccount()
    {
        return $this->hasOne(HcDailiAccount::class,'d_id');
    }

    /**
     * 代理商交易日志
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function DealerAccountLog()
    {
        return $this->hasMany(HcDailiAccountLog::class,'d_id');
    }

    /**
     * 代理商充值
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function DealerRechargeBank()
    {
        return $this->hasMany(HcDailiRechargeBank::class ,'d_id');
    }

    /**
     * 代理商提现
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function DealerWithdrawBank()
    {
        return $this->hasMany(HcDailiWithdrawBank::class,'d_id');
    }

    /**
     * 代理商透支日志
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function DailiOverdraftLog()
    {
        return $this->hasOne(HcDailiOverdraftLog::class,'d_id');
    }
}
