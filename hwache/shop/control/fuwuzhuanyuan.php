<?php
/**
 * 服务专员管理
 */

defined('InHG') or exit('Access Invalid!');

class fuwuzhuanyuanControl extends BaseSellerControl {

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
        $file_list= Model()->table('hg_waiter,hg_dealer')->join('left')->on('hg_waiter.dealer_id=hg_dealer.d_id')->where('hg_waiter.agent_id='.$_SESSION['member_id'])->page(30)->select();

        Tpl::output('file_list',$file_list);
        Tpl::showpage('fuwuzhuanyuan');
    }
    public function addOp()
    {

        $continue='hg_daili_dealer.dl_id='.$_SESSION['member_id'];
        $jingxiaoshang= Model()->table('hg_daili_dealer,hg_dealer')->join('left')->on('hg_daili_dealer.d_id=hg_dealer.d_id')->where($continue)->page(30)->select();

        Tpl::output('jingxiaoshang',$jingxiaoshang);
        Tpl::showpage('add_fuwuzhuanyuan');
    }
    public function updataOp()
    {

        // // 如果添加过了直接跳出
        // $m=Model('hg_file_agent')->where(array('agent_id'=>$_SESSION['member_id'],'file_id'=>$_GET['id'],'isself'=>$_GET['isself']))->find();
        // if ($m) {
        //     showMessage('已经添加过了', 'index.php?act=store_files&op=add_file');
        // }

        $data = array(
                'agent_id'         => $_SESSION['member_id'],
                'dealer_id'          => trim($_GET['dealer_id']), 
                'name'           =>$_GET['name'], 
                'mobile'           =>$_GET['mobile'],
                'tel'           =>$_GET['tel'],
                'notice'           =>$_GET['notice'],

            );
        Model('hg_waiter')->insert($data);
        showMessage('添加成功', 'index.php?act=fuwuzhuanyuan&op=index');

    }
    public function delOp()
    {
        Model('hg_file_agent')->delete($_GET['id']);

        showMessage('删除成功', 'index.php?act=store_files&op=index');
    }
}
