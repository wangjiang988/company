<?php
/**
 * Created by PhpStorm.
 * User: wangjiang
 * Date: 2016/12/19
 * Time: 17:55
 */

defined('InHG') or exit('Access Invalid!');
class area_manageControl extends SystemControl{

    public function __construct()
    {
        parent::__construct();
         Tpl::setDir('area_manage');
    }

    public function indexOp(){

        $where    =  ["1" => "1"];
        $area_id  =  $_GET['area_id']; 
        if(!$_GET['ajax']){
             $where['area_deep'] =1 ; 
        }
        if($area_id)
            $where = ['area_parent_id'=> $area_id];
        if(trim($_GET['area_name']))
            $where['area_name'] = ['like','%'.trim($_GET['area_name']).'%'];

       

        if($_GET['ajax']){
            $list =   Model('area')->field('area_id, area_name, area_parent_id,area_deep')
                            ->where($where)
                            ->select(); 
        }else{
            $list =   Model('area')->field('area_id, area_name, area_parent_id,area_deep')
                            ->where($where)
                            ->page(10)->select();
        }


        if($list)
        {
            foreach ($list as $key => $value) {
                {
                    $count       = Model('area')
                                     ->where(['area_id'=>$value['area_id']])
                                     ->count();
                    $list[$key]['child_count'] = $count;
                }
            }
        }
        if($_GET['ajax'])
            json_succ('',$list);

        Tpl::output('list',$list);
        Tpl::output('page',Model()->showPage());
        Tpl::showpage('index');
    }

    /**
     * 添加修改详情页面
     */
    public function addOp(){

        if ($_POST['form_submit'] == 'ok'){
            $data = [];

            $area_deep = 0;
            $area_parent_id = 0;
            if($_POST['province']==0){//加的是省级的数据
                $area_deep = 1;
            }elseif($_POST['province']>0 && $_POST['city']==0){
                $area_deep = 2;
                $area_parent_id = $_POST['province'];
            }else{
               $area_deep = 3; 
               $area_parent_id = $_POST['city'];
            }
            
            if($area_deep>0)
            {
                $data['area_deep'] = $area_deep;
                $data['area_name'] = trim($_POST['area_name']);
                $data['area_parent_id'] = $area_parent_id;
                $data['first_letter'] = getFirstCharter($data['area_name']);
                $data['not_mainland'] = isset($_POST['not_motherland'])?$_POST['not_motherland']:0;
                $ret  =  Model('area')->insert($data);
                if($ret)
                    showMessage("添加成功");
                else
                    showMessage("添加失败");
            }else{
                showMessage("未知错误");
            }


        }

        $list =     Model('area')->field('area_id, area_name, area_parent_id,area_deep')
                            ->where([
                                'area_deep' =>1  
                            ])
                            ->select();
        Tpl::output('province_list', $list);
        Tpl::showpage('add');
    }

    //删除
    public function delOp()
    {
        $request = getPayload();
        $id      = $request['id'];
        if(!$id)
        {
            json_error('参数错误！');
        }
        
        $ret = Model('area')->where(['area_id'=>intval($id)])->delete();
        if($ret)
        {
            json_succ('删除成功');
        }else{
            json_error("删除失败");
        }
         
    }


    /**
     *
     **/
    public function  getCityByProvinceOp()
    {
        $request  = getPayload();
        if(!$request['id'])  json_error('参数缺失');
        $city_list  =  Model('area')->where(['area_deep'=>2,'area_parent_id'=>$request['id']])
                        ->field('area_id, area_name, area_parent_id,area_deep')
                        ->select();
        if(!$city_list)  json_error('没有数据');

        json_succ('', $city_list);
    }

    /**
     * 跟新后台地区js
     **/
    public function cache_areaOp(){
        $province_list = Model('area')->where(['area_deep'=>1,'not_mainland'=>0])
                    ->field('area_id, area_name,area_parent_id')
                    ->select();
                    
        $content  = "nc_a = new Array();\r\n";
        $city_list    = [];
        $content_key  = 0;  //js数组下表
        if($province_list){
            $content .='nc_a[0]=[';
            foreach( $province_list as $province){

                $content .="['".$province['area_id']."','".$province['area_name']."'],";
                $content_key++;
                $city_list[$content_key]    =  Model('area')->where([
                                    'area_deep'=>2,
                                    'area_parent_id'=> $province['area_id']
                                    ])
                                ->field('area_id, area_name,area_parent_id')
                                ->select();
            }
            $content = substr($content,0,strlen($content)-1); 
            $content .="];\r\n";
        }
        $disctrict_list = []; //县
        if(count($city_list)>0)
        {
            foreach( $city_list as $key => $citys){
                if(count($citys)>0){
                    $content .='nc_a['.$key.']=[';
                    foreach($citys as $city)
                    {
                        $content .="['".$city['area_id']."','".$city['area_name']."'],";
                        $content_key++;
                        $disctrict_list[$content_key]  =  Model('area')->where([
                                                        'area_deep' => 3,
                                                        'area_parent_id' => $city['area_id'],
                                                    ])
                                                    ->field('area_id, area_name,area_parent_id')
                                                ->select();
                    }
                     
                    $content = substr($content,0,strlen($content)-1); 
                    $content .="];\r\n";
                }
               
            }
        }
        if(count($disctrict_list)>0)
        {
            foreach( $disctrict_list as $key => $disctricts){
                if(count($disctricts)>0){
                   $content .='nc_a['.$key.']=[';
                    foreach($disctricts as $disctrict)
                    {
                        $content .="['".$disctrict['area_id']."','".$disctrict['area_name']."'],";
                    }
                    $content = substr($content,0,strlen($content)-1); 
                    $content .="];\r\n";
                }
                
                
            }
        }

        file_put_contents(BASE_DATA_PATH.'/resource/js/area_array.js',$content);

        $this->_cache_all();
        
        exit('更新后台js成功');

    }

    private function _cache_all()
    {
        $province_list = Model('area')->where(['area_deep'=>1])
                    ->field('area_id, area_name,area_parent_id')
                    ->select();
                    
       $content  = "nc_a = new Array();\r\n";
        $city_list    = [];
        $content_key  = 0;  //js数组下表
        if($province_list){
            $content .='nc_a[0]=[';
            foreach( $province_list as $province){

                $content .="['".$province['area_id']."','".$province['area_name']."'],";
                $content_key++;
                $city_list[$content_key]    =  Model('area')->where([
                                    'area_deep'=>2,
                                    'area_parent_id'=> $province['area_id']
                                    ])
                                ->field('area_id, area_name,area_parent_id')
                                ->select();
            }
            $content = substr($content,0,strlen($content)-1); 
            $content .="];\r\n";
        }
        if($city_list)
        {
            foreach( $city_list as $key => $citys){
                if(count($citys)>0){
                    $content .='nc_a['.$key.']=[';
                    foreach($citys as $city)
                    {
                        $content .="['".$city['area_id']."','".$city['area_name']."'],";
                        $content_key++;
                    }
                    $content = substr($content,0,strlen($content)-1); 
                    $content .="];\r\n";
                }    
               
            }
        }
        file_put_contents(BASE_DATA_PATH.'/resource/js/city_select/area_array.js',$content);
        

    }

    /**
     * 查看详情页面
     */
    public function detailOp(){

        if ($_POST['form_submit'] == 'ok'){
             if(!isset($_POST['area_id']))
             {
                showMessage('参数缺失');
             }
             $id = $_POST['area_id'];

             $area = Model('area')->where(['area_id'=>$id])->find();
             if(!$area)
             {
                   showMessage('没有该ID的数据');
             }
             $ret = Model('area')->where(['area_id'=>$id])
                    ->update([
                        'area_name'=> trim($_POST['area_name']),
                        'not_mainland'=> isset($_POST['not_mainland'])?$_POST['not_mainland']:0,
                    ]);
            if($ret)
                showMessage('修改成功',url('area_manage','index'));        
            else showMessage('修改失败');
            exit();
        }


        if(!isset($_GET['id'])){
            showMessage('参数缺失！');
        }
        $id  =  $_GET['id'];

        $data  = Model('area')->field('area_id,area_name,not_mainland')->where(['area_id'=>$id])->find();
        if(!$data)  showMessage('没有该ID的数据');

        Tpl::output('data',$data);
        Tpl::showpage('detail');
    }
     
}