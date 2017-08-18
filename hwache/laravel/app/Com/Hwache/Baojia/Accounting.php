<?php
/**
 * Created by 华车价格计算.
 * User: cheney
 * Date: 2016/12/15
 * Time: 9:55
 */
namespace App\Com\Hwache\Baojia;
use DB;

/*
 * 测试用例
# use App\Com\Hwache\Baojia\Accounting;
class TestController extends Controller{
    protected $Price;
    public function __construct(){
        $this->Price = new Accounting();
    }

    public function index(){
        $lsit = $this->Price ->getPriceAll(371);
        dd($lsit);
    }

    public function save(){
        $lsit = $this->Price ->savePrice(731,167700);
        dd($lsit);
    }
}*
 */

class Accounting{
    protected $PRICE_TYPE;//华车服务费设置类型
    protected $TAX_RATE;
    protected $SERVER_PRICE;
    protected $HC_PROPORTION;
    protected $hcMinMaxPrice;//华车高低价
    const MAX_PARAM = 0.95;//高值固定参数
    const BASE_TABLE  = 'hg_baojia_price';//报价基础数据表
    const PRICE_TABLE = 'hc_price';

    public function __construct()
    {
        $this->PRICE_TYPE    = 1;//（1：固定类型，2：比例设置，3：动态调整）
        $this->TAX_RATE      = '0.94';//税率
        $this->HC_PROPORTION = '0.05';//华车佣金比例
        $this->SERVER_PRICE  = 500;
    }

    /**
     * 获取华车价格（系统计算）
     * @param $id
     * @return mixed
     */
    public function getPriceAll($id){
        //检索华车车价数据
        $isBaseData = $this->isCheckData(['id','=',$id]);
        //查找数据列表
        $basePrice = $this->getBaseData($id);

        $insertData = [
            'car_price'                    => $basePrice['car_price'],//开票价格
            'agent_service_price'          => $basePrice['agent_service_price'],//代理服务费
            'client_hand_price'            => $basePrice['client_hand_price'],//客户买车定金
            'client_license_compensate'    => $basePrice['client_license_compensate'],//客户上牌违约赔偿金
            'agent_numberplate_price'      => $basePrice['agent_numberplate_price'],
            'agent_temp_numberplate_price' => $basePrice['agent_temp_numberplate_price']
        ];
        if(empty($isBaseData)){
            //添加数据到华车价格表
            $insertData['id'] = $id;
            $this->insertPrice($insertData);
        }else{
            //同步更新华车数据
            DB::table(self::PRICE_TABLE)->where('id','=',$id)->update($insertData);
        }
        $priceFind = $this->getPriceFind($id);
        if(isset($priceFind['hwache_price']) && $priceFind['hwache_price'] > 0){
            $hcPrice = $priceFind['hwache_price'];
            $this->setPriceQuotes($basePrice['car_price'] , $basePrice['agent_service_price']);
        }else{
            //计算华车车价
            $hcPrice         = $this->setPriceQuotes($basePrice['car_price'] , $basePrice['agent_service_price']);
        }
        //计算华车服务费
        $hcServerPrice   = $this->setServerPrice($hcPrice , $basePrice['car_price']);
        $servers         = $this->setServers($basePrice['car_price']);
        //计算华车毛利
        $hcProfitMin      = $this->setProfit( $servers['minServer'] , $basePrice['agent_service_price']);
        $hcProfitMax      = $this->setProfit( $servers['maxServer'] , $basePrice['agent_service_price']);
        $hcProfit         = $this->setProfit( $hcServerPrice , $basePrice['agent_service_price']);

        //计算买车担保金
        $warrantPrice     = $this->setWarrentPrice($basePrice['client_hand_price'],$hcProfit,$hcServerPrice,$basePrice['client_license_compensate']);

        return [
            'hcPrice'           => $hcPrice,//华车车价
            'hcMinPrice'        => $this->hcMinMaxPrice['minPrice'],//华车车价低值
            'hcMaxPrice'        => $this->hcMinMaxPrice['maxPrice'],//华车车价高值
            'hcServerPrice'     => $hcServerPrice,//华车服务费
            'hcMinServerPrice'  => $servers['minServer'],//华车服务费低值
            'hcMaxServerPrice'  => $servers['maxServer'],//华车服务费高值
            'hcProfit'          => $hcProfit,//华车利润
            'hcMinProfit'       => $hcProfitMin,//华车利润低值
            'hcMaxProfit'       => $hcProfitMax,//华车利润高值
            'minSponsion'       => $warrantPrice['minSponsion'],//买车担保金低值
            'maxSponsion'       => $warrantPrice['maxSponsion'],//买车担保金高值
        ];
    }
    /**
     * @param $id        报价id
     * @param $hcPrice   华车价格
     * @return bool     保存价格
     */
    public function savePrice($id,$hcPrice){
        $priceFind = $this->getPriceFind($id);
        if($hcPrice <= 0){
            return false;
        }else{
            $dataPrice['hwache_price'] = $hcPrice;
            $prices = $this->getPriceAll($id);
            $dataPrice['hwache_price_low']            = $prices['hcMinPrice'];
            $dataPrice['hwache_price_high']           = $prices['hcMaxPrice'];
            $dataPrice['hwache_service_price']        = $prices['hcServerPrice'];
            $dataPrice['hwache_service_price_low']    = $prices['hcMinServerPrice'];
            $dataPrice['hwache_service_price_high']   = $prices['hcMaxServerPrice'];
            $dataPrice['hwache_margin_price']         = $prices['hcProfit'];
            $dataPrice['hwache_margin_price_low']     = $prices['hcMinProfit'];
            $dataPrice['hwache_margin_price_high']    = $prices['hcMaxProfit'];
            $dataPrice['client_sponsion_price_low']   = $prices['minSponsion'];
            $dataPrice['client_sponsion_price_high']  = $prices['maxSponsion'];
            return DB::table(self::PRICE_TABLE)->where('id','=',$id)->update($dataPrice);
        }
    }

    /**
     * 添加数据
     * @param $data 数据集合
     * @return bool
     */
    public function insertPrice($data){
        return DB::table(self::PRICE_TABLE)->insert($data);
    }
    /**
     * 计算华车车价
     * @param $car_price             华车开票价格
     * @param $agent_service_price   代理服务费
     * @return mixed
     */
    public function setPriceQuotes($car_price,$agent_service_price){
        //高值计算
        $maxPrice = $car_price/self::MAX_PARAM;
        $this->hcMinMaxPrice['maxPrice'] = $this->setPrice($maxPrice,0);
        //低值计算
        $minPrice = ($agent_service_price/$this->TAX_RATE) + $car_price;
        $minMoney = $minPrice + $this->SERVER_PRICE;

        $hcPrice  = ($minMoney > $maxPrice)?$maxPrice:$this->setPrice($minMoney);

        $this->hcMinMaxPrice['minPrice'] = $this->setPrice($minPrice,1);
        return $hcPrice;
        //return ($this->PRICE_TYPE ==2)?$this->hcMinMaxPrice['maxPrice']:$this->hcMinMaxPrice['minPrice'];
    }

    /**
     * 计算华车服务费
     * @param   $hcPrice   华车车价
     * @return $cartPrice  华车开票价
     */
    public function setServerPrice($hcPrice,$cartPrice){
        return $hcPrice - $cartPrice;
    }

    /**
     * 计算服务费高低价
     * @param $prices
     * @param $cartPrice
     * @return array
     */
    public function setServers($cartPrice){
        return [
            'minServer' =>  $this->hcMinMaxPrice['minPrice'] - $cartPrice,
            'maxServer' =>  $this->hcMinMaxPrice['maxPrice'] - $cartPrice,
        ];
    }
    /**
     * 计算华车利润
     * @param $serverPrice        服务费
     * @param $agentServerPrice   代理服务费
     * @return array|float
     */
    public function setProfit($serverPrice,$agentServerPrice){
        return round(($serverPrice - ($agentServerPrice / $this->TAX_RATE)),2);
    }

    /**
     * 计算买车担保金
     * @param $dj     客户买车定金
     * @param $ml     华车毛利
     * @param $server 华车服务费
     * @param $wyj    客户上牌违约金
     * @return array
     */
    public function setWarrentPrice($dj,$ml,$server,$wyj){
        $money    = $dj + $ml;
        $MaxMoney = ($money > $server) ? $money : $server;
        return [
            'maxSponsion' => $this->setDpj($this->setPrice($MaxMoney + $wyj)),
            'minSponsion' => $this->setDpj($this->setPrice($MaxMoney))
        ];
    }

    private function setDpj($price){
        return ($price < 500)?500:$price;
    }
    /**
     * @param $id
     * @return array|null  报价详情
     */
    public function getPriceFind($id){
        $pid = (int) $id;
        if(empty($pid)){
            return ['Sussess'=>0,'Msg'=>'报价标识错误！'];
        }
        return (array) DB::table(self::PRICE_TABLE)->where('id','=',$pid)->first();
    }

    /**
     * @param $where
     * @param string $table
     * @return mixed  判断数据
     */
    private function isCheckData($where,$table='hc_price'){
        return DB::table($table)->where($where[0],$where[1],$where[2])->count();
    }
    /**
     * 获取报价基础数据
     */
    private function getBaseData($bj_id){
        $pid = (int) $bj_id;
        if(empty($pid)){
            return ['Sussess'=>0,'Msg'=>'报价标识错误！'];
        }
        $col = sprintf(
            "bj_lckp_price as %s,bj_agent_service_price as %s,bj_doposit_price as %s,bj_license_plate_break_contract as %s,bj_shangpai_price as %s,bj_linpai_price as %s",
            'car_price','agent_service_price','client_hand_price','client_license_compensate','agent_numberplate_price','agent_temp_numberplate_price'
        );
        return (array) DB::table(self::BASE_TABLE)->select(DB::raw($col))->where('bj_id','=',$pid)->first();
    }
    /**
     * @param $price
     * @param int $type
     * @return string    设置精确到百位数
     */
    private function setPrice($price,$type=1){
        $addNum = ceil($price / 100) * 100;
        $delNum = floor($price / 100) * 100;
        return ($type==1)?$addNum:$delNum;
    }
}