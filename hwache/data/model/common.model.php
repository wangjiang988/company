<?php
/** 公共模型类
 * Created by PhpStorm.
 * User: QinLin
 * Date: 2016/11/8
 * Time: 10:05
 */
defined('InHG') or exit('Access Invalid!');
class commonModel extends Model{
    //protected $table_prefix = 'car_';
    public function __construct($table=null)
    {
        parent::__construct( $table );
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
        unset($options['group']);
        unset($options['having']);
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