<?php

namespace App\Repositories\Eloquent;

use App\Events\AccountTooLowEvent;
use App\Repositories\Contracts\HcDailiAccountRepositoryInterface;
use App\Models\HcDailiAccount;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * The HcUserAccount repository.
 */
class HcDailiAccountRepository extends Repository implements HcDailiAccountRepositoryInterface {

    /**
     * EloquentChannelRepository constructor.
     *
     * @param Channel $model
     */
    public function __construct(HcDailiAccount $model)
    {
        $this->model=$model;
    }

    /**
     * 添加代理商资金账号
     * @param $seller_id
     * @param int $money
     * @return mixed
     */
    public function addAccount($seller_id,$money)
    {
        $checkAccount = $this->count(['d_id'=>$seller_id]);
        if(empty($checkAccount)){
            $accountData = [
                    'd_id'              => $seller_id,
                    'credit_line'       => 0,
                    'total_deposit'     => $money,
                    'basic_deposit'     => 0,
                    'avaliable_deposit' => $money,
                    'freeze_deposit'    => 0,
                    'status'            => 1,
            ];
            return self::create($accountData);
        }else{
            //更新用户总金额,更新用户可用余额
            return $this->model->findOrFail($seller_id)
                ->increment('total_deposit',
                    $money ,
                    ['avaliable_deposit'=> DB::raw('avaliable_deposit+'.$money),
                        'updated_at'=>Carbon::now()
                    ]
                );
        }
    }

    /**
     * 消费用户资金（支付歉意金。或保证金利息）
     * @param $user_id
     * @param $moeny
     */
    public function delAccount($user_id,$money)
    {
        //更新用户总金额,更新用户可用余额
        return $this->model->findOrFail($user_id)
            ->decrement('total_deposit',
                $money ,
                ['avaliable_deposit'=> DB::raw('avaliable_deposit-'.$money),
                    'updated_at'=>Carbon::now()
                ]
            );
    }

    /**
     * 冻结用户余额（加信宝或其他操作）
     * @param $user_id
     * @param $money
     * @return mixed
     */
    public function UpdateFreezeDeposit($user_id,$money)
    {
        $userFind = $this->getById($user_id);
        $old_userFind = clone $userFind;

        if($userFind['avaliable_deposit'] >= $money){
            $update = [
                'freeze_deposit' => DB::raw('freeze_deposit+'.$money),
                'total_deposit'  => DB::raw('total_deposit-'.$money),
                'updated_at'     => Carbon::now()
            ];
        }else{
//            if(($userFind['credit_line'] + $userFind['avaliable_deposit']) >= $money){
                $update = [
                    'credit_line'    => DB::raw('credit_line-('.$money.'-avaliable_deposit)'),
                    'freeze_deposit' => DB::raw('freeze_deposit+'.$money),
                    'total_deposit'  => DB::raw('total_deposit-'.$money),
                    'updated_at'     => Carbon::now()
                ];
//            }else{
//                return false;
//            }
        }
        $ret =   $userFind->decrement('avaliable_deposit', $money , $update );
        if($ret) {
            $userFind->avaliable_deposit -= $money;
            event(new AccountTooLowEvent($userFind, $old_userFind));
        }
        return $ret;
    }

    /**
     * 提现临时冻结金额
     * @param $user_id
     * @param $money
     */
    public function WithdrawalFreeze($user_id,$money)
    {
        return $this->getById($user_id)->decrement('avaliable_deposit', $money ,
            [
                'temp_deposit'  => DB::raw('temp_deposit+'.$money),
                'total_deposit' => DB::raw('total_deposit-'.$money),
                'updated_at'    => Carbon::now()
            ]
        );
    }

    /**
     * 解冻加信宝
     * @param $user_id
     * @param $money
     */
    public function RemoveFreezeDeposit($user_id,$money)
    {
        return $this->model->findOrFail($user_id)
            ->increment('avaliable_deposit' , $money ,
                ['freeze_deposit'=> DB::raw('freeze_deposit-'.$money),
                    'total_deposit'=> DB::raw('total_deposit+'.$money),
                    'updated_at'=>Carbon::now()
                ]
            );
    }
}