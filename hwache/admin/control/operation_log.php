<?php
/**
 * 网站设置
 */
defined('InHG') or exit('Access Invalid!');
class operation_logControl extends SystemControl{

    private $operation;

	public function __construct(){
		parent::__construct();
		$this->operation  =  Model('hc_admin_operation');
        Tpl::setDir('operation_log');
		Language::read('setting');
	}

	/**
	 * 操作列表
	 */
	public function IndexOp()
    {
        $model  = $this->operation;
        $where = [];

        if(is_search())
        {
            $field_list = [
                'type|eq',
                'related|like',
            ];
            $where = trans_form_to_where($field_list);
        }
        $list   = $model->getList($where);

        $op_types = get_all_operation_type();
        Tpl::output('op_types',$op_types);
        Tpl::output('list',$list);
        Tpl::output('page',$model->showPage());
        Tpl::showpage('index');
    }


    /**
     * 操作明细
     */
    public function detailOp()
    {
        $id =  $_GET['id'];
        if(!$id)
            showMessage('参数错误');
        $model  = $this->operation;
        $where = ['id'=>$id];

        $operation   = $model->getDataByWhere($where);
        $detail_list = Model('hc_admin_operation_detail')
                        ->where(['op_id'=>$operation['id']])
                        ->select();

        Tpl::output('data',$operation);
        Tpl::output('list',$detail_list);
        Tpl::output('page',Model()->showPage());
        Tpl::showpage('detail');
    }
}
