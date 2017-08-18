<?php

namespace App\Repositories\Contracts;

interface HcUserOrderRepositoryInterface
{
    /**
     * @param $where
     * @param string $col
     * @param string $orderBy
     * @param int $limit
     * @return mixed
     */
     public function getList($where,$col='*',$orderBy='',$sort='desc',$limit=10);

    /**
     * @param $where
     * @param string $col
     * @return mixed
     */
     public function getFind($where,$col='*');
}
