<?php
/**
 * Created by PhpStorm.
 * User: jerry
 * Date: 2016/12/19
 * Time: 17:55
 */

defined('InHG') or exit('Access Invalid!');
class areaControl extends SystemControl{
    protected $Area;
    public function __construct()
    {
        parent::__construct();
        Language::read('area');
        $this->Area = Model('common','area');
        //$this->table_prefix = C('tablepre');
    }

    public function listOp(){
        $province_id = (int) $_GET['province_id'];
        $city_id     = (int) $_GET['city_id'];
        if(!empty($province_id)){
            $option['where']['area_parent_id'] = $province_id;
        }
        if(!empty($city_id)){
            $option['where']['area_id'] = $city_id;
        }
        $option['where']['area_deep'] = 2;
        $option['field'] = '*';
        $option['order'] = 'area_id asc';
        $result = $this->Area->getPageList($option);

        $regions = $this->regionOp(0,'return');
        Tpl::output('region',$regions);
        if(!empty($province_id)){
            $area = $this->regionOp($province_id,'return');
            Tpl::output('area',$area);
        }
        $search['province_id'] = $province_id;
        $search['city_id']     = $city_id;

        Tpl::output('search',$search);
        Tpl::output('list',$result['list']);
        Tpl::output('page',$result['page']);
        Tpl::setDir('Area');
        Tpl::showpage('area.list');
    }

    /**
     * 添加修改详情页面
     */
    public function editOp(){
        $id = (int) $_GET['id'];
        if(!empty($id)){
            $option['where'] = ['area_id'=>$id];
            //$option['field'] = '*';
            $find = $this->Area->getFind($option);
            Tpl::output('find',$find);
        }
        $this->showHtml($find['area_biaozhun'], $find['area_xianpai'], $find
        ['area_butie']);
        Tpl::setDir('Area');
        Tpl::showpage('area.detail');
    }

    /**
     * 查看详情页面
     */
    public function viewOp(){
        $id = (int) $_GET['id'];
        if(!empty($id)){
            $option['where'] = ['area_id'=>$id];

            $find = $this->Area->getFind($option);
            Tpl::output('find',$find);
        }

        Tpl::setDir('Area');
        Tpl::showpage('area.view');
    }
    /**
     * 保存地区关联
     */
    public function postOp(){
        $id = (int) $_POST['area_id'];
        $data = [];
        $data['area_chepai']      = trim($_POST['area_chepai']);
        $data['area_xianpai']     = intval($_POST['area_xianpai']);
        $data['area_biaozhun']    = implode(',',$_POST['area_biaozhun']);
        $data['special_file']     = implode('|',array_filter($_POST['fiels']));
        $data['car_boat_tax']     = implode(',',$_POST['tax']);
        $data['tips']             = trim($_POST['tips']);
        $data['notes']            = trim($_POST['notes']);
        $data['area_butie']       = trim($_POST['area_butie']);
        if(!empty($id)){
            $res = $this->Area->saveData($data,'update',['area_id'=>$id]);
        }
        if($res){
            showDialog('操作成功','index.php?act=area&op=list','succ');
        }else{
            showDialog('操作失败','','error');
        }
    }

    private function showHtml($area_biaozhun = '', $area_xianpai = '', $area_butie = '')
    {
        $biaozhunArr = [['id' => 1, 'name' => '国四'], ['id' => 0, 'name' => '国五'], ['id' => 2, 'name' => '国六']];
        $xianpaiArr = [['id' => 1, 'name' => '是'], ['id' => 0, 'name' => '否']];
        $butieArr = [['id' => 1, 'name' => '是'], ['id' => 0, 'name' => '否']];
        $biaozhunHtml = setHtml($biaozhunArr, 'area_biaozhun[]', 'checkbox', $area_biaozhun);
        $xianpaiHtml = setHtml($xianpaiArr, 'area_xianpai', 'radio', $area_xianpai);
        $butieHtml = setHtml($butieArr, 'area_butie', 'radio', $area_butie);
        Tpl::output('biaozhun', $biaozhunHtml);
        Tpl::output('xianpai', $xianpaiHtml);
        Tpl::output('butie', $butieHtml);
    }

    /**
     * 删除其他联系方式
     */
    public function delOtherOp(){
        if(isAjax()){
            $id = (int) $_POST['id'];
            $key       = (int) $_POST['key'];
            $option['where'] = ['area_id' => $id];
            $option['field'] = 'special_file';
            $files = $this->Area->getFind( $option );
            $otherArr = explode('|',$files['special_file']);
            if(isset($otherArr[$key])){
                unset($otherArr[$key]);
            }
            $res = $this->Area->saveData( ['special_file' => implode('|',$otherArr)], 'update', $option['where'] );

            $resultJson['Success'] = ($res == true) ? 1 : 0;
            $resultJson['Msg']     = '网络异常或程序异常！';
        }else{
            $resultJson['Success'] = 0;
            $resultJson['Msg']     = '请求错误！';
        }
        echo json_encode($resultJson);exit;
    }
}