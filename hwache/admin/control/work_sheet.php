<?php
/**
 * @author wangjiang
 * @time 201700804
 * @location admin.123.com
 * 工单管理
 */
defined('InHG') or exit('Access Invalid!');
class work_sheetControl extends SystemControl
{
    public function __construct()
    {
           parent::__construct();
        Tpl::setDir('work_sheet');
    }


    public function indexOp()
    {
        // $list  = 
        $model  =  Model('hc_inner_work_sheet');
        $list   =  $model->getListByWhere($where);
        
        
        Tpl::output('list', $list);
        Tpl::showpage('work_sheet.index');
    }

    public function createOp()
    {
        if(chksubmit())
        {
            $post  =  $_POST;
            if(!$post['dept'])  showMessage('跟进处理部门未选择');
            if(trim($post['subject']) == '' )  showMessage("主题不能为空");
            $model  =  Model('hc_inner_work_sheet');
            $data   =  [ 
                "target"      => 3,
                "handle_depts"     => $post['dept'].',',
                "subject"     => $post['subject'],
                "description" => $post['description'],
                "upload_files"=> $post['upload_files'],
                'user_phone'  => $post['user_phone'],
                "creator" => $this->admin_info['name'],
                "creator_id"   => $this->admin_info['id'],
            ];
            $ret  =  $model->insert($data);
            if($ret)
            {
                showMessage("新建工单成功",'index.php?act=work_sheet&op=view&id='.$ret,'html','succ',1,100000);
            }else{
                showMessage("新建工单失败",'index.php?act=work_sheet&op=create','html' ,'error',1,100000);
            }

            exit();
        }

        //获取所有部门列表
        $dept_list  = Model('hc_admin_dept')->getListByWhere(['status'=>1]);

        Tpl::output('dept_list', $dept_list);
        Tpl::showpage('work_sheet.create');
    }

    public function viewOp()
    {
        $id         =  $_GET['id'];
        if(!isset($id) || !is_numeric($id)) showMessage('参数错误');
        $model      =  Model('hc_inner_work_sheet');
        $work_sheet =  $model->where([ 'id' => $id ])->find(); 

        //获取证据
        $files = [];
        if($work_sheet['upload_files'])
            $files = array_filter(explode(",",$work_sheet['upload_files']));
        if(!empty($files))
        {
            // $work_sheet['upload_files']  =  $files;
            foreach($files  as $file_id)
            {
                $file    =  Model('hc_images')->where([ 'id' => $file_id ])->find();  
                if($file)  $work_sheet['files'][] =  $file ; 
            }
        }   

        //获取处理部门
        $depts =  [];
        if($work_sheet['handle_depts'])
        {
            $depts = array_filter(explode(',', $work_sheet['handle_depts']));
        }

        $current_handle_dept_id  = !empty($depts) ? end($depts):0;
        $current_handle_dept  =  Model('hc_admin_dept')->where(['id' => $current_handle_dept_id])->find();
        if(!$current_handle_dept)    showMessage("数据错误！没有处理部门");

        $work_sheet['current_handle_dept'] =  $current_handle_dept;

        //获取处理记录
        $handle_list  = Model('hc_inner_work_sheet_log')->where('inner_work_sheet_id='.$id)->order('created_at desc')->select();
        //筛选出已处理的记录
        $finished_list = [];
        if($handle_list){
            foreach($handle_list as $handle)
            {
                if($handle['is_finished']) {
                    //获取文件
                    if(strpos($handle['upload_files'],',')){
                        $handle['files'] = get_files($handle['upload_files']);
                    }

                    $finished_list[] = $handle;
                } 
            }
        }

        //当前用户是否可接单
        $can_handle = false;
        if(in_array($work_sheet['status'],[0,10]) && $current_handle_dept_id == $this->admin_info['dept_id'])
        {
            $can_handle = true;
        }

        //如果已接单，判断当前用户是否是接单人    
        $is_current_handler = false;
        

        if($work_sheet['status'] == 1){
            $lastest_handler = $handle_list[0];
            Tpl::output('current_handler', $lastest_handler);
            //获取所有部门,并输出
            $this->_assign('dept_list');

            if($lastest_handler  && $lastest_handler['creator_id'] == $this->admin_info['id'] ){
                $is_current_handler =true;
            } 
        }

        Tpl::output('data', $work_sheet);
        Tpl::output('handle_list', $finished_list);
        Tpl::output('can_handle', $can_handle);
        if($is_current_handler) {
            Tpl::output('is_current_handler', $is_current_handler);
            Tpl::showpage('work_sheet.handle');
        }  
        else  Tpl::showpage('work_sheet.view');
    }

    private function _assign($type="dept_list")
    {
        switch ($type)
        {
            case "dept_list":
                $dept_list  = Model('hc_admin_dept')->getListByWhere(['status'=>1]);
                Tpl::output('dept_list', $dept_list);
                break;
        }
    }

    /**接单操作
     *
     **/
    public function pickupOp()
    {
        $id         =  $_GET['id'];
        if(!isset($id) || !is_numeric($id)) json_error('参数错误');
        $model      =  Model('hc_inner_work_sheet');
        $work_sheet =  $model->where([ 'id' => $id ])->find(); 
        if( !$work_sheet ) json_error('该工单未找到');
        if($work_sheet['status'] == 1)  json_error("正在处理中，不能接单");

        $model_log = Model('hc_inner_work_sheet_log');
        //产生一个新的处理记录
        $ret = $model_log->generate_new_log($id, $this->admin_info);
        if($ret){

            //跟新工单状态为处理中
            $model->where(['id'=>$id])->update(['status'=>1]);
            json_succ('接单成功', $ret);
        }else{
            json_error("接单失败");
        }
    }

    /**
     * 处理记录
     */
    public function handleOp()
    {
        $id   =  $_GET['id'];
        if(!isset($id) || !is_numeric($id)) json_error('参数错误');
        $model   =   Model('hc_inner_work_sheet_log');
        $log  = $model->where(['id'=>$id])->find();
        if(!$log)json_error('该操作日志不存在');
        $post  = getPayload();

        if(count($post)>0){
            if($this->admin_info['id'] != $log['creator_id'])  json_error("不是您的处理日志，无权操作!");
           
            $data  = [
                'content' =>  $post['content'],
                'upload_files' => $post['upload_files'],
                'is_finished'  => 1,
            ];
            if($post['action'] == 'transfer'){
                 $data['next_dept_id'] = $post['dept'];
                 $data['next_dept']    = $post['dept_name'];
            }
            if($post['action'] == 'finish'){
                $data['next_dept_id'] = 0;
                $data['next_dept'] = "";
            }

            $ret  = $model->where(['id'=>$log['id']])->update($data);

            if($ret)
            {
                $work_sheet_model = Model('hc_inner_work_sheet');
                $work_sheet =  $work_sheet_model->where(['id'=>$log['inner_work_sheet_id']])->find();

                //转单
                if($post['action'] == 'transfer')
                {
                    
                    $handle_depts =  $work_sheet['handle_depts'].$post['dept'].',';
                    $update = [
                        'handle_depts'=> $handle_depts,
                        'status'      => 10
                        ];
                   
                } 
                //完结单
                if($post['action'] == 'finish'){
                    $update = [
                        'status'  => 2
                    ];
                }
                if(count($update)>0)
                     $work_sheet_model->where(['id'=>$work_sheet['id']])->update($update);
                json_succ('操作成功');
            }else{
                json_error('操作失败');
            }
        }
    }

}