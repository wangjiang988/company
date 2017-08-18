<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/6
 * Time: 16:59
 */
defined('InHG') or exit('Access Invalid!');
class hc_vouchersModel extends Model
{
    const GROUP_TALBE   = 'hc_vouchers_group';
    CONST RELEASE_TABLE = 'hc_vouchers_release';//投放表
    const VOUCHERS_TABLE = 'hc_vouchers';
    const USERS_TABLE    = 'users';
    const END_HOUR       = ' 23:59:59';
    protected $tableName = null;
    public function __construct()
    {
        parent::__construct('hc_vouchers');
    }

    public function setTable($table=null)
    {
        $tabeList = implode(',',[self::GROUP_TALBE,self::RELEASE_TABLE]);
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

    /** 获取所有列表
     * @param $table
     * @param array $condition
     * @param string $fields
     * @param null $join
     * @param null $on
     * @param null $order
     * @param null $group
     * @param null $having
     * @return mixed
     */
    public function getAll($table,$condition = array(), $fields = '*', $join=null , $on=null , $order = null , $group = null, $having = null)
    {
        return $this->table($table)->field($fields)->where($condition)
            ->join($join)->on($on)
            ->order($order)
            ->group($group)
            ->having($having)
            ->select();
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
     * @param int $type 是否激活的类型判断
     * @param $data
     * @return int
     */
    public function addGroup($post,$type=1)
    {
        $data = [
            'type'             => intval($post['type']), //'类别(通用[0]、品类,1)',
            'activated_type'   => $type, //'激活类型（0免激活，1需激活）',
            'brand_id'         => intval($post['brand_id']), //'品牌id',
            'series_id'        => intval($post['series_id']), //'车系列id',
            'model_id'         => intval($post['model_id']), //'车型id',
            'life_start_time'  => isset($post['life_start_time']) ? $post['life_start_time'] : '', //'有效期开始时间',
            'life_start_hour'  => isset($post['life_start_hour']) ? $post['life_start_hour'] : '', //'开始-小时',
            'life_end_time'    => isset($post['life_end_time']) ? $post['life_end_time'] : '', // datetime'有效期截止时间',
            'life_end_hour'    => isset($post['life_end_hour']) ? $post['life_end_hour'] : '', //'截止小时',
            'use_collateral'   => intval($post['use_collateral']), //'用途（支付担保金）',
            'use_sincerity'    => intval($post['use_sincerity']), //'用途(支付诚意金)',
            'collateral_money' => intval($post['collateral_money']), //'担保金面值',
            'sincerity_money'  => intval($post['sincerity_money']), //'诚意金面值',
            'ignore_collateral_temp' => '', // varchar(100)'免激活担保金模板',
            'ignore_sincerity_temp'  => '', // varchar(100)'免激活诚意金模板',
            'activated_num'          => intval($post['activated_num']), //'(激活码位数)【免激活=0】',
            'activated_rule'         => intval($post['activated_rule']), //'激活规则(1全数字,2全小写字母,3全大写字母,4前两位大写字母+数字,5前两位大写字母+数字+末尾两位大写字母)',
            'activated_start_time'   => isset($post['activated_start_time']) ? $post['activated_start_time'] : '', // datetime '激活有效期开始时间',
            'activated_end_time'     => isset($post['activated_end_time']) ? $post['activated_end_time'] : '', // datetime'激活有效期截止时间',
            'activated_code'         => '', // varchar(60)'激活码',
            'activated_total_num'    => intval($post['activated_total_num']), //'激活条数',
            'can_release_num'        => ($post['use_collateral'] + $post['use_sincerity']) * $post['activated_total_num'],
            'remark'                 => isset($post['remark']) ? $post['remark'] : '', // varchar(255) '备注',
            'created_at'             => get_now2(), // timestamp'新增时间',
            'updated_at'             => get_now2(), // timestamp'最后更新时间'
            'admin_id'               => intval($post['admin_id'])
        ];
        if($_FILES['ignore_collateral_temp']['name']){
           $data['ignore_collateral_temp'] = upOneFile('ignore_collateral_temp');
        }
        if($_FILES['ignore_sincerity_temp']['name']){
            $data['ignore_sincerity_temp'] = upOneFile('ignore_sincerity_temp');
        }
        return $this->saveData(self::GROUP_TALBE,$data);
    }

    /** 添加投放
     * @param $post
     * @param $group_id
     * @return int
     */
    public function addRelesase($post,$group_id)
    {
        $data=[
            'group_id'      => $group_id,//'投放对象组id',
            'activated_type'=> $post['activated_type'],//'激活类型(0免激活,1需激活)',
            'release_total_num' => intval($post['release_total_num']),//'投放数量',
            'release_object'=> intval($post['release_object']),//'投放对象(0内部，1代理)',
            'proxy_name'    => $post['proxy_name'] ? $post['proxy_name'] : '',//varchar(60) '代理人',
            'guide_dept'    => $post['guide_dept'] ? $post['guide_dept'] : '',// varchar(60) '指导部门',
            'guide_name'    => $post['guide_name'] ? $post['guide_name'] : '',// varchar(60) '指导人',
            'd_dept'        => $post['d_dept'] ? $post['d_dept'] : '',// varchar(60) NOT NULL DEFAULT '' COMMENT '代理指导部门',
            'd_guide_name'  => $post['d_guide_name'] ? $post['d_guide_name'] : '',// varchar(60) NOT NULL DEFAULT '' COMMENT '代理指导人',
            'reward_type'   => intval($post['reward_type']),//'0无，1，百分百，2固定金额',
            'reward_money'  => (int) $post['reward_money'.$post['reward_type']],//'奖励金额(根据类型判断)',
            'ignore_object' => intval($post['ignore_object']),//'免激活投放对象(0所有客户,1特定客户,2一年内未买车的客户,3三个月内新注册并且未买车的用户)',
            'ignore_users'  => isset($post['users']) ? $post['users'] : '',// varchar(255) NOT NULL DEFAULT '' COMMENT '免激活-指定用户组',
            'province'      => (int) $post['province'],// '投放区域-省、直辖市',
            'city'          => (int) $post['city'],//'投放区域-城市',
            'ignore_time_type' => intval($post['ignore_time_type']),//'投放时间类型（0批准后立即投放,1批准后定时投放）',
            'fixed_start_time' => $post['fixed_start_time'] ? $post['fixed_start_time'] : '',//'定时投放开始时间',
            'fixe_hour_time'   => $post['fixe_hour_time'] ? setHour($post['fixe_hour_time']) : '00:00:00',//'定时投放小时',
            'remark'           => $post['remark'] ? $post['remark'] : '',// varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
            'status'           => intval($post['status']),//'投放状态（0待批准,1审核,2未批准,3待投放,4已投放,5已失效）',
            'created_at'       => get_now2(),//
            'updated_at'       => get_now2(),
            'admin_id'         => intval($post['admin_id'])
        ];
        $res = $this->saveData(self::RELEASE_TABLE,$data);
        if($res){
            //更新组投放状态及投放数量
        }
        return $res;
    }

    /** 获取投放总数
     * @param string $type
     * @return mixed
     */
    public function getYearNotUserOrder($type='year',$count=true){
        //一年内未买车的客户 year(日期字段)=year(curdate())
        if($type == 'year'){
            $where = [
                'hc_order.updated_at' =>['exp',"year(hc_order.updated_at)=year(curdate())"],
                'hc_order.order_status'=> 99
                ///'hc_order.order_state' =>
            ];
        }else{//三个月内新注册并且未买车的用户
            $where = [
                'users.created_at' =>['exp',"month(users.created_at)+3=month(curdate())"],
                'hc_order.order_status'=> ['neq',99]
                ///'hc_order.order_state' =>
            ];
        }
        if($count){
            return $this->table('users,hc_order')
                ->where($where)
                ->join('left')
                ->on('users.id=hc_order.user_id')
                ->count('DISTINCT users.id');
        }else{
            return $this->table('users,hc_order')
                ->where($where)
                ->field('DISTINCT users.id as id')
                ->join('left')
                ->on('users.id=hc_order.user_id')
                ->select();
        }
    }
    /**
     * 批量生成代金券
     * @param $group_id
     */
    public function getGroupFind($group_id,$task=true,$_col='*')
    {
        $fields = "id as group_id,use_collateral,use_sincerity,collateral_money,sincerity_money,activated_total_num,life_start_time,";
        $fields .="life_start_hour,life_end_time,life_end_hour,activated_type,activated_code,activated_rule,activated_num,can_release_num";
        $col = $task ? $fields : $_col;
        return $this->getFind(self::GROUP_TALBE,['id'=>$group_id],$col);
        $use_collateral = intval($param['use_collateral']);
        $use_sincerity  = intval($param['use_sincerity']);
        if($use_collateral && $param['collateral_money']>0){
            $task['collateral'] = [
                'total' => $param['activated_total_num'],
                'data'  => $data
            ];
        }
        if($use_sincerity && $param['sincerity_money']>0){
            $task['sincerity'] = [
                'total' => $param['activated_total_num'],
                'data'  => $data
            ];
        }
        return $task;
    }

    /**
     * 批量生成激活码
     * @param $param
     * $toatl 投放条数
     * @return mixed
     */
    public function addAllVoucher($param,$total=0)
    {
        $data = [
            'release_id'       => intval($param['release_id']),
            'activated_type'   => $param['activated_type'],
            'ignore_object'    => isset($param['ignore_object']) ? intval($param['ignore_object']) : 0,
            'release_total'    => isset($param['release_total']) ? intval($param['ignore_object']) : 0,
            'use_collateral'   => intval($param['use_collateral']),
            'use_sincerity'    => intval($param['use_sincerity']),
            'sincerity_money'  => $param['sincerity_money'],
            'collateral_money' => $param['collateral_money'],
            'life_start_time'  => $param['life_start_time'],
            'life_start_hour'  => $param['life_start_hour'],
            'parent_sn'        => '',
            'group_id'         => $param['group_id'],
            'ignore_users'     => isset($param['ignore_users']) ? $param['ignore_users'] : $param['ignore_users'],
            'can_release_num'  => $param['can_release_num'],
            'activated_rule'   => $param['activated_rule'],
            'activated_num'    => $param['activated_num']
        ];
        $use_collateral = intval($param['use_collateral']);
        $use_sincerity  = intval($param['use_sincerity']);
        $insertAll = [];

        $totalNum = $total ? $total : $param['activated_total_num']*($use_collateral+$use_sincerity);
        $splitTnum = setTotalSplit($totalNum,$use_collateral+$use_sincerity);
        if($use_collateral && $param['collateral_money']>0){
            for($i=0 ; $i< $splitTnum[0] ; $i++){
                $insertAll[] = $this->setOneValues($data,'use_collateral');
            }
        }
        if($use_sincerity && $param['sincerity_money']>0){
            for($j=0 ; $j< $splitTnum[1] ; $j++){
                $insertAll[] = $this->setOneValues($data,'use_sincerity');
            }
        }
       return $this->saveVoucher($insertAll);
    }
    /** 生成单个代金券
     * @param $group_id
     * @param $money
     * @param $life_start_time
     * @param $life_end_time
     * @param int $activated_type
     * @param string $activated_code
     * @return mixed
     */
    public function saveVoucher(array $values){
        $fields  = "`group_id`,`release_id`,`voucher_sn`,`parent_sn`,`money`,`life_start_time`,`life_end_time`,";
        $fields .= "`activated_type`,`activated_code`,`activated`,`user_id`,`status`,`created_at`,`updated_at`,`use`";
        $_values  = implode(',',$values);
        $sql = sprintf("INSERT INTO car_%s (%s) values %s",self::VOUCHERS_TABLE,$fields,$_values);
        return DB::execute($sql);
    }

    /** 设置批量插入的数据
     * @param array $v
     * @return string
     */
    private function setValues(array $v)
    {
        $value = [];
        $life_start_time = $v['life_start_time'] ? $v['life_start_time'].' '.$v['life_start_hour'] : '';
        $life_end_time   = $v['life_end_time'] ? $v['life_end_time'].' '.$v['life_end_hour'] : '';
        $user_id = (int) $v['user_id'];
        $activated_code = ($v['activated_type']) ? getActivatedCode($v['activated_rule'],$v['activated_num']) : '';
        $status = intval($v['status']);
        $thisTime = get_now2();
        if($v['use_collateral'] && $v['collateral_money']>0){
            $money = intval($v['collateral_money']);
            $_value = "({$v['group_id']},{$v['release_id']},uuid(),'{$v['parent_sn']}',{$money},'{$life_start_time}','{$life_end_time}',";
            $_value .="{$v['activated_type']},'{$activated_code}',0,{$user_id},{$status},'{$thisTime}','{$thisTime}',1)";
            $value[] = $_value;
        }
        if($v['use_sincerity'] && $v['sincerity_money']>0){
            $money = intval($v['sincerity_money']);
            $valueStr = "({$v['group_id']},{$v['release_id']},uuid(),'{$v['parent_sn']}',{$money},'{$life_start_time}','{$life_end_time}',";
            $valueStr .="{$v['activated_type']},'{$activated_code}',0,{$user_id},{$status},'{$thisTime}','{$thisTime}',0)";
            $value[] = $valueStr;
        }
        $newValues = implode(',',$value);
        return $newValues;
    }

    private function setOneValues(array $v,$type){
        $value = [];
        $life_start_time = $v['life_start_time'] ? $v['life_start_time'].' '.$v['life_start_hour'] : '';
        $life_end_time   = $v['life_end_time'] ? $v['life_end_time'].' '.$v['life_end_hour'] : '';
        $user_id = (int) $v['user_id'];
        $activated_code = ($v['activated_type']) ? getActivatedCode($v['activated_rule'],$v['activated_num']) : '';
        $status = intval($v['status']);
        $thisTime = get_now2();
        if($type=='use_collateral'){
            if($v['use_collateral'] && $v['collateral_money']>0){
                $money = intval($v['collateral_money']);
                $value = "({$v['group_id']},{$v['release_id']},uuid(),'{$v['parent_sn']}',{$money},'{$life_start_time}','{$life_end_time}',";
                $value .="{$v['activated_type']},'{$activated_code}',0,{$user_id},{$status},'{$thisTime}','{$thisTime}',1)";
            }
        }else{
            if($v['use_sincerity'] && $v['sincerity_money']>0){
                $_money = intval($v['sincerity_money']);
                $value = "({$v['group_id']},{$v['release_id']},uuid(),'{$v['parent_sn']}',{$_money},'{$life_start_time}','{$life_end_time}',";
                $value .="{$v['activated_type']},'{$activated_code}',0,{$user_id},{$status},'{$thisTime}','{$thisTime}',0)";
            }
        }
        return $value;
    }

    /** 代金券列表
     * @param $param
     * @return array
     */
    public function getVouchersList($param)
    {
        $where  = [];
        $search = $param;
        $likeSource = $this->setSourceWehre($param['source'],$param['source_sn']);
        $whereLike= $this->setLikeWhere(array_merge([
            self::VOUCHERS_TABLE.'.voucher_sn' => trim($param['voucher_sn']),
            self::RELEASE_TABLE.'.id'          => trim($param['id']),
            //self::VOUCHERS_TABLE.'.group_id'   => trim($param['group_id']),
            self::RELEASE_TABLE.'.guide_name'    => $param['proxy_name'],
            self::USERS_TABLE.'.id'            => $param['user_name'],
            self::USERS_TABLE.'.phone'         => $param['user_mobile']
        ],$likeSource));
        if(count($whereLike) > 0) $where = array_merge($where,$whereLike);

        #------------
        $startEndWhere = $this->setStartToEnd([
            self::RELEASE_TABLE.'.release_total' => [$param['release_total1'],$param['release_total2']],
            self::RELEASE_TABLE.'.created_at'    => [$param['created_at1'],$param['created_at2'],self::END_HOUR]
            //[$param['settlement1'],$param['settlement1']]结算金额
            //[$param['settlement_time1'],$param['settlement_time2']]结算时间
        ]);
        if(count($startEndWhere) > 0)  $where  = (count($where) >0) ? array_merge($where,$startEndWhere) : $startEndWhere;
        #------------
        $operation[self::RELEASE_TABLE.'.activated_type'] = ['eq',$param['activated_type']];
        switch($param['use_vouchers']){
            case 1:
                $operation[self::GROUP_TALBE.'.use_collateral'] = ['eq',0];
                $operation[self::GROUP_TALBE.'.use_sincerity'] = ['eq',1];
                break;
            case 2:
                $operation[self::GROUP_TALBE.'.use_collateral'] = ['eq',1];
                $operation[self::GROUP_TALBE.'.use_sincerity'] = ['eq',0];
                break;
            case 3:
                $operation[self::GROUP_TALBE.'.use_collateral'] = ['eq',1];
                $operation[self::GROUP_TALBE.'.use_sincerity'] = ['eq',1];
                break;
        }
        if($param['reward_type']) $operation[self::RELEASE_TABLE.'.reward_type'] = ['eq',$param['reward_type']-1];//类别
        if($param['status']) $operation[self::VOUCHERS_TABLE.'.status'] = ['eq',$param['status']-1];//状态
        if($param['type']) $operation[self::GROUP_TALBE.'.type'] = ['eq',$param['type']-1];
        if($param['release_object']){
            $operation[self::VOUCHERS_TABLE.'.release_id'] = ['gt',0];
            if($param['release_object'] ==3){
                $operation[self::RELEASE_TABLE.'.release_object'] = ['eq',1];
                $operation[self::RELEASE_TABLE.'.d_dept'] = ['neq',''];
            }else{
                $operation[self::RELEASE_TABLE.'.release_object'] = ['eq',$param['release_object']-1];
            }
        }
        switch($param['source']){
            case 1://激活码
                $where[self::VOUCHERS_TABLE.'.activated_type'] = ['eq',1];
                break;
            case 2://母代金券
                $where[self::VOUCHERS_TABLE.'.parent_sn'] = ['neq',''];
                break;
            case 3://免激活
                $where[self::VOUCHERS_TABLE.'.activated_type'] = ['eq',0];
                break;
            case 4://平台
                $where[self::VOUCHERS_TABLE.'.activated_type'] = ['eq',2];
                break;
        }
        if($param['source_sn'] !=''){
            $_table = self::VOUCHERS_TABLE;
            $where['parent_sn'] = ['exp',"{$_table}.parent_sn like '%{$param['source_sn']}%' or {$_table}.group_id like '%{$param['source_sn']}%'"];
        }
        $operation[self::GROUP_TALBE.'.brand_id'] = ['eq',$param['brand_id']];//类别
        $operation[self::GROUP_TALBE.'.series_id'] = ['eq',$param['series_id']];
        $operation[self::GROUP_TALBE.'.model_id'] = ['eq',$param['model_id']];
        $operation[self::RELEASE_TABLE.'.province'] = ['eq',$param['province']];
        $operation[self::RELEASE_TABLE.'.city'] = ['eq',$param['city']];
        $operationWhere = $this->setOperationWhere($operation);
        if(count($operationWhere) > 0) $where = array_merge($where,$operationWhere);

        $this->setTable(implode(',',[self::VOUCHERS_TABLE , self::GROUP_TALBE , self::RELEASE_TABLE , self::USERS_TABLE]));
        $field = sprintf("
        %s.*,%s.type,%s.use_collateral,%s.use_sincerity,%s.collateral_money,%s.sincerity_money,
        %s.id as tf_id,%s.province,%s.city,%s.activated_type,%s.ignore_object,%s.ignore_users
        ",
            self::VOUCHERS_TABLE , self::GROUP_TALBE , self::GROUP_TALBE , self::GROUP_TALBE ,
            self::GROUP_TALBE , self::GROUP_TALBE , self::RELEASE_TABLE , self::RELEASE_TABLE ,
            self::RELEASE_TABLE , self::RELEASE_TABLE , self::RELEASE_TABLE , self::RELEASE_TABLE
        );
        $on = sprintf("%s.group_id=%s.id , %s.id = %s.release_id , %s.user_id=%s.id",self::VOUCHERS_TABLE,self::GROUP_TALBE,
            self::RELEASE_TABLE,self::VOUCHERS_TABLE,
            self::VOUCHERS_TABLE,self::USERS_TABLE);
        $result = $this->getPageList($where,$field,'left,left',$on,self::VOUCHERS_TABLE.'.id desc');

        $search['use_vouchers']   = $param['use_vouchers'] ? $param['use_vouchers'] : '9';
        $search['reward_type']    = $param['reward_type'] ? $param['reward_type'] : '9';
        $search['status']         = $param['status'] ? $param['status'] : '9';
        $search['type']           = $param['type'] ? $param['type'] : '9';
        $search['source']         = $param['source'] ? $param['source'] : '9';
        $search['brand_id']       = $param['brand_id'] ? $param['brand_id'] : '9';
        $search['series_id']      = $param['series_id'] ? $param['series_id'] : '9';
        $search['model_id']       = $param['model_id'] ? $param['model_id'] : '9';
        $search['release_object'] = $param['release_object'] ? $param['release_object'] : '9';
        $search['province']       = $param['province'] ? $param['province'] : '0';
        $search['city']           = $param['city'] ? $param['city'] : '0';
        $search['sp_province']    = $param['sp_province'] ? $param['sp_province'] : '0';
        $search['sp_city']        = $param['sp_city'] ? $param['sp_city'] : '0';
        return ['list'=>$result['list'],'page'=>$result['page'],'search'=>$search];
    }

    /** 设置激活条件
     * @param int $source
     * @param string $source_sn
     * @return array
     */
    private function setSourceWehre($source=0,$source_sn='')
    {
        switch($source){
            case 1://激活码
            case 3://免激活
            case 4://平台
                $where = [self::VOUCHERS_TABLE.'.release_id' => $source_sn];
                break;
            case 2://母代金券
                $where = [self::VOUCHERS_TABLE.'.parent_sn' => $source_sn];
                break;
            default:
                $where = [self::VOUCHERS_TABLE.'.parent_sn'=>''];
        }
        return $where;
    }
    /**
     * @param $id
     * @return mixed 代金券详情
     */
    public function getVoucherFind($id)
    {
        $table = implode(',',[self::VOUCHERS_TABLE , self::GROUP_TALBE , self::RELEASE_TABLE]);
        $_field = "
        t_v.*,t_g.type,t_g.use_collateral,t_g.use_sincerity,t_g.collateral_money,t_g.sincerity_money,t_g.brand_id,t_g.series_id,t_g.model_id,
        t_r.id as tf_id,t_r.province,t_r.city,t_r.ignore_object,t_r.ignore_users,t_r.release_object,t_r.guide_dept,t_r.guide_name,t_r.proxy_name,
        t_r.d_dept,t_r.d_guide_name,t_r.reward_type,t_r.reward_money,t_r.remark as tf_remark,t_r.id as tf_id,t_r.admin_id as tf_admin,t_r.updated_at as tf_time
        ";
        $field = str_replace(['t_v','t_g','t_r'],[self::VOUCHERS_TABLE , self::GROUP_TALBE , self::RELEASE_TABLE],$_field);
        $where = [self::VOUCHERS_TABLE.'.id'=>$id];
        $on = sprintf("%s.group_id=%s.id , %s.group_id=%s.id",self::VOUCHERS_TABLE,self::GROUP_TALBE,self::RELEASE_TABLE,self::GROUP_TALBE);
        return $this->getFind($table,$where,$field,'left,left',$on);
    }

    public function getGroupList($param)
    {
        $where  = [];
        $search = $param;
        $whereLike= $this->setLikeWhere([
            'remark' => trim($param['remark']),
            'id'     => trim($param['id'])
        ]);
        if(count($whereLike) > 0) $where = array_merge($where,$whereLike);
        #------------life_start_time1
        $startEndWhere = $this->setStartToEnd([
            'can_release_num'     => [$param['release_num1'],$param['release_num2']],
            'created_at'          => [$param['created_at1'],$param['created_at2'],self::END_HOUR],
            'life_start_time'     => [$param['life_start_time1'],$param['life_start_time2'],self::END_HOUR],
            'life_end_time'       => [$param['life_end_time1'],$param['life_end_time2'],self::END_HOUR],
        ]);
        if(count($startEndWhere) > 0)  $where  = (count($where) >0) ? array_merge($where,$startEndWhere) : $startEndWhere;
        #------------
        if($param['activated_type']) $operation['activated_type'] = ['eq',intval($param['activated_type']-1)];
        if($param['type']) $operation['type'] = ['eq',$param['type']-1];//类别
        $operation['brand_id']  = ['eq',$param['brand_id']];//类别
        $operation['series_id'] = ['eq',$param['series_id']];
        $operation['model_id']  = ['eq',$param['model_id']];
        switch($param['use_vouchers']){
            case 1:
                $operation['use_collateral'] = ['eq',0];
                $operation['use_sincerity'] = ['eq',1];
                break;
            case 2:
                $operation['use_collateral'] = ['eq',1];
                $operation['use_sincerity'] = ['eq',0];
                break;
            case 3:
                $operation['use_collateral'] = ['eq',1];
                $operation['use_sincerity'] = ['eq',1];
                break;
        }
        $operationWhere = $this->setOperationWhere($operation);
        if(count($operationWhere) > 0) $where = array_merge($where,$operationWhere);
        if($param['group_num1'] || $param['group_num2']){
            $total_col = "activated_total_num*(.use_collateral+use_sincerity)";
            if ($param['group_num1'] !='' && $param['group_num2'] ==''){
                $_wehreStr = $total_col." >=".$param['group_num1'];
            }
            if ($param['group_num2'] !='' && $param['group_num1'] ==''){
                $_wehreStr = $total_col." <=".$param['group_num2'];
            }
            if ($param['group_num2'] !='' && $param['group_num1'] !=''){
                $_wehreStr = sprintf("%s BETWEEN '%s' AND '%s'",$total_col,$param['group_num1'],$param['group_num2']);
            }
            $_wehreStr .= " or activated_type =0";
            $where[$group.'.activated_total_num'] = ['exp',$_wehreStr];
        }

        switch($param['is_release']){
            case 1://未投放
                $where['release_total'] = ['exp',"release_total=0 and status<>2"];
                break;
            case 2://部分投放
                $where['release_total'] = ['exp',"(use_collateral+use_sincerity*activated_total_num)-release_total>0 and status<>2 and release_total>0"];
                break;
            case 3://全部投放
                $where['release_total'] = ['exp',"(use_collateral+use_sincerity*activated_total_num)-release_total=0 and release_total>0"];
                break;
            case 4://已失效
                $where['status'] = ['eq',2];
                break;
        }

        $this->setTable(self::GROUP_TALBE);
        $result = $this->getPageList($where , '*' , null , null , 'id desc');
        $search['activated_type'] = $param['activated_type'] ? $param['activated_type'] : 9;
        $search['use_vouchers']   = $param['use_vouchers'] ? $param['use_vouchers'] : 9;
        $search['is_release']     = $param['is_release'] ? $param['is_release'] : 9;
        $search['type']           = $param['type'] ? $param['type'] : 9;
        $search['brand_id']       = $param['brand_id'] ? $param['brand_id'] : 'a';
        $search['series_id']      = $param['series_id'] ? $param['series_id'] : 'b';
        $search['model_id']       = $param['model_id'] ? $param['model_id'] : 'c';
        return ['list'=>$result['list'],'page'=>$result['page'],'search'=>$search];
    }

    /** 修改状态
     * @param int $status
     * @param $where
     * @param int $tableType
     * @return int
     */
    public function setStatus($status=0,$id,$tableType=2,$admin,$remarkContent='')
    {
        $tableArr = [self::VOUCHERS_TABLE,self::GROUP_TALBE,self::RELEASE_TABLE];
        $tableName = $tableArr[$tableType];
        $update = ['status'=>$status,'updated_at'=>get_now2()];
        $where = ['id'=>$id];
        $find = $this->table($tableName)->where($where)->find();
        switch($tableType){
            case 2:
                if($find['activated_type'] ==0) {//免激活投放数量
                    $useNum = $this->geUseNum($find['group_id']);
                    $release_total = $this->getReleaseTotal($find['ignore_object'], $find['ignore_users']);
                    $update['release_total_num'] = $release_total * $useNum;
                }
                //TODO[new] 失效和未批准需要更新已占用条数和可投放条数
                $statusRemark = [1=>'审批人:',2=>'投放人:',3=>'失效人:',4=>'未批准人:'];
                $remark = empty($remarkContent) ? $statusRemark[$status] : $remarkContent;
                break;
            case 1:
                $remark =$remarkContent;
                break;
        }
        //记录工单
        $operation['user_id']    = $admin['id'];
        $operation['user_name']  = $admin['name'];
        $operation['related_id'] = $id;
        $operation['type']       = $this->setStatusType($tableType);
        $operation['related']    = $tableName."|".$id;
        $operation['step']       = $status;
        $operation['operation']  = "将".$tableName."表的id为".$id .'的状态由' .$find['status'].'转为'.$status;
        $operation['remark']     = $remark;
        return $this->table($tableName)->update_with_record($where, $update, $find, $operation);
    }

    public function setStatusType($tableType)
    {
        $tableArr = [43,42,41];
        return $tableArr[$tableType];
    }

    /** 查看代金组的使用数
     * @param $group_id
     * @return mixed
     */
    private function geUseNum($group_id)
    {
        $groupUse = $this->table(self::GROUP_TALBE)->field("use_collateral+use_sincerity as use_num")->where(['id'=>$group_id])->find();
        return $groupUse['use_num'];
    }

    /** 查看日志
     * @param $id
     * @return mixed
     */
    public function getAdminoPeration($id,$type=41)
    {
        return $this->getAll('hc_admin_operation',
            ['related_id'=>$id,'type'=>$type],
            'DISTINCT related_id,created_at,remark,user_name,user_id,step',
            null,
            null,
            'step asc'
        );
    }
    
    /** 投放列表
     * @param $param
     * @return array
     */
    public function getReleaseList($param)
    {
        $where = [];
        $search = $param;
        $whereLike= $this->setLikeWhere([
            self::RELEASE_TABLE.'.group_id' => trim($param['group_id']),
            self::RELEASE_TABLE.'.id'       => trim($param['id']),
            self::GROUP_TALBE.'.activated_code' => $param['activated_code']
        ]);
        if(count($whereLike) > 0) $where = array_merge($where,$whereLike);
        #------------
        $startEndWhere = $this->setStartToEnd([
            self::RELEASE_TABLE.'.release_total' => [$param['release_total1'],$param['release_total2']],
            self::RELEASE_TABLE.'.created_at'    => [$param['created_at1'],$param['created_at2'],self::END_HOUR]
        ]);
        if(count($startEndWhere) > 0)  $where  = (count($where) >0) ? array_merge($where,$startEndWhere) : $startEndWhere;
        #------------
        $operation[self::RELEASE_TABLE.'.activated_type'] = ['eq',$param['activated_type']];
        switch($param['use_vouchers']){
            case 1:
                $operation[self::GROUP_TALBE.'.use_collateral'] = ['eq',0];
                $operation[self::GROUP_TALBE.'.use_sincerity'] = ['eq',1];
                break;
            case 2:
                $operation[self::GROUP_TALBE.'.use_collateral'] = ['eq',1];
                $operation[self::GROUP_TALBE.'.use_sincerity'] = ['eq',0];
                break;
            case 3:
                $operation[self::GROUP_TALBE.'.use_collateral'] = ['eq',1];
                $operation[self::GROUP_TALBE.'.use_sincerity'] = ['eq',1];
                break;
        }
        if($param['reward_type']) $operation[self::RELEASE_TABLE.'.reward_type'] = ['eq',$param['reward_type']-1];//类别
        if($param['status']) $operation[self::RELEASE_TABLE.'.status'] = ['eq',$param['status']-1];//状态
        if($param['type']) $operation[self::GROUP_TALBE.'.type'] = ['eq',$param['type']-1];
        if($param['ignore_object'] >1){
            $operation[self::RELEASE_TABLE.'.ignore_object'] = ['eq',$param['ignore_object']-1];
        }
        if($param['release_object'] >1){
            $operation[self::RELEASE_TABLE.'.release_object'] = ['eq',$param['release_object']-1];
        }
        $operation[self::GROUP_TALBE.'.brand_id'] = ['eq',$param['brand_id']];//类别
        $operation[self::GROUP_TALBE.'.series_id'] = ['eq',$param['series_id']];
        $operation[self::GROUP_TALBE.'.model_id'] = ['eq',$param['model_id']];
        $operation[self::RELEASE_TABLE.'.province'] = ['eq',$param['province']];
        $operation[self::RELEASE_TABLE.'.city'] = ['eq',$param['city']];
        $operationWhere = $this->setOperationWhere($operation);
        if(count($operationWhere) > 0) $where = array_merge($where,$operationWhere);

        $this->setTable(implode(',',[self::RELEASE_TABLE,self::GROUP_TALBE]));
        $on     = sprintf("%s.id=%s.group_id",self::GROUP_TALBE,self::RELEASE_TABLE);
        $field  = sprintf("%s.*,%s.*",self::GROUP_TALBE,self::RELEASE_TABLE);
        $result = $this->getPageList($where,$field,'left',$on,self::RELEASE_TABLE.'.id desc');

        $search['use_vouchers']   = $param['use_vouchers'] ? $param['use_vouchers'] : '9';
        $search['reward_type']    = $param['reward_type'] ? $param['reward_type'] : '9';
        $search['status']         = $param['status'] ? $param['status'] : '9';
        $search['type']           = $param['type'] ? $param['type'] : '9';
        $search['brand_id']       = $param['brand_id'] ? $param['brand_id'] : '9';
        $search['series_id']      = $param['series_id'] ? $param['series_id'] : '9';
        $search['model_id']       = $param['model_id'] ? $param['model_id'] : '9';
        $search['ignore_object'] = $param['ignore_object'] ? $param['ignore_object'] : '9';
        $search['release_object'] = $param['release_object'] ? $param['release_object'] : '9';
        $search['province']       = $param['province'] ? $param['province'] : '0';
        $search['city']           = $param['city'] ? $param['city'] : '0';
        return ['list'=>$result['list'],'page'=>$result['page'],'search'=>$search];
    }

    /** 投放详情
     * @param $id
     * @return mixed
     */
    public function getReleaseFind($id,$col=null){
        $where[self::RELEASE_TABLE.'.id'] = $id;
        $groupTable = self::GROUP_TALBE;
        if(is_null($col)) {
            $groupCol = [
                'type', 'activated_type', 'brand_id', 'series_id', 'model_id', 'life_start_time', 'life_start_hour', 'life_end_time',
                'life_end_hour', 'use_collateral', 'use_sincerity', 'collateral_money', 'sincerity_money', 'ignore_collateral_temp',
                'ignore_sincerity_temp', 'activated_num', 'activated_rule', 'activated_start_time', 'activated_end_time', 'activated_code',
                'activated_total_num', 'remark as group_remark', 'created_at as add_time', 'updated_at as last_time', 'can_release_num',
                'status as g_status', 'release_total', 'admin_id as sys_id'
            ];
            $fields = $groupTable . '.' . implode(',' . $groupTable . '.', $groupCol);
            $field = sprintf("%s.*,{$fields}", self::RELEASE_TABLE);
        }else{
            $field = $col;
        }
        $table  = implode(',',[self::RELEASE_TABLE,self::GROUP_TALBE]);
        $on     = sprintf("%s.id=%s.group_id",self::GROUP_TALBE,self::RELEASE_TABLE);
        $result = $this->getFind($table,$where,$field,'left',$on,self::RELEASE_TABLE.'.id desc');
        return $result;
    }

    /** 激活详情
     * @param $id
     * @return mixed
     */
    public function getActivatedFind($id)
    {
        $where[self::RELEASE_TABLE.'.id'] = $id;
        $table = implode(',',[self::RELEASE_TABLE,self::GROUP_TALBE,self::VOUCHERS_TABLE]);
        $on     = sprintf("%s.group_id=%s.id,%s.group_id=%s.group_id",self::RELEASE_TABLE,self::GROUP_TALBE,self::VOUCHERS_TABLE,self::RELEASE_TABLE);

        $countCol = " (select count(*) from car_hc_vouchers where activated=1 and release_id={$id}) as activated_total,";
	    $countCol .="ROUND((select count(*) from car_hc_vouchers where activated=1 and release_id={$id}) /release_total_num * 100) AS activated_rate";

        $field  = sprintf(
            "%s.id,%s.release_total_num,%s.life_start_time,%s.life_end_time,%s",
            self::RELEASE_TABLE,self::RELEASE_TABLE,self::GROUP_TALBE,self::GROUP_TALBE,$countCol
        );
        $result = $this->getFind($table,$where,$field,'left,left',$on,self::RELEASE_TABLE.'.id desc');
        return $result;
    }

    /** 查看激活码
     * @param $id
     */
    public function getReleaseActivated($id,$param=null)
    {
        $where[self::RELEASE_TABLE.'.id'] = $id;
        if(!is_null($param)){
            if($param['activated']){
                $where[self::VOUCHERS_TABLE.'.activated'] = ['eq',$param['activated']-1];
            }
            if($param['code']){
                $where[self::VOUCHERS_TABLE.'.activated_code'] = ['like','%'.$param['code'].'%'];
            }
        }
        $table = implode(',',[self::RELEASE_TABLE,self::GROUP_TALBE,self::VOUCHERS_TABLE]);
        $on     = sprintf("%s.group_id=%s.id,%s.group_id=%s.group_id",self::RELEASE_TABLE,self::GROUP_TALBE,self::VOUCHERS_TABLE,self::RELEASE_TABLE);
        $field  = sprintf("%s.activated_code",self::VOUCHERS_TABLE);
        $result = $this->getAll($table,$where,$field,'left,left',$on,self::RELEASE_TABLE.'.id desc');
        return $result;
    }
    /** 部门-用户
     * @param $adminId
     * @return mixed
     */
    public function getDeptAdmin($adminId)
    {
        $where = ['admin.admin_id'=>$adminId];
        $on = "admin.dept_id=hc_admin_dept.id";
        return $this->getFind('admin,hc_admin_dept',$where,'admin.admin_truename,hc_admin_dept.name','left',$on);
    }
    /**
     * 投放代金券
     */
    public function startRelease($param)
    {
        $this->beginTransaction();
        try{
            //计算投放数量
            if($param['activated_type'] ==0){//免激活
                if($param['ignore_time_type'] >0){//如果是定时投放
                    return false;exit;
                }
                $useNum = $this->geUseNum($param['group_id']);
                $releaseTotal  = $this->getReleaseTotal($param['ignore_object'],$param['ignore_users']);
                $release_total = $releaseTotal * $useNum;
                $this->addReleaseNotActivated($param);
                $update['can_release_num'] = 0;//可投放
            }else{
                $release_total = $param['release_total_num'];
                //修改投放id
                $where = sprintf("release_id =0 and group_id=%d",$param['group_id']);
                $sql   = sprintf("UPDATE `car_%s` SET `release_id`=%s,`status`=1 WHERE %s LIMIT %d",self::VOUCHERS_TABLE,$param['release_id'],$where,$release_total);
                DB::execute($sql);
                $update['can_release_num'] = intval($param['can_release_num']) - $release_total;//可投放
            }
            $update['status']          = 1;//设置代金券组状态未已投放
            $update['release_total']   = $param['release_total'] + $release_total;//已投放
            $update['updated_at'] = get_now2();
            //更新组投放状态
            $res = $this->saveData(self::GROUP_TALBE,$update,'update',['id'=>$param['group_id']]);
            $this->commit();
            return $res;
        }catch (Exception $e){
            $this->rollback();
            return false;
        }
    }

    private function getReleaseTotal($ignore_object,$users=null){
        switch($ignore_object){
            case 0://所有客户
                $release_total = $this->table('users')->count();
                break;
            case 1://指定客户
                $release_total = count(explode('、',$users));
                break;
            case 2://一年内未买车的客户
                $release_total = $this->getYearNotUserOrder('year');
                break;
            case 3://三个月内新注册并且未买车的用户
                $release_total = $this->getYearNotUserOrder('month');
                break;
            default:
                $release_total = 0;
        }
        return $release_total;
    }

    /** 投放免激活
     * @param $group_id
     * @param $ignore_object
     * @param $users
     * @return string
     */
    private function addReleaseNotActivated($data)
    {
        $ignore_object = intval($data['ignore_object']);
        $insertAll = [];
        switch($ignore_object){
            case 0://所有客户
                $userList = $this->table('users')->field('id')->select();
                foreach($userList as $value){
                    $data['user_id'] = $value['id'];
                    $insertAll[] = $this->setValues($data);
                }
                break;
            case 1://指定客户
                $users = str_replace('、',',',$data['ignore_users']);
                $userList = $this->table('users')->field('id')->where(['phone'=>['in',$users]])->select();
                foreach($userList as $value){
                    $data['user_id'] = $value['id'];
                    $insertAll[] = $this->setValues($data);
                }
                break;
            case 2://一年内未买车的客户
                $userList = $this->getYearNotUserOrder('year',false);
                foreach($userList as $value){
                    $data['user_id'] = $value['id'];
                    $insertAll[] = $this->setValues($data);
                }
                break;
            case 3://三个月内新注册并且未买车的用户
                $userList = $this->getYearNotUserOrder('month',false);
                foreach($userList as $value){
                    $data['user_id'] = $value['id'];
                    $insertAll[] = $this->setValues($data);
                }
                break;
            default:
                $userList = false;
        }
        if($userList){
            return $this->saveVoucher($insertAll);exit;
        }
        return false;
        /*$ids = implode(',', array_keys($display_order));
        $sql = "(CASE id ";
        foreach ($display_order as $id => $ordinal) {
            $sql .= sprintf("WHEN %d THEN %d ", $id, $ordinal);
        }
        $sql .= "END WHERE id IN ($ids))";
        return $sql;*/
    }

    /** 代金券投放条数
     * @param $id
     * @return mixed
     */
    public function getVouchersReleaseTotal($id)
    {
        return $this->getCount(self::VOUCHERS_TABLE,['release_id'=>$id]);
    }
}