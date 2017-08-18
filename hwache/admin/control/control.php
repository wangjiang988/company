<?php
/**
 * 系统后台公共方法
 *
 * 包括系统后台父类
 *
 */
defined('InHG') or exit('Access Invalid!');
class SystemControl{

	/**
	 * 管理员资料 name id group
	 */
	protected $admin_info;

	/**
	 * 权限内容
	 */
	protected $permission;
	protected function __construct(){
		Language::read('common,layout');
		/**
		 * 验证用户是否登录
		 * $admin_info 管理员资料 name id
		 */
		$this->admin_info = $this->systemLogin();
		if ($this->admin_info['id'] != 1){
			// 验证权限
			$this->checkPermission();
		}
		//转码  防止GBK下用ajax调用时传汉字数据出现乱码
		if (($_GET['branch']!='' || $_GET['op']=='ajax') && strtoupper(CHARSET) == 'GBK'){
			$_GET = Language::getGBK($_GET);
		}
	}

	/**
	 * 取得当前管理员信息
	 *
	 * @param
	 * @return 数组类型的返回结果
	 */
	protected final function getAdminInfo(){
		return $this->admin_info;
	}

	/**
	 * 系统后台登录验证
	 *
	 * @param
	 * @return array 数组类型的返回结果
	 */
	protected final function systemLogin(){
		//取得cookie内容，解密，和系统匹配
		$user = unserialize(decrypt(cookie('sys_key'),MD5_KEY));
		if (!array_key_exists('gid',(array)$user) || !isset($user['sp']) || (empty($user['name']) || empty($user['id']))){
			@header('Location: index.php?act=login&op=login');exit;
		}else {
			$this->systemSetKey($user);
		}
		return $user;
	}

	/**
	 * 系统后台 会员登录后 将会员验证内容写入对应cookie中
	 *
	 * @param string $name 用户名
	 * @param int $id 用户ID
	 * @return bool 布尔类型的返回结果
	 */
	protected final function systemSetKey($user){
		setNcCookie('sys_key',encrypt(serialize($user),MD5_KEY),3600,'',null);
	}

	/**
	 * 验证当前管理员权限是否可以进行操作
	 *
	 * @param string $link_nav
	 * @return
	 */
	protected final function checkPermission($link_nav = null){
		if ($this->admin_info['sp'] == 1) return true;

		$act = $_GET['act']?$_GET['act']:$_POST['act'];
		$op = $_GET['op']?$_GET['op']:$_POST['op'];
		if (empty($this->permission)){
			$gadmin = Model('gadmin')->getby_gid($this->admin_info['gid']);
			$permission = decrypt($gadmin['limits'],MD5_KEY.md5($gadmin['gname']));
			$this->permission = $permission = explode('|',$permission);
		}else{
			$permission = $this->permission;
		}
		//显示隐藏小导航，成功与否都直接返回
		if (is_array($link_nav)){
			if (!in_array("{$link_nav['act']}.{$link_nav['op']}",$permission) && !in_array($link_nav['act'],$permission)){
				return false;
			}else{
				return true;
			}
		}

		//以下几项不需要验证
		$tmp = array('index','dashboard','login','common','cms_base');
		if (in_array($act,$tmp)) return true;
		if (in_array($act,$permission) || in_array("$act.$op",$permission)){
			return true;
		}else{
			$extlimit = array('ajax','export_step1');
			if (in_array($op,$extlimit) && (in_array($act,$permission) || strpos(serialize($permission),'"'.$act.'.'))){
				return true;
			}
			//带前缀的都通过
			foreach ($permission as $v) {
			    if (!empty($v) && strpos("$act.$op",$v.'_') !== false) {
					return true;break;
				}
			}
		}
		showMessage(Language::get('nc_assign_right'),'','html','succ',0);
	}

	/**
	 * 取得后台菜单
	 *
	 * @param string $permission
	 * @return
	 */
	protected final function getNav($permission = '',&$top_nav,&$left_nav,&$map_nav){

		$act = $_GET['act']?$_GET['act']:$_POST['act'];
		$op = $_GET['op']?$_GET['op']:$_POST['op'];
		if ($this->admin_info['sp'] != 1 && empty($this->permission)){
			$gadmin = Model('gadmin')->getby_gid($this->admin_info['gid']);
			$permission = decrypt($gadmin['limits'],MD5_KEY.md5($gadmin['gname']));
			$this->permission = $permission = explode('|',$permission);
		}
		Language::read('common');
		$lang = Language::getLangContent();
		$array = require(BASE_PATH.'/include/menu.php');
		$array = $this->parseMenu($array);
		//管理地图
		$map_nav = $array['left'];
		unset($map_nav[0]);

		$model_nav = "<li><a class=\"link actived\" id=\"nav__nav_\" href=\"javascript:;\" onclick=\"openItem('_args_');\"><span>_text_</span></a></li>\n";
		$top_nav = '';

		//顶部菜单
		foreach ($array['top'] as $k=>$v) {
			$v['nav'] = $v['args'];
			$top_nav .= str_ireplace(array('_args_','_text_','_nav_'),$v,$model_nav);
		}
		$top_nav = str_ireplace("\n<li><a class=\"link actived\"","\n<li><a class=\"link\"",$top_nav);

		//左侧菜单
		$model_nav = "
          <ul id=\"sort__nav_\">
            <li>
              <dl>
                <dd>
                  <ol>
                    list_body
                  </ol>
                </dd>
              </dl>
            </li>
          </ul>\n";
		$left_nav = '';
		foreach ($array['left'] as $k=>$v) {
			$left_nav .= str_ireplace(array('_nav_'),array($v['nav']),$model_nav);
			$model_list = "<li nc_type='_pkey_'><a href=\"JavaScript:void(0);\" name=\"item__opact_\" id=\"item__opact_\" onclick=\"openItem('_args_');\">_text_</a></li>";
			$tmp_list = '';

			$current_parent = '';//当前父级key

			foreach ($v['list'] as $key=>$value) {
				$model_list_parent = '';
				$args = explode(',',$value['args']);
				if ($admin_array['admin_is_super'] != 1){
					if (!@in_array($args[1],$permission)){
						//continue;
					}
				}

				if (!empty($value['parent'])){
					if (empty($current_parent) || $current_parent != $value['parent']){
						$model_list_parent = "<li nc_type='parentli' dataparam='{$value['parent']}'><dt>{$value['parenttext']}</dt><dd style='display:block;'></dd></li>";
					}
					$current_parent = $value['parent'];
				}

				$value['op'] = $args[0];
				$value['act'] = $args[1];
				//$tmp_list .= str_ireplace(array('_args_','_text_','_op_'),$value,$model_list);
				$tmp_list .= str_ireplace(array('_args_','_text_','_opact_','_pkey_'),array($value['args'],$value['text'],$value['op'].$value['act'],$value['parent']),$model_list_parent.$model_list);
			}

			$left_nav = str_replace('list_body',$tmp_list,$left_nav);

		}
	}

	/**
	 * 过滤掉无权查看的菜单
	 *
	 * @param array $menu
	 * @return array
	 */
	private final function parseMenu($menu = array()){
		if ($this->admin_info['sp'] == 1) return $menu;
		foreach ($menu['left'] as $k=>$v) {
			foreach ($v['list'] as $xk=>$xv) {
				$tmp = explode(',',$xv['args']);
				//以下几项不需要验证
				$except = array('index','dashboard','login','common');
				if (in_array($tmp[1],$except)) continue;
				if (!in_array($tmp[1],$this->permission) && !in_array($tmp[1].'.'.$tmp[0],$this->permission)){
					unset($menu['left'][$k]['list'][$xk]);
				}
			}
			if (empty($menu['left'][$k]['list'])) {
				unset($menu['top'][$k]);unset($menu['left'][$k]);
			}
		}
		return $menu;
	}

	/**
	 * 取得顶部小导航
	 *
	 * @param array $links
	 * @param 当前页 $actived
	 */
	protected final function sublink($links = array(), $actived = '', $file='index.php'){
		$linkstr = '';
		foreach ($links as $k=>$v) {
			parse_str($v['url'],$array);
			if (!$this->checkPermission($array)) continue;
			$href = ($array['op'] == $actived ? null : "href=\"{$file}?{$v['url']}\"");
			$class = ($array['op'] == $actived ? "class=\"current\"" : null);
			$lang = L($v['lang']);
			$linkstr .= sprintf('<li><a %s %s><span>%s</span></a></li>',$href,$class,$lang);
		}
		return "<ul class=\"tab-base\">{$linkstr}</ul>";
	}

	/**
	 * 记录系统日志
	 *
	 * @param $lang 日志语言包
	 * @param $state 1成功0失败null不出现成功失败提示
	 * @param $admin_name
	 * @param $admin_id
	 */
	protected final function log($lang = '', $state = 1, $admin_name = '', $admin_id = 0){
		if (!C('sys_log') || !is_string($lang)) return;
		if ($admin_name == ''){
			$admin = unserialize(decrypt(cookie('sys_key'),MD5_KEY));
			$admin_name = $admin['name'];
			$admin_id = $admin['id'];
		}
		$data = array();
		if (is_null($state)){
			$state = null;
		}else{
//			$state = $state ? L('nc_succ') : L('nc_fail');
			$state = $state ? '' : L('nc_fail');
		}
		$data['content'] 	= $lang.$state;
		$data['admin_name'] = $admin_name;
		$data['createtime'] = TIMESTAMP;
		$data['admin_id'] 	= $admin_id;
		$data['ip']			= getIp();
		$data['url']		= $_REQUEST['act'].'&'.$_REQUEST['op'];
		return Model('admin_log')->insert($data);
	}
    /**
     * @param int $parent_id
     * @param string $return
     * @param string $fields
     * @param string $group
     */
	public function regionOp($parent_id=0,$return='json',$fields = '*', $group = ''){
	    $parent = isset($_REQUEST['parent'])?(int)$_REQUEST['parent']:$parent_id;
	    $area = Model('area');
        $condition['area_parent_id'] = $parent;
		$condition['not_mainland']   = 0;
	    $regionList = $area->getAreaList($condition,$fields,$group);
	    if($return =='json') {
            $result['Success'] = ($regionList == true)?1:0;
            $result['Data']    = ($regionList == true) ? $regionList : [];
            echo json_encode( $result );exit;
        }else{
            return $regionList;
        }
    }

    /**
     * 部门查找员工
     * @param string $return
     * @return mixed
     */
    public function dept_userOp($dept='',$return='json')
    {
        $dept = isset($_GET['dept']) ? $_GET['dept'] : $dept;
        $admin = Model('hc_vouchers');
        $dept_id = $admin->table('hc_admin_dept')->where(['name'=>['like','%'.$dept.'%']])->get_field('id');
        $adminList = $admin->table('admin')->field('admin_id,admin_name,admin_truename')->where(['dept_id'=>$dept_id])->select();
        if($return =='json') {
            echo json_encode( $adminList );exit;
        }else{
            return $adminList;
        }
    }

    /**
     * 判断用户手机号码
     */
    public function is_phoneOp()
    {
        if(isAjax()) {
            $mobile = $_REQUEST['mobile'];
            if (is_null($mobile) || empty($mobile)) {
                $result = [0, '手机号不能为空'];
            } else {
                $isMobile = is_phone($mobile);
                if (!$isMobile) {
                    $result = [0, '手机号格式错误'];
                } else {
                    $model = Model('hc_vouchers');
                    $mobileCount = $model->table('users')->where(['phone' => $mobile])->count();
                    $result = ($mobileCount) ? [1, 'ok'] : [0, '手机号不存在！'];
                }
            }
            echo setJsonMsg($result[0], $result[1]);
        }
    }

    /**
     * 生成excel,csv
     *
     * @param array $data
     */
    public function createExcel($data = array(),$titleArr=array(),$title='代金券',$type='csv'){
        switch($type){
            case 'csv':
                $filename = $title.$_GET['curpage'].'-'.date('Y-m-d-H',time());
                $this->csv_export($data,$titleArr,$filename);
                break;
            case 'excel':
                Language::read('export');
                import('libraries.excel');
                $excel_obj = new Excel();
                $excel_data = array();
                //设置样式
                $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
                //header
                foreach($titleArr as $k => $subject){
                    $excel_data[0][] = array('styleid'=>'s_title','data'=>$subject);
                }
                //data
                foreach ((array)$data as $k=>$v){
                    $tmp = array();
                    foreach($v as $val){
                        $tmp[] = array('data'=>$val);
                    }
                    $excel_data[] = $tmp;
                }
                $excel_data = $excel_obj->charset($excel_data,CHARSET);
                $excel_obj->addArray($excel_data);
                $excel_obj->addWorksheet($excel_obj->charset($title,CHARSET));
                $excel_obj->generateXML($excel_obj->charset($title,CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
                break;
        }
    }

    /**
     * 导出excel(csv)
     * @data 导出数据
     * @headlist 第一行,列名
     * @fileName 输出Excel文件名
     */
    function csv_export($data = array(), $headlist = array(), $fileName) {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$fileName.'.csv"');
        header('Cache-Control: max-age=0');
        //打开PHP文件句柄,php://output 表示直接输出到浏览器
        $fp = fopen('php://output', 'a');
        //输出Excel列名信息
        foreach ($headlist as $key => $value) {
            //CSV的Excel支持GBK编码，一定要转换，否则乱码
            $headlist[$key] = iconv('utf-8', 'gbk', $value);
        }
        //将数据通过fputcsv写到文件句柄
        fputcsv($fp, $headlist);
        //计数器
        $num = 0;
        //每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
        $limit = 100000;
        //逐行取出数据，不浪费内存
        $count = count($data);
        for ($i = 0; $i < $count; $i++) {
            $num++;
            //刷新一下输出buffer，防止由于数据过多造成问题
            if ($limit == $num) {
                ob_flush();
                flush();
                $num = 0;
            }
            $row = $data[$i];
            foreach ($row as $key => $value) {
                $row[$key] = iconv('utf-8', 'gbk', $value);
            }
            fputcsv($fp, $row);
        }
    }

    /**
     * 单文件上传类
     * @param $fileName      上传表单名称
     * @param bool $thumb    是否生成缩略图
     * @param string $width  缩略图宽度
     * @param string $height 缩略图高度
     * @return string
     */
    public function setUploads($fileName,$thumb=true,$width='400',$height='300')
    {
        $root_dir = date('Y/m/d/',time());
        $upload = new UploadFile();
        if ($thumb) {
            $upload->set( 'thumb_width', $width );
            $upload->set( 'thumb_height', $height );
            $upload->set( 'thumb_ext', '_small' );
        }
        $upload->set('max_size',C('image_max_filesize')?C('image_max_filesize'):1024);
        $upload->set('ifremove',false);//是否删除原图片
        $upload->set('default_dir',$root_dir);
        //$upload->set('file_name',newName().'.'.$upload->get);
        $result = $upload->upfile($fileName);
        if($result){
            return $upload->get('default_dir').$upload->file_name;
        }else{
            die($upload->error);
        }
    }

    /**
     * @return mixed
     */
    protected function getServerQueryString(){
        parse_str($_SERVER['QUERY_STRING'],$urlArray);//QUERY_STRING
        unset($urlArray['act']);
        unset($urlArray['op']);
        return $urlArray;
    }
}
