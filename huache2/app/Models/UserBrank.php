<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Common;

class UserBrank extends Model
{
    use Common;
    protected $table = 'user_bank';
    protected $fillable = ['user_id','bank_register_name','bank_code','bank_name','province','city','district','bank_address','sc_bank_img','bank_img','is_default','activated','is_verify'];//可修改字段
    protected $primaryKey = 'id';
    /*protected $guarded = ['_token','_url'];//过滤post参数*/
    public $timestamps = false;

    protected $upload;

    public function __construct()
    {
        parent::__construct([]);
    }

    public function saveData($data,$id=0)
    {
        $formData = [
            'user_id'       => (int) $data['user_id'],
            'bank_register_name' => isset($data['bank_register_name']) ? $data['bank_register_name'] : '',
            'bank_code'     => $data['bank_code'],
            'bank_name'     => $data['bank_name'],
            'province'      => isset($data['province']) ? $data['province'] : '',
            'city'          => isset($data['city']) ? $data['city'] : '',
            'district'      => isset($data['district']) ? $data['district'] : '',
            'bank_address'  => isset($data['bank_address']) ? $data['bank_address'] : '',
            'sc_bank_img'   => isset($data['sc_bank_img']) ? (int) $data['sc_bank_img'] : 0,
            'bank_img'      => isset($data['bank_img']) ? (int) $data['bank_img'] : 0 ,
            'is_default'    => !isset($data['is_default']) ? 1 : intval($data['is_default']),
            'activated'     => !isset($data['activated']) ? 0 : intval($data['activated']),
            'is_verify'     => !isset($data['is_verify']) ? 0 : intval($data['is_verify'])
        ];
        if($id >0){
            return DB::table($this->table)->where($this->primaryKey,$id)->update($formData);
        }
        return DB::table($this->table)->insertGetId($formData);
    }

    /** 保存数据
     * @param $data
     * @return mixedb
     */
    public function createData($data)
    {
        $isData = $this->where($data)->count();
        if($isData >0){
            return $this->where($data)->first();
        }
        $insertId = $this->saveData($data);
        return $this->where(['id'=>$insertId])->first();
    }
    /**
     * 获取银行卡列表
     * @param $where
     * @param string $col
     * @param null $order
     * @param string $sort
     * @param int $limit
     * @return mixed
     */
    public function getBrakPage($where,$col='*',$order=null,$sort='desc',$limit=10)
    {
        $orderStr = is_null($order) ? $this->primaryKey : $order;
        return self::select(DB::raw($col))
            ->map($where)
            ->orderBy($orderStr , $sort)
            ->paginate($limit);
    }

    public function getUserBrankCount($user_id){
        return self::where('user_id','=',$user_id)->count();
    }

    /** 反向关联用户表
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\Users');
        //return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function scopeBankAddress($query,$id)
    {
        return $query->where('id',$id)->first();
    }
}
