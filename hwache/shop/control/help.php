<?php
/**
 * 商品品牌管理
 */
defined('InHG') or exit('Access Invalid!');

class helpControl extends Control{

    /**
     * 解决编辑器跨域问题
     */
    public function kindeditorOp(){
        echo base64_decode($_GET['d']);
    }

}
