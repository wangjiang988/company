<?php
/**
 * 客户充值模型
 */
defined('InHG') or exit('Access Invalid!');
class hc_filter_templateModel extends Model {
    public function __construct(){
        parent::__construct('hc_filter_template');
    }




    public function getCurrentTemplate($type  = 11){
        $where = [
            'type'   => $type,
            'is_del' => 0,
            'status' => 1,
        ];
        $ret   =   $this->where($where)->find();
        if($ret && $ret['content'])
        {
            $ret['content']  = json_decode($ret['content'],true);
        }
        return $ret;
    }
    public function getCurrentTemplateList($type  = 11){
        $where = [
            'type'   => $type,
            'is_del' => 0,
            'status' => 1,
        ];
        $ret   =   $this->where($where)->select();
        if($ret && $ret['content'])
        {
            $ret['content']  = json_decode($ret['content'],true);
        }
        return $ret;
    }


    public function getTemplateList($type  = 11){
        $where = [
            'type'   => $type,
            'is_del' => 0,
        ];
        $ret   =   $this->where($where)->select();
        if($ret)
        {
            foreach ($ret as $k => $item){
                if($item['content']){
                    $ret[$k]['content']  = json_decode($ret[$k]['content'],true);
                }
            }
        }
        return $ret;
    }


    public function getTemplate($type  = 11, $where=["1"=>1],$parse_json=true){
        $ret   =   $this->where($where)->find();
        if($ret && $ret['content'] && $parse_json)
        {
            $ret['content']  = json_decode($ret['content'],true);
        }
        return $ret;
    }


    public function getUpdateHistory($where)
    {
        $ret = $this->table('hc_admin_operation_detail,hc_admin_operation,hc_filter_template')
                    ->field('
                    hc_admin_operation_detail.*,
                    hc_filter_template.id as template_id,
                    hc_filter_template.name as template_name,
                    hc_admin_operation.user_id,
                    hc_admin_operation.user_name,
                    hc_admin_operation.remark,
                    hc_admin_operation.operation
                    ')
                    ->join('left,left')
                    ->on('hc_admin_operation_detail.op_id=hc_admin_operation.id,hc_filter_template.id=hc_admin_operation_detail.related_id')
                    ->where($where)
                    ->order('hc_admin_operation.created_at desc')
                    ->page(10)
                    ->select();
        return $ret;
    }
}
