<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Requests\DealerPricesRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Delivery;
use App\Models\SettlementFilecount;
use App\Models\HcSettlement;
use App\Models\HcSettlementDetail;

class SettlementController extends Controller
{
    const ORIGIN = 10;
    const CONFIRM= 20;
    const CANCEL =30;

    public function __construct()
    {
        $this->middleware('auth.apiSeller');
    }

    public function index(DealerPricesRequest $request,$type= '')
    {
        $_prefix    = 'api_';
        $fillable   = [
            'get_file_count',
            'get_settlement',
            'get_settlement_detail',
            'mail_file',
            'mail_history',
            'cancel_mail'
        ];
        $uri = in_array($type,$fillable) ? $type : 'all' ;
        $method     =  $_prefix.$uri;
        $result     =  $this->$method($request);

       return $result;
    }

    //获取结算文件数
    public function api_get_file_count($request)
    {
        $member_id  = session('user.member_id');
        $filecount = SettlementFilecount::where(['member_id'=>$member_id])->first();
        if($filecount){
            return response()
                ->json($filecount);
        }else{
            //如果没有返回0
            return response()
                ->json([
                    'file_number' => 0
                ]);
        }
    }

    //查询最近一年的结算记录
    public function api_get_settlement($request)
    {
        $member_id  = session('user.member_id');

        //一年前的月份
        $from = Carbon::now()->subYear()->addMonth()->startOfMonth()->toDateTimeString();
        $list       = HcSettlement::where('member_id',$member_id)
                                ->where('created_at','>',$from)
                                 ->orderBy('created_at','desc')
                                 ->paginate(11);
        //加上手机号码，用来发短信
        $phone = session('user.seller_mobile');

        //计算本月的大概结算数据
        //TODO
//        $month = Carbon::now()->month;
//        if($month<10) $month ='0'.$month;
//        $now_year_month  = Carbon::now()->year.'-'.$month;
//        $predict_money   = 0;

        return response()
            ->json(['list'=>$list,'user_phone'=>$phone]);

    }

    public function api_get_settlement_detail($request)
    {
        $member_id  = session('user.member_id');
        $settlement_id = $request->input('settlement_id');
        if($request->input('order_id')){
            $list       = HcSettlementDetail::where('member_id',$member_id)
                ->where('order_id','like','%'.$request->input('order_id').'%')
                ->where('settlement_id',$settlement_id)
                ->orderBy('created_at','desc')
                ->paginate(10);
        }else{
            $list       = HcSettlementDetail::where('member_id',$member_id)
                ->where('settlement_id',$settlement_id)
                ->orderBy('created_at','desc')
                ->paginate(10);
        }

        //计算本月的大概结算数据
        //TODO

        return response()
            ->json(['list'=>$list]);

    }

    /**
     * 提交寄件信息
     * @author wangjiang
     */
    public function api_mail_file(DealerPricesRequest $request)
    {
        $member_id  = session('user.member_id');
        $this->validate($request, [
            'file_number'           => 'required|integer',
            'delivery_company_name' => 'required|max:200',
            'delivery_number'       => 'required|max:200'
        ]);

        $delivery  =  new Delivery($request->all());
        $delivery->member_id = $member_id;
        $id        = $delivery->save();

        return  response()
            ->json([
               'succcess' => $id,
            ]);
    }

    /**
     * 提交历史信息
     * @author wangjiang
     */
    public function api_mail_history(DealerPricesRequest $request)
    {
        $member_id  = session('user.member_id');

        $list       = Delivery::where(['member_id'=>$member_id])
                            ->orderBy('created_at','desc')
                            ->paginate(10);
        foreach ($list->items() as $key => $item) {
            if($item->status == self::ORIGIN){ //未确认
                $list->items()[$key]->status_name = '待确认';
                $list->items()[$key]->confirm_file_number = '';
                $list->items()[$key]->confirm_at = '';
            }elseif ($item['status'] == self::CONFIRM) {//已收到
                $list->items()[$key]->status_name ='已收到';
            }elseif ($item['status'] == self::CANCEL) {//已撤销
                $list->items()[$key]->status_name = '已撤销';
            }
        }

        return  response()
            ->json([
                'list' => $list,
            ]);
    }

    /**
     * 撤销寄件
     */
    public function api_cancel_mail(DealerPricesRequest $request)
    {
            $id = $request->input('id');
            $member_id  = session('user.member_id');

            $delivery = Delivery::where(['id'=>$id,'member_id'=>$member_id])->first();

            if($delivery){
                $delivery->status  = self::CANCEL;
                $delivery->confirm_at =  Carbon::now()->toDateTimeString();
                $ret = $delivery->save();
                return response()->json(['success' => $ret ]);
            }else{
                return response()->json(['数据不存在！']);
            }
    }
}
