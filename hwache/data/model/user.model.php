<?php
/**
 * 卖家帐号模型
 */
defined('InHG') or exit('Access Invalid!');
require_once "common.model.php";
class userModel extends commonModel{
    const USER_FREEZE     = 'user_freeze';
    const USER_ADDRESS_TABLE = 'user_address';
    protected $userView   = 'user_view';
    public function __construct(){
        parent::__construct('users');
    }

    /**
     * 读取列表
     * @param array $condition
     *
     */
    public function getUserList($options=null,$pageSize=10) {
        //$result = $this->field($field)->where($condition)->page($page)->order($order)->select();
        $result = $this->getPageList($options,$pageSize=10);
        return $result;
    }




    /**
     * 根据查询条件读取用户账户信息，联合查询
     * 加上用户status 状态验证
     * @author wangjiang
     * @param array $table
     * @param array $condition
     */
    public function getUserAccountList( $options=null, $pageSize=15) {
        $options['users.status']  = 1; //状态为1的用户可用
        $result =  $this->table('users,hc_user_account,user_extension')
                        ->join('left,left')
                        ->on('users.id=hc_user_account.user_id , user_extension.user_id=users.id')
                        ->field('users.id,users.name,users.phone,users.created_at,users.status, CONCAT(user_extension.last_name,user_extension.first_name) as fullname')
                        ->where($options)
                        ->page($pageSize)
                        ->order('hc_user_account.updated_at desc')
                        ->select();
        return $result;
    }

    /**
     * 查询用户加信宝数据
     * @param string $field
     * @param null $where
     * @param string $order
     * @param int $page
     * @return mixed
     */
    public function getUserList2($field='*',$where=null,$order='asc',$page =10)
    {
        $where['users.status'] = 1;
        $order  = 'hc_user_account.freeze_deposit '.$order;
        $return = $this->table('users,hc_user_account')
                ->join('left')
                ->on('users.id = hc_user_account.user_id')
                ->field($field)->where($where)->order($order)->page($page)->select();

        return $return;
    }

//所有客户冻结余额总计
    public function sum_freeze_deposit($where=[]){
//        $where['status']  = 1;
        $model = Model('hc_user_account');
        return $model->field('SUM(freeze_deposit) as sum_freeze_deposit')->where($where)->find();
    }

    //查看单条用户加信宝数据
    public function getUserAccount($where=[])
    {
        $where['users.status'] = 1;
        $return = $this->table('users,hc_user_account')
            ->join('left')
            ->on('users.id = hc_user_account.user_id')
            ->where($where)->find();

        return $return;
    }

    //查询加信宝订单列表
    public function getUserJiaxinbaoLogs($where=[])
    {
        $return = $this->table('hc_order,users,(select order_id|SUM( CASE WHEN type = 10 THEN money ELSE -money END) as sum from car_hc_order_jiaxinbao_detail as detail where detail.role=1  group by order_id) AS jiaxinbao')
                        ->field('users.id,hc_order.id,hc_order.created_at,jiaxinbao.sum')
                        ->join('left,left')
                        ->on('users.id=hc_order.user_id,hc_order.id=jiaxinbao.order_id')
                        ->where($where)->order('hc_order.created_at desc')->page(10)->select();
        return $return ;
    }

    /**
     * 读取单条记录
     * @param array $condition
     *
     */
    public function getInfo($condition) {
        $result = $this->where($condition)->find();
        return $result;
    }

    /**
     * 根据用户Id获取用户信息
     * @param $id
     * @author wangjiang
     */
    public function getUserById($id, $field = 'id, name, phone, email, login_num, status,created_at,updated_at')
    {
        $where = [
            'id' =>$id,
            'status' =>1
        ];

        $result = $this->where($where)->field($field)->find();
        if($result)
        {
            $extension = Model('user_extension')->where(['user_id'=>$id])->find();
            if($extension) $result = array_merge($result, $extension);
            $account   = Model('hc_user_account')->where(['user_id'=>$id])->find();
            if($account) $result = array_merge($result, $account);
        }

        return $result;
    }

    /**
     * @param $table
     * @param $where
     * @param string $col
     * @return array|null
     */
    public function getOne($table,$where,$col='*'){
        $param = array();
        $param['table'] = $table;
        $param['field'] = array_keys($where);
        $param['value'] = array_values($where);
        $res =  Db::getRow($param,$col);
        if(is_null($res)){
            return null;
        }
        return ($col != '*') ? $res[$col] : $res;
    }
    /*
     *  判断是否存在
     *  @param array $condition
     *
     */
    public function isExist($condition) {
        $result = $this->getInfo($condition);
        if(empty($result)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /*
     * 增加
     * @param array $param
     * @return bool
     */
    public function addUser($param){
        return $this->insert($param);
    }

    /*
     * 更新
     * @param array $update
     * @param array $condition
     * @return bool
     */
    public function editUser($update, $condition){
        return $this->where($condition)->update($update);
    }

    /*
     * 删除
     * @param array $condition
     * @return bool
     */
    public function delUser($condition){
        return $this->where($condition)->delete();
    }

    /**
     * 查看图片地址
     * @param $img_id
     * @return mixed
     */
    public function getImgidToImg($img_id){
        $param = array();
        $param['table'] = 'qiniu_images';
        $param['field'] = 'img_id';
        $param['value'] = intval($img_id);
        $result = Db::getRow($param);
        return $result['img_url'];
    }

    /**
     * @param $user_id
     * @return null
     */
    public function getTableList($where,$table='user_idcart_log')
    {
        $options['table'] = $table;
        $options['where'] = $where;
        return $this->getList($options);
    }

    /**
     * 保存数据
     * @param $table
     * @param $data
     * @param string $type
     * @param null $where
     * @return bool|mixed
     */
    public function saveDbData($table,$data,$type='insert',$where=null){
        switch($type){
            case 'insert':
                $result = Db::insert($table,$data);
                break;
            case 'update':
                $result = Db::update($table,$data,$where);
                break;
        }
        return $result;
    }

    /** 查看是否存在
     * @param $table
     * @param $where
     * @return int
     */
    public function isCheck($table,$where){
        return Db::getCount($table,$where);
    }

    /** 短信验证
     * @param $phone
     * @param $type
     * @param $day
     * @param $code
     * @return mixed
     */
    public function getValidationSms($phone,$template_code,$day){
        $map = [
            'phone' => $phone,
            'sms_template_code'  => $template_code,
            'status'=> 1,
            'send_time' =>['exp',"FROM_UNIXTIME(send_time,'%Y-%m-%d')= '{$day}'"],
            'validity_time'=>['exp',"(send_time+validity_time) >=UNIX_TIMESTAMP()"]
        ];
        $options['table'] = 'send_sms_log';
        $options['where'] = $map;
        $options['order'] = 'id desc';
        return $this->getFind($options);
    }

    /**
     * 验证短信
     * @param $phone
     * @param $type
     * @param $code
     * @return bool
     */
    public function VerifySms($phone,$template_code,$code){
        $newSmsFind = $this->getValidationSms($phone,$template_code,get_now());
        if($newSmsFind && ($newSmsFind['code'] == $code)){
            //更新短信验证状态
            $this->saveDbData('send_sms_log',['is_check'=>1],'update',"sms_template_code='{$template_code}' and `code`='{$code}'");
            return true;
        }else{
             return false;
        }
    }
    /** 判断当天手机验证的次数
     * @param $phone
     * @param $type
     * @param $day
     * @param int $max
     * @return bool
     */
    public function isDayCheckMsg($phone,$template_code,$day){
        $map = [
            'phone' => $phone,
            'sms_template_code'  => $template_code,
            'status'=>1,
            'send_time' =>['exp',"FROM_UNIXTIME(send_time,'%Y-%m-%d')= '{$day}'"]
        ];
        $options['table'] = 'send_sms_log';
        $options['where'] = $map;
        $options['order'] = 'id desc';
        return $this->getCount($options);
    }

    /** 查看冻结日志
     * @param $user_id
     * @param null $value
     * @return bool
     */
    public function getFreeze($user_id,$value,$col='*',$type='fr'){
        if(is_null($value)){
            return 0;
        }
        if(is_array($value)){
            $values = ['exp',"(`value` like '%{$value[0]}%' or `value` like '%{$value[1]}%')"];
        }else{
            $values = $value;
        }
        $map = [
            'value'  => $values,
            'type'   => $type,
            'created_at' =>['exp',"FROM_UNIXTIME(UNIX_TIMESTAMP(updated_at),'%Y-%m-%d')='".date('Y-m-d')."'"],
            'updated_at' =>['exp',"(UNIX_TIMESTAMP(updated_at)+validity_time) >= UNIX_TIMESTAMP()"]
        ];
        $options['table'] = self::USER_FREEZE;
        $options['field'] = $col;
        $options['where'] = $map;
        $options['order'] = 'id desc';
        return $this->getFind($options);
    }

    /** 查看用户基本信息
     * @param $user_id
     * @return mixed
     */
    public function getUserInfo($user_id){
        $option['table'] = $this->userView.','.self::USER_ADDRESS_TABLE;
        $option['join']  = ['left join'];
        $option['on']    = sprintf("%s.address_id=%s.address_id",$this->userView,self::USER_ADDRESS_TABLE);
        $option['where'] = [$this->userView.'.id'=>$user_id];
        $option['field'] = $this->userView.'.*,'.self::USER_ADDRESS_TABLE.'.*';
        return $this->getFind($option);
    }
}
