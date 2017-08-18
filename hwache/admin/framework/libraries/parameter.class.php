<?php
/**
 * 报价审核参数功能
 *
 * @package libraries
 * @author 技安 <php360@qq.com>
 * @link 技安后院 http://www.moqifei.com
 * @company 苏州华车网络科技有限公司 http://www.hwache.com
 */
class parameter
{
    /**
     * 报价审核参数表名称
     *
     * @var string
     */
    private $table = 'hc_price_auditing_parameter';

    /**
     * 审核参数数组
     *
     * @var array
     */
    private $param = [];

    /**
     * 字段关联
     *
     * @var array
     */
    private $fieldLink = [
        'agent_numberplate_price' => '代办上牌服务费',
        'agent_temp_numberplate_price' => '代办临牌（每次）服务费',
        'client_license_compensate' => '客户本人上牌违约赔偿',
        'client_hand_price' => '客户买车定金',
        'zhidaojia' => '厂商指导价',
        'car_price' => '车辆开票价格'
    ];

    /**
     * 运算符关联
     *
     * @var array
     */
    private $operator = [
        1 => '<',
        2 => '<=',
        3 => '=',
        4 => '>=',
        5 => '>',
    ];

    /**
     * 参数名称
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->param['title'] = $title;

        return $this;
    }

    /**
     * 关联字段(判断点)
     *
     * @param string $field
     * @return $this
     */
    public function setField($field)
    {
        $this->param['field'] = $field;

        return $this;
    }

    /**
     * 运算符
     *
     * @param string $operator
     * @return $this
     */
    public function setOperator($operator)
    {
        $this->param['operator'] = $operator;

        return $this;
    }

    /**
     * 常量值
     *
     * @param float $const
     * @return $this
     */
    public function setConst($const)
    {
        $this->param['const'] = $const;

        return $this;
    }

    /**
     * 关联字段（用于计算的）
     *
     * @param string $relationField
     * @return $this
     */
    public function setRelationField($relationField)
    {
        $this->param['relation_field'] = $relationField;

        return $this;
    }

    /**
     * 被关联字段倍数(小数点保留1位)
     *
     * @param float $multiple
     * @return $this
     */
    public function setMultiple($multiple)
    {
        $this->param['multiple'] = $multiple;

        return $this;
    }

    /**
     * 是否开启(0:关闭，1:开启)
     *
     * @param bool $open
     * @return $this
     */
    public function setOpen($open = false)
    {
        if ($open) {
            $this->param['open'] = 1;
        } else {
            $this->param['open'] = 0;
        }

        return $this;
    }

    /**
     * 启用时间
     *
     * @param string $openTime
     * @return $this
     */
    public function setOpenTime($openTime)
    {
        $this->param['open_time'] = $openTime;

        return $this;
    }

    /**
     * 批量参数设置，数组
     *
     * @param array $param
     * @return $this
     */
    public function setData(array $param)
    {
        $this->param = $param;

        return $this;
    }

    /**
     * 添加数据
     *
     * @return mixed
     */
    public function toAdd()
    {
        return Model($this->table)->insert($this->param);
    }

    /**
     * 更新数据
     *
     * @param int $id
     * @return mixed
     */
    public function toUpdate($id)
    {
        return Model($this->table)->where(['id' => $id])->update($this->param);
    }

    /**
     * 返回字段关联关系
     *
     * @param bool $json
     * @return string|array
     */
    public function getFieldLink($json = false)
    {
        if ($json) {
            return json_encode($this->fieldLink);
        }

        return $this->fieldLink;
    }

    /**
     * 返回字段关联关系
     *
     * @param bool $json
     * @return array|string
     */
    public function getOperator($json = false)
    {
        if ($json) {
            return json_encode($this->operator);
        }

        return $this->operator;
    }
}