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
namespace App\Com\Hwache\Baojia;

use DB;
use Cache;
use App\Core\Contracts\Money\Price as ContractPrice;

class Price implements ContractPrice
{
    /**
     * 价格数组
     *
     * @var array
     */
    private $price = [];

    /**
     * 设置车辆开票价
     *
     * @param int $price 车辆开票价格
     * @return $this
     */
    public function setCarPrice($price)
    {
        $this->price['car_price'] = $price;

        return $this;
    }

    /**
     * 设置代理服务费
     *
     * @param int $price 代理服务费价格
     * @return $this
     */
    public function setAgentServicePrice($price)
    {
        $this->price['agent_service_price'] = $price;

        return $this;
    }

    /**
     * 设置客户买车定金
     * 代理手动输入
     *
     * @param int $price 客户买车定金
     * @return $this
     */
    public function setClientHandPrice($price)
    {
        $this->price['client_hand_price'] = $price;

        return $this;
    }

    /**
     * 设置客户上牌违约赔偿金
     * 代理手动输入
     *
     * @param int $price 客户上牌违约赔偿金额
     * @return $this
     */
    public function setClientLicenseCompensate($price)
    {
        $this->price['client_license_compensate'] = $price;

        return $this;
    }

    /**
     * 设置代理上牌费
     * 代理手动输入
     *
     * @param int $price 代理上牌费用
     * @return $this
     */
    public function setAgentNumberplatePrice($price)
    {
        $this->price['agent_numberplate_price'] = $price;

        return $this;
    }

    /**
     * 设置代理上临牌费
     * 代理手动输入
     *
     * @param int $price 代理上临牌费用
     * @return $this
     */
    public function setAgentTempNumberplatePrice($price)
    {
        $this->price['agent_temp_numberplate_price'] = $price;

        return $this;
    }

    /**
     * 设置华车车价
     * 系统计算的金额
     *
     * @param int $price 华车车价
     * @return $this
     */
    public function setHwachePrice($price)
    {
        $this->price['hwache_price'] = $price;

        return $this;
    }

    /**
     * 设置华车车价最低值和最高值
     *
     * @param int $minPrice 最低值
     * @param int $maxPrice 最高值
     * @return $this
     */
    public function setMinMaxHwachePrice($minPrice, $maxPrice)
    {
        $this->price['hwache_price_low']  = $minPrice;
        $this->price['hwache_price_high'] = $maxPrice;

        return $this;
    }

    /**
     * 设置华车服务费
     * 系统计算的金额
     *
     * @param int $price 华车服务费金额
     * @return $this
     */
    public function setHwacheServicePrice($price)
    {
    $this->price['hwache_service_price'] = $price;

        return $this;
    }

    /**
     * 设置华车服务费最低值和最高值
     *
     * @param int $minPrice 最低值
     * @param int $maxPrice 最高值
     * @return $this
     */
    public function setMinMaxHwacheServicePrice($minPrice, $maxPrice)
    {
        $this->price['hwache_service_price_low']  = $minPrice;
        $this->price['hwache_service_price_high'] = $maxPrice;

        return $this;
    }

    /**
     * 设置华车毛利润
     * 系统计算的金额
     *
     * @param int $price 华车毛利润金额
     * @return $this
     */
    public function setHwacheMarginPrice($price)
    {
        $this->price['hwache_margin_price'] = $price;

        return $this;
    }

    /**
     * 设置华车毛利润最低值和最高值
     *
     * @param int $minPrice 最低值
     * @param int $maxPrice 最高值
     * @return $this
     */
    public function setMinMaxHwacheMarginPrice($minPrice, $maxPrice)
    {
        $this->price['hwache_margin_price_low']  = $minPrice;
        $this->price['hwache_margin_price_high'] = $maxPrice;

        return $this;
    }

    /**
     * 设置客户买车担保金
     * 系统计算的金额
     *
     * @param int $price 买车担保金额
     * @return $this
     */
    public function setClientSponsionPrice($price)
    {
        $this->price['client_sponsion_price'] = $price;

        return $this;
    }

    /**
     * 添加报价相关价格数据
     *
     * @param int $baojiaId 报价主键ID
     * @return array
     */
    public function toAdd($baojiaId)
    {
        $this->price['id'] = $baojiaId;

        if (DB::table('hc_price')->insert($this->price)) {
            return [
                'success' => true
            ];
        }

        return [
            'success' => false,
            'msg'     => trans('price.insert_price_false'),
        ];
    }

    /**
     * 编辑报价价格
     *
     * @param int $baojiaId 报价主键ID
     * @return array
     */
    public function toSave($baojiaId)
    {
        if (DB::table('hc_price')->where('id', $baojiaId)->update($this->price) !== false) {
            return [
                'success' => true
            ];
        }

        return [
            'success' => false,
            'msg'     => trans('price.update_price_false'),
        ];
    }

    /**
     * 删除指定报价的价格数据
     *
     * @param int $baojiaId 报价主键ID
     * @return array
     */
    public function toDelete($baojiaId)
    {
        if (DB::table('hc_price')->where('id', $baojiaId)->delete() !== false) {
            return [
                'success' => true
            ];
        }

        return [
            'success' => false,
            'msg'     => trans('price.delete_price_false'),
        ];
    }

    /**
     * 获取华车车价
     * 得到最低值和最高值两个。车价是最低值加上后台设定的固定值。
     * 结果不是100的整数倍，则增加到大于该值的最小100整数倍数值。
     *
     * @param int $agentServicePrice 代理服务费
     * @param int $carPrice 车辆开票价
     * @return array
     */
    public function getHwachePrice($agentServicePrice, $carPrice)
    {
        // 高值固定参数 (1-5%) = 0.95
        $highParam = 0.95;

        // 读取税务系数
        $taxationQuotiety = floatval($this->_getConfig()['taxation_quotiety']);

        // 计算低值,升百位
        $hwachePriceLow = $this->_hundredCarry(intval($agentServicePrice / $taxationQuotiety + $carPrice));
        // 计算高值,降百位
        $hwachePriceHigh = $this->_hundredCarry(intval($carPrice / $highParam), false);
        // 华车车价 = 低值加上后台固定值价格
        $hwachePrice = $hwachePriceLow + $this->_getConfig()['hwache_price_immobilisation_factor'];
        $hwachePrice = $hwachePrice < $hwachePriceHigh ? $hwachePrice : $hwachePriceHigh; // 和高值作比较

        // 华车服务费
        $hwacheServicePrice = $this->getHwacheServicePrice($hwachePrice, $carPrice);
        // 华车服务费低值
        $hwacheServicePriceLow = $this->getHwacheServicePrice($hwachePriceLow, $carPrice);
        // 华车服务费高值
        $hwacheServicePriceHigh = $this->getHwacheServicePrice($hwachePriceHigh, $carPrice);

        // 华车毛利 不大于华车车价的5%
        $hwacheMarginPrice = $this->getHwacheMarginPrice($hwacheServicePrice, $agentServicePrice);
        $hwacheMarginPrice = ($hwacheMarginPrice < $hwachePrice * 0.05) ? $hwacheMarginPrice : $hwachePrice * 0.05;
        // 华车毛利低值
        $hwacheMarginPriceLow = $this->getHwacheMarginPrice($hwacheServicePriceLow, $agentServicePrice);
        // 华车毛利高值
        $hwacheMarginPriceHigh = $this->getHwacheMarginPrice($hwacheServicePriceHigh, $agentServicePrice);

        return [
            'success' => true,
            'data'    => [
                'hwachePrice'           => $hwachePrice, // 华车车价
                'minHwachePrice'        => $hwachePriceLow, // 华车车价低值
                'maxHwachePrice'        => $hwachePriceHigh, // 华车车价高值
                'hwacheServicePrice'    => $hwacheServicePrice, // 华车服务费
                'minHwacheServicePrice' => $hwacheServicePriceLow, // 华车服务费低值
                'maxHwacheServicePrice' => $hwacheServicePriceHigh, // 华车服务费高值
                'hwacheMarginPrice'     => $hwacheMarginPrice, // 华车毛利
                'minHwacheMarginPrice'  => $hwacheMarginPriceLow, // 华车毛利低值
                'maxHwacheMarginPrice'  => $hwacheMarginPriceHigh, // 华车毛利高值
            ],
        ];
    }

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
    public function getHwacheServicePrice($hwachePrice, $carPrice)
    {
        return $hwachePrice - $carPrice;
    }

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
    public function getHwacheMarginPrice($hwacheServicePrice, $agentServicePrice)
    {
        // 读取税务系数
        $taxationQuotiety = floatval($this->_getConfig()['taxation_quotiety']);

        return round(($hwacheServicePrice - $agentServicePrice / $taxationQuotiety), 2);
    }

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
    )
    {
        $priceTemp = $clientHandPrice + $hwacheMarginPrice;
        $priceTemp = $priceTemp > $hwacheServicePrice ? $priceTemp : $hwacheServicePrice;

        return $this->_hundredCarry($priceTemp + $clientLicenseCompensate);
    }

    /**
     * 根据报价ID获取该报价对应的各种价格数据
     *
     * @param int $baojiaId 报价的主键ID
     * @return array
     */
    public function getBaojiaPriceById($baojiaId)
    {
        return (array) DB::table('hc_price')->where('id', $baojiaId)->first();
    }

    /**
     * 校验车辆开票价
     * 大于等于厂商指导价50%
     *
     * @param int $carPrice 车辆开票价
     * @param int $zhidaoPrice 厂商指导价
     * @return array
     */
    public function verifyCarPrice($carPrice, $zhidaoPrice)
    {
        if ($carPrice >= ($zhidaoPrice * 0.5)) {
            return [
                'success' => true,
            ];
        }

        return [
            'success' => false,
            'msg' => trans('price.verify_car_price_false'),
        ];
    }

    /**
     * 验证代理服务费
     * 车辆开票价3%与5万之间取其小值
     *
     * @param int $price 价格
     * @param int $carPrice 车辆开票价
     * @return array
     */
    public function verifyAgentServicePrice($price, $carPrice)
    {
        // 固定的5万
        $fixedPrice = 50000;
        // 可变的车辆开票价的3%
        $unfixedPrice = $carPrice * 0.03;
        // 取其小值
        $compare = $unfixedPrice < $fixedPrice ? $unfixedPrice : $fixedPrice;

        if ($price <= $compare) {
            return [
                'success' => true,
            ];
        }

        return [
            'success' => false,
            'msg' => trans('price.verify_agent_service_price_false'),
        ];
    }

    /**
     * 验证客户上牌违约赔偿金
     * 必须是100的整数倍
     *
     * @param int $price 金额
     * @return array
     */
    public function verifyClientLicenseCompensate($price)
    {
        if ($this->_verifyHundred($price)) {
            return [
                'success' => true,
            ];
        }

        return [
            'success' => false,
            'msg' => trans('price.verify_client_license_compensate_false'),
        ];
    }

    /**
     * 验证代办上牌服务费
     * 必须是100的整数倍
     *
     * @param int $price 金额
     * @return array
     */
    public function verifyAgentNumberplatePrice($price)
    {
        if ($this->_verifyHundred($price)) {
            return [
                'success' => true,
            ];
        }

        return [
            'success' => false,
            'msg' => trans('price.verify_agent_numberplate_price_false'),
        ];
    }

    /**
     * 验证代办临时牌照服务费
     * 必须是100的整数倍
     *
     * @param int $price 金额
     * @return array
     */
    public function verifyAgentTempNumberplatePrice($price)
    {
        if ($this->_verifyHundred($price)) {
            return [
                'success' => true,
            ];
        }

        return [
            'success' => false,
            'msg' => trans('price.verify_agent_temp_numberplate_price_false'),
        ];
    }

    /**
     * 升至最小100整数倍
     *
     * @param int $price 价格
     * @param bool $up 升计算，为false则做降计算
     * @return int $price 大于参数的最小100的整数倍数值
     */
    private function _hundredCarry($price, $up = true)
    {
        if ($price <= 100) {
            $price = 100;
        } elseif ($price % 100 != 0) {
            if ($up) {
                $price = intval(ceil(($price / 100))) * 100;
            } else {
                $price = intval(floor(($price / 100))) * 100;
            }
        }

        return $price;
    }

    /**
     * 验证100整数倍
     *
     * @param int $price 价格
     * @return bool
     */
    private function _verifyHundred($price)
    {
        if ($price % 100 == 0) {
            return true;
        }

        return false;
    }

    /**
     * 读取系统资金配置表，默认缓存24小时，超时重新读取
     *
     * @return mixed
     */
    private function _getConfig()
    {
        $cacheName = 'hc_money';

        if (Cache::has($cacheName)) {
            return Cache::get($cacheName);
        }

        return Cache::remember($cacheName, 24 * 60, function() {
            $config = DB::table('hg_money')->get();

            $return = [];
            foreach ($config as $item) {
                $return[$item->name] = $item->value;
            }

            return $return;
        });
    }
}