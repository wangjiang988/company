<?php
/**
 * 车型报价之价格生成库
 *
 * 根据代理服务费(净收入)的值来确定不同档次的价格
 *
 * @author 技安 php360@qq.com
 * @copyright 苏州华车网络科技有限公司
 * @link http://www.hwache.com
 */

class Price
{
    /**
     * 裸车开票价
     *
     * @var int
     */
    private $exposeInvoicePrice;

    /**
     * 经销商代理服务费(净收入)
     *
     * @var int
     */
    private $agentServicePrice;

    /**
     * 经销商代理服务费档次等级
     *
     * @var array
     */
    private $agentServicePriceGrade = array(
        1 => 800,
        2 => 3360,
        3 => 21000,
        4 => 49500,
    );

    /**
     * 华车服务费
     *
     * @var int
     */
    private $hwacheServicePrice;

    /**
     * 经销商代理填写的消费者定金
     *
     * @var int
     */
    private $comsumerDepositProfit;

    /**
     * 消费者上牌违约赔偿金
     *
     * @var int
     */
    private $licensePlateBreakContract;

    /**
     * 毛利润
     *
     * @var int
     */
    private $profit;

    /**
     * 车价格包含服务费
     *
     * @var int
     */
    private $carPrice;

    /**
     * 车价初始化参数
     *
     * @param $exposeInvoicePrice               裸车开票价
     * @param $agentServicePrice                代理服务费(净收入)
     * @param $hwacheServicePrice               华车服务费
     * @param int $comsumerDepositProfit        经销商代理填写的消费者定金(默认为：0)
     * @param int $licensePlateBreakContract    消费者上牌违约赔偿金(默认为：0)
     */
    public function __construct($exposeInvoicePrice, $agentServicePrice, $hwacheServicePrice, $comsumerDepositProfit = 0, $licensePlateBreakContract = 0)
    {
        $this->exposeInvoicePrice        = $exposeInvoicePrice; // 裸车开票价
        $this->agentServicePrice         = $agentServicePrice; // 代理服务费(净收入)
        $this->hwacheServicePrice        = $hwacheServicePrice; // 华车服务费
        $this->comsumerDepositProfit     = $comsumerDepositProfit; // 经销商代理填写的消费者定金(默认为：0)
        $this->licensePlateBreakContract = $licensePlateBreakContract; // 消费者上牌违约赔偿金(默认为：0)

        /**
         * 得到毛利润
         */
        $this->profit = intval(ceil($this->checkAgentServicePrice()));

        /**
         * 得到当前价格档期的车价(包含服务费)
         * 毛利润+华车服务费+裸车开票价
         */
        $this->carPrice = $this->profit + $this->hwacheServicePrice + $this->exposeInvoicePrice;
    }

    /**
     * 获取当前价格档期的毛利润
     *
     * @return int
     */
    public function getProfitPrice()
    {
        return $this->profit;
    }

    /**
     * 获取当前价格档期的车价(包含服务费)
     * 返回大于该数值的最小的百位整数。例如：12345则返回12400
     *
     * @return int
     */
    public function getCarPrice()
    {
        return intval(ceil(($this->carPrice / 100))) * 100;
    }

    /**
     * 返回消费者需要交纳的保证金金额
     * x:代理服务费,y:代理填写的消费者保证金,z:上牌违约赔偿金
     * z>0 (x+z)和y比较
     * z=0 x和y比较
     * 返回大的值加华车服务费
     *
     * @return int
     */
    public function getCarGuarantee()
    {
        if ($this->licensePlateBreakContract) {
            $sum = $this->agentServicePrice + $this->licensePlateBreakContract;
            $r = ($sum > $this->comsumerDepositProfit) ? $sum : $this->comsumerDepositProfit;
        } else {
            $r  = ($this->agentServicePrice > $this->comsumerDepositProfit)
                ? $this->agentServicePrice
                : $this->comsumerDepositProfit;
        }

        return intval(ceil($r + $this->hwacheServicePrice));
    }

    /**
     * 检测代理服务费档次
     *
     * @return object
     */
    private function checkAgentServicePrice()
    {
        if ($this->agentServicePrice <= $this->agentServicePriceGrade[1]) {
            // <=800
            return $this->getPrice(function(){
                return $this->agentServicePrice / 0.94;
            });
        } elseif ($this->agentServicePrice <= $this->agentServicePriceGrade[2]) {
            // <=3360
            return $this->getPrice(function(){
                return ($this->agentServicePrice - 160) / 0.8 / 0.94;
            });
        } elseif ($this->agentServicePrice <= $this->agentServicePriceGrade[3]) {
            // <=21000
            return $this->getPrice(function(){
                return $this->agentServicePrice / 0.84 / 0.94;
            });
        } elseif ($this->agentServicePrice <= $this->agentServicePriceGrade[4]) {
            // <=49500
            return $this->getPrice(function(){
                return ($this->agentServicePrice - 2000) / 0.76 / 0.94;
            });
        } else {
            // >49500
            return $this->getPrice(function(){
                return ($this->agentServicePrice - 7000) / 0.68 / 0.94;
            });
        }
    }

    /**
     * 价格计算器闭包方法
     *
     * @param \Closure $callback
     * @return mixed
     */
    private function getPrice(Closure $callback)
    {
        return call_user_func($callback);
    }

}
