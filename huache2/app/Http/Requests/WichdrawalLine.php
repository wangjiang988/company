<?php

namespace App\Http\Requests;

use App\Models\HcUserBank;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\HcUserWithdrawLine;
use App\Repositories\Eloquent\HcUserWithdrawLineRepository as WithdrawLine;
use Illuminate\Support\Facades\DB;

class WichdrawalLine extends FormRequest
{
    protected $line;

    public function __construct()
    {
        $this->line = new WithdrawLine(new HcUserWithdrawLine());
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
     * @return null  提现线路列表
     */
    public function getLineAll()
    {
        $col  = "car_hc_user_withdraw_line.*,car_bank.province,car_bank.city,car_bank.bank_address,car_bank.is_default";
        $col .= ",car_bank.activated,car_bank.is_verify,car_u.last_name,car_u.first_name, car_ur.user_bank_name";
        $userLine = \App\User::find($this->user()->id)
            ->UserWithdrawLine()
            ->select(DB::raw($col))
            ->leftJoin('user_bank as bank','bank.id','bank_id')
            ->leftJoin('user_extension as u','u.user_id','bank.user_id')
            ->leftJoin('hc_user_recharge as ur','ur.uwl_id','hc_user_withdraw_line.uwl_id')
            ->get();
        $isData = (count($userLine->toArray()));
        return empty($isData) ? null : $userLine;
    }

    /**
     * @return null  提现线路列表
     */
    public function getLineAll_v2()
    {
        $list  = HcUserWithdrawLine::select('hc_user_withdraw_line.*')
                    ->rightJoin(DB::Raw('(select distinct(uwl_id) as uwl_id from car_hc_user_recharge where status=2 or status=3 order by created_at desc) AS car_uwl'),'uwl.uwl_id','hc_user_withdraw_line.uwl_id')
                    ->where('hc_user_withdraw_line.user_id',$this->user()->id)
                    ->where('hc_user_withdraw_line.is_del',0)
                    ->orderBy('hc_user_withdraw_line.status','desc')
                    ->orderBy('hc_user_withdraw_line.line_type','asc')
                    ->orderBy('hc_user_withdraw_line.created_at','desc')
                    ->get('hc_user_withdraw_line.*');
        //封装数据，进行排序
// 一）账户性质优先级：1.绑定的银行账户、2.线上支付、3.银行转账、4.失效线上支付、失效银行转账
//二）验证情况次优级：1.已验证可使用、2.可使用、3.可使用（待完善）、4.已失效不可用。
//三）次优级相同的，按账户生成时间先后顺序排列。


//        获取已绑定账户
        $is_default  =  HcUserBank::where('user_id',$this->user()->id)
                            ->where('is_default',1)->first();

//        if($is_default)
//        {
//            $list->push($is);
//        }

        $isData = (count($list->toArray()));
        return empty($isData) ? null : $list;
    }
}
