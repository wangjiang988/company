<?php
/**
 * 报价审核
 *
 * @package Admin
 * @author 技安 <php360@qq.com>
 * @link 技安后院 http://www.moqifei.com
 * @company 苏州华车网络科技有限公司 http://www.hwache.com
 */
defined('InHG') or exit('Access Invalid!');

class parameterControl extends SystemControl
{
    private $parameter;

    /**
     * parameterControl constructor.
     */
    public function __construct()
    {
        parent::__construct();

        require_once dirname(__DIR__) . '/framework/libraries/parameter.class.php';

        $this->parameter = new parameter();

        Tpl::output('title', '报价发布参数审核');
    }

    /**
     * 报价参数审核列表首页
     *
     * @return mixed
     */
    public function indexOp()
    {
        Tpl::output('data', [
            'list' => Model('hc_price_auditing_parameter')->getList(),
            'fields' => $this->parameter->getFieldLink(),
            'operators' => $this->parameter->getOperator(),
        ]);

        Tpl::showpage('parameter.index');
    }

    public function addOp()
    {
        Tpl::output('data', [
            'fields' => $this->parameter->getFieldLink(true),
            'operators' => $this->parameter->getOperator(true),
        ]);

        Tpl::showpage('parameter.add');
    }

    /**
     * post添加审核参数
     */
    public function postaddOp()
    {
        // 运算符
        $operators = $this->parameter->getOperator();
        // 数据整理
        $data = [
            'title' => $_POST['title'],
            'field' => $_POST['field'],
            'operator' => $operators[$_POST['operator']],
            'const' => $_POST['type'] == 1 ? $_POST['const'] : 0,
            'relation_field' => $_POST['type'] == 0 ? $_POST['relation_field'] : '',
            'multiple' => $_POST['type'] == 0 ? $_POST['multiple'] : 0,
            'open' => $_POST['open'],
            'open_time' => $_POST['open'] == 1 ? date('Y-m-d H:i:s') : 0,
        ];

        if ($res = $this->parameter->setData($data)->toAdd()) {
            $return = [
                'success' => true,
                'msg' => '添加成功',
            ];
        } else {
            $return = [
                'success' => false,
                'msg' => '添加失败，请重新添加',
            ];
        }

        echo json_encode($return);
    }

    public function postopenOp()
    {
        $id = $_POST['id'];

        if ($this->parameter->setOpen(true)->setOpenTime(date('Y-m-d H:i:s'))->toUpdate($id)) {
            $return = [
                'success' => true,
                'msg' => '启用成功',
            ];
        } else {
            $return = [
                'success' => false,
                'msg' => '启用失败，请重新再试',
            ];
        }

        echo json_encode($return);
    }

    public function postcloseOp()
    {
        $id = $_POST['id'];

        if ($this->parameter->setOpen()->setOpenTime(date('Y-m-d H:i:s'))->toUpdate($id)) {
            $return = [
                'success' => true,
                'msg' => '关闭成功',
            ];
        } else {
            $return = [
                'success' => false,
                'msg' => '关闭失败，请重新再试',
            ];
        }

        echo json_encode($return);
    }
}