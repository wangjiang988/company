<?php
/**
 * 操作记录表Model
 * @author wangjiang
 */
defined('InHG') or exit('Access Invalid!');
class hc_admin_operationModel extends Model
{
    public function __construct()
    {
        parent::__construct('hc_admin_operation');
    }

    /**
     * 添加操作记录
     * @author wangjiang
     */
    public function add_operation($operation)
    {
        $operation['updated_at'] = $operation['created_at'] = get_now2();
        return $this->table('hc_admin_operation')->insert($operation);
    }

    /**
     * 添加操作记录明细表
     * @author wangjiang
     */
    public function add_details($details)
    {
        if (!$details) return false;
        $ret = $this->table('hc_admin_operation_detail')->insertAll($details);
        return $ret;
    }

    /**
     * 添加到操作附件表
     * @author wangjiang
     */
    public function add_comments($comments)
    {
        if (!$comments) return false;
        $ret = $this->table('hc_admin_operation_comment')->insertAll($comments);
        return $ret;
    }

    /**
     * 获取某个表的某条记录某个字段的最新一条操作记录
     * @param  $where
     */
    public function findLastLogDetail($where)
    {
        $ret = $this->table('hc_admin_operation_detail')
            ->where($where)
            ->order('created_at desc')
            ->limit(1)
            ->select();

        if ($ret) {
            $ret = $ret[0];
            $ret['operation'] = $this->find($ret['op_id']);
        }
        return $ret;
    }

    /**
     * 获取操作日志list
     */
    public function getList($where=[], $fields ="*", $page= 15){
        $ret  = $this->where($where)
                    ->field($fields)
                    ->page($page)
                    ->order('created_at desc')
                    ->select();

        if($ret)
        {
            foreach ($ret as $kk => $log)
            {
                $user  = $this->table('admin')->field('admin_id,admin_name,admin_truename')
                    ->where(['admin_id'=>$log['user_id']])->find();
                $ret[$kk]['user']       = $user;
                $ret[$kk]['uwl']        = $this->table('hc_user_withdraw_line')
                    ->where(['uwl_id'=>$log['uwl_id']])->find();
                $ret[$kk]['details']        = $this->table('hc_admin_operation_detail')
                    ->where(['op_id'=>$log['id']])->select();
            }
        }



        return $ret;
    }


    /**
     * 获取充值记录审核的附件信息
     */
    public function getCommentsByRecharge($recharge)
    {
        $where= [
            'related'        =>    'hc_user_recharge|'.    $recharge['ur_id'],
//            'step'         =>    $recharge['status'],
            'is_del'         =>     0

        ];
        $ret = $this->table('hc_admin_operation_comment')->where($where)->order('created_at asc')->select();
        if($ret){
            foreach($ret as $k => $comment)
            {
                if($comment['file_name']){
                    $ret[$k]['file_list'][]  = [
                        'file_name' =>$comment['file_name'],
                        'file_path' =>$comment['file_path'],
                    ];
                    continue;
                }
                $file_arr = [];
                $files = explode('|',$comment['file_path']);
                foreach ($files as $file){
                    $tmp = explode(',',$file);
                    $file_arr[]  =[
                        'file_name'  => $tmp[0],
                        'file_path'  => $tmp[1]
                    ];
                }
                $ret[$k]['file_list']  = $file_arr;
            }
        }
        return $ret;
    }


    /**
     * 获取提现记录审核的附件信息
     */
    public function getCommentsByWithdraw($withdraw)
    {
        $where= [
            'related'        =>    'hc_user_withdraw|'.    $withdraw['uw_id'],
//            'step'           =>    $withdraw['status'],
            'is_del'         =>     0

        ];
        $ret = $this->table('hc_admin_operation_comment')->where($where)->order('created_at asc')->select();
        if($ret){
            foreach($ret as $k => $comment)
            {
                if($comment['file_name']){
                    $ret[$k]['file_list'][]  = [
                        'file_name' =>$comment['file_name'],
                        'file_path' =>$comment['file_path'],
                    ];
                    continue;
                }
                $file_arr = [];
                $files = explode('|',$comment['file_path']);
                foreach ($files as $file){
                    $tmp = explode(',',$file);
                    $file_arr[]  =[
                        'file_name'  => $tmp[0],
                        'file_path'  => $tmp[1]
                    ];
                }
                $ret[$k]['file_list']  = $file_arr;
            }
        }
        return $ret;
    }

    /**
     * 根据comment_id查找hc_admin_operation_comment记录
     */
    public function getCommentByCommentId($comment_id)
    {
        $where= [
            'id'             =>    $comment_id,
            'is_del'         =>     0

        ];
        $ret = $this->table('hc_admin_operation_comment')->where($where)->find();
        return $ret;
    }

    /**
     * 根据comment 删除comment
     */
    public function delCommentByComment($comment)
    {
        $update= [
            'is_del'         =>     1

        ];
        $ret = $this->table('hc_admin_operation_comment')->where(['id'=>$comment['id']])->update($update);
        return $ret;
    }

}