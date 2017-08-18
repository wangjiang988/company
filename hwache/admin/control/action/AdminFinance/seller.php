<?php


class seller extends SystemControl
{

    //对应hc_filter_template type字段
    const WITHDRAW_LIMIT_TYPE = 21;
    const WITHDRAW_FILTER_TYPE = 22;

    const WITHDRAW_LIMIT_OPERATION_TYPE = 35;
    const WITHDRAW_FILTER_OPERATION_TYPE = 34;
    const SETTLEMENT_CONFIRM_OPERATION_TYPE = 37;


    public function __construct()
    {
        parent::__construct();
        Tpl::setDir('AdminFinance');
    }


    ###售方提现拦截
    //售方提现拦截
    public function seller_withdraw_filter(){
        $model  =   Model('hc_filter_template');
        $list = $model->getCurrentTemplateList(self::WITHDRAW_FILTER_TYPE);

        Tpl::output('list', $list);
        Tpl::showpage('seller_withdraw_filter');
    }

    //售方提现拦截修改
    public function seller_change_withdraw_filter()
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

                if(isset($request['name_'.$i]) && $request['template_'.$i]){ //记录所有启用的模板
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
        Tpl::showpage('seller_change_withdraw_filter');
    }

    //添加拦截条件
    public function seller_add_withdraw_filter(){
        $model  =   Model('hc_filter_template');
        $list = $model->getCurrentTemplateList(self::WITHDRAW_FILTER_TYPE);

        Tpl::output('list', $list);
        Tpl::showpage('seller_add_withdraw_filter');
    }

    //拦截条件修改历史
    public function seller_change_withdraw_filter_history(){
        $operation_model   =   Model('hc_filter_template');
        $where = [
            'hc_admin_operation.type' => self::WITHDRAW_FILTER_OPERATION_TYPE,
        ];

        $list     =   $operation_model->getUpdateHistory($where);
        Tpl::output('list', $list);
        Tpl::output('page', $operation_model->showPage());
        Tpl::showpage('seller_change_withdraw_filter_history');
    }


    ####售方提现手续设定
    //当前使用模板
    public function seller_withdraw_limit()
    {
        $model  =   Model('hc_filter');
        $filter = $model->getCurrentTemplate(self::WITHDRAW_LIMIT_TYPE);
        Tpl::output('filter', $filter);
        Tpl::showpage('seller_withdraw_limit');
    }
    //修改当前模板
    public function seller_ajax_change_template()
    {
        $template_model  =   Model('hc_filter_template');
        $request  = getPayload();
        if($request['in_ajax'])
        {
            $id     = $request['id'] ;
            if(!$id){
                json_error('参数错误');
            }

            $template  = $template_model->getTemplate(self::WITHDRAW_LIMIT_TYPE, ['id'=>$id] , false);
            if(!$template)
                json_error('没有此模板记录');

            $where = ['id' =>  $id] ;
            $update['content'] = json_encode([
                'type'     => $request['type']?$request['type']:2,
                'fee'      => $request['fee'],
                'freetime' => $request['freetime']
            ]);
            $update['description'] = $request['description'];
            $operation = [
                'user_id'   => $this->admin_info['id'],
                'user_name' => $this->admin_info['name'],
                'related'   =>'hc_filter_template|'.$template['id'],
                'remark'    => $template['name'],
                'type'      => 38, //使用默认值
                'operation' =>'修改售方提现手续费标准',
            ];
            $template_model->update_with_record($where,$update,$template,$operation);
            json([
                'code' =>200,
                'msg'  =>'设置成功'
            ]);
        }

        $id   = $_GET['template_id'];
        if(!$id){
            showMessage('参数错误');
        }
        $model  =   Model('hc_filter_template');
        $where  =  [
            'id'  =>$id,
            'status' => 1,
            'type'   => self::WITHDRAW_LIMIT_TYPE,
            'is_del'  =>0
        ];

        $template = $model->getDataByWhere($where);
        $template['content']  = json_decode($template['content'],true);
        Tpl::output('template', $template);
        Tpl::showpage('seller_ajax_change_template');
    }

    //查看修改记录
    public function seller_ajax_change_template_history()
    {
        $model   = Model('hc_filter_template');
        $id   = $_GET['template_id'];
        if(!$id){
            showMessage('参数错误');
        }
        $template  = $model->getTemplate(self::WITHDRAW_LIMIT_TYPE, ['id'=>$id] );


        $where =[
            'related'   =>  'hc_filter_template|'.$id,
            'type'      =>  38
        ];
        $model   = Model('hc_admin_operation');
        $list =  $model->getListByWhere($where);

        $detail_model  = Model('hc_admin_operation_detail');
        foreach ($list as $k => $item)
        {
            $map = [
                'op_id'=>$item['id'] ,
                'table_name'=>'hc_filter_template',
                'related_id' => $id
            ];

            $detail =  $detail_model->getDataByWhere($map);
            if($detail){
                $detail['now_val']  = json_decode($detail['now_val'],true);
                $list[$k]['detail'] = $detail;
            }
        }
        Tpl::output('template', $template);
        Tpl::output('list', $list);
        Tpl::showpage('seller_ajax_change_template_history');
    }

    //更换模板
    public function seller_change_withdraw_limit()
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
        Tpl::showpage('seller_change_withdraw_limit');
    }

    public function seller_ajax_update_form()
    {
        $id = $_GET['template_id'];
        $template_model  = Model('hc_filter_template');

        if(!$id){
            echo "参数错误";
        }

        $template  = $template_model->getTemplate(self::WITHDRAW_LIMIT_TYPE, ['id'=>$id]);
        Tpl::output('template',$template);
        Tpl::showpage('seller_ajax_update_form');
    }

    //添加模板
    public function seller_add_withdraw_limit()
    {

        Tpl::showpage('seller_add_withdraw_limit');
    }

    //查看更新记录
    public function seller_change_withdraw_limit_history()
    {
        $operation_model   =   Model('hc_admin_operation');
        $where = [
            'type' => self::WITHDRAW_LIMIT_OPERATION_TYPE,
        ];

        $list              =   $operation_model->getList($where);


        Tpl::output('list', $list);
        Tpl::showpage('seller_change_withdraw_limit_history');
    }



    //售方收入开票入账 (售方结算)
    public function seller_settlement()
    {
        $model  = Model('hc_settlement');

        //是否有查询条件 TODO 查询
        $where   = [];
        if(is_search())
        {
            $field_list =  [
                'member_name|like|member',
                'member_truename|like|member',
                'year|eq|hc_settlement',
                'month|eq|hc_settlement',
                'status|eq|hc_settlement',
                's_money,e_money|between|hc_settlement',
                's_confirm_money,e_confirm_money|between|hc_settlement',
                's_confirm_at,e_confirm_at|between|hc_settlement',
                'file_number|eq|hc_settlement_filecount',
            ];
            $where       =  trans_form_to_where($field_list);
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
            $excel_data[0][0] = array('styleid'=>'s_title','data'=>'结算年月');
            $excel_data[0][1] = array('styleid'=>'s_title','data'=>'售方用户名');
            $excel_data[0][2] = array('styleid'=>'s_title','data'=>'姓名	');
            $excel_data[0][3] = array('styleid'=>'s_title','data'=>'身份证号');
            $excel_data[0][4] = array('styleid'=>'s_title','data'=>'结算文件剩余数');
            $excel_data[0][5] = array('styleid'=>'s_title','data'=>'结算总金额');
            $excel_data[0][6] = array('styleid'=>'s_title','data'=>'实入售方金额');
            $excel_data[0][7] = array('styleid'=>'s_title','data'=>'实入售方时间');
            //data
            foreach ($list as $k=>$v){
                $excel_data[$k+1][0] = array('data'=>$v['year'].'-'.$v['month']);
                $excel_data[$k+1][1] = array('data'=>$v['member_name']);
                $excel_data[$k+1][2] = array('data'=>$v['member_truename']);
                $excel_data[$k+1][3] = array('data'=>$v['seller_card_num']);
                $file_number  = '';
                if($v['status']>0)  $file_number =  '—' ; else $file_number =$v['file_number'];
                $excel_data[$k+1][4] = array('data'=>$file_number);
                $excel_data[$k+1][5] = array('data'=>"￥".$v['money']);
                if($v['status']>0) {
                    $excel_data[$k+1][6] = array('data'=>"￥".$v['confirm_money']);
                    $excel_data[$k+1][7] = array('data'=> $v['confirm_at']);
                }else{
                    $excel_data[$k+1][6] = array('data'=>'');
                    $excel_data[$k+1][7] = array('data'=>'');
                }
            }
            $excel_data = $excel_obj->charset($excel_data,CHARSET);
            $excel_obj->addArray($excel_data);
            $title = '售方收入开票入账';
            $excel_obj->addWorksheet($excel_obj->charset('平台财务-'.$title,CHARSET));
            $p = $_GET['curpage'] ? $_GET['curpage'] : 1;
            $excel_obj->generateXML($excel_obj->charset('平台财务-'.$title.'-P'.$p.'-',CHARSET).date('Y-m-d-H',time()));
            exit();
        }else {
            //后台有的年月
            $years = $model->table('hc_settlement')->getAllYears();
            $tmp = [];
            array_walk($years, function ($v, $k) use (&$tmp) {
                $tmp[] = $v['year'];
            });
            $years = $tmp;

            Tpl::output('years', $years);
            Tpl::output('list', $list);
            Tpl::output('page', $model->showPage());

            Tpl::showpage('seller_settlement');
        }
    }

    /**
     * calc
     */
    public function seller_settlement_calc()
    {
        $model  = Model('hc_settlement');

        //是否有查询条件 TODO 查询
        $_GET  = getPayload();
        $where   = [];
        if(is_search())
        {
            $field_list =  [
                'member_name|like|member',
                'member_truename|like|member',
                'year|eq|hc_settlement',
                'month|eq|hc_settlement',
                'status|eq|hc_settlement',
                's_money,e_money|between|hc_settlement',
                's_confirm_money,e_confirm_money|between|hc_settlement',
                's_confirm_at,e_confirm_at|between|hc_settlement',
                'file_number|eq|hc_settlement_filecount',
            ];
            $where       =  trans_form_to_where($field_list);
        }

        $ret = $model->calc($where);
        json_succ("成功",$ret);
    }

    //查看结算订单明细
    public function seller_ajax_settlement_detail()
    {
        $id = $_GET['settlement_id'];
        if(!$id) showMessage("参数错误");
        $settlement_detail_model  =  Model('hc_settlement_detail');
        $list       =  $settlement_detail_model->where(['settlement_id' => $id ])->select();

        $settlement_model         =  Model('hc_settlement');
        $settlement      = $settlement_model->getInfoById($id);


        if(is_export())
        {
            import('libraries.excel');
            $excel_obj = new Excel();
            $excel_data = array();
            //设置样式
            $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
            //header
            $excel_data[0][0] = array('styleid'=>'s_title','data'=>'售方用户名:'.$settlement['member_name']);
            $excel_data[0][1] = array('styleid'=>'s_title','data'=>'姓名:'.$settlement['member_truename']);
            $excel_data[0][2] = array('styleid'=>'s_title','data'=>'身份证号:'.$settlement['seller_card_num']);
            $excel_data[0][3] = array('styleid'=>'s_title','data'=>'结算年月:'.$settlement['year'].'-'.$settlement['month']);

            $excel_data[1][0] = array('styleid'=>'s_title','data'=>'订单号');
            $excel_data[1][1] = array('styleid'=>'s_title','data'=>'成本序列号');
            $excel_data[1][2] = array('styleid'=>'s_title','data'=>'结算金额	');
            $excel_data[1][3] = array('styleid'=>'s_title','data'=>'项目明细及金额');
            //data
            foreach ($list as $k=>$v){
                $excel_data[$k+2][0] = array('data'=>$v['order_id']);
                $excel_data[$k+2][1] = array('data'=>$v['firstcost_series_number']);
                $excel_data[$k+2][2] = array('data'=>$v['money']);
                $excel_data[$k+2][3] = array('data'=>$v['description']);
            }
            $excel_data = $excel_obj->charset($excel_data,CHARSET);
            $excel_obj->addArray($excel_data);
            $title = '结算订单明细';
            $excel_obj->addWorksheet($excel_obj->charset('平台财务-'.$title,CHARSET));
            $p = $_GET['curpage'] ? $_GET['curpage'] : 1;
            $excel_obj->generateXML($excel_obj->charset('平台财务-'.$title.'-P'.$p.'-',CHARSET).date('Y-m-d-H',time()));
            exit();
        }else {

            Tpl::output('settlement', $settlement);
            Tpl::output('list', $list);
            Tpl::showpage('seller_ajax_settlement_detail');
        }
    }

    //实入售方详情/确定实入售方
    public function seller_ajax_settlement_confirm()
    {
        $settlement_model         =  Model('hc_settlement');

        $request  =  getPayload();
        if($request['in_ajax']) {
            $id  = $request['id'];
            $settlement = $settlement_model->getById($id);
            if(!$settlement) json_error('目标不存在');

            $where  = [
                'id' => $id
            ];
            $update = [
                'confirm_money' => $request['confirm_money'],
                'confirm_at' => get_now2(),
                'confirm_user_id' => $this->admin_info['id'],
                'confirm_user_name' => $this->admin_info['name'],
                'status' =>1,
                'service_invoice_number' => $request['service_invoice_number'],
                'accord_voucher_number' => $request['accord_voucher_number']
            ];

            //记录操作
            $operation = [
                'user_id'   => $this->admin_info['id'],
                'user_name' => $this->admin_info['name'],
                'related'   =>'hc_settlement|'.$settlement['id'],
                'remark'    => '',
                'type'      => self::SETTLEMENT_CONFIRM_OPERATION_TYPE,//37
                'operation' =>'提交实入售方信息',
            ];

            $ret = $settlement_model->update_with_record($where,$update,$settlement,$operation);
            if($ret) json_succ('提交成功');
            else json_error('提交失败，请重新提交！');

        }

        $id = $_GET['settlement_id'];
        if(!$id) showMessage("参数错误");

        $settlement      = $settlement_model->getInfoById($id);

        Tpl::output('settlement', $settlement);
        Tpl::showpage('seller_ajax_settlement_confirm');
    }

    //售方加信报
    public function seller_jiaxinbao()
    {
        $model   =  Model('member');

        $where   = [];
        $order   = $_GET['order']?$_GET['order']:'asc';
        if(is_search())
        {
            $field_list =  [
                'member_name|like|member',
                'seller_phone|like|seller',
                's_freeze_deposit,e_freeze_deposit|between|hc_daili_account',
            ];
            $where       =  trans_form_to_where($field_list);
        }

        $list  = $model->getMemberAccountList('member.member_id,member.member_name,seller.seller_phone,hc_daili_account.*',$where , $order);

        if(is_export())
        {
            import('libraries.excel');
            $excel_obj = new Excel();
            $excel_data = array();
            //设置样式
            $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));

            $excel_data[0][0] = array('styleid'=>'s_title','data'=>'售方用户名');
            $excel_data[0][1] = array('styleid'=>'s_title','data'=>'售方手机');
            $excel_data[0][2] = array('styleid'=>'s_title','data'=>'冻结金额	');
            //data
            foreach ($list as $k=>$v){
                $excel_data[$k+1][0] = array('data'=>$v['member_name']);
                $excel_data[$k+1][1] = array('data'=>$v['seller_phone']);
                $excel_data[$k+1][2] = array('data'=>$v['freeze_deposit']? $v['freeze_deposit']:0);
            }
            $excel_data = $excel_obj->charset($excel_data,CHARSET);
            $excel_obj->addArray($excel_data);
            $title = '售方加信宝';
            $excel_obj->addWorksheet($excel_obj->charset('平台财务-'.$title,CHARSET));
            $p = $_GET['curpage'] ? $_GET['curpage'] : 1;
            $excel_obj->generateXML($excel_obj->charset('平台财务-'.$title.'-P'.$p.'-',CHARSET).date('Y-m-d-H',time()));
            exit();
        }else {
            //所有金额总计
            $data = $model->sum_freeze_deposit();
            $sum_freeze_deposit = 0;
            if($data['sum_freeze_deposit']){
                $sum_freeze_deposit = $data['sum_freeze_deposit'];
            }

            Tpl::output('sum_freeze_deposit', $sum_freeze_deposit);
            Tpl::output('list', $list);
            Tpl::output('page', $model->showPage());
            Tpl::showpage('seller_jiaxinbao');
        }
    }

    //客户加信宝 -查看
    public function seller_jiaxinbao_detail()
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

        $where['member.member_id'] =   $id;
        $map['member.member_id'] =   $id;



        $model   =  Model('member');
        $data         =  $model->getMemberAccount($map);

        //查询加信宝订单列表
        $list   = $model->getMemberJiaxinbaoLogs($where);

        Tpl::output('data', $data);
        Tpl::output('list', $list);
        Tpl::output('page', $model->showPage());
        Tpl::showpage('seller_jiaxinbao_detail');
    }

}