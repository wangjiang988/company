<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\HcUserRechargeRepositoryInterface;
use App\Models\HcUserRecharge;

/**
 * The HcUserAccount repository.
 */
class HcUserRechargeRepository extends Repository implements HcUserRechargeRepositoryInterface {

    /**
     * EloquentChannelRepository constructor.
     *
     * @param Channel $model
     */
    public function __construct(HcUserRecharge $model)
    {
        $this->model=$model;
    }
}