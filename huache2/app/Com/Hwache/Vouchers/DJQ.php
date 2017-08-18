<?php

namespace App\Com\Hwache\Vouchers;

use App\Models\HcVoucher;
use App\Models\HcVoucherGroup;
use App\Models\HcVouchersRelease;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\HcVoucherLog;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;

class DJQ
{
    protected $group,$voucher,$release;
    public function __construct(HcVoucherGroup $group,HcVouchersRelease $release,HcVoucher $voucher)
    {
        $this->group   = $group;
        $this->voucher = $voucher;
        $this->release = $release;
    }

    /**
     * 查询需要定时投放，且没投放的投放申请
     * @return mixed
     */
    public function getReleaseTfList()
    {
        return $this->release
            ->select('hc_vouchers_release.id','hc_vouchers_release.fixed_start_time','hc_vouchers_release.fixe_hour_time')
            ->whereRaw("car_hc_vouchers_release.activated_type=0 and car_hc_vouchers_release.ignore_time_type=1")
            ->whereRaw("car_hc_vouchers_release.fixed_start_time >= CURDATE() and car_hc_vouchers_release.status=2")
            ->leftJoin('hc_vouchers','hc_vouchers.release_id','=','hc_vouchers_release.id')
            ->groupBy("hc_vouchers.release_id")
            ->having(DB::raw("count(car_hc_vouchers.release_id)"),'=','0')
            ->get();
    }

    /**
     * 查看投放详情
     * @param $id
     * @param null $col
     * @return mixed
     */
    public function getReleaseFind($id,$col=null)
    {
        $_col = "vr.id as release_id,vr.activated_type,vr.ignore_object,vr.release_total_num,vr.group_id,vr.ignore_users,
        vg.use_collateral,vg.use_sincerity,vg.sincerity_money,vg.collateral_money,vg.life_start_time,vg.life_start_hour,
        vg.can_release_num,vg.release_total,vg.activated_rule,'' as parent_sn,1 as status";
        if(is_null($col)) {
            $field = str_replace(['vr','vg'],['car_hc_vouchers_release','car_hc_vouchers_group'],$_col);
        }else{
            $field = $col;
        }
        $result = $this->release->select(DB::raw($field))
            ->where(["hc_vouchers_release.id"=>$id])
            ->leftJoin('hc_vouchers_group','hc_vouchers_group.id','=','hc_vouchers_release.group_id')
            ->first();
        return $result;
    }

    /**
     * 投放代金券
     */
    public function startRelease($param)
    {
        //计算投放数量
        if($param->activated_type ==0){//免激活
            $useNum = $this->geUseNum($param->group_id);
            $releaseTotal  = $this->getReleaseTotal($param->ignore_object,$param->ignore_users);
            $release_total = $releaseTotal * $useNum;
            $isAdd = $this->addReleaseNotActivated($param);
        }
        $group = $this->group ->findOrFail($param->group_id);
        $group->status =1;
        $group->release_total = $param->release_total + $release_total;
        $group->updated_at    = Carbon::now()->toDateTimeString();
        //更新组投放状态
        return $group->save();
    }

    /** 生成单个代金券
     * @param $group_id
     * @param $money
     * @param $life_start_time
     * @param $life_end_time
     * @param int $activated_type
     * @param string $activated_code
     * @return mixed
     */
    public function saveVoucher(array $values){
        //return $this->voucher->firstOrCreate($values);
        return DB::table('hc_vouchers')->insert($values);
        //return DB::insert($sql);
    }
    /** 查看代金组的使用数
     * @param $group_id
     * @return mixed
     */
    private function geUseNum($group_id)
    {
        return $this->group ->where(['id'=>$group_id])->value(DB::raw("use_collateral+use_sincerity"));
    }

    /**
     * 根据条件返回投放用户及总数
     * @param $ignore_object
     * @param null $users
     * @return int|mixed
     */
    private function getReleaseTotal($ignore_object,$users=null){
        switch($ignore_object){
            case 0://所有客户
                $release_total = DB::table('users')->count();
                break;
            case 1://指定客户
                $release_total = count(explode('、',$users));
                break;
            case 2://一年内未买车的客户
                $release_total = $this->getYearNotUserOrder('year');
                break;
            case 3://三个月内新注册并且未买车的用户
                $release_total = $this->getYearNotUserOrder('month');
                break;
            default:
                $release_total = 0;
        }
        return $release_total;
    }

    /** 获取投放总数
     * @param string $type
     * @return mixed
     */
    public function getYearNotUserOrder($type='year',$count=true){
        //一年内未买车的客户 year(日期字段)=year(curdate())
        if($type == 'year'){
            $where = "year(hc_order.updated_at) = year(curdate()) and hc_order.order_status = 99";
        }else{//三个月内新注册并且未买车的用户
            $where = "month(users.created_at)+3=month(curdate()) and hc_order.order_status <> 99";
        }
        if($count){
            return DB::table('users')->whereRaw($where)->leftJoin('hc_order','users.id','=','hc_order.user_id') ->count(DB::raw('DISTINCT users.id'));
        }else{
            return DB::table('users')->select(DB::raw("DISTINCT users.id as id"))->whereRaw($where)->leftJoin('hc_order','users.id','=','hc_order.user_id')->get();
        }
    }

    /** 投放免激活
     * @param $group_id
     * @param $ignore_object
     * @param $users
     * @return string
     */
    private function addReleaseNotActivated($data)
    {
        $ignore_object = intval($data->ignore_object);
        $insertAll = [];
        switch($ignore_object){
            case 0://所有客户
                $userList = DB::table('users')->select('id')->get();
                foreach($userList as $value){
                    $data->user_id = $value->id;
                    $insertAll[] = $this->setValues($data);
                }
                break;
            case 1://指定客户
                $users = explode('、',$data->ignore_users);
                $userList = DB::table('users')->select('id')->whereIn('id', $users)->get();
                foreach($userList as $value){
                    $data->user_id = $value->id;
                    $insertAll[] = $this->setValues($data);
                }
                break;
            case 2://一年内未买车的客户
                $userList = $this->getYearNotUserOrder('year',false);
                foreach($userList as $value){
                    $data->user_id = $value->id;
                    $insertAll[] = $this->setValues($data);
                }
                break;
            case 3://三个月内新注册并且未买车的用户
                $userList = $this->getYearNotUserOrder('month',false);
                foreach($userList as $value){
                    $data->user_id = $value->id;
                    $insertAll[] = $this->setValues($data);
                }
                break;
            default:
                $userList = false;
        }
        if($userList){
            return $this->saveVoucher($insertAll);exit;
        }
        return false;
    }

    /** 设置批量插入的数据
     * @param array $v
     * @return string
     */
    private function setValues($v)
    {
        $life_start_time = $v->life_start_time ? $v->life_start_time.' '.$v->life_start_hour : '';
        $life_end_time   = $v->life_end_time ? $v->life_end_time.' '.$v->life_end_hour : '';
        $user_id = (int) $v->user_id;
        //$activated_code = ($v->activated_type) ? getActivatedCode($v->activated_rule,$v->activated_num) : '';
        if($v->use_collateral && $v->collateral_money>0){
            $money = intval($v->collateral_money);
            $_value = [
                'group_id'        => $v->group_id,
                'release_id'      => $v->release_id,
                'voucher_sn'      => DB::raw("uuid()"),
                'money'           => $money,
                'life_start_time' => $life_start_time,
                'life_end_time'   => $life_end_time,
                'activated_type'  => $v->activated_type,
                'user_id'         => $user_id,
                'created_at'      => Carbon::now()->toDateTimeString(),
                'updated_at'      => Carbon::now()->toDateTimeString(),
                'use'             => 1
            ];
        }
        if($v->use_sincerity && $v->sincerity_money>0){
            $money = intval($v->sincerity_money);
            $_value = [
                'group_id'        => $v->group_id,
                'release_id'      => $v->release_id,
                'voucher_sn'      => DB::raw("uuid()"),
                'money'           => $money,
                'life_start_time' => $life_start_time,
                'life_end_time'   => $life_end_time,
                'activated_type'  => $v->activated_type,
                'user_id'         => $user_id,
                'created_at'      => Carbon::now()->toDateTimeString(),
                'updated_at'      => Carbon::now()->toDateTimeString(),
                'use'             => 0
            ];

        }
        return $_value;
    }

    /**
     * 生成子代金券
     * @param $code   原代金券编号
     * @param $money  消费金额
     * @return mixed
     */
    public function AddChildVoucher($code,$money)
    {
        $info = $this->voucher ->select('hc_vouchers.*','group.activated_rule','group.activated_num')
            ->leftJoin('hc_vouchers_group as group','group.id','=','hc_vouchers.group_id')
            ->where('hc_vouchers.voucher_sn',$code) ->first();
        if(is_null($info)){
            throw new \Exception('代金券不存在！！');
            return false;
        }

        $activated_code = ($info->activated_type) ? getActivatedCode($info->activated_rule,$info->activated_num) : '';
        $data = [
            'group_id'        => $info->group_id,
            'release_id'      => $info->release_id,
            'voucher_sn'      => DB::raw("uuid()"),
            'parent_sn'       => $code,
            'money'           => $info->money - $money,
            'life_start_time' => $info->life_start_time,
            'life_end_time'   => $info->life_end_time,
            'activated'       => $info->activated,
            'activated_type'  => $info->activated_type,
            'activated_code'  => $activated_code,
            'user_id'         => $info->user_id,
            'created_at'      => Carbon::now()->toDateTimeString(),
            'updated_at'      => Carbon::now()->toDateTimeString(),
            'use'             => $info->use
        ];
        return $this->voucher->firstOrCreate($data);
    }

    /**
     * 使用代金券
     * @param $voucher_id  代金券编号
     * @param $money       使用金额
     */
    public function useVouchers($voucher_id,$order_id,$money)
    {
        $voucherInfo = $this->voucher ->where(['id'=>$voucher_id])->first();
        $order = \App\Models\HgOrder::findOrFail($order_id)->first();
        $userCount = DB::table('hc_user_account')->where('user_id', $order->user_id)->lockForUpdate()->first();
        DB::beginTransaction();
        try {
            if($voucherInfo->money > $money){//生成子代金券
                //todo 改成db写法
                $this->AddChildVoucher($voucherInfo->voucher_sn, $money);
            }

            //代金券消费日志
            DB::table('hc_voucher_log')->insertGetId([
                'vid'          => $voucher_id,
                'order_id'     => $order_id,
                'consume_money'=> $money,
                'balance'      => $voucherInfo->money - $money,
                'created_at'   => Carbon::now()->toDateTimeString()
            ]);

            //更新代金券状态
            DB::table('hc_vouchers')->where(['id'=>$voucher_id])->update(['status' => 3]);

            //用户加信宝日志数据
            $item = HcItem::where(['code'=>'KHJXB-0101'])->first();
            DB::table('hc_user_account_log')->insert(
                [
                    'order_id' => $order_id,
                    'item' => $item->id,
                    'item_id' => $item->name,
                    'type' => 10,
                    'user_id' => $order->user_id,
                    'role' => 1,
                    'money' => $money,
                    'freeze_avaiable' => $userCount->freeze_deposit+$money,
                    'description' =>$item->name,
                ]
            );

            //更新用户账户中加信宝数据和可用余额
            DB::table('hc_user_account')
                ->where('user_id', $order->user_id)
                ->update(['freeze_deposit'=> DB::raw('freeze_deposit+'.$money)]);

            //更新订单信宝数据
            DB::table('hc_order')
                ->where('id', $order_id)
                ->update(['user_freeze_jxb'=>DB::raw('user_freeze_jxb+'.$money)]);

            DB::commit();
            return true;
        }catch (\Exception $e){
            DB::rollBack();
            return false;
        }
    }
}
