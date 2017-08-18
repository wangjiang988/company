<?php
/**
 * 代理商管理
 * Created by PhpStorm.
 * User: Qinlin
 * Date: 2016/11/8
 * Time: 10:44
 */
defined('InHG') or exit('Access Invalid!');
class new_userControl extends SystemControl
{
    protected $User;
    protected $table = 'user_view';
    const USER_ADDRESS_TABLE = 'user_address';
    const USERS_TABLE        = 'users';
    const USER_EXTENSION  = 'user_extension';
    const USER_BANK       = 'user_bank';
    const USER_IDCART_LOG = 'user_idcart_log';
    const USER_BANK_LOG   = 'user_bank_log';
    const USER_FREEZE     = 'user_freeze';

    public function __construct()
    {
        parent::__construct();
        Language::read('user');
        $this->User = Model('user');
        //$this->table_prefix = C('tablepre');
    }

    /**
     * 代理商列表
     */
    public function listOp(){
        $keyword = trim($_GET['search_keyword']);
        if(!empty($keyword)){
            $option['where']['real_name'] = ['like','%'.$keyword.'%'];
            $search['keyword'] = $keyword;
        }
        $email_phone = trim($_GET['email_phone']);
        if(!empty($email_phone)){
            $option['where']['email|phone'] = ['like','%'.$email_phone.'%'];
            $search['email_phone'] = $email_phone;
        }
        $status    = trim($_GET['status']);
        if($status != '' && $status >=0){
            $option['where']['status'] = intval($status);
            $search['status'] = $status;
        }
        $option['table'] = $this->table;
        $option['field'] = '*';
        $option['order'] ='id desc';
        $result = $this->User->getPageList($option);
        Tpl::output('search',$search);
        Tpl::output('list',$result['list']);
        Tpl::output('page',$result['page']);
        Tpl::setDir('User');
        Tpl::showpage('user.list');
    }

    /**
     * 添加修改详情页面
     */
    public function editOp(){
        $id = (int) $_GET['id'];
        if(!empty($id)){
            $find = $this->getUserInfo($id);
            if($find['status'] ==1){
                //邮箱找密码冻结
                $email = $this->getFreeze($id,$find['email'],'id,updated_at,status,validity_time','pwd_dj');
                Tpl::output('email',$email);
                //手机找密码冻结
                $mobile = $this->getFreeze($id,$find['phone'],'id,updated_at,status,validity_time','pwd_dj');
                Tpl::output('mobile',$mobile);
                //手机登录冻结
                $djMobile = $this->getFreeze($id,[$find['phone'],$find['email']] , 'id,created_at,updated_at,status,validity_time,click_num','dj');
                Tpl::output('djMobile',$djMobile);
                //brak
                $bank = $this->getUserBank(['user_id'=>$id , 'is_verify'=>1]);
                Tpl::output('bank',$bank);
            }
            Tpl::output('find',$find);
        }
        Tpl::setDir('User');
        Tpl::showpage('user.edit');
    }

    /**
     * 查看详情页面
     */
    public function viewOp(){
        $id = (int) $_GET['id'];
        if(!empty($id)){
            $find = $this->getUserInfo($id);
            if($find['status'] ==1) {
                //邮箱找密码冻结
                $email = $this->getFreeze($id, $find['email'], 'id,updated_at,status,validity_time', 'pwd_dj');
                Tpl::output('email', $email);
                //手机找密码冻结
                $mobile = $this->getFreeze($id, $find['phone'], 'id,updated_at,status,validity_time', 'pwd_dj');
                Tpl::output('mobile', $mobile);
                //手机登录冻结
                $djMobile = $this->getFreeze($id, [$find['phone'], $find['email']], 'id,updated_at,status,validity_time', 'dj');
                Tpl::output('djMobile', $djMobile);

                //brak
                $bank = $this->getUserBank(['user_id' => $id, 'is_verify' => 1]);
                Tpl::output('bank',$bank);
            }

            Tpl::output('find',$find);
        }
        Tpl::setDir('User');
        Tpl::showpage('user.view');
    }

    /**
     * @param $user_id
     */
    private function getUserBank($map){
        $map['is_default'] = 1;
        $map['is_verify'] = 1;
        $option['table'] = self::USER_BANK;
        $option['where'] = $map;
        $option['field'] = '*';
        $option['order'] = 'id desc';
        return $this->User->getFind($option);
    }

    /** 查看用户基本信息
     * @param $user_id
     * @return mixed
     */
    private function getUserInfo($user_id){
        $option['table'] = $this->table.','.self::USER_ADDRESS_TABLE;
        $option['join']  = ['left join'];
        $option['on']    = sprintf("%s.address_id=%s.address_id",$this->table,self::USER_ADDRESS_TABLE);
        $option['where'] = [$this->table.'.id'=>$user_id];
        $option['field'] = $this->table.'.*,'.self::USER_ADDRESS_TABLE.'.*';
        return $this->User->getFind($option);
    }
    /** 查看银行卡信息
     * @param $id
     */
    public function bankOp(){
        $id = (int) $_GET['id'];
        if(empty($id))
        {
            showMessage('缺少参数id');
        }
        //brak
        $bank = $this->getUserBank(['user_id'=>$id]);
        $find = $this->getUserInfo($bank['user_id']);
        $bankLogList = $this->User->getTableList(['user_id'=>$id],self::USER_BANK_LOG);
        Tpl::output('bank_log',$bankLogList);

        Tpl::output('bank',$bank);
        Tpl::output('find',$find);
        Tpl::setDir('User');
        Tpl::showpage('bank.info');
    }

    /**
     * 审核详情
     */
    public function bankLogOp()
    {
        $id = (int) $_GET['id'];
        $option['table'] = self::USER_BANK_LOG;
        $option['where'] = ['id'=>$id];
        $find = $this->User->getFind($option);
        Tpl::output('find',$find);
        Tpl::setDir('User');
        Tpl::showpage('bank.log');
    }
    /**
     * 查看用户认证信息
     */
    public function idCartOp(){
        $id = (int) $_GET['id'];
        $find = $this->getUserInfo($id);
        switch($find['is_id_verify']){
            case 1:
                $tpl = "user.idcart";
                break;
            default://
                $IdCartList = $this->User->getTableList(['user_id'=>$id]);
                Tpl::output('idcart_log',$IdCartList);
                $tpl = "idcart.update";
        }
        Tpl::output('find',$find);
        Tpl::setDir('User');
        Tpl::showpage($tpl);
    }


    /**
     * 保存银行卡认证信息
     */
    public function postBankOp(){
        $user_id = (int) $_POST['user_id'];
        $bank_id = (int) $_POST['bank_id'];
        $data['is_verify']          = $logData['status'] = intval($_POST['status']);
        $logData['user_id']         = $user_id;
        $logData['bank_id']         = $bank_id;
        $logData['bank_code']       = trim($_POST['bank_code']);
        $logData['bank_address']    = trim($_POST['bank_address']);
        $logData['sc_bank_img']     = (int) $_POST['sc_bank_img'];
        $logData['bank_img']        = (int) $_POST['bank_img'];
        $logData['desc']            = trim($_POST['desc']);
        $logData['created_at']      = date('Y-m-d H:i:s',time());
        $logData['updated_at']      = date('Y-m-d H:i:s',time());
        $logData['admin_id']        = $this->admin_info['id'];
        //更新认证状态
        $res = $this->User->saveDbData(self::USER_BANK,$data,'update','id='.$bank_id);
        //写入认证日志
        if($res){
            $LogRes = $this->User->saveDbData(self::USER_BANK_LOG,$logData);
        }


        if($res && $LogRes){
            showDialog('操作成功','index.php?act=new_user&op=bank&id='.$user_id,'succ');
        }else{
            showDialog('操作失败','','error');
        }
    }
    /**
     * 查看用户相册
     * @return mixed
     */
    public function imgListOp(){
        $id = (int) $_GET['id'];
        $type = $_GET['type'];
        $list = $this->User->getTableList(['user_id'=>$id,'action_type'=>$type],'qiniu_images');
        Tpl::output('list',$list);
        Tpl::setDir('User');
        Tpl::showpage('img.list');
    }

    public function registerOp($max=6)
    {
        $where['type'] = 'reg_dj';
        $keyword = trim($_GET['search_keyword']);
        $status  = intval($_GET['status']);
        if(!empty($keyword)){
            $where['value'] = ['like','%'.$keyword.'%'];
        }
        $search['keyword'] = isset($keyword) ? $keyword : '';
        $search['status'] = isset($_GET['status']) ? $_GET['status'] : 9;

        $isFreeze = "FROM_UNIXTIME(UNIX_TIMESTAMP(updated_at),'%Y-%m-%d')='".get_now()."' and `status`=1 and click_num>={$max}";
        if($status>1){
            if($status ==2)
                $where['status'] = ['exp',$isFreeze];

            if($status ==3){
                $sFreeze  = "`status`= 0 or (FROM_UNIXTIME(UNIX_TIMESTAMP(updated_at),'%Y-%m-%d') < '".get_now()."')";
                $sFreeze .= " or (FROM_UNIXTIME(UNIX_TIMESTAMP(updated_at),'%Y-%m-%d')='".get_now()."' and `status`=1 and click_num<{$max})";
                $where['status'] = ['exp',$sFreeze];
            }
        }
        $ifFreezeCol = ",if({$isFreeze},1,2) as is_freeze";
        $option['table']   = self::USER_FREEZE;
        $option['where']   = $where;
        $option['field']   = '*'.$ifFreezeCol;
        $option['order']   = 'id desc';
        //print_r($option);exit;
        $result = $this->User->getPageList($option);
        Tpl::output('search',$search);
        Tpl::output('list',$result['list']);
        Tpl::output('page',$result['page']);
        Tpl::setDir('User');
        Tpl::showpage('freeze-register.list');
    }
    /**
     * 防骚扰列表
     */
    public function fsrOp($freezeType='fr')
    {
        $type = intval($_GET['type']);
        if(empty($type)){
            $where[self::USER_FREEZE.'.is_mobile'] = 1;
        }else{
            $where[self::USER_FREEZE.'.is_email'] = 1;
        }
        $where[self::USER_FREEZE.'.status'] = 1;
        $where[self::USER_FREEZE.'.activated'] = 1;
        $where[self::USER_FREEZE.'.type'] = $freezeType;
        $search['type']  = $type;
        $keyword = trim($_GET['search_keyword']);
        if(!empty($keyword)){
            $where[self::USER_FREEZE.'.value'] = ['like','%'.$keyword.'%'];
            $search['keyword'] = $keyword;
        }
        $option['table']   = self::USER_FREEZE.','.$this->table;
        $option['join']    = ['left join'];
        $option['on']      = sprintf("%s.user_id=%s.user_id",self::USER_FREEZE,$this->table);
        $option['where']   = $where;
        $option['field']   = sprintf('%s.*,%s.real_name',self::USER_FREEZE,$this->table);
        $option['order']   = self::USER_FREEZE.'.id desc';
        //print_r($option);exit;
        $result = $this->User->getPageList($option);
        Tpl::output('search',$search);
        Tpl::output('list',$result['list']);
        Tpl::output('page',$result['page']);
        Tpl::setDir('User');
        Tpl::showpage('fsr.list');
    }
    /**
     *  设置防骚扰
     */
    public function freezeOp(){
        $id         = (int) $_POST['id'];
        $value      = isset($_POST['value']) ? $_POST['value'] : '';
        if(isset($_POST['type'])){
            $name       = ($_POST['type'] =='mobile') ? '设置手机防骚扰':'设置邮箱防骚扰';
            $is_mobile  = ($_POST['type'] =='mobile') ? 1 : 0;
            $is_email   = ($_POST['type'] =='email') ? 1 : 0;
        }
        $type       = $_POST['freeze'];
        $status     = intval($_POST['status']);
        $activated  = 1;
        if($id >0){
            $where = "id=".$id." AND type='".$type."'";//['id'=>$id,'type'=>$type];
        }else{
            $where = "value='".$value."' AND type='".$type."'";//['value'=>$value,'type'=>$type];
        }

        $phoneFind = $this->User ->table('user_freeze')->field('value')->where(['id'=>$id])->find();
        //查看是否存在
        $isCheck = $this->User->isCheck(self::USER_FREEZE,$where);
        $data['click_num']     = 0;
        //$data['validity_time'] = 0;
        $data['updated_at'] = date('Y-m-d H:i:s',strtotime('day -1'));
        if($isCheck){
            $data['status'] = $status;
            //$where= "user_id=".$user_id." AND value='".$value."' AND type='".$type."'";
            $result = $this->User->saveDbData(self::USER_FREEZE,$data,'update',$where);
            if($result >=0 && $type == 'reg_dj'){
                //发送短信 template_code=78585101
                require_once dirname(__FILE__).'/../vendor/SendSms.php';
                $sms = new SendSms();
                echo $sms->sendSms($phoneFind['value'],'78585101');
            }else{
                $res = ($result >=0);
                echo ($res) ? json_encode(['Success'=>1]) : json_encode(['Success'=>0]) ;
            }
        }else{
            //$data['user_id']    = $user_id;
            $data['value']      = $value;
            $data['name']       = $name;
            $data['type']       = $type;
            $data['is_mobile']  = $is_mobile;
            $data['is_email']   = $is_email;
            $data['status']     = $status;
            $data['activated']  = $activated;
            $data['created_at']  = date('Y-m-d H:i:s');
            $res = $this->User->saveDbData(self::USER_FREEZE,$data);
            echo ($res) ? json_encode(['Success'=>1]) : json_encode(['Success'=>0]) ;
        }
    }

    /**
     * 修改过用户冻结状态
     */
    public function updateStatusOp(){
        $userId = $_POST['user_id'];
        $status = intval($_POST['status']);
        $res = $this->User->saveDbData(self::USERS_TABLE,['status'=>$status],'update','id='.$userId);
        if(empty($status) && $res){
            //清除登录冻结及找密冻结数据
            $userFind = $this->User ->table('users')->field('phone,email')->where(['id'=>$userId])->find();
            $this->updateFreeze($userFind['phone']);
            $this->updateFreeze($userFind['phone'],'pwd_dj');
            $this->updateFreeze($userFind['email'],'pwd_dj');
        }
        echo json_encode(['Success'=>($res) ? 1 : 0]);
    }

    private function updateFreeze($value,$type='dj',$status=0)
    {
        $where = "value='".$value."' AND type='".$type."'";
        //查看是否存在
        $isCheck = $this->User->isCheck(self::USER_FREEZE,$where);
        $data['click_num']  = 0;
        $data['updated_at'] = date('Y-m-d H:i:s',strtotime('day -1'));
        $data['status']     = $status;
        if($isCheck) {
            return $this->User->saveDbData(self::USER_FREEZE, $data, 'update', $where);
        }
        return false;
    }

    /**
     * 批量解除防骚扰
     */
    public function delFreezeOp()
    {
        $idFind = implode(',',$_POST['id']);
        $data['status']     = 0;
        $data['activated']  = 0;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['click_num']  = 0;
        $where= "id in (".$idFind.")";
        $res = $this->User->saveDbData(self::USER_FREEZE,$data,'update',$where);
        if($res){
            showDialog('操作成功','index.php?act=new_user&op=fsr','succ');
        }else{
            showDialog('操作失败','','error');
        }
    }

    public function addFreezeOp()
    {
        if($_POST == NULL){
            $type= intval($_GET['type']);
            $title = empty($type) ? '设为防骚扰的手机号' : '设为防骚扰的邮件地址';
            $typeName = empty($type) ? 'phone' : 'email' ;
            Tpl::output('find',['title'=>$title,'type'=> $typeName]);
            Tpl::setDir('User');
            Tpl::showpage('fsr.add');
        }else{
            $value      = $_POST['value'];
            $type       = $_POST['type'];
            //检查格式
            if(!is_phone($value) && !is_email($value)){
                $errorMsg = ($type=='phone') ? '手机格式错误！':'邮件格式错误！';
                echo json_encode(['Success'=>0,'Msg'=>$errorMsg]);exit;
            }
            //查找用户
            $user_id = $this->User->getOne(self::USERS_TABLE,[$type=>$value],'id');
            if(is_null($user_id)){
                echo json_encode(['Success'=>0,'Msg'=>'此号码不存在！']);exit;
            }
            //判断是否已经设置
            $className  = 'fr';
            $name       = ($type =='phone') ? '设置手机防骚扰':'设置邮箱防骚扰';
            $is_mobile  = ($_POST['type'] =='phone') ? 1 : 0;
            $is_email   = ($_POST['type'] =='email') ? 1 : 0;
            $status     = 1;
            $activated  = 1;
            //查看是否存在
            $isCheck = $this->User->isCheck(self::USER_FREEZE,['value'=>$value,'type'=>$className]);
            if(!$isCheck){
                $data = [
                    'user_id'    => $user_id,
                    'value'      => $value,
                    'name'       => $name,
                    'type'       => $className,
                    'is_mobile'  => $is_mobile,
                    'is_email'   => $is_email,
                    'status'     => $status,
                    'activated'  => $activated,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'click_num'  => 1
                ];
                $res = $this->User->saveDbData(self::USER_FREEZE,$data);
            }else{
                $res = $this->User->saveDbData(self::USER_FREEZE,
                    ['status'=>1,'activated'=>1,'updated_at'=>get_now2(),'click_num'=>0]
                ,'update',"`value`='{$value}' and `type`='{$className}'"
                );
            }
            echo ($res) ? json_encode(['Success'=>1,'Msg'=>'设置成功']) : json_encode(['Success'=>0,'Msg'=>'设置失败！']) ;
        }
    }

    public function showMobileOp(){
        $user_id = $_GET['id'];
        $find = $this->getUserInfo($user_id);
        Tpl::output('find',$find);
        Tpl::setDir('User');
        Tpl::showpage('update.phone');
    }
    /**
     * 检查手机号
     */
    public function checkUserOp()
    {
        $phone = $_GET['phone'];
        if(!is_phone($phone)){
            echo setJsonMsg(0,'手机号格式错误！');exit;
        }
        $isPhone = $this->User->isCheck(self::USERS_TABLE,['phone'=>$phone]);
        if($isPhone >0){
            echo setJsonMsg(0,'手机号被使用！');exit;
        }else{
            echo setJsonMsg(1,'手机号可以使用！');exit;
        }
    }

    /**
     * 更新手机
     */
    public function updatePhoneOp(){
        if($_POST != NULL){
            $phone   = $_POST['phone'];
            $user_id = (int) $_POST['user_id'];
            $code    = trim($_POST['code']);
            if(empty($phone) || empty($code)){
                echo setJsonMsg(0,'手机号或者验证码不能为空！');exit;
            }
            $template_code  = '78530065';
            $max   = 5;
            $isValidation = $this->User -> VerifySms($phone,$template_code,$code);
            if(!$isValidation){
                $sendCount = $this->User ->isDayCheckMsg($phone, $template_code , date('Y-m-d' , time()));
                $push = ['count' => $max - $sendCount];
                echo setJsonMsg(0,'短信验证失败！',$push);exit;
            }else{
                $data['phone'] = $phone;
                $res = $this->User->saveDbData(self::USERS_TABLE,$data,'update','id='.$user_id);
                if($res){
                    //发送成功短信
                    require_once dirname(__FILE__).'/../vendor/SendSms.php';
                    $sms = new SendSms();
                    $sendSms = $sms->sendSms($phone);
                }
                echo ($res) ? setJsonMsg(1,'手机号更新成功！') : setJsonMsg(0,'手机号更新失败！');exit;
            }
        }
    }

    /** 查看冻结日志
     * @param $user_id
     * @param null $value
     * @return bool
     */
    private function getFreeze($user_id,$value,$col='*',$type='fr'){
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
        return $this->User->getFind($options);
    }
}