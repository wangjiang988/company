<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/16
 * Time: 14:17
 */
defined('InHG') or exit('Access Invalid!');
class hc_daili_accountModel extends Model{
    CONST ACCOUNT_TABLE           = 'hc_daili_account';
    CONST ACCOUNT_LOG_TABLE       = 'hc_daili_account_log';
    CONST ACCOUNT_RECHARGE_TABLE  = 'hc_daili_recharge_bank';
    CONST ACCOUNT_WITHDRAW_TABLE  = 'hc_daili_withdraw_bank';
    CONST ACCOUNT_OVERDRAFT_TABLE = 'hc_daili_overdraft_log';
    CONST ADMIN_OPERATION         = 'hc_admin_operation';
    CONST SPECIAL_TABLE           = 'hc_user_special_application';//特别事项
    CONST SYS_ACCOUNT_LOG         = 'hc_account_log';
    CONST SELLER_TABLE            = 'seller';
    CONST MEMBER_TABLE            = 'member';

    CONST ACCOUNT_RECHARGE_TYPE = 22;
    CONST ACCOUNT_WITHDRAW_TYPE = 23;
    protected $tableName = null;

    public function __construct()
    {
        parent::__construct('hc_daili_account');
    }

    public function setTable($table=null)
    {
        $tabeList = implode(',',[self::SELLER_TABLE,self::MEMBER_TABLE,self::ACCOUNT_TABLE,self::ACCOUNT_LOG_TABLE,self::ACCOUNT_OVERDRAFT_TABLE]);
        $this->tableName = is_null($table) ? $tabeList : $table;
    }
    /**
     * 列表翻页
     * @param array $condition
     * @param string $fields
     * @param null $join
     * @param null $on
     * @param null $order
     * @param null $group
     * @param null $having
     * @param int $pageSize
     * @param null $key
     * @return array
     */
    public function getPageList($condition = array(), $fields = '*', $join=null , $on=null , $order = null , $group = null, $having = null , $pageSize = 10,$key = null) {
        if( is_null($this->tableName) ){
            $this->setTable();
        }
        $list = $this->table($this->tableName)->field($fields)->where($condition)
            ->join($join)->on($on)
            ->order($order)
            ->page($pageSize)
            ->group($group)->having($having)
            ->key($key)->select();
        return ['list'=>$list,'page'=>$this->showpage()];
    }

    /**
     * 获取单条记录
     * @param $table
     * @param array $condition
     * @param string $field
     * @param null $join
     * @param null $on
     * @param null $order
     * @param null $group
     * @param null $having
     * @return mixed
     */
    public function getFind($table , $condition =array(), $field='*' , $join=null , $on=null , $order = null , $group = null, $having = null)
    {
        return $this->table($table)->field($field)->where($condition)->join($join)->on($on)
            ->order($order)->group($group)->having($having)->find();
    }
    /**
     * 取得总数量
     * @param unknown $condition
     */
    public function getCount($table,$condition,$join=null,$on=null) {
        return $this->table($table)->where($condition)->join($join)->on($on)->count();
    }

    /**
     * 保存信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function saveData($table,$data,$type='insert',$where=null) {
        if($type=='insert')
            return $this->table($table)->insert($data);
        else
            return $this->table($table)->where($where)->update($data);
    }

    /**
     * @param $member_id
     * @return mixed
     */
    public function getSellerInfo($member_id)
    {
        $table = self::MEMBER_TABLE.','.self::ACCOUNT_TABLE;
        $col = sprintf("%s.member_id,%s.member_name,%s.member_truename,%s.member_mobile,%s.*",
            self::MEMBER_TABLE,self::MEMBER_TABLE,self::MEMBER_TABLE,self::MEMBER_TABLE,self::ACCOUNT_TABLE
            );
        $on = sprintf("%s.member_id = %s.d_id",self::MEMBER_TABLE,self::ACCOUNT_TABLE);
        return $this->getFind($table,[self::MEMBER_TABLE.'.member_id'=>$member_id],$col,['left'],$on);
    }

    /**
     * 交易日志列表
     * @param int $pagsize
     * @return array
     */
    public function getSellerLogList($param){
        $search = $param;
        $this->setTable(self::ACCOUNT_LOG_TABLE);
        $member_id = (int) $param['id'];
        $where['d_id'] = ['eq',$member_id];
        $where['type'] = ['exp',"type=2 or (status=1 and type=1)"];
        $date = intval($param['date']);
        if($date >0){
            $month = date('Y-m-d H:is',strtotime('-1 month'));
            $year  = date('Y-m-d H:is',strtotime('-1 year'));
            switch($date){
                case 1://一个月内
                    $where['updated_at'] = ['exp',sprintf("created_at BETWEEN '%s' AND '%s'",$month,get_now2())];
                    break;
                case 2://一年内
                    $where['updated_at'] = ['exp',sprintf("created_at BETWEEN '%s' AND '%s'",$year,get_now2())];
                    break;
                case 3://大于一年
                    $where['updated_at'] = ['exp',sprintf("created_at < '%s'",$year)];
                    break;
            }
        }
        $whereLike= $this->setLikeWhere([
            'remark' => trim($param['remark']),
            'item'   => trim($param['remark'])
        ]);
        if(count($whereLike) > 0){
            $where = array_merge($where,$whereLike);
        }
        $whereStartEnd = $this->setStartToEnd([
            'created_at' => [trim($param['start_date']),trim($param['end_date'])]
        ]);
        if(count($whereStartEnd) > 0){
            $where = array_merge($where,$whereStartEnd);
        }
        $result = $this->getPageList($where,'*',null,null,'da_log_id desc');
        return ['list'=>$result['list'],'page'=>$result['page'],'search'=>$search];
    }

    /**
     * 充值列表
     * @param $param
     * @return array
     */
    public function getRechargeList($param)
    {
        $whereSearch = $this->setRechargeWhere($param);
        $this->setTable(self::ACCOUNT_RECHARGE_TABLE.','.self::MEMBER_TABLE);
        $join = ['left'];
        $on = sprintf("%s.member_id = %s.d_id",self::MEMBER_TABLE,self::ACCOUNT_RECHARGE_TABLE);
        $order = self::ACCOUNT_RECHARGE_TABLE.".drb_id desc";
        $fields = sprintf("%s.*,%s.member_name,%s.member_truename,%s.member_mobile",self::ACCOUNT_RECHARGE_TABLE,self::MEMBER_TABLE,self::MEMBER_TABLE,self::MEMBER_TABLE);
        $this->setCountCol(sprintf('DISTINCT %s.drb_id',self::ACCOUNT_RECHARGE_TABLE));
        $result = $this->getPageList($whereSearch['where'],$fields,$join,$on,$order);
        return ['list'=>$result['list'],'page'=>$result['page'],'search'=>$whereSearch['search']];
    }

    /**
     * @param $where
     */
    public function getRechargeFind($where,$_col=null){
        $table = self::ACCOUNT_RECHARGE_TABLE.','.self::MEMBER_TABLE;
        $on = sprintf("%s.member_id = %s.d_id",self::MEMBER_TABLE,self::ACCOUNT_RECHARGE_TABLE);
        $fields = sprintf("%s.*,%s.member_name,%s.member_truename,%s.member_mobile",self::ACCOUNT_RECHARGE_TABLE,self::MEMBER_TABLE,self::MEMBER_TABLE,self::MEMBER_TABLE);
        $col = is_null($_col) ? $fields : $_col;
        return $this->getFind($table,$where,$col,['left'],$on);
    }

    public function getWithdrawFind($where,$_col=null)
    {
        $table = self::ACCOUNT_WITHDRAW_TABLE.','.self::MEMBER_TABLE;
        $on = sprintf("%s.member_id = %s.d_id",self::MEMBER_TABLE,self::ACCOUNT_WITHDRAW_TABLE);
        $fields = sprintf("%s.*,%s.member_name,%s.member_truename,%s.member_mobile",self::ACCOUNT_WITHDRAW_TABLE,self::MEMBER_TABLE,self::MEMBER_TABLE,self::MEMBER_TABLE);
        $col = is_null($_col) ? $fields : $_col;
        return $this->getFind($table,$where,$col,['left'],$on);
    }
    /** 充值条件
     * @param $params
     * @return array
     */
    private function setRechargeWhere($params){
        $search = $params;
        $where = $this->setLikeWhere([
            self::ACCOUNT_RECHARGE_TABLE.'.drb_id' => $params['code'],
            self::MEMBER_TABLE.'.member_name'     => $params['seller_name'],
            self::MEMBER_TABLE.'.member_truename' => $params['member_truename'],
            self::MEMBER_TABLE.'.member_mobile'   => $params['seller_phone'],
            self::ACCOUNT_RECHARGE_TABLE.'.bank_voucher_code' => $params['bank_voucher_code'],
            self::ACCOUNT_RECHARGE_TABLE.'.accounting_voucher'=> $params['accounting_voucher'],
        ]);
        if($params['status'] !=''){
            $status = ($params['status'] =='-1') ? 0 : $params['status'];
            $operationWhere = $this->setOperationWhere([self::ACCOUNT_RECHARGE_TABLE.'.kefu_confirm_status'=>['eq',$status]]);
            if(count($operationWhere) > 0){
                $where  = (count($where) >0) ? array_merge($where,$operationWhere) : $operationWhere;
            }
        }
        $startEndWhere = $this->setStartToEnd([
                self::ACCOUNT_RECHARGE_TABLE.'.money' => [$params['start_money'],$params['end_money']],//提交金额
                self::ACCOUNT_RECHARGE_TABLE.'.kefu_confirm_money' => [$params['start_recorded'],$params['end_recorded']],//入账金额
                self::ACCOUNT_RECHARGE_TABLE.'.updated_at'=>[$params['start_order_time'],$params['end_order_time']],//工单时间
                self::ACCOUNT_RECHARGE_TABLE.'.created_at'=>[$params['start_submit_time'],$params['end_submit_time']],//提交时间
                self::ACCOUNT_RECHARGE_TABLE.'.recharge_confirm_at'=>[$params['start_recorded_time'],$params['end_recorded_time']],
            ]
        );
        if(count($startEndWhere) > 0){
            $where  = (count($where) >0) ? array_merge($where,$startEndWhere) : $startEndWhere;
        }
        $search['status'] = empty($params['status']) ? '8' : $params['status'] ;
        return ['where' => $where ,  'search' => $search];
    }

    /** 特别事项查询条件
     * @param $param
     * @return array
     */
    public function setSpecialWhereSearch($param)
    {
        $search = $param;
        $special = 'hc_user_special_application';
        $where = $this->setLikeWhere([
            $special.'.id'         => $param['code'],
            $special.'.apply_admin_name'  => $param['apply_admin_name'],
            $special.'.name'       => $param['item'],
            'member.member_name'   => $param['member_name'],
            'member.member_mobile' => $param['member_mobile'],
            'admin.dept_id'        => $param['dept_id']
        ]);
        $operationWhere = $this->setOperationWhere([$special.'.status'=>['eq',$param['status']]]);
        if(count($operationWhere) > 0){
            $where  = (count($where) >0) ? array_merge($where,$operationWhere) : $operationWhere;
        }
        $startEndWhere = $this->setStartToEnd([
                $special.'.created_at' => [$param['start_order_time'],$param['end_order_time']],//工单时间
                $special.'.updated_at' => [$param['start_update_time'],$param['end_update_time']],
            ]
        );
        if(count($startEndWhere) > 0){
            $where  = (count($where) >0) ? array_merge($where,$startEndWhere) : $startEndWhere;
        }
        $search['status']  = ($param['status'] =='') ? 5 : $param['status'];
        $search['item']    = ($param['item'] =='') ? 8 : $param['item'];
        $search['dept_id'] = ($param['dept_id'] =='') ? 8 : $param['dept_id'];
        return ['where'=>$where,'search'=>$search];
    }
    /**
     * @param $params
     * @return array
     */
    public function setWhere($params){
        $having = null;
        $search = $params;
        $dTable = self::ACCOUNT_TABLE;
        $where = $this->setLikeWhere([
            self::SELLER_TABLE.'.seller_name' => $params['seller_name'],
            self::MEMBER_TABLE.'.member_truename' => $params['member_truename'],
            self::MEMBER_TABLE.'.member_mobile'   => $params['seller_phone'],
            self::ACCOUNT_RECHARGE_TABLE.'.bank_voucher_code' => $params['bank_voucher_code'],
            self::ACCOUNT_RECHARGE_TABLE.'.accounting_voucher'=> $params['accounting_voucher'],
        ]);
        $startEndWhere = $this->setStartToEnd([
            self::ACCOUNT_TABLE.'.basic_deposit' => [$params['start_deposit'],$params['end_deposit']],//固定保证金
            self::ACCOUNT_TABLE.'.credit_line'   => [$params['start_credit'],$params['end_credit']],//授信额度
            self::ACCOUNT_TABLE.'.avaliable_deposit'=>[$params['start_avaliable'],$params['end_avaliable']],//可提现余额
            self::ACCOUNT_TABLE.'.temp_deposit'=>[$params['start_ptdj'],$params['end_ptdj']],//平台冻结
            self::ACCOUNT_TABLE.'.total_deposit'=>[$params['start_total'],$params['end_total']]//账户总金额
        ]);
        if(count($startEndWhere) > 0){
            $where  = (count($where) >0) ? array_merge($where,$startEndWhere) : $startEndWhere;
        }
        #-------------- 加信宝- having sum(jxb_total)>0
        if ($params['start_jiaxb'] !='' && is_numeric($params['start_jiaxb']) && $params['end_jiaxb'] ==''){
            $having = sprintf('sum(%s.money) >=',self::ACCOUNT_LOG_TABLE).$params['start_jiaxb'];
        }
        if ($params['end_jiaxb'] !='' && is_numeric($params['end_jiaxb']) && $params['start_jiaxb'] ==''){
            $having = sprintf('sum(%s.money) <=',self::ACCOUNT_LOG_TABLE).$params['start_jiaxb'];
        }
        if ($params['start_jiaxb'] !='' && is_numeric($params['start_jiaxb']) && $params['end_jiaxb'] !='' && is_numeric($params['end_jiaxb'])){
            $having = sprintf('sum(%s.money) >=%d and sum(%s.money) <=%d',self::ACCOUNT_LOG_TABLE,$params['start_jiaxb'],self::ACCOUNT_LOG_TABLE,$params['end_jiaxb']);
        }
        #--------- 账户状态 -------------
        switch($params['status']){
            case 1://有效
                $whereStr = self::ACCOUNT_TABLE.'.avaliable_deposit >=0 or '.self::ACCOUNT_TABLE.'.status is NULL';
                $where[self::ACCOUNT_TABLE.'.avaliable_deposit'] = ['exp',$whereStr];
                break;
            case 2://失效
                $where[self::ACCOUNT_TABLE.'.status'] = ['eq',2];
                break;
            case -1://透支
                $created = self::ACCOUNT_OVERDRAFT_TABLE.'.created_at';
                $where[$created] = ['exp',sprintf("TO_DAYS(NOW())-TO_DAYS(%s) <=20",$created)];
                break;
            case 4://受限
                $created = self::ACCOUNT_OVERDRAFT_TABLE.'.created_at';
                $where[$created] = ['exp',sprintf("TO_DAYS(NOW())-TO_DAYS(%s) >20",$created)];
                break;
        }
        $where[self::ACCOUNT_TABLE.'.d_id'] = ['exp',"{$dTable}.d_id is not null"];//and {$dTable}.credit_line>0
        $search['status'] = empty($params['status']) ? '8' : $params['status'] ;
        return ['where' => $where , 'having' => $having , 'search' => $search];
    }

    /** 初始化条件查询
     * @return mixed
     */
    public function getPageParam()
    {
        $on = sprintf("%s.member_id=%s.member_id , %s.member_id=%s.d_id , %s.member_id=%s.d_id and %s.freeze_status=1 , %s.member_id=%s.d_id",
            self::SELLER_TABLE,self::MEMBER_TABLE,
            self::SELLER_TABLE,self::ACCOUNT_TABLE,
            self::SELLER_TABLE,self::ACCOUNT_LOG_TABLE,self::ACCOUNT_LOG_TABLE,
            self::SELLER_TABLE,self::ACCOUNT_OVERDRAFT_TABLE
        );
        $option['field'] = sprintf("%s.member_id,%s.seller_name , %s.member_truename,%s.member_mobile , %s.* , sum(%s.money) as jxb_total , %s.created_at as overdraft_time",
            self::SELLER_TABLE,self::SELLER_TABLE,self::MEMBER_TABLE,self::MEMBER_TABLE,self::ACCOUNT_TABLE,self::ACCOUNT_LOG_TABLE,self::ACCOUNT_OVERDRAFT_TABLE
        );
        $option['join']  = ['left','left','left','left'];
        $option['on']    = $on;
        $option['group'] = self::SELLER_TABLE.'.member_id';
        $option['order'] = self::SELLER_TABLE.'.member_id desc';
        return $option;
    }
    /**
     * @param $status 获取充值的状态（文字）
     * @return mixed|string
     */
    public function getRegStatus($status,$type='recharge')
    {
        $statusArr= $this->getStatusData($type);
        foreach($statusArr as $k => $val){
            if($status == $val['val']){
                return $val['name'];
            }
        }
    }

    /** 获取下一部的操作状态
     * @param $status
     * @param string $type
     * @return bool
     */
    public function getSeepStatus($status,$type='recharge')
    {
        $statusArr= $this->getStatusData($type);
        foreach($statusArr as $key =>$val){
            if($status == $val['val']){
                return $statusArr[$key+1]['val'];
            }
        }
        return false;
    }

    /** 修改提现数据（并关联工单）
     * @param $id
     * @param $step
     * @param $type
     * @param $find
     * @param $operation
     * @param null $options
     * @return mixed
     */
    public function updateWithdraw($id,$step,$type,$find,$operation,$options=null)
    {
        $operation['related_id'] = $id;
        $operation['step']       = $step;
        $update['updated_at']    = get_now2();
        $update['kefu_confirm_status'] = $this->getSeepStatus($step,'withdraw');
        switch($type){
            case 'refuse'://平台拒绝
                $update['kefu_confirm_status'] = 5;
                $operation['operation'] = "拒绝";
                $operation['remark']    = "平台拒绝";
                //添加日志并退还可用余额
                $this->setAccountEvent($find['d_id'] , $id , $find['money'] , 3 , 1 ,$find['fee']);
                break;
            case 'returns'://退单
                $update['kefu_confirm_status'] = 3;
                $operation['operation'] = "退单";
                $operation['remark']    = "退单并返回到待接单状态";
                break;
            case 'confirms'://提现确认
                //确认提现要插入交易日志
                $this->setAccountEvent($find['d_id'] , $id , $find['money'] , 2 , 1 , $find['fee']);
                $update['kefu_confirm_money']  = $find['money'];
                $update['recharge_confirm_at'] = trim($_POST['recharge_confirm_at']);
                break;
            case 'saves'://状态为已入账未补充（不修改状态）
            case 'subSaves'://保存改状态
            case 'updates'://状态为已入账已补充更正
                if($type == 'saves'){
                    unset($update['kefu_confirm_status']);
                }
                $update['bank_voucher_code']  = trim($_POST['bank_voucher_code']);
                $update['accounting_voucher'] = trim($_POST['accounting_voucher']);
                $update['recharge_voucher']   = upOneFile('recharge_voucher');
            default:;
                /**
                case 'agree'://批准办理
                case 'orders'://提现接单
                case 'update_reason'://更正信息
                */
        }
        if(in_array($type,['updates','notUps'])){
            $update['kefu_confirm_status'] = 1;
        }
        if(!isset($operation['operation'])){
            $operation['operation'] = "将".self::ACCOUNT_WITHDRAW_TABLE."表的dwb_id为".$id .'的提现记录由' .$find['kefu_confirm_status'].'转为'.$update['kefu_confirm_status'];
        }
        if(!is_null($options)){
            if(isset($options['update'])){
                foreach($options['update'] as $key =>$_val){
                    $update[$key] = $_val;
                }
            }
        }
        $where = ['dwb_id' => $id];
        return $this->table(self::ACCOUNT_WITHDRAW_TABLE)->update_with_record($where, $update, $find, $operation);
    }

    /** 操作状态数据获取
     * @param string $type
     * @return string
     */
    public function getStatusData($type='recharge'){
        $statusArr['recharge'] = [
            ['name'=>'待入账','val'=>0],
            ['name'=>'核实入账','val'=>1],
            ['name'=>'已入账未补充','val'=>2],
            ['name'=> '已入账已补充','val'=>3],
            ['name'=>'无此款项','val'=>4]
        ];
        $statusArr['withdraw'] = [
            ['name'=>'待批准','val'=>0],
            ['name'=>'待接单','val'=>3],
            ['name'=>'待转账','val'=>4],
            ['name'=>'已入账未补充','val'=>2],
            ['name'=>'已转账已补充','val'=>1],
            ['name'=>'已转账已补充待更正','val'=>'6'],//待更正
            ['name'=>'未成功','val'=>5]
        ];
        return in_array($type,['recharge','withdraw']) ? $statusArr[$type] : '';
    }

    /** 提现列表
     * @param $param
     * @return array
     */
    public function getWithdrawList($param)
    {
        $whereSearch = $this->setWithdrawWhere($param);
        $this->setTable(self::ACCOUNT_WITHDRAW_TABLE.','.self::MEMBER_TABLE.','.self::ADMIN_OPERATION);
        $join = ['left','left'];
        $on = sprintf("%s.member_id = %s.d_id , %s.dwb_id=%s.related_id and %s.type=%d",
            self::MEMBER_TABLE,self::ACCOUNT_WITHDRAW_TABLE,self::ACCOUNT_WITHDRAW_TABLE,self::ADMIN_OPERATION ,self::ADMIN_OPERATION,self::ACCOUNT_WITHDRAW_TYPE);
        $order = self::ACCOUNT_WITHDRAW_TABLE.".dwb_id desc";//self::ADMIN_OPERATION.'.updated_at desc,'.
        $fields = sprintf("%s.*,%s.member_name,%s.member_truename,%s.member_mobile",self::ACCOUNT_WITHDRAW_TABLE,self::MEMBER_TABLE,self::MEMBER_TABLE,self::MEMBER_TABLE);
        $this->setCountCol(sprintf('DISTINCT %s.dwb_id',self::ACCOUNT_WITHDRAW_TABLE));
        $result = $this->getPageList($whereSearch['where'],$fields,$join,$on,$order,self::ACCOUNT_WITHDRAW_TABLE.'.dwb_id');
        return ['list'=>$result['list'],'page'=>$result['page'],'search'=>$whereSearch['search']];
    }

    /**
     * 提现条件
     * @param $params
     * @return array
     */
    private function setWithdrawWhere($params){
        $search = $params;
        $where = $this->setLikeWhere([
            self::ACCOUNT_WITHDRAW_TABLE.'.dwb_id' => $params['code'],
            self::MEMBER_TABLE.'.member_name' => $params['seller_name'],
            self::MEMBER_TABLE.'.member_truename'=>$params['member_truename'],
            self::MEMBER_TABLE.'.member_mobile' => $params['seller_phone'],
            self::ACCOUNT_WITHDRAW_TABLE.'.bank_account'=>$params['receipt_user'],//收款方
            self::ACCOUNT_WITHDRAW_TABLE.'.bank_voucher_code'=>$params['bank_voucher_code'],//银行凭证
            self::ACCOUNT_WITHDRAW_TABLE.'.accounting_voucher'=>$params['accounting_voucher'],//记账凭证
            self::ADMIN_OPERATION.'.step_user_name' => $params['step_user_name']//办理人
        ]);
        $startEndWhere = $this->setStartToEnd([
            self::ACCOUNT_WITHDRAW_TABLE.'.money' => [$params['start_money'],$params['end_money']],//
            self::ACCOUNT_WITHDRAW_TABLE.'.kefu_confirm_money'=> [$params['start_recorded'],$params['end_recorded']],//入账金额
            self::ACCOUNT_WITHDRAW_TABLE.'.created_at'=> [$params['start_order_time'],$params['end_order_time']],//工单时间
            self::ACCOUNT_WITHDRAW_TABLE.'.updated_at' => [$params['start_update_time'],$params['end_update_time']],//状态更新时间
            self::ACCOUNT_RECHARGE_TABLE.'.recharge_confirm_at' => [$params['start_recorded_time'],$params['start_recorded_time']],//入账时间

        ]);
        if(count($startEndWhere) > 0){
            $where = count($where)>0 ? array_merge($where,$startEndWhere) : $startEndWhere;
        }
        if($params['status'] !=''){
            if($params['status'] == '-1'){
                $where[self::ACCOUNT_WITHDRAW_TABLE.'.kefu_confirm_status'] = ['eq',0];
            }else{
                $where[self::ACCOUNT_WITHDRAW_TABLE.'.kefu_confirm_status'] = ['eq',$params['status']];
            }
        }
        $operationWhere = $this->setOperationWhere([
            //self::ACCOUNT_WITHDRAW_TABLE.'.kefu_confirm_status' => ['eq',$params['status']],//状态
            self::ADMIN_OPERATION.'.step' =>  ['eq',$params['step']]   //办理步骤
        ]);
        if(count($operationWhere) > 0){
            $where = count($where)>0 ? array_merge($where,$operationWhere) : $operationWhere;
        }

        #--- 手续费 ---------------------
        if ($params['fee'] !=''){
            switch($params['fee']){
                case 1:
                    $where[self::ACCOUNT_WITHDRAW_TABLE.'.fee'] = ['gt',0];
                    break;
                case 4:
                    $where[self::ACCOUNT_WITHDRAW_TABLE.'.fee'] = ['elt',0];
                    break;
            }
            $search['fee'] = $params['fee'];
        }
        $search['status'] = empty($params['status']) ? '8' : $params['status'];
        $search['step']   = empty($params['step']) ? '8' : $params['step'];
        $search['fee']    = empty($params['fee']) ? '8' : $params['fee'];;
        return ['where' => $where ,  'search' => $search];
    }
    /**
     * 获取充值记录审核的附件信息
     */
    public function getCommentsByRecharge($id,$type=0)
    {
        $tableArr = ['hc_daili_recharge_bank','hc_daili_withdraw_bank'];
        $table = $tableArr[$type];
        $where= [
            'related'        =>    $table.'|'.$id,
//            'step'         =>    $recharge['status'],//
            'is_del'         =>     0
        ];
        $ret = $this->table('hc_admin_operation_comment')->where($where)->order('created_at desc')->select();
        return $ret;
    }

    public function getOperationCount($id,$step,$type=0)
    {
        $tableArr = ['hc_daili_recharge_bank','hc_daili_withdraw_bank'];
        $table = $tableArr[$type];
        $where= [
            'related'        =>  $table.'|'.$id,
            'step'           =>  $step,
            'is_del'         =>  1
        ];
        return $this->table('hc_admin_operation_comment')->where($where)->count();
    }

    public function delComent($id)
    {
        return $this->table('hc_admin_operation_comment')->where(['id'=>$id])->update(['is_del'=>1]);
    }
    /** 查询工单
     * @param $type
     * @param $id
     * @param bool $list
     * @param null $step
     * @return mixed
     */
    public function getOperations($type,$id,$list=true,$step=null)
    {
        $log_map['type']       =  $type;
        $log_map['related_id'] =  $id;
        if(!is_null($step)){
            $log_map['step']   = $step;
        }
        if($list){
            $operations = $this->table('hc_admin_operation')->where($log_map)->order('created_at asc')->select();
        }else{
            $operations = $this->table('hc_admin_operation')->where($log_map)->order('created_at desc')->find();
        }
        return $operations;
    }
    /**
     * @param $seller_id    售方id
     * @param $id           对象id（充值或提现）
     * @param $money        操作金额
     * @param int $type     交易类型（1充值，2提现、3提现不成功）
     * @param int $status   日志状态
     * @return bool
     */
    public function setAccountEvent($seller_id,$id,$money,$type=1,$status=1,$fee=''){
        $remarkArr = ['充值（确认到账）','已完成','未成功'];
        $remark = $remarkArr[$type-1];
        //检查资金账号，不存在添加账号
        $this->checkAccount($seller_id);
        $this->beginTransaction();
        try{
            //修改支付日志状态
            $this->updateAccountLog($id , $type , $status , $remark , $money);
            switch($type){
                case 1:
                    $this->addAccount($seller_id,$money);//增加账号余额
                    //添加资金总日志
                    $this->addAccountLog($seller_id,$money,$id,$type);
                    break;
                case 2://提现成功
                    $this->reduceAccount($seller_id,$money,$fee,$id);//扣除账号余额
                    break;
                case 3://提现无此款项
                    $this->returnAccount($seller_id,$id,$money);//返回可用余额
                    //添加资金总日志
                    $this->addAccountLog($seller_id,$money,$id,$type);
                    break;
                default:
                return false;
            }
            $this->commit();
            return true;
        }catch (Exception $e){
            $this->rollback();
            return false;
        }
        return true;
    }

    /**
     * 账号日志总表记录
     * @param $d_id
     * @param $money
     * @param $recharge_id
     * @param int $payType
     * @param int $order_id
     * @return mixed
     */
    private function addAccountLog($d_id,$money,$recharge_id,$payType,$order_id=0)
    {
        switch($payType){
            case 1:
                $typeArr = ['from_remark'=>'无','to_remark'=>'充值（确认到账）','remark'=>'充值','to_user_id'=>$d_id,'to_where'=>2];
                break;
            case 2:
                $typeArr = ['from_remark'=>'提现','to_remark'=>'无','remark'=>'提现','to_user_id'=>0,'to_where'=>4];
                break;
            case 3:
                $typeArr = ['from_remark'=>'无','to_remark'=>'重新入账','remark'=>'提现重新入账','to_user_id'=>$d_id,'to_where'=>2];
                break;
        }
        $logData = [
            'from_user_id'          => ($payType==1) ? 0 : $d_id,//'支出方用户id  结合对应where使用',
            'from_where'            => ($payType==1) ? 4 : 2,//'1,客户 2,售方 3,平台',
            'from_remark'           => $typeArr['from_remark'],
            'to_user_id'            => $typeArr['to_user_id'],//'收入方id',
            'to_where'              => $typeArr['to_where'],//'1.客户，2. 售方 3.平台,4.外部',
            'to_remark'             => $typeArr['to_remark'],//'收入方说明',
            'trade_no'              => '',//'流水号',
            'remark'                => "售方的账号{$typeArr['remark']}:￥".$money,//'说明',
            'money'                 => $money,//'金额',
            'type'                  => $payType.'1',//'流水类型、10转入客户 11转入售方、20客户转出，21售方转出、30客户解冻，31售方解冻、40客户冻结，41售方冻结',
            'method_type'           => $payType.'1',//'流水类型、10客户充值 11售方充值 、20k客户提现 21 售方提现、30购买、40退款'
            'related_id'            => $recharge_id,//'对应表的id  对应表的id 结合method_type来做',
            'order_id'              => $order_id,//'购车订单号',
            'special_application_id'=> 0,//'特事审批id (跟type配合使用)',
            'flow_type'             => 1,//'1收入，2成本 （该字段表示该资金流向是收入还是成本）',
            'status'                => 1,//'状态',
            'created_at'            => get_now2(),
            'updated_at'            => get_now2()
        ];
       return $this->table(self::SYS_ACCOUNT_LOG)->insert($logData);
    }

    /**
     * @param $item_id
     * @param int $type 1充值、2提现、3提现无此款项
     * @param int $status
     * @return mixed
     */
    private function updateAccountLog($item_id,$type=1,$status=1,$remark='',$money)
    {
        $type = ($type >2) ? 2 : $type;
        $isLog = $this->table(self::ACCOUNT_LOG_TABLE)->where(['item_id'=>$item_id,'type'=>$type])->count();
        if($isLog){
            $update = [
                'status'            => $status,
                'updated_at'        => get_now2()
            ];
            if($type ==1) {
                $update['item']            = $remark;
                $update['remark']          = $this->rechargeRemark($item_id);
                $update['credit_avaiable'] = ['exp','credit_avaiable+'.($money)];
                $update['money']           = $money;
            }else{
                $update['remark'] = $remark;
            }
            return $this->table(self::ACCOUNT_LOG_TABLE)
                ->where(['item_id'=>$item_id,'type'=>$type])
                ->update($update);
        }
    }

    /**
     * 格式化充值成功 说明
     * @param $item_id
     * @return string
     */
    public function rechargeRemark($item_id){
        //银行卡号
        $bank = $this->table(self::ACCOUNT_RECHARGE_TABLE)->field('d_id,bank_account')->where(['drb_id'=>$item_id])->find();
        //用户名
        $member = $this->table(self::MEMBER_TABLE)->field('member_truename')->where(['member_id'=>$bank['d_id']])->find();
        return chanageStr($bank['bank_account'],0,-4,'***').chanageStr($member['member_truename'],3,strlen($member['member_truename']),'***');
    }
    /** 充值成功
     * @param $seller_id
     * @param $money
     * @return mixed
     */
    private function addAccount($seller_id,$money)
    {
        //更新用户总金额,更新用户可用余额
        return $this->table(self::ACCOUNT_TABLE)
            ->where(['d_id'=>$seller_id])
            ->update([
                'updated_at'        =>get_now2(),
                'total_deposit'     =>['exp','total_deposit+'.$money],
                'avaliable_deposit' =>['exp','avaliable_deposit+'.$money]
            ]);
    }

    /**
     * 提现成功
     * @param $seller_id
     * @param $money
     * @param $fee   (手续费)
     * @return bool
     */
    public function reduceAccount($seller_id,$money,$fee,$item_id)
    {
        $isAccount = $this->table(self::ACCOUNT_TABLE)
        ->where(['d_id' => $seller_id])
        ->update([
            'updated_at'        => get_now2(),
            'total_deposit'     => ['exp','total_deposit-'.$fee],
            'avaliable_deposit' => ['exp','avaliable_deposit-'.$fee],
            //'temp_deposit'      => ['exp','temp_deposit-'.$money]
        ]);
        //插入资金总表
        //$this ->addAccountLog($seller_id,$money,$item_id,2);
        if($isAccount){
            //生成手续费记录
            if($fee >0){
                //添加日志
                $logData = [
                    'd_id'             => $seller_id,
                    'money'            => $fee,
                    'item_id'          => $item_id,
                    'item'             => '提现手续费',
                    'remark'           => '超过当月免费次数',
                    'credit_avaiable'  => $this->getUserMoney($seller_id),
                    'type'             => 2,
                    'pay_type'         => 2,
                    'order_id'         => 0,
                    'freeze_status'    => 0,
                    'freeze_time'      => '',
                    'money_type'       => '-',
                    'status'           => 1,
                    'created_at'       => get_now2(),
                    'updated_at'       => get_now2()
                ];
                $this->table(self::ACCOUNT_LOG_TABLE)->insert($logData);
                //手续费资金日志
                $logData = [
                    'from_user_id'          => $seller_id,//'支出方用户id  结合对应where使用',
                    'from_where'            => 2,//'1,客户 2,售方 3,平台',
                    'from_remark'           => '提现手续费',//'支出方说明',
                    'to_user_id'            => 0,//'收入方id',
                    'to_where'              => 3,//'1.客户，2. 售方 3.平台',
                    'to_remark'             => '提现手续费',//'收入方说明',
                    'trade_no'              => '',//'流水号',
                    'remark'                => "售方的账号提现手续费:￥".$fee,//'说明',
                    'money'                 => $fee,//'金额',
                    'type'                  => '21',//'流水类型、10转入客户 11转入售方、20客户转出，21售方转出、30客户解冻，31售方解冻、40客户冻结，41售方冻结',
                    'method_type'           => '21',//'流水类型、10客户充值 11售方充值 、20k客户提现 21 售方提现、30购买、40退款'
                    'related_id'            => $item_id,//'对应表的id  对应表的id 结合method_type来做',
                    'order_id'              => 0,//'购车订单号',
                    'special_application_id'=> 0,//'特事审批id (跟type配合使用)',
                    'flow_type'             => 1,//'1收入，2成本 （该字段表示该资金流向是收入还是成本）',
                    'status'                => 1,//'状态',
                    'created_at'       => get_now2(),
                    'updated_at'       => get_now2()
                ];
                $this->table(self::SYS_ACCOUNT_LOG)->insert($logData);
            }
        }
    }

    /** 用户可用余额
     * @param $user_id
     * @return mixed
     */
    private function getUserMoney($user_id)
    {
        $account = $this->table(self::ACCOUNT_TABLE)->where(['d_id'=>$user_id])->find();
        return $account['avaliable_deposit'];
    }

    /** 提现不成功
     * @param $seller_id
     * @param $item_id
     * @param string $remark
     * @return bool
     */
    public function returnAccount($seller_id,$item_id,$money,$remark='重新入账')
    {
        $oldLog = $this->table(self::ACCOUNT_LOG_TABLE)->where(['item_id'=>$item_id,'type'=>2])->find();
        //添加日志
        $logData = [
            'd_id'             => $seller_id,
            'money'            => $money,
            'item_id'          => $item_id,
            'item'             => $remark,
            'remark'           => "提现不成功（{$item_id}）",
            'credit_avaiable'  => $oldLog['credit_avaiable'] + $money,
            'type'             => $oldLog['type'],
            'pay_type'         => $oldLog['pay_type'],
            'order_id'         => $oldLog['order_id'],
            'freeze_status'    => $oldLog['freeze_status'],
            'freeze_time'      => $oldLog['freeze_time'],
            'money_type'       => '+',
            'status'           => 1
        ];
        $isAdd = $this->table(self::ACCOUNT_LOG_TABLE)->insert($logData);
        if(!$isAdd){
            throw new Exception('账户添加失败！');
        }
        //更新用户总金额,更新用户可用余额
        $isAccount = $this->table(self::ACCOUNT_TABLE)
        ->where(['d_id' => $seller_id])
        ->update([
            'updated_at'        => get_now2(),
            'total_deposit'     => ['exp','total_deposit+'.$money],
            'avaliable_deposit' => ['exp','avaliable_deposit+'.$money],
            //'temp_deposit'      => ['exp','temp_deposit-'.$money]
        ]);
        if(!$isAccount){
            throw new Exception('账户更新失败！');
        }
    }

    /**
     * 检查售方账号
     * @param $seller_id
     * @return mixed
     */
    private function checkAccount($seller_id){
        $checkAccount = $this->table(self::ACCOUNT_TABLE)->where(['d_id'=>$seller_id])->count();
        if(empty($checkAccount)){
            return $this->table(self::ACCOUNT_TABLE)->insert(['d_id'=>$seller_id]);
        }
    }
}