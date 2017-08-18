<?php

namespace App\Http\Controllers\Member;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HcUserAccount;
use App\Models\HcOrderJiaXinBao;

class FinancialController extends Controller
{
    const NAV_TAB = 'myBalance';

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * 我的余额
     */
    public function myBalance(Request $request)
    {
        $data['title'] = '我的余额';
        $data['nav'] = self::NAV_TAB;
        $user_id = $request->user()->id;
        $start_date = $request->get('start_date', '');
        $end_date = $request->get('end_date', '');
        if (!empty($start_date) && !empty($end_date)) {
            $startDate = str_replace(['年', '月', '日'], ['-', '-', ''], $start_date);
            $endDate = str_replace(['年', '月', '日'], ['-', '-', ''], $end_date);
            $where = "created_at between '{$startDate}' and '{$endDate} 23:59:59'";
            $gtYearNum = $endDate - $startDate;
            $search['start_date'] = $startDate;
            $search['end_date']   = $endDate;
        } else {
            /*$yearDate = Carbon::now()->subYear()->toDateTimeString();
            $where    = "created_at between '{$yearDate}' and NOW()";*/
            $where = "ua_log_id > 0 and status = 1";
            $gtYearNum = 0;
            $search['end_date'] = Carbon::now()->toDateString();
            $search['start_date']   = Carbon::now()->subMonth()->toDateString();
        }
        //一年内的记录
        $logList = \App\User::findOrFail($user_id)->UserAccountLog()
            ->whereRaw($where)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        if ($gtYearNum >= 1){
            //超过一年的记录
            $gtYearMoney = \App\User::findOrFail($user_id)->UserAccountLog()->whereRaw("created_at < '{$startDate}'")->sum('money');
            if ($gtYearMoney >0) {
                $data['gtYear'] = (object) ['dateTime'=>Carbon::now()->subYear()->format('Y-m-').'01 00:00:01','money'=>$gtYearMoney];
            }
         }

        $data['logs'] = $logList;
        //查询用户可用余额
        $userAccount = \App\User::findOrFail($user_id)->UserAccount()->value('avaliable_deposit');
        //查询加信宝
        // $myPayAccount = \App\User::findOrFail($user_id)->UserAccountLog()->where('is_freeze',11)->sum('money');
        $myPayAccount = \App\User::findOrFail($user_id)->UserAccount()->value('freeze_deposit');;
        $data['Account'] = (object) ['avaliable_deposit'=>$userAccount,'PayTotal'=>$myPayAccount];
        $data['search']  = $search;
        //dd($LogList->render());
        return view('HomeV2.User.Financial.index')->with($data);
    }

    /**
     * 加信宝
     */
    public function myPay(Request $request)
    {
        $data['title'] = '我的加信宝';
        $data['nav'] = self::NAV_TAB;
        $user_id = $request->user()->id;
        $user_account = HcUserAccount::getUserAccountById($user_id);
        $data['payTotal'] = $user_account->freeze_deposit;
        $data['pays'] = HcOrderJiaXinBao::where(['is_del' => 0, 'user_id' => $user_id])->orderBy('id', 'desc')->paginate(10);

        return view('HomeV2.User.Financial.my_pay')->with($data);
    }
}
