<?php
/**
 * 用户资金管理
 */

namespace App\Http\Requests;

use App\Models\HcDailiAccountLog;
//use App\Models\HcDailiWithdrawBank;
use Illuminate\Foundation\Http\FormRequest;

use App\Repositories\Contracts\HcDailiAccountRepositoryInterface;
use App\Repositories\Contracts\HcDailiAccountLogRepositoryInterface;
use App\Repositories\Contracts\HcDailiRechargeBankRepositoryInterface;
use App\Repositories\Contracts\HcDailiWithdrawBankRepositoryInterface;
use App\Models\HgUser;
use App\Com\Hwache\Jiaxinbao\Account as JXB;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Carbon\Carbon;

class DealerPricesRequest extends FormRequest
{
    protected $dailiAccount, $dailiAccountLog, $dailiRecharge, $dailiWithdraw;

    public function __construct(
        HcDailiAccountRepositoryInterface $hcDailiAccountRepository,
        HcDailiAccountLogRepositoryInterface $hcDailiAccountLogRepository,
        HcDailiRechargeBankRepositoryInterface $hcDailiRechargeBankRepository,
        HcDailiWithdrawBankRepositoryInterface $hcDailiWithdrawBankRepository
    )
    {
        $this->dailiAccount = $hcDailiAccountRepository;
        $this->dailiAccountLog = $hcDailiAccountLogRepository;
        $this->dailiRecharge = $hcDailiRechargeBankRepository;
        $this->dailiWithdraw = $hcDailiWithdrawBankRepository;
    }

    /**
     * 获取代理商登录id
     * @return mixed
     */
    public function getLoginId()
    {
        return $this->session()->get('user.member_id');
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * 代理商充值
     * @param $money
     * @param string $remark
     * @param string $voucher
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function addRecharge($money, $voucher = '', $remark = '待入账')
    {
        $seller_id = $this->getLoginId();
        $sellerBank = $this->getSellerBankToName();
        if (!$sellerBank || empty($sellerBank->seller_bank_account)) {
            throw new \Exception('代理商银行账号不存在');
        }
        $data = [
            'd_id' => $this->getLoginId(),
            'bank_name' => $sellerBank->seller_bank_addr,//'开户行',
            'bank_account' => $sellerBank->seller_bank_account, // '银行帐号',
            'daili_bank_name' => $sellerBank->sellerName,// '银行账户名'
            'money' => $money,//'金额',
            'remark' => $remark,// '说明',
            'voucher' => $voucher, //'转账凭证',
            'kefu_confirm_money' => 0, // '客服确认金额',
            'kefu_confirm_status' => 0, // '客服审核状态，1成功，2失败',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
        ];
        $sellerMoney = $this->getUserAccount($seller_id);
        //开启事务
        DB::beginTransaction();
        try {
            $drb_id = DB::table('hc_daili_recharge_bank')->insertGetId($data);
            if (!$drb_id) {
                throw new \Exception('充值失败！');
            }
            $logData = [
                'd_id' => $seller_id,
                'money' => $money,
                'item_id' => $drb_id,
                'item' => '充值',
                'remark' => $remark,
                'credit_avaiable' => $sellerMoney,
                'type' => 1,
                'pay_type' => 2,
                'order_id' => 0,
                'freeze_status' => 0,
                'freeze_time' => '',
                'money_type' => '+',
                'status' => 0,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ];
            $isLog = DB::table('hc_daili_account_log')->insert($logData);
            if (!$isLog) {
                throw new \Exception('交易日志添加失败！');
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * 保存提现申请
     * @param $money
     * @param $fee
     * @return bool
     * @throws \Exception
     */
    public function saveWithdrawalApplication($money, $fee, $remark = '正在办理')
    {
        //查询用户可以提现余额
        $account = DB::table('hc_daili_account')->where(['d_id' => $this->getLoginId()])->first();
        if (!$account || $account->avaliable_deposit <= 0) {
            return false;
        }
        $sellerBank = $this->getSellerBankToName();
        $seller_id = $this->getLoginId();
        //开启事务
        DB::beginTransaction();
        try {
            //插入提现记录
            $data = [
                'd_id' => $seller_id,
                'bank_name' => $sellerBank->seller_bank_addr,
                'bank_account' => $sellerBank->seller_bank_account,
                'daili_bank_name' => $sellerBank->sellerName,
                'money' => $money,
                'fee' => $fee,
                'remark' => $remark,
                'kefu_confirm_money' => 0,
                'kefu_confirm_status' => 0,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ];
            $dwb_id = DB::table('hc_daili_withdraw_bank')->insertGetId($data);
            //判断失败
            if (!$dwb_id) {
                throw new \Exception('提现申请失败！');
            }
            $sellerMoney = $this->getUserAccount($seller_id);
            $isLog = DB::table('hc_daili_account_log')->insert([
                'd_id' => $seller_id,
                'money' => $money,
                'item_id' => $dwb_id,
                'item' => '提现',
                'remark' => $remark,
                'credit_avaiable' => ($sellerMoney - $money),
                'type' => 2,
                'pay_type' => 2,
                'freeze_status' => 0,
                'money_type' => '-',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ]);
            if (!$isLog) {
                throw new \Exception('交易日志添加失败！');
            }
            //插入资金账号表
            DB::table('hc_account_log')->insert([
                'from_user_id'          => $seller_id,//'支出方用户id  结合对应where使用',
                'from_where'            => 2,//'1,客户 2,售方 3,平台',
                'from_remark'           => '提现',
                'to_user_id'            => 0,//'收入方id',
                'to_where'              => 4,//'1.客户，2. 售方 3.平台,4.外部',
                'to_remark'             => '无',//'收入方说明',
                'remark'                => "售方的账号提现申请:￥".$money,//'说明',
                'money'                 => $money,//'金额',
                'type'                  => 21,//'流水类型、10转入客户 11转入售方、20客户转出，21售方转出、30客户解冻，31售方解冻、40客户冻结，41售方冻结',
                'method_type'           => 21,//'流水类型、10客户充值 11售方充值 、20k客户提现 21 售方提现、30购买、40退款'
                'related_id'            => $dwb_id,//'对应表的id  对应表的id 结合method_type来做',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ]);
            //冻结用户提现金额
            $isAccount = $this->WithdrawalFreeze($seller_id, $money);
            if (!$isAccount) {
                throw new \Exception('代理商资金修改失败！');
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function getTotalList()
    {
        //"seller_bank_city_str" ,"seller_bank_account" ,"seller_bank_addr"
        $seller_id = $this->getLoginId();
        return HgUser::findOrFail($seller_id)->MemberSeller()->first();
    }

    /**
     * 获取代理商银行卡信息
     * @return mixed
     */
    public function getSellerBank()
    {
        //"seller_bank_city_str" ,"seller_bank_account" ,"seller_bank_addr"
        $seller_id = $this->getLoginId();
        return HgUser::findOrFail($seller_id)->MemberSeller()->first();
    }

    /**
     * 获取用户的可用金额
     * @param $seller_id
     * @return int
     */
    public function getUserAccount($seller_id = 0)
    {
        $accountFind = $this->getSellerTotal();
        return (int)$accountFind->avaliable_deposit;
    }

    /**
     * 获取最新的充值凭证
     * @return mixed
     */
    public function getSellerRechargeSuccess()
    {
        return HgUser::findOrFail($this->getLoginId())->DealerRechargeBank()->orderBy('drb_id', 'desc')->first();
    }

    /**
     * 充值列表
     * @param int $pagsize
     * @return array
     */
    public function getSellerRechargeList($pagsize = 10)
    {
        $pageData = [];
        $start_date = $this->get('start_date', '');
        $end_date = $this->get('end_date', '');

        $start_create = $this->get('start_create', '');
        $end_create = $this->get('end_create', '');

        $status = $this->get('status', '');
        $where = "car_c.d_id =" . $this->getLoginId();
        if (!empty($start_date) && !empty($end_date)) {
            $startDate = str_replace(['年', '月', '日'], ['-', '-', ''], $start_date);
            $endDate = str_replace(['年', '月', '日'], ['-', '-', ''], $end_date) . ' 23:59:59';
            $where .= " and (car_c.created_at between '{$startDate}' and '{$endDate}')";
            $search['start_date'] = $pageData['start_date'] = $start_date;
            $search['end_date'] = $pageData['end_date'] = $end_date;
        } else {
            $search['start_date'] = Carbon::now()->subMonth()->format('Y年m月d日');
            $search['end_date'] = Carbon::now()->format('Y年m月d日');
        }
        if (!is_null($status) && $status != '') {
            $pageData['status'] = $status;
        }
        if (!empty($start_create) && !empty($end_create)) {
            $startCreate = str_replace(['年', '月', '日'], ['-', '-', ''], $start_create);
            $endCreate = str_replace(['年', '月', '日'], ['-', '-', ''], $end_create) . ' 23:59:59';
            $where .= " and (car_c.updated_at between '{$startCreate}' and '{$endCreate}')";
            $search['start_create'] = $pageData['start_create'] = $start_create;
            $search['end_create'] = $pageData['end_create'] = $end_create;
        } else {
            $search['start_create'] = Carbon::now()->subMonth()->format('Y年m月d日');
            $search['end_create'] = Carbon::now()->format('Y年m月d日');
        }
        if ($status != '' && $status != 1) {
            $sqlStatusArr = [2 => 0, 3 => 1, 4 => 4];
            $sqlStatus = $sqlStatusArr[$status];
            $where .= " and car_dal.status = '{$sqlStatus}'";
        }
        $search['status'] = $this->setStatus($status);
        $list = DB::table('hc_daili_recharge_bank as c')
            ->select('c.*', 'dal.status')
            ->leftJoin('hc_daili_account_log as dal', function ($join) {
                $join->on('c.drb_id', '=', 'dal.item_id')->where('dal.type', 1);
            })
            ->whereRaw($where)
            ->orderBy('c.drb_id', 'desc')
            ->paginate($pagsize);
        return ['pages' => $list, 'search' => $search, 'pageData' => $pageData];
    }

    /**
     * 提现列表
     * @param int $pagsize
     * @return array
     */
    public function getSellerWithdrawalList($pagsize = 10)
    {
        $pageData = [];
        $start_date = $this->get('start_date', '');
        $end_date = $this->get('end_date', '');

        $status = $this->get('status', '');
        $where = "car_dal.item_id > 0 and car_c.d_id =" . $this->getLoginId();
        if (!empty($start_date) && !empty($end_date)) {
            $startDate = str_replace(['年', '月', '日'], ['-', '-', ''], $start_date);
            $endDate = str_replace(['年', '月', '日'], ['-', '-', ''], $end_date) . ' 23:59:59';
            $where .= " and (car_c.created_at between '{$startDate}' and '{$endDate}')";
            $search['start_date'] = $pageData['start_date'] = $start_date;
            $search['end_date'] = $pageData['end_date'] = $end_date;
        } else {
            $search['start_date'] = Carbon::now()->subMonth()->format('Y年m月d日');
            $search['end_date'] = Carbon::now()->format('Y年m月d日');
        }
        if ($status != '' && $status != 1 && !is_null($status)) {
            $sqlStatusArr = [2 => '0,3,4', 3 => '1,2', 4 => '5'];
            $sqlStatus = $sqlStatusArr[$status];
            $where .= " and car_c.kefu_confirm_status in ({$sqlStatus})";
            $pageData['status'] = $status;
        }
        $search['status'] = $this->setStatus($status, 1);
        $list = DB::table('hc_daili_withdraw_bank as c')
            ->select('c.*', 'dal.status', 'dal.da_log_id')
            ->leftJoin('hc_daili_account_log as dal', function ($join) {
                $join->on('c.dwb_id', '=', 'dal.item_id')
                    ->on('dal.d_id', '=', 'c.d_id')
                    ->where('dal.type', 2);
            })
            ->whereRaw($where)
            ->orderBy('c.dwb_id', 'desc')
            ->groupBy('c.dwb_id')
            ->paginate($pagsize);
        return ['pages' => $list, 'search' => $search, 'pageData' => $pageData];
    }

    /**
     * 交易日志列表
     * @param int $pagsize
     * @return array
     */
    public function getSellerLogList($pagsize = 10)
    {
        $pageData = [];
        $start_date = $this->get('start_date', '');
        $end_date = $this->get('end_date', '');

        $search['item'] = $item = $this->get('item', '');
        $search['remark'] = $remark = $this->get('remark', '');

        $where = [['log.d_id', '=', $this->getLoginId()]];
        $yearDate = Carbon::now()->subYear()->toDateTimeString();
        //$thisDay  = Carbon::now()->toDateTimeString();

        if (!empty($start_date) && !empty($end_date)) {
            $pageData['start_date'] = $start_date;
            $pageData['end_date'] = $end_date;
            $startDate = str_replace(['年', '月', '日'], ['-', '-', ''], $start_date);
            $endDate = str_replace(['年', '月', '日'], ['-', '-', ''], $end_date);
            $search['start_date'] = $startDate;
            $search['end_date'] = $endDate;
        } else {
            $startDate = $endDate = $search['start_date'] = $search['end_date'] = Carbon::now()->toDateString();
        }
        $searchDate[0] = Carbon::parse($endDate)->toDateString();
        $searchDate[1] = Carbon::parse($endDate)->subMonth()->toDateString();
        $searchDate[2] = Carbon::parse($endDate)->subMonth(3)->toDateString();
        $searchDate[3] = Carbon::parse($endDate)->subYear()->toDateString();
        $search['thisDate'] = array_search($startDate, $searchDate);
        if ($remark != '') {
            array_push($where, ['log.remark', 'like', '%' . $remark . '%']);
            $pageData['remark'] = $remark;
        }
        if ($item != '') {
            array_push($where, ['log.remark', 'like', '%' . $item . '%']);
            $pageData['item'] = $item;
        }
        $list = DB::table('hc_daili_account_log as log')
            ->where($where)
            ->whereRaw("car_log.item <>''")
            ->where('log.type', '=', 2)
            ->orWhere(function ($query) {
                $query->where(['log.status' => 1, 'log.type' => 1]);
            })
            ->whereBetween('log.created_at', [$startDate, $endDate . " 23:59:59"])
            ->select('log.*')
            ->orderBy('log.da_log_id', 'desc')
            ->paginate($pagsize);
        $gtYear = ['dateTime' => Carbon::now()->subYear()->format('Y-m-') . '01 00:00:01'];
        //超过一年的记录
        $gtYearMoney = HgUser::findOrFail($this->getLoginId())->DealerAccountLog()->whereRaw("created_at < '{$yearDate}'")->sum('money');
        $gtYear['money'] = $gtYearMoney;
        return ['pages' => $list, 'search' => $search, 'gtYear' => $gtYear, 'pageData' => $pageData];
    }

    public function checkStatus($log_id)
    {
        $accountLog = HgUser::findOrFail($this->getLoginId())->DealerAccountLog()->where('da_log_id', $log_id)->first();
        if($accountLog){
            //修改提现状态
            $withdraw = DB::table('hc_daili_withdraw_bank')->where(['dwb_id' => $accountLog->item_id])->first();
            return ($withdraw && in_array($withdraw->kefu_confirm_status,[0,3]));
        }
        return false;
    }

    /**
     * 终止提现
     * @param $log_id
     * @param int $status
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateLogStatus($log_id, $status = 4)
    {
        DB::beginTransaction();
        $account = DB::table('hc_daili_account')->where(['d_id' => $this->getLoginId()])->lockForUpdate()->first();
        $accountLog = HgUser::findOrFail($this->getLoginId())->DealerAccountLog()->where('da_log_id', $log_id)->first();
        try {
            //修改日志状态
            DB::table('hc_daili_account_log')->where(['da_log_id' => $log_id])->update(
                [
                    'remark' => '未成功',
                    'status' => $status,
                    'updated_at' => Carbon::now()->toDateTimeString()
                ]);
            //修改提现状态
            DB::table('hc_daili_withdraw_bank')->where(['dwb_id' => $accountLog->item_id])
                ->update(['kefu_confirm_status' => 5, 'reject_status' => 52, 'updated_at' => Carbon::now()->toDateTimeString()]);
            //返还用户余额
            DB::table('hc_daili_account')->where(['d_id' => $this->getLoginId()])
                ->increment('total_deposit', $accountLog->money,
                    [
                        'avaliable_deposit' => DB::raw('avaliable_deposit+' . $accountLog->money),
                        //'temp_deposit' => DB::raw('temp_deposit-' . $accountLog->money),
                        'updated_at' => Carbon::now()
                    ]
                );
            //生成返还日志
            DB::table('hc_daili_account_log')->insert([
                'd_id' => $this->getLoginId(),
                'money' => $accountLog->money,
                'item_id' => $accountLog->item_id,
                'item' => '重新入账',
                'remark' => "提现不成功（{$accountLog->item_id}）",
                'credit_avaiable' => $account->avaliable_deposit + $accountLog->money,
                'type' => $accountLog['type'],
                'pay_type' => $accountLog['pay_type'],
                'order_id' => $accountLog['order_id'],
                'freeze_status' => $accountLog['freeze_status'],
                'freeze_time' => $accountLog['freeze_time'],
                'money_type' => '+',
                'status' => 1,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ]);
            //资金总表录入日志
            DB::table('hc_account_log')->insert([
                'from_user_id'          => $this->getLoginId(),//'支出方用户id  结合对应where使用',
                'from_where'            => 4,//'1,客户 2,售方 3,平台',
                'from_remark'           => '无',
                'to_user_id'            => $this->getLoginId(),//'收入方id'
                'to_where'              => 2,//'1.客户，2. 售方 3.平台,4.外部',
                'to_remark'             => '重新入账',//'收入方说明',
                'trade_no'              => '',//'流水号',
                'remark'                => "售方的账号,提现重新入账:￥".$accountLog->money,//'说明',
                'money'                 => $accountLog->money,//'金额',
                'type'                  => 21,//'流水类型、10转入客户 11转入售方、20客户转出，21售方转出、30客户解冻，31售方解冻、40客户冻结，41售方冻结',
                'method_type'           => 21,//'流水类型、10客户充值 11售方充值 、20k客户提现 21 售方提现、30购买、40退款'
                'related_id'            => $accountLog->item_id,//'对应表的id  对应表的id 结合method_type来做',
                'order_id'              => $accountLog->order_id,//'购车订单号',
                'special_application_id'=> 0,//'特事审批id (跟type配合使用)',
                'flow_type'             => 1,//'1收入，2成本 （该字段表示该资金流向是收入还是成本）',
                'status'                => 1,//'状态',
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString()
            ]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    /**
     * 查看代理商资金账号
     */
    public function getSellerTotal()
    {
        //判断代理商账户是否存在
        $isAccount = $this->isSellerAccount();
        $result = $isAccount ? HgUser::findOrFail($this->getLoginId())->DealerAccount()->first() : false;
        //$result = $isAccount ? $this->dailiAccount->getById($this->getLoginId())->first() : false;
        $account = ['avaliable_deposit' => 0,
            'total_deposit' => 0,
            'basic_deposit' => 0,
            'credit_line' => 0,
            'freeze_deposit' => 0,
            'temp_deposit' => 0,
            'jxbTotal' => 0
        ];
        if ($result == false) {
            return (object)$account;
        } else {
            if ($result->status == 2) {//账号禁用
                return (object)$account;
            } else {
                //售方加信包总额
                $result['jxbTotal'] = JXB::getUserJxbTotal($this->getLoginId(), 2);
                return $result;
            }
        }
    }

    /**
     * 查询代理商冻结总金额(加信宝)
     */
    public function getFreezeTotal()
    {
        return JXB::getUserJxbTotal($this->getLoginId(), 2);
    }

    /**
     * 加信宝
     * @param int $pageSize
     * @return mixed
     */
    public function getFreezeList($pageSize = 10)
    {
        $pageData = [];
        $sn = $search['sn'] = $this->get('sn', '');
        $status = $this->get('status', '');
        $where = "car_log.`is_del` =0 and car_log.role=2 and car_log.`user_id` =" . $this->getLoginId();
        if ($status != -1 && in_array($status, ['1', '2'])) {
            $statusArr = [2 => '>0', 1 => '=0'];
            $where .= " and car_log.`money` " . $statusArr[$status];
        } else {
            $status = '-1';
        }
        if (!is_null($status) && $status != '') {
            $pageData['status'] = $status;
        }
        if ($sn != '') {
            $where .= " and car_o.`order_sn` like '%{$sn}%'";
            $pageData['sn'] = $sn;
        }
        $searchStatus = ['-1' => '全部', 2 => '>0', 1 => '=0'];
        $search['status'] = $searchStatus[$status];
        $list = DB::table('hc_order_jiaxinbao_detail as log')
            ->select('o.order_sn', 'o.created_at', 'log.money', 'log.order_id')
            ->leftJoin('hc_order as o', 'log.order_id', 'o.id')
            ->whereRaw($where)
            ->groupBy('log.order_id')
            ->paginate($pageSize);
        return ['list' => $list, 'search' => $search, 'pageData' => $pageData];
    }

    /**
     * 查看加信宝冻结金额
     * @param  [type] $order_id [description]
     * @return [type]           [description]
     */
    public function getFreezeOrderMoney($order_id)
    {
        return DB::table('hc_order_jiaxinbao_detail')
            ->where(['order_id' => $order_id, 'user_id' => $this->getLoginId()])
            ->where(['is_del' => 0, 'role' => 2])
            ->sum('money');
    }

    /** 冻结详情
     * @param $order_id
     * @return mixed
     */
    public function getFreezeDetail($order_id)
    {
        $col = "if(car_log.type=10,1,0) as isNegative,if(car_log.type=10,'冻结','解冻') as status,car_log.money as price,car_log.item as project,car_log.updated_at as info";
        return DB::table('hc_order_jiaxinbao_detail as log')
            ->select(DB::raw($col))
            ->leftJoin('hc_order as o', 'log.order_id', 'o.id')
            ->where(['log.order_id' => $order_id, 'log.user_id' => $this->getLoginId()])
            ->where(['log.role' => 2, 'log.is_del' => 0])
            ->get()
            ->toArray();
    }

    /**
     * 判断代理商资金数据是否有记录
     * @return mixed
     */
    public function isSellerAccount()
    {
        return $this->dailiAccount->count(['d_id' => $this->getLoginId()]);
    }

    /**
     * 查询代理商最新的一条透支记录
     * @return mixed
     */
    public function getSellerOverdraftLog($type = 0)
    {
        return HgUser::findOrFail($this->getLoginId())->DailiOverdraftLog()
            ->orderBy('do_log_id', 'desc')
            ->where('type', $type)
            ->first();
    }

    /**
     * @return mixed 查询当月申请提现次数
     */
    public function getWithdrawalCount()
    {
        $startDate = Carbon::now()->subMonth()->toDateTimeString();
        $endDate = Carbon::now()->toDateString() . ' 23:59:59';
        $where = 'type = 2 and d_id = ' . $this->getLoginId();
        $where .= " and (created_at between '{$startDate}' and '{$endDate}')";
        return HcDailiAccountLog::whereRaw($where)->count();
    }

    /**
     * @return mixed (代理商及银行信息)
     */
    public function getSellerBankToName()
    {
        $seller_id = $this->getLoginId();
        $sellerName = HgUser::where(['member_id' => $seller_id])->firstOrFail();
        $sellerBank = $this->getSellerBank();
        $sellerBank->sellerName = $sellerName ? $sellerName->member_truename : '';
        return $sellerBank;
    }

    /**
     * 格式化 状态
     * @param null $val
     * @return mixed
     */
    private function setStatus($val = null, $type = 0)
    {
        $_val = is_null($val) || empty($val) ? 1 : $val;
        $arr = [
            [1 => "全部", 2 => "正在核实", 3 => "已入账", 4 => "无此款项"],
            [1 => "全部", 2 => "正在办理", 3 => "已完成", 4 => "未完成"]
        ];
        return $arr[$type][$_val];
    }

    /**
     * 提现临时冻结金额
     * @param $user_id
     * @param $money
     */
    public function WithdrawalFreeze($user_id,$money)
    {
        return DB::table('hc_daili_account')->where('d_id',$user_id)->decrement('avaliable_deposit', $money ,
            [
                'total_deposit' => DB::raw('total_deposit-'.$money),
                //'temp_deposit'  => DB::raw('temp_deposit+'.$money),
                'updated_at'    => Carbon::now()->toDateTimeString()
            ]
        );
    }
}
