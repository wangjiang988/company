<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\HcDailiWithdrawBankRepositoryInterface;
use App\Models\HcDailiWithdrawBank;

/**
 * The HcUserAccount repository.
 */
class HcDailiWithdrawBankRepository extends Repository implements HcDailiWithdrawBankRepositoryInterface {

    /**
     * EloquentChannelRepository constructor.
     *
     * @param Channel $model
     */
    public function __construct(HcDailiWithdrawBank $model)
    {
        $this->model=$model;
    }
}