<?php
/**
 * 代理商管理
 * Created by PhpStorm.
 * User: Qinlin
 * Date: 2016/11/8
 * Time: 10:44
 */
defined('InHG') or exit('Access Invalid!');
class sellerControl extends SystemControl
{
    protected $Seller;
    public function __construct()
    {
        parent::__construct();
        Language::read('seller');
        $this->Seller = Model('common','seller');
        //$this->table_prefix = C('tablepre');
    }

    public function testOp(){
        $price = Model('price');
        $prices = $price->getPriceAll(658);
        print_r($prices);exit;
        $res = $price->savePrice(631,95200);
        var_dump($res);
    }

    /**
     * 代理商列表
     */
    public function listOp(){
        $option = $this->joinMember();
        $keyword = trim($_GET['search_keyword']);
        if(!empty($keyword)){
            $option['where']['seller.seller_name'] = ['like','%'.$keyword.'%'];
            $search['keyword'] = $keyword;
        }
        $name_phone = trim($_GET['name_phone']);
        if(!empty($name_phone)){
            $option['where']['member.member_truename|member.member_mobile'] = ['like','%'.$name_phone.'%'];
            $search['name_phone'] = $name_phone;
        }
        $status    = trim($_GET['status']);
        if($status != ''){
            $option['where']['seller.status'] = intval($status);
            $search['status'] = $status;
        }
        $search['start_date'] = $start_date = trim($_GET['start_date']);
        $search['end_date']   = $end_date   = trim($_GET['end_date']);
        if(!empty($start_date) && empty($end_date)){
            $option['where']['member.member_time'] = ['egt',strtotime($start_date)];
        }
        if(!empty($ent_date) && empty($start_date)){
            $option['where']['member.member_time'] = ['elt',strtotime($end_date)];
        }
        if(!empty($start_date) && !empty($end_date)){
            $option['where']['member.member_time'] = ['exp',"member.member_time>=".strtotime($start_date)." AND member.member_time<=".strtotime($end_date)];
        }

        $option['where']['seller.seller_name'] = ['exp',"seller.seller_name != ''"];

        $option['order'] = 'seller.seller_id desc';
        $result = $this->Seller->getPageList($option);

        Tpl::output('search',$search);
        Tpl::output('list',$result['list']);
        Tpl::output('page',$result['page']);
        Tpl::setDir('Seller');
        Tpl::showpage('seller.list');
    }

    /**
     * 添加修改详情页面
     */
    public function editOp(){
        $id = (int) $_GET['id'];
        if(!empty($id)){
            $option = $this->joinMember();
            $option['where'] = ['seller.seller_id'=>$id];
            $find = $this->Seller->getFind($option);
            Tpl::output('find',$find);
        }
        $contact_model = Model('seller_contact');
        $contact_arr = $contact_model->where(['seller_id'=>$find['seller_id']])->select();
        Tpl::output('contact',$contact_arr);

        $regions = $this->regionOp(0,'return');
        Tpl::output('region',$regions);
        if(!empty($find['seller_province_id'])){
            $area = $this->regionOp($find['seller_province_id'],'return');
            Tpl::output('area',$area);
        }
        Tpl::setDir('Seller');
        Tpl::showpage('seller.detail');
    }

    /**
     * 查看详情页面
     */
    public function viewOp(){
        $id = (int) $_GET['id'];
        if(!empty($id)){
            $option = $this->joinMember();
            $option['where'] = ['seller.seller_id'=>$id];
            $find = $this->Seller->getFind($option);
            Tpl::output('find',$find);
        }
        $contact_model = Model('seller_contact');
        $contact_arr = $contact_model->where(['seller_id'=>$find['seller_id']])->select();
        Tpl::output('contact',$contact_arr);

        $regions = $this->regionOp(0,'return');
        Tpl::output('region',$regions);
        if(!empty($find['seller_province_id'])){
            $area = $this->regionOp($find['seller_province_id'],'return');
            Tpl::output('area',$area);
        }
        Tpl::setDir('Seller');
        Tpl::showpage('seller.view');
    }

    /**
     * 添加代理商
     */
    public function addOp(){
        $regions = $this->regionOp(0,'return');
        Tpl::output('region',$regions);
        Tpl::setDir('Seller');
        Tpl::showpage('seller.add');
    }

    /**
     * 判断用户名是否存在
     * @return bool
     */
    public function checkSellerOp(){
        $SellerName = trim($_POST['username']);
        $seller_id  = (int) $_REQUEST['seller_id'];
        $option['where']['seller_name'] = $SellerName;
        if($seller_id >0){
            $option['where']['seller_id'] = ['neq',$seller_id];
        }
        $option['field'] = 'count(*) as _count';
        $res = $this->Seller->getFind($option);
        echo ($res['_count'] > 0) ? 'false' : 'true';
    }
    /**
     * 保存代理商
     */
    public function postOp(){
        $id        = (int) $_POST['seller_id'];
        $member_id = (int) $_POST['member_id'];
        $data = [];
        $data['seller_name']      = trim($_POST['seller_name']);
        $data['status']           = intval($_POST['status']);
        $data['identity']         = intval($_POST['identity']);
        $data['seller_phone']     = trim($_POST['seller_phone']);
        $data['seller_card_num']  = trim($_POST['seller_card_num']);
        $data['seller_province_id'] = (int) $_POST['seller_province_id'];
        $data['seller_city_id']     = (int) $_POST['seller_city_id'];
        $data['seller_bank_city_str'] = getRegion($data['seller_province_id']).' '.getRegion($data['seller_city_id']) ;
        $data['seller_bank_addr']   = trim($_POST['seller_bank_addr']);
        $data['seller_bank_account']= trim($_POST['seller_bank_account']);
        $data['seller_sex']         = intval($_POST['seller_sex']);
        $data['seller_email']       = trim($_POST['seller_email']);
        $data['seller_postcode']    = trim($_POST['seller_postcode']);
        $data['seller_weixin']      = trim($_POST['seller_weixin']);
        $data['remarks']            = trim($_POST['remarks']);

        if(!empty($_FILES['seller_photo']['name'])){
            $data['seller_photo'] = $this->setUploads('seller_photo',false);
        }
        $memberData = [];
        $password = trim($_POST['password']);
        if(!empty($password)){
            $memberData['member_passwd'] = bcrypt($password);
        }
        $memberData['member_truename']     = trim($_POST['member_truename']);
        $memberData['member_mobile']       = trim($_POST['member_mobile']);
        $memberData['member_address']      = trim($_POST['member_address']);

        $accountData['basic_deposit']  = (int) $_POST['basic_deposit'];
        $accountData['credit_line']    = (int) $_POST['credit_line'];
        $sellerModel = Model('seller');

        if(!empty($id)){
             $updateMember = $this->saveMember($memberData,$member_id);
             $res = $sellerModel->saveData('seller',$data,'update',['seller_id'=>$id]);
             $this->saveAccount($accountData,$member_id);//basic_deposit
        }else{
            $memberData['member_name']   = $data['seller_name'];
            $memberData['member_mobile'] = trim($_POST['member_mobile']);
            $insertMember = $this->saveMember($memberData,$member_id);
            if($insertMember){
                $data['member_id'] = $insertMember;
            }else{
                exit('用户添加失败！');
            }
            $res = $sellerModel->saveData('seller',$data);
            $this->saveAccount($accountData,$insertMember);
        }
        if($res){
            showDialog('操作成功','index.php?act=seller&op=list','succ');
        }else{
            showDialog('操作失败','','error');
        }
    }
    /**
     * 操作用户表
     * @param $data
     * @param int $member_id
     * @return mixed
     */
    private function saveMember($data,$member_id=0){
        $member = Model('member');
        return empty($member_id) ? $member->addMember($data) : $member->updateMember($data,$member_id);
    }

    /** 操作账号资金
     * @param $data
     * @param $memner_id
     */
    private function saveAccount($data,$memner_id)
    {
        $account_model = Model('hc_daili_account');
        $where = ['d_id' => $memner_id];
        $isAccount = $account_model->getCount('hc_daili_account',$where);
        if($isAccount){
            $res = $account_model->saveData('hc_daili_account',$data,'update',['d_id' =>$memner_id]);
        }else{
            $insert = [
                'd_id'             => $memner_id,
                'total_deposit'    => '0.00',//'账户总金额',
                'avaliable_deposit'=> '0.00',//'可用保证金（资金池可提现余额）',
                'temp_deposit'     => '0.00',//临时冻结资金',
                'freeze_deposit'   => '0.00',//'冻结保证金',
                'status'           => '0',//'状态，默认0，1可用，2禁用',
                'created_at'       => date('Y-m-d H:i:s',time()),//'创建时间',
                'updated_at'       => date('Y-m-d H:i:s',time())//'最后修改时间',
            ];
            $res = $account_model->saveData('hc_daili_account',array_merge($data,$insert));
        }
        return $res;
    }
    /**
     * 构造用户表的联合查询
     * @return array
     */
    private function joinMember(){
        $option['table'] = 'seller,member,hc_daili_account';
        $option['field'] = 'seller.*,member.member_truename,member.member_mobile,member.member_address,hc_daili_account.basic_deposit,hc_daili_account.credit_line';
        $option['join']  = ['left join','left join'];
        $option['on']    = "seller.member_id=member.member_id , seller.member_id=hc_daili_account.d_id";
        return $option;
    }
    /**
     * 添加其他联系方式页面
     */
    public function otherOp(){
        Tpl::output('seller_id',(int) $_REQUEST['id']);
        Tpl::setDir('Seller');
        Tpl::showpage('seller.dialog');
    }
    /**
     * 添加其他联系方式
     */
    public function addOtherOp()
    {
        $oth_name = trim( $_POST['oth_name'] );
        $oth_value = trim( $_POST['oth_value'] );
        if (empty( $oth_name ) || empty( $oth_value )) {
            $resultJson['Success'] = 0;
            $resultJson['Msg'] = '名称或者号码为空哟！';
        } else {
            $id = (int)$_POST['seller_id'];
            $option['where'] = ['seller_id' => $id];
            $option['field'] = 'seller_other_contact';
            $seller_other = $this->Seller->getFind( $option );
          //  $datas = $this->setJsonContact( $seller_other['seller_other_contact'], ['key' => $oth_name, 'val' => $oth_value] );
            $contact_model = Model('seller_contact');
            $datas = [
               'name' => $oth_name,
               'phone' => $oth_value,
               'seller_id' => $id
            ];
            $res = $contact_model->insert( $datas);
            $resultJson['Success'] = ($res == true) ? 1 : 0;
            $resultJson['Msg']     = '网络异常或程序异常！';
        }
        echo json_encode($resultJson);
    }

    /**
     * 删除其他联系方式
     */
    public function delOtherOp(){
        if(isAjax()){
            $id = (int) $_POST['key'];
            // $key       = (int) $_POST['key'];
            // $option['where'] = ['seller_id' => $seller_id];
            // $option['field'] = 'seller_other_contact';
            // $seller_other = $this->Seller->getFind( $option );
            $contact_model = Model('seller_contact');
            $res = $contact_model->delete($id);
         //   $res = $this->Seller->saveData( ['seller_other_contact' => serialize($otherArr)], 'update', $option['where'] );

            $resultJson['Success'] = ($res == true) ? 1 : 0;
            $resultJson['Msg']     = '网络异常或程序异常！';
        }else{
            $resultJson['Success'] = 0;
            $resultJson['Msg']     = '请求错误！';
        }
        echo json_encode($resultJson);exit;
    }
    /**
     * 设置json数据
     * @param null $jsonStr
     * @param $newArr
     * @return string
     */
    private function setJsonContact($jsonStr=null,$newArr){
        if(!empty($jsonStr) && !is_null($jsonStr)){
            $ContactArr = unserialize($jsonStr);
            if(is_null($ContactArr)){
                return serialize([$newArr]);
            }
            array_push($ContactArr,$newArr);
            return serialize($ContactArr);
        }else{
            return serialize([$newArr]);
        }
    }
}