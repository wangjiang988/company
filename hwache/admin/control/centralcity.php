<?php
/**
 * User: S4p3r
 * Date: 2016/12/23
 * Time: 17:48
 * 中心城市管理
 */
defined('InHG') or exit('Access Invalid!');

class centralcityControl extends SystemControl
{
    const EXPORT_SIZE = 5000;

    public $fields = ['area_id', 'area_name'];

    public function __construct()
    {
        parent::__construct();
        Language::read('public');
    }

    /**
     * 中心城市首页部分
     */
    public function indexOp()
    {
        $province_model = Model('area');
        $condition['area_parent_id'] = 0;
        $province_lists = $province_model->getAreaList($condition, $this->fields);
        Tpl::output('province_lists', $province_lists);
        //搜索
        $model_central = Model('hc_central_city');
        if ($_GET['id']) {
            $citydata = $model_central->getCitydata($condition = array('id' => $_GET['id']));
        } else {
            $citydata = $model_central->getCitydata($condition = array(), 10);
        }
        $citydatas = $model_central->getCitydata($condition = array());
        Tpl::output('citydata', $citydata);
        Tpl::output('page', $model_central->showpage());
        Tpl::output('citydatas', $citydatas);
        Tpl::showpage('centralcity.index');
    }

    /**
     * 请求数据
     */
    public function citylistOp()
    {
        $area_id = $_GET['area_id'];
        $province_model = Model('area');
        $condition['area_parent_id'] = $area_id;
        $province_lists = $province_model->getAreaList($condition, $this->fields);
        echo json_encode($province_lists);
    }

    /**
     * 保存数据
     * @return bool
     */
    public function savacityOp()
    {
        $area_id = $_POST['area_id'];
        $model = Model('hc_central_city');
        $result = $model->where(array('area_city_id' => $area_id))->select();
        if ($result) {
            echo json_encode(array('error_code' => 0));
            return false;
        }
        $data = $model->insert(array('area_city_id' => $area_id));
        if ($data) {
            echo json_encode(array('error_code' => 1));
        }
    }

    /**
     * 中心城市删除操作
     */
    public function delareaOp()
    {
        $id = $_POST['id'];
        $result = Model('hc_central_city')
            ->where(array('id' => $id))
            ->delete();
        if ($result) {
            echo json_encode([
                'error_code' => 1,
            ]);
        }
    }

}