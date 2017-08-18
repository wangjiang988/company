<?php defined('InHG') or exit('Access Invalid!');?>
<style type="text/css">
    .table input.txt{width: 120px;}
    .table input.date , .table input.date:hover{width:160px ; padding:5px; border:1px #000 solid;}
    .table input.txt{width: 280px; padding:5px; border: 1px #000 solid;}
    .border-trtd{border:1px #ccc solid;}
    .border-trtd tr , .border-trtd td{border:1px #ccc solid;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><a href="<?=url('seller_finance','index');?>">售方财务</a></h3>
            <ul class="tab-base">
                <li><a href="<?=url('seller_finance','withdraw');?>"><span>提现</span></a><em>  </em></li>
                <li><a href="JavaScript:void(0);" class="current"><span><?=$output['statusString']?></span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form id="seller_form" action="<?=url('seller_finance','save_withdraw');?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="type" id="type" value="" />
        <input type="hidden" name="dwb_id" id="dwb_id" value="<?=$output['find']['dwb_id'];?>" />
        <input type="hidden" name="form_submit" value="ok" />
        <table class="table tb-type2">
            <tbody>
            <tr class="noborder">
                <td class="required"><label for="drb_id">工单编号：</label></td>
                <td class="vatop rowform">
                    <?=$output['find']['dwb_id']?>
                </td>
                <td class="required"><label for="created_at">工单时间：</label></td>
                <td class="vatop rowform">
                    <?=$output['find']['created_at']?>
                </td>
                <td class="required"><label for="status">状态：</label></td>
                <td class="vatop rowform">
                    <?=$output['statusString']?>
                </td>
            </tr>

            <tr class="noborder">
                <td class="required"><label for="member_name">售方用户名：</label></td>
                <td class="vatop rowform">
                    <?=$output['find']['member_name'];?>
                </td>
                <td class="required"><label for="member_truename">售方用户姓名：</label></td>
                <td class="vatop rowform">
                    <?=$output['find']['member_truename'];?>
                </td>
                <td class="required"><label for="seller_card_num">售方手机号：</label></td>
                <td class="vatop rowform">
                    <?=$output['find']['member_mobile'];?>
                </td>
            </tr>
            <tr>
                <td class="required"><label for="money">提现金额：</label></td>
                <td class="vatop rowform">
                    <?=$output['find']['money']?>
                </td>
                <td class="required"><label for="voucher">提现手续费：</label></td>
                <td class="vatop rowform">
                    <?=$output['find']['fee']?>
                </td>
                <td class="required">拦截提现原因：</td>
                <td class="vatop rowform">TODO</td>
            </tr>
            <!-- 入账 -->
            <tr>
                <td class="required" colspan="6" style="margin-left: 5px; border-bottom: 1px #000 solid"> <b>提现路线</b></td>
            </tr>

            <tr>
                <td width="required" colspan="4">收款路线：<?=$output['find']['bank_name']?></td>
            </tr>
            <tr>
                <td class="required" colspan="6">收款方账号：<?=$output['find']['bank_account'];?></td>
            </tr>
            <tr>
                <td class="required" colspan="6">收款方户名：<?=$output['find']['daili_bank_name'];?></td>
            </tr>

            <?php
            if($output['operation']){
            ?>
            <tr>
                <td class="required" colspan="3">转账报错人：<?=$output['operation']['user_name'];?></td>
                <td class="required" colspan="3">转账报错时间：<?=$output['operation']['created_at'];?></td>
            </tr>
            <?php }?>

            <?php
            if($output['operations']){
                $keyArrTitle = ['接单','确认','补充','报错'];
                foreach($output['operations'] as $key => $operation){
                    ?>
                    <tr>
                        <td class="required" colspan="3">转账<?=$keyArrTitle[$key]?>人：<?=$operation['user_name'];?></td>
                        <td class="required" colspan="3">转账<?=$keyArrTitle[$key]?>时间：<?=$operation['created_at'];?></td>
                    </tr>
                <?php } }?>

            <!-- 备注 -->
            <tr>
                <td class="required" colspan="6" style=" margin-left: 5px;border-bottom: 1px #000 solid;">  <b>备注</b>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <table style="width: 100%;">
                        <thead>
                        <tr style="border: 1px #ccc solid;font-size: 14px; text-align: center;">
                            <td width="10%"><b>编号</b></td>
                            <td width="20%"><b>内容</b></td>
                            <td width="20%"><b>证据</b></td>
                            <td width="20%"><b>备注人</b></td>
                            <td width="20%"><b>备注时间</b></td>
                            <td width="10%"><b>操作</b></td>
                        </tr>
                        </thead>
                        <tbody style="border: 1px #ccc solid;">
                        <?php
                        if($output['remarks']) {
                            foreach ($output['remarks'] as $key => $ov) {
                                ?>
                                <tr class="border-trtd">
                                    <td><?= $ov['id'] ?></td>
                                    <td><?= $ov['remark'] ?></td>
                                    <td><?=explodeImg($ov['file_path'])?></td>
                                    <td><?= $ov['user_name'] ?></td>
                                    <td><?= $ov['created_at'] ?></td>
                                    <td>
                                        <?php
                                        if($output['find']['delCommentCount'] ==0 && ($ov['user_id'] == $output['find']['admin_id'])){
                                            ?>
                                            <a href="javascript:;" onclick="show_del_dialog(<?=$ov['id']?>);">删除</a>
                                        <? } ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }else{
                            ?>
                            <tr><td colspan="6" align="center"><h1>没有记录。。。</h1></td></tr>
                        <?php } ?>
                        <tr>
                            <td colspan="6" align="left">&nbsp;<button type="button" onclick="addRemarkDialog(<?=$output['find']['dwb_id']?>,<?=$output['find']['kefu_confirm_status']?>)" class="button button-small">添加</button></td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>

            </tbody>
            <tfoot>
            <tr class="tfoot">
                <td colspan="15" style="text-align: center;" >
                    <a href="<?=url('seller_finance','withdraw');?>" class="button button-primary button-small"> 返回 </a>
                </td>
            </tr>
            </tfoot>
        </table>
    </form>
</div>

<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/buttons.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/region.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/diy_validate.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/SellerFinance/withdraw.js" charset="utf-8"></script>
