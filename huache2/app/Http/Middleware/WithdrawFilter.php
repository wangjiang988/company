<?php

namespace App\Http\Middleware;

use App\Models\HcAccountLog;
use App\Models\HcDailiAccountLog;
use App\Models\HcDailiWithdrawBank;
use App\Models\HcUserAccountLog;
use App\Models\HcUserWithdraw;
use Closure;
use App\Models\HcFilter;
use App\Models\HcFilterTemplate;
use App\Models\HcOrderConciliation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WithdrawFilter
{

    const DEALER_FILTER_TYPE = 22;
    const MEMBER_FILTER_TYPE = 12;

    /**
     * 提现拦截器
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        switch ($role) {
            case 'member': //客户提现
                $filter = HcFilter::where('type', self::MEMBER_FILTER_TYPE)->firstOrFail(['template_id']);
                break;
            case 'dealer': //售方提现
                //获取售方拦截条件
                $filter = HcFilter::where('type', self::DEALER_FILTER_TYPE)->firstOrFail(['template_id']);
                break;
            default:
                ;
        }

        if(isset($filter->template_id) && $filter->template_id)
        {
            $template_ids = explode(',', $filter->template_id);
            $template_list  =  HcFilterTemplate::whereIn('id',$template_ids)->get();
            //处理拦截模板
            if($template_list->count());
               $ret =  $this->_handle_filter($template_list, $request);
            if($ret)
            {
                return $ret;
            }
        }

        return $next($request);
    }

    /**
     * 处理拦截模板
     */
    private function _handle_filter($template_list, $request)
    {
        foreach ($template_list as $template)
        {
            $content =  json_decode($template->content);
            switch ($content->type)
            {
                case "member_unfinished_order"://客户争议工单大于0

                    $count  =  DB::table('hc_order')
                                ->leftJoin('hc_order_conciliation', 'hc_order.id', '=', 'hc_order_conciliation.order_id')
                                ->where('hc_order_conciliation.target',2)//1，平台，2,客户，3.售方
                                ->where('hc_order_conciliation.status','!=',3) //3 已了结
                                ->where('hc_order.user_id',$request->user_id)  //3 当前用户
                                ->count();
                    if($count){
//                    if(1){
                        //提现记录log
                        $log  = new HcUserAccountLog();
                        $log->user_id   =  $request->user_id;
                        $log->money     =  $request->total_money;
                        $log->credit_avaliable  =  $request->avaliable_deposit;
                        $log->type   =  2;
                        $log->item   = '提现';
                        $log->pay_type   =  2;
                        $log->status     =  5; //提现被拦截
                        $log->remark     =  "客户{$request->user_id}提现申请，客户争议工单大于0，拦截成功！";
                        $log->money_type = "-";
                        $log->save();

                        //提现记录
                        if(is_array($request->money)) {
                            foreach ($request->money as $k => $money) {
                                $withdraw  = new HcUserWithdraw();
                                $withdraw->ulog_id =  $log->ua_log_id;
                                $withdraw->user_id =  $request->user_id;
                                $withdraw->ur_id   =  $request->ur_id[$k];
                                $withdraw->line_id =  $request->uwl_id[$k];
                                $withdraw->money   =  $money;
                                $withdraw->fee     =  $request->fee[$k];
                                $withdraw->status  = 4;
                                $withdraw->reject_status =  41;
                                $withdraw->remark  =  "售方{$request->user_id}提现申请，客户争议工单大于0，拦截成功！";
                                $withdraw->save();
                            }
                        }

                        Log::error("客户{$request->user_id}提现申请，当前客户争议工单大于0，拦截成功！");
                        return response()->json(['message'=>'客户争议工单大于0'], 417);
                    }
                    break;
                case "dealer_unfinished_order"://售方争议工单大于0
                    $count  =  DB::table('hc_order')
                        ->leftJoin('hc_order_conciliation', 'hc_order.id', '=', 'hc_order_conciliation.order_id')
                        ->where('hc_order_conciliation.target',2)//1，平台，2,客户，3.售方
                        ->where('hc_order_conciliation.status','!=',3) //3 已了结
                        ->where('hc_order.seller_id',$request->user_id)  //3 当前用户
                        ->count();
                    if($count){
                        Log::error("售方提现申请，客户争议工单大于0，拦截成功！");
                        //生成一条提现记录
                        $withdraw  = new HcDailiWithdrawBank();
                        $withdraw->d_id   =  $request->user_id;
                        $withdraw->bank_name  =  $request->seller_bank_addr;
                        $withdraw->bank_account  =  $request->seller_bank_account;
                        $withdraw->daili_bank_name  =  $request->sellerName;
                        $withdraw->money  =  $request->money;
                        $withdraw->fee  =  $request->fee;
                        $withdraw->kefu_confirm_status   = 5;
                        $withdraw->reject_status =  51; //
                        $withdraw->remark =  "售方{$request->user_id}提现申请，客户争议工单大于0，拦截成功！";
//                        $withdraw->money_type = '-';
                        $withdraw->save();
                        //生成一条售方申请失败记录LOG
                        $log  = new HcDailiAccountLog();
                        $log->d_id   =  $request->user_id;
                        $log->money  =  $request->money;
                        $log->credit_avaiable  =  $request->avaliable_deposit;
                        $log->type   =  2;
                        $log->item   = '提现';
                        $log->pay_type   =  2;
                        $log->status =  5; //
                        $log->remark =  "售方{$request->user_id}提现申请，客户争议工单大于0，拦截成功！";
                        $log->money_type = '-';
                        $log->save();

                        return response()->json(['message'=>'售方争议工单大于0'], 417);
                    }
                    break;
                default:;
            }
            return 0;
        }
    }

}