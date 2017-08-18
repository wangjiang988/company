<?php
/**
 * 我的提款记录
 */
namespace App\Http\Controllers\Member;

use App\Http\Requests\WichdrawalApplication;
use App\Http\Requests\WichdrawalLine;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\Contracts\HcUserWithdrawRepositoryInterface;
use App\Repositories\Contracts\HcUserAccountLogRepositoryInterface;
use App\Repositories\Contracts\HcUserAccountRepositoryInterface;
use App\Repositories\Contracts\HcUserConsumeRepositoryInterface;

use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    const NAV_TAB = 'Withdrawal';
    protected $wichdrawal,$userAccountLog,$userAccount,$userConsume;

    public function __construct(
        HcUserWithdrawRepositoryInterface $hcUserWithdrawRepository,
        HcUserAccountLogRepositoryInterface $hcUserAccountLogRepository,
        HcUserAccountRepositoryInterface $hcUserAccountRepository,
        HcUserConsumeRepositoryInterface $hcUserConsumeRepository
        )
   {
        $this->middleware('auth');
        $this->wichdrawal     = $hcUserWithdrawRepository;
        $this->userAccountLog = $hcUserAccountLogRepository;
        $this->userAccount    = $hcUserAccountRepository;
        $this->userConsume    = $hcUserConsumeRepository;
    }
    /**
     * 列表
     */
    public function getIndex(WichdrawalApplication $request)
    {
        $data['title'] = '我的提款记录';
        $data['nav']   = self::NAV_TAB;
        $result  = $request->getWichdrawalList_v2();
        $data['list']   = $result['page'];
        $data['search'] = $result['search'];
        return view('HomeV2.User.Withdrawal.index')->with($data);
    }

    /**
     * 额度管理
     */
    public function showCeiling(WichdrawalApplication $request)
    {
        $user_id = $request->user()->id;
        $data['title'] = '提现额度管理';
        $data['nav']   = self::NAV_TAB;

        //查询用户可用余额
        $userAccount = \App\User::findOrFail($user_id)->UserAccount()->value('avaliable_deposit');
        if($userAccount > 0){
            $withdrawalList   = $request->getWithdrawalCeiling_v2();
            $isWithdrawalList = count($withdrawalList->toArray());
            if(!empty($isWithdrawalList)){
                $data['withdrawalCeiling'] = $withdrawalList;
            }
        }

        //查询提醒总金额及可提醒额度
        $total = $request->get_all_confirm_recharge();
        $data['total'] = $total;
        $data['avaliable_deposit'] = $userAccount;

        return view('HomeV2.User.Withdrawal.ceilling_index')->with($data);
    }

    /**
     * 提现线路管理
     * @param WichdrawalLine $request
     * @return $this
     */
   public function showLine(WichdrawalLine $request)
   {
        $data['title'] = '提现线路管理';
        $data['nav']   = self::NAV_TAB;
        $userLine = $request->getLineAll_v2();
        if($userLine){
            $data['line'] = $userLine;
        }
        return view('HomeV2.User.Withdrawal.line')->with($data);
   }

    /**
     * 提现详情
     * @param WichdrawalApplication $request
     * @param $id
     * @return $this
     */
    public function showDetail(WichdrawalApplication $request,$id)
    {
        $data['title'] = '提现详情';
        $data['nav']   = self::NAV_TAB;
        $data['withdraw']  = $request->getWichdrawalDetailList_v2($id);
        return view('HomeV2.User.Withdrawal.detail')->with($data);
    }

    /**
     * 提现申请
     */
    public function showApplication(WichdrawalApplication $request)
    {
        $user_id = $request->user()->id;
        if($request->isMethod('post')){
            $res = $request->saveWichdrawalData();
            if($res){
                //TODO 提现申请成功发送短信
                $options = ['time'=>Carbon::now()->toDateTimeString()];
                (new \App\Models\SendSmsLog())->sendSms($request->user()->phone,'78530072',$options);
            }
            $data = ($res) ? setJsonMsg(1,'提交成功') : setJsonMsg(0,'提交失败！') ;
            return response()->json($data);
        }else{
            $data['title'] = '提现申请';
            $data['nav']   = self::NAV_TAB;
            //查询用户可用余额
            $userAccount = \App\User::findOrFail($user_id)->UserAccount()->first();
            if($userAccount && $userAccount->avaliable_deposit > 0){
                $withdrawalList   = $request->wichdrawalAvaliableList_v2();
                $data['withdrawalLine']  =  $withdrawalList;
            }

            //查询提现总金额
            $data['user_id'] = $user_id;
            $data['avaliable_deposit']   = $userAccount ? $userAccount->avaliable_deposit : 0;
            return view('HomeV2.User.Withdrawal.application')->with($data);
        }
    }

    /**
     * 维护提现开户行信息
     * @param Request $request
     * @param $id
     * @return $this
     */
    public function showBankEdit(Request $request,$id)
    {
        if($request->isMethod('post')){
            $userBank = \App\User::find($request->user()->id)->UserBank(true, ['is_default' => 1, 'id' => $id])->first();
            $userBank->province  = $request->province;
            $userBank->city      = $request->city;
            $userBank->bank_address  = $request->bank_address;
            $userBank->save();
            return  redirect()->route('Withdrawal.Line');exit;
        }else {
            $userBank = \App\User::find($request->user()->id)->UserBank(true, ['is_default' => 1, 'id' => $id])->first();
            $userInfo = \App\User::getUserHomeInfo($request->user()->id);
            //$find->last_name.$find->first_name
            $userBank->bank_id = $id;
            $userBank->real_name = $userInfo->last_name . $userInfo->first_name;

            $data['title'] = '维护提现开户行信息';
            $data['nav'] = self::NAV_TAB;
            $data['find'] = $userBank;
            return view('HomeV2.User.Withdrawal.bank_edit')->with($data);
        }
    }
}
