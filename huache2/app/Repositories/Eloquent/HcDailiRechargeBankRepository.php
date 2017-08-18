<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\HcDailiRechargeBankRepositoryInterface;
use App\Models\HcDailiRechargeBank;

/**
 * The HcUserAccount repository.
 */
class HcDailiRechargeBankRepository extends Repository implements HcDailiRechargeBankRepositoryInterface {

    /**
     * EloquentChannelRepository constructor.
     *
     * @param Channel $model
     */
    public function __construct(HcDailiRechargeBank $model)
    {
        $this->model=$model;
    }
}