<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20
 * Time: 14:08
 */

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\HcUserConsumeRepositoryInterface;
use App\Models\HcUserConsume;

class HcUserConsumeRepository extends Repository implements HcUserConsumeRepositoryInterface
{
    public function __construct(HcUserConsume $model)
    {
        $this->model = $model;
    }

    /** 添加消费记录
     * @param $user_id    用户id
     * @param $money      金额
     * @param int $ur_id  充值id
     * @param int $uw_id  提现id
     * @param int $uwl_id  提现线路id
     * @param int $avaliable_money    可用金额
     * @param string $remark          备注/说明
     * @param int $status             消费状态
     * @param string $wichdraw_end_at 提现截止日期
     * @return mixed
     */
    public function addConsume($user_id,$money,$ur_id=0,$uw_id=0,$uwl_id,$avaliable_money=0,$remark='',$status=0,$wichdraw_end_at='')
    {
        //消费记录
        $xfData=[
            'user_id'         => $user_id,
            'ur_id'           => $ur_id,
            'uw_id'           => $uw_id,
            'uwl_id'          => $uwl_id,
            'consume_money'   => $money,
            'avaliable_money' => $avaliable_money,
            'remark'          => $remark,
            'is_new'          => 1,
            'status'          => $status,
            'wichdraw_end_at' => $wichdraw_end_at
        ];
        return $this->model ->create($xfData);
    }
    /** 更新消费记录
     * @param int $user_id  购买，提现，退款
     * @param $money
     */
    public function updateConsume($user_id,$price){
        static $money;
        $money = $price;
        //查询消费记录正序排列
        $consumeList = \App\User::find($user_id)
            ->UserConsume()
            ->leftJoin('hc_user_recharge as ur',function($join){
                $join->on('ur.ur_id','=','ur_id')->where('ur.status',1);
            })
            ->where('avaliable_money','>',0)
            ->where('status',1)
            ->orderBy('cid','asc')
            ->get();

        $keyName = $this->model->getKeyName();
        foreach($consumeList as $key => $itme){
            if($money > 0){
                if($money == $itme->avaliable_money){
                    $this->setFindConsume($itme->$keyName,0);
                    $money = 0;
                }else{
                    if($money > $itme->avaliable_money){
                        $this->setFindConsume($itme->$keyName,0);
                        $money -= $itme->avaliable_money;
                    }else{
                        $this->setFindConsume($itme->$keyName,($itme->avaliable_money - $money));
                        $money = 0;
                    }
                }
            }
        }
    }
    /**更新单条记录
     * @param $id
     * @param $price
     */
    public function setFindConsume($id,$price){
        $data['avaliable_money'] = $price;
        $data['is_new'] = 1;
        $keyName = $this->model->getKeyName();
        $this->model->where($keyName,$id)->update($data);
    }
}