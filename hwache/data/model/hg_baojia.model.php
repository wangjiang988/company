<?php
/**
 * 报价模型管理
 */
defined('InHG') or exit('Access Invalid!');
class hg_baojiaModel extends Model {
    public function __construct(){
        parent::__construct('hg_baojia');
    }

	/**
	 * 查询保险公司列表
     *
	 * @param array $condition 查询条件
	 * @param int $page 分页数
	 * @param string $order 排序
	 * @param string $field 字段
	 * @param string $limit 取多少条
     * @return array
	 */
    public function getBaoxianList($condition, $page = null, $order = '', $field = '*', $limit = '') {
        $result = $this->field($field)->where($condition)->order($order)->limit($limit)->page($page)->select();
        return $result;
    }

	/**
	 * 查询有效店铺列表
     *
	 * @param array $condition 查询条件
	 * @param int $page 分页数
	 * @param string $order 排序
	 * @param string $field 字段
     * @return array
	 */
    public function getStoreOnlineList($condition, $page = null, $order = '', $field = '*') {
        $condition['store_state'] = 1;
        return $this->getStoreList($condition, $page, $order, $field);
    }

    /**
     * 店铺数量
     * @param array $condition
     * @return int
     */
    public function getStoreCount($condition) {
        return $this->where($condition)->count();
    }

    /**
	 * 按店铺编号查询店铺的开店信息
     *
	 * @param array $storeid_array 店铺编号
     * @return array
	 */
    public function getStoreMemberIDList($storeid_array) {
        $store_list = $this->table('store')->where(array('store_id'=> array('in', $storeid_array)))->field('store_id,member_id,store_domain')->key('store_id')->select();
        return $store_list;
    }

    /**
	 * 查询店铺信息
     *
	 * @param array $condition 查询条件
     * @return array
	 */
    public function getStoreInfo($condition) {
        $store_info = $this->where($condition)->find();
        if(!empty($store_info)) {
            if(!empty($store_info['store_presales'])) $store_info['store_presales'] = unserialize($store_info['store_presales']);
            if(!empty($store_info['store_aftersales'])) $store_info['store_aftersales'] = unserialize($store_info['store_aftersales']);

            //商品数
            $model_goods = Model('goods');
            $store_info['goods_count'] = $model_goods->getGoodsCommonOnlineCount(array('store_id' => $store_info['store_id']));

            //店铺评价
            $model_evaluate_store = Model('evaluate_store');
            $store_evaluate_info = $model_evaluate_store->getEvaluateStoreInfoByStoreID($store_info['store_id'], $store_info['sc_id']);

            $store_info = array_merge($store_info, $store_evaluate_info);
        }
        return $store_info;
    }

    /**
	 * 通过店铺编号查询店铺信息
     *
	 * @param int $store_id 店铺编号
     * @return array
	 */
    public function getStoreInfoByID($store_id) {
        $store_info = rcache($store_id, 'store_info');
        if(empty($store_info)) {
            $store_info = $this->getStoreInfo(array('store_id' => $store_id));
            wcache($store_id, $store_info, 'store_info');
        }
        return $store_info;
    }

    public function getStoreOnlineInfoByID($store_id) {
        $store_info = $this->getStoreInfoByID($store_id);
        if(empty($store_info) || $store_info['store_state'] == '0') {
            return null;
        } else {
            return $store_info;
        }
    }

    public function getStoreIDString($condition) {
        $condition['store_state'] = 1;
        $store_list = $this->getStoreList($condition);
        $store_id_string = '';
        foreach ($store_list as $value) {
            $store_id_string .= $value['store_id'].',';
        }
        return $store_id_string;
    }

	/*
	 * 添加店铺
     *
	 * @param array $param 店铺信息
	 * @return bool
	 */
    public function addStore($param){
        return $this->insert($param);
    }

	/*
	 * 编辑店铺
     *
	 * @param array $update 更新信息
	 * @param array $condition 条件
	 * @return bool
	 */
    public function editStore($update, $condition){
        //清空缓存
        $store_list = $this->getStoreList($condition);
        foreach ($store_list as $value) {
            wcache($value['store_id'], array(), 'store_info');
        }

        return $this->where($condition)->update($update);
    }

	/*
	 * 删除店铺
     *
	 * @param array $condition 条件
	 * @return bool
	 */
    public function delStore($condition){
        $store_info = $this->getStoreInfo($condition);
        //删除店铺相关图片
        @unlink(BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.$store_info['store_label']);
        @unlink(BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.$store_info['store_banner']);
        if($store_info['store_slide'] != ''){
            foreach(explode(',', $store_info['store_slide']) as $val){
                @unlink(BASE_UPLOAD_PATH.DS.ATTACH_SLIDE.DS.$val);
            }
        }

        //清空缓存
        wcache($store_info['store_id'], array(), 'store_info');

        return $this->where($condition)->delete();
    }

    /**
     * 获取商品销售排行
     *
     * @param int $store_id 店铺编号
     * @param int $limit 数量
     * @return array	商品信息
     */
    public function getHotSalesList($store_id, $limit = 5) {
        $prefix = 'store_hot_sales_list_' . $limit;
        $hot_sales_list = rcache($store_id, $prefix);
        if(empty($hot_sales_list)) {
            $model_goods = Model('goods');
            $hot_sales_list = $model_goods->getGoodsOnlineList(array('store_id' => $store_id), '*', 0, 'goods_salenum desc', $limit);
            wcache($store_id, $hot_sales_list, $prefix);
        }
        return $hot_sales_list;
    }

    /**
     * 获取商品收藏排行
     *
     * @param int $store_id 店铺编号
     * @param int $limit 数量
     * @return array	商品信息
     */
    public function getHotCollectList($store_id, $limit = 5) {
        $prefix = 'store_collect_sales_list_' . $limit;
        $hot_collect_list = rcache($store_id, $prefix);
        if(empty($hot_collect_list)) {
            $model_goods = Model('goods');
            $hot_collect_list = $model_goods->getGoodsOnlineList(array('store_id' => $store_id), '*', 0, 'goods_collect desc', $limit);
            wcache($store_id, $hot_collect_list, $prefix);
        }
        return $hot_collect_list;
    }
    public function getXzjList($condition, $page = null, $field = '*,hg_xzj_daili.id as d_id', $order = '', $limit = '')
    {
        return $this->table('hg_xzj_daili_main,hg_xzj_daili,hg_xzj_list')
            ->field($field)
            ->join('inner,left')
            ->on('hg_xzj_daili_main.id=hg_xzj_daili.main_id,hg_xzj_daili.xzj_list_id=hg_xzj_list.id')
            ->where($condition)
            ->order($order)
            ->limit($limit)
            ->page($page)
            ->select();
    }
    public function getMyXzj($condition, $field = '*',$page = null, $order = '', $limit = '')
    {
        return $this->table('hg_baojia_xzj,hg_xzj_list,hg_xzj_daili,hg_xzj_daili_main')
            ->field($field)
            ->join('inner,left,left')
            ->on('hg_baojia_xzj.xzj_id=hg_xzj_list.id,hg_xzj_list.id=hg_xzj_daili.xzj_list_id,hg_xzj_daili.main_id=hg_xzj_daili_main.id')
            ->where($condition)
            ->select();
    }
    // 如果报价超时，设置为终止状态
    public function chaoshi($m){
        $my_baojia=$this->where(array('m_id'=>$m))->select();
        //var_dump($my_baojia);exit;
        foreach ($my_baojia as $key => $value) {
            if($value['bj_end_time']<(time()+3600*24)){
                $this->where(array('bj_id'=>$value['bj_id']))->update(array('bj_status'=>0));
            }
        }

    }

    /**
     * 列表
     *
     * @param array $condition 检索条件
     * @param obj $page 分页
     * @return array 数组结构的返回结果
     */
    public function getBaojiaList($condition, $page)
    {
        $condition_str = $this->_condition($condition);
        $result = $this->table('hg_baojia,member,hg_dealer')
            ->field('hg_baojia.*,member.member_name,hg_dealer.d_areainfo')
            ->on('hg_baojia.m_id=member.member_id,hg_dealer.d_id=hg_baojia.dealer_id')
            ->where($condition_str)
            ->order('hg_baojia.bj_id desc')
            ->page($page)
            ->select();

        if ($result) {
            //映射报价状态
            foreach ($result as &$list) {
                $list['bj_status'] = $this->getBaojiaStatus($list['bj_status'], $list['bj_step'], $list['bj_start_time'], $list['bj_end_time']);
            }
        }
        return $result;
    }

    /**
     * 构造检索条件
     *
     * @param int $id 记录ID
     * @return string 字符串类型的返回结果
     */
    private function _condition($condition)
    {
        $condition_str = 'bj_status <> 3';

        //会员信息
        if ($condition['user_name'] || $condition['user_realname'] || $condition['user_phone']) {
            $sql = "select member_id from car_member where member_state=1";
            if ($condition['user_name']) {
                $sql .= " and member_name like '%$condition[user_name]%'";
            }
            if ($condition['user_realname']) {
                $sql .= " and member_truename like '%$condition[user_realname]%'";
            }
            if ($condition['user_phone']) {
                $sql .= " and member_mobile like '%$condition[user_phone]%'";
            }

            $members = Model()->query($sql);
            if ($members) {
                foreach ($members as $member) {
                    $mids[] = $member['member_id'];
                }
                $condition_str .= " and m_id in (" . implode(',', $mids) . ")";
            } else {
                $condition_str .= " and m_id in ('')";
            }
        }

        //经销商
        if ($condition['dealer_name']) {
            $condition_str .= " and dealer_name like '%" . $condition['dealer_name'] . "%'";
        }

        //归属地区
        if ($condition['dealer_area']) {
            $sql = "select d_id from car_hg_dealer where d_areainfo= '" . $condition['dealer_area'] . "'";
            $dealers = Model()->query($sql);
            if ($dealers) {
                foreach ($dealers as $dealer) {
                    $dealer_ids[] = $dealer['d_id'];
                }
                $condition_str .= " and dealer_id in (" . implode(',', $dealer_ids) . ")";
            } else {
                $condition_str .= " and dealer_id in ('')";
            }
        }

        //品牌
        if ($condition['brand_id']) {
            $sql = "select c.gc_id from car_goods_class a 
                    left join car_goods_class b on b.gc_parent_id=a.gc_id
                    left join car_goods_class c on c.gc_parent_id=b.gc_id
                    where a.gc_id='" . $condition['brand_id'] . "' and c.gc_id<>''";
            $brands = Model()->query($sql);
            if ($brands) {
                foreach ($brands as $brand) {
                    $brand_ids[] = $brand['gc_id'];
                }
                $condition_str .= " and brand_id in (" . implode(',', $brand_ids) . ")";
            } else {
                $condition_str .= " and brand_id in ('')";
            }
        }

        //车系
        if ($condition['gc_series']) {
            $condition_str .= " and  gc_series= '" . $condition['gc_series'] . "'";
        }

        //车型
        if ($condition['gc_name']) {
            $condition_str .= " and brand_id= '" . $condition['gc_name'] . "'";
        }

        //报价编号
        if ($condition['bj_serial']) {
            $condition_str .= " and  bj_serial like '%" . $condition['bj_serial'] . "%'";
        }

        //报价状态
        if ($condition['bj_status'] != '') {
//            $condition_str .= " and bj_status = '" . $condition['bj_status'] . "'";
            $now = time();
            if ($condition['bj_status'] == 1) {//正在报价
                $condition_str .= " and bj_step=99 and bj_status=1 and bj_start_time<$now and bj_end_time>$now";
            }
            if ($condition['bj_status'] == 2) {//暂时下架
                $condition_str .= " and bj_step=99 and bj_status=2";
            }
            if ($condition['bj_status'] == 4) {//失效报价（过期失效，主动终止）

                $condition_str .= " AND (bj_step=99 and bj_status=1 and bj_end_time<$now) or bj_status=0";
            }
            if ($condition['bj_status'] == 5) {//新建未完
                $condition_str .= " and bj_step<99 and bj_status=1";
            }
            if ($condition['bj_status'] == 6) {//等待生效
                $condition_str .= " and bj_step=99 and bj_status=1 and bj_start_time>$now";
            }
        }

        //发布状态
        if ($condition['bj_is_public'] != '') {
            if ($condition['bj_status'] == 4 || $condition['bj_status'] == 5) {
                $condition['bj_is_public'] = 0;
            }
            $condition_str .= " and bj_is_public = '" . $condition['bj_is_public'] . "'";
        }

        //是否现车
        if ($condition['bj_is_xianche'] != '') {
            $condition_str .= " and bj_is_xianche = '" . $condition['bj_is_xianche'] . "'";
        }

        //是否通过人工审核
        if ($condition['bj_is_pass'] != '') {
            $condition_str .= " and bj_is_pass = '" . $condition['bj_is_pass'] . "'";
        }

        if ($condition['bj_create_time_start']) {
            $condition_str .= " and bj_create_time > '" . $condition['bj_create_time_start'] . "'";
        }

        if ($condition['bj_create_time_end']) {
            $bj_create_time_end = date("Y-m-d", strtotime($condition['bj_create_time_end']) + 86400);
            $condition_str .= " and bj_create_time < '" . $bj_create_time_end . "'";
        }

        if ($condition['bj_start_time']) {
            $condition_str .= " and bj_start_time > '" . strtotime($condition['bj_start_time']) . "'";
        }

        if ($condition['bj_end_time']) {
            $bj_end_time = strtotime($condition['bj_end_time']) + 86400;
            $condition_str .= " and bj_start_time < '" . $bj_end_time . "'";
        }

        return $condition_str;
    }

    /**
     * 报价状态
     */
    function getBaojiaStatus($bj_status,$bj_step,$bj_start_time,$bj_end_time)
    {
        $status = '未定义状态';
        $now = time();
        if ($bj_step == 99 && $bj_status == 1 && $bj_start_time < $now && $bj_end_time > $now) {
            $status = '正在报价';
        }

        if ($bj_step == 99 && $bj_status == 2) {
            $status = '暂时下架';
        }

        if (($bj_step == 99 && $bj_status == 1 && $bj_end_time < $now) || $bj_status == 0) {
            $status = '失效报价';
        }

        if ($bj_step < 99 && $bj_status == 1) {
            $status = '新建未完';
        }

        if ($bj_step == 99 && $bj_status == 1 && $bj_start_time > $now) {
            $status = '等待生效';
        }

        return $status;
    }

    /**
     * 查询报价单中所有品牌
     */
    public function getBrandFromBaojia()
    {
        $sql = "select d.gc_id,d.gc_name from car_hg_baojia a 
                    left join car_goods_class b on a.brand_id=b.gc_id 
                    left join car_goods_class c on c.gc_id=b.gc_parent_id 
                    left join car_goods_class d on c.gc_parent_id=d.gc_id
                    where d.gc_id>0 and a.bj_status <>0 and a.bj_status<>3
                    group by gc_id";
        return Model()->query($sql);
    }

    /**
     * 查询报价单中某品牌的车系
     */
    public function getCarseriesFromBaojia($brand_id)
    {
        $sql = "select c.gc_name,c.gc_id from car_hg_baojia a 
                join car_goods_class b on a.brand_id=b.gc_id 
                join car_goods_class c on b.gc_parent_id=c.gc_id
                where c.gc_parent_id=$brand_id and a.bj_status <>0 and a.bj_status<>3
                group by c.gc_name";

        return Model()->query($sql);
    }

    /**
     * 查询报价单中某车系的车型列表
     */
    public function getCarmodelFromBaojia($brand_id,$gc_series)
    {
        $sql = "select b.gc_name,b.gc_id from car_hg_baojia a 
                join car_goods_class b on a.brand_id=b.gc_id 
                join car_goods_class c on b.gc_parent_id=c.gc_id
                where c.gc_parent_id=$brand_id and a.gc_series='$gc_series' and a.bj_status <>0 and a.bj_status<>3
                group by b.gc_id";

        return Model()->query($sql);
    }

    /**
     * 查询报价单中所有经销地区
     */
    public function getAreaListFromBaojia()
    {
        //经销商归属区域
        $sql ="select d_areainfo as area_name from car_hg_baojia a
                left join car_hg_dealer b
                on a.dealer_id=b.d_id 
                group by d_areainfo";

        return Model()->query($sql);
    }

    /**
     * 查询报价单的地区信息
     */
    public function getAreasByBaojiaId($bj_id)
    {
        $result = '';

        $sql = "SELECT d.area_name as province,c.area_name as city FROM car_hg_baojia a
                LEFT JOIN car_hg_baojia_area b ON a.bj_id = b.bj_id
                LEFT JOIN car_area c ON b.city = c.area_id
                LEFT JOIN car_area d ON b.province = d.area_id
                WHERE a.bj_id = $bj_id";
        $ret = Model()->query($sql);

        if ($ret) {
            //todo 所有城市都选择时，只显示省
            $temp = array();
            foreach ($ret as $v) {
                $temp[$v['province']][] = $v['city'];
            }

            foreach ($temp as $p => $t) {
                $result .= $p . '(' . implode(',', $t) . ');';
            }
        }

        return $result;
    }

    /**
     * 报价的车源范围
     */
    function getScopeByBaojiaId($bj_id)
    {
        $sql = "select b.* from car_hg_baojia a left join car_hc_scope b
                on a.daili_dealer_id=b.dealer_id
                WHERE a.bj_id = $bj_id";
        $ret = Model()->query($sql);
        $result = '';
        if($ret){
            $areas = array(
                $ret[0]['province1_name'] . $ret[0]['area1_name'],
                $ret[0]['province2_name'] . $ret[0]['area2_name'],
                $ret[0]['province3_name'] . $ret[0]['area3_name']
            );
            $result = implode('，', array_filter($areas));
        }
        
        return $result;
    }

    /**
     * 报价的选装件
     */
    function getXzjByBaojiaId($bj_id)
    {
        $sql = "select a.*,b.xzj_title,b.xzj_model,b.xzj_guide_price,c.xzj_cs_serial
                from car_hg_baojia_xzj a 
                left join car_hg_xzj_list b on b.id=a.xzj_id
                left join car_hg_xzj_daili c on c.id=a.m_id
                where a.bj_id=$bj_id and a.is_install=1";
        return Model()->query($sql);
    }

    /**
     * 报价的免费礼品和服务
     */
    function getZengpinByBaojiaId($bj_id)
    {
        $sql = "select * from car_hg_baojia_zengpin a 
                left join car_hg_zengpin b 
                on a.zengpin_id=b.id 
                where a.bj_id=$bj_id";
        return Model()->query($sql);
    }

    /**
     * 报价的选装精品
     */
    function getXzjpByBaojiaId($bj_id)
    {
        $sql = "select a.*,b.xzj_title,b.xzj_model,b.xzj_guide_price,c.xzj_cs_serial,c.xzj_has_num
                from car_hg_baojia_xzj a 
                left join car_hg_xzj_list b on b.id=a.xzj_id
                left join car_hg_xzj_daili c on c.id=a.m_id
                where a.bj_id=$bj_id and b.xzj_front=0";
        return Model()->query($sql);
    }
}
