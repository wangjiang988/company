<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HcVehicleToolsFiles extends Model
{
    protected $table = 'hc_vehicle_tools_files';

    /**
     * @param $brand_id
     *  根据车型id读取随车工具和文件(1文件,2工具)
     * @return array
     */
    public static function getAnnex($brand_id)
    {
        $Annexs = self::whereBrandId($brand_id)->get();
        $result = ['files' => [], 'tools' => []];
        $Annexs->each(function ($item) use (&$result) {
            if ($item->type === 1) {
                $result['files'][] = $item->toArray();
            }
            if ($item->type === 2) {
                $result['tools'][] = $item->toArray();
            }
        });
        return $result;
    }
}
