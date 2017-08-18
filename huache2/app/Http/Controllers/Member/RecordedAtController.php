<?php
/**
 * 我的入账
 */
namespace App\Http\Controllers\Member;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\HcUserAccountLogRepository;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Models\HcUserRecharge;
use App\Models\HcUserWithdraw;

class RecordedAtController extends Controller
{
    const NAV_TAB = 'RecordedAt';
    protected $repositories;
    public function __construct(HcUserAccountLogRepository $log)
    {
        $this->middleware('auth');
        $this->repositories = $log;
    }
    /**
     * 列表
     */
    public function getIndex(Request $request)
    {
        $data['title'] = '我的入账';
        $data['nav']   = self::NAV_TAB;
        $user = User::find($request->user()->id);
        $start_date = $request->get('start_date','');
        $end_date   = $request->get('end_date','');
        $create_start  = $request->get('create_start','');
        $create_end    = $request->get('create_end','');
        $pay_status    = $request->get('pay_status','-1');
        $status        = $request->get('status','-1');

        $where = "money_type='+'";
        if(!empty($start_date) && !empty($end_date)){
            $startDate = str_replace(['年','月','日'],['-','-',''],$start_date);
            $endDate   = str_replace(['年','月','日'],['-','-',''],$end_date);
            $where .= " and updated_at between '{$startDate}' and '{$endDate} 23:59:59'";
            $search['start_date'] = $start_date;
            $search['end_date']   = $end_date;
        }else{
            $search['start_date'] = Carbon::now()->subMonth()->format('Y年m月d日');
            $search['end_date']   = Carbon::now()->format('Y年m月d日');
        }
        if(!empty($create_start) && !empty($create_end)){
            $createStart = str_replace(['年','月','日'],['-','-',''],$create_start);
            $createEnd = str_replace(['年','月','日'],['-','-',''],$create_end);
            $where .= " and created_at between '{$createStart}' and '{$createEnd} 23:59:59'";

            $search['createStartDate'] = $create_start;
            $search['createEndDate']   = $create_end;
        }else{
            $search['createStartDate'] = Carbon::now()->subMonth()->format('Y年m月d日');
            $search['createEndDate']   = Carbon::now()->format('Y年m月d日');
        }
        if($pay_status !='' && $pay_status !=-1){
            $where .= " and pay_type = '{$pay_status}'";
        }else{
            $where .= " and pay_type in (1,2)";
        }
        $search['pay_status'] = $this->setStatus('pay_status',$pay_status);
        $search['status']     = $this->setStatus('status',$status);
        if($status !='' && $status != -1){
            $newStatus = ($status ==2) ? 0 : $status;
            $where .= " and status = '{$newStatus}'";
        }
        $list = $user->UserAccountLog()
            ->whereRaw($where)
            ->orderBy('created_at','desc')
            ->paginate(10);
        if($list->count())
        {
            foreach($list as $log)
            {
                if($log->type ==1) //线上支付，获取充值记录
                {
                    $log->recharge = HcUserRecharge::where('ur_id',$log->item_id)->first();
                }
                // if($log->type ==2) //提现，获取充值记录
                // {
                //     $log->withdraw = HcUserWithdraw::where('ur_id',$log->item_id)->first();
                // }
                $log->usage = $this->_get_usage($log->type);
            }
        }
        $data['list']   = $list;
        $data['search'] = $search;
        return view('HomeV2.User.RecordedAt.index')->with($data);
    }

    private function setStatus($name='pay_status',$_val='-1'){
        $val = is_null($_val) ? '-1' : $_val;
        $pay_status = [-1=>"全部",1=>"线上支付",2=>"银行转账"];
        $status     = [-1=>"全部",2=>"正在核实",1=>"已入账",4=>"无此款项"];
        return ($name == 'pay_status') ? $pay_status[$val] : $status[$val];
    }

    //获取user_account_log 用途 ，根据type字段判断
    private function _get_usage($type)
    {
        switch($type)
        {
            case "1": return "充值";
            case "2": return "提现";
            case "3": return "购买";
            case "4": return "退款";
            case "5": return "加信宝";
            case "6": return "特事审批";
        }
    }
    /**
     * 我的入账详情
     * @param Request $request
     * @param $id
     * @return $this
     */
    public function showDetail(Request $request,$id){
        $data['title'] = '我的入账详情';
        $data['nav']   = self::NAV_TAB;
        $Model = (new \App\Models\HcUserAccountLog());
        $table = $Model->getTable();
        $col = 'car_'.$table.'.*,car_ur.bank_name,car_ur.bank_account,car_ur.user_bank_name,car_ur.money as apply_money,';
        $col .= "car_ur.voucher,car_ur.alipay_user_name";
        $data['find'] = $Model->select(DB::raw($col))
                        ->leftjoin('hc_user_recharge as ur','ur.ur_id','item_id')
                        ->where('ua_log_id',$id)
                        ->first();
        return view('HomeV2.User.RecordedAt.detail')->with($data);
    }
}
