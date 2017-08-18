<?php

namespace App\Http\Requests;

use App\Models\HcUserAccount;
use App\Models\HcUserRecharge;
use App\Models\HcUserWithdrawLine;
use App\Models\UserBrank;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

use App\Models\HcUserWithdraw;
use App\Models\HcUserConsume;

use App\Models\HcUserAccountLog;
use App\Repositories\Contracts\HcUserWithdrawRepositoryInterface;
use App\Repositories\Contracts\HcUserAccountLogRepositoryInterface;
use App\Repositories\Contracts\HcUserAccountRepositoryInterface;
use App\Repositories\Contracts\HcUserConsumeRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class WichdrawalApplication extends FormRequest
{
    protected $wichdrawal,$userAccountLog,$userAccount,$userConsume;
    public function __construct(HcUserWithdrawRepositoryInterface $hcUserWithdrawRepository,
                                HcUserAccountLogRepositoryInterface $hcUserAccountLogRepository,
                                HcUserAccountRepositoryInterface $hcUserAccountRepository,
                                HcUserConsumeRepositoryInterface $hcUserConsumeRepository
    )
    {
        $this->wichdrawal     = $hcUserWithdrawRepository;
        $this->userAccountLog = $hcUserAccountLogRepository;
        $this->userAccount    = $hcUserAccountRepository;
        $this->userConsume    = $hcUserConsumeRepository;
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
     * 保存提现记录
     */
    public function saveWichdrawalData()
    {
        $user_id      = $this->user()->id;
        $lineLogid    = $this->input('log_id');
        $lineS        = $this->input('uwl_id');
        $rechargeId   = $this->input('ur_id');
        $moneyS       = $this->input('money');
        $feeS         = $this->input('fee');
        $syMoney      = $this->input('avaliable_money');
        $remarkS      = $this->input('remark');
//        $pay_type         = $this->input('type');
        //查询用户可用余额
        $userAccount =  HcUserAccount::where('user_id',$user_id)->firstOrFail();
        $userMoney   =  $userAccount->avaliable_deposit;
        //$wichdrawEndS = $this->input('wichdraw_end_at');
        $totalMoney   = $this->input('totalMoney');
        //为保证，开启事务
        DB::beginTransaction();
        try{
            //1.记录账号日志
            $logData = [
                'user_id'          => $user_id,
                'item_id'          => 0,
                'item'             => '提现',
                'remark'           => '正在办理',
                'money'            => $totalMoney,
                'credit_avaliable' => $userMoney - $totalMoney, //TODO
                'type'             => 2,
                'pay_type'         => 0,
                'order_id'         => 0,
                'status'           => 0,
                'wichdraw_end_at'  => '',
                'money_type'       => '-',
                'is_freeze'        => 1,
                'freeze_remark'    => '用户申请提现，审核通过前冻结资金'
            ];
            $isLog = $this->userAccountLog ->addLog($logData);
            if(!$isLog){
                throw new Exception('添加交流流水错误！！！');
            }
            foreach($lineLogid as $k => $type){
                //2.插入提现记录
                //在这里计算手续费
                $res = $this->createWichdrawal($user_id,$isLog->ua_log_id,$moneyS[$k],$feeS[$k],$remarkS[$k],$rechargeId[$k],$lineS[$k]);
                if(!$res){
                    throw new Exception('插入提现记录发生错误！！！');
                }else{
                    //3.记录消费日志
                    $uw_id = $res->uw_id;
                    $isConsume = $this->userConsume ->addConsume($user_id,$moneyS[$k],$rechargeId[$k],$uw_id,$lineS[$k],$syMoney[$k],$remarkS[$k]);
                    if(!$isConsume){
                        throw new Exception('添加消费日志错误！！！');
                    }
                }
            }
            //4.冻结用户余额，等待审核通过后。更新用户余额。
            $isAccount = $this->userAccount ->UpdateTempDeposit($user_id,$totalMoney);
            if(!$isAccount){
                throw new Exception('冻结账号资金发生错误！！！');
            }
            //全部提交成功提交事务
            DB::commit();
            return true;
        }catch (Exception $e){
            //否则回顾
            DB::rollBack();
            //dd($e->getMessage());
            return false;
        }
    }
    /**
     * 添加银行卡提现记录
     * @param $user_id
     * @param $bank_name
     * @param $bank_account
     * @param $user_bank_name
     * @param $money
     * @param $fee
     * @param $remark
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function createWichdrawal($user_id,$log_id,$money,$fee,$remark,$ur_id,$line_id){
        $data = [
            'user_id'        => $user_id,
            'ulog_id'        => $log_id,
            'ur_id'          => $ur_id,
            'line_id'        => $line_id,
            'money'          => $money,
            'fee'            => $fee,
            'remark'         => $remark,
            'status'         => 0
        ];
        if(strpos($ur_id,',')){
            $data['ur_ids'] = $ur_id;
        }
        return $this->wichdrawal ->create($data);
    }

    /**
     * 提现线路列表
     * @return mixed
     */
    public function wichdrawalAvaliableList()
    {
        $user_id = $this->user()->id;
        $consumTable = (new HcUserConsume())->getTable();
        $col = "car_uc.*,car_ur.bank_id,car_ur.bank_name,car_ur.alipay_user_name,car_ur.recharge_type,";
        $col .= "car_ur.uwl_id,car_ur.bank_account,car_ur.user_bank_name,car_ur.money,car_ur.recharge_money,car_log.ua_log_id";
        $consumeList = DB::table($consumTable.' as uc')
            ->select(DB::raw($col))
            ->leftJoin('hc_user_recharge as ur',function($join){
                $join->on('ur.ur_id','=','uc.ur_id');
            })
            ->leftJoin('hc_user_withdraw as uw','uw.ur_id','uc.ur_id')
            ->leftJoin('hc_user_account_log as log',function($join){
                $join->on('log.item_id','=','ur.ur_id')->whereRaw("car_log.money_type='+'");
            })
            ->whereIn('ur.status',[2,3])
            ->where('ur.recharge_money','>',0)
            ->where('uc.user_id',$user_id)
            ->whereRaw("car_uc.`wichdraw_end_at` >='".Carbon::now()."' and car_uw.ur_id is null")
            ->orderBy('uc.cid','desc')
            ->get();
        //计算线路手续费
        $consumeList->map(function (&$item, $key) use ($user_id) {
            //使用avaliable_money字段计算提现手续费
            if($item->recharge_type !=2) $fee =  0;
            else{
                $fee  = calcWithdrawFee($user_id, $item->money,'member');
            }
            $item->fee = $fee;
            $item->recharge_name      =  get_recharge_name($item->recharge_type);
        });
        
        return $consumeList;
    }

    /**
     * 提现线路列表
     * @return mixed
     */
    public function wichdrawalAvaliableList_v2()
    {
        $user_id = $this->user()->id;

        $withdraw_list  =  HcUserWithdraw::where('user_id', $user_id)->where('status','<>', 4)->get(['ur_id','ur_ids','uw_id']);
        $has_withdraw_recharge_ids = [];
        if($withdraw_list->count())
        {
            foreach ($withdraw_list as $withdraw)
            {
                if(!strpos($withdraw->ur_ids,','))
                {
                    $has_withdraw_recharge_ids[] = $withdraw->ur_id;
                }else{
                    $ids  =  array_filter(explode(',', $withdraw->ur_ids));
                    $has_withdraw_recharge_ids = array_merge($has_withdraw_recharge_ids,$ids);
                }
            }
        }

        $list  =  HcUserRecharge::with(['line','consume','user_bank'])->where('hc_user_recharge.user_id', $user_id)
                                ->whereNotIn('hc_user_recharge.ur_id', $has_withdraw_recharge_ids)
                                ->leftJoin('hc_user_account_log','hc_user_account_log.item_id','hc_user_recharge.ur_id')
                                ->where('hc_user_account_log.type', 1 )
                                ->where('hc_user_account_log.type', 1 )
                                ->where('hc_user_account_log.wichdraw_end_at', '>=', Carbon::now()->toDateTimeString() )
                                ->select('hc_user_recharge.*','hc_user_account_log.ua_log_id')
                                ->get();
        //合并提现线路
        $line_list  = [];
        if($list)
        {
            foreach($list as $recharge)
            {
                $uwl_id = $recharge->uwl_id;
                if(!isset($line_list[$uwl_id]))
                {
                    //使用avaliable_money字段计算提现手续费
                    if($recharge->recharge_type !=2) $fee =  0;
                    elseif(isset($recharge->user_bank) && trim($recharge->user_bank->province.$recharge->user_bank->city)!='' ){
                        $fee = 0;
                    }else{
                        $fee  = calcWithdrawFee($user_id, $recharge->money,'member');
                    }
                    $recharge->fee            =  $fee;
                    $recharge->ur_ids         =  $recharge->ur_id;
                    $recharge->recharge_name  =  get_recharge_name($recharge->recharge_type);
                    $recharge->avaliable_money= $recharge->consume->avaliable_money;
                    $line_list[$uwl_id]       = $recharge;
                }else{
                    $line_list[$uwl_id]->money          += $recharge->money;
                    $line_list[$uwl_id]->recharge_money += $recharge->recharge_money;
                    $line_list[$uwl_id]->ua_log_id      .= ','.$recharge->ua_log_id;
                    $line_list[$uwl_id]->ur_ids          .= ','.$recharge->ur_id;
                }
            }
        }
        return $line_list;
    }

    /**
     * 查询提现额度
     */
    public function getWithdrawalCeiling()
    {
        $user_id = $this->user()->id;
        $where = "car_hc_user_consume.`wichdraw_end_at` >='".Carbon::now()."' and car_uw.ur_id is null";
        $where .= " and car_hc_user_consume.avaliable_money >0";
        $col = "car_hc_user_consume.*,car_ur.recharge_type,car_ur.alipay_user_name,car_ur.`bank_name`,car_ur.`bank_account`,car_ur.`user_bank_name`";
        return  \App\User::findOrFail($user_id)
            ->UserConsume()
            ->select(DB::raw($col))
            ->leftJoin('hc_user_withdraw as uw','uw.ur_id','hc_user_consume.ur_id')
            ->leftJoin('hc_user_recharge as ur','hc_user_consume.ur_id','ur.ur_id')
            ->whereRaw($where)
            ->orderBy('hc_user_consume.created_at','desc')
            ->paginate(10);
    }

    /**
     * 查询提现额度第二版本
     */
    public function getWithdrawalCeiling_v2()
    {
        $user_id = $this->user()->id;
        $recharge_list =  HcUserRecharge::with('consume')->where('user_id', $user_id)
                        ->whereIn('status', [2,3])
                        ->orderBy('created_at','desc')
                        ->paginate(10);
        //封装数据
        if($recharge_list->items())
        {
            foreach ($recharge_list->items() as $recharge)
            {
                $withdraw = HcUserWithdraw::where('ur_id', $recharge->ur_id)
                                ->whereIn('status',[1,5,6])
                                ->first();
                if($withdraw){
                    $recharge->withdraw = $withdraw;
                }
                if($recharge->recharge_type != 2)
                {
                    $recharge->recharge_type_name = $this->_get_online_pay_type($recharge->recharge_type);
                }
            }
        }
        return $recharge_list;
    }

    /**
     * 查询提现额度
     */
    public function getTotal()
    {
        $user_id = $this->user()->id;
        //$logTable = (new HcUserAccountLog())->getTable();
        //计算收入总金额
        $addTotal = HcUserAccountLog::where(['user_id'=>$user_id,'money_type'=>'+'])->sum('credit_avaliable');
        //计算支出总金额
        $delTotal = HcUserAccountLog::where(['user_id'=>$user_id,'money_type'=>'-'])->sum('money');
        //计算冻结总金额
        $FreezeTotal = HcUserAccountLog::where(['user_id'=>$user_id,'money_type'=>'+'])
            ->whereRaw("`wichdraw_end_at` <'".Carbon::now()."'")
            ->sum('credit_avaliable');
        //计算可用余额
        $avaliable_deposit = $addTotal-$delTotal-$FreezeTotal;
        //计算提现总金额
        $total_deposit     = $addTotal- $delTotal;
        return (object) ['total_deposit'=>$total_deposit,'avaliable_deposit'=>$avaliable_deposit];
    }

    /**
     *  查询可提现总额
     */
     public function get_all_confirm_recharge()
     {
        $user_id = $this->user()->id;

        $sql  = "select sum(ur.recharge_money) as total FROM car_hc_user_recharge as ur LEFT JOIN  ".
                    "(select ur_id as i,user_id from car_hc_user_withdraw) as uw  ".
                    "ON ur.ur_id=uw.i where uw.i IS NULL  and ur.user_id='".$user_id."' and ur.status in (2,3)";
        $ret = DB::select($sql);

        return $ret[0]->total?$ret[0]->total:0;
     }



    /**
     * 查询提现记录
     * @return mixed
     */
    public function getWichdrawalList()
    {
        $user_id = $this->user()->id;
        $logTable = 'car_'.(new HcUserAccountLog())->getTable();
        $start_date = $this->get('start_date','');
        $end_date   = $this->get('end_date','');
        $status     = $this->get('status','');
        $where = "type = 2";
        if(!empty($start_date) && !empty($end_date)){
            $startDate = str_replace(['年','月','日'],['-','-',''],$start_date);
            $endDate   = str_replace(['年','月','日'],['-','-',''],$end_date).' 23:59:59';
            $where .= " and ({$logTable}.created_at between '{$startDate}' and '{$endDate}')";
            $search['start_date'] = $start_date;
            $search['end_date']   = $end_date;
        }else{
            $search['start_date'] = Carbon::now()->subMonth()->format('Y年m月d日');
            $search['end_date']   = Carbon::now()->format('Y年m月d日');
        }
        if($status !='' && $status !=1){
            $sqlStatusArr = [2=>0,3=>1,4=>4,5=>2];
            $sqlStatus = $sqlStatusArr[$status];
            $where .= " and {$logTable}.status = '{$sqlStatus}'";
        }
        $search['status'] = $this->setStatus($status);

        $page =  \App\User::findOrFail($user_id)
            ->UserAccountLog()
            ->select(DB::raw("car_hc_user_account_log.*,GROUP_CONCAT(DISTINCT car_uwl.account_name) as line_all"))
            ->leftJoin('hc_user_withdraw as uw','uw.ulog_id','ua_log_id')
            ->leftJoin('hc_user_withdraw_line as uwl','uwl.uwl_id','uw.line_id')
            ->groupBy('uw.ulog_id')
            ->orderBy('hc_user_account_log.created_at','desc')
            ->whereRaw($where)
            ->paginate(10);
        return ['page'=>$page,'search'=>$search];
    }
    public function getWichdrawalList_v2()
    {
        $user_id = $this->user()->id;
        $start_date = $this->get('start_date','');
        $end_date   = $this->get('end_date','');
        $status     = $this->get('status','');
        $where = " user_id=".$user_id;
        if(!empty($start_date) && !empty($end_date)){
            $startDate = str_replace(['年','月','日'],['-','-',''],$start_date);
            $endDate   = str_replace(['年','月','日'],['-','-',''],$end_date).' 23:59:59';
            $where .= " and (created_at between '{$startDate}' and '{$endDate}')";
            $search['start_date'] = $start_date;
            $search['end_date']   = $end_date;
        }else{
            $search['start_date'] = Carbon::now()->subMonth()->format('Y年m月d日');
            $search['end_date']   = Carbon::now()->format('Y年m月d日');
        }
            if($status !='' && $status !=1){
            $sqlStatusArr = [2=>0,3=>1,4=>4,5=>2];
            $sqlStatus = $sqlStatusArr[$status];
            $where .= " and status = '{$sqlStatus}'";
        }
        $search['status'] = $this->setStatus($status);
        $list  =   HcUserWithdraw::with(['recharge','line'])->whereRaw($where)->orderBy('created_at','desc')->paginate(10);
        //数据封装
        if($list->items())
        {
            foreach ( $list->items() as $item)
            {
                //银行转账合并
                if(isset($item->recharge) && $item->recharge->recharge_type != 2){
                    $item->line_name  =  '线上支付--'.get_recharge_name($item->recharge->recharge_type).substr($item->line->account_code, 0,3).'***';
                    $item->true_money = $item->money-$item->fee;
                }else{
                    $item->line_name  =  '银行转账--***'.substr($item->line->account_code, 0,3).mb_substr($item->line->account_name,0,1).'***';
                    $item->true_money = $item->money-$item->fee;
                }
            }
        }

        return ['page'=>$list,'search'=>$search];
    }

    private function setStatus($val=null)
    {
        $_val = is_null($val) || empty($val) ? 1 : $val ;
        $arr = [1=>"不限",2=>"正在办理",3=>"已完成",4=>"未成功",5=>"部分未成功"];
        return $arr[$_val];
    }

    /**
     * 提现详情
     * @param $log_id
     * @return mixed
     */
    public function getWichdrawalUserList($log_id)
    {
        $user_id = $this->user()->id;
        $col = "car_hc_user_withdraw.*,car_ur.alipay_user_name,car_ur.bank_name,car_ur.bank_account,car_ur.user_bank_name,";
        $col .= "car_ur.recharge_type as recharge_type,car_ur.voucher";
        return HcUserWithdraw::select(DB::raw($col))
            ->where(['hc_user_withdraw.ulog_id'=>$log_id,'hc_user_withdraw.user_id'=>$user_id])
            ->leftJoin('hc_user_recharge as ur','ur.ur_id','hc_user_withdraw.ur_id')
            ->orderByRaw(' (case when recharge_type=2 then 1  when recharge_type=1 then 2  end ) desc'  )
            ->get();
    }

    /**
     * 提现详情  wangjiang
     * @param $log_id
     * @return mixed
     */
    public function getWichdrawalDetailList($log_id)
    {
        $user_id     = $this->user()->id;
        //提现列表
        $user_account_log  = HcUserAccountLog::where('ua_log_id', $log_id)->firstOrFail();
        $list        = HcUserWithdraw::with('recharge','line')
                        ->leftJoin('hc_user_recharge','hc_user_recharge.ur_id','hc_user_withdraw.ur_id')
                        ->select('hc_user_withdraw.*', 'hc_user_recharge.recharge_type as recharge_type','hc_user_recharge.alipay_user_name as alipay_user_name')
                        ->where('ulog_id',$log_id)
                        ->where('hc_user_withdraw.user_id',$user_id)
                        ->orderByRaw(' (case when recharge_type=2 then 1  when recharge_type=1 then 2  end ) desc'  )
                        ->get();
        // 提现组合列表
        $group_list  = $list->groupBy(function ($item, $k){
            return $item->recharge_type . '-' . $item->alipay_user_name;
        });

        //合并线上支付 同一个支付方式的金额
        $offline_list     = collect();
        $online_list      = collect();
        foreach ($group_list as $key => $group)
        {
            //线上支付组合数据
            $pay_data =  explode('-', $key);
            if($pay_data[0] == 2) {
                $offline_list = $group;
                continue;
            }
            $temp_group  = [];
            //总的支付结算
            $pay_method_data  = new \stdClass();
            $pay_method_data->created_at       = $user_account_log->created_at;
            $pay_method_data->ulog_id               = $user_account_log->ua_log_id;
            $pay_method_data->pay_method       = '线上支付';
            $pay_method_data->pay_type         =  $this->_get_online_pay_type($pay_data[0]);
            $pay_method_data->alipay_user_name =  $pay_data[1];
            $pay_method_data->money            =  0;

            foreach ($group as $item)
            {
                $pay_method_data->money += $item->money;
            }
            $temp_group['sum_collect']  = $pay_method_data;
            $temp_group['list']  = $group;
            $online_list->push($temp_group) ;
        }

        return ['offline_list'=>$offline_list ,  'online_list'=>$online_list];
    }

    /**
     * 提现详情  wangjiang
     * @param $log_id
     * @return mixed
     */
    public function getWichdrawalDetailList_v2($uw_id)
    {
        $user_id     = $this->user()->id;
//        $user_account_log  = HcUserAccountLog::where('ua_log_id', $log_id)->firstOrFail();
        $withdraw        = HcUserWithdraw::with(['recharge','line','user_log'])
            ->leftJoin('hc_user_recharge','hc_user_recharge.ur_id','hc_user_withdraw.ur_id')
            ->select('hc_user_withdraw.*', 'hc_user_recharge.recharge_type as recharge_type','hc_user_recharge.alipay_user_name as alipay_user_name')
            ->where('uw_id',$uw_id)
            ->where('hc_user_withdraw.user_id',$user_id)
            ->first();

        $withdraw->pay_type         =  $this->_get_online_pay_type($withdraw->recharge->recharge_type);
        //封装数据
        if(strpos($withdraw->ur_ids,',')){
            $ur_id_array  =  array_filter(explode(',', $withdraw->ur_ids));
        }else{
            $ur_id_array[] =  $withdraw->ur_id ;
        }
        $ur_list      =  HcUserRecharge::whereIn('ur_id',$ur_id_array)->get();
        $withdraw->recharges = $ur_list;
        return $withdraw;
    }

    /**
     * @param $type
     */
    private function _get_online_pay_type($type)
    {
        switch ($type)
        {
            case '1' :  return "支付宝";
            case '3' :  return "财付通";
            default: return "未知支付方式";
        }
    }
}