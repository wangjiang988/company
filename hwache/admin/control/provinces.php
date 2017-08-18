<?php
/**
 * User: S4p3r
 * Date: 2016/12/21
 * Time: 14:58
 * 周边省份管理
 */
defined('InHG') or exit('Access Invalid!');

class provincesControl extends SystemControl
{
    const EXPORT_SIZE = 5000;

    public function __construct()
    {
        parent::__construct();
        Language::read('public');
    }

    public $fields = ['area_id', 'area_name'];

    /**
     *首页控制器
     */
    public function indexOp()
    {
        $area_id = $_GET['area_id'];
        $fields = $this->fields;
        if ($area_id) {
            $condition = [
                'area_parent_id' => 0,
                'area_id'        => $area_id,
            ];
        } else {
            $condition = [
                'area_parent_id' => 0,
            ];
        }
        $conditions = array('area_parent_id' => 0);
        $province_model = Model('area');
        $province_list = $province_model->getAreaTran($condition, $fields, 10);
        $province_lists = $province_model->getAreaList($conditions, $fields);
        Tpl::output('province_lists', $province_lists);
        Tpl::output('province_list', $province_list);
        Tpl::output('page', $province_model->showpage());
        $citydata = Model()->table('hc_provinces_manage,area')
            ->join('left')->on('hc_provinces_manage.ambitus_area_id=area.area_id')
            ->where(array('area_parent_id' => 0))
            ->order('area.area_id asc')
            ->field('hc_provinces_manage.*,area_name')
            ->select();
        $result = array();
        foreach ($citydata as $key => $value) {
            $result[$value['area_id']][] = $value;
        }
        Tpl::output('citydata', $result);
        Tpl::showpage('province.index');
    }

    /**
     * 省份编辑操作
     */
    public function editOp()
    {
        $area_id = $_GET['area_id'];
        $condition['area_id'] = $area_id;
        $province_model = Model('area');
        $area = $province_model->getAreaList($condition, $this->fields);
        Tpl::output('area', $area);
        Tpl::output('area_other', $this->listarea($area_id));
        Tpl::showpage('province.edit');
    }

    public function listarea($area_id)
    {
        $data = Model('hc_provinces_manage')
            ->where(array('area_id' => $area_id))->select();
        $tmp = [];
        foreach ($data as $value) {
            $tmp[] = $value['ambitus_area_id'];
        };
        $province_model = Model('area');
        $condition['area_id'] = array('in', $tmp);
        $area_list = $province_model->getAreaList($condition, $this->fields);
        return $area_list;
    }

    /**
     * 保存数据
     */
    public function saveOp()
    {
        $area_id = $_POST['area_id'];
        $areas_id = $_POST['areas_id'];
        $provinces_manage = Model('hc_provinces_manage');
        $data = $provinces_manage->where(array('area_id' => $area_id))->select();
        foreach ($areas_id as $value) {
            $arr['area_id'] = $area_id;
            $arr['ambitus_area_id'] = $value;
            $result = $provinces_manage->insert($arr);
        }
        if ($result) {
            echo json_encode([
                'error_code' => 1,
            ]);
        }
    }

    /**
     * ajax请求
     */
    public function ajaxareaOp()
    {
        $area_id = $_POST['area_id'];
        $province_model = Model('area');
        $conditions['area_parent_id'] = 0;
        $result = $province_model->getAreaList($conditions, $this->fields);
        $tmp = $this->listarea($area_id);
        foreach ($tmp as $value) {
            $temp[] = $value['area_id'];
        }
        $temp [] = $area_id;
        foreach ($result as $key => $value) {
            if (in_array($value['area_id'], $temp)) {
                unset($result[$key]);
            }
        }
        echo json_encode($result);
    }

    /**
     * 删除操作
     */
    public function delareaOp()
    {
        $area_id = $_POST['area_id'];
        $result = Model('hc_provinces_manage')
            ->where(array('ambitus_area_id' => $area_id))
            ->delete();
        if ($result) {
            echo json_encode([
                'error_code' => 1,
            ]);
        }
    }


}