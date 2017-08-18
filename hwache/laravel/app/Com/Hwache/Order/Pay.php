<?php namespace App\Com\Hwache\Order;
/**
 * 支付功能模块
 *
 * @author Andy php360@qq.com
 * @copyright 苏州华车网络科技有限公司
 * @link http://www.hwache.com
 */

class Pay
{
    /**
     * 第三方支付列表
     * @var array
     */
    protected $payment = [
        'alipay'    => [
            'name'  => '支付宝',
            'route' => 'pay.alipay',
        ],
    ];

    /**
     * 获取支付方式
     * @return array
     */
    public function getAllPayment()
    {
        // TODO 这里查询所有的支付方式，包含从数据库中读取
        return $this->payment;
    }

    /**
     * 检测第三方支付合法性
     * @param $pay_name
     * @return bool
     */
    public function checkPayment($pay_name)
    {
        // 获取所有的支付方式
        $this->getAllPayment();

        if (isset($this->payment[$pay_name])) {
            return true;
        }

        return false;
    }

    /**
     * 获取当前支付方式的信息
     * @param $pay_name
     * @return bool
     */
    public function getPayment($pay_name)
    {
        if ($this->checkPayment($pay_name)) {
            return $this->payment[$pay_name];
        }

        return false;
    }

    public function requestPay()
    {
        switch (session('order.payment')) {
            case 'alipay' :
                return redirect()->route('pay.alipay');
                break;
        }
    }

}
