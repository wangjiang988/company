<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Common;

class AreaSpecialFile extends Model
{
    use Common;
    protected $table = 'area_special_files';
    protected $fillable = ['file_name','area_id','area_level','user_id','country_car','use_car','licence_user_type','licence_other','file_url'];

    public static function saves($data)
    {
        return self::create($data);
    }

    /**
     * 查询列表
     * @param $wheres
     * @param string $col
     * @param int $pageSize
     * @param int $page
     * @param string $order
     * @param string $sort
     * @return mixed
     */
    public static function pageList($wheres,$col='*',$pageSize=10, $page=1,$order='id',$sort='desc')
    {
        $result = self::select(DB::raw($col))
            ->map($wheres)
            ->orderBy(DB::raw($order),$sort)
            ->paginate($pageSize);
        return $result;
    }
}
