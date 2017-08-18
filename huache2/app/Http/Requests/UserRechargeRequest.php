<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Carbon\Carbon;
use App\Repositories\Contracts\HcUserRechargeRepositoryInterface;
use App\Repositories\Contracts\HcUserAccountLogRepositoryInterface;
use App\Repositories\Contracts\HcUserAccountRepositoryInterface;
use App\Repositories\Contracts\HcUserConsumeRepositoryInterface;
use App\Repositories\Contracts\HcAccountLogRepositoryInterface;
use App\Repositories\Contracts\HcUserWithdrawLineRepositoryInterface;
use App\Models\UserBrank;
use Illuminate\Support\Facades\DB;

class UserRechargeRequest extends FormRequest
{
    protected $recharge,$consume,$userAccountLog,$accountLog,$userAccount,$accountLine;
    public function __construct(HcUserRechargeRepositoryInterface $userRechargeRepository,
                                HcUserConsumeRepositoryInterface $hcUserConsumeRepository,
                                HcUserAccountLogRepositoryInterface $userAccountLogRepository,
                                HcUserAccountRepositoryInterface $userAccountRepository,
                                HcAccountLogRepositoryInterface $accountLogRepository,
                                HcUserWithdrawLineRepositoryInterface $userWithdrawLineRepository
    )
    {
        $this->recharge       = $userRechargeRepository;//充值
        $this->consume        = $hcUserConsumeRepository;//消费
        $this->userAccountLog = $userAccountLogRepository;//用户资金日志
        $this->accountLog     = $accountLogRepository;//平台资金日志
        $this->userAccount    = $userAccountRepository;//用户账号
        $this->accountLine    = $userWithdrawLineRepository;//提现线路
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * 保存充值凭证
     */
    public function saveBankVoucher()
    {
        $user_id = $this->user()->id;
        $userAccount = $this->userAccount->first(['user_id'=>$user_id]);
        $userMoney   = $userAccount->avaliable_deposit;
        DB::beginTransaction();
        try {
            $bank = (new UserBrank())-> createData(['user_id' => $user_id,
                'bank_register_name' => $this->input('user_name'),
                'bank_code'          => $this->input('bank_code'),
                'bank_name'          => $this->input('bank_name')]);
            //提现线路
            $brankData = [
                'user_id'      => $user_id,
                'line_type'    => 2,//COMMENT '线路类型（1支付宝，2银行卡，3微信，4银联）',
                'account_code' => $this->input('bank_code'),//'账号（支付宝,微信,银联,银行卡号码）
                'account_name' => $this->input('bank_name'),//账户名称(支付宝,微信,银联,银行名称)
                'bank_id'      => $bank->id//银行卡账号id(管理用户银行卡表)
            ];
            $line = $this->accountLine->firstOrCreate($brankData);
            $cData =[
                    'user_id'        => $user_id,
                    'uwl_id'         => $line->uwl_id,
                    'money'          => $this->input('price'),
                    'remark'         => '银行卡转账',
                    'bank_id'        => $bank->id,
                    'bank_name'      => $this->input('bank_name'),
                    'bank_account'   => $this->input('bank_code'),
                    'user_bank_name' => $this->input('user_name'),
                    'voucher'        => '',
                    'recharge_type'  => 2,
                    'status'         => 0
                ];
            if($this->hasFile('bank_voucher')){
                $cData['voucher'] = simpleUpFile($this->file('bank_voucher'));
            }
            $recharge = $this->recharge->create($cData);
            $wichdraw_end_at = Carbon::now()->addYear()->toDateTimeString();
            //消费记录
            $xfData = [
                'user_id'         => $user_id,
                'ur_id'           => $recharge->ur_id,
                'uw_id'           => 0,
                'consume_money'   => $this->input('price'),
                'avaliable_money' => $userMoney,
                'remark'          => '银行卡转账',
                'is_new'          => 1,
                'status'          => 0,
                'wichdraw_end_at' => $wichdraw_end_at
            ];
            $this->consume->create($xfData);
            //插入交易日志
            $hide_bank_code  = '****'.substr($this->input('bank_code'),-4);
            $hide_bank_name  = mb_substr($this->input('user_name'),0,1).'***';
            $logData = [
                'user_id'         => $user_id,
                'item_id'          => $recharge->ur_id,
                'item'             => '银行卡转账',
                'remark'           => '银行转账-' . $hide_bank_code.$hide_bank_name,
                'money'            => $this->input('price'),
                'credit_avaliable' => $userMoney,
                'type'             => 1,
                'pay_type'         => 2,
                'order_id'         => $this->input('order_id'),
                'status'           => 0,
                'wichdraw_end_at'  => $wichdraw_end_at
            ];
            //保存用户交易日志
            $this->userAccountLog->create($logData);
            //添加用户余额
            //$this->userAccount->addAccount($user_id, $this->input('price'));
            //保存交易总日志
            //$this->accountLog ->addRechargeLog($this->input('price'),$user_id,$recharge->ur_id,$this->input('order_id'));
            DB::commit();
            return $recharge->ur_id;
        }catch (\Exception $e){
            //dd($e->getMessage());
            DB::rollBack();
            return false;
        }
    }
}
