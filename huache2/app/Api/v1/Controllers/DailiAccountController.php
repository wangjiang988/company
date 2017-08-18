<?php

namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Auth;
use App\Models\HcDailiAccount;
use Carbon\Carbon;

class DailiAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('filter.inner_ip');
    }

    /**
     * 检查用户账户余额太低的情况
     */
    public function check_too_low($id, Request $request)
    {
        $daili_account   =   HcDailiAccount::getAccountInfo(['d_id'=>$id]);
        $result =  $this->_do_if_need_suspend_baojia($daili_account);
        return response()->json($result);
    }

    /**
     * 设置用户账户余额小于0时，
     */
    public function set_too_low_time($id, Request $request)
    {
        $daili_account   =   HcDailiAccount::getAccountInfo(['d_id'=>$id]);

        //余额小于0,但是保存前的余额大于0  记录到0时间
        if($daili_account->avaliable_deposit > 0){
            return  response()->json([
                    "status"  => false,
                    'code'    => 200,
                    'msg'     => '可用余额大于0，不能设置到0时间',
                ]);
        }

        $daili_account->down_to_zero_time  =  Carbon::now()->toDateTimeString();
        $ret = $daili_account->save();
        if($ret)
        {
            return  response()->json([
                "status"  => true,
                'code'    => 200,
                'msg'     => '设置成功',
                'data'     => $ret,
            ]);
        }else{
            return  response()->json([
                "status"  => false,
                'code'    => 500,
                'msg'     => '设置失败',
                'data'     => $ret,
            ]);
        }

    }


    //检查账号，查看是否需要下架所有产品
    //1.账户余额小于0，且授信额度也降低到0  暂时下架所有报价
    //2.账户余额小于0,且授信额度》=0  ，但是小于零的状态已经超过72小时，暂时下架所有报价
    private function _do_if_need_suspend_baojia(HcDailiAccount $daili_account)
    {
        //余额大于0
        if($daili_account->avaliable_deposit  >0 )
            return  [
                "status"  => false,
                'code'    => 500,
                'msg'     => '余额大于0',
            ];

        //condition 1
        $now_left_money                   =    (float) $daili_account->avaliable_deposit;
        $credit_line                      =    (float) $daili_account->credit_line;
        $ret = false;
        if($now_left_money+$credit_line <= 0) { //下架所有报价
            $ret = HcDailiAccount::suspend_all_baojia_by_account($daili_account);
        }
        else{
            //condition 2
            $down_to_zero_timestamp   =  strtotime($daili_account->down_to_zero_time);
            $now_timestamp            =  time();
            if( $now_timestamp-$down_to_zero_timestamp >= 60*60**72)
            {   //下架所有报价
                $ret = HcDailiAccount::suspend_all_baojia_by_account($daili_account);
            }
        }
        if($ret['code']=200){
            return  [
                "status"  => true,
                'code'    => 200,
                'msg'     => '下架member_id='.$daili_account->d_id.'的代理商的所有报价',
            ];
        }else{
            return  [
                "status"  => false,
                'code'    => 500,
                'msg'     => $ret['msg'],
            ];
        }
    }
}
