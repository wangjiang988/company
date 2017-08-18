<?php
/**
 * 网站设置
 */
defined('InHG') or exit('Access Invalid!');
class fwsettingControl extends SystemControl{

	public function __construct(){
		parent::__construct();
	}

	/**
	 * 系统金额设置
	 */
	public function moneyOp() {
		$model_hg_money = Model('hg_money');
		// 检测是否有提交表单操作
		if(chksubmit()) {
			//var_dump($_POST);
			unset($_POST['form_submit']);
			$m = Model('hg_money');
			foreach ($_POST as $k => $v) {
				$m->where(array('name'=>$k))->update(array('value'=>$v));
			}
			showMessage('修改成功','index.php?act=fwsetting&op=money');
		} else {
			$list_setting = $model_hg_money->getListSetting();
			Tpl::output('list_setting',$list_setting);
			Tpl::showpage('fwsetting.money');
		}
	}

	/**
	 * 保险公司管理
	 */
	public function baoxianOp() {
		$model_hg_baoxian = Model('hg_baoxian');
		// 检测是否有提交表单操作
		if(chksubmit()) {
			var_dump($_POST);
			// 先删除缓存在生成缓存
			H('bxCom',null);
            $bxCom = $model_hg_baoxian->field('bx_id,bx_title')->where('1=1')->select();
            if (!empty($bxCom)) {
                $tmpArr = array();
                foreach ($bxCom as $key => $value) {
                    $tmpArr[$value['bx_id']] = $value['bx_title'];
                }
                $bxCom = $tmpArr;
                unset($tmpArr);
                F('bxCom', $bxCom);
            }
		} else {
			$list = $model_hg_baoxian->page(10)->order('bx_sort,bx_id')->select();
			Tpl::output('page',$model_hg_baoxian->showpage());

			Tpl::output('list',$list);


			Tpl::showpage('fwsetting.baoxian');
		}
	}
	public function baoxianaddOp(){
		if(chksubmit()) {
			$data = array(

    			'bx_is_quanguo'=>$_POST['bx_is_quanguo'],
    			'bx_name'=>$_POST['bx_name'],
         		'bx_title'=>$_POST['bx_title'],
         		'bx_sort'=>$_POST['bx_sort'],
			);
			$r=Model('hg_baoxian')->insert($data);
			if($r){
				//更新缓存文件
				$d=Model('hg_baoxian')->field('bx_title')->select();
				$a=array();
				foreach ($d as $key => $value) {
					$a[]=$value['bx_title'];
				}
				F('bxCom',$a);
				showMessage('添加成功','index.php?act=fwsetting&op=baoxian');
			}else{
				showMessage('添加失败','index.php?act=fwsetting&op=baoxian');
			}

		}else{

			Tpl::showpage('fwsetting.baoxianadd');
		}

	}
	public function zengpinOp(){
		$model_zengpin = Model('hg_zengpin');

        $where  = [];
        if(is_search())
        {
            $file_list  = [
                'title|like'
            ];
            $where  = trans_form_to_where($file_list);
        }

		if(chksubmit()) {


		}else{
			$list = Model()->table('hg_zengpin,goods_class')
                    ->field('*')->join('left')->on('hg_zengpin.brand_id=goods_class.gc_id')
                    ->where($where)
                    ->page(10)->select();
			Tpl::output('page',$model_zengpin->showpage());

			Tpl::output('list',$list);
			Tpl::showpage('fwsetting.zengpin');
		}
	}
	public function zengpinaddOp(){

		if(chksubmit()) {

			$model_zengpin = Model('hg_zengpin');
			$data = array('title' => $_POST['title'],'price'=>$_POST['price'] ,'brand_id'=>$_POST['brand_id'],'beizhu'=>$_POST['beizhu']);
			$model_zengpin->insert($data);
			showMessage('添加成功','index.php?act=fwsetting&op=zengpin');

		}else{
			$model_class = Model('goods_class');
			$parent_list = $model_class->getTreeClassList(3);

        	Tpl::output('parent_list', $parent_list);
			Tpl::showpage('fwsetting.zengpin_add');
		}

	}
	public function zengpineditOp(){
		$id=$_GET['id'];
		Tpl::output('id', $id);
		$model_zengpin = Model('hg_zengpin');
		if(chksubmit()) {
			$data = array('title' => $_POST['title'],'price'=>$_POST['price'] ,'brand_id'=>$_POST['brand_id'],'beizhu'=>$_POST['beizhu']);
			$model_zengpin->where(array('id'=>$id))->update($data);
			showMessage('修改成功','index.php?act=fwsetting&op=zengpin');

		}else{
			$zengpin=$model_zengpin->where('id='.$id)->find();
			Tpl::output('zengpin', $zengpin);
			$model_class = Model('goods_class');
			$parent_list = $model_class->getTreeClassList(3);
			Tpl::output('parent_list', $parent_list);
			Tpl::showpage('fwsetting.zengpin_edit');
		}

	}
	public function zengpindelOp(){
		$model_zengpin = Model('hg_zengpin');
		$model_zengpin->where(array('id'=>$_GET['id']))->delete();

		showMessage('删除成功','index.php?act=fwsetting&op=zengpin');

	}
	public function baoxian_editOp(){
		if(chksubmit()) {
			$data = array(
				'bx_id'=>$_POST['bx_id'],
    			'bx_is_quanguo'=>$_POST['bx_is_quanguo'],
    			'bx_name'=>$_POST['bx_name'],
         		'bx_title'=>$_POST['bx_title'],
         		'bx_sort'=>$_POST['bx_sort'],
			);
			Model('hg_baoxian')->where(array('bx_id'=>$_POST['id']))->update($data);
			//更新缓存文件
			$d=Model('hg_baoxian')->field('bx_title')->select();
			$a=array();
			foreach ($d as $key => $value) {
				$a[]=$value['bx_title'];
			}
			F('bxCom',$a);

			showMessage('修改成功','index.php?act=fwsetting&op=baoxian');
		}else{
			$list=Model('hg_baoxian')->where(array('bx_id'=>$_GET['id']))->find();
			Tpl::output('list',$list);
			Tpl::showpage('fwsetting.baoxian_edit');
		}
	}
	public function baoxian_delOp(){
		Model('hg_baoxian')->where(array('bx_id'=>$_GET['id']))->delete();
		//更新缓存文件
			$d=Model('hg_baoxian')->field('bx_title')->select();
			$a=array();
			foreach ($d as $key => $value) {
				$a[]=$value['bx_title'];
			}
			F('bxCom',$a);
		showMessage('删除成功','index.php?act=fwsetting&op=baoxian');
	}
	// 交车所需要的文件资料
	public function filesOp()
	{
		$where  = [];
		if(is_search())
		{
			$file_list  = [
                'car_use_type|eq|hg_file',
				'title|like|hg_file',
                'identity_id|eq|hg_file',
                'cate_id|eq|hg_file_cate'
			];
			$where  = trans_form_to_where($file_list);
		}

        $model  =  Model('hc_file');
//		if($where['hg_file.car_use_type'] !=2)
//            $where['hg_file.cate']=['exp','hg_file.car_use_type= hc_dealer_identity.identity_type'];
		$list  = $model->table('hg_file,hg_file_cate,hc_dealer_identity')
                        ->field('hc_dealer_identity.identity_id,
                                 hc_dealer_identity.id,
                                hc_dealer_identity.identity_name,
                                hg_file_cate.cate,
                                hg_file.title,
                                hg_file.car_use_type,
                                hg_file.file_id             
                            ')
                        ->join('left,left')
                        ->on('hg_file.cate_id=hg_file_cate.cate_id,hg_file.identity_id=hc_dealer_identity.id')
                        ->where($where)
                        ->order('file_id desc')
                        ->page(10)
                        ->select();

//		json($list);
		//查询使用场合
        $cate_list = Model('hg_file_cate')->select();
        Tpl::output('cate_list', $cate_list);
		Tpl::output('list', $list);
		Tpl::output('page', Model()->showPage());
		Tpl::showpage('fwsetting.files');

	}

	//根据车辆类型获取身份
    public function get_identity_by_carusetypeOp()
    {
        $request = getPayload();
        if(!isset($request['car_use_type']) ||  $request['car_use_type']==='')
        {
            json_error('参数错误');
        }

        $list  = Model('hc_dealer_identity')->where([
                    'identity_type' =>$request['car_use_type'],
            ])->select();

        if($list)
        {
            json_succ('200', $list);
        }else {
            json_error('没有结果');
        }
    }
    //根据车辆类型获取场合
    public function get_cate_by_carusetypeOp()
    {
        $request = getPayload();
        if(!isset($request['car_use_type']) || $request['car_use_type']==='')
        {
            json_error('参数错误');
        }
        $where = [];
        if($request['car_use_type']==2){
            $where['is_other'] = 1;
        }
        $list  = Model('hg_file_cate')->where($where)->select();

        if($list)
        {
            json_succ('200', $list);
        }else {
            json_error('没有结果');
        }

    }


	public function fileaddOp()
	{
		if(chksubmit()) {
			$data = array(
			    'title' => $_POST['title'],
                'cate_id'=>$_POST['cate_id'],
                'identity_id'=>$_POST['identity_id'],
                'car_use_type' =>$_POST['car_use_type'],
            );
			$i=Model('hg_file')->insert($data);
			if ($i) {
				showMessage('添加成功','index.php?act=fwsetting&op=files');
			}else{
				exit('添加失败');
			}

		}else{
			$list = Model('hg_file_cate')->select();
        	Tpl::output('list', $list);
			Tpl::showpage('fwsetting.fileadd');
		}


	}
	public function fileeditOp(){

		if(chksubmit()) {

			$data = array(
                'title' => $_POST['title'],
                'cate_id'=>$_POST['cate_id'],
                'identity_id'=>$_POST['identity_id'],
                'car_use_type' =>$_POST['car_use_type'],
            );
			Model('hg_file')->where(array('file_id'=>$_POST['file_id']))->update($data);
			showMessage('修改成功','index.php?act=fwsetting&op=files');

		}else{
			$file=Model('hg_file')->where(array('file_id' =>$_GET['id'] ))->find();
			Tpl::output('file', $file);
			$list = Model('hg_file_cate')->select();
        	Tpl::output('list', $list);
			Tpl::showpage('fwsetting.fileedit');
		}

	}

    public function filedelOp(){

        Model('hg_file')->where(array('file_id'=>$_GET['id']))->delete();
        showMessage('删除成功','index.php?act=fwsetting&op=files');
    }

	public function cateaddOp()
	{
		if(chksubmit()) {
			$data = array('cate' => $_POST['cate'],'regular'=>$_POST['regular']);
			$i=Model('hg_file_cate')->insert($data);
			if ($i) {
				showMessage('添加成功','index.php?act=fwsetting&op=files');
			}else{
				exit('添加失败');
			}

		}else{

			Tpl::showpage('fwsetting.cateadd');
		}


	}
	// 其他商业保险
	public function otherBaoxianOp()
	{
		$list = Model()->table('hg_other_baoxian')->page(30)->select();

		Tpl::output('list', $list);
		Tpl::showpage('fwsetting.otherbaoxian');

	}
	public function otherBaoxianAddOp()
	{
		if(chksubmit()) {
			$data = array('baoxian_name' => $_POST['title'],'zhuxian'=>$_POST['cate_id']);
			$i=Model('hg_other_baoxian')->insert($data);
			if ($i) {
				showMessage('添加成功','index.php?act=fwsetting&op=otherbaoxian');
			}else{
				exit('添加失败');
			}

		}else{
			$zhuxian=C('otherbaoxian');
			Tpl::output('zhuxian', $zhuxian);
			Tpl::showpage('fwsetting.otherbaoxianadd');
		}

	}
	// 修改其他保险
	public function otherBaoxianEditOp()
	{
		if(chksubmit()) {
			$data = array('baoxian_name' => $_POST['title'],'zhuxian'=>$_POST['cate_id'],'id'=>$_POST['id']);

			if (Model('hg_other_baoxian')->update($data)) {
				showMessage('修改成功','index.php?act=fwsetting&op=otherbaoxian');
			}else{
				exit('修改失败');
			}

		}else{
			$zhuxian=C('otherbaoxian');
			Tpl::output('zhuxian', $zhuxian);
			$baoxian=Model('hg_other_baoxian')->find($_GET['id']);
			Tpl::output('baoxian', $baoxian);

			Tpl::showpage('fwsetting.otherbaoxianedit');
		}

	}
	// 客户提交的特殊文件
	public function tellusOp()
	{

		$continue=$_GET['status']?' status='.$_GET['status']:' status=0';

		$list = Model()->table('hg_tellus')->where($continue)->page(30)->select();

		Tpl::output('list', $list);
		Tpl::showpage('fwsetting.tellus');

	}
	// 如果已在后台添加则将状态设置为已添加
	public function dotellusOp()
	{
		$data = array('status' => 1,'id'=>$_GET['id']);

			if (Model('hg_tellus')->update($data)) {
				showMessage('处理成功','index.php?act=fwsetting&op=tellus');
			}else{
				exit('修改失败');
			}
	}

	/**
	 * 随车工具列表页
	 */
	public function suicheOp(){
		$model = Model();

		$condition = array();
		$goods_class = Model('goods_class')->getGoodsClassList(array('gc_parent_id'=>0));
		if(!empty($_GET['car_brand'])){//车型 品牌
			$chexi = Model('goods_class')->getGoodsClassList(array('gc_parent_id'=>$_GET['car_brand']));
			$condition['c_brand'] = $_GET['car_brand'];
		}else{
			$chexi = array();
		}
		if(!empty($_GET['car_chexi'])){//车系
			$chexing = Model('goods_class')->getGoodsClassList(array('gc_parent_id'=>$_GET['car_chexi']));
			$condition['c_chexi'] = $_GET['car_chexi'];
		}else{
			$chexing = array();
		}

		if(!empty($_GET['car_chexing'])){//车型
			$condition['c_id'] = $_GET['car_chexing'];
		}
		if(!empty($_GET['type'])){//随车工具类型
			$condition['type'] = $_GET['type'];
		}
		if(!empty($_GET['public'])){//随车工具类型
			$condition['public'] = $_GET['public'];
		}
		if(!empty($_GET['title'])){//随车工具名称
			$condition['title'] = array("like","%".$_GET['title']."%");
		}
		$list= $model->table('hg_annex')->where($condition)->page(10)->select();
		Tpl::output('goods_class',$goods_class);
		Tpl::output('chexi',$chexi);
		Tpl::output('chexing',$chexing);
		Tpl::output('list',$list);
		Tpl::output('page',$model->showpage());
		Tpl::showpage('fwsetting.suiche');
	}
	/**
	 * 随车工具编辑
	 */
	public function suicheeditOp(){

		if($_POST['form_submit']=='ok'){
			if(intval($_POST['public'])==1){
				$data = array(
						'c_brand'=>0,
						'c_chexi'=>0,
						'c_id'=>0,
						'car_name'=>"通用",
						'title'=>$_POST['title'],
						'num'=>$_POST['num'],
						'notice'=>$_POST['note'],
						'type'=>$_POST['type'],
						'public'=>1,
				);
			}else{
				$data = array(
						'c_brand'=>$_POST['car_brand'],
						'c_chexi'=>$_POST['car_chexi'],
						'c_id'=>$_POST['car_chexing'],
						'car_name'=>$_POST['car_name'],//:"通用的随车工具",
						'title'=>$_POST['title'],
						'num'=>$_POST['num'],
						'notice'=>$_POST['note'],
						'type'=>$_POST['type'],
						'public'=>0,
				);
			}

			if(intval($_POST['id'])==0){//新增
				$checkCondition = array(
						'title'=>$_POST['title'],
						'type'=>$_POST['type'],
				);
				$checkExist = Model('hg_annex')->where($checkCondition)->count();
				if($checkExist>=1){
					echo "名称重复，三秒后自动返回";
					echo "<script>setTimeout('window.history.back()',3000);</script>";
					exit;
				}
				$e = Model('hg_annex')->insert($data);
			}else{//更新
				$checkCondition =" title = '".$_POST['title']."' and id !=".$_POST['id'];
				$checkCondition .= " and type ='".$_POST['type']."'";
				$checkExist = Model('hg_annex')->where($checkCondition)->count();

				if($checkExist>=1){
					echo "名称重复，三秒后自动返回";
					echo "<script>setTimeout('window.history.back()',3000);</script>";
					exit;
				}
				$e = Model('hg_annex')->where(array('id'=>$_POST['id']))->update($data);
				$id = $_POST['id'];
			}
			if(!$e){
				showMessage('保存失败','index.php?act=fwsetting&op=suiche');
			}else{
				if(intval($_POST['id']) ==0){
					showMessage('添加成功','index.php?act=fwsetting&op=suiche');
				}else{
					showMessage('保存成功','index.php?act=fwsetting&op=suicheedit&id='.$id);
				}
			}
		}
		$id = $_GET['id'];
		$public  = !empty($_GET['public'])?$_GET['public']:0;
		if($id==0){
			$suicheInfo = array(
					'id'=>0,
					'title'=>'',
					'num'=>'',
					'notice'=>'',
					'c_brand'=>0,
					'public'=>0,
			);
			$chexi = array();
			$chexing = array();
		}else{
			$suicheInfo = Model('hg_annex')->where(array('id'=>$id))->find();
			$chexi = Model('goods_class')->getGoodsClassList(array('gc_parent_id'=>$suicheInfo['c_brand']));
			$chexing = Model('goods_class')->getGoodsClassList(array('gc_parent_id'=>$suicheInfo['c_chexi']));
			$public = $suicheInfo['public'];
		}
		// 车型品牌
		$goods_class = Model('goods_class')->getGoodsClassList(array('gc_parent_id'=>0));
		Tpl::output('goods_class',$goods_class);
		Tpl::output('suicheInfo',$suicheInfo);
		Tpl::output('chexi',$chexi);
		Tpl::output('chexing',$chexing);
		Tpl::output('public',$public);

		Tpl::showpage('fwsetting.suiche.add');
	}
	/**
	 * 随车工具删除
	 */
	public function suichedelOp(){
		// 车型品牌
		$e = Model('hg_annex')->where(array('id'=>$_GET['id']))->delete();
		if(!$e){
			showMessage('删除失败','index.php?act=fwsetting&op=suiche');
		}else{
			showMessage('删除成功','index.php?act=fwsetting&op=suiche');
		}
	}

	/**
	 * ajax 动态获取
	 */
	public function ajax_getOp(){
		$type  = $_GET['type'];
		if($type =='car'){
			$brand = intval($_GET['brand']);
			$goods_class = Model('goods_class')->getGoodsClassList(array('gc_parent_id'=>$brand));
			echo json_encode($goods_class);
		}
	}
}