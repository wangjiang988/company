<?php
/**
 * 经销商代理配置保险功能
 */

defined('InHG') or exit('Access Invalid!');

class store_baoxianControl extends BaseSellerControl {

    private $bxcfg;
    public function __construct() {
        parent::__construct();
        // 加载保险配置文件
        $this->bxcfg = require_once(BASE_DATA_PATH.'/config/baoxian.ini.php');
    }

    public function indexOp() {
        // 保险列表
        $m = Model('hg_dealer_baoxian');
        $list = $m->field('*')
              ->where('m_id='.$_SESSION['member_id'])
              ->page(10)
              ->order('is_enable DESC,id ASC')
              ->select();
        Tpl::output('list', $list); // 保险列表
        Tpl::output('show_page',$m->showpage()); // 分页

        // 查询保险公司
        $bxCom = F('bxCom');
        if (empty($bxCom)) {
            $bxCom = Model('hg_baoxian')->field('bx_id,bx_title')->where('m_id='.$_SESSION['member_id'])->select();
            if (!empty($bxCom)) {
                $tmpArr = array();
                foreach ($bxCom as $key => $value) {
                    $tmpArr[$value['bx_id']] = $value['bx_title'];
                }
                $bxCom = $tmpArr;
                unset($tmpArr);
                F('bxCom', $bxCom);
            }
        }
        Tpl::output('bxCom', $bxCom); // 保险公司列表

        Tpl::showpage('store_baoxian.index');
    }

    /**
     * 添加保险公司和保险费率设置
     */
    public function addOp() {
        if (chksubmit()) {
            $data = array (
                'm_id'      => $_SESSION['member_id'],
                'co_id'     => intval($_POST['co_id']),
                'title'     => trim($_POST['title']),
                'is_enable' => intval($_POST['enable']),
                'is_default' => intval($_POST['is_default']),
                'add_time'  => time()
            );
            $m = Model('hg_dealer_baoxian');
            // 先查询是否已经有同标题的数据
            $map = array (
                'm_id'  => $_SESSION['member_id'],
                'title' => $data['title']
            );
            $v = $m->where($map)->count();
            if (empty($v)) {
                $r = $m->insert($data);
                if ($r) {
                    redirect(urlShop('store_baoxian', 'set', array('id' => $r)));
                } else {
                    showMessage('添加失败', getReferer(), 'html', 'error');
                }
            } else {
                showMessage('已存在同名的记录', getReferer(), 'html', 'error');
            }
        } else {
            // 查询保险公司
            $bxCom = F('bxCom');
            if (empty($bxCom)) {
                $bxCom = Model('hg_baoxian')->field('bx_id,bx_title')->where('1=1')->select();
                if (!empty($bxCom)) {
                    $tmpArr = array();
                    foreach ($bxCom as $key => $value) {
                        $tmpArr[$value['bx_id']] = $value['bx_title'];
                    }
                    $bxCom = $tmpArr;
                    unset($tmpArr);
                    F('bxCom', $bxCom);
                }
            }
            Tpl::output('bxCom', $bxCom); // 保险公司列表

            Tpl::output('edit_baoxian', false); // 这里是添加保险,把编辑保险标记设置false
            Tpl::showpage('store_baoxian.add');
        }
    }

    /**
     * 设置保险费率相关
     */
    public function setOp() {
        if (chksubmit()) {
            $baoxian_id = intval($_POST['baoxian_id']); // 保险ID
            // 更新设置标示
            $data = array(
                    'is_set' =>1 ,
                    'id'=>$baoxian_id );
                Model('hg_dealer_baoxian')->update($data);

            // 车损险数据
            $chesun = $_POST['chesun'];
            $chesun_arr = array ();
            foreach ($chesun as $key => $value) {
                $chesun_arr[] = array (
                    'type'          => $key,
                    'baoxian_id'    => $baoxian_id,
                    'base'          => trim($value['base']),
                    'rate'          => trim($value['rate'])
                );
            }
            Model('hg_dealer_baoxian_chesun')->insertAll($chesun_arr);

            // 盗抢险数据
            $daoqiang = $_POST['daoqiang'];
            $daoqiang_arr = array ();
            foreach ($daoqiang as $key => $value) {
                $daoqiang_arr[] = array (
                    'type'          => $key,
                    'baoxian_id'    => $baoxian_id,
                    'base'          => trim($value['base']),
                    'rate'          => trim($value['rate'])
                );
            }
            Model('hg_dealer_baoxian_daoqiang')->insertAll($daoqiang_arr);

            // 三者险
            $sanzhe = $_POST['sanzhe'];
            $sanzhe_arr = array ();
            foreach ($sanzhe as $key => $value) {
                foreach ($value as $k => $v) {
                    $sanzhe_arr[] = array (
                        'type'          => $k,
                        'baoxian_id'    => $baoxian_id,
                        'base'          => $key,
                        'price'          => trim($v['base'])
                    );
                }
            }
            Model('hg_dealer_baoxian_sanzhe')->insertAll($sanzhe_arr);

            // 车上人员责任险
            $renyuan = $_POST['renyuan'];
            $renyuan_arr = array ();
            foreach ($renyuan as $key => $value) {
                $title = '';
                if ($key=='sj') {
                    $title = '司机';
                } else if ($key=='ck') {
                    $title = '乘客';
                }
                foreach ($value as $k => $v) {
                    foreach ($v as $_k => $_v) {
                        $renyuan_arr[] = array (
                            'type'      => $_k,
                            'baoxian_id'=> $baoxian_id,
                            'title'     => $title,
                            'base'      => $k,
                            'rate'      => $_v['rate']
                        );
                    }
                }
            }
            Model('hg_dealer_baoxian_renyuan')->insertAll($renyuan_arr);

            // 玻璃单独破碎险
            $boli = $_POST['boli'];
            $boli_arr = array ();
            foreach ($boli as $key => $value) {
                $title = '';
                if ($key=='jk') {
                    $title = '进口';
                } else if ($key=='gc') {
                    $title = '国产';
                }
                foreach ($value as $k => $v) {
                    $boli_arr[] = array (
                        'type'      => $k,
                        'baoxian_id'=> $baoxian_id,
                        'title'     => $title,
                        'rate'      => $v['rate']
                    );
                }
            }
            Model('hg_dealer_baoxian_boli')->insertAll($boli_arr);

            // 自燃损失险
            $ziran = $_POST['ziran'];
            $ziran_arr = array ();
            foreach ($ziran as $key => $value) {
                foreach ($value as $k => $v) {
                    $ziran_arr[] = array (
                        'type'      => $k,
                        'baoxian_id'=> $baoxian_id,
                        'rate'      => $v['rate']
                    );
                }
            }
            Model('hg_dealer_baoxian_ziran')->insertAll($ziran_arr);

            // 车身划痕险
            $huahen = $_POST['huahen'];
            $huahen_arr = array ();
            foreach ($huahen as $key => $value) {
                $title = '';
                if ($key==30) {
                    $title = '30万以下';
                } else if ($key==3050) {
                    $title = '30~50万';
                } else if ($key==50) {
                    $title = '50万以上';
                }
                foreach ($value as $k => $v) {
                    foreach ($v as $_k => $_v) {
                        $huahen_arr[] = array (
                            'type'      => $_k,
                            'baoxian_id'=> $baoxian_id,
                            'title'     => $title,
                            'peifu'     => $k,
                            'price'     => $_v['peifu']
                        );
                    }
                }
            }
            Model('hg_dealer_baoxian_huahen')->insertAll($huahen_arr);

            // 不计免赔险
            $bujimian = $_POST['bujimian'];
            $bujimian_arr = array ();
            foreach ($bujimian as $key => $value) {
                foreach ($value as $k => $v) {
                    $bujimian_arr[] = array (
                        'type'          => $k,
                        'baoxian_id'    => $baoxian_id,
                        'baoxian_type'  => $key,
                        'rate'          => $v['baoxian_type']
                    );
                }
            }
            Model('hg_dealer_baoxian_bujimian')->insertAll($bujimian_arr);

            showMessage('添加完成', urlShop('store_baoxian', 'index'));

            // $data = array (
            //     'chesun'    => $chesun_arr,
            //     'daoqiang'    => $daoqiang_arr,
            //     'sanzhe'    => $sanzhe_arr,
            //     'renyuan'    => $renyuan_arr,
            //     'boli'    => $boli_arr,
            //     'ziran'    => $ziran_arr,
            //     'huahen'    => $huahen_arr,
            //     'bujimian'    => $bujimian_arr,
            // );
            // var_dump($data);

        } else {
            // 保险配置文件
            // 车辆座位
            Tpl::output('baoxian_seat_num', $this->bxcfg['baoxian_seat_num']);
            // 保险种类,模板中主要循环数据
            Tpl::output('baoxianType', $this->bxcfg['baoxianType']);

            // 保险ID
            Tpl::output('baoxian_id', intval($_GET['id']));

            Tpl::output('edit_baoxian', false); // 这里是添加保险,把编辑保险标记设置false
            Tpl::showpage('store_baoxian.set');
        }

    }
    // 删除用户设置的保险，同时删除各种险种
   public function delOp(){
   		//删除车损险
   		Model('hg_dealer_baoxian_chesun')->where(array('baoxian_id'=>$_GET['id']))->delete();
   		//盗抢险
		Model('hg_dealer_baoxian_daoqiang')->where(array('baoxian_id'=>$_GET['id']))->delete();
		// 三者险
		Model('hg_dealer_baoxian_sanzhe')->where(array('baoxian_id'=>$_GET['id']))->delete();
		// 车上人员责任险
		Model('hg_dealer_baoxian_renyuan')->where(array('baoxian_id'=>$_GET['id']))->delete();
		// 玻璃单独破碎险
		Model('hg_dealer_baoxian_boli')->where(array('baoxian_id'=>$_GET['id']))->delete();
		// 自燃损失险
		Model('hg_dealer_baoxian_ziran')->where(array('baoxian_id'=>$_GET['id']))->delete();
		// 车身划痕险
		Model('hg_dealer_baoxian_huahen')->where(array('baoxian_id'=>$_GET['id']))->delete();
		// 不计免赔险
		Model('hg_dealer_baoxian_bujimian')->where(array('baoxian_id'=>$_GET['id']))->delete();


   		$model_daili_dealer = Model('hg_dealer_baoxian');
		$model_daili_dealer->delete($_GET['id']);

		showMessage('删除成功','index.php?act=store_baoxian&op=index');

    }
    public function editOp() {
        if (chksubmit()) {
            $baoxian_id=intval($_POST['baoxian_id']);
            //更新车损险
            $csx=$_POST['chesun'];
            foreach ($csx as $key => $value) {
                $data = array(
                    'base' =>$value['base'] ,
                    'rate'=>$value['rate'],
                    'id'=>$key );
                Model('hg_dealer_baoxian_chesun')->update($data);
            }
           // 更新盗抢险
            foreach ($_POST['daoqiang'] as $key => $value) {
                $data = array(
                    'base' =>$value['base'] ,
                    'rate'=>$value['rate'],
                    'id'=>$key );
                Model('hg_dealer_baoxian_daoqiang')->update($data);
            }
            // 更新第三者责任险
            foreach ($_POST['sanzhe'] as $key=>$value) {
                $data = array(
                    'price' =>$value ,     
                    'id'=>$key );
                Model('hg_dealer_baoxian_sanzhe')->update($data);
            }
            //更新车上人员责任险
            foreach ($_POST['renyuan'] as $key=>$value) {
                $data = array(
                    'rate' =>$value ,     
                    'id'=>$key );
                Model('hg_dealer_baoxian_renyuan')->update($data);
            }
            // 更新玻璃单独破碎险
            foreach ($_POST['boli'] as $key=>$value) {
                $data = array(
                    'rate' =>$value ,     
                    'id'=>$key );
                Model('hg_dealer_baoxian_boli')->update($data);
            }
            // 更新自燃险
            foreach ($_POST['ziran'] as $key=>$value) {
                $data = array(
                    'rate' =>$value ,     
                    'id'=>$key );
                Model('hg_dealer_baoxian_ziran')->update($data);
            }
            // 更新车身划痕损失险
            foreach ($_POST['huahen'] as $key=>$value) {
                $data = array(
                    'price' =>$value ,     
                    'id'=>$key );
                Model('hg_dealer_baoxian_huahen')->update($data);
            }
            // 不计免赔特约险
            foreach ($_POST['bujimian'] as $key=>$value) {
                $data = array(
                    'rate' =>$value ,
                    'id'=>$key );
                Model('hg_dealer_baoxian_bujimian')->update($data);
            }

            showMessage('更新成功','index.php?act=store_baoxian&op=index');
        }else{
            $baoxian_id=$_GET['id'];
            // 判断是否设置过费率，如果没跳到设置页面
            $is_set=Model('hg_dealer_baoxian')->where(array('id'=>$_GET['id']))->find();
            if($is_set['is_set']==0){
                redirect(urlShop('store_baoxian', 'set', array('id' => $baoxian_id)));                
            }
        // 取得车损险
        $csx=Model()->table('hg_dealer_baoxian_chesun')->where('baoxian_id='.$baoxian_id)->order('type asc')->select();
        Tpl::output('csx',$csx);   
        //盗抢险
        $dqx=Model()->table('hg_dealer_baoxian_daoqiang')->where('baoxian_id='.$baoxian_id)->order('type asc')->select();
        Tpl::output('dqx',$dqx); 
        //第三者险
        $sanzhe=Model()->table('hg_dealer_baoxian_sanzhe')->where('baoxian_id='.$baoxian_id)->order('type asc')->select();
        Tpl::output('sanzhe',$sanzhe); 
        //车上人员责任险
        $renyuan=Model()->table('hg_dealer_baoxian_renyuan')->where('baoxian_id='.$baoxian_id)->order('type asc')->select();

        Tpl::output('renyuan',$renyuan); 
        //玻璃险
        $boli=Model()->table('hg_dealer_baoxian_boli')->where('baoxian_id='.$baoxian_id)->order('type asc')->select();

        Tpl::output('boli',$boli);
        //自燃险
        $ziran=Model()->table('hg_dealer_baoxian_ziran')->where('baoxian_id='.$baoxian_id)->order('type asc')->select();

        Tpl::output('ziran',$ziran);
        //不计免赔特约险
        $bujimian=Model()->table('hg_dealer_baoxian_bujimian')->where('baoxian_id='.$baoxian_id)->order('type asc')->select();
        Tpl::output('bujimian',$bujimian);
        //划痕险
        $huahen=Model()->table('hg_dealer_baoxian_huahen')->where('baoxian_id='.$baoxian_id)->order('type asc')->select();
        Tpl::output('huahen',$huahen);

        Tpl::output('baoxian_id',$baoxian_id);
        Tpl::showpage('store_baoxian_edit_set');

        }
        
    }

}
