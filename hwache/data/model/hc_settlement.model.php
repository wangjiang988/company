<?php
/**
 * 结算模型管理
 */
defined('InHG') or exit('Access Invalid!');
class hc_settlementModel extends Model {
    public function __construct(){
        parent::__construct('hc_settlement');
    }

    public function getList($where =[1=>1])
    {
        $list = $this->table('hc_settlement,member,hc_settlement_filecount,seller')
                        ->field('hc_settlement.*,
                                member.member_name,
                                member.member_truename,
                                seller.seller_card_num,
                                hc_settlement_filecount.file_number
                                ')
                        ->join("left,left,left")
                        ->on('hc_settlement.member_id=member.member_id,member.member_id=hc_settlement_filecount.member_id,member.member_id=seller.member_id')
                        ->where($where)
                        ->page(10)->select();
        return $list;
    }


    /**
     * 计算count,sum
     * @param array $where
     * @return mixed
     */
    public function calc($where =[1=>1])
    {
        $ret = $this->table('hc_settlement,member,hc_settlement_filecount,seller')
            ->field('SUM(hc_settlement.money) as sum_money, SUM(hc_settlement.confirm_money) as total
                                ')
            ->join("left,left,left")
            ->on('hc_settlement.member_id=member.member_id,member.member_id=hc_settlement_filecount.member_id,member.member_id=seller.member_id')
            ->where($where)->find();
//        $ret = $this->field('SUM(money) as sum_money, SUM(confirm_money) as total')->where($where)->find();
        return $ret;
    }

    /**
     * 获取数据中的所有年月
     */

    public function getAllYears($where =[])
    {
        $list  =  $this->field('DISTINCT(year)')->where($where)->select();
        return $list;
    }

    /**
     * 得到某个订单的明细
     */
    public function getInfoById($id)
    {
        $where =[
            'hc_settlement.id' => $id,
        ];
        $info  = $this->table('hc_settlement,member,seller')
            ->field('hc_settlement.*,
                    member.member_name,
                    member.member_truename,
                    seller.seller_card_num
                    ')
            ->join("left,left")
            ->on('hc_settlement.member_id=member.member_id,member.member_id=seller.member_id')
            ->where($where)
            ->find();
        return $info;
    }
}
