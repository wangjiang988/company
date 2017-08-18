<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/14
 * Time: 14:39
 */

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\HcUserWithdrawLineRepositoryInterface;
use App\Models\HcUserWithdrawLine;


class HcUserWithdrawLineRepository extends Repository implements HcUserWithdrawLineRepositoryInterface
{
    public function __construct(HcUserWithdrawLine $model)
    {
        $this->model = $model;
    }
}