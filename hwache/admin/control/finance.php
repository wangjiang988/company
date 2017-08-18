<?php
/**
 * @author wangjiang
 * @time 20170505
 * @location admin.123.com
 * @module  交易
 * 财务管理
 * --客户财务
 * --售方财务
 * --平台财务
 */
defined('InHG') or exit('Access Invalid!');
class financeControl extends SystemControl
{
    //设置转入方式
    const RECHARGE_BANK_TYPE   = 2; //银行转账
    const RECHARGE_ONLINE_TYPE = 1; //线上转账

    //管理员操作类型
    const TRANSFER_BANK_OPERATION_TYPE   = 12; //转入-银行转账
    const WITHDRAW_OPERATION_TYPE        = 13; //提现操作类型
    const SPECIAL_OPERATION_TYPE        = 14; //特事审批操作类型

    //客户申请类型
    const APPLICATION_TYPE =    10;

    public function __construct()
    {
        parent::__construct();
        Tpl::setDir('Finance');
    }

    /**
     * 客户财务index
     * @author wangjiang
     */
    public function indexOp()
    {

        $user_model         =  Model('user');
        $account_model      =  Model('hc_user_account');
        $account_log_model  =  Model('hc_user_account_log');

        //是否有查询条件
        $where   = [];
        if(is_search())
        {
            $field_list =  [
                'id|like|users',
                'phone|like|users',
                's_avaliable_deposit,e_avaliable_deposit|between|hc_user_account',
                's_freeze_deposit,e_freeze_deposit|between|hc_user_account',
                //TODO	前端平台冻结字段查询条件没加
            ];
            $where       =  trans_form_to_where($field_list);
            $name = trim($_GET['name']);
            if(isset($_GET['name']) && $name!=''){
                $where['name']  =  ['exp','CONCAT(user_extension.last_name, user_extension.first_name) like "%'.$name.'%"'];
            }
        }
        $user_list       =  $user_model->getUserAccountList($where);
        $page            =  $user_model->showPage();
        //查詢出所有用戶的账户信息
        foreach ($user_list as $k => $user)
        {
            //name字段没有数据的写"未命名"
            if(!$user['name'])
                $user_list[$k]['name']  = C('default_username');
            $account                  =   $account_model->findByUser($user);
            if(!$account) { //TODO 解决历史数据没有一一对应的问题,写入模拟数据
                $account    = [
                    "user_id"=> $user['id'],
                    "total_deposit" => "0.00",
                    "avaliable_deposit"=> "0.00",
                    "freeze_deposit"=> "0.00",
                    "status"=> "0",
                    "created_at"=> "2000-00-00 00:00:00",
                    "updated_at"  => "2000-00-00 00:00:00"
                ];
            }
            $user_list[$k]['account']  = $account;

            //账户最新变动记录
            $last_log                   = $account_log_model->findLastLogByUser($user);
            if($last_log){
                $last_log = $last_log['item'].' / '.$last_log['money_type'].'￥'.
                            $last_log['money'].' / '.$last_log['created_at'];
            }else{
                $last_log = "暂无";
            }

            $user_list[$k]['last_log']  = $last_log;
        }

        //是否需要导出
        if(is_export())
        {
            import('libraries.excel');
            $excel_obj = new Excel();
            $excel_data = array();
            //设置样式
            $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
            //header
            $excel_data[0][0] = array('styleid'=>'s_title','data'=>'客户会员号');
            $excel_data[0][1] = array('styleid'=>'s_title','data'=>'客户姓名');
            $excel_data[0][2] = array('styleid'=>'s_title','data'=>'客户手机	');
            $excel_data[0][3] = array('styleid'=>'s_title','data'=>'可用余额');
            $excel_data[0][4] = array('styleid'=>'s_title','data'=>'加信宝');
            $excel_data[0][5] = array('styleid'=>'s_title','data'=>'平台冻结');
            $excel_data[0][6] = array('styleid'=>'s_title','data'=>'账户最新变动记录');
            //data
            foreach ($user_list as $k=>$v){
                $excel_data[$k+1][0] = array('data'=>$v['id']);
                $excel_data[$k+1][1] = array('data'=>$v['fullname']);
                $excel_data[$k+1][2] = array('data'=>$v['phone']);
                $excel_data[$k+1][3] = array('data'=>$v['account']['avaliable_deposit']);
                $excel_data[$k+1][4] = array('data'=>$v['account']['freeze_deposit']);
                $excel_data[$k+1][5] = array('data'=>0); //TODO  平台冻结暂时都设为0
                $excel_data[$k+1][6] = array('data'=>$v['last_log']);
            }
            $excel_data = $excel_obj->charset($excel_data,CHARSET);
            $excel_obj->addArray($excel_data);
            $excel_obj->addWorksheet($excel_obj->charset('客户财务',CHARSET));
            $p = $_GET['curpage'] ? $_GET['curpage'] : 1;
            $excel_obj->generateXML($excel_obj->charset('客户财务-P'.$p.'-',CHARSET).date('Y-m-d-H',time()));
            exit();
        }else{
            Tpl::output('list',$user_list);
            Tpl::output('page',$page);

            Tpl::showpage('finance.index');
        }
    }

    /**
     * 余额详情
     * @author wangjiang
     */
    public function a_deposit_detailOp()
    {
        if(!isset($_GET['uid']) || !is_numeric($_GET['uid'])){
            showMessage('uid参数不正确');
        }

        //获取用户信息
        $user_model         =  Model('user');
        $user               =  $user_model->getUserById($_GET['uid'], 'id,name,phone,email,created_at,updated_at');
        if(!$user['name']) $user['name'] = C('default_username');
        if(!isset($_GET['period'])) $_GET['period'] = 1;
        //获取|当前用户的交易记录
        if(is_search())
        {
            //period = 0  说明有查询时间
            //=1   1个月内
            //=2   1年内
            //=3  1年以上
            if($_GET['period'] == 1){
                $_GET['s_created_at'] = get_last_month_date();
                $_GET['e_created_at'] = get_now();
            }elseif ($_GET['period'] == 2){
                $_GET['s_created_at'] = get_last_year_date();
                $_GET['e_created_at'] = get_now();
            }elseif ($_GET['period'] == 3){
                //1年以上 即所有数据
            }else{
                //其他数字，则按照0来计算
            }
        }else{
            //默认查询一个月内的数据
            $_GET['s_created_at'] = get_last_month_date();
            $_GET['e_created_at'] = get_tomorrow();
        }
        $field_list =  [
            's_created_at,e_created_at|between',
        ];

        
        $where       =  trans_form_to_where($field_list);
        $where['status']    =  1;
        $account_log_model  =  Model('hc_user_account_log');
        $log_list           =  $account_log_model->getLogListByUser($user,$where);
        $page               =  $account_log_model->showPage();

        //是否需要导出
        if(is_export())
        {
            import('libraries.excel');
            $excel_obj = new Excel();
            $excel_data = array();
            //设置样式
            $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
            //header
            $excel_data[0][0] = array('styleid'=>'s_title','data'=>'发生时间');
            $excel_data[0][1] = array('styleid'=>'s_title','data'=>'项目');
            $excel_data[0][2] = array('styleid'=>'s_title','data'=>'说明	');
            $excel_data[0][3] = array('styleid'=>'s_title','data'=>'收支金额');
            $excel_data[0][4] = array('styleid'=>'s_title','data'=>'可用余额');
            //data
            foreach ($log_list as $k=>$v){
                $excel_data[$k+1][0] = array('data'=>$v['created_at']);
                $excel_data[$k+1][1] = array('data'=>$v['item']);
                $excel_data[$k+1][2] = array('data'=>$v['remark']);
                $excel_data[$k+1][3] = array('data'=>$v['money_type'].'￥'.$v['money']);
                $excel_data[$k+1][4] = array('data'=>'￥'.$v['credit_avaliable']);
            }
            $excel_data = $excel_obj->charset($excel_data,CHARSET);
            $excel_obj->addArray($excel_data);
            $excel_obj->addWorksheet($excel_obj->charset('可用余额',CHARSET));
            $p = $_GET['curpage']? $_GET['curpage'] :1;
            $excel_obj->generateXML($excel_obj->charset('客户财务-可用余额-phone('.$user['phone'].')-P'.$p.'-',CHARSET).date('Y-m-d-H',time()));
            exit();
        }else {
            Tpl::output('user', $user);
            Tpl::output('list', $log_list);
            Tpl::output('page', $page);
            Tpl::showpage('finance.a_deposit_detail');
        }
    }

    /**
     * 提现额度
     * @author wangjiang
     */
    public function with_draw_limitOp()
    {
        if(!isset($_GET['uid']) || !is_numeric($_GET['uid'])){
            showMessage('uid参数不正确');
        }

        //获取用户信息
        $user_model         =  Model('user');
        $user               =  $user_model->getUserById($_GET['uid'], 'id,name,phone,email,created_at,updated_at');
        $user_account       =  Model('hc_user_account')->where(['user_id'=>$_GET['uid']])->find();
        if(!$user_account)  showMessage('用户账户不存在');
        if(!$user['name']) $user['name'] = C('default_username');

        //从消费记录表中，查询出消费记录
        $where         =  [];
        $recharge_model  =  Model('hc_user_recharge');
        $list   =  $recharge_model->get_all_withdraw_limit($user['id']);
        $page          =  $recharge_model->showPage();
        $user['total_left_with_draw_limit']   =  $user_account['avaliable_deposit'];

        Tpl::output('user',$user);
        Tpl::output('list',$list);
        Tpl::output('page',$page);
        Tpl::showpage('finance.with_draw_limit');
    }

    /**
     * 提现线路
     * @author wangjiang
     */
    public function with_draw_lineOp()
    {
        if(!isset($_GET['uid']) || !is_numeric($_GET['uid'])){
            showMessage('uid参数不正确');
        }

        //获取用户信息
        $user_model         =  Model('user');
        $user               =  $user_model->getUserById($_GET['uid'], 'id,name,phone,email,created_at,updated_at');
        if(!$user['name'])   $user['name'] = C('default_username');

        //获取用户提现线路
        $account_withdraw_line_model  =  Model('hc_user_withdraw_line');
        $list                         =  $account_withdraw_line_model->getListByUser($user);
        $page                         =  $account_withdraw_line_model->showPage();
        Tpl::output('user',$user);
        Tpl::output('list',$list);
        Tpl::output('page',$page);
        Tpl::showpage('finance.with_draw_line');
    }

    /**
     * 提现线路操作日志
     * @author wangjiang
     */
    public function withdraw_line_operationOp(){
        if(!isset($_GET['uid']) || !is_numeric($_GET['uid'])){
            showMessage('uid参数不正确');
        }

        //获取用户信息
        $user_model         =  Model('user');
        $user               =  $user_model->getUserById($_GET['uid'], 'id,name,phone,email,created_at,updated_at');
        if(!$user)  showMessage('该用户不存在');

        $account_withdraw_line_model  = Model('hc_user_withdraw_line');
        $withdraw_line_list           =  $account_withdraw_line_model->getAll(['user_id'=>$user['id']]);

        //默认显示第一条
//        选中的记录
        $withdraw_line = [];
        $uwl_id   = 0;
        if(isset($_GET['uwl_id'])){
            $uwl_id        =  $_GET['uwl_id'];
            $withdraw_line =  Model('hc_user_withdraw_line')->getByWhere(['uwl_id' => $uwl_id]);
        }else{
            if(isset($withdraw_line_list[0])){
                $withdraw_line = $withdraw_line_list[0];
                $uwl_id   =  $withdraw_line_list[0]['uwl_id'];
                //查询该条记录的操作日志
            }
        }

        $withdraw_line['user'] = $user;

        //查询操作日志
        $log_list = [];
        if($uwl_id > 0){
            $type         =  C('operation_type')['withdraw_line'];
            $log_model    =  Model('hc_admin_operation');
            $where        =  [
                    'type'               =>  $type,
                    'related'  =>    'hc_user_withdraw_line|'.$withdraw_line['uwl_id'],
            ];
            $log_list     =  $log_model->getList($where);
        }

        Tpl::output('current_withdraw_line',$withdraw_line);
        Tpl::output('withdraw_line_list',$withdraw_line_list);
        Tpl::output('log_list',$log_list);

        Tpl::showpage('finance.with_draw_line_operation');
    }

    /**
     * 提现线路修改
     * @author wangjiang
     */
    public function withdraw_line_editOp()
    {
        $request = getPayload();
        if(isset($request['in_ajax'])) {
            if(!$request['uwl_id']){
                json_error("线路id(uwl_id)参数错误！");
            }
            $model = Model('hc_user_withdraw_line');

            $where = ['uwl_id'=>$request['uwl_id']];
            $withdraw_line = $model->where($where)->find();
            if(!$withdraw_line) json_error('该提现路线不存在');
            //获取银行卡信息
            $bank  = $model->table('user_bank')->where(['id'=>$withdraw_line['bank_id']])->find();
            if($bank)
            {
                $withdraw_line['bank']  = $bank;
            }

            //提现线路字段解析
            $bank_full_name  = $request['bank_full_address'];
            $bank_name_array = parse_address($bank_full_name);

            $update    = [
                'account_code'  => $request['bank_code'],
                'account_name'  => $request['bank_address'],
                'son'           =>  [ //子表更新
                    'user_bank'   =>[
                        'where'       => ['id'=>$withdraw_line['bank_id']],
                        'update'      => [
                            'bank_code'           => $request['bank_code'],
                            'bank_register_name'  => $request['bank_register_name'],
                            'bank_address'        => $request['bank_address'],
                            'province'            => $bank_name_array['province'],
                            'city'                => $bank_name_array['city'],
                            'district'            => $bank_name_array['district'],
                        ],
                        'old_data'    =>$bank,
                    ]
                ]
            ];
            $operation = [
                'user_id' => $this->admin_info['id'],
                'user_name' => $this->admin_info['name'],
                'related'   =>'hc_user_withdraw_line|'.$withdraw_line['uwl_id'],
                'remark'  => $request['remark'] ? $request['remark'] : '',
                'operation'=>'更改银行卡号信息',
            ];
            $ret = $model->table('hc_user_withdraw_line')->update_with_record($where,$update,$withdraw_line,$operation);

            json(['msg' =>'跟新成功','code' => 200 ,'ret'=>$ret]);
        }else{
            if(!isset($_GET['uwl_id']) || !is_numeric($_GET['uwl_id'])){
                showMessage('uwl_id');
            }
            $uwl_id = $_GET['uwl_id'];
            $model  = Model('hc_user_withdraw_line');
            $uwl    = $model->find($uwl_id);
            if($uwl)
            {
                $bank  = $model->table('user_bank')->where(['id'=>$uwl['bank_id']])->find();
                if($bank)
                {
                    $uwl['bank_full_address'] =  $bank['province'].$bank['city'].
                        $bank['district'];
                    $uwl['bank'] = $bank;
                }
            }
            Tpl::output('data',$uwl);

            Tpl::showpage('ajax_withdraw_line_edit');
        }
    }

    /**
     * ajax post  修改路线状态
     * @author wangjiang
     */
    public function ajax_set_statusOp()
    {
        $request_data = getPayload();
        if(isset($request_data['in_ajax'])){
            //获取路线信息
            //备注内容不得为空
            if(trim($request_data['remark']) == ""){
                json_error("备注内容不得为空！");
            }
            if(!$request_data['uwl_id']){
                json_error("线路id(uwl_id)参数错误！");
            }
            $model = Model('hc_user_withdraw_line');

            $where = ['uwl_id'=>$request_data['uwl_id']];
            $withdraw_line = $model->where($where)->find();
            if(!$withdraw_line) json_error('该提现路线不存在');

            //跟新
            $new_status = 1;//默认状态为1
            //如果是设置失效
            if($request_data['status'] == 0)
            {
                $new_status   = $request_data['status'];
            }else{
//                如果是恢复有效
                //获取这条路线记录的最新一次操作记录
                $operation_model    =    Model('hc_admin_operation');
                $map      =   [
                    'table_name'  => 'hc_user_withdraw_line',
                    'related_id'  => $request_data['uwl_id'],
                    'field_name'  => 'status',
                ];
                $last_log           =    $operation_model->findLastLogDetail($map);
                if($last_log){
                    $new_status = $last_log['old_val'];
                }
            }

            $update    = ['status'=>$new_status];
            $operation = [
                'user_id' => $this->admin_info['id'],
                'user_name' => $this->admin_info['name'],
                'remark'  => $request_data['remark'],
                'related' => 'hc_user_withdraw_line|'.$request_data['uwl_id'],
                'operation'=>'提现线路状态变更,由 '.show_withdraw_line_status($withdraw_line['status'],$withdraw_line['line_type']).
                    ' 改为 '.show_withdraw_line_status($update['status'],$withdraw_line['line_type']),
            ];
            $ret = $model->update_with_record($where,$update,$withdraw_line,$operation);


            json(['msg' =>'跟新成功','code' => 200 ,'ret'=>$ret]);
        }else{
            Tpl::output('user_id',$_GET['uid']);
            Tpl::output('uwl_id',$_GET['uwl_id']);
            Tpl::output('status',$_GET['status']);
            Tpl::showpage('ajax_status_form');
        }
    }

    /**
     * 提现额度页面，已提现额度明细弹窗
     * @author  wangjiang
     */
    public function ajax_show_withdrawsOp()
    {
        $cid           =  $_GET['cid'];
        $consume_model =  Model('hc_user_consume');
        $consume       =  $consume_model->getby_cid($cid);

        //一条消费记录对应一条
        $consume['withdraw'] = $consume_model->table('hc_user_withdraw')->getby_ur_id($consume['ur_id']);
        Tpl::output('consume',$consume);
        Tpl::showpage('ajax_show_withdraws');
    }


    /**
     * ajax post  删除线路
     * @author wangjiang
     */
    public function withdraw_line_delOp()
    {
        $request_data = getPayload();
        if(isset($request_data['in_ajax'])){
            //备注内容不得为空
            if(trim($request_data['remark']) == ""){
                json_error("备注内容不得为空！");
            }
            //获取路线信息
            if(!$request_data['uwl_id']){
                json_error("线路id(uwl_id)参数错误！");
            }
            $model = Model('hc_user_withdraw_line');

            $where = ['uwl_id'=>$request_data['uwl_id']];
            $withdraw_line = $model->where($where)->find();
            if(!$withdraw_line) json_error('该提现路线不存在');

            //查看该线路是否是失效状态，如果非失效状态不可删除
            if($withdraw_line['status'] != 0) json_error('该提现路线状态正常，不可删除');

            $update    = ['is_del'=>1];
            $operation = [
                'user_id' => $this->admin_info['id'] ,
                'user_name' => $this->admin_info['name'] ,
                'remark'  => $request_data['remark'] ,
                'operation'=>'删除uwl_id='.$withdraw_line['uwl_id'].'的提现线路' ,
            ];
            $ret = $model->update_with_record($where,$update,$withdraw_line,$operation);


            json(['msg' =>'删除成功','code' => 200 ,'ret'=>$ret]);
        }else{
            Tpl::output('uwl_id',$_GET['uwl_id']);
            Tpl::showpage('ajax_withdraw_line_del');
        }
    }


    #####这里开始是转入-银行转账/线上转账

    /**
     * 转入-银行转账/线上转账
     * @author wangjiang
     */
    public function recharge_indexOp(){
        $recharge_method = self::RECHARGE_BANK_TYPE; //默认查询银行转账
        if(isset($_GET['recharge_method']) && is_numeric($_GET['recharge_method']))
        {
            $recharge_method = $_GET['recharge_method'];
        }

        $recharge_model =   Model('hc_user_recharge');

        //是否有查询条件
        $where   = [];
        if(is_search())
        {
            $field_list =  [
                'ur_id|like|hc_user_recharge',
                'user_id|like|hc_user_recharge',
                'phone|like|users',
                'bank_account|like|hc_user_recharge',
                'user_bank_name|like|hc_user_recharge',
                'recharge_type|eq|hc_user_recharge',
                'use_type|eq|hc_user_recharge',
                's_money,e_money|between|hc_user_recharge',
                's_recharge_money,e_recharge_money|between|hc_user_recharge',
                'trade_no|like|hc_user_recharge',
                'accounting_voucher|like|hc_user_recharge',
                'accounting_voucher|like|hc_user_recharge',
                'status|eq|hc_user_recharge',
                's_created_at,e_created_at|between|hc_user_recharge',
                's_recharge_confirm_at,e_recharge_confirm_at|between|hc_user_recharge',
            ];
            $where       =  trans_form_to_where($field_list);
        }
        if(!$where['hc_user_recharge.recharge_type']){
            if ($recharge_method ==2 ){
                if(!isset($_GET['status'])){
                    $where['hc_user_recharge.status'] = 0;
                }
                $where['hc_user_recharge.recharge_type']   = $recharge_method;
            }
            else
                $where['hc_user_recharge.recharge_type']   = ['in','1,3,4'];
        }
        //默认状态待入账
        $list           =   $recharge_model->getRechargeList($where);
        $page           =   $recharge_model->showPage();

        //是否需要导出
        if(is_export())
        {
            import('libraries.excel');
            $excel_obj = new Excel();
            $excel_data = array();
            //设置样式
            $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
            //header
            if($recharge_method ==2){  //银行转账
                $excel_data[0][0] = array('styleid'=>'s_title','data'=>'转入编号');
                $excel_data[0][1] = array('styleid'=>'s_title','data'=>'客户手机');
                $excel_data[0][2] = array('styleid'=>'s_title','data'=>'提交金额	');
                $excel_data[0][3] = array('styleid'=>'s_title','data'=>'汇款银行');
                $excel_data[0][4] = array('styleid'=>'s_title','data'=>'汇款人账号');
                $excel_data[0][5] = array('styleid'=>'s_title','data'=>'汇款人户名');
                $excel_data[0][6] = array('styleid'=>'s_title','data'=>'转入用途');
                $excel_data[0][7] = array('styleid'=>'s_title','data'=>'入账金额');
                $excel_data[0][8] = array('styleid'=>'s_title','data'=>'银行凭证号');
                $excel_data[0][9] = array('styleid'=>'s_title','data'=>'状态');
                //data
                foreach ($list as $k=>$v){
                    $excel_data[$k+1][0] = array('data'=>$v['ur_id']);
                    $excel_data[$k+1][1] = array('data'=>$v['phone']);
                    $excel_data[$k+1][2] = array('data'=>$v['money']);
                    $excel_data[$k+1][3] = array('data'=>$v['bank_name']);
                    $excel_data[$k+1][4] = array('data'=>$v['bank_account']);
                    $excel_data[$k+1][5] = array('data'=>$v['user_bank_name']);
                    if($v['use_type']>0){
                        $excel_data[$k+1][6] = array('data'=>show_recharge_use_type($v['use_type']."(订单号：{$v['order_id']})"));
                    } else{
                        $excel_data[$k+1][6] = array('data'=>show_recharge_use_type($v['use_type']));
                    }
                    $excel_data[$k+1][7] = array('data'=>$v['recharge_money']);
                    $excel_data[$k+1][8] = array('data'=>$v['trade_no']); //TODO  平台冻结暂时都设为0
                    $excel_data[$k+1][9] = array('data'=>show_recharge_status($v['status']));
                }
            }else{ //线上支付
                $excel_data[0][0] = array('styleid'=>'s_title','data'=>'转入编号');
                $excel_data[0][1] = array('styleid'=>'s_title','data'=>'客户会员号');
                $excel_data[0][2] = array('styleid'=>'s_title','data'=>'提交金额	');
                $excel_data[0][3] = array('styleid'=>'s_title','data'=>'支付方式');
                $excel_data[0][4] = array('styleid'=>'s_title','data'=>'转入方账户');
                $excel_data[0][5] = array('styleid'=>'s_title','data'=>'转入用途');
                $excel_data[0][6] = array('styleid'=>'s_title','data'=>'入账金额');
                $excel_data[0][7] = array('styleid'=>'s_title','data'=>'入账流水号');
                $excel_data[0][8] = array('styleid'=>'s_title','data'=>'状态');
                //data
                foreach ($list as $k=>$v){
                    $excel_data[$k+1][0] = array('data'=>$v['ur_id']);
                    $excel_data[$k+1][1] = array('data'=>$v['user_id']);
                    $excel_data[$k+1][2] = array('data'=>$v['money']);
                    $excel_data[$k+1][3] = array('data'=>show_recharge_type($v['recharge_type']));
                    $excel_data[$k+1][4] = array('data'=>$v['alipay_user_name']);
                    if($v['use_type']>0){
                        $excel_data[$k+1][5] = array('data'=>show_recharge_use_type($v['use_type']."(订单号：{$v['order_id']})"));
                    } else{
                        $excel_data[$k+1][5] = array('data'=>show_recharge_use_type($v['use_type']));
                    }
                    $excel_data[$k+1][6] = array('data'=>$v['recharge_money']);
                    $excel_data[$k+1][7] = array('data'=>$v['trade_no']);
                    $excel_data[$k+1][8] = array('data'=>show_recharge_status($v['status']));
                }
            }

            $excel_data = $excel_obj->charset($excel_data,CHARSET);
            $excel_obj->addArray($excel_data);
            $title = '转入-';
            if($recharge_method ==2)
            {
                $title .= '银行转账';
            }else{
                $title .= '线上支付';
            }
            $excel_obj->addWorksheet($excel_obj->charset('客户财务-'.$title,CHARSET));
            $p = $_GET['curpage'] ? $_GET['curpage'] : 1;
            $excel_obj->generateXML($excel_obj->charset('客户财务-'.$title.'-P'.$p.'-',CHARSET).date('Y-m-d-H',time()));
            exit();
        }else{
            Tpl::output('list', $list);
            Tpl::output('recharge_method', $recharge_method);
            Tpl::output('page', $page);

            Tpl::showpage('finance.recharge_index');
        }
    }


    /**
     * 支付详情
     * @author wangjiang
     */
    public function recharge_detailOp()
    {
        //测试数据 recharge表 id 31充值 33 诚意金
        $recharge_method = self::RECHARGE_BANK_TYPE; //默认查询银行转账
        if(isset($_GET['recharge_method']) && is_numeric($_GET['recharge_method']))
        {
            $recharge_method = $_GET['recharge_method'];
        }


        //是否表单提交
        $request      =  getPayload();
        if(isset($request['in_ajax'])) {
            //TODO  订单入账金额  订单处理逻辑未定
            $ur_id             = $request['ur_id'];
            $recharge_model    = Model('hc_user_recharge');
            $recharge          = $recharge_model->getById($ur_id);

            $ret  =  '';  //处理结果

            $operation = [
                'user_id'    =>  $this->admin_info['id'],
                'user_name'  =>  $this->admin_info['name'],
                'related'   =>   'hc_user_recharge|'.$ur_id, //关联数据
                'step'   =>    $recharge['status'], //操作步骤
                'type'       =>  self::TRANSFER_BANK_OPERATION_TYPE,  //12
            ];

            //已入账
            if($request['recharge_operation_yes']) {
                if (!$request['save']) //如果是保存按钮
                {
                    $new_status = (int)$recharge['status'] + 1;
                    $update['status'] = $new_status; //步骤+1
                    $operation['operation'] = "将recharge表的ur_id为" . $recharge['ur_id'] . '的充值记录由' .
                        show_recharge_status($recharge['status']) . '转为' . show_recharge_status($new_status);
                }else{
                    $operation['operation'] = "保存";
                    $operation['type'] = "10";//默认操作
                }

                if ($recharge['status'] < 2) {//0,1状态还没入账
                    $update['recharge_money'] = $request['recharge_money'];
                    $update['transfer_to_order'] = $request['transfer_to_order'];
                    $update['transfer_to_account'] = $request['transfer_to_account'];
                    $update['recharge_confirm_at'] = $request['recharge_confirm_at'];
                } elseif ($recharge['status'] == 2) { //2  入账补充
                    //判断信息是否输入
                    if (!$request['save']){//如果不是保存按钮
                        if (trim($request['trade_no']) == '' || trim($request['accounting_voucher']) == '') {
                            json_error('请将 补充入账信息 填写完整');
                        }
                    }
                    $update['transfer_bank_name'] = $request['transfer_bank_name'];
                    $update['trade_no'] = $request['trade_no'];
                    $update['accounting_voucher'] = $request['accounting_voucher'];
                }
                $where = ['ur_id' => $recharge['ur_id']];
                $recharge['id'] = $recharge['ur_id']; //解决主键找不到的问题
                $ret = $recharge_model->table('hc_user_recharge')->update_with_record($where, $update, $recharge, $operation);
                if($ret){
                    //1. 如果transfer_to_account>0 则要对用户账户的余额进行增加
                    //2. transfer_to_order 则需要对订单的处理 TODO
                    //1. 对余额进行操作
                    if(isset($new_status) && $new_status == 2){
                        $user_account  =  Model('hc_user_account')->where('user_id='.$recharge['user_id'])->find();
                        if($user_account){
                            $new_recharge  = $recharge_model->table('hc_user_recharge')->where(['ur_id'=>$recharge['ur_id']])->find();
                            if( $new_recharge['transfer_to_account'] > 0)
                            {
                                $update_account = [
                                    'total_deposit' =>  ['exp','total_deposit+'.$new_recharge['transfer_to_account'] ],
                                    'avaliable_deposit' =>  ['exp','avaliable_deposit+'.$new_recharge['transfer_to_account'] ],
                                ];
                                $map_account = [
                                    'user_id' => $new_recharge['user_id'],
                                    'status' => ['neq',2]     // 状态2账户禁用
                                ];
                                //更改用户总额，可用余额
                                $recharge_model->table('hc_user_account')->where($map_account)->update($update_account);

                                $user_account_log = [
                                    'status' => 1,
                                    'item'   => "转入 ( 确认到账 )",
                                    'money'  => $request['recharge_money'],
                                    'credit_avaliable' =>   (float)$user_account['avaliable_deposit'] +   (float)$new_recharge['transfer_to_account'],
                                    'created_at'  =>  get_now2(),
                                ];
                                $map_log = [
                                    'item_id'=>$recharge['ur_id'],
                                     'user_id' => $recharge['user_id']
                                    ];
                                Model()->table('hc_user_account_log')->where($map_log)->update($user_account_log);

                            }

                            

                            $account_log  =  [
                                    'from_where'   => 1 ,//客户 
                                    'from_remark'  => '客户充值',
                                    'from_user_id' =>  $recharge['user_id'],
                                    'to_where'     => 3, //平台
                                    'to_remark'    => '客户充值', 
                                    'trade_no'     =>  $recharge['trade_no'],
                                    'money'        =>  $recharge['money'],
                                    'type'         =>  10,//转入客户
                                    'method_type'  =>  10,//客户充值
                                    'related_id'   =>  $recharge['ur_id'],
                                    'flow_type'    =>  1,//收入
                                    'created_at'   =>  get_now2(),
                            ];
                            Model('hc_account_log')->insert($account_log);
                        } 
                    }
                    
                    json(['code'=>200,'msg'=>'处理成功']);
                }else{
                    json_error('处理失败');
                }

            }else{// 未入账
                $operation['operation']  = "将recharge表的ur_id为".$recharge['ur_id'].'的充值记录转为未入账状态';
                $new_status = $update['status']        = 4;
                $update['recharge_money'] = 0;
                $update['recharge_confirm_at'] = get_now2();
                $where  = ['ur_id'=>$recharge['ur_id']];
                $recharge['id']   = $recharge['ur_id']; //解决主键找不到的问题
                $ret    = $recharge_model->table('hc_user_recharge')->update_with_record($where, $update, $recharge, $operation);
                if($ret){
                     $user_account  =  Model('hc_user_account')->where('user_id='.$recharge['user_id'])->find();
                     //修改log记录
                    if(isset($new_status) && $new_status == 4)
                    {
                        $user_account_log = [
                            'status' => 2,
                            'item'   => "无此款项",
                            'credit_avaliable' =>   (float)$user_account['avaliable_deposit'],
                            'created_at'  =>  get_now2(),
                        ];

                        $map_log = [
                            'item_id'=>$recharge['ur_id'],
                            'user_id' => $recharge['user_id']
                        ];
                        Model()->table('hc_user_account_log')->where($map_log)->update($user_account_log);
                    }
                    json(['code'=>200,'msg'=>'处理成功']);
                }else{
                    json_error('处理失败');
                }
            }

            if($ret){
                json(['code'=>200,'msg'=>'处理成功']);
            }

            exit();
        }
        //ajax 处理结束

        if(!isset($_GET['ur_id']) || !is_numeric($_GET['ur_id']))
        {
            showMessage('ur_id参数缺失！');
        }


        $ur_id             = $_GET['ur_id'];
        $recharge_model    = Model('hc_user_recharge');
        $recharge          = $recharge_model->getById($ur_id);

        //获取附件列表
        $comment_model     = Model('hc_admin_operation');
        $comment_list      = $comment_model->getCommentsByRecharge($recharge);
        //历史操作记录
        $log_map=[
            'type'     =>  self::TRANSFER_BANK_OPERATION_TYPE,
            'related'  =>  'hc_user_recharge|'.$ur_id,
        ];
        $log_list          = $comment_model->table('hc_admin_operation')->where($log_map)->order('step')->select();

        $model = Model();
        //客户绑定银行卡信息
        $bank_list         =  $model->table('user_bank')->where(['user_id'=>$recharge['user_id']])->select();

        Tpl::output('recharge', $recharge);
        Tpl::output('bank_list', $bank_list);
        Tpl::output('log_list', $log_list);
        Tpl::output('comment_list', $comment_list);
        Tpl::output('recharge_method', $recharge_method);
        Tpl::showpage('finance.recharge_detail'.$recharge_method);
    }


    /**
     * 添加附件
     */
    public function ajax_add_commentOp()
    {
        $request = getPayload();
        if(isset($request['in_ajax'])) {
            if (!$request['ur_id']) {
                json_error("ur_id参数错误！");
            }
            if (!$request['remark']) {
                json_error("备注内容不能为空！");
            }

            $model = Model('hc_admin_operation_comment');
            $where  = [
                'related'  => 'hc_user_recharge|'.$request['ur_id'],
            ];
            $comment_count = $model->where($where)->count();

            $now   = get_now2();
            $comment = [
                'user_id'           => $this->admin_info['id'],
                'user_name'         => $this->admin_info['name'],
                'file_name'         => $request['file_name'],
                'file_path'         => $request['file_path'],
                'file_description'  => '', //文件描述暂时为空
                'related'           => 'hc_user_recharge|'.$request['ur_id'],
                'step'              =>  $request['status'],
                'order_num'             =>  (int)$comment_count+1,
                'remark'            => $request['remark'],
                'created_at'        => $now ,
                'updated_at'        => $now ,
            ];
            $id = $model->insert($comment);
            if($id)  json(['msg' =>'添加成功','code' => 200 ,'ret'=>$id]);

        }else {
            if (!isset($_GET['ur_id']) || !is_numeric($_GET['ur_id'])) {
                showMessage('ur_id参数不正确');
            }
            $ur_id             = $_GET['ur_id'];
            $recharge_model    = Model('hc_user_recharge');
            $recharge          = $recharge_model->getById($ur_id);

            Tpl::output('ur_id', $_GET['ur_id']);
            Tpl::output('recharge', $recharge);

            Tpl::showpage('ajax_add_comment');
        }
    }

    /**
     * 取消上传
     */
    public function ajax_cancel_fileOp()
    {
        $request = getPayload();
        if(isset($request['file_url'])) {
                $model  = Model('hc_admin_operation_comment');
                $comment = $model->where(['file_path'=>['like','%'.$request['file_url'].'%']])->count();
                if($comment){
                    json_error('已经备注完成,上传失败!');
                }else{
                    @unlink(UPLOAD_SITE_URL.$request['file_url']);
                    json(['code'=>200,'msg'=>'删除成功']);
                }
        } else{
            json_error("url参数缺失");
        }
    }

    /**
     * 通用添加备注附件方法
     */
    public function ajax_common_add_commentOp()
    {
        $request = getPayload();
        if(isset($request['in_ajax'])) {
            if (!$request['id']) {
                json_error("id参数错误！");
            }
            if (!$request['remark']) {
                json_error("备注内容不能为空！");
            }

            $table            =  get_tableinfo_from_operation_type($request['operation_type']);
            if(strpos($table,'|'))
                $table_info = explode('|',$table);
            else
                $table_info = [$table,'id'];


            $model = Model('hc_admin_operation_comment');
            $now   = get_now2();
            $comment = [
                'user_id'           => $this->admin_info['id'],
                'user_name'         => $this->admin_info['name'],
                'file_name'         => $request['file_name'],
                'file_path'         => $request['file_path'],
                'file_description'  => '', //文件描述暂时为空
                'related'           => $table_info[0].'|'.$request['id'],
                'step'              =>  $request['status'],
                'remark'            =>  $request['remark'],
                'created_at'        =>  $now ,
                'updated_at'        =>  $now ,
            ];

            $id = $model->insert($comment);
            if($id)  json(['msg' =>'添加成功','code' => 200 ,'ret'=>$id]);

        }else {
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                showMessage('id参数不正确');
            }
            if (!isset($_GET['operation_type']) || !is_numeric($_GET['operation_type'])) {
                showMessage('operation_type参数不正确');
            }


            $operation_type   = $_GET['operation_type'];

            $table            =  get_tableinfo_from_operation_type($operation_type);
            if(strpos($table,'|'))
                $table_info = explode('|',$table);
            else
                $table_info = [$table,'id'];

            $id              = $_GET['id'];
            $common_model    = Model($table_info[0]);
            $common_model->set_pk($table_info[1]);
            $data            = $common_model->find($id);
            if (isset($_GET['status'])) {
                $data['status']    = $_GET['status'];
            }
            Tpl::output('id', $id);
            Tpl::output('data', $data);
            Tpl::output('operation_type', $operation_type);

            Tpl::showpage('ajax_common_add_comment');
        }
    }

    /**
     * 删除附件 (通用)
     */
    public function ajax_del_commentOp()
    {
         //当前登录人可删除
        $admin_info = $this->admin_info;
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            json_error('id参数不正确');
        }

        $id             = $_GET['id'];
        $operation_model    = Model('hc_admin_operation');
        $comment           = $operation_model->getCommentByCommentId($id);

        if(!$comment)
        {
            json_error('该条评论不存在，或已删除');
        }else{
            //只能删除自己创建的
            if($admin_info['id'] != $comment['user_id'])
            {
                json_error('您不是该条信息的创建人，不能删除');
            }
            //只可删除本步骤添加的备注，不可删除之前步骤的备注。“删除”按钮在本步骤添加后出现
            $table_name = explode('|',$comment['related'])[0];
            //转入，在核实入账时，可以删除待入账的数据
            if($_GET['current_status'] == 1 && $comment['step'] == "0" && $table_name == "hc_user_recharge"){
//                执行、删除  可以执行后面的删除操作
            }elseif (!isset($_GET['current_status']) || (isset($_GET['current_status']) && ($comment['step'] != $_GET['current_status']))) {
                        json_error('非当前步骤备注，不能删除');
            }

            $ret  = $operation_model->delCommentByComment($comment);
            if($ret) json(['code'=>200,'msg'=>'删除成功']);
            else json_error('删除失败');
        }


    }


    #####这里开始是  提现.

    /**
     * 提现首页
     */
    public function withdraw_indexOp()
    {
        $common_model = Model('hc_user_withdraw');
        //是否有查询条件
        $where   = [];
        if(is_search())
        {
            $field_list =  [
                'uw_id|like|hc_user_withdraw',
                'user_id|like|hc_user_withdraw',
                'phone|like|users',
                'trade_no|like|hc_user_recharge',
                'accounting_voucher|like|hc_user_recharge',
                'line_type|eq|hc_user_withdraw_line',
                'status|eq|hc_user_withdraw',
//                'bank_account|like|hc_user_withdraw',
//                'recharge_type|eq|hc_user_recharge',
                's_recharge_money,e_recharge_money|between|hc_user_recharge',
                's_created_at,e_created_at|between|hc_user_withdraw',
                's_transfer_at,e_transfer_at|between|hc_user_withdraw',
                's_updated_at,e_updated_at|between|hc_user_withdraw',
                'step|eq',
                'user_name|eq',
            ];
            $where       =  trans_form_to_where($field_list);
            //不对应表字段
            //1. recharge_method
            //2. bank_account
            //3. 办理步骤加操作人

//            1.recharge_method
            if($_GET['recharge_method'] !== ''){
                $recharge_type = $_GET['recharge_method'] == 1?['in','1,3,4']:['eq','2'];
                $where['hc_user_recharge.recharge_type'] = $recharge_type;
            }
//            2.bank_account
            if($_GET['bank_account'] !== ''){
                $where['hc_user_recharge.bank_account|hc_user_recharge.alipay_user_name'] = array('like','%'.$_GET['bank_account'].'%');
            }
        }
        $list  = $common_model->getDetailList($where);
        //是否需要导出
        if(is_export())
        {
            import('libraries.excel');
            $excel_obj = new Excel();
            $excel_data = array();
            //设置样式
            $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
            //header
            $excel_data[0][0] = array('styleid'=>'s_title','data'=>'提现编号');
            $excel_data[0][1] = array('styleid'=>'s_title','data'=>'申请时间');
            $excel_data[0][2] = array('styleid'=>'s_title','data'=>'客户会员号	');
            $excel_data[0][3] = array('styleid'=>'s_title','data'=>'客户手机号');
            $excel_data[0][4] = array('styleid'=>'s_title','data'=>'提现付款金额');
            $excel_data[0][5] = array('styleid'=>'s_title','data'=>'提现方式');
            $excel_data[0][6] = array('styleid'=>'s_title','data'=>'收款方');
            $excel_data[0][7] = array('styleid'=>'s_title','data'=>'银行凭证号/转账流水号');
            $excel_data[0][8] = array('styleid'=>'s_title','data'=>'状态更新时间');
            $excel_data[0][9] = array('styleid'=>'s_title','data'=>'状态');
            //data
            foreach ($list as $k=>$v){
                $excel_data[$k+1][0] = array('data'=>$v['uw_id']);
                $excel_data[$k+1][1] = array('data'=>$v['created_at']);
                $excel_data[$k+1][2] = array('data'=>$v['user_id']);
                $excel_data[$k+1][3] = array('data'=>$v['u_phone']);
                $excel_data[$k+1][4] = array('data'=>$v['ur_recharge_money']);
                $excel_data[$k+1][5] = array('data'=>show_recharge_method($v['ur_recharge_type']));
                if($v['ur_recharge_type']!=2){
                    $excel_data[$k+1][6] = array('data'=> show_recharge_type($v['ur_recharge_type']).'/'.$v['ur_alipay_user_name']);
                } else{
                    $excel_data[$k+1][6] = array('data'=>$v['ur_bank_account'].'/'.$v['ur_user_bank_name']);
                }
                $excel_data[$k+1][7] = array('data'=>$v['ur_trade_no']);
                $excel_data[$k+1][8] = array('data'=>$v['updated_at']); //TODO  平台冻结暂时都设为0
                $excel_data[$k+1][9] = array('data'=>show_withdraw_status($v['status']));
            }


            $excel_data = $excel_obj->charset($excel_data,CHARSET);
            $excel_obj->addArray($excel_data);
            $title = '提现';
            $excel_obj->addWorksheet($excel_obj->charset('客户财务-'.$title,CHARSET));
            $p = $_GET['curpage'] ? $_GET['curpage'] : 1;
            $excel_obj->generateXML($excel_obj->charset('客户财务-'.$title.'-P'.$p.'-',CHARSET).date('Y-m-d-H',time()));
            exit();
        }else {
            Tpl::output('list', $list);
            Tpl::output('page', $common_model->showPage());

            Tpl::showpage('finance.withdraw_index');
        }
    }

    /**
     * 提现详情
     */
    public function withdraw_detailOp()
    {
        //是否表单提交
        $request      =  getPayload();
        if(isset($request['in_ajax'])) {
            $uw_id             = $request['uw_id'];
            $withdraw_model    = Model('hc_user_withdraw');
            $withdraw          = $withdraw_model->where(['uw_id'=>$uw_id])->find();

            $operation = [
                'user_id'    =>  $this->admin_info['id'],
                'user_name'  =>  $this->admin_info['name'],
                'related'   =>   'hc_user_withdraw|'.$uw_id, //关联数据
                'step'   =>      $withdraw['status'], //操作步骤
                'type'       =>  self::WITHDRAW_OPERATION_TYPE,  //13
            ];


            //操作类型
            if ($request['reject']) //拒绝
            {
                $update['status']     = 4; //4未成功
                $update['reject_status']     = $request['reject_status']; //客户原因
                $operation['operation'] = "拒绝-客户原因";
                $operation['step'] = $request['step']?$request['step']:4;
                if(isset($request['remark']) && $request['remark'] != ''){
                     $operation['remark'] = $request['remark'];
                } else{
                     $operation['remark'] = '提现付款金额已重新转入可用余额';
                }
            }elseif($request['back'] ){//退回操作
                $operation['type']      = "10";//默认操作
                $new_status           = get_back_withdraw_status($withdraw['status']);
                $update['status']     = $new_status; //步骤+1
                $update['updated_at']     = get_now2(); //步骤更新，更新时间更新
                //重置接单人信息
                $update['operation_admin_id']     =  0;
                $update['operation_admin_name']     = '';

                $operation['operation'] = "将withdraw表的uw_id为" . $withdraw['uw_id'] . '的充值记录由' .
                    show_withdraw_status($withdraw['status']) . '转为' . show_withdraw_status($new_status);
            }else{  //下一步操作
                if(!$request['save']){
                    $new_status           = get_next_withdraw_status($withdraw['status']);
                    $update['status']     = $new_status; //步骤+1
                    $update['updated_at']     = get_now2(); //步骤更新，更新时间更新
                    $operation['operation'] = "将withdraw表的uw_id为" . $withdraw['uw_id'] . '的充值记录由' .
                        show_withdraw_status($withdraw['status']) . '转为' . show_withdraw_status($new_status);

                }else{
                    $operation['operation'] = "保存补充信息";
                    $operation['type']      = "10";//默认操作
                }
            }
            //状态判断
            if ($withdraw['status'] == 2) {//2 接单操作
                $update['operation_admin_id']    = $this->admin_info['id'];
                $update['operation_admin_name']  = $this->admin_info['name'];
                $update['operation_at']  = get_now2();
            } elseif ($withdraw['status'] == 3) { //3  转账操作
                $update['transfer_at']  = $request['transfer_at'];
            }elseif($withdraw['status'] == 5) { //5提交补充信息  转账操作
                $update['transfer_bank_name']  = $request['transfer_bank_name'];
                $update['transfer_trade_no']   = $request['transfer_trade_no'];
                $update['transfer_record_no']  = $request['transfer_record_no'];
                $update['transfer_voucher']    = $request['transfer_voucher'];
            }elseif(in_array($withdraw['status'] , [1,6]) &&  !$request['reject_status']) { //更正状态
                $update['transfer_at']  = $request['transfer_at'];
                $update['transfer_bank_name']  = $request['transfer_bank_name'];
                $update['transfer_trade_no']   = $request['transfer_trade_no'];
                $update['transfer_record_no']  = $request['transfer_record_no'];
                $update['transfer_voucher']    = $request['transfer_voucher'];
            }
            $where = ['uw_id' => $withdraw['uw_id']];
            $withdraw['id'] = $withdraw['uw_id']; //解决主键找不到的问题
            $ret = $withdraw_model->table('hc_user_withdraw')->update_with_record($where, $update, $withdraw, $operation);
            if($ret){
                //驳回
              
                if($update['status'] == 4 && $withdraw['money'] > 0)
                {
                    $update_account = [
                        'total_deposit' =>  ['exp','total_deposit+'.$withdraw['money'] ],
                        'avaliable_deposit' =>  ['exp','avaliable_deposit+'.$withdraw['money'] ],
                        'temp_deposit' =>  ['exp','temp_deposit-'.$withdraw['money'] ],
                    ];
                    $map_account = [
                        'user_id' => $withdraw['user_id'],
                        'status' => ['neq',2]     // 状态2账户禁用
                    ];
                    // //更改用户总额，可用余额
                    $withdraw_model->table('hc_user_account')->where($map_account)->update($update_account);
                    //生成一重新入账的记录
                    $user_account  = $withdraw_model->table('hc_user_account')->where($map_account)->find();
                    $log =  [
                        'status' =>1,
                        'user_id' => $withdraw['user_id'],
                        'item_id' => $withdraw['uw_id'],
                        'item'    => '重新转入',
                        'remark'  => '提现不成功('.$withdraw['uw_id'].')',
                        'money'   =>  $withdraw['money'],
                        'credit_avaliable' => (float)$user_account['avaliable_deposit'],
                        'type'   => 4,
                        'pay_type' =>  5,
                        'money_type'=> '+',
                    ];
                    $withdraw_model->table('hc_user_account_log')->insert($log);
                }

                if($update['status'] == 5) //通过了
                {
                    //审核通过，处理账户金额，并且记录log
                    $this->_handle_pass_withdraw($withdraw, $withdraw_model);
                }


                json(['code'=>200,'msg'=>'处理成功']);
            }else{
                json_error('处理失败');
            }

            exit();
        }
        //ajax 处理结束


        if(!isset($_GET['uw_id']) || !is_numeric($_GET['uw_id']))
        {
            showMessage('uw_id参数缺失！');
        }

        $uw_id             = $_GET['uw_id'];
        $withdraw_model     = Model('hc_user_withdraw');
        $withdraw          = $withdraw_model->table('hc_user_withdraw')->getDetailById($uw_id);
        if($withdraw['uwl_line_type'] == 2){
            $_GET['recharge_method'] = 2;
        }else{
            $_GET['recharge_method'] = 1;
        }
        //判断是否需要确定当前接单人
        if($withdraw['status'] == 3){
            $is_operator  = $withdraw['operation_admin_id'] == $this->admin_info['id'];
            Tpl::output('is_operator', $is_operator);
        }

        //获取附件列表
        $comment_model     = Model('hc_admin_operation');
        $comment_list      = $comment_model->getCommentsByWithdraw($withdraw);

        //历史操作记录
        $log_map=[
            'type'     =>  self::WITHDRAW_OPERATION_TYPE,
            'related'  =>  'hc_user_withdraw|'.$uw_id,
        ];
        $log_list          = $comment_model->table('hc_admin_operation')->where($log_map)->order('created_at')->select();
        $new_log_list      = [];
        if($log_list){
            foreach ($log_list as $log){
                $new_log_list[$log['step']] = $log;
            }
        }
        $model = Model();
        //客户绑定银行卡信息
        $bank_list         =  $model->table('user_bank')->where(['user_id'=>$withdraw['user_id']])->select();
        Tpl::output('withdraw', $withdraw);
        Tpl::output('bank_list', $bank_list);
        Tpl::output('log_list', $log_list);
        Tpl::output('step_log_list', $new_log_list);
        Tpl::output('comment_list', $comment_list);
        Tpl::output('operation_type', self::WITHDRAW_OPERATION_TYPE);
        if($_GET['reset']){
            Tpl::showpage('finance.withdraw_detail_reset');
        }else{
            if($withdraw['status'] <6){
                $status   =   $withdraw['status'];
            }else{
                $status   =  1;
            }
             Tpl::showpage('finance.withdraw_detail'.$status);
        }
    }

    //审核通过，处理账户金额，并且记录log
    private function _handle_pass_withdraw($withdraw, $withdraw_model)
    {

        try{
           $withdraw_model->beginTransaction();
            $map = [
                'user_id' => $withdraw['user_id'],
                'status' => ['neq',2]     // 状态2账户禁用
            ];
            $user_account = $withdraw_model->table('hc_user_account')->where($map)->find();
            //冻结资金不够扣了，说明帐对不上了
            if( $user_account['temp_deposit'] - (float)$withdraw['money'] <0 )
            {
                throw  new Exception('账户冻结余额不够扣了');
            }           
            ### 处理账户资金,用户资金表去掉手续费，冻结金额
            $update = [
                'temp_deposit' =>  ['exp','temp_deposit-'.$withdraw['money'] ],
            ];
           
            //更改用户总额，可用余额
            $ret1 = $withdraw_model->table('hc_user_account')->where($map)->update($update);

            ### 插入资金总表
            $account_log = [
                'from_where'   =>  3,  //1 客户，2售方，3平台
                'from_remark'  =>  '提现',
                'to_user_id'   =>  $withdraw['user_id'],
                'to_where'     =>  1,
                'to_remark'    =>  '获得提现',
                'money'        =>  (float)$withdraw['money']-(float)$withdraw['fee'],
                'type'         =>  20,
                'method_type'  =>  20,
                'related_id'   =>  $withdraw['uw_id'],
                'flow_type'    =>  2, //支出
                'created_at'   =>  get_now2()
            ];
            $ret2 = Model('hc_account_log')->insert($account_log);
            ###提现手续费
            $ret3 = $ret4 = 1;
            if((float)$withdraw['fee'] >0){  //如果有提现手续费
                //资金总表
                $account_log = [
                    'from_user_id' =>  $withdraw['user_id'],
                    'from_where'   =>  1,  //1 客户，2售方，3平台
                    'from_remark'  =>  '',
//                            'to_user_id'   =>  $withdraw['user_id'],
                    'to_where'     =>  3,
                    'to_remark'    =>  '提现手续费',
                    'money'        =>  $withdraw['fee'],
                    'type'         =>  20,
                    'method_type'  =>  20,
                    'related_id'   =>  $withdraw['uw_id'],
                    'flow_type'    =>  1, //收入
                    'created_at'   =>  get_now2()
                ];
                $ret3 = Model('hc_account_log')->insert($account_log);
                //用户资金表
                $user_account_log = [
                    'user_id'   =>  $withdraw['user_id'],
                    'item_id'   =>  $withdraw['uw_id'],
                    'item'      =>  '提现',
                    'remark'    =>  '提现手续费',
                    'money'     =>  $withdraw['fee'],
                    'type'      =>  $withdraw['type'],
                    'credit_avaliable' => $user_account['avaliable_deposit'],
                    'pay_type'  =>  2,
                    'status'    => 1,
                    'money_type'=> '-',
                    'created_at' => get_now2()
                ];

                $ret4 = Model('hc_user_account_log')->insert($user_account_log);
            }

            if($ret1 && $ret2 && $ret3 && $ret4)
            {
                $withdraw_model->commit();
                //查看用户该笔提现所在的log表中，总的提现记录是否完成。
                $ret = $withdraw_model->table('hc_user_withdraw')->where('ulog_id='.$withdraw['ulog_id'].' and status in (0,2,3) and uw_id<>'.$withdraw['uw_id'] )->count();
                if($ret < 1) {
                    $withdraw_model->table('hc_user_account_log')->where('ua_log_id='.$withdraw['ulog_id'])->update(['status'=>1,'remark'=>'已完成']);
                }

            }else{
                $withdraw_model->rollback();
            }
        }catch (Exception $e) {
            $withdraw_model->rollback();
            json_error($e->getMessage());
        }
    }

    #####这里开始是  特事审批
    /**
     * 特事审批首页
     */
    public function special_indexOp()
    {
        $special_model     =  Model('hc_user_special_application');

        //是否有查询条件
        $where   = [];
        if(is_search()) {
            $field_list = [
                'id|like|hc_user_special_application',
                'user_id|like|hc_user_special_application',
                'phone|like|users',
                'special_type|eq|hc_user_special_application',
                'dept_id|eq|admin',
                'apply_admin_name|like|hc_user_special_application',
                'status|eq|hc_user_special_application',
                's_created_at,e_created_at|between|hc_user_special_application',
                's_updated_at,e_updated_at|between|hc_user_special_application',
            ];
            $where = trans_form_to_where($field_list);
        }

        $list              =  $special_model->getList($where);

        //所有部分列表
        $common_model      =  Model();
        $dept_list         =  $common_model->table('hc_admin_dept')
                                           ->where(['is_del'=>0])
                                           ->select();

        Tpl::output('list', $list);
        Tpl::output('dept_list', $dept_list);
        Tpl::showpage('finance.special_index');
    }


    /**
     * 提出特别事项申请
     */
    public function special_addOp()
    {
        //提交表单
        $request    =   getPayload();
        if($request['in_ajax'])
        {
            $application_model =  Model('hc_user_special_application');
            $special_type    =    $request['special_type'];
            $money = 0;
            if($special_type && $special_type < 6){
                $money       =    $request['special_type_'.$special_type.'_val'];
            }
            if($money<= 0){
                json_error('金额不正确！');
            }
            $special_info   = get_application_info_by_special_type($special_type);
            //申请记录
            $application  = [
                'user_id'         => $request['uid'],
                'special_type'    => $request['special_type'],
                'money'           => $money,
                'money_type'      => $special_info['money_type'],
                'name'            => $special_info['name'],
                'from_where'      => $special_info['from'],
                'to_where'        => $special_info['to'],
                'type'            => self::APPLICATION_TYPE,
                'reason'          => $request['reason'],
                'remark'          => $request['remark'],
                'type'            => self::APPLICATION_TYPE,
                'apply_admin_id'  => $this->admin_info['id'],
                'apply_admin_name'=> $this->admin_info['name'],
            ];

            $id = $application_model->addApplication($application);
            if($id){
                json(['code'=>200,'data'=>$id,'msg'=>'申请提交成功']);
            }else{
                json_error('申请失败');
            }

        }


        if(!isset($_GET['uid']) || !is_numeric($_GET['uid'])){
            showMessage('uid参数不正确');
        }

        //获取用户信息
        $user_model         =  Model('user');
        $user               =  $user_model->getUserById($_GET['uid'], 'id,name,phone,email,created_at,updated_at');
        if(!$user['name']) $user['name'] = C('default_username');
        $account   =   $user_model->table('hc_user_account')->where(['user_id'=>$user['id']])->find();
        if($account)
        {
            $user['account'] = $account;
        }
        //获取用户相关订单信息
        $order_list =  $user_model->table('hc_order')->field('id')->where(['user_id'=>$user['id'],''])->select();

//        json($order_list);
        $max_transfer_to_user_account = config('max_transfer_to_user_account');
        Tpl::output('max_transfer_to_user_account',$max_transfer_to_user_account);
        Tpl::output('order_list',$order_list);
        Tpl::output("user",$user);

        Tpl::showpage('finance.special_add');
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

    /**
     * 用户特别事项列表
     */
    public function user_special_indexOp()
    {
        if(!isset($_GET['uid']) || !is_numeric($_GET['uid'])){
            showMessage('uid参数不正确');
        }



        //获取用户信息
        $user_model         =  Model('user');
        $user               =  $user_model->getUserById($_GET['uid'], 'id,name,phone,email,created_at,updated_at');
        if(!$user['name']) $user['name'] = C('default_username');

        $account   =   $user_model->table('hc_user_account')->where(['user_id'=>$user['id']])->find();
        if($account)
        {
            $user['account'] = $account;
        }
        
        //获取该用户的特别事项列表
        $map =  [];
        if(isset($_GET['status']) && is_numeric($_GET['status'])){
            $map['status']  = $_GET['status'];
            Tpl::output("status", $_GET['status']);
        }

        $map['user_id']    = $user['id'];
        $application_list  =  $user_model->table('hc_user_special_application')
                                        ->where($map)
                                        ->order('created_at desc')
                                        ->select();
//        json($application_list);
        Tpl::output("list",$application_list);

	    Tpl::output("user",$user);
        Tpl::showpage('finance.user_special_index');
    }

    /**
     * 用户特别事项详情
     */
    public function user_special_detailOp()
    {
        if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
            showMessage('id参数不正确');
        }

        //有uid代表从客户财务那里进来的
        if(!isset($_GET['uid']) || !is_numeric($_GET['uid'])){
            showMessage('uid参数不正确');
        }

        //获取用户信息
        $user_model         =  Model('user');
        $user               =  $user_model->getUserById($_GET['uid'], 'id,name,phone,email,created_at,updated_at');
        //获取用户账户信息
        if(!$user['name']) $user['name'] = C('default_username');


        //获取该用户的特别事项详情
        $application  =  $user_model->table('hc_user_special_application')
                            ->where(['user_id'=>$user['id'],'id'=>$_GET['id']])
                            ->order('created_at desc')
                            ->find();
        Tpl::output("user",$user);
        Tpl::output("application",$application);
        Tpl::showpage('finance.user_special_detail');
    }

    /**
     * 特别事项详情
     */
    public function special_detailOp()
    {
        $request    =    getPayload();
        if($request['in_ajax']){
            if(!$request['id']) json_error('缺少参数id');
            if(!$request['status']) json_error('缺少参数status');
            $application_id      = $request['id'];
            $application_model   = Model('hc_user_special_application');
            $application         = $application_model->where(['id'=>$application_id])
                                                    ->find();
            if($application['status'] > 0){ //已经审批过了
                 json_error('该特事已完成审批');
            }

            //记录审批操作
            $operation  =[
                'user_id'    =>  $this->admin_info['id'],
                'user_name'  =>  $this->admin_info['name'],
                'related'   =>   'hc_user_special_application|'.$application_id, //关联数据
                'step'   =>      $application['status'], //操作步骤
                'type'       =>  self::SPECIAL_OPERATION_TYPE,  //14
            ];


            $now    = get_now2();
            $update  =[
                'status'  => $request['status'],
                'judge_remark' => $request['judge_remark'],
                'updated_at' => $now,
                'judge_admin_name' => $this->admin_info['name'],
                'judge_admin_id'   => $this->admin_info['id'],
                'judge_at'         => $now, //审批时间
            ];
           

            if($request['status'] ==1){
                //通过操作
                $handle_ret = $this->_handle_application($application);
                if($handle_ret){ //处理成功
                    $operation['operation'] ="审批申请-审批通过，处理成功" ;
                }else{//处理失败
                    $update['status']  = 2 ;//2 未通过状态
                    $update['judge_remark'] .="(审批申请-审批通过，但数据更新失败)" ;
                    $operation['operation'] ="审批申请-审批通过，但数据更新失败" ;
                }

            }else{
                $operation['operation'] ="审批申请-未通过" ;
            }
            $where   =[
                'id'  =>  $application_id
            ];

            $ret  = $application_model->update_with_record($where, $update, $application, $operation);
            //记录到user_account_log
            $user_account =   Model('hc_user_account')->where('user_id='.$application['user_id'])->find();
            $log  =  [
                'user_id'  => $application['user_id'],
                'item_id'  => $application['id'],
                'item'     => '特事审批-'.$application['name'],
                'remark'   => $application['name'],
                'money'    => $application['money'],
                'credit_avaliable' => isset($user_account['avaliable_deposit'])?$user_account['avaliable_deposit']:0,
                'type'     => 6,
                'pay_type' => 5,
                'created_at' => get_now2()
            ];

            if(in_array($application['special_type'],['1','3','5']))
                $log['money_type'] = '-';
            else
                $log['money_type'] = '+';



            if($ret) {
                if($handle_ret) //实际数据处理结果
                    $log['status'] = 1; //1成功，2失败
                else   
                    $log['status'] = 2; //1成功，2失败 

                 Model('hc_user_account_log')->insert($log);

                if( $update['status'] ==1 )  //审批通过
                { //插入资金总表
                    record_to_account_log($application,'application','member');
                }
                json([
                    'code' =>  200,
                    'msg'  =>  '审批成功',
                ]);
            }
            else {
                 $log['status'] = 2;// 1成功，2失败

                 Model('hc_user_account_log')->insert($log);

                 json_error('审批失败');
            }
        }


        if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
            showMessage('id参数不正确');
        }
        //获取用户信息
        $user_model         =  Model('user');

        //获取该用户的特别事项详情
        $application  =  $user_model->table('hc_user_special_application')
            ->where(['id'=>$_GET['id']])
            ->find();
        if($application){
            $user               =  $user_model->table('users')
                                        ->getUserById($application['user_id'], 'id,name,phone,email,created_at,updated_at');
        }else{
            showMessage('该申请不存在');
        }

        Tpl::output("user",$user);
        Tpl::output("application",$application);
        Tpl::showpage('finance.special_detail');
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
                        $data  = $common_model->table($table[0])
                                 ->where(['user_id'=>$user_id])->find();
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
                                    ->where(['user_id'=>$user_id])
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
                            ->where(['user_id'=>$user_id])
                            ->update($update);  //增加成功
                    }
                }
            }
            return true;
        }else{//返还已得 获取返还  TODO  流程未通

        }


    }
}
