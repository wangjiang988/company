<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/16
 * Time: 16:45
 */
defined('InHG') or exit('Access Invalid!');
class seller_financeControl extends SystemControl{
    protected $account;

    CONST ACCOUNT_RECHARGE_TABLE  = 'hc_daili_recharge_bank';
    CONST ACCOUNT_WITHDRAW_TABLE  = 'hc_daili_withdraw_bank';

    CONST ACCOUNT_RECHARGE_TYPE = 22;
    CONST ACCOUNT_WITHDRAW_TYPE = 23;
    CONST APPLICATION_TYPE      = 20;

    public function __construct()
    {
        parent::__construct();
        $this->account = Model('hc_daili_account');
    }

    public function indexOp($export=false)
    {
        $account = $this->account;
        $searchOptions = $account->setWhere($_GET);
        $option = $account->getPageParam();
        $where  = $searchOptions['where'];
        $search = $searchOptions['search'];
        //$model = $this->getModel(self::SELLER_TABLE);
        $result = $account->getPageList($where,$option['field'],$option['join'],$option['on'],$option['order'],$option['group'],$searchOptions['having']);//$model ->getPageList($option);

        if($export==true){
            return $result['list'];exit;
        }
        Tpl::output('search',$search);
        Tpl::output('list',$result['list']);
        Tpl::output('page',$result['page']);
        Tpl::output('uri',$this->getServerQueryString());
        Tpl::setDir('SellerFinance');
        Tpl::showpage('finance.list');
    }
    /** 可提现余额
     * @param bool $export
     * @return mixed
     */
    public function avaliableOp($export=false)
    {
        $member_id = (int) $_GET['id'];
        $sellerInfo = $this->account->getSellerInfo($member_id,'member_name,member_truename,member_mobile');
        $result = $this->account->getSellerLogList($_GET);
        if($export==true){
            return $result['list'];exit;
        }
        Tpl::output('seller',$sellerInfo);
        Tpl::output('search',$result['search']);
        Tpl::output('list',$result['list']);
        Tpl::output('page',$result['page']);
        Tpl::output('uri',$this->getServerQueryString());
        Tpl::setDir('SellerFinance');
        Tpl::showpage('finance.avaliable_list');
    }

    /**
     * 充值列表
     */
    public function rechargeOp($export=false)
    {
        $result = $this->account->getRechargeList($_GET);
        if($export==true){
            return $result['list'];exit;
        }
        Tpl::output('search',$result['search']);
        Tpl::output('list',$result['list']);
        Tpl::output('page',$result['page']);
        Tpl::output('uri',$this->getServerQueryString());
        Tpl::setDir('SellerFinance');
        Tpl::showpage('finance.recharge_list');
    }

    /**
     * 充值详情
     */
    public function recharge_detailOp()
    {
        $id = (int) $_GET['id'];
        $find = $this->account->getRechargeFind(['drb_id'=>$id]);
        $delCount = $this->account->getOperationCount($id,$find['kefu_confirm_status']);
        $find['delCommentCount'] = $delCount;
        $find['admin_id'] = $this->admin_info['id'];

        switch($find['kefu_confirm_status']){
            case 0:
            case 1:
                $operation = $this->account ->getOperations(self::ACCOUNT_RECHARGE_TYPE,$id,false,0);
                Tpl::output('operation',$operation);
                $tpl = 'finance.recharge_detail';
                break;
            case 2:
            case 3:
                $tpl = 'finance.recharge_ok_detail';
                break;
            default:
                $tpl = 'finance.recharge_err';
        }
        $remarks    = $this->account->getCommentsByRecharge($id);
        $operations = $this->account->getOperations(self::ACCOUNT_RECHARGE_TYPE,$id);
        Tpl::output('operations',$operations);

        Tpl::output('remarks',$remarks);
        Tpl::output('statusString',$this->account->getRegStatus($find['kefu_confirm_status']));
        Tpl::output('find',$find);
        Tpl::setDir('SellerFinance');
        Tpl::showpage($tpl);
    }

    public function ajax_del_commentOp()
    {
        $id = (int) $_GET['id'];
        $res = $this->account ->delComent($id);
        $jsonArray = $res ? ['code'=>200,'msg'=>'删除成功'] : ['code'=>404,'msg'=>'删除失败！'];
        echo json_encode($jsonArray);exit;
    }
    /**
     * 提交充值修改记录
     */
    public function save_rechargeOp()
    {
        if($_POST != NULL){
            $id                  = $_POST['drb_id'];
            $find = $this->account->getRechargeFind(['drb_id'=>$id]);
            $adminInfo = $this->systemLogin();
            $operation = [
                'user_id'    =>  $adminInfo['id'],
                'user_name'  =>  $adminInfo['name'],
                'related'    =>  self::ACCOUNT_RECHARGE_TABLE.'|'.$id, //关联数据
                'step'       =>  $find['kefu_confirm_status'], //操作步骤
                'type'       =>  self::ACCOUNT_RECHARGE_TYPE,  //13
            ];
            $status              = intval($_POST['recorded_status']);
            $type                = $_POST['type'];
            $recharge_money      = (int) $_POST['recharge_money'];
            $recharge_confirm_at = $_POST['recharge_confirm_at'];
            $step                = $find['kefu_confirm_status'];
            $remark              = trim($_POST['remark']);
            if($status==1){
                $update['kefu_confirm_status'] = $find['kefu_confirm_status'] +1;
                if($recharge_money >0 ){
                    $update['kefu_confirm_money'] = $recharge_money;
                    if($type =='confirms'){//更新账户
                        $this->account ->setAccountEvent($find['d_id'],$id,$recharge_money,1,1);
                    }
                }
                $update['recharge_confirm_at'] = $recharge_confirm_at;
                $update['updated_at'] = get_now2();
                $operation['operation'] = "将".self::ACCOUNT_RECHARGE_TABLE."表的drb_id为" .$id . '的充值记录由' .
                    $find['kefu_confirm_status'] . '转为' . $update['kefu_confirm_status'];
                $operation['step'] = $step;
            }else{
                $update['kefu_confirm_status'] = 4;
                $update['updated_at'] = get_now2();
                $operation['operation'] = "拒绝";
                $operation['step'] = 4;
                isset($_POST['remark']) && ($operation['remark'] = $remark);
            }
            $where = ['drb_id' => $id];
            $res = $this->account->table(self::ACCOUNT_RECHARGE_TABLE)->update_with_record($where, $update, $find, $operation);
            if($res){
                showDialog('操作成功','index.php?act=seller_finance&op=recharge_detail&id='.$id,'succ');
            }else{
                showDialog('操作失败','','error');
            }
        }
    }
    /**
     * 保存成功的入账状态
     */
    public function save_recharge_okOp()
    {
        if($_POST != NULL) {
            $id = $_POST['drb_id'];
            $find = $this->account->getRechargeFind(['drb_id' => $id]);
            $adminInfo = $this->systemLogin();
            $_type = trim($_POST['type']);
            $operation = [
                'user_id' => $adminInfo['id'],
                'user_name' => $adminInfo['name'],
                'related' => self::ACCOUNT_RECHARGE_TABLE . '|' . $id, //关联数据
                'step' => $find['kefu_confirm_status'] //操作步骤
            ];
            if(in_array($_type,['submits','saves'])){
                if($_type =='submits'){//提交补充信息
                    $update['kefu_confirm_status'] = $find['kefu_confirm_status'] +1;
                    $operation[ 'type'] = self::ACCOUNT_RECHARGE_TYPE;
                    $operation['operation'] = "将".self::ACCOUNT_RECHARGE_TABLE."表的drb_id为" .$id . '的充值记录由' .
                        $find['kefu_confirm_status'] . '转为' . $update['kefu_confirm_status'];
                }else{//保存
                    $operation['operation'] = "保存充值补充信息";
                    $operation[ 'type']     = 20;  //13
                }
                $bank_name          = trim($_POST['bank_name']);
                $bank_voucher_code  = trim($_POST['bank_voucher_code']);
                $accounting_voucher = trim($_POST['accounting_voucher']);
                $update['admin_bank_name']    = $bank_name;
                $update['bank_voucher_code']  = $bank_voucher_code;
                $update['accounting_voucher'] = $accounting_voucher;
            }
            $update['updated_at'] = get_now2();
            $operation['step']    = $find['kefu_confirm_status'];
            $where = ['drb_id' => $id];
            $res = $this->account->table(self::ACCOUNT_RECHARGE_TABLE)->update_with_record($where, $update, $find, $operation);
            if($res){
                showDialog('操作成功','index.php?act=seller_finance&op=recharge_detail&id='.$id,'succ');
            }else{
                showDialog('操作失败','','error');
            }
        }
    }

    /**
     * 提现列表
     * @param bool $export
     * @return mixed
     */
    public function withdrawOp($export=false)
    {
        $result = $this->account->getWithdrawList($_GET);
        if($export==true){
            return $result['list'];exit;
        }
        Tpl::output('search',$result['search']);
        Tpl::output('list',$result['list']);
        Tpl::output('page',$result['page']);
        Tpl::output('uri',$this->getServerQueryString());
        Tpl::setDir('SellerFinance');
        Tpl::showpage('finance.withdraw_list');
    }

    public function withdraw_detailOp()
    {
        $id = (int) $_GET['id'];
        $find = $this->account->getWithdrawFind(['dwb_id'=>$id]);
        $errStatus = $this->account->getRegStatus($find['kefu_confirm_status'],'withdraw');
        $delCount = $this->account->getOperationCount($id,$find['kefu_confirm_status'],1);
        $find['delCommentCount'] = $delCount;
        $find['admin_id'] = $this->admin_info['id'];
        switch($find['kefu_confirm_status']){
            case 0:
            case 3:
            case 4:
                $operations = $this->account ->getOperations(self::ACCOUNT_WITHDRAW_TYPE,$id,false,3);
                $tpl = 'finance.withdraw_detail';
                break;
            case 1:
            case 2:
                $tpl = 'finance.withdraw_ok_detail';
                break;
            case 6:
                switch($find['reject_status']){
                    case 53://平台原因（更正）
                       /* $operation = $this->account ->getOperations(self::ACCOUNT_WITHDRAW_TYPE,$id,false,1);
                        Tpl::output('operation',$operation);*/
                        $tpl = 'finance.withdraw_ok_detail';
                    break;
                }
                break;
            case 5:
                switch($find['reject_status']) {
                    case 52://售方原因
                    case 54://售方账号错误 TODO(提现金额已重新转入可提现余额)
                        $errStatus = '未成功';
                        $tpl = 'finance.withdraw_refuse';
                        break;
                    case 51://平台拒绝
                        $errStatus = '未成功';
                        $operations = '';
                        $operation = $this->account ->getOperations(self::ACCOUNT_WITHDRAW_TYPE,$id,false,1);
                        Tpl::output('operation',$operation);
                        $tpl = 'finance.withdraw_refuse';
                        break;
                    default:
                        $tpl = 'finance.withdraw_err';
                }
            break;
        }
        $remarks = $this->account->getCommentsByRecharge($id,1);
        if(!isset($operations)){
            $operations = $this->account->getOperations(self::ACCOUNT_WITHDRAW_TYPE,$id,true,['exp','step>0 and step<6']);
        }
        Tpl::output('operations',$operations);
        Tpl::output('remarks',$remarks);
        Tpl::output('statusString',$errStatus);
        Tpl::output('find',$find);
        Tpl::setDir('SellerFinance');
        Tpl::showpage($tpl);
    }

    /**
     * 提交提现修改记录
     */
    public function save_withdrawOp()
    {
        if($_POST != NULL){
            $id                  = $_POST['dwb_id'];
            $type                = $_POST['type'];
            $this->setWithdrawData($id,$type);
        }
    }

    /**
     * 更正提现
     */
    public function withdraw_resultOp()
    {
        $reason = intval($_GET['reason']);//
        $id = (int) $_GET['id'];
        $update['reject_status'] = $reason;
        if($reason ==52){
            $update['kefu_confirm_status'] = 5;
        }
        $this->setWithdrawData($id,'update_reason',['update'=>$update]);
    }

    /**
     * 特别情况
     */
    public function withdraw_endOp()
    {
        $member_id = (int) $_GET['id'];
        $find = $this->account->getSellerInfo($member_id);
        $special = Model('hc_user_special_application');
        $where = ['user_id'=>$member_id,'type'=>self::APPLICATION_TYPE];
        $status = $_GET['status'];
        if($status !='' && is_numeric($status)){
            $where['hc_user_special_application.status'] = $status;
        }
        $table = 'hc_user_special_application,member,admin';
        $field="hc_user_special_application.*,member.member_mobile as user_phone,member.member_email as user_email,admin.dept_id";
        $on = 'hc_user_special_application.user_id=member.member_id,hc_user_special_application.apply_admin_id=admin.admin_id';
        $result = $special->getList($where,['table'=>$table,'field'=>$field,'on'=>$on]);
        Tpl::output('seller',$find);
        Tpl::output('search',['status'=>($_GET['status'] =='') ? 8:$_GET['status']]);
        Tpl::output('list',$result);
        Tpl::output('page',$special->showPage());
        Tpl::setDir('SellerFinance');
        Tpl::showpage('finance.special');
    }
    /**
     *
     */
    public function add_specialOp()
    {
        if(chksubmit()){
            $application_model = Model('hc_user_special_application');
            //提交表单
            $request         =   $_POST;
            $special_type    =    $request['special_type'];
            $money = 0;
            if($special_type && $special_type < 6){
                $money       =    $request['special_type_'.$special_type.'_val'];
            }
            if($money<= 0){
                showDialog('金额不正确','','error');
            }
            $special_info   = get_application_info_by_special_type($special_type, 'member');
            //申请记录
            $application  = [
                'user_id'         => $request['id'],
                'special_type'    => $request['special_type'],
                'money'           => $money,
                'money_type'      => $special_info['money_type'],
                'name'            => $special_info['name'],
                'from_where'      => $special_info['from'],
                'to_where'        => $special_info['to'],
                'type'            => self::APPLICATION_TYPE,
                'reason'          => $request['reason'],
                'remark'          => $request['remark'],
                'apply_admin_id'  => $this->admin_info['id'],
                'apply_admin_name'=> $this->admin_info['name'],
            ];
            $res = $application_model->addApplication($application);
            if($res){
                showDialog('操作成功',url('seller_finance','withdraw_end',['id'=>$request['id']]),'succ');
            }else{
                showDialog('操作失败','','error');
            }
        }else{
            $member_id = (int) $_GET['id'];
            $find = $this->account->getSellerInfo($member_id);
            //获取用户相关订单信息
            $order_list =  $this->account->table('hc_order')->field('order_sn,id')->where(['seller_id'=>$member_id,''])->select();
            foreach ($order_list as $key => $item)
            {
                $order_list[$key]['label']  =  $item['order_sn'];
            }
            $max_transfer_to_user_account = config('max_transfer_to_user_account');
            Tpl::output('max_transfer_to_user_account',$max_transfer_to_user_account);
            Tpl::output('order_list',$order_list);
            Tpl::output('seller',$find);
            Tpl::setDir('SellerFinance');
            Tpl::showpage('finance.special_add');
        }
    }

    public function save_specialOp()
    {
        if(chksubmit()){
            $id = $_POST['id'];
            if(!$id) showMessage('缺少参数[id]');
            $account_model = Model('hc_daili_account');
            $special      = Model('hc_user_special_application');
            $application  = $special->where(['id'=> $id])->find();
            if(!$application)  showMessage('该特事审批不存在');
            $seller_account = $account_model->where(['d_id'=>$application['user_id']])->find();
            if(!$seller_account)  showMessage('该特事审批用户账户不存在');
            $data['status']       = intval($_POST['status']);
            $data['judge_remark'] = trim($_POST['judge_remark']);
            $data['judge_at']     = get_now2();

            $data['judge_admin_name'] = $this->admin_info['name'];
            $res = $special ->updateApplication($data,['id'=>$id]);
            if($res){
                if( $data['status'] == 1 )  //审批通过
                {
                    //修改售方账户资金
                    $ret = $this->_handle_application($application);
                    //插入资金总表
                    if($ret)
                    {
                        //处理售方可用余额变动
                        check_seller_account_by_application($seller_account,$application);
                        record_to_account_log($application,'application','dealer');
                    }
                }

                showDialog('操作成功',url('seller_finance','special_service'),'succ');
            }else{
                showDialog('操作失败','','error');
            }
        }
    }
    //特办事项处理子方法
    //处理钱的问题
    private function _handle_application($application)
    {
        $common_model  =   Model();
        $money         =   $application['money'];
        $user_id       =   $application['user_id'];
        if ($application['special_type'] < 5 ){
            $from  =  $application['from_where'];
            if($from == "1" || $from == "2"){ //TODO 平台转出，未定

            }else{
                $handle_arr = explode(',', $from);
                if($handle_arr && is_array($handle_arr)){
                    //第一个循环查看扣减字段，金额是否足
                    $enough = true;
                    foreach ($handle_arr as $table_info){
                        $table = explode('.',$table_info);
                        $data =  $common_model->table($table[0])
                            ->where(['d_id'=>$user_id])->find();
                        if($data[$table[1]]-$money < 0){ //字段金额不足
                            $enough = false;
                            break;
                        }
                    }
                    if(!$enough) return false;
                    foreach ($handle_arr as $table_info){
                        $table = explode('.',$table_info);

                        $update = [];
                        $update[$table[1]] = array('exp', $table[1].'-'.$money);
                        $common_model->table($table[0])
                            ->where(['d_id'=>$user_id])
                            ->update($update);  //减少成功
                    }
                }
            }
            $to     =    $application['to_where'];
            if($to == "1" || $to == "2"){ //TODO 平台转出,售方转出，未定

            }else {
                $handle_arr2 = explode(',', $to);
                if($handle_arr2 && is_array($handle_arr2)){
                    foreach ($handle_arr2 as $table_info2){
                        $table  = explode('.',$table_info2);

                        $update = [];
                        $update[$table[1]] = array('exp', $table[1].'+'.$money);
                        $common_model->table($table[0])
                            ->where(['d_id'=>$user_id])
                            ->update($update);  //增加成功
                    }
                }
            }
            return true;
        }else{//返还已得 获取返还  TODO  流程未通

        }


    }
    /**
     * 特别情况详情
     */
    public function special_detailOp()
    {
        if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
            showMessage('id参数不正确');
        }
        $id = $_GET['id'];
        $special = Model('hc_user_special_application');
        $find = $special->getFind(['id'=>$id]);
        $seller = $this->account ->getSellerInfo($find['user_id']);
        Tpl::output('seller',$seller);
        Tpl::output('application',$find);
        Tpl::setDir('SellerFinance');
        Tpl::showpage('finance.seller_special_detail');
    }

    /** 特勤审批列表
     * @param bool $export
     * @return mixed
     */
    public function special_serviceOp($export=false)
    {
        //部门表
        $special = Model('hc_user_special_application');
        $whereSearch = $this->account ->setSpecialWhereSearch($_GET);
        $where = $whereSearch['where'];
        $where['type'] = self::APPLICATION_TYPE;

        $search = $whereSearch['search'];
        $table = 'hc_user_special_application,member,admin';
        $field="hc_user_special_application.*,member.member_mobile as user_phone,member.member_name as user_name,admin.dept_id";
        $on = 'hc_user_special_application.user_id=member.member_id,hc_user_special_application.apply_admin_id=admin.admin_id';
        $result = $special->getList($where,['table'=>$table,'field'=>$field,'on'=>$on]);
        if($export ==true){
            return $result;
        }
        $dept = $this->account ->table('hc_admin_dept')->field('id,name')->select();
        Tpl::output('dept',$dept);
        Tpl::output('search',$search);
        Tpl::output('list',$result);
        Tpl::output('page',$special->showPage());
        Tpl::output('uri',$this->getServerQueryString());
        Tpl::setDir('SellerFinance');
        Tpl::showpage('finance.special_service_list');
    }

    /**
     * 导出特勤审批
     */
    public function exportSpecialOp()
    {
        $result = $this->special_serviceOp(true);
        $tmp = array();
        foreach($result as $k => $v){
            $tmp[$k][] = $v['id'];
            $tmp[$k][] = $v['created_at'];
            $tmp[$k][] = $v['user_name'];
            $tmp[$k][] = $v['user_phone'];
            $tmp[$k][] = $v['apply_admin_name'];
            $tmp[$k][] = $v['name'];
            $tmp[$k][] = $v['reason'];
            $tmp[$k][] = $v['updated_at'];
            $statusArr = ['待批准','通过','未通过'];
            $tmp[$k][] = $statusArr[$v['status']];
        }
        $titleArray = ['工单编号','工单时间','售方用户名','售方手机','申请人','申请项目','申请原因','状态更新时间','状态'];
        $this->createExcel($tmp,$titleArray,'售方财务-特勤审批');
    }

    public function finance_service_detailOp()
    {
        $id = $_GET['id'];
        if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
            showMessage('id参数不正确');
        }
        $special = Model('hc_user_special_application');
        $find = $special->getFind(['id'=>$id]);
        $seller = $this->account ->getSellerInfo($find['user_id']);
        Tpl::output('seller',$seller);
        Tpl::output('application',$find);
        Tpl::setDir('SellerFinance');
        Tpl::showpage('finance.special_detail');
    }
    /**
     * 异步，根据订单id 获取订单详情
     * 主要获取该订单的客户返回金额，售方平台的可用余额等等，供特别事项申请页面使用
     */
    public function ajax_get_orderinfoOp()
    {
        $request = getPayload();
        if($request['id'])
        {
            $order_id = $request['id'];
            $order_model  = Model('hc_order');
            //TODO  model 方法未写
            $order_info   = $order_model->getOrderDetailInfo($order_id);
            if($order_info){
                json(['code'=>200, 'data' => $order_info]);
            }
            else{
                json_error('请选择订单');
            }
        }else{
            json_error("订单id缺失") ;
        }
    }

    /** 设置修改数据
     * @param $id
     * @param $type
     * @param null $options
     */
    private function setWithdrawData($id,$type,$options=null){
        $find = $this->account->getWithdrawFind(['dwb_id'=>$id]);
        $operation = [
            'user_id'    =>  $this->admin_info['id'],
            'user_name'  =>  $this->admin_info['name'],
            'related'    =>  self::ACCOUNT_WITHDRAW_TABLE.'|'.$id, //关联数据
            'type'       =>  self::ACCOUNT_WITHDRAW_TYPE,
        ];
        $step                = $find['kefu_confirm_status'];
        $res = $this->account->updateWithdraw($id,$step,$type,$find,$operation,$options);
        if($res){
            //发送成功短信
            require_once dirname(__FILE__).'/../vendor/SendSms.php';
            $sms = new SendSms();
            switch($type){
                case 'refuse':
                    //触发提现拒绝短信（78745062）
                    $sms->sendSms($find['member_mobile'],'78745062','',['money'=>'￥'.$find['money']]);
                    break;
                case 'confirms':
                    //触发提现成功短信（78645065）
                    $sms->sendSms($find['member_mobile'],'78645065','',['money'=>'￥'.$find['money']]);
                    //插入真实提现记录
                    break;
            }
            showDialog('操作成功','index.php?act=seller_finance&op=withdraw_detail&id='.$id,'succ');
        }else{
            showDialog('操作失败','','error');
        }
    }
    /**
     * 导出文件
     */
    public function exportFileOp()
    {
        $result = $this->indexOp(true);
        $tmp = array();
        foreach($result as $k => $v){
            $tmp[$k][] = $v['seller_name'];
            $tmp[$k][] = $v['member_truename'];
            $tmp[$k][] = $v['member_mobile'];
            $tmp[$k][] = $v['basic_deposit'];
            $tmp[$k][] = $v['credit_line'];
            $tmp[$k][] = $v['avaliable_deposit'];
            $tmp[$k][] = $v['jxb_total'];
            $tmp[$k][] = $v['temp_deposit'];
            $tmp[$k][] = $v['total_deposit'];
            if($v['status'] ==2){
                $statusStr = "失效";
            }else{
                if($v['avaliable_deposit'] >=0){
                    $statusStr = "有效";
                }else{
                    $isOverdraft = getSellerOverdraftTime($v['$credit_line'],$v['overdraft_time']);
                    $statusStr = $isOverdraft ? "透支" : "受限" ;
                }
            }
            $tmp[$k][] = $statusStr;
        }
        $titleArray = ['用户名','售方姓名','售方手机','固定保证金','平台授信额度','可提现余额','加信宝','平台冻结','总资产','账户状态'];
        $this->createExcel($tmp,$titleArray);
    }

    /**
     * 导出可提现余额
     */
    public function exportAvaliableFileOp()
    {
        $result = $this->avaliableOp(true);
        $tmp = [];
        foreach($result as $k => $v){
            $tmp[$k][] = $v['created_at'];
            $tmp[$k][] = $v['item'];
            $tmp[$k][] = $v['remark'];
            $tmp[$k][] = $v['money'];
            $tmp[$k][] = $v['credit_avaiable'];
        }
        $titleArray = ['发生时间','项目','说明','收支金额','可提现余额'];
        $this->createExcel($tmp,$titleArray,'售方财务-可提现余额');
    }

    /**
     * 导出充值数据
     */
    public function exportRechargeOp()
    {
        $result = $this->rechargeOp(true);
        $tmp = [];
        foreach($result as $k => $v){
            $tmp[$k][] = $v['drb_id'];
            $tmp[$k][] = $v['created_at'];
            $tmp[$k][] = $v['member_name'];
            $tmp[$k][] = $v['member_truename'];
            $tmp[$k][] = $v['member_mobile'];
            $tmp[$k][] = $v['money'];
            $tmp[$k][] = in_array($v['kefu_confirm_status'],[2,3]) ? $v['money'] : '';
            $tmp[$k][] = chanageStr($v['bank_account'],0,-4,'***').chanageStr($v['daili_bank_name'],3,strlen($v['daili_bank_name']),'***');
            $tmp[$k][] = in_array($v['kefu_confirm_status'],[2,3]) ? $v['money'] : '';
            $tmp[$k][] = in_array($v['kefu_confirm_status'],[2,3]) ? $v['created_at'] : '';
            $tmp[$k][] = $this->account->getRegStatus($v['kefu_confirm_status']);
        }
        $titleArray = ['工单编号','提交时间','用户名','售方姓名','售方手机','提交金额','充值金额','充值方式','入账金额','确认入账时间','入账状态'];
        $this->createExcel($tmp,$titleArray,'售方财务-充值');
    }

    public function exportWithdrawOp()
    {
        $result = $this->withdrawOp(true);
        $tmp = [];
        foreach($result as $k => $v){
            $tmp[$k][] = $v['dwb_id'];
            $tmp[$k][] = $v['created_at'];
            $tmp[$k][] = $v['member_name'];
            $tmp[$k][] = $v['member_truename'];
            $tmp[$k][] = $v['member_mobile'];
            $tmp[$k][] = $v['money'];
            $tmp[$k][] = $v['fee'];
            $tmp[$k][] = chanageStr($v['bank_account'],0,-4,'***').chanageStr($v['daili_bank_name'],3,strlen($v['daili_bank_name']),'***');
            $tmp[$k][] = $v['bank_voucher_code'];
            $tmp[$k][] = in_array($v['kefu_confirm_status'],[1,2]) ? $v['created_at'] : '';
            $tmp[$k][] = $this->account->getRegStatus($v['kefu_confirm_status'],'withdraw');
        }
        $titleArray = ['工单编号','工单时间','售方用户名','售方姓名','售方手机','提现金额','提现手续费','收款方','银行凭证号','状态更新时间','状态'];
        $this->createExcel($tmp,$titleArray,'售方财务-提现');
    }
}