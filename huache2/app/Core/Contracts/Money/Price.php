<?php
/**
 * 价格生成接口
 *
 * @author 李扬(Andy) <php360@qq.com>
 * @link 技安后院 http://www.moqifei.com
 * @copyright 苏州华车网络科技有限公司 http://www.hwache.com
 *
 * @version 1.0.1
 */
namespace App\Core\Contracts\Money;

interface Price
{
    /**
     * 设置车辆开票价
     *
     * @param int $price 车辆开票价格
     * @return $this
     */
    public function setCarPrice($price);

    /**
     * 设置代理服务费
     *
     * @param int $price 代理服务费价格
     * @return $this
     */
    public function setAgentServicePrice($price);

    /**
     * 设置客户买车定金
     * 代理手动输入
     *
     * @param int $price 客户买车定金
     * @return $this
     */
    public function setClientHandPrice($price);

    /**
     * 设置客户上牌违约赔偿金
     * 代理手动输入
     *
     * @param int $price 客户上牌违约赔偿金额
     * @return $this
     */
    public function setClientLicenseCompensate($price);

    /**
     * 设置代理上牌费
     * 代理手动输入
     *
     * @param int $price 代理上牌费用
     * @return $this
     */
    public function setAgentNumberplatePrice($price);

    /**
     * 设置代理上临牌费
     * 代理手动输入
     *
     * @param int $price 代理上临牌费用
     * @return $this
     */
    public function setAgentTempNumberplatePrice($price);

    /**
     * 设置华车车价
     * 系统计算的金额
     *
     * @param int $price 华车车价
     * @return $this
     */
    public function setHwachePrice($price);

    /**
     * 设置华车车价最低值和最高值
     *
     * @param int $minPrice 最低值
     * @param int $maxPrice 最高值
     * @return $this
     */
    public function setMinMaxHwachePrice($minPrice, $maxPrice);

    /**
     * 设置华车服务费
     * 系统计算的金额
     *
     * @param int $price 华车服务费金额
     * @return $this
     */
    public function setHwacheServicePrice($price);

    /**
     * 设置华车服务费最低值和最高值
     *
     * @param int $minPrice 最低值
     * @param int $maxPrice 最高值
     * @return $this
     */
    public function setMinMaxHwacheServicePrice($minPrice, $maxPrice);

    /**
     * 设置华车毛利润
     * 系统计算的金额
     *
     * @param int $price 华车毛利润金额
     * @return $this
     */
    public function setHwacheMarginPrice($price);

    /**
     * 设置华车毛利润最低值和最高值
     *
     * @param int $minPrice 最低值
     * @param int $maxPrice 最高值
     * @return $this
     */
    public function setMinMaxHwacheMarginPrice($minPrice, $maxPrice);

    /**
     * 设置客户买车担保金
     * 系统计算的金额
     *
     * @param int $price 买车担保金额
     * @return $this
     */
    public function setClientSponsionPrice($price);

    /**
     * 添加报价相关价格数据
     *
     * @param int $baojiaId 报价主键ID
     * @return array
     */
    public function toAdd($baojiaId);

    /**
     * 编辑报价价格
     *
     * @param int $baojiaId 报价主键ID
     * @return array
     */
    public function toSave($baojiaId);

    /**
     * 删除指定报价的价格数据
     *
     * @param int $baojiaId 报价主键ID
     * @return array
     */
    public function toDelete($baojiaId);

    /**
     * 获取华车车价
     * 得到最低值和最高值两个。车价是最低值加上后台设定的固定值。
     * 结果不是100的整数倍，则增加到大于该值的最小100整数倍数值。
     *
     * @param int $agentServicePrice 代理服务费
     * @param int $carPrice 车辆开票价
     * @return array
     */
    public function getHwachePrice($agentServicePrice, $carPrice);

    /**
     * 获取华车服务费
     * 华车车价-车辆开票价 与 华车车价*5% 取其大值
     *
     * @param float $hwachePrice 华车车价
     * @param float $carPrice 车辆开票价
     * @return float
     *
     * @deprecated since version 1.0.1
     */
    public function getHwacheServicePrice($hwachePrice, $carPrice);

    /**
     * 获取华车毛利
     * 花车服务费-（代理服务费/税务系数）
     *
     * @param float $hwacheServicePrice 华车服务费
     * @param float $agentServicePrice 代理服务费
     * @return float
     *
     * @deprecated since version 1.0.1
     */
    public function getHwacheMarginPrice($hwacheServicePrice, $agentServicePrice);

    /**
     * 获取客户买车担保金
     * （客户买车定金+华车毛利）与华车服务费取最大值 + 客户上牌违约赔偿金。
     * 如果结果不是100的整数倍数，则进位比该数值大的最小的100整数倍
     *
     * @param float $clientHandPrice 客户买车定金
     * @param float $hwacheMarginPrice 华车毛利
     * @param float $hwacheServicePrice 华车服务费
     * @param float $clientLicenseCompensate 客户上牌违约赔偿金
     * @return int
     */
    public function getClientSponsionPrice(
        $clientHandPrice,
        $hwacheMarginPrice,
        $hwacheServicePrice,
        $clientLicenseCompensate
    );

    /**
     * 根据报价ID获取该报价对应的各种价格数据
     *
     * @param int $baojiaId 报价的主键ID
     * @return array
     */
    public function getBaojiaPriceById($baojiaId);

    /**
     * 校验车辆开票价
     * 大于等于厂商指导价50%
     *
     * @param int $carPrice 车辆开票价
     * @param int $zhidaoPrice 厂商指导价
     * @return array
     */
    public function verifyCarPrice($carPrice, $zhidaoPrice);

    /**
     * 验证代理服务费
     * 车辆开票价3%与5万之间取其小值
     *
     * @param int $price 价格
     * @param int $carPrice 车辆开票价
     * @return array
     */
    public function verifyAgentServicePrice($price, $carPrice);

    /**
     * 验证客户上牌违约赔偿金
     * 必须是100的整数倍
     *
     * @param int $price 金额
     * @return array
     */
    public function verifyClientLicenseCompensate($price);

    /**
     * 验证代办上牌服务费
     * 必须是100的整数倍
     *
     * @param int $price 金额
     * @return array
     */
    public function verifyAgentNumberplatePrice($price);

    /**
     * 验证代办临时牌照服务费
     * 必须是100的整数倍
     *
     * @param int $price 金额
     * @return array
     */
    public function verifyAgentTempNumberplatePrice($price);
}