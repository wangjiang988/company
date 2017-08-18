<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/24
 * Time: 11:33
 */
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/6
 * Time: 16:59
 */
defined('InHG') or exit('Access Invalid!');
class approveModel extends Model
{
    const USER_ID_CART_LOG = 'user_idcart_log';
    const USER_BANK_LOG   = 'user_bank_log';
    const USER_TABLE      = 'user_view';
    const USER_EXTENSION  = 'user_extension';
    const END_HOUR        = ' 23:59:59';
    protected $tableName = null;

    public function __construct()
    {
        parent::__construct('user_idcart_log');
    }

    public function setTable($table = null)
    {
        $tabeList = implode(',', [self::USER_ID_CART_LOG, self::USER_TABLE]);
        $this->tableName = is_null($table) ? $tabeList : $table;
    }

    /**
     * 列表翻页
     * @param array $condition
     * @param string $fields
     * @param null $join
     * @param null $on
     * @param null $order
     * @param null $group
     * @param null $having
     * @param int $pageSize
     * @param null $key
     * @return array
     */
    public function getPageList($condition = array(), $fields = '*', $join = null, $on = null, $order = null, $group = null, $having = null, $pageSize = 10, $key = null)
    {
        if (is_null($this->tableName)) {
            $this->setTable();
        }
        $list = $this->table($this->tableName)->field($fields)->where($condition)
            ->join($join)->on($on)
            ->order($order)
            ->page($pageSize)
            ->group($group)->having($having)
            ->key($key)->select();
        return ['list' => $list, 'page' => $this->showpage()];
    }

    /** 获取所有列表
     * @param $table
     * @param array $condition
     * @param string $fields
     * @param null $join
     * @param null $on
     * @param null $order
     * @param null $group
     * @param null $having
     * @return mixed
     */
    public function getAll($table, $condition = array(), $fields = '*', $join = null, $on = null, $order = null, $group = null, $having = null)
    {
        return $this->table($table)->field($fields)->where($condition)
            ->join($join)->on($on)
            ->order($order)
            ->group($group)
            ->having($having)
            ->select();
    }

    /**
     * 获取单条记录
     * @param $table
     * @param array $condition
     * @param string $field
     * @param null $join
     * @param null $on
     * @param null $order
     * @param null $group
     * @param null $having
     * @return mixed
     */
    public function getFind($table, $condition = array(), $field = '*', $join = null, $on = null, $order = null, $group = null, $having = null)
    {
        return $this->table($table)->field($field)->where($condition)->join($join)->on($on)
            ->order($order)->group($group)->having($having)->find();
    }

    /**
     * 取得总数量
     * @param unknown $condition
     */
    public function getCount($table, $condition, $join = null, $on = null)
    {
        return $this->table($table)->where($condition)->join($join)->on($on)->count();
    }

    /**
     * 保存信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function saveData($table, $data, $type = 'insert', $where = null)
    {
        if ($type == 'insert')
            return $this->table($table)->insert($data);
        else
            return $this->table($table)->where($where)->update($data);
    }

    public function getIdCartPageList($param)
    {
        $where = [];
        $search = $param;
        $whereLike= $this->setLikeWhere([
            self::USER_TABLE.'.real_name' => trim($param['user_name']),
            self::USER_TABLE.'.phone'     => trim($param['phone']),
            self::USER_TABLE.'.id_cart'   => $param['id_cart']
        ]);
        if(count($whereLike) > 0) $where = array_merge($where,$whereLike);
        #------------
        $log = self::USER_ID_CART_LOG;
        $createdAt = $param['created_at'];
        $review_time =$param['review_time'];
        if($param['id']) $operation[self::USER_ID_CART_LOG.'.id'] = ['eq',$param['id']];//状态
        if($param['user_id']) $operation[self::USER_ID_CART_LOG.'.user_id'] = ['eq',$param['user_id']];//状态
        if($param['status'] > 0) $operation[self::USER_ID_CART_LOG.'.status'] = ['eq',$param['status']-1];//状态
        if($param['created_at']) $operation[self::USER_ID_CART_LOG.'.created_at'] =['exp',"date_format({$log}.created_at,'%Y-%m-%d')='{$createdAt}'"];
        if($param['review_time']) $operation[self::USER_ID_CART_LOG.'.review_time'] =['exp',"date_format({$log}.review_time,'%Y-%m-%d')='{$review_time}'"];
        if($operation){
            $operationWhere = $this->setOperationWhere($operation);
            $where = array_merge($where,$operationWhere);
        }
        $on     = sprintf("%s.id=%s.user_id",self::USER_TABLE,self::USER_ID_CART_LOG);
        $fields = "log.*,u.real_name,u.phone,u.id_cart";
        $field = str_replace(['log.','u.'],[self::USER_ID_CART_LOG.'.',self::USER_TABLE.'.'],$fields);
        $result = $this->getPageList($where,$field,'left',$on,self::USER_ID_CART_LOG.'.id desc');

        $search['status']         = $param['status'] ? $param['status'] : '9';
        return ['list'=>$result['list'],'page'=>$result['page'],'search'=>$search];
    }

    /**
     * 实名认证工单详情
     * @param $id
     * @return mixed
     */
    public function getIdcartDetail($id)
    {
        $table = implode(',', [self::USER_ID_CART_LOG, self::USER_TABLE]);
        $where[self::USER_ID_CART_LOG.'.id'] = ['eq',$id];
        $on     = sprintf("%s.id=%s.user_id",self::USER_TABLE,self::USER_ID_CART_LOG);
        $fields = "log.*,u.last_name,u.first_name,u.phone,u.id_cart,u.is_id_verify";
        $field = str_replace(['log.','u.'],[self::USER_ID_CART_LOG.'.',self::USER_TABLE.'.'],$fields);
        return $this->getFind($table,$where,$field,'left',$on);
    }

    public function saveIdCart($user_id,$logId,$data,$logData)
    {
        //更新认证状态
        $res = $this->saveData(self::USER_EXTENSION,$data,'update','user_id='.$user_id);
        //更新认证日志
        if($res){
            $LogRes = $this->saveData(self::USER_ID_CART_LOG,$logData,'update','id='.$logId);
        }

        return $LogRes;
    }

    /**
     * 银行卡认证列表
     * @param $param
     * @return array
     */
    public function getBankPageList($param)
    {
        // $this->setTable(implode(',', [self::USER_BANK_LOG, self::USER_TABLE]));
        $filter =[
            'id|like|user_bank_log',
            'user_id|like|user_bank_log',
            'real_name|like|user_view',
            'phone|like|user_views',
            'bank_code|like|user_bank_log',
            'created_at|like|user_bank_log',
            'review_time|like|user_bank_log',
            'status|eq|user_bank_log',
        ];
        $where = trans_form_to_where($filter);
        // json($where);

        $field  ="user_bank_log.*,
                    user_view.phone,
                    user_view.real_name";

        $list =  $this->table('user_bank_log,user_view')->field($field)
                            ->join('left')->on('user_bank_log.user_id = user_view.id')
                            ->page(10)->order('user_bank_log.id desc')
                            ->where($where)->select();
        $page =  $this->showPage();
        return [
            'list' => $list,
            'page' => $page,
        ];

    }
}