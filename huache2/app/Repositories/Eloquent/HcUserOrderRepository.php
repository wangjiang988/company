<?php

namespace App\Repositories\Eloquent;

use App\Models\HgOrder;
use App\Repositories\Contracts\HcUserOrderRepositoryInterface;
use Illuminate\Support\Facades\DB;

class HcUserOrderRepository extends Repository implements HcUserOrderRepositoryInterface
{
    public function __construct()
    {
        $this->model = new HgOrder();//HgOrder::class;
    }

    /**
     * @param $where
     * @param string $col
     * @param string $orderBy
     * @param int $limit
     */
    public function getList($where,$col='*',$orderBy='id',$sort='desc',$limit=10)
    {
        $result = $this->model->select(DB::raw($col))
            ->where($where)
            ->orderBy(DB::raw($orderBy),$sort)
            ->paginate($limit);
        return $result;
    }


    /**
     * @param  array  $where
     * @param array  $times
     * @param null   $brand
     * @param string $orderBy
     * @param string $sort
     * @param int    $limit
     *
     */
    public function getSearchList($where,$times=[],$brand=null,$orderBy='id',$sort='desc',
        $limit = 10
    ) {
        $result = $this->model->where($where)
            ->where(function ($query) use ($times) {
                if (isset($times['type'])
                    && ($times['type'] == 1
                        || $times['type'] == 2)
                ) {
                    if ($times['type'] == 1) {
                        $query->where('created_at', '>=', $times['time']);
                    } else {
                        $query->where('created_at', '<', $times['time']);
                    }
                }
            })
            ->where(function ($query) use ($brand) {
                if ( ! is_null($brand)) {
                    $query->where('gc_name', 'like', $brand . '%');
                }
            })
            ->where('order_status','<>',1)
            ->where('order_state','<>',200)
            ->orderBy(DB::raw($orderBy), $sort)
            ->paginate($limit);
        return $result;
    }

    /**
     * @param $where
     * @param string $col
     */
    public function getFind($where,$col='*')
    {
        return $this->model->FindOrder($where);
    }

    /**
     * @param $where
     *
     * @return mixed
     */
    public function groupBrand($where)
    {
        return $this->model->where($where)
            ->select(DB::raw('substring_index(gc_name, " &gt;", 1) as brand'))
            ->groupBy('brand')
            ->pluck('brand');
    }
}
