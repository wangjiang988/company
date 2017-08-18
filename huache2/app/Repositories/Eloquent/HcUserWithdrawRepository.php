<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\HcUserWithdrawRepositoryInterface;
use App\Models\HcUserWithdraw;

/**
 * The HcUserAccount repository.
 */
class HcUserWithdrawRepository extends Repository implements HcUserWithdrawRepositoryInterface {

    /**
     * EloquentChannelRepository constructor.
     *
     * @param Channel $model
     */
    public function __construct(HcUserWithdraw $model)
    {
        $this->model=$model;
    }
}