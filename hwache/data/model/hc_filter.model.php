<?php
/**
 * 客户充值模型
 */
defined('InHG') or exit('Access Invalid!');
class hc_filterModel extends Model {
    public function __construct(){
        parent::__construct('hc_filter');
    }


    public function getCurrentTemplate($type  = 11){
        $where = [
            'type'   => $type,
        ];
        $ret   =   $this->where($where)->find();
        if($ret)
        {
            $template_model  = Model('hc_filter_template');
            $template = $template_model->where(['id'=>$ret['template_id']])->find();
            if($template && $template['content']){
                $template['content'] =  json_decode($template['content'],true);
            }
            $ret['template']  = $template;
        }
        return $ret;
    }

    public function getFilter($type  = 11){
        $where = [
            'type'   => $type,
        ];
        $ret   =   $this->where($where)->find();
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


    public function getTemplate($type  = 11, $where=["1"=>1]){
        $ret   =   $this->where($where)->find();
        if($ret && $ret['content'])
        {
            $ret['content']  = json_decode($ret['content'],true);
        }
        return $ret;
    }

}
