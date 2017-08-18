<?php
/**
 * 自定义字段管理
 */

defined('InHG') or exit('Access Invalid!');

class fieldsControl extends SystemControl{

    private $config;

    public function __construct(){
        parent::__construct();

        $this->config = require(BASE_DATA_PATH.'/config/base.ini.php');

        // 关联模型数据
        Tpl::output('carmodel', $this->config['model']);

        // 字段数据类
        Tpl::output('type', $this->config['type']);
    }

    /**
     * 自定义字段列表
     */
    public function indexOp() {
        // 获取所有字段信息
        $where = [];
        if(is_search()){
            $fields_list  =  [
                'model|like',
                'name|like'
            ];
            $where = trans_form_to_where($fields_list);
        }
        $model_fields = Model('fields');
        $fields_list  = $model_fields->getFieldsList($where, '*', 10, 'sort ASC,id ASC');
//        $fields_list  = $model_fields->getListByWhere($where, '', '');
        Tpl::output('fields_list',$fields_list);

        Tpl::output('page',$model_fields->showpage());
        Tpl::showpage('fields.index');
    }

    /**
     * 自定义字段增加
     */
    public function addOp() {
        // 是否有提交表单操作
        if (chksubmit()) {
            // 保存数据数组
            $data = array(
                'model'     => trim($_POST['model']), // 模型
                'name'      => trim($_POST['name']), // 字段名
                'title'     => trim($_POST['title']), // 显示名称
                'type'      => trim($_POST['type']), // 数据类型
                'desc'      => trim($_POST['desc']), // 字段说明
                'sort'      => intval(trim($_POST['sort'])), // 排序
                'is_search' => intval(trim($_POST['search'])), // 是否支持搜索
                'is_add'    => intval(trim($_POST['add'])), // 是否添加时显示
                'is_index'  => intval(trim($_POST['index'])), // 是否前端显示
                'readonly'  => intval(trim($_POST['readonly'])), // 是否只读
                'operating' => 0, // 操作权限
            );

            // 如果字段类型为单选,多线,下拉列表
            if (in_array($data['type'], $this->config['box'])) {
                if (!empty($_POST['setting'])) {
                    $setting = array();
                    $tmp_arr = explode("\r", $_POST['setting']);
                    foreach ($tmp_arr as $key => $value) {
                        $setting[$key] = trim($value);
                    }
                    $data['setting'] = serialize($setting); // 如果是选项自选,序列化选项值
                }
            }

            // 加载模型
            $model_fields = Model('fields');
            $r = $model_fields->addFields($data);
            if ($r) {
                showMessage('添加自定义字段成功', 'index.php?act=fields');
            } else {
                showMessage('添加自定义字段失败');
            }

        } else {
            Tpl::showpage('fields.add');
        }
    }
    
    public function delOp(){
    	$field_id = $_GET['id'];
    	$model_fields = Model('fields');
//    	$condition = array('id'=>$field_id,'model'=>'carmodel');
    	$condition = array('id'=>$field_id);
    	$field_info = Model('fields')->where($condition)->find();
    	if(!$field_info){
    		showMessage('没有此字段', 'index.php?act=fields');
    	}
    	//exit;
    	$r = $model_fields->where($condition)->delete();
    	$condition1 =array('name'=>$field_info['name']);
    	$r1 = Model('hg_baojia_fields')->where($condition1)->delete();
    	
    	if ($r) {
    		showMessage('删除自定义字段成功', 'index.php?act=fields');
    	} else {
    		showMessage('删除自定义字段失败');
    	}
    
    }

    /**
     * 自定义字段修改
     */
    public function editOp() {
        // 是否有提交表单操作
        if (chksubmit()) {
            // 保存数据数组
            $data = array(
                'model'     => trim($_POST['model']), // 模型
                'name'      => trim($_POST['name']), // 字段名
                'title'     => trim($_POST['title']), // 显示名称
                'type'      => trim($_POST['type']), // 数据类型
                'desc'      => trim($_POST['desc']), // 字段说明
                'sort'      => intval(trim($_POST['sort'])), // 排序
                'is_search' => intval(trim($_POST['search'])), // 是否支持搜索
                'is_add'    => intval(trim($_POST['add'])), // 是否添加时显示
                'is_index'  => intval(trim($_POST['index'])), // 是否前端显示
                'readonly'  => intval(trim($_POST['readonly'])), // 是否只读
                'operating' => 0, // 操作权限
            );

            // 如果字段类型为单选,多线,下拉列表
            if (in_array($data['type'], $this->config['box'])) {
                if (!empty($_POST['setting'])) {
                    $setting = array();
                    $tmp_arr = explode("\r", $_POST['setting']);
                    foreach ($tmp_arr as $key => $value) {
                        $setting[$key] = trim($value);
                    }
                    $data['setting'] = serialize($setting); // 如果是选项自选,序列化选项值
                }
            }

            // 加载模型
            $model_fields = Model('fields');
            $r = $model_fields->updateFields($data,$_POST['id']);
            if ($r) {
                showMessage('添加自定义字段成功', 'index.php?act=fields');
            } else {
                showMessage('添加自定义字段失败');
            }

        } else {
            $field_id = $_GET['id'];
            $model_fields = Model('fields');
            $condition = array('id'=>$field_id);
            $field_info = Model('fields')->where($condition)->find();
            if(!$field_info){
                showMessage('没有此字段', 'index.php?act=fields');
            }
            Tpl::output('data',$field_info);
            Tpl::showpage('fields.edit');
        }
    }

}