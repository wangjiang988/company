<?php

/**
 * 用户账户金额
 */

namespace App\Com\Hwache\Jiaxinbao;

use App\Events\AccountTooLowEvent;
use App\Repositories\Contracts\HcDailiAccountRepositoryInterface;
use App\Repositories\Contracts\HcDailiAccountLogRepositoryInterface;
use App\Repositories\Contracts\HcUserAccountRepositoryInterface;
use App\Repositories\Contracts\HcUserAccountLogRepositoryInterface;
use App\Repositories\Contracts\HcAccountLogRepositoryInterface;
use App\Models\HcUserRecharge;
use App\Models\HcOrderJiaXinBao;
use App\Models\HgOrder;
use App\Models\HcItem;
use App\Models\HcVoucherLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Account
{
    const ACCONNT_TYPE = ['member','seller'];
    const ADD_FREEZE_TYPE    = 10;//添加冻结
    const REMOVE_FREEZE_TYPE = 20;//解除冻结

    protected $khJxb = [
        1=>'KHJXB-0100',//可用余额（诚意金）
        2=>'KHJXB-0101',//代金券+可用余额（诚意金）
        3=>'KHJXB-0200',//可用余额（买车担保金）
        4=>'KHJXB-0201',//线上支付（买车担保金余款）
        5=>'KHJXB-0202',//银行转账（买车担保金余款）
        6=>'KHJXB-0203',//代金券+可用余额（买车担保金余款）
        7=>'KHJXB-0301',//诚意金赔偿
        8=>'KHJXB-0302',//买车担保金赔偿
        9=>'KHJXB-0303',//诚意金赔偿&买车担保金赔偿
        10=>'KHJXB-0401',//已退还可用余额
        11=>'KHJXB-0402'//失效代金券
    ];

    protected $sfJxb =[
        1=>'SFJXB-0001',//	歉意金（可用余额）
        2=>'SFJXB-0002',//	客户买车担保金利息
        3=>'SFJXB-0003',//	歉意金赔偿
        4=>'SFJXB-0004',//	客户买车担保金利息赔偿
        5=>'SFJXB-0005',//	已退还可提现余额
        6=>'SFJXB-0006',//	赔偿售方买车担保金
    ];

    private $role;//加信宝账号类型（用户、售方）
    protected $jxbType;//加信宝类型（冻结、解冻）
    protected $dailiAccount , $userAccount , $accountLog , $dailiAccountLog , $userAccountLog , $errorMsg;

    public function __construct(
                                HcDailiAccountRepositoryInterface $hcDailiAccountRepository,
                                HcUserAccountRepositoryInterface $hcUserAccountRepository,
                                HcAccountLogRepositoryInterface $hcAccountLogRepository,
                                HcDailiAccountLogRepositoryInterface $hcDailiAccountLogRepository,
                                HcUserAccountLogRepositoryInterface $hcUserAccountLogRepository
    )
    {
        $this->dailiAccount    = $hcDailiAccountRepository;
        $this->userAccount     = $hcUserAccountRepository;
        $this->accountLog      = $hcAccountLogRepository;
        $this->dailiAccountLog = $hcDailiAccountLogRepository;
        $this->userAccountLog  = $hcUserAccountLogRepository;
    }
    /** 获取加信宝类型
     * @param $code
     * @return mixed
     */
    protected function getItems($code,$from=''){
        $items = HcItem::where(['code'=>$code])->first();
        if($items) {
            $items->from = $from;
        }
        return $items;
    }
    /**
     * 加信宝(支付诚意金)
     * @param $order_id       订单id
     * @param $money          金额
     * @param null $voucher   代金券
     * @param string $subject (1可用余额（诚意金）, 2代金券+可用余额（诚意金）
     */
    public function addUserJxb($order_id,$money,$subject=1,$voucher=null)
    {
        $orderInfo = $this->getOrderInfo($order_id);
        //获取加信宝类型数据
        $itemInfo = $this->getItems($this->khJxb[$subject]);
        //添加加信宝数据
        $this->insertJxbTable($orderInfo->id,$itemInfo,$orderInfo->user_id,$money,10,$voucher);
        //修改客户账号金额
        $this->updateFreezeData($orderInfo->user_id,[
                'avaliable_deposit'=>"avaliable_deposit-{$money}",
                'total_deposit'=> "total_deposit-{$money}",
                'freeze_deposit'=> "freeze_deposit+{$money}"
            ]);
        //更新客户订单加信宝数据
        $this->setOrderJxb($orderInfo->id,$money,10,'member');
        //付诚意金是冻结歉意金。
        $this->addSellerJxb($order_id,$money,1,$voucher);
    }

    /**
     * 加信宝(支付担保金)
     * @param $order_id    订单id
     * @param $money       金额
     * @param null $voucher 代金券
     * @param string $subject (1可用余额（诚意金）, 2线上支付（诚意金）, 3代金券+可用余额（诚意金）
     */
    public function payDepositJxb($order_id,$money,$subject=1,$voucher=null)
    {
        $orderInfo = $this->getOrderInfo($order_id);
        //获取加信宝类型数据
        $itemInfo = $this->getItems($this->khJxb[$subject]);
        //添加加信宝数据
        $this->insertJxbTable($orderInfo->id,$itemInfo,$orderInfo->user_id,$money,10,$voucher);
        //修改客户账号金额
        $this->updateFreezeData($orderInfo->user_id,[
            'avaliable_deposit' => "avaliable_deposit-{$money}",
            'total_deposit'     => "total_deposit-{$money}",
            'freeze_deposit'    => "freeze_deposit+{$money}"
        ]);
        //更新客户订单加信宝数据
        $this->setOrderJxb($orderInfo->id,$money,10,'member');
        //付诚意金是冻结歉意金。
        $this->addSellerJxb($order_id,$money,1,$voucher);
        // todo 查找订单是否使用代金券(多个代金券逻辑)
        //$isVoucher = $this->voucherLogFind($order_id);
        //付担保金是冻结利息*/
        $this->addSellerJxb($order_id,$money,2,$voucher);
    }

    private function setErrorMsg($message,$errCode='0001')
    {
        $this->errorMsg = ['message'=>$message , 'code' => $errCode];
    }
    /**
     * @param $order_id   售方加信宝
     * @param int $subject (7歉意金（可用余额）,8客户买车担保金利息)
     */
    public function addSellerJxb($order_id,$money,$subject=1,$voucher=null)
    {
        $orderInfo = $this->getOrderInfo($order_id);
        $price  =  ($subject) ? $money : $this->setDbjFree($money);
        $itemInfo = $this->getItems($this->sfJxb[$subject]);
        //添加加信宝数据
        $this->insertJxbTable($orderInfo->id,$itemInfo,$orderInfo->user_id,$price,10,$voucher);
        //修改客户账号金额
        $this->dailiAccount->UpdateFreezeDeposit($orderInfo->seller_id,$price);
        //更新客户订单加信宝数据
        $this->setOrderJxb($orderInfo->id,$money,10,'seller');
    }
    /**
     * 解冻用户加信宝
     * @param $orderid
     * @param int $subject 解冻具体操作（0诚意金赔偿&买车担保金赔偿，1转付华车服务费，2已退还可用余额
     * $returnMoney 退还可用余额-金额
     */
    public function removeUserJxb($order_id,$subject=0,$returnMoney=0)
    {
        $orderInfo = $this->getOrderInfo($order_id);
        $cyjMoney  = (int) $orderInfo->earnest_price;//诚意金
        $dbjMoney  = (int) $orderInfo->sponsion_price;//担保金
        switch($subject){
            case 0://诚意金赔偿（客户付完诚意金后，售方终止时触发【TODO 判断代金券、如果有失效代金券】）
                $toResult = $this->getReferee($order_id);
                //['items'=>$items,'moneys'=>$moneys,'users'=>$users,'subject'=> $subjects,'toObject'=>$toObject];
                //TODO 代金券
                break;
            break;
            case 1://转付华车服务费（结算时触发【扣除用户的可以余额】）
                $toObject = $this->setToObject('转付华车服务费','转付华车服务费',$this->getHcPrice($orderInfo),'system','member');
                $toResult = ['items'=>['KHJXB-0010'],'moneys'=>[$cyjMoney+$dbjMoney],'users'=>['member'],'subject'=> [10],'toObject'=>[$toObject]];
                break;
            case 2://已退还可用余额（）
                $item = (object) ['name'=>'已退还可提现余额','description'=>'已退还可提现余额','from'=>''];
                return $this->addAccount($orderInfo, $cyjMoney , $item, 20, 'member');
                break;
            default:
                $this->setErrorMsg('不存在该解冻操作！','SUBJECT-000'.$subject);
                return false;exit;
        }
        return $this->removeFromDataToData($orderInfo,$toResult);
    }

    /**
     * 售方解冻操作
     * @param $order_id
     * @param int $subject      解冻具体操作 （0歉意金赔偿，1已退还可提现余额）
     * @param int $returnMoney 已退还可提现余额
     */
    public function removeSellerJxb($order_id,$subject=0,$returnMoney=0)
    {
        $orderInfo = $this->getOrderInfo($order_id);
        $cyjMoney  = (int) $orderInfo->earnest_price;//诚意金
        //$dbjMoney  = (int) $orderInfo->sponsion_price;//担保金
        switch($subject){
            case 0://歉意金赔偿
                $toObject = $this->setToObject('歉意金赔偿','歉意金赔偿',$cyjMoney,'member');
                //客户买车担保金利息赔偿(返还客户可用余额+客户买车担保金利息赔偿)
                $orderFree = $this->getOrderFree($order_id);
                $items    = is_null($orderFree) ? ['SFJXB-0003'] : ['SFJXB-0003','KHJXB-0010','SFJXB-0004'];
                $moneys   = is_null($orderFree) ? [$cyjMoney] : [$cyjMoney,$orderFree,$orderFree];
                $users    = is_null($orderFree) ? ['seller'] : ['seller','member','seller'];
                $subjects = is_null($orderFree) ? [10] : [10,20,10];
                $toResult = ['items'=>$items, 'moneys'=>$moneys, 'users'=>$users, 'subject'=> $subjects, 'toObject'=>[$toObject]];
                return $this->removeFromDataToData($orderInfo,$toResult);
                break;
            case 1://已退还可提现余额
                $item = (object) ['name'=>'已退还可提现余额','description'=>'已退还可提现余额','from'=>''];
                return $this->addAccount($orderInfo, $cyjMoney , $item, 20, 'seller');
                break;
            default:
                $this->setErrorMsg('不存在该解冻操作！','SUBJECT-000'.$subject);
                return false;
        }
    }

    /**
     * 解冻数据并发送其他数据
     * @param $orderInfo
     * @param null $toResult
     * @return bool|void
     */
    private function removeFromDataToData($orderInfo,$toResult=null)
    {
        if(is_null($toResult)) return ;
        $moneys   = $toResult['moneys'];
        $item     = $toResult['items'];
        $role     = $toResult['users'];
        $subjects = $toResult['subject'];
        $toObject = $toResult['toObject'];
        foreach($moneys as $k => $m){
            if($m >0){
                $itemInfo = $this->getItems($item[$k]);
                $res = $this->insertJxbData($orderInfo,$itemInfo,$m,$role[$k],$subjects[$k]);
                if($res){
                    if(!is_null($toObject)){
                        if(!is_null($toObject[$k])) {
                            $to = (object)$toObject[$k];
                            $this->addAccount($orderInfo, $to->money, $to->item, $to->type, $to->role);
                        }
                    }
                }
            }
        }
        return true;
    }

    public function addAccount($order,$money,$item,$type=10,$userType='member')
    {
        switch($userType){
            case 'member':
                $logData = $this->getUserLog($order,$money,$item,$type);
                $userModel = $this->userAccount;
                $logModel = $this->userAccountLog;
                $user_id  = $order->user_id;
                break;
            case 'seller':
                $logData = $this->getDailiLog($order,$money,$item,$type);
                $userModel = $this->dailiAccount;
                $logModel = $this->dailiAccountLog;
                $user_id  = $order->seller_id;
                break;
            case 'system':
                $logData = $this->getSystemLog($order,$money,$item);
                $logModel = $this->accountLog;
                break;
        }
        //为保证，开启事务
        DB::beginTransaction();
        try{
            $logModel ->addLog($logData);
            if(in_array($userType,['member','seller'])){
                if($type == 10){//冻结用户余额，等待审核通过后。更新用户余额。
                    $userModel ->UpdateFreezeDeposit($user_id,$money);
                }
                if($type == 20){//解冻用户加信宝
                    $userModel ->RemoveFreezeDeposit($user_id,$money);
                }
            }
            DB::commit();
            return true;
        }catch (Exception $e){
            //否则回顾
            DB::rollBack();
            $this->setErrorMsg($e->getMessage(),'DB-0002');
            return false;
        }
    }

    public function getUserLog($order,$money,$item,$type=10)
    {
        $user = $this->userAccount->getById($order->user_id);
        //1.记录账号日志
        $logData = [
            'user_id'          => $order->user_id,
            'item_id'          => 0,
            'item'             => $item->name,
            'remark'           => $item->description,
            'money'            => $money,
            'credit_avaliable' => $user->avaliable_deposit,
            'type'             => 5,
            'pay_type'         => 3,
            'order_id'         => $order->id,
            'status'           => 0,
            'wichdraw_end_at'  => '',
            'money_type'       => ($type==10) ? '-' : '+',
            'is_freeze'        => 0,
            'freeze_remark'    => ''
        ];
        return $logData;
    }

    public function getDailiLog($order,$money,$item,$type=10)
    {
        $seller = $this->dailiAccount->getById($order->seller_id);
        $logData = [
            'd_id'             => $order->seller_id,
            'money'            => $money,
            'item_id'          => 0,
            'item'             => $item->name,
            'remark'           => $item->description,
            'credit_avaiable'  => $seller->avaliable_deposit,
            'type'             => 0,
            'pay_type'         => 3,
            'order_id'         => $order->id,
            'freeze_status'    => 0,
            'freeze_time'      => '',
            'money_type'       => ($type==10) ? '-' : '+',
            'status'           => 0
        ];
        return $logData;
    }

    public function getSystemLog($order,$money,$item)
    {
        //插入交易日志
        $logData = [
            'from_user_id'          => ($item->from == 'member') ? $order->user_id : $order->seller_id,
            'from_where'            => ($item->from == 'member') ? 1 : 2,//'1,客户 2,售方 3,平台',
            'from_remark'           => '',//'支出方说明',
            'to_user_id'            => 0,//'收入方id',
            'to_where'              => 3,//'1.客户，2. 售方 3.平台',
            'to_remark'             => '',//'收入方说明',
            'trade_no'              => '',//'流水号',
            'remark'                => $item->name,//'说明',
            'money'                 => $money,//'金额',
            'type'                  => 0,
            'method_type'           => 0,//'流水类型、10客户充值 11售方充值 、20k客户提现 21 售方提现、30购买、40退款'
            'related_id'            => 0,//'对应表的id  对应表的id 结合method_type来做',
            'order_id'              => $order->id,//'购车订单号',
            'special_application_id'=> 0,//'特事审批id (跟type配合使用)',
            'flow_type'             => 1,//'1收入，2 （该字段表示该资金流向是收入还是成本）',
            'status'                => 0//'状态',
        ];
        return $logData;
    }
    
    /**
     * @param $order_id
     * @return mixed
     */
    public function getOrderInfo($order_id){
        return HgOrder::where(['id'=>$order_id])->first();
        //return \App\Models\HgOrder::findOrFail($order_id)->first();
    }
    /**
     * @param $money
     * @return mixed 计算利息
     */
    private function setDbjFree($money)
    {
        return $money * ( 2 / 10000);
    }

    /**
     * 获取买车担保金利息
     * @param $order_id
     * @return mixed
     */
    private function getOrderFree($order_id,$item_id=16)
    {
        return HcOrderJiaXinBao::where(['order_id'=>$order_id])->where(['item_id'=>$item_id])->sum('money');
    }
    /** 获取裁判赔偿金额
     * @param $order_id
     * @return null
     */
    private function getReferee($order_id)
    {
        $where = [
            ['order_id','=',$order_id],
            ['status','=',3]
        ];
        $ocId = DB::table('hc_order_conciliation')->where($where)->orderBy('id','desc')->value('id');
        $orderInfo = $this->getOrderInfo($order_id);
        if(!is_null($ocId)){
            $result = DB::table('hc_order_conciliation_arbitrate')
                ->where(['ocid'=>$ocId])
                ->where('status','<>',2)->first();
            switch($result->arbitrate_result){
                case 3:
                    $items    = ['KHJXB-0001','KHJXB-0007'];
                    $moneys   = [$orderInfo->earnest_price,'299'];
                    $users    = ['member','seller'];
                    $subjects = [10,20];
                    $toObject = [null,$this->setToObject('华车服务费','转付转付华车服务费',$orderInfo->earnest_price-299,'system','member')];
                    break;
                case 2:
                case 1:
                    $items  = ['KHJXB-0008','KHJXB-0008','KHJXB-0010','KHJXB-0012','SFJXB-0003','SFJXB-0004','SFJXB-0005'];
                    $moneys = [
                        $result->seller_deposit_from_userjxb,
                        $result->hwache_deposit_from_userjxb,
                        $result->return_user_available_deposit_from_userjxb,
                        $result->transfer_hwache_service_charge_from_userjxb,
                        $result->apology_money_from_sellerjxb,
                        $result->user_deposit_interest_from_sellerjxb,
                        $result->return_user_avaiable_from_sellerjxb
                    ];
                    $users  = ['member','member','member','member','seller','seller','seller'];
                    $subjects = [10,10,10,10,10,10,10];
                    $toObject = [
                        $this->setToObject('赔偿售方','买车担保金赔偿',$result->seller_deposit_from_userjxb,'seller'),
                        $this->setToObject('赔偿平台','买车担保金赔偿',$result->hwache_deposit_from_userjxb,'system','member'),
                        $this->setToObject('退回客户','买车担保金赔偿',$result->return_user_available_deposit_from_userjxb,'member'),
                        $this->setToObject('华车服务费','转付转付华车服务费',$result->transfer_hwache_service_charge_from_userjxb,'system','member'),
                        $this->setToObject('赔偿客户','歉意金N赔偿',$result->apology_money_from_sellerjxb,'member'),
                        $this->setToObject('赔偿客户','客户买车担保金利息N赔偿',$result->user_deposit_interest_from_sellerjxb,'member'),
                        $this->setToObject('退回售方','退还可提现余额',$result->return_user_avaiable_from_sellerjxb,'seller')
                    ];
                    break;
            }
           return ['items'=>$items,'moneys'=>$moneys,'users'=>$users,'subject'=> $subjects,'toObject'=>$toObject];
        }
        return null;
    }

    /** 设置 访问对象数据
     * @param $name
     * @param $remark
     * @param $money
     * @param $role
     * @param int $type
     * @return array
     */
    private function setToObject($name,$remark,$money,$role,$from='',$type=20)
    {
       return [
            'item'  => (object) ['name'=>$name,'description'=>$remark,'from'=>$from],
            'money' => $money,
            'role'  => $role,
            'type'  => $type
        ];
    }
    /**
     * 更新订单加信宝金额
     * @param $order_id
     * @param $money
     * @param int $action 冻结，解冻
     */
    private function setOrderJxb($order_id,$money,$action=10,$userType='member')
    {
        $field = ($userType =='member') ? 'user_freeze_jxb' : 'seller_freeze_jxb';
        if($action ==10){
            $res = HgOrder::findOrFail($order_id)->increment($field,$money);
        }else{
            $res = HgOrder::findOrFail($order_id)->decrement($field,$money);
        }
        return $res;
    }
    /** 获取华车服务费
     * @param $order
     */
    private function getHcPrice($order)
    {
        //获取用户余额
        $user = $this->userAccount ->getById($order->user_id);
        $hcPrice = DB::table('hc_price')->where(['id'=>$order->bj_id])->value('hwache_service_price');
        if($user->avaliable_deposit >= (int) $hcPrice){
            return $hcPrice;
        }
        $this->setErrorMsg('用户可用余额透支或不足！','USER-0001');
    }

    /** 添加加信宝记录
     * @param $order_id
     * @param $item
     * @param $user_id
     * @param $money
     * @param string $desc
     * @param int $type
     * @return mixed
     */
    private function insertJxbTable($order_id,$item,$user_id,$money,$type=10,$voucher=null)
    {
        $userRole = ($this->role =='member') ? 1 : 2;
        $data = [
            'order_id'   => $order_id,
            'voucher_id' => !is_null($voucher) ? $voucher->id : 0,
            'item_id'    => $item->id,
            'item'       => $item->name,
            'type'       => $type,
            'user_id'    => $user_id,
            'role'       => $userRole,//用户、售方
            'money'      => $money,
            'voucher_money' => !is_null($voucher) ? $voucher->money : 0,
            'freeze_avaiable'=> $this->getUserJxbTotal($user_id,$userRole),
            'is_del'     => 0,
            'description'=> $item->description,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
        ];
        return DB::table('hc_order_jiaxinbao_detail')->insert($data);
    }

    /** 添加加信宝记录
     * @param $orderInfo
     * @param $itemInfo
     * @param $money
     * @return bool
     */
    private function insertJxbData($orderInfo,$itemInfo,$money,$userType,$type=10,$voucher=null)
    {
        DB::beginTransaction();
        try{
            if($userType =='member') {
                $user_id   = $orderInfo->user_id;
                $userModel = $this->userAccount;
            }else {
                $user_id   = $orderInfo->seller_id;
                $userModel = $this->dailiAccount;
            }
            //添加加信宝数据
            $this->insertJxbTable($orderInfo->id,$itemInfo,$user_id,$money,$type,$voucher);

            DB::commit();
            if($type == 10){//冻结用户余额，等待审核通过后。更新用户余额。
                $userModel ->UpdateFreezeDeposit($user_id,$money);
            }
            if($type == 20){//解冻用户加信宝
                $userModel ->RemoveFreezeDeposit($user_id,$money);
            }
            //更新订单加信宝
            $this->setOrderJxb($orderInfo->id,$money,$type,$userType);

            return true;
        }catch (\Exception $e){
            $this->setErrorMsg($e->getMessage(),'DB-0001');
            DB::rollBack();
            return false;
        }
    }

    /**
     * 更新用户资金账号数据
     * @param $role    角色(1客户，2售方)
     * @param $user_id 用户id
     * @param $update  更新数据 test['avaliable_deposit'=>'avaliable_deposit+100','freeze_deposit'=>'freeze_deposit-100']
     */
    private function updateFreezeData($user_id,array $update,$role=1)
    {
        $table = [1=>'hc_user_account',2=>'hc_daili_account'][$role];
        $where = [1=>['user_id'=>$user_id],2=>['d_id'=>$user_id]];
        $isAccount = DB::table($table)->where($where)->count();
        if($isAccount){
            $save = [];
            foreach($update as $key => $val){
                $save[$key] = DB::raw("{$val}");
            }
            $save['updated_at'] =  Carbon::now()->toDateTimeString();
            return DB::table($table)->where($where)->update($save);
        }
        return false;
    }

    public function voucherLogFind($order_id)
    {

    }

    /**
     * @param $user_id   用户id
     * @param int $role  用户角色（1客户、2售方、3平台）
     */
    public static function getUserJxbTotal($user_id,$role=1)
    {
        $model = HcOrderJiaXinBao::where(['user_id'=>$user_id,'role'=>$role]);
        if($model->first()){
            return $model->where('type',10)->sum('money') - $model->where('type',20)->sum('money');
        }
        return 0;
    }

    public function __destruct()
    {
        if($this->errorMsg){
            return response()->json($this->errorMsg);
        }
    }
}