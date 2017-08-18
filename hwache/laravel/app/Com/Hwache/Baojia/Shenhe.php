<?php
/**
 * 报价参数系统审核规则判断
 *
 * 用法：
 *      框架中 use App\Com\Hwache\Baojia\Shenhe
 *      $shenhe = new Shenhe();
 *      $shenhe->verifyBaojia($baojiaId [, $return]);
 * 参数：
 *      $baojiaId 必须 报价的id
 *      $return 可选 true/false，是否以返回值的方式。
 *              默认false，则直接写入数据库；
 *              设置true，则返回数组；
 *
 * 返回值：数组
 *      如果success为true，则list数组为空
 *      [
 *          'success' => true,
 *          'list' => []
 *      ]
 *      如果success为false，则list数组是审核不通过的信息
 *      [
 *          "success" => false
 *          "list" => [
 *              0 => "代办上牌服务费 应当 < 2000.00"
 *              1 => "厂商指导价 应当 <= 厂商指导价 的 0.1 倍"
 *              2 => "代办上牌服务费 应当 <= 厂商指导价 的 0.1 倍"
 *          ]
 *      ]
 *
 * @author 技安 <php360@qq.com>
 * @link 技安后院 https://www.moqifei.com
 * @company 苏州华车网络科技有限公司
 */
namespace App\Com\Hwache\Baojia;

use DB;
use Log;
use Carbon\Carbon;

class Shenhe
{
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
    ];

    /**
     * 返回值数组
     *
     * @var array
     */
    private $result = [
        'success' => true,
        'list' => []
    ];

    /**
     * 通过报价审核参数验证报价是否合规
     * 这是唯一对外暴露的方法
     *
     * @param $baojiaId
     * @param bool $return 是否返回数据，默认为false，直接内部写入数据库中
     *
     * @return mixed
     */
    public function verifyBaojia($baojiaId, $return = false)
    {
//        dd($this->getParamList());
        foreach ($this->getParamList() as $item) {
            if (!$this->comparePrice($baojiaId, $item)) {
                $this->result['success'] = false;
                $this->result['list'][] = empty($item->relation_field)
                    ? $this->fieldLink[$item->field].' 应当 '.$item->operator.' '.$item->const
                    : $this->fieldLink[$item->field].' 应当 '.$item->operator.' '.$this->fieldLink[$item->relation_field].' 的 '.$item->multiple.' 倍';
            }
        }

        // 返回结果
        if ($return) {
            return $this->result;
        }

        // 直接写入数据库
        if (!$this->result['success']) {
            DB::table('hg_baojia')->where('bj_id', $baojiaId)->update(['bj_is_public' => 2]);

            $remark = '';
            foreach ($this->result['list'] as $key => $value) {
                $remark .= ($key + 1).':'.$value."\n";
            }
            $status = 2;
        } else {
            // 系统审核通过则报价正常
            DB::table('hg_baojia')->where('bj_id', $baojiaId)->update(['bj_is_public' => 1]);
            $remark = '正常';
            $status = 1;
        }

        DB::table('hc_baojia_public')->insert([
            'baojia_id' => $baojiaId,
            'member_id' => 5, // system专用账户id
            'status' => $status,
            'remark' => $remark,
            'created_at' => Carbon::now()
        ]);

        return null;
    }

    /**
     * 获取审核规则已启用的列表
     *
     * @return mixed
     */
    private function getParamList()
    {
        return DB::table('hc_price_auditing_parameter')
            ->where('open', 1)
            ->get();
    }

    /**
     * 获取报价的规则内的参数字段价格
     *
     * @param $baojiaId
     *
     * @return mixed
     */
    private function getPrice($baojiaId)
    {
        return DB::table('hc_price')
            ->select(
                'agent_numberplate_price',
                'agent_temp_numberplate_price',
                'client_license_compensate',
                'client_hand_price')
            ->where('id', $baojiaId)
            ->first();
    }

    /**
     * 获取指导价
     *
     * @param $baojiaId
     * @return float
     */
    private function getZhidaojia($baojiaId)
    {
        $zhidaojia = DB::table('hg_baojia')
            ->leftJoin('hg_car_info', 'hg_baojia.brand_id', '=', 'hg_car_info.gc_id')
            ->where('hg_baojia.bj_id', $baojiaId)
            ->where('hg_car_info.model', 'carmodel')
            ->where('hg_car_info.name', 'zhidaojia')
            ->value('hg_car_info.value');

        if (!empty($zhidaojia)) {
            return (float) unserialize($zhidaojia);
        }

        return 0;
    }

    /**
     * 获取规则里参数的价格
     * 根据不同的规则参数计算出被比较的价格
     *
     * @param resource $item
     * @param $price 该报价已经生成的各种价格
     * @param $zhidaojia 该报价对应车型的指导价
     *
     * @return float
     */
    private function getParamPrice($item, $price, $zhidaojia)
    {
        if (!empty($item->relation_field)) {
            switch ($item->relation_field) {
                case 'zhidaojia' :
                    $return = $zhidaojia * (float) $item->multiple;
                    break;
                case 'agent_numberplate_price' :
                    $return = (float) $price->agent_numberplate_price * (float) $item->multiple;
                    break;
                case 'agent_temp_numberplate_price' :
                    $return = (float) $price->agent_temp_numberplate_price * (float) $item->multiple;
                    break;
                case 'client_license_compensate' :
                    $return = (float) $price->client_license_compensate * (float) $item->multiple;
                    break;
                case 'client_hand_price' :
                    $return = (float) $price->client_hand_price * (float) $item->multiple;
                    break;
                default :
                    $return = 0;
            }

            return $return;
        }

        return (float) $item->const;
    }

    /**
     * 比较价格
     *
     * @param $baojiaId 报价id
     * @param resource $item 报价参数对象
     *
     * @return bool
     */
    private function comparePrice($baojiaId, $item)
    {
        $first = $second = 0;

        $price = $this->getPrice($baojiaId);
        $zhidaojia = $this->getZhidaojia($baojiaId);

        switch ($item->field) {
            case 'zhidaojia' :
                $first = $zhidaojia;
                $second = $this->getParamPrice($item, $price, $zhidaojia);
                break;
            case 'agent_numberplate_price' :
                $first = (float) $price->agent_numberplate_price;
                $second = $this->getParamPrice($item, $price, $zhidaojia);
                break;
            case 'agent_temp_numberplate_price' :
                $first = (float) $price->agent_temp_numberplate_price;
                $second = $this->getParamPrice($item, $price, $zhidaojia);
                break;
            case 'client_license_compensate' :
                $first = (float) $price->client_license_compensate;
                $second = $this->getParamPrice($item, $price, $zhidaojia);
                break;
            case 'client_hand_price' :
                $first = (float) $price->client_hand_price;
                $second = $this->getParamPrice($item, $price, $zhidaojia);
                break;
        }
//dd($item, $first, $second);
        return $this->compareOperator($item->operator, $first, $second);
    }

    /**
     * 根据运算符做比较
     *
     * @param string $operator 运算符
     * @param int/float $first 比较值
     * @param int/float $second 被比较值
     *
     * @return bool
     */
    private function compareOperator($operator, $first, $second)
    {
        switch ($operator) {
            case '<' :
                $return = $first < $second;
                break;
            case '<=' :
                $return = $first <= $second;
                break;
            case '=' :
                $return = $first == $second;
                break;
            case '>=' :
                $return = $first >= $second;
                break;
            case '>' :
                $return = $first > $second;
                break;
            default :
                $return = false;
        }

        return $return;
    }
}