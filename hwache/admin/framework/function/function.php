<?php
/**
 * 后台公共方法
 *
 * 公共方法
 * @author wangjiang
 */
defined('InHG') or exit('Access Invalid!');


/**
 * 获取表单payload内容
 */
function getPayload()
{
    $json =  file_get_contents('php://input');
    return json_decode($json,true);
}

/**
 * 错误输出ajax
 */
function json_error($msg)
{
    json([
            'msg'    => $msg ,
            'code'   => 500
        ]);
}


/**
 * 根据用户支付类型 显示+-号
 * @param 读hc_account_log  type字段
 * type` tinyint(1) DEFAULT '0' COMMENT '流水类型、1充值、2提现、3购买、4退款',
 * return  "+","-"
 */
function GetSymbolByUserAccountLogType($type)
{
    $symbol = "+";
    if($type == 1 || $type == 4 )
    {
        $symbol = "+";
    }elseif($type == 2 || $type == 3 ){
        $symbol = "-";
    }else{
        $symbol = null;
    }

    return $symbol;
}

/**
 * 充值方式type转换文字
 * @param `recharge_type` tinyint(1) DEFAULT '0' COMMENT '充值方式，默认0，系统充值，1支付宝，2银行转账',
 */
function show_recharge_type($recharge_type){
    switch ($recharge_type)
    {
        case "1": return "支付宝";
        case "2": return "银行转账";
        case "3": return "财付通";
        case "4": return "通联支付";
        case "0": return "系统充值";
        default:;
    }
}
/**
 * 付款方式$recharge_type转换文字
 * @param `recharge_type` tinyint(1) DEFAULT '0' COMMENT '，2银行转账',
 */
function show_recharge_method($recharge_type){
    switch ($recharge_type)
    {
        case "1": return "线上付款";
        case "2": return "银行转账";
        case "3": return "线上付款";
        case "4": return "线上付款";
        case "0": return "系统充值";
        default:;
    }
}

/**
 *  提现表状态
 */
function show_withdraw_status($status){
    if($status >=100 ) return "已转账已补充";
    else{
        switch ($status)
        {
            case "1": return "已转账已补充";
            case "2": return "待接单";
            case "3": return "待转账";
            case "4": return "未成功";
            case "5": return "已转账未补充";
            case "0": return "待批准";
            default:;
        }
    }

}

/**
 * 获取提现表下一个状态
 */
function get_next_withdraw_status($status){
    //如果状态大于6  则从100开始无限往后加
    if($status>100) return  $status+1;
    $step_array = ["0","2","3","5","1","100"];
    $flip_array = array_flip($step_array);
    $key =  $flip_array[$status];
    if(!$step_array[$key+1]) return $step_array[$key]+1; //100后面开始状态变为101
    return $step_array[$key+1];
}
/**
 * 获取提现表上一个状态
 */
function get_back_withdraw_status($status){
    $step_array = ["0","2","3","5","1","6"];
    $flip_array = array_flip($step_array);
    $key =  $flip_array[$status];

    return $step_array[$key-1];
}
/**
 * 充值方式use_type转换文字
 * @param   `use_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '入账用途/类型（0充值、1支付买车担保金、2支付诚意金）',
 */
function show_recharge_use_type($recharge_type){
    switch ($recharge_type)
    {
        case "1": return "支付买车担保金";
        case "2": return "支付诚意金";
        case "0": return "充值";
        default:;
    }
}

/**
 * 客户财务转入-银行转账 下一步按钮文字
 */
function show_recharge_confirm_name($status)
{
    switch ($status)
    {
        case "0": return '提交核实';
        case "1": return "已核实，提交";
        case "2": return "提交补充信息";

        default:;
    }
}



/**
 * 充值表状态
 * @param $status `status` tinyint(255) DEFAULT '0' COMMENT '状态，0待入账，1核实入账、
 * 2已入账未补充、
 * 3已入账已补充、
 * 4无此款项',
 */
function show_recharge_status($status,$method=2) //1线上支付 2银行转账
{
    if($method ==2){
        switch ($status)
        {
            case "1": return "核实入账";
            case "2": return "已入账未补充";
            case "3": return "已入账已补充";
            case "4": return "无此款项";
            case "0": return "待入账";
            default:;
        }
    }else{
        switch ($status)
        {
            case "2": return "已入账";
            case "4": return "无此款项";
            case "0": return "待入账";
            default:;
        }
    }

}


/**
 * 提现方式type转换文字
 * car_hc_user_withdraw_line
 * `line_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '线路类型（1支付宝，2银行卡，3微信，4银联）',
 */
function show_line_type($line_type)
{
    switch ($line_type)
    {
        case "1": return "线上支付";
        case "2": return "银行转账";
        case "3": return "线上支付";
        case "4": return "银联转账";
        case "0": return "系统充值";
        default:;
    }
}

/**
 * 提现线路状态转换文字
 *  //四个状态  1.可使用，有充值记录  2.已验证可使用 ( 有充值 有提现记录) 3.已绑定账户 0. 已失效不可用
 * line_type 2 银行卡，其他事线上支付
 */
function show_withdraw_line_status($status, $line_type=2)
{
    if($line_type == 2){
        switch ($status)
        {
            case "1": return "可使用";
            case "2": return "已验证可使用";
            case "3": return "已绑定账户";
            case "0": return "已失效不可用";
            default:  return "未知状态";
        }
    }else{
        switch ($status)
        {
            case "1": return "已验证可使用";
            case "3": return "已绑定账户";
            case "0": return "已失效不可用";
            default:  return "未知状态";
        }
    }

}

/**
 * 转换数据状态为文字
 * @param $status 1,0
 * @param string $table
 * @return string
 */
function show_status($status,$table ='hc_user_withdraw_line')
{
    if($type='hc_user_withdraw_line'){
        switch ($status)
        {
            case "1": return "线上支付";
            case "2": return "银行转账";
            case "0": return "系统充值";
            default:;
        }
    }

}

/**
 * 性别转化为文字
 * @param $status 1,0
 * @param string $table
 * @return string
 */
function show_sex($sex)
{
    switch ($sex)
    {
        case "1": return "男";
        case "2": return "女";
        case "0": return "保密";
        default:;
    }

}



/**
 * 判断是否有查询条件
 */
function is_search()
{
    if(isset($_GET['is_search']) && $_GET['is_search'] == 1)
    {
        return true;
    }else{
        return false;
    }

}

/**
 * 对表单的数据转换为查询条件
 * 's_avaliable_deposit,e_avaliable_deposit|between|hc_user_account_log',
 * 字段 | 查询条件 | 表名
 */
function trans_form_to_where($field_list,$method ='GET')
{
    $where= [];
    $method  = strtoupper($method);
    if($method == 'POST')
    {
        $form = $_POST;
    }else{
        $form = $_GET;
    }
    foreach ($field_list as $field_condition)
    {
        //第一个字段为表字段
        $f_arr = explode('|',$field_condition);
        if(strpos($f_arr[0],',')){
            $field = explode(',',$f_arr[0]);
        }else{
            $field = $f_arr[0];
        }

        //第二个字段为查询条件
        $condition =  $f_arr[1];
        //第三个字段为表名
        $table_name = '';
        if (isset($f_arr[2]))
            $table_name= $f_arr[2].'.';
        switch ($condition)
        {
            case 'eq':
                if(!is_array($field) && isset($form[$field]) && trim($form[$field]) !=='')
                {
                    $where[$table_name.$field] = $form[$field];
                }
                break;
            case 'like':
                if(!is_array($field) && isset($form[$field]) && trim($form[$field]) !=='')
                {
                    $where[$table_name.$field] = ['like' ,'%'.$form[$field].'%'];
                }
                break;
            case 'between':
                if(is_array($field) && count($field) ==2 )
                {
                    //这里要求前台表单名称规范为s_字段名
                    $field_name = substr($field[0], 2);
                    if (isset($form[$field[0]]) && trim($form[$field[0]]) !== '' && isset($form[$field[1]]) && trim($form[$field[1]]) !== ''){
                        if(trim($form[$field[0]]) === '0' ||trim($form[$field[1]]) === '0')
                        {
                            $where[$table_name.$field_name]  = ['exp', $table_name.$field_name.' >= '.$form[$field[0]].' and '.$table_name.$field_name.' <= '. $form[$field[1]]];
                        }else{
                            $where[$table_name.$field_name] = ['between', '' . $form[$field[0]] . ',' . $form[$field[1]]];
                        }
                    }else{
                        if (isset($form[$field[0]]) && trim($form[$field[0]]) !== ''){
                            $where[$table_name.$field_name] = ['egt', $form[$field[0]]];
                        }

                        if (isset($form[$field[1]]) && trim($form[$field[1]]) !== ''){
                            $where[$table_name.$field_name] = ['elt', $form[$field[1]]];
                        }
                    }
                }
                break;
            default:;

        }

    }
    return $where;
}


/**
 * 是否请求中有导出要求
 */
function is_export()
{
    if(isset($_GET['export']) && $_GET['export'] ==1 )
        return true;
    else
        return false;
}

function json_succ($msg,$data=''){
    json([
        'code' => 200,
        'msg' => $msg,
        'data' => $data
    ]);
}
/**
 * 获取当前时间
 * 格式化时间格式，非时间戳
 */
function get_now()
{
    return     date('Y-m-d',time());
}
function get_now2()
{
    return     date('Y-m-d H:i:s',time());
}
function get_tomorrow()
{
    return date('Y-m-d',strtotime("+1 day"));
}
/**
 * 获取一个月前的时间
 * 格式化时间格式，非时间戳
 */
function get_last_month_date()
{
    return     date('Y-m-d',strtotime("-1 month"));
}

/**
 * 获取一个月前的时间
 * 格式化时间格式，非时间戳
 */
function get_last_year_date()
{
    return     date('Y-m-d',strtotime("-1 year"));
}

 /**
  * 添加修改记录
  * @author wangjiang
  */
function logger($operation, $details=[], $comments=[]){
    import('libraries.Logger');
    return Logger::record($operation,$details,$comments);
}

/**
 * 显示操作日志步骤
 */
function show_operation_step($step, $type=12)
{
    if(in_array($type,[12,22])){
        switch ($step)
        {
            case "1": return "核实";
            case "2": return "补充";
            case "0": return "提交";
            default:;
        }
    }
    if(in_array($type,[13,23])){
        if($step>=100) return "更正";
        switch ($step)
        {
            case "3": return "确认";
            case "2": return "接单";
            case "4": return "报错";
            case "41": return "拒绝";
            case "5": return "补充";
            case "1": return "更正";
            case "0": return "批准";
            default:;
        }
    }
}

/**
 * 解析地址 如：
 * 地址：江苏省苏州市高新区科灵路78号
 * 解析为
 * [
 * 'province' => '江苏省',
 * 'city' => '苏州市' ,
 * 'district'=>'高新区',
 * 'address'=>'科灵路78号'
 * ]
 * @author wangjiang
 */
function parse_address($address,$encoding= 'utf8'){
    $address_array  = [];
    $pos = strpos($address, '省');
    if($encoding='utf8')
        $pos /= 3;
    if($pos)
    {
        $address_array['province'] =  mb_substr($address,0,$pos+1);
        $address                   =  mb_substr($address,$pos+1);
    }

    $pos = strpos($address, '市');
    if($encoding='utf8')
        $pos /= 3;
    if($pos)
    {
        $address_array['city']     =  mb_substr($address,0,$pos+1);
        $address                   =  mb_substr($address,$pos+1);
    }
    $pos = strpos($address, '区');
    if($encoding='utf8')
        $pos /= 3;
    if($pos)
    {
        $address_array['district'] =  mb_substr($address,0,$pos+1);
        $address                   =  mb_substr($address,$pos+1);
    }
    if(!isset($address_array['district']) && isset($address_array['city']) )
    {
        $pos = strpos($address, '市');
        if($encoding='utf8')
            $pos /= 3;
        if($pos)
        {
            $address_array['district'] =  mb_substr($address,0,$pos+1);
            $address                   =  mb_substr($address,$pos+1);
        }
    }
    $address_array['address'] =$address;


    return $address_array;
}

/**
 * 格式化银行卡号
 * 4位隔开
 */
function format_bank_code($bank_code)
{
    return  implode(' ',str_split($bank_code,4));
}

/**
 * 根据管理员操作类型识别所操作的主表
 */
function get_tableinfo_from_operation_type($operation_type)
{
    switch ($operation_type)
    {
        case "11": return "hc_user_withdraw_line|uwl_id";
        case "12": return "hc_user_recharge|ur_id";
        case "13": return "hc_user_withdraw|uw_id";

        case "22": return "hc_daili_recharge_bank|drb_id";
        case "23": return "hc_daili_withdraw_bank|dwb_id";
        default:;
    }
}

/**
 * @param $key
 * @return 后台admin的相关配置文件
 */
function config($key){
    $config = require(BASE_DATA_PATH.'/config/admin.ini.php');
    return $config[$key];
}


/**
 * 显示客户特别事项状态
 */
function show_special_status($status){
    switch ($status)
    {
        case "0": return "待批准";
        case "1": return "已通过";
        case "2": return "未通过";
        default:;
    }
}

/**
 * 根据客户提出特别申请的类型返回相关信息
 * from 从哪里减，
 * to   到哪里加
 * 1 代表平台
 */
function get_application_info_by_special_type($special_type, $role='user')
{
    if($role == 'user')
    {
        switch ($special_type)
        {
            case "1": return [
                'name'=>'冻结',
                'money_type'=>'0',
                'from'=> 'hc_user_account.avaliable_deposit,hc_user_account.total_deposit',
                'to'  => 'hc_user_account.temp_deposit'
            ]; // 1 代表平台
            case "2":  return [
                'name'=>'解冻',
                'money_type'=>'1',
                'from'=> 'hc_user_account.temp_deposit',
                'to'  => 'hc_user_account.avaliable_deposit,hc_user_account.total_deposit'
            ];
            case "3":  return [
                'name'=>'转出',
                'money_type'=>'0',
                'from'=> 'hc_user_account.avaliable_deposit,hc_user_account.total_deposit',
                'to'  => '1'// 1 代表平台
            ]; //客户余额转出到平台
            case "4": return [
                'name'=>'转入',
                'money_type'=>'1',
                'from'=> '1',
                'to'  => 'hc_user_account.avaliable_deposit,hc_user_account.total_deposit'// 1 代表平台
            ];//平台转入到客户
            case "5": return [
                'name'=>'返还已得',
                'money_type'=>'0',
                'from'=> 'hc_user_account.avaliable_deposit,hc_user_account.total_deposit',
                'to'  => '2'// 2 代表售方账号 TODO
            ];
            case "6": return [
                'name'=>'获取返还',
                'money_type'=>'1',
                'from'=> '表1.字段1+表2.字段2',//+  代表多个字段之和
                'to'  => 'hc_user_account.avaliable_deposit,hc_user_account.total_deposit'// 2 代表售方账号
            ];
            default:;
        }
    }else{
        switch ($special_type)
        {
            case "1": return [
                'name'=>'冻结',
                'money_type'=>'0',
                'from'=> 'hc_daili_account.avaliable_deposit,hc_daili_account.total_deposit',
                'to'  => 'hc_user_account.temp_deposit'
            ]; // 1 代表平台
            case "2":  return [
                'name'=>'解冻',
                'money_type'=>'1',
                'from'=> 'hc_daili_account.temp_deposit',
                'to'  => 'hc_daili_account.avaliable_deposit,hc_daili_account.total_deposit'
            ];
            case "3":  return [
                'name'=>'转出',
                'money_type'=>'0',
                'from'=> 'hc_daili_account.avaliable_deposit,hc_daili_account.total_deposit',
                'to'  => '1'// 1 代表平台
            ]; //客户余额转出到平台
            case "4": return [
                'name'=>'转入',
                'money_type'=>'1',
                'from'=> '1',
                'to'  => 'hc_daili_account.avaliable_deposit,hc_daili_account.total_deposit'// 1 代表平台
            ];//平台转入到客户
            case "5": return [
                'name'=>'返还已得',
                'money_type'=>'0',
                'from'=> 'hc_daili_account.avaliable_deposit,hc_daili_account.total_deposit',
                'to'  => '2'// 2 代表售方账号 TODO
            ];
            case "6": return [
                'name'=>'获取返还',
                'money_type'=>'1',
                'from'=> '表1.字段1+表2.字段2',//+  代表多个字段之和
                'to'  => 'hc_daili_account.avaliable_deposit,hc_daili_account.total_deposit'// 2 代表售方账号
            ];
            default:;
        }
    }

}

/**
 * 显示具体操作结果
 */
function show_change_detail($detail){
    $field  = $detail['table_name'].'.'.$detail['field_name'];
    switch ($field){
        case "user_bank.bank_code":return  "<br/>银行卡号由".$detail['old_val'].',改为:'.$detail['now_val'];
        case "user_bank.bank_register_name":return  "<br/>银行开户名由:".$detail['old_val'].',改为:'.$detail['now_val'];
        case "user_bank.bank_address":return  "<br/>银行地址由: ".$detail['old_val'].',改为: '.$detail['now_val'];
        default:;
    }
}

//显示账户资金流动用户信息明细
function show_account_log_where($account_log, $where='from')
{
    $user_id = $account_log[$where.'_user_id'];
    switch ($account_log[$where.'_where']){
        case "1":  //客户
                $model  = Model('user');
                $user  =  $model->getInfo(['id'=>$user_id]);
                if($user) return '客户'.':'.$user['phone'];
            break;
        case "2":
                $model  = Model('member');
                $user =   $model->getMemberInfo(['member_id'=>$user_id]);
                if($user) return '售方'.':'.$user['member_name'];
            break;
        case "3":return "平台";
        case "4":return "外部";
    }

}

//申报类型
function show_declare_type($type)
{
    switch ($type)
    {
        case "10": return "订单收入";
        case "20":case '21': return "提现手续费";
        case "30": return "转入收入";
        case "40": return "转出支出";
        default:;
    }

}

//申报-是否开票
function show_declare_is_invoice($is_invoice)
{
    switch ($is_invoice)
    {
     
        case "0": return "";
        case "1": return "是";
        default:;
    }

}
//申报状态
function show_declare_status($status)
{
    switch ($status)
    {

        case "10": return "未申报";
        case "20": return "已申报";
        case "30": return "免申报";
        default:;
    }

}
//申报维护状态
function show_declare_maintenance_status($stat)
{
    switch ($stat)
    {

        case "0": return "未添加";
        case "1": return "已添加";
        default:;
    }
}

//申报-售方入账
function show_to_seller_account($to_seller_account)
{
    switch ($to_seller_account)
    {

        case "0": return "";
        case "1": return "是";
        default:;
    }

}

//获取当前年月
function get_now_year_month(){
    return date("Y-m",time());
}

//获取指定日期所在月的第一天和最后一天
function GetTheMonth($date){
    $firstday = date("Y-m-01 H:i:s",strtotime($date));
    $lastday = date("Y-m-d H:i:s",strtotime("$firstday +1 month"));
    return array($firstday,$lastday);
}


//获取所有操作类型
function get_all_operation_type()
{
    $model = Model('hc_type_description');

    $list = $model->getListByWhere(['table_name'=>'hc_admin_operation','field_name'=>'type'],'*','type asc');
    return $list;
}
//操作类型
function show_operation_type($type){
    $model = Model('hc_type_description');

    $type = $model->getDataByWhere([
        'type' => $type,
        'table_name' => 'hc_admin_operation',
        'field_name' => 'type'        

    ]);
    return $type['description']?$type['description']:"未知类型";

}


/**
 * 添加记录到资金总表
 */
function add_to_account_log($log)
{

}

/**
 *  添加记录到资金总表
 * $role  member 客户，dealer 售方
 * $data_type 数据类型
 */
function record_to_account_log($data, $data_type,$role='member')
{
    if(!$data ||  !is_array($data)) return false;
    $log      = [];
    

    switch ($data_type)
    {
        case "application":  //特事审批
             switch ($data['special_type']){
                 case "1":case "2": //冻结 //解冻，不做记录
                     break;
                 case "3": //转出
                     $log['from_user_id'] = $data['user_id'];
                     $log['from_remark'] = '转出支出';
                     $log['from_where'] = $data['type']=='10'?1:2;
                     $log['to_remark'] = '转出支出';
                     $log['to_where'] =  3;
                     $log['special_application_id'] = $data['id'];
                     $log['flow_type'] = 1;
                     $log['money'] = $data['money'];
                     $log['type'] = 20;
                     $log['created_at'] = get_now2();

                     break;
                 case "4": //转入
                     $log['from_remark'] = '转入收入';
                     $log['from_where'] =  3;
                     $log['to_user_id'] = $data['user_id'];
                     $log['to_remark'] = '转入收入';
                     $log['to_where'] = $data['type']=='10'?1:2;
                     $log['special_application_id'] = $data['id'];
                     $log['flow_type'] = 2;
                     $log['money'] = $data['money'];
                     $log['type'] = 10;
                     $log['created_at'] = get_now2();
                     break;
                 case "5": break;//返还已得 TODO
                 case "6": break;//获取返还 TODO
                 default:;
             }

            break;
        case "withdraw":     //提现 TODO
            if($role=='member'){

            }else{

            }
            break;
        case "fee" :         //提现手续费
            break;
    }
    

    if(!empty($log))
          Model('hc_account_log')->insert($log);
}


//php获取中文字符拼音首字母 
function getFirstCharter($str){ 
 if(empty($str)){return '';} 
 $fchar=ord($str{0}); 
 if($fchar>=ord('A')&&$fchar<=ord('z')) return strtoupper($str{0}); 
 $s1=iconv('UTF-8','gb2312',$str); 
 $s2=iconv('gb2312','UTF-8',$s1); 
 $s=$s2==$str?$s1:$str; 
 $asc=ord($s{0})*256+ord($s{1})-65536; 
 if($asc>=-20319&&$asc<=-20284) return 'A'; 
 if($asc>=-20283&&$asc<=-19776) return 'B'; 
 if($asc>=-19775&&$asc<=-19219) return 'C'; 
 if($asc>=-19218&&$asc<=-18711) return 'D'; 
 if($asc>=-18710&&$asc<=-18527) return 'E'; 
 if($asc>=-18526&&$asc<=-18240) return 'F'; 
 if($asc>=-18239&&$asc<=-17923) return 'G'; 
 if($asc>=-17922&&$asc<=-17418) return 'H'; 
 if($asc>=-17417&&$asc<=-16475) return 'J'; 
 if($asc>=-16474&&$asc<=-16213) return 'K'; 
 if($asc>=-16212&&$asc<=-15641) return 'L'; 
 if($asc>=-15640&&$asc<=-15166) return 'M'; 
 if($asc>=-15165&&$asc<=-14923) return 'N'; 
 if($asc>=-14922&&$asc<=-14915) return 'O'; 
 if($asc>=-14914&&$asc<=-14631) return 'P'; 
 if($asc>=-14630&&$asc<=-14150) return 'Q'; 
 if($asc>=-14149&&$asc<=-14091) return 'R'; 
 if($asc>=-14090&&$asc<=-13319) return 'S'; 
 if($asc>=-13318&&$asc<=-12839) return 'T'; 
 if($asc>=-12838&&$asc<=-12557) return 'W'; 
 if($asc>=-12556&&$asc<=-11848) return 'X'; 
 if($asc>=-11847&&$asc<=-11056) return 'Y'; 
 if($asc>=-11055&&$asc<=-10247) return 'Z'; 
 return null; 
} 

// 检查账号变化逻辑
function check_seller_account_by_application($old_daili_account_data, $application)
{
    //售方申请
    if(isset($application) && $application['type'] == '20')
    {
        if(strpos($application['from_where'],'daili_account.avaliable_deposit') 
        || strpos($application['to_where'],'daili_account.avaliable_deposit'))
        {
            $old_avaliable_deposit = (float)$old_daili_account_data['avaliable_deposit'];
            //金额变动
            if($application['money_type'] > 0) //用户加钱
            {
                $now_avaliable_deposit  =   $old_avaliable_deposit + (float)$application['money'];
            }else{                           //用户减钱
                $now_avaliable_deposit  =   $old_avaliable_deposit - (float)$application['money'];
            }
            
             //1. 现在余额大于0  之前余额小于0
             // 上架所有余额不足报价
             // 上架单独写api
            if( $now_avaliable_deposit >0 && $old_avaliable_deposit <0) {
                return false;
                // if(check_rest_time())
                //     $update  =  [
                //         'bj_status'  =>  1,
                //         'bj_status_change_code' => '',
                //         'bj_status_change_time' => get_now2(),
                //         'bj_reason'    =>'恢复报价',
                //     ]; 
                //     $where = [
                //         'm_id'  =>   $old_daili_account_data['d_id'],
                //         'bj_status' =>  2,
                //         'bj_status_change_code' => '',
                //         'bj_reason' => '',
                //     ];
            }

            //2. 现在余额小0 ，之前余额大于0
            //  --记录到0时间
            if($now_avaliable_deposit <=0 && $old_avaliable_deposit>0){
                    $update = [
                        'down_to_zero_time' => get_now2(),
                    ];

                    Model('hc_daili_account')->where(['d_id'=>$old_daili_account_data['d_id']])->update($update);
            }

            //3. 现在余额小于0
            //  --并检查信用额度+余额是否还是小于0
            //  --  1.小于0，下架所有报价，记录余额不足状态
            //  --  2.大于0  
            $credit_line                      =    (float) $old_avaliable_deposit['credit_line'];
            if($now_avaliable_deposit <0){
                if($now_avaliable_deposit+$credit_line <= 0)  //condition 1  //下架所有报价
                {
                    $update = [
                        'bj_reason'  =>  '资金池可提现余额不足',
                        'bj_status'  =>   2, //暂时下架
                        'bj_status_change_code' => 'hwache_baojia_money_end',
                        'bj_status_change_time' => get_now2()
                    ];   
                    $where = "bj_status=1 or (bj_status=2 and bj_reason='非销售时间')";

                    Model('hg_baojia')->where($where)->update($update);
                }
                else{
                    //condition 2
                    $down_to_zero_timestamp   =  strtotime($daili_account->down_to_zero_time);
                    $now_timestamp            =  time();
                    if( $now_timestamp - $down_to_zero_timestamp >= 60*60**72)
                    {   //下架所有报价
                        $update = [
                            'bj_reason'  =>  '资金池可提现余额不足',
                            'bj_status'  =>   2, //暂时下架
                            'bj_status_change_code' => 'hwache_baojia_money_end',
                            'bj_status_change_time' => get_now2()
                        ];   
                        $where = "bj_status=1 or (bj_status=2 and bj_reason='非销售时间')";

                        Model('hg_baojia')->where($where)->update($update);
                    }
                }
            }


        }
        
    }
    
}


function get_files($file_string)
{
    $files_array = [];
    $files = array_filter(explode(",",$file_string));
    if(!empty($files))
    {
        foreach($files as $file_id)
        {
            $file    =  Model('hc_images')->where([ 'id' => $file_id ])->find();  
            if($file)  $files_array[] =  $file ; 
        }
    }  

    return $files_array; 
}

function show_target($type)
{
    switch($type)
    {
        case 1: return "客户";
        case 2: return "售方";
        case 3: return "平台";
        default:;
    }
}