<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/24
 * Time: 11:29
 */

defined('InHG') or exit('Access Invalid!');
class approveControl extends SystemControl
{
    public function __construct()
    {
        parent::__construct();
        //Language::read('approve');
        $this->Log = Model('approve');
    }

    public function idcartOp()
    {
        $result = $this->Log -> getIdCartPageList($_GET);
        Tpl::output('search',$result['search']);
        Tpl::output('list',$result['list']);
        Tpl::output('page',$result['page']);
        Tpl::setDir('User');
        Tpl::showpage('approve.idcart.list');
    }

    public function idcart_detailOp()
    {
        $id = (int) $_GET['id'];
        $find = $this->Log -> getIdcartDetail($id);
        if(strpos($find['file_path'] , ',')){
            $file_path = implode(',',array_filter(explode(',', $find['file_path'])));
            $images = Model('hc_images')->where('id in ('.$file_path.')')->select();
            if($images)   Tpl::output('images',$images);
        }

        Tpl::output('find',$find);
        Tpl::setDir('User');
        if($find['status']==0)
            Tpl::showpage('approve.idcart.detail');
        else
            Tpl::showpage('approve.idcart.view');
    }

    /**
     * 保存用户认证工单
     */
    public function postOp(){
        $id      = (int) $_POST['id'];
        $user_id = (int) $_POST['user_id'];
        $data['is_id_verify']      = intval($_POST['status']);
        $data['last_name']         = trim($_POST['last_name']);
        $data['first_name']        = trim($_POST['first_name']);
        if(empty($data['last_name']) || empty($data['first_name'])){
            showDialog('姓或名不能未空！','','error');
        }
        $logData['admin_id']    = $this->admin_info['id'];
        $logData['admin_name']  = $this->admin_info['name'];
        $logData['review_time'] = get_now2();
        $logData['file_path']   =  $_POST['file_path'];
        $logData['reason']      = $_POST['reason'];
        $logData['remark']      = trim($_POST['remark']);
        $logData['updated_at']  = get_now2();
        $logData['status']      = (int)($data['is_id_verify']==1)? 1 : 2;

        $res = $this->Log ->saveIdCart($user_id,$id,$data,$logData);
        if($res){
            showDialog('操作成功','index.php?act=approve&op=idcart_detail&id='.$id,'succ');
        }else{
            showDialog('操作失败','','error');
        }
    }


    public function bankOp()
    {
        $result = $this->Log -> getBankPageList($_GET);
        Tpl::output('list',$result['list']);
        Tpl::output('page',$result['page']);
        Tpl::setDir('User');
        Tpl::showpage('approve.bank.list');
    }

    public function bank_detailOp()
    {
        if(!isset($_GET['id'])) showMessage('缺少参数！');
        $id   =  $_GET['id'];
        $data = Model()->table('user_bank_log,user_view')->join('left')->on('user_bank_log.user_id = user_view.id')
                    ->field('user_bank_log.* ,user_view.phone,CONCAT(user_view.last_name,user_view.first_name) as real_name')
                    ->where('user_bank_log.id='.$id)->find();

        if($data['bank_id'])  $data['bank'] = Model('user_bank')->where('id', $data['bank_id'])->find();

        if(strpos($data['file_path'] , ',')){
            $file_path = implode(',',array_filter(explode(',', $data['file_path'])));
            $images = Model('hc_images')->where('id in ('.$file_path.')')->select();
            if($images)   Tpl::output('images',$images);
        }

        Tpl::output('data',$data);
        Tpl::setDir('User');
        if($data['status'] == 0 )
            Tpl::showpage('approve.bank.detail');
        else
            Tpl::showpage('approve.bank.view');
    }

     /**
     * 保存用户认证工单
     */
    public function bank_postOp(){
        $id      = (int) $_POST['id'];
        $user_id = (int) $_POST['user_id'];
        $user_extension = Model('user_view')->field('id,is_id_verify')->where('id='.$user_id)->find();
        if(!$user_extension) showMessage('该用户不存在');


        $model =   Model('user_bank_log');
        $log  = $model->where('id='.$id)->find();
        if(!$log) showMessage('该数据不存在');

        $status      = intval($_POST['status']);
        $success    = '操作成功';

        if($status == 1 && $user_extension['is_id_verify'] !=1 ){
            $logData['status']      = 2;
            $logData['reason']      = '该用户还未通过实名认证，暂不能通过审核';
            $success    = '该用户还未通过实名认证，暂不能通过审核';
        } else{
            $logData['status']      = $status;
            $logData['reason']      = $_POST['reason'];
        }

        $logData['admin_id']    = $this->admin_info['id'];
        $logData['admin_name']  = $this->admin_info['name'];
        $logData['review_time'] = get_now2();
        $logData['file_path']   = $_POST['file_path'];
        $logData['remark']      = trim($_POST['remark']);
        $logData['updated_at']  = get_now2();

        $bankVerify = Model('user_bank')->field('is_verify')->where('id='.$log['bank_id'])->find();
        $is_verify = empty($bankVerify['is_verify']) ? 2 : 4;
        $res  = $model->where('id='.$id)->update($logData);
        if($res){
            if($logData['status'] == 1)
                Model('user_bank')->where('id='.$log['bank_id'])->update(['is_verify'=>1]);
            else{
                Model('user_bank')->where('id='.$log['bank_id'])->update(['is_verify'=>$is_verify]);
            }
            showDialog($success ,'index.php?act=approve&op=bank_detail&id='.$id,'succ');
        }else{
             showDialog('操作失败','','error');
        }
    }
}