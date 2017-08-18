<?php
/**
 * 经销商代理结算文件
 */
defined('InHG') or exit('Access Invalid!');

class dealer_calc_fileModel extends Model {
    public function __construct() {
        parent::__construct('hg_dealer_calc_file');
    }

    /**
     * 结算文件列表
     *
     * @param array $condition
     * @param string $page
     * @return array
     */
    public function getCalcFileList($condition,$page=10) {
        return $this->where($condition)->page($page)->select();
    }

}