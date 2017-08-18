<?php
/**
 * 交易日志模型管理
 */
defined('InHG') or exit('Access Invalid!');
class hc_account_logModel extends Model {
    public function __construct(){
        parent::__construct('hc_account_log');
    }

    public function getList($where =[1=>1])
    {
        $query_type  = 1;  //默认单表查询
        if($where['users.phone'] && !$where['member.member_name'] )
        {
            $query_type  = 2;  //联合user查询
        }elseif (!$where['users.phone'] && $where['member.member_name']){
            $query_type  = 3;  //联合member查询
        }elseif($where['users.phone'] && $where['member.member_name']){
            $query_type  = 4;  // //联合user,member查询
        }else{

        }
        $model = $this;
        switch ($query_type){
            case 1: //单表查询，去掉表名
                $new_where=[];
               array_walk($where,function($v, $k) use (&$new_where){
                    $k = explode('.',$k)[1];
                    $new_where[$k] = $v;
                });
                $where = $new_where;
                $query  = $model ;
                break;
            case 2:
                if($where['from_name'])
                {
                    $query  = $model->table('hc_account_log,users')
                        ->join('left')
                        ->on('hc_account_log.from_user_id=users.id');
                    unset($where['from_name']);
                }
                if($where['to_name'])
                {
                    $query  = $model->table('hc_account_log,users')
                        ->join('left')
                        ->on('hc_account_log.to_user_id=users.id');
                    unset($where['to_name']);
                }
                break;
            case 3:
                if($where['from_name'] )
                {
                    $query  = $model->table('hc_account_log,member')
                        ->join('left')
                        ->on('hc_account_log.from_user_id=member.member_id');
                    unset($where['from_name']);
                }
                if($where['to_name'])
                {
                    $query  = $model->table('hc_account_log,member')
                        ->join('left')
                        ->on('hc_account_log.to_user_id=member.member_id');
                    unset($where['to_name']);
                }
                break;
            case 4:
                $query  = $model->table('hc_account_log,users,member')
                    ->join('left,left');
                if($where['hc_account_log.from_where'] == 1 )
                {
                        $on = 'hc_account_log.from_user_id=users.id,';
                }else{
                        $on = 'hc_account_log.from_user_id=member.member_id';
                }
                if($where['hc_account_log.to_where'] == 1 )
                {
                    $on  = 'hc_account_log.to_user_id=users.id,'.$on;
                }else{
                    $on  = $on.'hc_account_log.to_user_id=member.member_id';
                }
                $query = $query->on($on);
                unset($where['from_name']);
                unset($where['to_name']);
                break;
        }

        $list = $query->where($where)
                        ->page(10)->order('created_at desc')->select();
        return $list;
    }

    function getLogListByWhere($where,$page=10)
    {
        $list  = $this->where($where)->page($page)->order('created_at desc')->select();

        if($list)
        {
            foreach ($list as $k => $item)
            {
                $user_model  = Model('user');
                $member_model = Model('member');
                if($item['from_where'] == 1){
                    $item['from'] = $user_model->getUserById($item['from_user_id']);
                }

                if($item['from_where'] == 2){
                    $item['from'] = $member_model->getMemberInfo(['member_id'=>$item['from_user_id']]);
                }

                if($item['to_where'] == 1){
                    $item['to'] = $user_model->getUserById($item['to_user_id']);
                }

                if($item['to_where'] == 2){
                    $item['to'] = $member_model->getMemberInfo(['member_id'=>$item['to_user_id']]);
                }
                $list[$k] = $item;

            }
        }
        return $list;
    }


    /**
     * 计算count,sum
     * @param array $where
     * @return mixed
     */
    public function calc($where =[1=>1])
    {
        $query_type  = 1;  //默认单表查询
        if($where['users.phone'] && !$where['member.member_name'] )
        {
            $query_type  = 2;  //联合user查询
        }elseif (!$where['users.phone'] && $where['member.member_name']){
            $query_type  = 3;  //联合member查询
        }elseif($where['users.phone'] && $where['member.member_name']){
            $query_type  = 4;  //联合user,member查询
        }else{

        }
        $model = $this;
        switch ($query_type){
            case 1:
                $new_where=[];
                array_walk($where,function($v, $k) use (&$new_where){
                    $k = explode('.',$k)[1];
                    $new_where[$k] = $v;
                });
                $where = $new_where;
                $query  = $model ;break;
            case 2:
                if($where['from_name'])
                {
                    $query  = $model->table('hc_account_log,users')
                        ->join('left')
                        ->on('hc_account_log.from_user_id=users.id');
                    unset($where['from_name']);
                }
                if($where['to_name'])
                {
                    $query  = $model->table('hc_account_log,users')
                        ->join('left')
                        ->on('hc_account_log.to_user_id=users.id');
                    unset($where['to_name']);
                }
                break;
            case 3:
                if($where['from_name'] )
                {
                    $query  = $model->table('hc_account_log,member')
                        ->join('left')
                        ->on('hc_account_log.from_user_id=member.member_id');
                    unset($where['from_name']);
                }
                if($where['to_name'])
                {
                    $query  = $model->table('hc_account_log,member')
                        ->join('left')
                        ->on('hc_account_log.to_user_id=member.member_id');
                    unset($where['to_name']);
                }
                break;
            case 4:
                $query  = $model->table('hc_account_log,users,member')
                    ->join('left,left');
                if($where['hc_account_log.from_where'] == 1 )
                {
                    $on = 'hc_account_log.from_user_id=users.id,';
                }else{
                    $on = 'hc_account_log.from_user_id=member.member_id';
                }
                if($where['hc_account_log.to_where'] == 1 )
                {
                    $on  = 'hc_account_log.to_user_id=users.id,'.$on;
                }else{
                    $on  = $on.'hc_account_log.to_user_id=member.member_id';
                }
                $query = $query->on($on);
                unset($where['from_name']);
                unset($where['to_name']);
                break;
        }

        $ret = $query->field('count(*) as count, SUM(money) as total')->where($where)->find();
        return $ret;
    }


    /**
     * 获取某条订单的资金平台 收入支出记录
     * flow_type  1,收入，2 成本
     */
    public function getLogsByOrderid($order_id,$flow_type=1){
            $where = [
                'order_id'  => $order_id,
                'to_where|from_where'  => 3,
                'flow_type'     =>  $flow_type
            ];
            $list  = $this->where($where) ->order('created_at desc')->select();
            foreach ($list as $k => $log){
                if($log['to_where'] ==3){
                    $log['sign']   = "+";
                    $log['remark'] = $log['to_remark'];
                }
                if($log['from_where'] ==3){
                    $log['sign']   = "-";
                    $log['remark'] = $log['from_remark'];
                }
                $list[$k] = $log;
            }
            return $list;
    }

}
