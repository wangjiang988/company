<?php


class user extends SystemControl{

    //对应hc_filter_template type字段
    const WITHDRAW_LIMIT_TYPE = 11;
    const ONLINE_PAY_LIMIT_TYPE = 10;
    const WITHDRAW_FILTER_TYPE = 12;

    const WITHDRAW_LIMIT_OPERATION_TYPE = 31;
    const ONLINE_PAY_LIMIT_OPERATION_TYPE = 32;
    const WITHDRAW_FILTER_OPERATION_TYPE = 33;
    public function __construct()
    {
        parent::__construct();
        Tpl::setDir('AdminFinance');
    }

    ####客户提现手续设定
    //当前使用模板
    public function user_withdraw_limit()
    {
//        $data = [
//            ['range'=>[0.01,5],'fee'=>0],
//            ['range'=>[5.01,10010.00],'fee'=>5.00],
//            ['range'=>[10010.01,100015.00],'fee'=>10.00],
//            ['range'=>[100015.01,500020.00],'fee'=>15.00],
//            ['range'=>[500020.01,1000020.00],'fee'=>20.00],
//            ['range'=>[1000020,0],'fee'=>'提现金额 x 0.002%','percent'=>'0.00002'],
//        ];
//        json($data);
        $model  =   Model('hc_filter');
        $filter = $model->getCurrentTemplate(self::WITHDRAW_LIMIT_TYPE);
        Tpl::output('filter', $filter);
        Tpl::showpage('user_withdraw_limit');
    }

    //更换模板
    public function user_change_withdraw_limit()
    {
        $template_model  =   Model('hc_filter_template');
        $filter_model    =   Model('hc_filter');

        $request  =  getPayload();
        if($request['in_ajax']){
            $filter = $filter_model->getFilter(self::WITHDRAW_LIMIT_TYPE);
            $id     = $request['template'] ;
            if($id == $filter['template_id']){
                json_error('无需更改');
            }

            $template  = $template_model->getTemplate(self::WITHDRAW_LIMIT_TYPE, ['id'=>$id]);
            if(!$template)
                json_error('没有此模板记录');

            $where['id'] = $filter['id'];
            $update['template_id'] = $id;
            $operation = [
                'user_id'   => $this->admin_info['id'],
                'user_name' => $this->admin_info['name'],
                'related'   =>'hc_filter|'.$filter['id'],
                'remark'    => $template['name'],
                'type'      => self::WITHDRAW_LIMIT_OPERATION_TYPE, //使用默认值
                'operation' =>'设定模板为'.$template['name'],
            ];
            $filter_model->update_with_record($where,$update,$template,$operation);
            json([
                'code' =>200,
                'msg'  =>'设置成功'
            ]);
        }

        $filter = $filter_model->getFilter(self::WITHDRAW_LIMIT_TYPE);
        $list = $template_model->getTemplateList(self::WITHDRAW_LIMIT_TYPE);
        Tpl::output('filter', $filter);
        Tpl::output('list', $list);
        Tpl::showpage('user_change_withdraw_limit');
    }

    public function user_ajax_update_form()
    {
        $id = $_GET['template_id'];
        $template_model  = Model('hc_filter_template');

        if(!$id){
            echo "参数错误";
        }

        $template  = $template_model->getTemplate(self::WITHDRAW_LIMIT_TYPE, ['id'=>$id]);
        Tpl::output('template',$template);
        Tpl::showpage('user_ajax_update_form');
    }

    //添加模板
    public function user_add_withdraw_limit()
    {

        Tpl::showpage('user_add_withdraw_limit');
    }

    //查看更新记录
    public function user_change_withdraw_limit_history()
    {
        $operation_model   =   Model('hc_admin_operation');
        $where = [
            'type' => self::WITHDRAW_LIMIT_OPERATION_TYPE,
        ];
        $list              =   $operation_model->getList($where);
        Tpl::output('list', $list);
        Tpl::showpage('user_change_withdraw_limit_history');
    }

    ####客户线上支付退款周期设定
    //退款时间
    public function user_online_pay_limit()
    {
        $model = Model('hc_filter_template');
        $list  = $model->getTemplateList(self::ONLINE_PAY_LIMIT_TYPE);

        Tpl::output('list', $list);
        Tpl::showpage('user_online_pay_limit');
    }

    //修改
    public function user_update_online_pay_limit()
    {
        $model = Model('hc_filter_template');

        $request  =  getPayload();
        if($request['in_ajax']) {
            $template = $model->getTemplate(self::ONLINE_PAY_LIMIT_TYPE,['id'=>$request['id']],false);
            if(!$template){
                json('未找到');
            }

            $where['id'] = $template['id'];
            $update = [
                'user_range' => $request['user'],
                'admin_range' => $request['admin']
            ];
            $operation = [
                'user_id'   => $this->admin_info['id'],
                'user_name' => $this->admin_info['name'],
                'related'   =>'hc_filter_template|'.$template['id'],
                'remark'    => $template['name'],
                'type'      => self::ONLINE_PAY_LIMIT_OPERATION_TYPE,
                'operation' =>'修改'.$template['name'],
            ];
            $model->update_with_record($where,$update,$template,$operation);
            json([
                'code' =>200,
                'msg'  =>'修改成功'
            ]);

        }

        $list  = $model->getTemplateList(self::ONLINE_PAY_LIMIT_TYPE);

        Tpl::output('list', $list);
        Tpl::showpage('user_update_online_pay_limit');
    }

    //修改历史
    public function user_update_online_pay_limit_history()
    {
        $operation_model   =   Model('hc_filter_template');
        $where = [
            'hc_admin_operation.type' => self::ONLINE_PAY_LIMIT_OPERATION_TYPE,
        ];

        $list     =   $operation_model->getUpdateHistory($where);
        Tpl::output('list', $list);
        Tpl::output('page', $operation_model->showPage());
        Tpl::showpage('user_update_online_pay_limit_history');
    }

    ###客户提现拦截
    //客户提现拦截
    public function user_withdraw_filter(){
        $model  =   Model('hc_filter_template');
        $list = $model->getCurrentTemplateList(self::WITHDRAW_FILTER_TYPE);

        Tpl::output('list', $list);
        Tpl::showpage('user_withdraw_filter');
    }

    //客户提现拦截修改
    public function user_change_withdraw_filter()
    {
        $model = Model('hc_filter_template');

        $request  =  getPayload();
        if($request['in_ajax']) {
            $count    = $request['count'];
            $template_ids = [];
            for ($i=0;$i<$count;$i++){
                $id       =  $request['name_'.$i];
                $template =  $model->getTemplate(self::WITHDRAW_FILTER_TYPE,['id'=>$id],false);
                if(!$template) continue;
                if($template['status'] == $request['template_'.$i])  continue;//和原来的值一样，则不用修改

                $where['id'] = $template['id'];
                $update = [
                    'status' => $request['template_'.$i],
                ];
                $operation = [
                    'user_id'   => $this->admin_info['id'],
                    'user_name' => $this->admin_info['name'],
                    'related'   =>'hc_filter_template|'.$template['id'],
                    'remark'    => $request['template_'.$i]?'启用':"禁用",
                    'type'      => self::WITHDRAW_FILTER_OPERATION_TYPE,
                    'operation' => $request['template_'.$i]?'启用':"禁用",
                ];
                $model->update_with_record($where,$update,$template,$operation);

                if(isset($request['name_'.$i]) && $request['template_'.$i]){
                    $template_ids[] = $template['id'];
                }
            }
            // if($template_ids){
            $template_ids = implode(',',$template_ids);

            $filter_model    =   Model('hc_filter');
            $filter_model->where(['type'=>self::WITHDRAW_FILTER_TYPE])->update(['template_id'=>$template_ids]);
            // }else{
            //     json_error('没有需要修改的选项');
            // }

            json([
                'code' =>200,
                'msg'  =>'修改成功'
            ]);

        }

        $list  = $model->getTemplateList(self::WITHDRAW_FILTER_TYPE);

        Tpl::output('list', $list);
        Tpl::showpage('user_change_withdraw_filter');
    }

    //添加拦截条件
    public function user_add_withdraw_filter(){
        $model  =   Model('hc_filter_template');
        $list = $model->getCurrentTemplateList(self::WITHDRAW_FILTER_TYPE);

        Tpl::output('list', $list);
        Tpl::showpage('user_add_withdraw_filter');
    }

    //拦截条件修改历史
    public function user_change_withdraw_filter_history(){
        $operation_model   =   Model('hc_filter_template');
        $where = [
            'hc_admin_operation.type' => self::WITHDRAW_FILTER_OPERATION_TYPE,
        ];

        $list     =   $operation_model->getUpdateHistory($where);
        Tpl::output('list', $list);
        Tpl::output('page', $operation_model->showPage());
        Tpl::showpage('user_change_withdraw_filter_history');
    }

    //用户加信保
    public function user_jiaxinbao()
    {
        $user_model   =  Model('user');

        $where   = [];
        $order   = $_GET['order']?$_GET['order']:'asc';
        if(is_search())
        {
            $field_list =  [
                'id|like|users',
                'phone|like|users',
                's_freeze_deposit,e_freeze_deposit|between|hc_user_account',
            ];
            $where       =  trans_form_to_where($field_list);
        }


        $list  = $user_model->getUserList2('users.id,users.name,users.phone,hc_user_account.*',$where , $order);

        if(is_export())
        {
            import('libraries.excel');
            $excel_obj = new Excel();
            $excel_data = array();
            //设置样式
            $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));

            $excel_data[0][0] = array('styleid'=>'s_title','data'=>'客户会员号');
            $excel_data[0][1] = array('styleid'=>'s_title','data'=>'客户手机号码');
            $excel_data[0][2] = array('styleid'=>'s_title','data'=>'冻结金额	');
            //data
            foreach ($list as $k=>$v){
                $excel_data[$k+1][0] = array('data'=>$v['id']);
                $excel_data[$k+1][1] = array('data'=>$v['phone']);
                $excel_data[$k+1][2] = array('data'=>$v['freeze_deposit']? $v['freeze_deposit']:0);
            }
            $excel_data = $excel_obj->charset($excel_data,CHARSET);
            $excel_obj->addArray($excel_data);
            $title = '客户加信宝';
            $excel_obj->addWorksheet($excel_obj->charset('平台财务-'.$title,CHARSET));
            $p = $_GET['curpage'] ? $_GET['curpage'] : 1;
            $excel_obj->generateXML($excel_obj->charset('平台财务-'.$title.'-P'.$p.'-',CHARSET).date('Y-m-d-H',time()));
            exit();
        }else {
            //所有金额总计、
            $data = $user_model->sum_freeze_deposit();
            $sum_freeze_deposit = 0;
            if($data['sum_freeze_deposit']){
                $sum_freeze_deposit = $data['sum_freeze_deposit'];
            }

            Tpl::output('sum_freeze_deposit', $sum_freeze_deposit);
            Tpl::output('list', $list);
            Tpl::output('page', $user_model->showPage());
            Tpl::showpage('user_jiaxinbao');
        }
    }

    //客户加信宝 -查看
    public function user_jiaxinbao_detail()
    {
        $id   = $_GET['id'];
        if(!$id){
            showMessage('参数错误');
        }

        $where = [];
        if(is_search())
        {
            $field_list =  [
                'order_id|like|jiaxinbao',
                's_sum,e_sum|between|jiaxinbao',
            ];
            $where       =  trans_form_to_where($field_list);
        }

        $where['users.id'] =   $id;
        $map['users.id'] =   $id;

        $user_model   =  Model('user');
        $data         =  $user_model->getUserAccount($map);

        //查询加信宝订单列表
        $list   = $user_model->getUserJiaxinbaoLogs($where);

        if(is_export())
        {
            import('libraries.excel');
            $excel_obj = new Excel();
            $excel_data = array();
            //设置样式
            $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));

            $excel_data[0][0] = array('styleid'=>'s_title','data'=>'订单号');
            $excel_data[0][1] = array('styleid'=>'s_title','data'=>'订单时间');
            $excel_data[0][2] = array('styleid'=>'s_title','data'=>'冻结金额	');
            //data
            foreach ($list as $k=>$v){
                $excel_data[$k+1][0] = array('data'=>$v['id']);
                $excel_data[$k+1][1] = array('data'=>$v['created_at']);
                $excel_data[$k+1][2] = array('data'=>$v['sum']? $v['sum']:0.00);
            }
            $excel_data = $excel_obj->charset($excel_data,CHARSET);
            $excel_obj->addArray($excel_data);
            $title = '客户加信宝-用户ID-'.$id;
            $excel_obj->addWorksheet($excel_obj->charset('平台财务-'.$title,CHARSET));
            $p = $_GET['curpage'] ? $_GET['curpage'] : 1;
            $excel_obj->generateXML($excel_obj->charset('平台财务-'.$title.'-P'.$p.'-',CHARSET).date('Y-m-d-H',time()));
            exit();
        }else {

            Tpl::output('data', $data);
            Tpl::output('list', $list);
            Tpl::output('page', $user_model->showPage());
            Tpl::showpage('user_jiaxinbao_detail');
        }
    }
}

