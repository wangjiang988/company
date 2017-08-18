<?php
/**
 * 服务专员管理
 */

defined('InHG') or exit('Access Invalid!');

class yijiaoControl extends BaseSellerControl {

    public function __construct() {
        parent::__construct();
        Language::read('member_store_index');
    }

    /**
     * 文件列表
     *
     */
    public function indexOp()
    {
        $file_list= Model()->table('hg_annex,goods_class')->join('left')->on('hg_annex.c_id=goods_class.gc_id')->where('hg_annex.member_id='.$_SESSION['member_id'])->page(30)->select();

        Tpl::output('file_list',$file_list);
        Tpl::showpage('yijiao');
    }
    public function addOp()
    {

        // 实例化商品分类模型
        $model_goodsclass = Model('goods_class');
        // 商品分类
        $goods_class = $model_goodsclass->getGoodsClass($_SESSION['store_id']);

        // 常用商品分类
        $model_staple = Model('goods_class_staple');
        $param_array = array();
        $param_array['member_id'] = $_SESSION['member_id'];
        $staple_array = $model_staple->getStapleList($param_array);

        Tpl::output('staple_array', $staple_array);
        Tpl::output('goods_class', $goods_class);
        Tpl::showpage('add_yijiao');
    }
    public function updataOp()
    {

        // // 如果添加过了直接跳出
        // $m=Model('hg_file_agent')->where(array('agent_id'=>$_SESSION['member_id'],'file_id'=>$_GET['id'],'isself'=>$_GET['isself']))->find();
        // if ($m) {
        //     showMessage('已经添加过了', 'index.php?act=store_files&op=add_file');
        // }
        if(!$_GET['class_id']) exit('请选择车型');
        $data = array(
                'member_id'         => $_SESSION['member_id'],
                'c_id'          => trim($_GET['class_id']), 
                'title'           =>$_GET['title'], 
                'num'           =>$_GET['num'],
                'type'           =>$_GET['type'],
                'notice'        =>$_GET['notice'],
            );
        Model('hg_annex')->insert($data);
        showMessage('添加成功', 'index.php?act=yijiao&op=index');

    }
    public function delOp()
    {
        Model('hg_annex')->delete($_GET['id']);

        showMessage('删除成功', 'index.php?act=store_files&op=index');
    }
}
