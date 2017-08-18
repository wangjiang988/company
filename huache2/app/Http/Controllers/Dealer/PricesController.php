<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Requests\DealerPricesRequest;
use App\Http\Controllers\Controller;
use App\Models\HgUser;
use Carbon\Carbon;
use App\Models\SettlementFilecount;
use App\Models\HcSettlement;
use App\Models\HcFilter;
use App\Models\HcFilterTemplate;

class PricesController extends Controller
{
    public function getIndex(DealerPricesRequest $request,$type='')
    {
        $_prefix    = 'show';
        $fillable   = ['capitalpool','pay','recharge','withdrawal','application','settlement','recharge_voucher','recharge_success','recharge_error','put_withdrawal','freeze_detail'];
        $ucfirstStr = in_array($type,$fillable) ? $type : 'all' ;
        $method     = $_prefix.ucfirst($ucfirstStr);
        $result    = $this->$method($request);
        if($request->ajax()){
            return $result;
        }
        if(empty($result['tpl'])){
            abort(404, '模板文件不存在！');exit;
        }
        return view($result['tpl'])->with($result['data']);
    }
    /**
     * 资金管理总览
     * @param DealerPricesRequest $request
     * @return $this
     */
    private function showAll(DealerPricesRequest $request){
        $data['title'] = '资金管理';
        $data['flag']  = 'myPrices';
        //加信宝浮动资金
        //$newAccount->freezeTotal = $request->getFreezeTotal();
        $data['account'] = $request->getSellerTotal();
        //结算文件剩余\
        $member_id  = session('user.member_id');
        $data['filecount'] = SettlementFilecount::where(['member_id'=>$member_id])->first();
        $data['filecount'] =  isset($data['filecount']->file_number)? $data['filecount']->file_number: 0;

        //结算信息列表
        //最新一个月有收入的记录
        $from = Carbon::now()->subYear()->addMonth()->startOfMonth()->toDateTimeString();
        $data['last_settlement']  = HcSettlement::where('member_id',$member_id)
            ->where('service_income','>','0')
            ->where('created_at','>',$from)
            ->orderBy('created_at','desc')
            ->first();


        $tpl = 'dealer.Funds.index';
        return ['tpl'=>$tpl,'data'=>$data];
    }
    /**
     * 资金池
     * @param DealerPricesRequest $request
     */
    private function showCapitalpool(DealerPricesRequest $request)
    {
        $page = $request->get('page',1);
        $data['title'] = '资金池';
        $data['flag'] = 'capitalPool';
        $account = $request->getSellerTotal();
        if ($account->avaliable_deposit < 0) {//todo 下架代理商所以报价
            $OverdraftLog = $request->getSellerOverdraftLog();
            if (!$OverdraftLog) {
                //todo 调试阶段不抛出错误，正式上线应该有错误页面
                //throw new \Exception('未添加透支日志，请联系相关人员。。。');
                $data['overdraftLog'] = null;
            }else{
                $data['overdraftLog'] = $this->getSellerOverdraftTime($account, $OverdraftLog);
            }
        }
        $data['account'] = $account;
        $result = $request->getSellerLogList();
        $tpl = 'dealer.Funds.capital_pool';
        return ['tpl'=>$tpl,'data'=>array_merge($data,$result)];
    }
    /**
     * 计算透支时间
     * @param $account
     * @param $OverdraftLog
     * @return array
     * @throws \Exception
     */
    private function getSellerOverdraftTime($account,$OverdraftLog){
        if ($account->credit_line >0) {
            $overdrartTime = strtotime($OverdraftLog->created_at) + (24*60*60);
            //判断24小时以内
            if($overdrartTime >= time()){
                $day = 1;
                $endDate = Carbon::parse($OverdraftLog->created_at)->addDay()->toDateTimeString();
            }else{
                $day =0;
                $endDate = Carbon::parse($OverdraftLog->created_at)->addDay(20)->toDateTimeString();
            }
            return ['start_date' => Carbon::now()->toDateTimeString(), 'end_date' => $endDate,'is_day'=>$day];
        }else {
            //todo 授信额度透支！
            throw new \Exception('授信额度透支！');
        }
    }
    /** 加信宝
     * @param DealerPricesRequest $request
     */
    private function showPay(DealerPricesRequest $request)
    {
        $data['title'] = '加信宝';
        $data['flag']  = 'hwachePay';
        $data['freezeTotal'] = $request->getFreezeTotal();
        $result              = $request->getFreezeList();

        $tpl = 'dealer.Funds.my_pay';
        return ['tpl'=>$tpl,'data'=>array_merge($data,$result)];
    }

    /**
     * 冻结详情
     */
    private function showFreeze_detail(DealerPricesRequest $request)
    {
       $order_id = $request->get('id');
       $data['list']    = $request->getFreezeDetail($order_id);
       $data['orderSn'] = \App\Models\HgOrder::where('id',$order_id)->value('order_sn');
       $data['freezeTotalMoney'] = $request->getFreezeOrderMoney($order_id);
       return response()->json($data);
    }

    /** 我的充值
     * @param DealerPricesRequest $request
     */
    private function showRecharge(DealerPricesRequest $request)
    {
        $data['title'] = '我的充值';
        $data['flag']  = 'myRecharge';

        $sellerBank = $request->getSellerBankToName();
        if(!$sellerBank || empty($sellerBank->seller_bank_account)){
            abort(404,'代理商银行账号不存在');exit;
        }
        $data['bank']   = $request->getSellerBankToName();
        $result = $request->getSellerRechargeList();
        $tpl = 'dealer.Funds.recharge';
        return ['tpl'=>$tpl,'data'=>array_merge($data,$result)];
    }

    /**
     * 充值凭证
     * @param DealerPricesRequest $request
     * @return array
     */
    public function showRecharge_voucher(DealerPricesRequest $request)
    {
        if($request->isMethod('post')){
            $rule = [
                'price'       => 'required|numeric',
                'voucher'     => 'required|image'
            ];
            $this->validate($request,$rule);
            $money   = $request->input('price');
            $voucher = simpleUpFile($request->file('voucher'));
            $isRecharge = $request->addRecharge($money,$voucher);
            if($isRecharge){
                return response()->json(setJsonMsg(1,'提交凭证成功'));
            }else{
                return response()->json(setJsonMsg(0,'提交凭证失败'));
            }
            exit;
        }else{
            $data['title'] = '提交充值凭证';
            $data['flag']  = 'myRecharge';
            $data['bank'] = $request->getSellerBankToName();
                //$sellerBank->seller_bank_photo2;
            $tpl = 'dealer.Funds.voucher';
            return ['tpl'=>$tpl,'data'=>$data];
        }
    }

    /**
     * @return array
     */
    private function showRecharge_success(DealerPricesRequest $request)
    {
        $data['title'] = '提交充值凭证成功';
        $data['flag']  = 'myRecharge';
        $data['success'] = $request->getSellerRechargeSuccess();
        $tpl = 'dealer.Funds.recharge_success';
        return ['tpl'=>$tpl,'data'=>$data];
    }
    /**
     * @return array
     */
    private function showRecharge_error()
    {
        $data['title'] = '提交充值凭证失败';
        $data['flag']  = 'myRecharge';
        $tpl = 'dealer.Funds.recharge_error';
        return ['tpl'=>$tpl,'data'=>$data];
    }
    /** 我的提款
     * @param DealerPricesRequest $request
     */
    private function showWithdrawal(DealerPricesRequest $request)
    {
        $data['title'] = '我的提款';
        $data['flag']  = 'myWithdrawal';
        $result = $request->getSellerWithdrawalList();
        $tpl = 'dealer.Funds.withdrawal';
        return ['tpl'=>$tpl,'data'=>array_merge($data,$result)];
    }
    /** 申请提款
     * @param DealerPricesRequest $request
     */
    public function showApplication(DealerPricesRequest $request)
    {
        if($request->isMethod('post')){
            $this->validate($request,['money'=>'required|numeric']);
            $res = $request->saveWithdrawalApplication($request->input('money'),$request->input('fee'));
            if($res){
                return response()->json(setJsonMsg(1,'申请提现成功'));
            }else{
                return response()->json(setJsonMsg(1,'申请提现失败！'));
            }
        }else{
            $data['title']   = '申请提款';
            $data['flag']    = 'myWithdrawal';
            $data['bank']    = $request->getSellerBankToName();
            $data['account'] = $request->getSellerTotal();
            $data['txtCount'] = $request->getWithdrawalCount();
            //获取提现手续费模板
            $filter = HcFilter::where('type',21)->firstOrFail();
            if($filter && $filter->template_id)
                $template  = HcFilterTemplate::where('id', $filter->template_id)
                ->firstOrFail();
            $fee = 0;
            if($template && $template->content)//如果提现手续费
            {
                $filter_content = json_decode($template->content);
                $template->content = $filter_content;
                //不等于1的到页面输入具体提现数值再计算
                //1 为超过免费次数后，收取手续费
                if($filter_content->type == 1 && isset($filter_content->freetime)) {
                    if($data['txtCount'] > $filter_content->freetime)
                            $fee = isset($filter_content->fee)?$filter_content->fee:0; 
                }
            }
            $data['fee']      =  $fee;
            $data['template'] =  $template;
            $data['user_id'] =$request->getLoginId();
            $tpl = 'dealer.Funds.application';
            return ['tpl'=>$tpl,'data'=>$data];
        }
    }
    /**
     * 终止提现
     * @param DealerPricesRequest $request
     * @return array
     */
    public function showPut_withdrawal(DealerPricesRequest $request)
    {
        $dwb_id = $request->get('dwb_id');
        //判断是否接单
        if($request->has('type') && $request->ajax()){
            if($request->get('type') == 'check'){
                $isCheck = $request->checkStatus($dwb_id);
                return ($isCheck) ? setJsonMsg(1,'可终止') : setJsonMsg(0,'不可终止！');
            }
        }
        $res = $request->updateLogStatus($dwb_id);
        return ($res) ? setJsonMsg(1,'终止成功') : setJsonMsg(0,'终止失败！');
    }
    /**
     * 结算
     * @author wangjiang
     */
    public function showSettlement(DealerPricesRequest $request)
    {
        $data['title'] = '结算';
        $data['flag']  = 'settlement';
        $tpl = 'dealer.Funds.settlement';
        return view($tpl)->with($data);
    }



}
