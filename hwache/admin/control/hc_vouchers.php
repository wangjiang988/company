<?php
/**
 * 代金券
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/6
 * Time: 17:18
 */
defined('InHG') or exit('Access Invalid!');
require_once dirname(__FILE__).'/../vendor/vouchers.php';
class hc_vouchersControl extends SystemControl
{
    protected $vouchers;

    CONST ACCOUNT_RECHARGE_TABLE = 'hc_daili_recharge_bank';
    CONST ACCOUNT_WITHDRAW_TABLE = 'hc_daili_withdraw_bank';

    CONST ACCOUNT_RECHARGE_TYPE = 22;
    CONST ACCOUNT_WITHDRAW_TYPE = 23;
    CONST APPLICATION_TYPE = 20;

    public function __construct()
    {
        parent::__construct();
        $this->vouchers = Model('hc_vouchers');
        Tpl::output('get',$_GET);
    }

    public function indexOp($export=false){
        $result = $this->vouchers ->getVouchersList($_GET);
        if($export==true){
            return $result['list'];exit;
        }
        Tpl::output('brands',$this->brandOp());
        Tpl::output('series',$this->brandOp($_GET['brand_id']));
        Tpl::output('models',$this->brandOp($_GET['series_id']));
        #------------ 区域搜索 --------------
        $regions = $this->regionOp(0,'return');
        Tpl::output('region',$regions);
        if(!empty($find['province'])){
            $area = $this->regionOp($find['province'],'return');
            Tpl::output('area',$area);
        }
        if(!empty($find['sp_province'])){
            $sp_area = $this->regionOp($find['sp_province'],'return');
            Tpl::output('sp_area',$sp_area);
        }

        Tpl::output('search',$result['search']);
        Tpl::output('list',$result['list']);
        Tpl::output('page',$result['page']);
        Tpl::output('uri',$this->getServerQueryString());
        Tpl::setDir('Vouchers');
        Tpl::showpage('list');
    }

    /**
     * 组列表
     */
    public function groupOp($export=false)
    {
        $result = $this->vouchers ->getGroupList($_GET);
        if($export==true){
            return $result['list'];exit;
        }
        Tpl::output('search',$result['search']);
        Tpl::output('brands',$this->brandOp());
        Tpl::output('series',$this->brandOp($_GET['brand_id']));
        Tpl::output('models',$this->brandOp($_GET['series_id']));
        Tpl::output('list',$result['list']);
        Tpl::output('page',$result['page']);
        Tpl::output('uri',$this->getServerQueryString());
        Tpl::setDir('Vouchers');
        Tpl::showpage('group_list');
    }

    public function viewOp()
    {
        $id = (int) $_GET['id'];
        $groupInfo = $this->vouchers ->getVoucherFind($id);
        Tpl::output('info',$groupInfo);
        Tpl::setDir('Vouchers');
        Tpl::showpage('view_vouchers');
    }

    /**
     * 添加代金券组
     */
    public function add_groupOp()
    {
        $id = (int) $_GET['id'];
        if(!empty($id)){
            $this->viewGroup($id);
        }else{
            $activated = intval($_GET['activated']);
            Tpl::output('brands',$this->brandOp());
            Tpl::setDir('Vouchers');
            if($activated ==1)
                Tpl::showpage('add_group');
            else
                Tpl::showpage('add_not_group');
        }
    }

    private function viewGroup($group_id)
    {
        $groupInfo = $this->vouchers ->getGroupFind($group_id,false);
        $peration = $this->vouchers->getAdminoPeration($group_id,42);
        if($peration)
        Tpl::output('peration',$peration);

        Tpl::output('group',$groupInfo);
        Tpl::setDir('Vouchers');
        if($groupInfo['activated_type'] ==1)
            Tpl::showpage('view_group');
        else
            Tpl::showpage('view_not_group');
    }

    /**
     * 保存组
     */
    public function save_groupOp()
    {
        if($_POST != NULL){
            $type = intval($_POST['activated_type']);
            $_POST['admin_id']   = $this->admin_info['id'];
            $res = $this->vouchers ->addGroup($_POST,$type);
            if($res){
                showDialog('操作成功','index.php?act=hc_vouchers&op=group','succ');
            }else{
                showDialog('操作失败','','error');
            }
        }
    }

    /**
     * 添加投放记录
     */
    public function save_releaseOp()
    {
        if($_POST != NULL){
            $group_id = intval($_POST['group_id']);
            $_POST['admin_id']   = $this->admin_info['id'];
            $activated = $_POST['activated_type'];
            $res = $this->vouchers ->addRelesase($_POST,$group_id);
            if($res){
                showDialog('操作成功','index.php?act=hc_vouchers&op=release&activated='.$activated,'succ');
            }else{
                showDialog('操作失败','','error');
            }
        }
    }
    /**
     * 投放列表
     */
    public function releaseOp($export=false)
    {
        $activated = intval($_GET['activated']);
        $_GET['activated_type'] = $activated;
        $result = $this->vouchers ->getReleaseList($_GET);
        if($export==true){
            return $result['list'];exit;
        }
        Tpl::output('search',$result['search']);
        Tpl::output('brands',$this->brandOp());
        Tpl::output('series',$this->brandOp($_GET['brand_id']));
        Tpl::output('models',$this->brandOp($_GET['series_id']));
        Tpl::output('list',$result['list']);
        Tpl::output('page',$result['page']);
        Tpl::output('uri',$this->getServerQueryString());
        #------------ 区域搜索 --------------
        $regions = $this->regionOp(0,'return');
        Tpl::output('region',$regions);
        if(!empty($find['province'])){
            $area = $this->regionOp($find['province'],'return');
            Tpl::output('area',$area);
        }

        Tpl::setDir('Vouchers');
        if($activated ==1)
            Tpl::showpage('release_list');
        else
            Tpl::showpage('release_not_list');
    }
    /**
     * 申请投放
     */
    public function app_releaseOp()
    {
        $id = (int) $_GET['id'];
        if(!empty($id)){
            $this->viewRelease($id);
        }else {
            $group_id = (int)$_GET['group_id'];
            $groupInfo = $this->vouchers->getGroupFind($group_id, false);
            Tpl::output('group', $groupInfo);
            ###----------- 部门 --------------
            $dept = $this->vouchers->table('hc_admin_dept')->field('id,name')->select();
            Tpl::output('dept', $dept);
            ###-------------------- 区域 ---------
            $regions = $this->regionOp(0, 'return');
            Tpl::output('region', $regions);
            Tpl::setDir('Vouchers');
            if ($groupInfo['activated_type'] == 1)
                Tpl::showpage('add_release');
            else
                Tpl::showpage('add_not_release');
        }
    }

    /**
     * 投放详情
     * @param $id
     */
    private function viewRelease($id)
    {
        $info = $this->vouchers ->getReleaseFind($id);
        //审批id
        $approval = intval($_GET['approval']);
        $info['approval'] = $approval;
        //审批日志
        $log = $this->vouchers ->getAdminoPeration($id);
        Tpl::output('log',$log);
        Tpl::output('info',$info);
        Tpl::setDir('Vouchers');
        if($info['activated_type'] ==1)
            Tpl::showpage('view_release');
        else
            Tpl::showpage('view_not_release');
    }

    /**
     * 更改状态
     */
    public function setStatusOp()
    {
        if(isAjax()){
            $id     = (int) $_REQUEST['id'];
            $status = (int) $_REQUEST['status'];
            $type   = intval($_REQUEST['type']);
            $remark = trim($_REQUEST['remark']);
            $activated = intval($_REQUEST['activated']);
            $res = $this->vouchers ->setStatus($status,$id,$type,$this->admin_info,$remark);
            if($res){
                if($activated){
                    if($status ==1){
                        //投放激活码
                        $this->executeStask($id, $type);
                    }
                }
            }
            echo $res ? setJsonMsg(1,'操作成功！') : setJsonMsg(0,'操作失败！');
        }
    }

    /**
     * 下载激活码，并修改状态为已投放
     */
    public function postSetStatusOp()
    {
        if($_POST != NULL) {
            $id             = (int)$_POST['id'];
            $status         = (int)$_POST['status'];
            $type           = intval($_POST['type']);
            $activated_type = intval($_POST['activated_type']);
            $res = $this->vouchers->setStatus($status, $id, $type,$this->admin_info);
            if($res){
                if ($status == 2) {
                    if($activated_type ==1){
                        //下载Excel
                        $this->exportActivatedOp($id);
                    }
                    //投放激活码
                    $this->executeStask($id, $type);
                }
                showDialog('操作成功','index.php?act=hc_vouchers&op=release&activated='.$activated_type,'succ');
            }else{
                showDialog('操作失败','','error');
            }
        }
    }

    /**
     * 查看激活码列表
     */
    public function showAcitvatedOp()
    {
        $id = (int) $_GET['id'];
        $activatedList = $this->vouchers->getReleaseActivated($id,$_GET);
        $ActivatedInfo = $this->vouchers->getActivatedFind($id);
        if(count($activatedList) >0){
            $result = array_chunk($activatedList,10);
            Tpl::output('list',$result);
        }
        $search = $_GET;
        $search['activated'] = isset($_GET['activated']) ? intval($_GET['activated']) : 3;
        Tpl::output('search',$search);
        Tpl::output('info',$ActivatedInfo);
        Tpl::setDir('Vouchers');
        Tpl::showpage('acitvate_list');
    }
    /**
     * 智能代金券
     */
    public function cleverOp()
    {
        Tpl::setDir('Vouchers');
        Tpl::showpage('clever_list');
    }
    /**
     * 智能代金券
     */
    public function add_cleverOp()
    {
        Tpl::setDir('Vouchers');
        Tpl::showpage('add_clever');
    }

    /**
     * 代金券推广
     */
    public function promotionOp()
    {
        $search = ['status'=>9,'province'=>0,'city'=>0,'year'=>0,'month'=>0];

        $regions = $this->regionOp(0,'return');
        Tpl::output('region',$regions);
        if(!empty($find['province'])){
            $area = $this->regionOp($find['province'],'return');
            Tpl::output('area',$area);
        }
        Tpl::output('search',$search);
        Tpl::output('list',[[]]);
        Tpl::setDir('Vouchers');
        Tpl::showpage('promotion_list');
    }

    /**
     * 代理投放列表
     */
    public function dl_releaseOp()
    {
        $search = ['status'=>9,'province'=>0,'city'=>0,'type'=>0,'use_vouchers'=>0,'reward_type'=>0];

        $regions = $this->regionOp(0,'return');
        Tpl::output('region',$regions);
        if(!empty($find['province'])){
            $area = $this->regionOp($find['province'],'return');
            Tpl::output('area',$area);
        }
        Tpl::output('search',$search);
        Tpl::output('list',[[]]);

        Tpl::setDir('Vouchers');
        Tpl::showpage('dl_release_list');
    }

    public function dl_view_releaseOp()
    {
        exit('功能待定');
    }

    /**
     * 代理酬劳列表
     */
    public function dl_rewardOp()
    {
        $search = ['year'=>0,'month'=>0];
        Tpl::output('search',$search);
        Tpl::output('list',[[]]);
        Tpl::setDir('Vouchers');
        Tpl::showpage('dl_reward_list');
    }
    /**
     * 触发定时任务
     */
    public function startTask(array $task)
    {
        foreach($task as $item){
            taskEvent::addTask($item['time'],$item['object'],['data'=>$item['data']],$item['total']);
        }
        taskEvent::runTask();
    }
    /**
     * @param $id  执行计划任务
     * @param int $type
     */
    private function executeStask($id,$type=1)
    {
        switch($type){
            case 1: //定时任务批量生成代金券
                $release = $this->vouchers ->getReleaseFind($id);
                $group    = $this->vouchers ->getGroupFind($release['group_id']);
                $group['release_id'] = $id;
                return $this->vouchers ->addAllVoucher($group,$release['release_total_num']);
                exit;
                $taskContainer[] = ['time'=> 1,'object'=> 'addVouchers','data'=> $group,'total' => 1];
                break;
            case 2://投放
                $release = $this->vouchers ->getReleaseFind($id);
               /* $_time = empty($release['ignore_time_type']) ? 1 : strtotime($release['fixed_start_time'].' '.$release['fixe_hour_time'])-time();
                $time = ($_time <=1) ? 1 : $_time ;*/
                $data = [
                    'release_id'       => $id,
                    'ignore_time_type' => intval($release['ignore_time_type']),
                    'activated_type'   => $release['activated_type'],
                    'ignore_object'    => $release['ignore_object'],
                    'release_total'    => $release['release_total'],
                    'release_total_num'=> $release['release_total_num'],
                    'use_collateral'   => $release['use_collateral'],
                    'use_sincerity'    => $release['use_sincerity'],
                    'sincerity_money'  => $release['sincerity_money'],
                    'collateral_money' => $release['collateral_money'],
                    'life_start_time'  => $release['life_start_time'],
                    'life_start_hour'  => $release['life_start_hour'],
                    'parent_sn'        => '',
                    'group_id'         => $release['group_id'],
                    'ignore_users'     => $release['ignore_users'],
                    'can_release_num'  => $release['can_release_num'],
                    'activated_rule'   => $release['activated_rule'],
                    'activated_num'    => $release['activated_num'],
                    'status'           => 1//投放后状态未未使用
                ];
                return $this->vouchers ->startRelease($data);
                exit;
                $taskContainer[] = ['time'   => $time,'object' => 'releaseVouchers','data'   => $data,'total'  => 1];
                break;
        }
       return $this->startTask($taskContainer);
    }
    /** 联动品牌车型车系
     * @param int $parent
     * @return mixed
     */
    public function brandOp($parent=0)
    {
        if(isAjax()){
            $parent = $_GET['parent'];
        }
        if(isset($_GET['parent'])){
            if(empty($_GET['parent'])){
                echo json_encode([]);exit;
            }
        }
        $result = $this->vouchers ->table('goods_class')
            ->field('gc_id,gc_name,gc_parent_id')
            ->where(['gc_parent_id'=>$parent])
            ->select();

        if(isAjax()){
            echo json_encode($result);exit;
        }else{
            return $result;
        }
    }

    /**
     * 导出代金券
     */
    public function exportVoucherOp()
    {
        $result = $this->indexOp(true);
        $tmp = array();
        foreach($result as $k => $v){
            $tmp[$k][] = $v['voucher_sn'];
            $tmp[$k][] = getVouchersSource($v['id']);
            $tmp[$k][] = getVouchersSourceCode($v['id']);
            $tmp[$k][] = $v['tf_id'];
            $tmp[$k][] = $v['user_id'];
            $tmp[$k][] = ($v['type']) ? '品类券' : '通用券';
            if($v['use_collateral'] && $v['use_sincerity']){
                $useStr = '诚意金/买车担保金余款';
            }else{
                $useStr = ($v['use_collateral']) ? '买车担保金余款':'诚意金';
            }
            $tmp[$k][] = $useStr;
            if($v['use_collateral'] && $v['use_sincerity']){
                $useMoney = '￥'.$v['collateral_money'].'/￥'.$v['sincerity_money'];
            }else{
                $useMoney = ($v['use_collateral']) ? '￥'.$v['collateral_money'] : '￥'.$v['sincerity_money'];
            }
            $tmp[$k][] = $useMoney;
            $tmp[$k][] = 'TODO';
            $tmp[$k][] = 'TODO';
            $tmp[$k][] = ($v['province']==0) ? '全国' : getRegion($v['province']).getRegion($v['city']);
            $tmp[$k][] = 'TODO';
            $statusArr = ['未生效','未使用','已过期','已使用','已结算'];
            $tmp[$k][] = $statusArr[$v['status']];
        }
        $titleArray = ['代金券编码','来源','来源编码','申请投放编码','客户会员号','券类别','代用款项','面值','结算金额','结算时间','投放区域','上牌地区','券状态'];
        $this->createExcel($tmp,$titleArray,'代金券-代金券列表');
    }

    /**
     * 导出代金券组
     */
    public function exportGroupOp()
    {
        $result = $this->groupOp(true);
        $tmp = array();
        foreach($result as $k => $v){
            $tmp[$k][] = $v['id'];
            $tmp[$k][] =($v['activated_type'])?'需激活':'免激活';
            $tmp[$k][] =($v['type'])?'品类券':'通用券';
            $tmp[$k][] =($v['type'] ==1)?getGoodsClass($v['brand_id']).getGoodsClass($v['series_id']).getGoodsClass($v['model_id']):'-';

            if($v['use_collateral'] && $v['use_sincerity']){
                $useStr = '诚意金/买车担保金余款';
            }else{
                $useStr = ($v['use_collateral']) ? '买车担保金余款':'诚意金';
            }
            $tmp[$k][] = $useStr;
            if($v['use_collateral'] && $v['use_sincerity']){
                $useMoney = '￥'.$v['collateral_money'].'/￥'.$v['sincerity_money'];
            }else{
                $useMoney =  ($v['use_collateral']) ? '￥'.$v['collateral_money'] : '￥'.$v['sincerity_money'];
            }
            $tmp[$k][] = $useMoney;
            $tmp[$k][] = ($v['use_collateral'] + $v['use_sincerity']) * $v['activated_total_num'];
            $tmp[$k][] = $v['can_release_num'];
            $tmp[$k][] = $v['life_start_time'] .' '.$v['life_start_hour'].' ~ '. $v['life_end_time'].' '.$v['life_end_hour'];
            $touNum = (($v['use_collateral'] + $v['use_sincerity']) * $v['activated_total_num']) - $v['release_total'];
            if($v['release_total'] ==0){
                $touStr = "未投放";
            }else{
                $touStr = ($touNum ==0) ? "全部投放" : "部分投放";
            }
            $tmp[$k][] = $touStr;
        }
        $titleArray = ['代金券组编号','激活类型','券类别','品牌/车系/车型','代用款项','面值','每组条数','可投放数','有效期','券组状态'];
        $this->createExcel($tmp,$titleArray,'代金券-代金券组列表');
    }

    /**
     * 需激活投放列表
     */
    public function exportReleaseOp()
    {
        $result = $this->releaseOp(true);
        $tmp = array();
        foreach($result as $k => $v){
            $tmp[$k][] = $v['id'];
            $tmp[$k][] =$v['group_id'];
            $tmp[$k][] =($v['type'])?'品类券':'通用券';
            $tmp[$k][] =($v['type'] ==1)?getGoodsClass($v['brand_id']).getGoodsClass($v['series_id']).getGoodsClass($v['model_id']):'-';
            if($v['use_collateral'] && $v['use_sincerity']){
                $useStr = '诚意金/买车担保金余款';
            }else{
                $useStr = ($v['use_collateral']) ? '买车担保金余款':'诚意金';
            }
            $tmp[$k][] = $useStr;
            if($v['use_collateral'] && $v['use_sincerity']){
                $useMoney = '￥'.$v['collateral_money'].'/￥'.$v['sincerity_money'];
            }else{
                $useMoney = ($v['use_collateral']) ? '￥'.$v['collateral_money'] : '￥'.$v['sincerity_money'];
            }
            $tmp[$k][] = $useMoney;
            $tmp[$k][] = ($v['use_collateral'] + $v['use_sincerity']) * $v['activated_total_num'];
            $tmp[$k][] =$v['can_release_num'];
            if(empty($v['reward_type'])){
                $rewardStr = $v['guide_dept'] .' '.$v['guide_name'];
            }else{
                $rewardStr = $v['proxy_name'] ? $v['proxy_name'] :$v['d_dept'] .' '.$v['d_guide_name'];
            }
            $tmp[$k][] = $rewardStr;
            $tmp[$k][] =($v['province']==0) ? '全国' : getRegion($v['province']).getRegion($v['city']);
            $tmp[$k][] = $v['created_at'];
            $statusArr =['待批准','待投放','已投放','已失效','未批准'];
            $tmp[$k][] = $statusArr[$v['status']];
        }
        $titleArray = ['申请投放编号','金券组编号','券类别','品牌/车系/车型','代用款项','面值','推广奖励','投放条数','投放对象','投放区域','投放时间','投放状态'];
        $this->createExcel($tmp,$titleArray,'代金券-激活投放列表');
    }

    /**
     * 免激活投放
     */
    public function exportNotReleaseOp()
    {
        $result = $this->releaseOp(true);
        $tmp = array();
        foreach($result as $k => $v){
            $tmp[$k][] = $v['id'];
            $tmp[$k][] =$v['group_id'];
            $tmp[$k][] =($v['type'])?'品类券':'通用券';
            $tmp[$k][] =($v['type'] ==1)? getGoodsClass($v['brand_id']).getGoodsClass($v['series_id']).getGoodsClass($v['model_id']):'-';

            if($v['use_collateral'] && $v['use_sincerity']){
                $useStr = '诚意金/买车担保金余款';
            }else{
                $useStr = ($v['use_collateral']) ?  '买车担保金余款' : '诚意金';
            }
            $tmp[$k][] = $useStr;

            if($v['use_collateral'] && $v['use_sincerity']){
                $useMoney = '￥'.$v['collateral_money'].'/￥'.$v['sincerity_money'];
            }else{
                $useMoney = ($v['use_collateral']) ? '￥'.$v['collateral_money'] : '￥'.$v['sincerity_money'];
            }
            $tmp[$k][] = $useMoney;
            $tmp[$k][] = ($v['use_collateral'] + $v['use_sincerity']) * $v['activated_total_num'];
            $tmp[$k][] = $v['can_release_num'];
            $objectArr = ['所有客户','特定客户','一年内未买车的客户','三个月内新注册并且未买车的用户'];
            $tmp[$k][] = $objectArr[$v['ignore_object']];
            $statusArr = ['待批准','待投放','已投放','已失效','未批准'];
            $tmp[$k][] = $statusArr[$v['status']];
        }
        $titleArray = ['申请投放编号','代金券组编号','券类别','品牌/车系/车型','代用款项','面值','投放条数','投放对象','投放区域','投放时间','投放状态'];
        $this->createExcel($tmp,$titleArray,'代金券-免激活投放列表');
    }

    /**
     * @param $id 导出激活码
     */
    public function exportActivatedOp($id=0)
    {
        $id = empty($_GET['id']) ? $id : $_GET['id'];
        $activatedList = $this->vouchers->getReleaseActivated($id);
        if(count($activatedList) >0){
            $result = array_chunk($activatedList,10);
            $tmp = array();
            foreach($result as $k => $v){
                foreach($v as $key =>$val){
                    $tmp[$k][] = $val['activated_code'];
                }
            }
            $this->createExcel($tmp,[],'代金券-'.$id.'-下载激活码');
        }
    }
}