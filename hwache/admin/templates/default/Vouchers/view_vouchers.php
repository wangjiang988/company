<?php defined('InHG') or exit('Access Invalid!');?>
<style type="text/css">
    .table .required{width: 10%;}
    .table tbody{margin-bottom: 15px; float: left; width: 100%;}
    .border {border: 1px #000 solid;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><a href="<?=url('hc_vouchers','index');?>">代金券</a></h3>
            <ul class="tab-base">
                <li><a href="JavaScript:void(0);" class="current"><span>代金券详情</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <?php
    $group = $output['info'];
    ?>
    <form id="voucher_form" action="<?=url('hc_vouchers','');?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="group_id" value="<?=$group['id'];?>" />
        <input type="hidden" name="can_release_num" value="<?=$group['can_release_num'];?>" />
        <input type="hidden" name="activated_type" value="1" />
        <input type="hidden" name="ref_url" value="<?php echo getReferer();?>" />

        <table class="table tb-type2">
            <thead>
            <th colspan="4">基本信息</th>
            </thead>
            <tbody class="border">
            <tr>
                <td width="10%" class="required"><label>代金券编号：</label></td>
                <td class="vatop rowform"><?=$group['voucher_sn']?></td>
                <td width="10%" class="required"><label>券状态：</label></td>
                <td class="vatop rowform">
                    <?php
                    $statusArr = ['未生效','未使用','已过期','已使用','已结算'];
                    echo $statusArr[$group['status']];
                    ?>
                </td>
            </tr>

            <tr class="noborder">
                <td class="required">券类别：</td>
                <td class="vatop rowform" colspan="3">
                    <?php
                    if($group['type'] ==1){
                        $brandArr= [getGoodsClass($group['brand_id']),getGoodsClass($group['series_id']),getGoodsClass($group['model_id'])];
                        echo '品类券 ('.implode( ' > ',array_filter($brandArr)).')';
                    }else{
                        echo '通用券';
                    }
                    ?>
                </td>
            </tr>

            <tr class="noborder">
                <td class="required">有效使用时间：</td>
                <td class="vatop rowform" colspan="3">
                    <?php
                    echo $group['life_start_time'] .' ~ '. $group['life_end_time'];
                    ?>
                </td>
            </tr>

            <tr>
                <td class="required"><label>代用款项：</label></td>
                <td class="vatop rowform">
                <?php
                if($group['use_sincerity']){
                    echo '诚意金';
                    if($group['ignore_sincerity_temp']){
                        echo '<a href="'.$group['ignore_sincerity_temp'].'">查看设计模板</a><br />';
                    }
                }
                if($group['use_collateral']){
                    echo '买车担保金余款';
                    if($group['ignore_collateral_temp']){
                        echo '<a href="'.$group['ignore_collateral_temp'].'">查看设计模板</a><br />';
                    }
                }
                ?>
                </td>
                <td class="required"><label>面值：</label></td>
                <td class="vatop rowform">
                    <?php
                    if($group['use_collateral'] && $group['use_sincerity']){
                        echo '￥'.$group['sincerity_money'].'/￥'.$group['collateral_money'];
                    }else{
                        echo ($group['use_collateral']) ? '￥'.$group['collateral_money'] : '￥'.$group['sincerity_money'];
                    }
                    ?>
                </td>
            </tr>

            <thead>
            <th colspan="4">准备信息</th>
            </thead>
            <tbody class="border">
            <tr>
                <td class="required"><label>代金券组编号：</label></td>
                <td class="vatop rowform" colspan="3"><?=$group['group_id']?></td>
            </tr>
            <tr>
                <td class="required"><label>申请投放编号：</label></td>
                <td class="vatop rowform"><?=$group['tf_id']?></td>
                <td class="required"><label>投放对象：</label></td>
                <td class="vatop rowform">
                    <?php
                    if(empty($group['release_object'])) {
                        echo '华车内部/'.$group['guide_dept'] . ' ' . $group['guide_name'];
                    }else {
                        echo '推广代理/' . $group['proxy_name'];
                    }
                    ?>
                </td>

            </tr>
            <tr>
                <td class="required"><label>华车指导：</label></td>
                <td class="vatop rowform">
                <?php
                if($group['d_dept'] && $group['d_guide_name']){
                    echo ' 华车指导'.$group['d_dept'] . ' ' . $group['d_guide_name'];
                }
                ?>
                </td>
                <td class="required">推广奖励:</td>
                <td class="vatop rowform">
                    <?php
                    echo $group['reward_type'] ? '￥'.$group['reward_money'] : $group['reward_money'].'%';
                    ?>
                </td>
            </tr>
            <tr>
                <td class="required"><label>投放说明：</label></td>
                <td class="vatop rowform"><?=$group['tf_remark']?></td>
                <td class="required"><label>投放区域：</label></td>
                <td class="vatop rowform">
                <?php
                    echo ($group['province']==0) ? '全国' : getRegion($group['province']).getRegion($group['city']);
                ?>
                </td>
            </tr>
            <tr>
                <td class="required"><label>投放人：</label></td>
                <td class="vatop rowform"><?=getDeptAdmin($group['tf_admin'])?></td>
                <td class="required"><label>投放时间：</label></td>
                <td class="vatop rowform"><?=$group['tf_time']?></td>
            </tr>
            <tr>
                <td class="required"><label>来源：</label></td>
                <td class="vatop rowform"><?=getVouchersSource($group['id'])?></td>
                <td class="required"><label>来源编号：</label></td>
                <td class="vatop rowform"><?=getVouchersSourceCode($group['id'])?></td>
            </tr>
            <tr>
                <td class="required"><label>到账时间：</label></td>
                <td class="vatop rowform" colspan="3">TODO<?=$group['']?></td>
            </tr>
            <tr>
                <td class="required"><label>客户会员号：</label></td>
                <td class="vatop rowform"><?=$group['user_id']?></td>
                <td class="required"><label>客户手机号：</label></td>
                <td class="vatop rowform"><?=getUserFind($group['user_id'],'phone')?></td>
            </tr>
            </tbody>

            <thead>
            <th colspan="4">使用信息</th>
            </thead>
            <tbody class="border">
            <tr>
                <td class="required"><label>使用时间：</label></td>
                <td class="vatop rowform">TODO</td>
                <td class="required"><label>使用金额：</label></td>
                <td class="vatop rowform">TODO</td>
            </tr>
            <tr>
                <td class="required"><label>订单号：</label></td>
                <td class="vatop rowform">TODO</td>
                <td class="required"><label>上牌地区：</label></td>
                <td class="vatop rowform">TODO</td>
            </tr>
            <tr>
                <td class="required"><label>购买车型：</label></td>
                <td class="vatop rowform" colspan="3">TODO</td>
            </tr>
            <tr>
                <td class="required"><label>结算时间：</label></td>
                <td class="vatop rowform">TODO</td>
                <td class="required"><label>结算金额：</label></td>
                <td class="vatop rowform">TODO</td>
            </tr>
            <tr>
                <td class="required"><label>结算用途：</label></td>
                <td class="vatop rowform" colspan="3">TODO/转付华车服务费使用</td>
            </tr>
            <tr>
                <td class="required"><label>过期时间：</label></td>
                <td class="vatop rowform" colspan="3">
                    <?=$group['life_end_time']. ' '.$group['life_end_hour'] ?>
                </td>
            </tr>
            <tr>
                <td class="required"><label>备注：</label></td>
                <td class="vatop rowform" colspan="3"><?=$group['remark']?></td>
            </tr>
            </tbody>
            <!---->
            <thead><th>&nbsp;</th></thead>
            <tfoot>
            <tr class="tfoot">
                <td colspan="6" style="text-align: center;" >
                    <a href="<?=url('hc_vouchers','index');?>" class="button button-primary button-small"> 返回 </a>
                </td>
            </tr>
            </tfoot>
        </table>
    </form>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/buttons.css"  />