<?php

namespace App\Models;

use App\Events\HcDailiAccountSavedEvent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class HcDailiAccount extends Model
{
    protected $table = 'hc_daili_account';

    protected $primaryKey = 'd_id';

    protected $fillable = ['d_id'];

    protected $events =
        [
//            'saved' => HcDailiAccountSavedEvent::class,
            'updated' => HcDailiAccountSavedEvent::class,
        ];

    //资金池可提现余额不足专用方法
    //所有上架产品全部都暂时下架
    public static function suspend_all_baojia_by_account(HcDailiAccount $account)
    {
        $member_id =  $account->d_id;
        $count = HgBaojia::where('m_id',$member_id)->where('bj_status',1)->count();
        if($count){
            //下架该账户的所有报价
            $update = [
                'bj_reason'  =>  '资金池可提现余额不足',
                'bj_status'  =>   2, //暂时下架
                'bj_status_change_code' => 'hwache_baojia_money_end',
                'bj_status_change_time' => Carbon::now()->toDateTimeString()
            ];
            $ret = HgBaojia::where('m_id',$member_id)->where(function($query){
                                                                $query->where('bj_status',1)
                                                                    ->orWhere(function($query){
                                                                        $query->where('bj_status', 2)->where('bj_reason', '非销售时间');
                                                                    });
                                                            })->update($update);
            return  ['code'=>'200','data'=>$ret];
        }else{
            return ['code'=>'400','msg'=>"没有需要修改的报价"];
        }


    }

    //资金池可提现余额不足专用方法
    //所有上架产品全部都暂时下架
    //@params  $id_array hcDailiAccount d_id字段数组
    public static function suspend_all_baojia_by_account_id_array(array $id_array)
    {
        if(count($id_array) <= 0) return false;
        $count = HgBaojia::whereIn('m_id',$id_array)->where('bj_status',1)->count();
        if($count){
            //下架该账户的所有报价
            $update = [
                'bj_reason'  =>  '资金池可提现余额不足',
                'bj_status'  =>   2, //暂时下架
                'bj_status_change_code' => 'hwache_baojia_money_end',
                'bj_status_change_time' => Carbon::now()->toDateTimeString()
            ];
            $ret = HgBaojia::whereIn('m_id',$id_array)->where(function($query){
                $query->where('bj_status',1)
                    ->orWhere(function($query){
                        $query->where('bj_status', 2)->where('bj_reason', '非销售时间');
                    });
            })->update($update);
            return  ['code'=>'200','data'=>$ret];
        }else{
            return ['code'=>'400','msg'=>"没有需要修改的报价"];
        }
    }

    public static function getAccountInfo($where)
    {
        return self::where($where)->firstOrFail();

    }

    //查询所有余额不足的售方账户
    public static  function get_no_money_daili_account()
    {
        $result  =  self::where(function($query){
                            $query->whereRaw(' avaliable_deposit + credit_line  < 0');
                        })
                        ->orWhere(function($query){
                            $query->where('avaliable_deposit','<',0)->whereRaw('UNIX_TIMESTAMP(down_to_zero_time) > '.(time()-60*60*72));
                        })->get();
        return $result;
    }
    
    //查询有足够余额，但是仍有因余额不足挂起的报价
    public static function get_enongh_money_but_suspend_baojia_account()
    {
        $result = self::leftJoin('hg_baojia', 'hc_daili_account.d_id','=', 'hg_baojia.m_id')
                        ->whereRaw('car_hg_baojia.bj_status=2 and car_hg_baojia.bj_step=99 and car_hg_baojia.bj_status_change_code = \'hwache_baojia_money_end\'')
                        ->whereRaw('( car_hc_daili_account.avaliable_deposit + car_hc_daili_account.credit_line  > 0) ')
                        ->groupby('d_id')->distinct()->select('d_id')->get();
        $ids = [];
        if($result->count()>0)
        {
            foreach ($result as $account)
            {
                $ids[] = $account->d_id;
            }
        }
        return $ids;
    }
}
