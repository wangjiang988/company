<?php
/**
 * 代理-经销商对应关系模型
 */
defined('InHG') or exit('Access Invalid!');
class hg_daili_dealerModel extends Model {

    public function __construct(){
        parent::__construct('hg_daili_dealer');
    }

    /**
     * 经销商详细信息
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getDealerInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }

    /**
     * 经销商列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getDailiDealerList($condition = array(), $field = '*', $page = 0, $order = 'd_id desc') {
       return $this->where($condition)->page($page)->order($order)->select();
    }

    /**
     * 经销商数量
     * @param array $condition
     * @return int
     */
    public function getDailiDealerCount($condition) {
        return $this->where($condition)->count();
    }

    /**
     * 登录时创建会话SESSION
     *
     * @param array $member_info 经销商信息
     */
    public function createSession($member_info = array()) {
        if (empty($member_info) || !is_array($member_info)) return ;
		$_SESSION['is_login']	= 1;
		$_SESSION['member_id']	= $member_info['member_id'];
		$_SESSION['member_name']= $member_info['member_name'];
		$_SESSION['member_email']= $member_info['member_email'];
		$_SESSION['is_buy']		= $member_info['is_buy'];
		$_SESSION['avatar'] 	= $member_info['member_avatar'];
		$seller_info = Model('seller')->getSellerInfo(array('member_id'=>$_SESSION['member_id']));
		$_SESSION['store_id'] = $seller_info['store_id'];
		if (trim($member_info['member_qqopenid'])){
			$_SESSION['openid']		= $member_info['member_qqopenid'];
		}
		if (trim($member_info['member_sinaopenid'])){
			$_SESSION['slast_key']['uid'] = $member_info['member_sinaopenid'];
		}
		if(!empty($member_info['member_login_time'])) {//登录时间更新
    		$update_info	= array(
    		'member_login_num'=> ($member_info['member_login_num']+1),
    		'member_login_time'=> time(),
    		'member_old_login_time'=> $member_info['member_login_time'],
    		'member_login_ip'=> getIp(),
    		'member_old_login_ip'=> $member_info['member_login_ip']);
    		$this->updateDealer($update_info,$member_info['member_id']);
		}
    }

    /**
     * 注册
     */
    public function register($register_info) {
		// 注册验证
		$obj_validate = new Validate();
		$obj_validate->validateparam = array(
		array("input"=>$register_info["username"],		"require"=>"true",		"message"=>'用户名不能为空'),
		array("input"=>$register_info["password"],		"require"=>"true",		"message"=>'密码不能为空'),
		array("input"=>$register_info["password_confirm"],"require"=>"true",	"validator"=>"Compare","operator"=>"==","to"=>$register_info["password"],"message"=>'密码与确认密码不相同'),
		array("input"=>$register_info["email"],			"require"=>"true",		"validator"=>"email", "message"=>'电子邮件格式不正确'),
		);
		$error = $obj_validate->validate();
		if ($error != ''){
            return array('error' => $error);
		}

        // 验证用户名是否重复
		$check_member_name	= $this->infoDealer(array('member_name'=>trim($register_info['username'])));
		if(is_array($check_member_name) and count($check_member_name) > 0) {
            return array('error' => '用户名已存在');
		}

        // 验证邮箱是否重复
		$check_member_email	= $this->infoDealer(array('member_email'=>trim($register_info['email'])));
		if(is_array($check_member_email) and count($check_member_email)>0) {
            return array('error' => '邮箱已存在');
		}

		// 经销商添加
		$member_info	= array();
		$member_info['member_name']		= $register_info['username'];
		$member_info['member_passwd']	= $register_info['password'];
		$member_info['member_email']		= $register_info['email'];
		$insert_id	= $this->addDealer($member_info);
		if($insert_id) {
			//添加经销商积分
			if ($GLOBALS['setting_config']['points_isuse'] == 1){
				$points_model = Model('points');
				$points_model->savePointsLog('regist',array('pl_memberid'=>$insert_id,'pl_membername'=>$register_info['username']),false);
			}

            // 添加默认相册
            $insert['ac_name']      = '买家秀';
            $insert['member_id']    = $insert_id;
            $insert['ac_des']       = '买家秀默认相册';
            $insert['ac_sort']      = 1;
            $insert['is_default']   = 1;
            $insert['upload_time']  = TIMESTAMP;
            Model()->table('sns_albumclass')->insert($insert);

            $member_info['member_id'] = $insert_id;
            $member_info['is_buy'] = 1;

            return $member_info;
		} else {
            return array('error' => '注册失败');
		}

    }

	/**
	 * 注册商城经销商
	 *
	 * @param	array $param 经销商信息
	 * @return	array 数组格式的返回结果
	 */
	public function addDailiDealer($param) {
		if(empty($param)) {
			return false;
		}
		$result	= Db::insert('hg_daili_dealer',$param);
		if($result) {
			return Db::getLastId();
		} else {
			return false;
		}
	}
	/**
	 * 获取经销商信息
	 *
	 * @param	array $param 经销商条件
	 * @param	string $field 显示字段
	 * @return	array 数组格式的返回结果
	 */
	public function infoDealer($param, $field='*') {
		if (empty($param)) return false;

		//得到条件语句
		$condition_str	= $this->getCondition($param);
		$param	= array();
		$param['table']	= 'member';
		$param['where']	= $condition_str;
		$param['field']	= $field;
		$param['limit'] = 1;
		$member_list	= Db::select($param);
		$member_info	= $member_list[0];
		if (intval($member_info['store_id']) > 0){
	      $param	= array();
	      $param['table']	= 'store';
	      $param['field']	= 'store_id';
	      $param['value']	= $member_info['store_id'];
	      $field	= 'store_id,store_name,grade_id';
	      $store_info	= Db::getRow($param,$field);
	      if (!empty($store_info) && is_array($store_info)){
		      $member_info['store_name']	= $store_info['store_name'];
		      $member_info['grade_id']	= $store_info['grade_id'];
	      }
		}
		return $member_info;
	}

	/**
	 * 更新经销商信息
	 *
	 * @param	array $param 更改信息
	 * @param	int $member_id 经销商条件 id
	 * @return	array 数组格式的返回结果
	 */
	public function updateDealer($param,$member_id) {
		if(empty($param)) {
			return false;
		}
		$update		= false;
		//得到条件语句
		$condition_str	= " member_id='{$member_id}' ";
		$update		= Db::update('member',$param,$condition_str);
		return $update;
	}
	/**
	 * 经销商登录检查
	 *
	 */
	public function checkloginDealer() {
		if($_SESSION['is_login'] == 1) {
			@header("Location: index.php");
			exit();
		}
	}

    /**
	 * 检查经销商是否允许举报商品
	 *
	 */
	public function isDealerAllowInform($member_id) {

        $condition = array();
        $condition['member_id'] = $member_id;
        $member_info = $this->infoDealer($condition,'inform_allow');
        if(intval($member_info['inform_allow']) === 1) {
            return true;
        }
        else {
            return false;
        }
	}


	/**
	 * 将条件数组组合为SQL语句的条件部分
	 *
	 * @param	array $conditon_array
	 * @return	string
	 */
	private function getCondition($conditon_array){
		$condition_sql = '';
		if($conditon_array['member_id'] != '') {
			$condition_sql	.= " and member_id= '" .intval($conditon_array['member_id']). "'";
		}
		if($conditon_array['member_name'] != '') {
			$condition_sql	.= " and member_name='".$conditon_array['member_name']."'";
		}
		if($conditon_array['member_passwd'] != '') {
			$condition_sql	.= " and member_passwd='".$conditon_array['member_passwd']."'";
		}
		//是否允许举报
		if($conditon_array['inform_allow'] != '') {
			$condition_sql	.= " and inform_allow='{$conditon_array['inform_allow']}'";
		}
		//是否允许购买
		if($conditon_array['is_buy'] != '') {
			$condition_sql	.= " and is_buy='{$conditon_array['is_buy']}'";
		}
		//是否允许发言
		if($conditon_array['is_allowtalk'] != '') {
			$condition_sql	.= " and is_allowtalk='{$conditon_array['is_allowtalk']}'";
		}
		//是否允许登录
		if($conditon_array['member_state'] != '') {
			$condition_sql	.= " and member_state='{$conditon_array['member_state']}'";
		}
		if($conditon_array['friend_list'] != '') {
			$condition_sql	.= " and member_name IN (".$conditon_array['friend_list'].")";
		}
		if($conditon_array['member_email'] != '') {
			$condition_sql	.= " and member_email='".$conditon_array['member_email']."'";
		}
		if($conditon_array['no_member_id'] != '') {
			$condition_sql	.= " and member_id != '".$conditon_array['no_member_id']."'";
		}
		if($conditon_array['like_member_name'] != '') {
			$condition_sql	.= " and member_name like '%".$conditon_array['like_member_name']."%'";
		}
		if($conditon_array['like_member_email'] != '') {
			$condition_sql	.= " and member_email like '%".$conditon_array['like_member_email']."%'";
		}
		if($conditon_array['like_member_truename'] != '') {
			$condition_sql	.= " and member_truename like '%".$conditon_array['like_member_truename']."%'";
		}
		if($conditon_array['in_member_id'] != '') {
			$condition_sql	.= " and member_id IN (".$conditon_array['in_member_id'].")";
		}
		if($conditon_array['in_member_name'] != '') {
			$condition_sql	.= " and member_name IN (".$conditon_array['in_member_name'].")";
		}
		if($conditon_array['member_qqopenid'] != '') {
			$condition_sql	.= " and member_qqopenid = '{$conditon_array['member_qqopenid']}'";
		}
		if($conditon_array['member_sinaopenid'] != '') {
			$condition_sql	.= " and member_sinaopenid = '{$conditon_array['member_sinaopenid']}'";
		}

		return $condition_sql;
	}

// 	/**
// 	 * 经销商列表
// 	 *
// 	 * @param array $condition 检索条件
// 	 * @param obj $obj_page 分页对象
// 	 * @return array 数组类型的返回结果
// 	 */
// 	public function getDealerList($condition,$obj_page='',$field='*'){
// 		$condition_str = $this->getCondition($condition);
// 		$param = array();
// 		$param['table'] = 'member';
// 		$param['where'] = $condition_str;
// 		$param['order'] = $condition['order'] ? $condition['order'] : 'member_id desc';
// 		$param['field'] = $field;
// 		$param['limit'] = $condition['limit'];
// 		$member_list = Db::select($param,$obj_page);
// 		return $member_list;
// 	}

	/**
	 * 删除经销商
	 *
	 * @param int $id 记录ID
	 * @return array $rs_row 返回数组形式的查询结果
	 */
	public function del($id){
		if (intval($id) > 0){
			$where = " member_id = '". intval($id) ."'";
			$result = Db::delete('member',$where);
			return $result;
		}else {
			return false;
		}
	}
	/**
	 * 查询经销商总数
	 */
	public function countDailiDealer($condition){
		//得到条件语句
		$condition_str	= $this->getCondition($condition);
		$count = Db::getCount('hg_daili_dealer',$condition_str);
		return $count;
	}
}
