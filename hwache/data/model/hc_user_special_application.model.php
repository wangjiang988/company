<?php
/**
 * 订单模型管理
 */
defined('InHG') or exit('Access Invalid!');
class hc_user_special_applicationModel extends Model {
    public function __construct(){
        parent::__construct('hc_user_special_application');
    }

     /**
      * 添加特别事项表单申请
      */
     public function addApplication($application)
     {
        $application['created_at'] =  $application['updated_at'] = get_now2();
        $ret = $this->insert($application);
        return $ret;
     }

    public function updateApplication($data,$where)
    {
        $data['updated_at'] = get_now2();
        return $this->where($where)->update($data);
    }
    /**
    * 获取特事审批列表
    */
    public function getList($where, $options=array(), $page=10)
    {
        $where['hc_user_special_application.is_del']  = 0;
        $table = isset($options['table']) ? $options['table'] : 'hc_user_special_application,users,admin';
        $field="hc_user_special_application.*,users.phone as user_phone,users.email as user_email,admin.dept_id";
        $col = isset($options['field']) ? $options['field'] : $field;
        $on = isset($options['on']) ? $options['on'] : 'hc_user_special_application.user_id=users.id,hc_user_special_application.apply_admin_id=admin.admin_id';

        $list  =  $this->table($table)
            ->join('left,left')
            ->on($on)
            ->field($col)
            ->where($where)
            ->order('hc_user_special_application.id desc')
            ->page($page)
            ->select();
        return $list;
    }

    public function getFind($where)
    {
        return $this->where($where)->find();
    }


}
