<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\HcUserOrderRepositoryInterface;
use App\Repositories\Contracts\HcUserAccountRepositoryInterface;
use App\Repositories\Contracts\HcUserAccountLogRepositoryInterface;
use App\Repositories\Contracts\HcUserWithdrawLineRepositoryInterface;
use App\Repositories\Contracts\HcUserRechargeRepositoryInterface;
use App\Repositories\Contracts\HcUserWithdrawRepositoryInterface;
use App\Repositories\Contracts\HcUserConsumeRepositoryInterface;
use App\Repositories\Contracts\HcDailiAccountRepositoryInterface;
use App\Repositories\Contracts\HcDailiAccountLogRepositoryInterface;
use App\Repositories\Contracts\HcDailiRechargeBankRepositoryInterface;
use App\Repositories\Contracts\HcDailiWithdrawBankRepositoryInterface;
use App\Repositories\Contracts\HcAccountLogRepositoryInterface;

use App\Repositories\Eloquent\HcUserOrderRepository;
use App\Repositories\Eloquent\HcUserAccountRepository;
use App\Repositories\Eloquent\HcUserAccountLogRepository;
use App\Repositories\Eloquent\HcUserWithdrawLineRepository;
use App\Repositories\Eloquent\HcUserRechargeRepository;
use App\Repositories\Eloquent\HcUserWithdrawRepository;
use App\Repositories\Eloquent\HcUserConsumeRepository;
use App\Repositories\Eloquent\HcDailiAccountRepository;
use App\Repositories\Eloquent\HcDailiAccountLogRepository;
use App\Repositories\Eloquent\HcDailiRechargeBankRepository;
use App\Repositories\Eloquent\HcDailiWithdrawBankRepository;
use App\Repositories\Eloquent\HcAccountLogRepositroy;

/**
 * The repository service provider, binds interfaces to concrete classes for dependency injection.
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class RepositoryServiceProvider extends ServiceProvider
{
    protected $repositories = [
        HcUserAccountRepositoryInterface::class             => HcUserAccountRepository::class,
        HcUserAccountLogRepositoryInterface::class          => HcUserAccountLogRepository::class,
        HcUserRechargeRepositoryInterface::class            => HcUserRechargeRepository::class,
        HcUserWithdrawRepositoryInterface::class            => HcUserWithdrawRepository::class,
        HcUserConsumeRepositoryInterface::class             => HcUserConsumeRepository::class,
        HcDailiAccountRepositoryInterface::class            => HcDailiAccountRepository::class,
        HcDailiAccountLogRepositoryInterface::class         => HcDailiAccountLogRepository::class,
        HcDailiRechargeBankRepositoryInterface::class       => HcDailiRechargeBankRepository::class,
        HcDailiWithdrawBankRepositoryInterface::class       => HcDailiWithdrawBankRepository::class,
        HcUserOrderRepositoryInterface::class               => HcUserOrderRepository::class,
        HcUserWithdrawLineRepositoryInterface::class        => HcUserWithdrawLineRepository::class,
        HcAccountLogRepositoryInterface::class              => HcAccountLogRepositroy::class,
    ];
    /**
     * Bind the repository interface to the implementations.
     */
    public function register()
    {
        foreach ($this->repositories as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }
}
