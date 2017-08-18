<?php

class hc_orderModel extends Model{
    const order_cancel = 0;//订单取消
    const order_place = 1;//下单
    const order_book =2;//诚意预约（客户）、反馈订单（售方)
    const order_pay_deposit = 3;//付担保金（客户）、准备订单（售方)
    const order_delivery = 4;//预约交车
    const order_pay_ = 5;//付款提车（客户）、交车执行（售方)
    const order_confirm = 6;//协商确认
    const order_end = 99;//订单结束


    public function __construct()
    {
        parent::__construct('hc_order');
    }

    /**
     * 订单详情
     * 王将
     */
    public function getOrderDetailInfo($order_id)
    {
        $order  =   $this->where('id='.$order_id)->find();
        if($order)
        {
            //TODO 查询所得信息 暂无字段
        }
        return $order;
    }

    function getOrderStatusList($type=1,$pri_status=0)
    {
        $model = Model('hc_order_progress_status');
        if($pri_status==0){ //订单主状态
            $progress_name = $type==1?'user_progress':'seller_progress';
            $result = $model->where("type in ($type,3) and is_show=1")
                ->field("pri_status,$progress_name")
                ->group('pri_status')
                ->order('pri_status asc')
                ->select();
        }

        if($pri_status>0) { //子状态
            $result = $model->where("type in ($type,3) and is_show=1 and pri_status=$pri_status")
                ->field('sub_status,remark')
                ->order('sub_status asc')
                ->select();
        }
        return $result;

    }

    /**
     * 订单列表
     * @param $condition
     * @param string $field\
     */
    public function getOrderList($condition, $page=10)
    {
        $condition_str = $this->_condition($condition);
        $result = $this->table('hc_order,users,member,hc_order_progress_status,hg_dealer,hg_baojia,hc_order_appoint_car,hc_order_xzj_edit,hc_order_conciliation')
            ->field('hc_order.*,
                users.phone,
                member.member_truename,
                hc_order_progress_status.user_progress,
                hc_order_progress_status.seller_progress,
                hc_order_progress_status.remark,
                hg_dealer.d_name,
                hg_baojia.gc_series,
                (CASE hc_order_appoint_car.is_feeback WHEN \'1\' THEN member_data WHEN \'2\' THEN seller_data ELSE default_data END) as jc_data,
                hc_order_xzj_edit.is_install,
                hc_order_conciliation.target'
            )
            ->on('hc_order.user_id=users.id,
                hc_order.seller_id=member.member_id,
                hc_order_progress_status.sub_status=hc_order.order_state,
                hc_order.dealer_id=hg_dealer.d_id,
                hg_baojia.bj_id=hc_order.bj_id,
                hc_order_appoint_car.order_id=hc_order.id,
                hc_order_xzj_edit.order_id=hc_order.id,
                hc_order_conciliation.order_id=hc_order.id'
            )
            ->where($condition_str)
            ->page($page)
            ->order('hc_order.id desc')
            ->group('hc_order.id')
            ->select();

        return $result;
    }

    /**
     * 构造检索条件
     *
     * @param int $id 记录ID
     * @return string 字符串类型的返回结果
     */
    private function _condition($condition){
        $condition_str = '1=1';

        //根据订单号查询
        if ($condition['order_id'] > 0) {
            $condition_str .= " and hc_order.order_sn like'%" . $condition['order_id'] . "%'";
        }

        //根据客户手机号查询
        if ($condition['user_phone']) {
            $condition_str .= " and users.phone='" . $condition['user_phone'] . "'";
        }

        //根据售方名称查询
        if ($condition['seller_name'] != '') {
            $condition_str .= " and member.member_truename='" . $condition['seller_name'] . "'";
        }

        //根据经销商名称查询
        if ($condition['dealer_name'] != '') {
            $condition_str .= " and hg_dealer.d_name like '%" . $condition['dealer_name'] . "%'";
        }

        //根据下单时间查询
        if ($condition['order_create_time_start']) {
            $condition_str .= " and hc_order.created_at > '" . $condition['order_create_time_start'] . "'";
        }
        if ($condition['order_create_time_end']) {
            $order_create_time_end = date("Y-m-d", strtotime($condition['order_create_time_end']) + 86400);
            $condition_str .= " and hc_order.created_at < '" . $order_create_time_end . "'";
        }

        //根据品牌查询
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
                $condition_str .= " and hc_order.brand_id in (" . implode(',', $brand_ids) . ")";
            } else {
                $condition_str .= " and hc_order.brand_id in ('')";
            }
        }

        //车系
        if ($condition['gc_series']) {
            $condition_str .= " and  hg_baojia.gc_series= '" . $condition['gc_series'] . "'";
        }

        //车型
        if ($condition['gc_name']) {
            $condition_str .= " and hc_order.brand_id= '" . $condition['gc_name'] . "'";
        }

        //根据订单结束时间查询
        if ($condition['order_finished_starttime']) {
            $condition_str .= " and hc_order.order_status=99 and hc_order.updated_at > '" . $condition['order_finished_starttime'] . "'";
        }
        if ($condition['order_finished_endtime']) {
            $order_create_time_end = date("Y-m-d", strtotime($condition['order_finished_endtime']) + 86400);
            $condition_str .= " and hc_order.order_status=99 and hc_order.updated_at < '" . $order_create_time_end . "'";
        }

        //根据客户订单状态查询
        if ($condition['user_order_pri_status']) {
            $condition_str .= " and hc_order.order_status= '" . $condition['user_order_pri_status'] . "'";
        }
        if ($condition['user_order_sub_status']) {
            $condition_str .= " and hc_order.order_state= '" . $condition['user_order_sub_status'] . "'";
        }

        //根据交车约定时间查询
        if ($condition['appoint_car_starttime']) {
            $condition_str .= " and (CASE hc_order_appoint_car.is_feeback WHEN '1' THEN member_data WHEN '2' THEN seller_data ELSE default_data END)>'" . $condition['appoint_car_starttime'] . "'";
        }
        if ($condition['appoint_car_endtime']) {
            $order_create_time_end = date("Y-m-d", strtotime($condition['appoint_car_endtime']) + 86400);
            $condition_str .= " and (CASE hc_order_appoint_car.is_feeback WHEN '1' THEN member_data WHEN '2' THEN seller_data ELSE default_data END)<'" . $order_create_time_end . "'";
        }

        //根据售方订单状态查询
        if ($condition['seller_order_pri_status']) {
            $condition_str .= " and hc_order.order_status= '" . $condition['seller_order_pri_status'] . "'";
        }
        if ($condition['seller_order_sub_status']) {
            $condition_str .= " and hc_order.order_state= '" . $condition['seller_order_sub_status'] . "'";
        }

        //根据是否选装修改协商查询
        if ($condition['xzjp_is_install']) {
            $condition_str .= " and hc_order_xzj_edit.is_install= '" . $condition['xzjp_is_install'] . "'";
        }

        //根据用户是否超时查询
        if ($condition['user_pay_timeout'] != '') {
            $where_str = $condition['user_pay_timeout'] == 1 ? 'in' : 'not in';
            $condition_str .= " and hc_order.order_state " . $where_str . " (292,392,492)'";
        }

        //根据工单处理情况进行查询
        if ($condition['order_in_negotiation'] != '') {
            $condition_str .= " and hc_order_conciliation.status !=3 and hc_order_conciliation.target ='" . $condition['order_in_negotiation'] . "''";
        }

        //根据工单处理部门进行查询
        if ($condition['conciliate_department'] != '') {
            $condition_str .= " and hc_order_conciliation.status in (1,2) and hc_order_conciliation.follow_depid ='" . $condition['conciliate_department'] . "''";
        }

        return $condition_str;
    }

    /**
     * @param     $options 数组
     * @param $options['distinct'] 是否查询唯一（true，false）
     * @param $options['field']    查询字段
     * @param $options['join']     join 联合查询子句
     * @param $options['on']       join 条件
     * @param $options['where']    查询条件
     * @param $options['group']    分组
     * @param $options['having']   分组判断
     * @param $options['order']    排序字段
     * @param $options['limit']    分页条件
     * @param $options['union']    union 查询子句
     * @param $options['index']    index 关键字
     * @param int $pageSize        分页参数
     * @return array
     */
    public function getPageList($options=null,$pageSize=10){
        import('page');
        $page = new Page();
        //设置翻页条数
        $page->setEachNum($pageSize);
        $option = $this->setOption($options);
        //获取记录总数并赋值给page
        $page->setTotalNum($this->getcount($option));
        $list = $this->page($pageSize)->select($option);

        $show = $page->show();
        return ['list'=>$list,'page'=>$show];
    }
    /** 获取列表
     * @param $options
     * @return null
     */
    public function getList($options=null){
        $option = $this->setOption($options);
        return $this->select($option);
    }
    /**
     * @param $options
     * @return null 获取一条记录
     */
    public function getFind($options){
        $option = $this->setOption($options);
        $option['limit'] = 1;
        $result = $this->select($option);
        if(empty($result)) {
            return array();
        }
        return $result[0];
    }

    /**
     * @param $options
     * @return mixed 获取记录数
     */
    public function getCount($options){
        $option = $this->setOption($options);
        $option['field'] = 'count(*) as _count';
        $result = $this->getFind($option);
        return $result['_count'];
    }
    /**
     * 保存记录
     * @param $data
     * @param string $type
     * @param null $where
     * @return bool
     */
    public function saveData($data,$type='insert',$where=null){
        return ($type == 'insert') ? $this->addData($data) : $this->updateData($data,$where);
    }
    /**
     * 删除数据
     * @param $where
     * @return bool
     */
    public function delData($where){
        return $this->delete($where);
    }
    /**
     * 添加记录
     * @param $data
     * @return mixed
     */
    private function addData($data){
        return $this->insert($data);
    }
    /**
     * 更新记录
     * @param $data
     * @param $where
     * @return bool
     */
    private function updateData($data,$where){
        return $this->update($data,['where'=>$where]);
    }
    /**
     * 设置查询条件
     * @param $options
     * @return mixed
     */
    private function setOption($options=null){
        $option['table']    = isset($options['table']) ? $options['table'] : $this->table_name;
        $option['distinct'] = isset($options['distinct']) ? $options['distinct'] : false;
        $option['field']    = isset($options['field']) ? $options['field'] : '*';
        $option['join']     = isset($options['join']) ? $options['join'] : '';
        $option['on']       = isset($options['on']) ? $options['on'] : array();
        $option['where']    = isset($options['where']) ? $options['where'] : '';
        $option['group']    = isset($options['group']) ? $options['group'] : '';
        $option['having']   = isset($options['having']) ? $options['having'] : '';
        $option['order']    = isset($options['order']) ? $options['order'] : '';
        $option['union']    = isset($options['union']) ? $options['union'] : '';
        $option['index']    = isset($options['index']) ? $options['index'] : '';
        return $option;
    }
}
