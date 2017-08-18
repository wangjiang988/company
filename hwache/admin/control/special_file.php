<?php
/**
 * @author wangjiang
 * @time 20170621
 * @location admin.123.com
 * 特殊文件
 */
defined('InHG') or exit('Access Invalid!');

class special_fileControl extends SystemControl
{
    private $model ;
    public function __construct()
    {
        parent::__construct();
        $this->model  =Model('area_special_files');
        Tpl::setDir('special_file');
    }

    //首页
    public function indexOp()
    {
        $model  = $this->model;
        $where = [];
        if(is_search())
        {
            $field_list  = [
                'file_name|like|area_special_files',
                'status|eq|area_special_files',
                'area_id|eq|area_special_files',
                's_created_at,e_created_at|between|area_special_files',
                's_confirm_at,e_confirm_at|between|area_special_files',
                'user_id|like|area_special_files',
                'phone|like|users'
            ];
            $where = trans_form_to_where($field_list);
        }
        if(!$where['area_special_files.status'] && !isset($_GET['status'])){
            $where['area_special_files.status'] = 0;
            $_GET['status'] ='0';
        }
        $list   =   $model->getList($where);
        $area_model   =  Model('area');
        if($list){
            foreach ($list as $k => $item)
            {
                if($item['area_id']){
                    $list[$k]['area_name'] = $area_model->getAreaFullNameById($item['area_id']);
                }
            }
        }

        //获取所有省
        $province_list  = $area_model->getListByWhere(['area_deep'=>1],'area_id, area_name','first_letter asc' );
        Tpl::output('province_list',$province_list);
        Tpl::output('list',$list);
        Tpl::output('page',$model->showPage());
        Tpl::showpage('index');

    }

    //查看
    public function detailOp()
    {
        if(!$_GET['id']){
            showMessage('参数错误');
        }
        $special_file    =  $this->model->getDataByWhere(['id'=>$_GET['id']]);
        $request = getPayload();
        if($request['in_ajax']){
            $update = [
                'status'  =>  $request['status'],
                'admin_id'=>  $this->admin_info['id'],
                'admin_name' => $this->admin_info['name'],
                'confirm_at' => get_now2()
            ];
            $where = [
                'id'   =>  $_GET['id']
            ];
            $operation = [
                'user_id' => $this->admin_info['id'],
                'user_name' => $this->admin_info['name'],
                'remark'  => $request['remark'],
                'type'    => 51,
                'related' => 'area_special_files|'.$_GET['id'],
                'operation'=>'审核特殊文件，结果为'.($request['status']==1?'通过':'驳回'),
            ];
            $model = Model('area_special_files');
            $ret = $model->update_with_record($where,$update,$special_file,$operation);
            if($ret) json(['msg' =>'处理成功','code' => 200 ,'ret'=>$ret]);
            else  json_error('处理失败');
        }


        if($special_file)
        {
            $user_model  = Model('user');
            $area_model = Model('area');
            $qiniu_model      =  Model('qiniu_images');
            $special_file['user'] = $user_model->getUserById($special_file['user_id']);
            $special_file['area_name'] = $area_model->getAreaFullNameById($special_file['area_id']);
            $image_list  = $qiniu_model->where(['img_id' => ['in', $special_file['file_url']]])->select();
            $special_file['image_list']  = $image_list;
            $operation_model  = Model('hc_admin_operation');
            $special_file['operation'] = $operation_model->getDataByWhere([
                'related'=>'area_special_files|'.$special_file['id'],
                'type'   => 51
                ]);

        }
        Tpl::output('data',$special_file);
        Tpl::showpage('detail');
    }

    //根据area_id获取下一层所有数据
    public function getSubAreaOp()
    {
        $area_id  =  $_GET['area_id'];
        if(!$area_id){
            json_error('参数错误');
        }
        $model = Model('area');
        $list  = $model->getListByWhere(['area_parent_id'=>$area_id],'area_id,area_name', 'first_letter asc');
        json(['code'=>200,'data'=> $list]);
    }



}