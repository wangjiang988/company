<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\HcUserAccountRepositoryInterface;
use App\Models\HcUserAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Exception;

/**
 * The HcUserAccount repository.
 */
class HcUserAccountRepository extends Repository implements HcUserAccountRepositoryInterface {

    /**
     * EloquentChannelRepository constructor.
     *
     * @param Channel $model
     */
    public function __construct(HcUserAccount $model)
    {
        $this->model=$model;
    }

    /**
     * @param $user_id
     * @param $moeny
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function addAccount($user_id,$money){
        $checkAccount = $this->count(['user_id'=>$user_id]);
        if(empty($checkAccount)){
            $accountData = [
                'user_id'            => $user_id,
                'total_deposit'      => $money,
                'avaliable_deposit'  => $money,
                'freeze_deposit'     => 0,
                'status'             => 1
            ];
            return $this->create($accountData);
        }else{
            //更新用户总金额,更新用户可用余额
            return $this->model->findOrFail($user_id)
                    ->increment('total_deposit',$money ,
                    [
                        'avaliable_deposit'=> DB::raw('avaliable_deposit+'.$money),
                        'updated_at'       => Carbon::now()
                    ]
                );
        }
    }
    /**
     * @param $user_id
     * @param $moeny
     */
    public function delAccount($user_id,$money)
    {
        //更新用户总金额,更新用户可用余额
        return $this->model->findOrFail($user_id)
            ->decrement('total_deposit',
                $money ,
                [
                    'avaliable_deposit'=> DB::raw('avaliable_deposit-'.$money),
                    'updated_at'=>Carbon::now()
                ]
            );
    }

    /** 冻结用户余额（提现或其他操作）
     * @param $user_id
     * @param $money
     * @return mixed
     */
    public function UpdateFreezeDeposit($user_id,$money)
    {
        $userFind = $this->first(['user_id'=>$user_id]);
        if($userFind['avaliable_deposit'] < $money){
            throw new \Exception('用户余额不足！');
            return false;
        }
         return $this->model->findOrFail($user_id)
             ->decrement('avaliable_deposit' , $money ,
                 [
                     'freeze_deposit'=> DB::raw('freeze_deposit+'.$money),
                     'total_deposit'=> DB::raw('total_deposit-'.$money),
                     'updated_at'=>Carbon::now()
                 ]
             );
    }

    /** 冻结用户余额（提现或其他操作）
     * @param $user_id
     * @param $money
     * @return mixed
     */
    public function UpdateTempDeposit($user_id,$money)
    {
        return $this->model->findOrFail($user_id)
            ->decrement('avaliable_deposit' , $money ,
                [
                    'temp_deposit'=> DB::raw('temp_deposit+'.$money),
                    'total_deposit'=> DB::raw('total_deposit-'.$money),
                    'updated_at'=>Carbon::now()
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
                [
                    'freeze_deposit'=> DB::raw('freeze_deposit-'.$money),
                    'total_deposit'=> DB::raw('total_deposit+'.$money),
                    'updated_at'=>Carbon::now()
                ]
            );
    }
}