<?php
/**
 * 常用文件管理
 */

defined('InHG') or exit('Access Invalid!');

class store_filesControl extends BaseSellerControl {

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
        $file_list= Model()->table('hg_file_agent,hg_file,hg_file_cate')->join('inner,left')->on('hg_file_agent.file_id=hg_file.file_id,hg_file.cate_id=hg_file_cate.cate_id')->where('hg_file_agent.agent_id=1')->page(30)->select();

        Tpl::output('file_list',$file_list);
        Tpl::showpage('store_files');
    }
    public function add_fileOp()
    {
        $cate=Model('hg_file_cate')->select();
        $continue=$_GET['cate_id']?'hg_file.cate_id='.$_GET['cate_id']:'';
        $file_list= Model()->table('hg_file,hg_file_cate')->join('left')->on('hg_file.cate_id=hg_file_cate.cate_id')->where($continue)->page(30)->select();
        Tpl::output('cate',$cate);
        Tpl::output('file_list',$file_list);
        Tpl::showpage('add_file');
    }
    public function select_fileOp()
    {

        // 如果添加过了直接跳出
        $m=Model('hg_file_agent')->where(array('agent_id'=>$_SESSION['member_id'],'file_id'=>$_GET['id'],'isself'=>$_GET['isself']))->find();
        if ($m) {
            showMessage('已经添加过了', 'index.php?act=store_files&op=add_file');
        }

        $data = array(
                'agent_id'         => $_SESSION['member_id'],
                'file_id'          => trim($_GET['id']), 
                'isself'           =>$_GET['isself'],  
            );
        Model('hg_file_agent')->insert($data);
        showMessage('成功添加文件', 'index.php?act=store_files&op=add_file');

    }
    public function delOp()
    {
        Model('hg_file_agent')->delete($_GET['id']);

        showMessage('删除成功', 'index.php?act=store_files&op=index');
    }
}
