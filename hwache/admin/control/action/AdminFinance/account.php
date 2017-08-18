<?php


class account extends SystemControl
{

//account_log客户充值 method_type
const USER_WITHDRAW_TYPE = 20;
const SELLER_WITHDRAW_TYPE = 21;
const PAGE = 20;

//操作类型
CONST MAINTAIN_OPERATION_TYPE = 36;


    public function __construct()
    {
        parent::__construct();
        Tpl::setDir('AdminFinance');
    }


    ###账户资金流动
    public function account_log(){
        $model   = Model('hc_account_log');
//是否有查询条件
        $where   = [];
        if(is_search())
        {
            $field_list =  [
                's_created_at,e_created_at|between|hc_account_log',
                's_money,e_money|between|hc_account_log',
                'from_where|eq|hc_account_log',
                'to_where|eq|hc_account_log',
                'trade_no|like|hc_account_log',
                'order_id|like|hc_account_log',
                'from_name|eq',
                'to_name|eq'
            ];
            $where       =  trans_form_to_where($field_list);
            if(isset($where['hc_account_log.from_where']) && $where['hc_account_log.from_where']<3 && trim($_GET['from_name'])){
                if($where['hc_account_log.from_where'] ==1)
                     $where['users.phone'] = ['like','%'.trim($_GET['from_name']).'%'];
                if($where['hc_account_log.from_where'] ==2)
                    $where['member.member_name'] = ['like','%'.trim($_GET['from_name']).'%'];
            }else unset($where['from_name']);
            if(isset($where['hc_account_log.to_where']) && $where['hc_account_log.to_where']<3  && trim($_GET['to_name'])){
                if($where['hc_account_log.to_where'] == 1)
                    $where['users.phone'] = ['like','%'.trim($_GET['to_name']).'%'];
                if($where['hc_account_log.to_where'] ==2)
                    $where['member.member_name'] = ['like','%'.trim($_GET['to_name']).'%'];
            }else unset($where['to_name']);
            //不对应表字段 1.recharge_method
            //2.bank_account
            //3.办理步骤加操作人
        }
        $list  = $model->getList($where);
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
            $excel_data[0][1] = array('styleid'=>'s_title','data'=>'发生金额');
            $excel_data[0][2] = array('styleid'=>'s_title','data'=>'支出方	');
            $excel_data[0][3] = array('styleid'=>'s_title','data'=>'支出方说明');
            $excel_data[0][4] = array('styleid'=>'s_title','data'=>'收入方');
            $excel_data[0][5] = array('styleid'=>'s_title','data'=>'收入方说明');
            $excel_data[0][6] = array('styleid'=>'s_title','data'=>'订单号');
            $excel_data[0][7] = array('styleid'=>'s_title','data'=>'流水号');
            //data
            foreach ($list as $k=>$v){
                $excel_data[$k+1][0] = array('data'=>$v['created_at']);
                $excel_data[$k+1][1] = array('data'=>$v['money']);
                $excel_data[$k+1][2] = array('data'=>show_account_log_where($v,'from'));
                $excel_data[$k+1][3] = array('data'=>$v['from_remark']);
                $excel_data[$k+1][4] = array('data'=>show_account_log_where($v,'to'));
                $excel_data[$k+1][5] = array('data'=>$v['to_remark']);
                $excel_data[$k+1][6] = array('data'=>$v['order_id']);
                $excel_data[$k+1][7] = array('data'=>$v['trade_no']);
            }


            $excel_data = $excel_obj->charset($excel_data,CHARSET);
            $excel_obj->addArray($excel_data);
            $title = '账户资金流动';
            $excel_obj->addWorksheet($excel_obj->charset('平台财务-'.$title,CHARSET));
            $p = $_GET['curpage'] ? $_GET['curpage'] : 1;
            $excel_obj->generateXML($excel_obj->charset('平台财务-'.$title.'-P'.$p.'-',CHARSET).date('Y-m-d-H',time()));
            exit();
        }else {
            Tpl::output('list', $list);
            Tpl::output('page', $model->showPage());

            Tpl::showpage('account_log');
        }

    }

    //计算
    public function account_calc()
    {
        $model   = Model('hc_account_log');

        $request = getPayload();
        if($request) $_GET = $request;
        if(is_search())
        {
            $field_list =  [
                's_created_at,e_created_at|between|hc_account_log',
                's_money,e_money|between|hc_account_log',
                'from_where|eq|hc_account_log',
                'to_where|eq|hc_account_log',
                'trade_no|like|hc_account_log',
                'order_id|like|hc_account_log',
                'from_name|eq',
                'to_name|eq'
            ];
            $where       =  trans_form_to_where($field_list);
            if(isset($where['hc_account_log.from_where']) && $where['hc_account_log.from_where']<3 && trim($_GET['from_name'])){
                if($where['hc_account_log.from_where'] ==1)
                    $where['users.phone'] = ['like','%'.trim($_GET['from_name']).'%'];
                if($where['hc_account_log.from_where'] ==2)
                    $where['member.member_name'] = ['like','%'.trim($_GET['from_name']).'%'];
            }else unset($where['from_name']);
            if(isset($where['hc_account_log.to_where']) && $where['hc_account_log.to_where']<3  && trim($_GET['to_name'])){
                if($where['hc_account_log.to_where'] == 1)
                    $where['users.phone'] = ['like','%'.trim($_GET['to_name']).'%'];
                if($where['hc_account_log.to_where'] ==2)
                    $where['member.member_name'] = ['like','%'.trim($_GET['to_name']).'%'];
            }else unset($where['to_name']);
            //不对应表字段 1.recharge_method
            //2.bank_account
            //3.办理步骤加操作人
        }
        $ret  = $model->calc($where);
        json(['code'=>200,'data'=>$ret]);
    }




    ###收入支出申报
    public function account_settlement()
    {
        $model   = Model('hc_admin_declare');
//是否有查询条件
        $where   = [];
        if(is_search())
        {
            $field_list =  [
                'type|eq',
                'year|eq',
                'month|eq',
                'status|eq',
                'is_invoice|eq',
                'status|eq',
                'to_seller_account|eq',
                'maintenance_status|eq',
                'to_seller_account|eq',
                'description|like'
            ];
            $where       =  trans_form_to_where($field_list);

            if(isset($where['maintenance_status']) && trim($_GET['voucher_number'])){
                $where['income_voucher_number|firstcost_voucher_number'] = ['like','%'.trim($_GET['voucher_number']).'%'];
            }
            if(isset($_GET['do_settlement']) && $_GET['do_settlement']!==''){ //暂不申报
                $where['status']  = 10;
            }
        }
        $order = 'updated_at desc';
        $list  = $model->getList($where,$order);

        //是否需要导出
        if(is_export())
        {
            import('libraries.excel');
            $excel_obj = new Excel();
            $excel_data = array();
            //设置样式
            $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
            //header
            $excel_data[0][0] = array('styleid'=>'s_title','data'=>'最近更新时间');
            $excel_data[0][1] = array('styleid'=>'s_title','data'=>'类别');
            $excel_data[0][2] = array('styleid'=>'s_title','data'=>'说明	');
            $excel_data[0][3] = array('styleid'=>'s_title','data'=>'收入金额');
            $excel_data[0][4] = array('styleid'=>'s_title','data'=>'业务成本');
            $excel_data[0][5] = array('styleid'=>'s_title','data'=>'含税毛利');
            $excel_data[0][6] = array('styleid'=>'s_title','data'=>'业务申报年月');
            $excel_data[0][7] = array('styleid'=>'s_title','data'=>'暂不申报');
            $excel_data[0][8] = array('styleid'=>'s_title','data'=>'状态');
            $excel_data[0][9] = array('styleid'=>'s_title','data'=>'申报凭证号');
            $excel_data[0][10] = array('styleid'=>'s_title','data'=>'平台开票');
            $excel_data[0][11] = array('styleid'=>'s_title','data'=>'售方入账');
            //data
            foreach ($list as $k=>$v){
                $excel_data[$k+1][0] = array('data'=>$v['updated_at']);
                $excel_data[$k+1][1] = array('data'=>show_declare_type($v['type']));
                $excel_data[$k+1][2] = array('data'=>$v['description']);
                $excel_data[$k+1][3] = array('data'=>$v['money']);
                $excel_data[$k+1][4] = array('data'=>$v['first_cost']);
                $excel_data[$k+1][5] = array('data'=>$v['gross_profit']);
                $excel_data[$k+1][6] = array('data'=>$v['year'].'-'.$v['month']);
                $excel_data[$k+1][7] = array('data'=>'');
                $excel_data[$k+1][8] = array('data'=>show_declare_status($v['status']));
                $excel_data[$k+1][9] = array('data'=>$v['income_series_number']);
                $excel_data[$k+1][10] = array('data'=>show_declare_is_invoice($v['is_invoice']));
                $excel_data[$k+1][11] = array('data'=>show_to_seller_account($v['to_seller_account']));
            }


            $excel_data = $excel_obj->charset($excel_data,CHARSET);
            $excel_obj->addArray($excel_data);
            $title = '收入支出申报';
            $excel_obj->addWorksheet($excel_obj->charset('平台财务-'.$title,CHARSET));
            $p = $_GET['curpage'] ? $_GET['curpage'] : 1;
            $excel_obj->generateXML($excel_obj->charset('平台财务-'.$title.'-P'.$p.'-',CHARSET).date('Y-m-d-H',time()));
            exit();
        }else {
            //后台有的年月
            $years = $model->getAllYears();
            $tmp = [];
            array_walk($years, function ($v, $k) use (&$tmp) {
                $tmp[] = $v['year'];
            });
            $years = $tmp;

            Tpl::output('years', $years);
            Tpl::output('list', $list);
            Tpl::output('page', $model->showPage());

            Tpl::showpage('account_settlement');
        }
    }

    //添加备注
    public function account_ajax_add_comment()
    {
        $comment_model  =  Model('hc_admin_declare_comment');

        $request  =  getPayload();
        if($request['in_ajax']) {
            if(trim($request['comment'])==''){
                json_error('备注内容不完整～');
            }
            $comment  = [
                'user_id'   => $this->admin_info['id'],
                'user_name' => $this->admin_info['name'],
                'declare_id' => $request['declare_id'],
                'comment'    => $request['comment'],
                'created_at' => get_now2()
            ];
            $ret = $comment_model->insert($comment);
            if($ret) json_succ('添加成功');
            else json_error('因故提交失败，请重新提交！');

        }

        $list  =  $comment_model->getList(['declare_id'=>$_GET['declare_id']]);

        Tpl::output('list',$list);
        Tpl::showpage('account_ajax_add_comment');
    }

    //获取收入详情
    public function account_ajax_get_declare_reason(){
        $request  =  getPayload();
        $type  = $request['type'];
        $declare_id  = $request['declare_id'];
        if(!$declare_id || !$type)
            json_error('参数错误');

        $model   = Model('hc_admin_declare');
        $flow_type = $request['flow_type']?$request['flow_type']:"1";
        $declare = $model->getDetailById($declare_id,$flow_type);
        if(!$declare)   json_error('目标不存在');

        json_succ('成功',$declare);
    }


    //获取收入详情
    public function account_ajax_get_declare(){
        $model   = Model('hc_admin_declare');
        $request  =  getPayload();
        if($request['in_ajax']) {
            $declare_id  = $request['id'];
            $declare = $model->getById($declare_id);
            if(!$declare) json_error('目标不存在');

            $where  = [
                'id' => $declare_id
            ];
            $update = [];
            if($request['income_voucher_number'])
                $update['income_voucher_number'] = $request['income_voucher_number'];
            if($request['firstcost_voucher_number'])
                $update['firstcost_voucher_number'] = $request['firstcost_voucher_number'];
            if($update)
                $update['maintenance_status'] = 1;

            //记录操作
            $operation = [
                'user_id'   => $this->admin_info['id'],
                'user_name' => $this->admin_info['name'],
                'related'   =>'hc_admin_declare|'.$declare['id'],
                'remark'    => '',
                'type'      => self::MAINTAIN_OPERATION_TYPE,//36
                'operation' =>'维护申报凭证号 添加凭证号',
            ];

            $ret = $model->update_with_record($where,$update,$declare,$operation);
            if($ret) json_succ('添加成功');
            else json_error('提交失败，请重新提交！');

        }
        $request  =  $_GET;
        $declare_id  = $request['declare_id'];
        if(!$declare_id)
            showMessage('参数错误');


        $declare = $model->getById($declare_id);
        if(!$declare) showMessage('目标不存在');

        Tpl::output('declare',$declare);
        Tpl::showpage('account_ajax_get_declare');
    }

    //修改
    public function account_ajax_update_declare(){
        $model   = Model('hc_admin_declare');
        $request  =  getPayload();
        if($request['in_ajax']) {
            $declare_id  = $request['id'];
            $declare = $model->getById($declare_id);
            if(!$declare) json_error('目标不存在');

            $where  = [
                'id' => $declare_id
            ];
            $update = [];
            if($request['income_voucher_number'])
                $update['income_voucher_number'] = $request['income_voucher_number'];
            if($request['firstcost_voucher_number'])
                $update['firstcost_voucher_number'] = $request['firstcost_voucher_number'];
            if($update)
                $update['maintenance_status'] = 1;

            //记录操作
            $operation = [
                'user_id'   => $this->admin_info['id'],
                'user_name' => $this->admin_info['name'],
                'related'   =>'hc_admin_declare|'.$declare['id'],
                'remark'    => '',
                'type'      => self::MAINTAIN_OPERATION_TYPE,//36
                'operation' =>'维护申报凭证号 修改凭证号',
            ];

            $ret = $model->update_with_record($where,$update,$declare,$operation);
            if($ret) json_succ('修改成功');
            else json_error('提交失败，请重新提交！');

        }
        $request  =  $_GET;
        $declare_id  = $request['declare_id'];
        if(!$declare_id)
            showMessage('参数错误');


        $declare = $model->getById($declare_id);
        if(!$declare) showMessage('目标不存在');

        Tpl::output('declare',$declare);
        Tpl::showpage('account_ajax_update_declare');
    }

    //收入或成本详情
    public function account_ajax_show_income(){
        $model   = Model('hc_admin_declare');
        $declare_id = $_GET['declare_id'];
        if(!$declare_id)
            showMessage('参数错误');
        $flow_type = $_GET['flow_type']?$_GET['flow_type']:"1";
        $declare = $model->getDetailById($declare_id,$flow_type);
        Tpl::output('declare',$declare);
        Tpl::showpage('account_ajax_show_income');
    }


    //收入支出维护日志
    public function account_ajax_declare_log(){
        $log_model  = Model('hc_admin_operation_detail');

        $declare_id  = $_GET['declare_id'];
        if(!$declare_id)
            showMessage('参数错误');
        $where1 = [
            'table_name' => 'hc_admin_declare',
            'related_id' => $declare_id,
            'field_name' => 'income_voucher_number'
        ];
        $where2 = [
            'table_name' => 'hc_admin_declare',
            'related_id' => $declare_id,
            'field_name' => 'firstcost_voucher_number'
        ];

        $list1  = $log_model->getDetailListByWhere($where1);
        $list2  = $log_model->getDetailListByWhere($where2);

        Tpl::output('income_list',$list1);
        Tpl::output('firstcost_list',$list2);
        Tpl::showpage('account_ajax_declare_log');
    }

    //提现手续费收入详情
    public function account_show_withdraw()
    {
        $model  =  Model('hc_admin_declare');
        if(!isset($_GET['declare_id']))
            showMessage('参数错误');
        $declare = $model->getById($_GET['declare_id']);

        if(!$declare){
            showMessage('系统错误');
        }

        //客户手续费list
        $log_model  = Model('hc_account_log');
        //获取时间和范围
        $range = GetTheMonth($declare['year'].'-'.$declare['month']);
        if($declare['type'] ==20)
        {
            $where = [
                'from_where'    => 1,
                'created_at'  => ['between',$range],
                'method_type' => self::USER_WITHDRAW_TYPE,
                'to_where'    => 3,
            ];

        }elseif ($declare['type'] ==21) //售方手续费
        {
            $where = [
                'from_where'    => 2,
                'created_at'  => ['between',$range],
                'method_type' => self::SELLER_WITHDRAW_TYPE,
                'to_where'    => 3,
            ];
        }else{ //暂无其他

        }
        $list  = $log_model->getLogListByWhere($where,self::PAGE);
        //是否需要导出
        if(is_export())
        {
            import('libraries.excel');
            $excel_obj = new Excel();
            $excel_data = array();
            //设置样式
            $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
            if($declare['type'] ==20){ //客户
                //header
                $excel_data[0][0] = array('styleid'=>'s_title','data'=>'客户会员号');
                $excel_data[0][1] = array('styleid'=>'s_title','data'=>'金额');
                $excel_data[0][2] = array('styleid'=>'s_title','data'=>'时间	');

                //data
                foreach ($list as $k=>$v){
                    $excel_data[$k+1][0] = array('data'=>$v['from']['phone']);
                    $excel_data[$k+1][1] = array('data'=>$v['money']);
                    $excel_data[$k+1][2] = array('data'=>$v['created_at']);
                }
            }else{ //售方
                //header
                $excel_data[0][0] = array('styleid'=>'s_title','data'=>'售方用户名');
                $excel_data[0][1] = array('styleid'=>'s_title','data'=>'金额');
                $excel_data[0][2] = array('styleid'=>'s_title','data'=>'时间	');

                //data
                foreach ($list as $k=>$v){
                    $excel_data[$k+1][0] = array('data'=>$v['from']['member_name']);
                    $excel_data[$k+1][1] = array('data'=>$v['money']);
                    $excel_data[$k+1][2] = array('data'=>$v['created_at']);
                }
            }

            $excel_data = $excel_obj->charset($excel_data,CHARSET);
            $excel_obj->addArray($excel_data);
            $title = '收入支出申报-提现手续费详情';
            $excel_obj->addWorksheet($excel_obj->charset('平台财务-'.$title,CHARSET));
            $p = $_GET['curpage'] ? $_GET['curpage'] : 1;
            $excel_obj->generateXML($excel_obj->charset('平台财务-'.$title.'-P'.$p.'-',CHARSET).date('Y-m-d-H',time()));
            exit();
        }else {

            Tpl::output('declare', $declare);
            Tpl::output('page', $model->showPage());
            Tpl::output('list',$list);
            Tpl::showpage('account_show_withdraw');

        }
    }

    //申报凭证号维护
    public function account_declare_maintain(){

        $model  =  Model('hc_admin_declare');
        //是否有查询条件
        $where   = [];
        if(is_search())
        {
            $field_list =  [
                'type|eq',
                'year|eq',
                'month|eq',
                'maintenance_status|eq',
                'description|like',
                'income_series_number|like',
                'income_voucher_number|like',
                'firstcost_series_number|like',
                'firstcost_voucher_number|like'
            ];
            $where       =  trans_form_to_where($field_list);



            if(isset($where['maintenance_status']) && trim($_GET['voucher_number'])){
                $where['income_voucher_number|firstcost_voucher_number'] = ['like','%'.trim($_GET['voucher_number']).'%'];
            }
//            if(isset($_GET['do_settlement']) && $_GET['do_settlement']!==''){ //暂不申报
//                $where['status']  = 10;
//            }
        }

        //页面显示已申报内容
        $where['status']  =20 ; //'10待申报 20已申报 30 免申报',
        $list  = $model->getList($where);

        //是否需要导出
        if(is_export())
        {
            import('libraries.excel');
            $excel_obj = new Excel();
            $excel_data = array();
            //设置样式
            $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
            //header
            $excel_data[0][0] = array('styleid'=>'s_title','data'=>'业务申报年月');
            $excel_data[0][1] = array('styleid'=>'s_title','data'=>'类别');
            $excel_data[0][2] = array('styleid'=>'s_title','data'=>'说明	');
            $excel_data[0][3] = array('styleid'=>'s_title','data'=>'维护状态');
            $excel_data[0][4] = array('styleid'=>'s_title','data'=>'收入金额');
            $excel_data[0][5] = array('styleid'=>'s_title','data'=>'收入序列号');
            $excel_data[0][6] = array('styleid'=>'s_title','data'=>'收入凭证号');
            $excel_data[0][7] = array('styleid'=>'s_title','data'=>'业务成本');
            $excel_data[0][8] = array('styleid'=>'s_title','data'=>'成本序列号');
            $excel_data[0][9] = array('styleid'=>'s_title','data'=>'成本凭证号');
            //data
            foreach ($list as $k=>$v){
                $excel_data[$k+1][0] = array('data'=>$v['year'].'-'.$v['month']);
                $excel_data[$k+1][1] = array('data'=>show_declare_type($v['type']));
                $excel_data[$k+1][2] = array('data'=>$v['description']);
                $excel_data[$k+1][3] = array('data'=>show_declare_maintenance_status($v['maintenance_status']));
                $excel_data[$k+1][4] = array('data'=>$v['money']);
                $excel_data[$k+1][5] = array('data'=>$v['income_series_number']);
                $excel_data[$k+1][6] = array('data'=>$v['income_voucher_number']);
                $excel_data[$k+1][7] = array('data'=>$v['first_cost']);
                $excel_data[$k+1][8] = array('data'=>$v['firstcost_series_number']);
                $excel_data[$k+1][9] = array('data'=>$v['firstcost_voucher_number']);
            }


            $excel_data = $excel_obj->charset($excel_data,CHARSET);
            $excel_obj->addArray($excel_data);
            $title = '收入支出申报-申报凭证号维护';
            $excel_obj->addWorksheet($excel_obj->charset('平台财务-'.$title,CHARSET));
            $p = $_GET['curpage'] ? $_GET['curpage'] : 1;
            $excel_obj->generateXML($excel_obj->charset('平台财务-'.$title.'-P'.$p.'-',CHARSET).date('Y-m-d-H',time()));
            exit();
        }else {


            //后台有的年月
            $years = $model->getAllYears();
            $tmp = [];
            array_walk($years, function ($v, $k) use (&$tmp) {
                $tmp[] = $v['year'];
            });
            $years = $tmp;


            Tpl::output('years', $years);
            Tpl::output('list', $list);
            Tpl::output('page', $model->showPage());


            Tpl::showpage('account_declare_maintain');
        }
    }

    //查看加信宝冻结余额明细
    public function account_ajax_get_jiaxinbao_detail()
    {
        $model  =  Model('hc_order_jiaxinbao_detail');

        $role  = $_GET['role'];
        $order_id  = $_GET['order_id'];
        if(!$role || !$order_id)
        {
            showMessage('参数错误 ');
        }

        $where = [
            'role' => $role,
            'order_id' =>$order_id
        ];

        $list  =  $model->getList($where);
        $sum  =  $model->getSum($where);

        Tpl::output('list', $list);
        Tpl::output('sum', $sum['sum']);
        Tpl::showpage('account_ajax_get_jiaxinbao_detail');

    }
}