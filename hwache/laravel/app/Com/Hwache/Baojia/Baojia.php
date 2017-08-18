<?php namespace App\Com\Hwache\Baojia;
/**
 * 报价管理功能模块
 */

use App\Models\HgBaojia;

class Baojia
{
    /**
     * @var $baojia
     */
    protected $baojia;

    /**
     * 检测报价编号是否存在
     * @param $serial
     */
    public function checkBaojiaId($serial)
    {
        $baojia_data = HgBaojia::getBaojiaInfo($serial, true);
        if (empty($baojia_data) || !is_object($baojia_data)) {
            // TODO 结果为空做一个提示
            exit('没有该报价哦!!');
        }elseif($baojia_data['bj_step']<>99){
            exit('该报价不可用!!');
        }
        $baojia = $baojia_data->toArray();
        return $baojia;
    }

    /**
     * 减少库存
     * @param $bjId
     * @param int $count
     * @return mixed
     */
    public function decrementStock($bjId, $count = 1)
    {
        // 悲观锁
        return \DB::transaction(function() use ($bjId, $count) {
            $carCount = HgBaojia::where('bj_id', $bjId)->sharedLock()->value('bj_num');
            if ($carCount <= 0) {
                // throw new \Exception('Car Num Is Empty');
                return false;
            }

            return HgBaojia::where('bj_id', $bjId)->decrement('bj_num', $count);
        });
    }

    /**
     * 增加库存
     * @param $bjId
     * @param int $count
     * @return mixed
     */
    public function incrementStock($bjId, $count = 1)
    {
        return HgBaojia::where('bj_id', $bjId)->increment('bj_num', $count);
    }
}