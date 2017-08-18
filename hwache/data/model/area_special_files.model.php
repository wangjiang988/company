<?php
/**
 * 特殊文件模型
 */
defined('InHG') or exit('Access Invalid!');

class area_special_filesModel extends Model{

    public function __construct() {
        parent::__construct('area_special_files');
    }


   public function getList($where, $order='area_special_files.created_at desc')
   {
        $ret = $this->table('area_special_files,users')
                ->join('left')
                ->on('area_special_files.user_id = users.id')
                ->field('area_special_files.*, users.name, users.phone')
                ->where($where)
                ->order($order)->select();
        return $ret;
   }


}