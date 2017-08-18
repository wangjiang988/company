<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Common;
use DB;

class UserExtension extends Model
{
    protected $table = 'user_extension';
    protected $primaryKey = 'user_id';//指定主键名称
    public $incrementing = false;//主建非自增
    protected $fillable = [
        'user_id','call','last_name','first_name','sex','birthday','address_id','photo',
        'id_cart','sc_id_cart_img','id_facade_img','id_behind_img','user_money','is_id_verify'
        ];
    protected $guarded =['_token','_url'];

    public $timestamps = false;
    use Common;

    public function getRow($where,$orderStr='user_id',$sort='desc',$col='*'){
        return self::select(DB::raw($col))
            ->map($where)
            ->orderBy(DB::raw($orderStr),$sort)
            ->first();
    }

    public function getPageList($where,$orderStr='id',$sort='desc',$col='*',$pageSize=10){
        $result = self::select(DB::raw($col))
            ->map($where)
            ->orderBy(DB::raw($orderStr),$sort)
            ->paginate($pageSize);
        return $result;
    }

    /**
     * 关联用户表
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo('\App\User');
    }

    public function users(){
        return $this->belongsToMany('\App\User');
    }
}
