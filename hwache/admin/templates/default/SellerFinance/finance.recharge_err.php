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
                <li><a href="<?=url('seller_finance','recharge');?>"><span>充值</span></a><em>  </em></li>
                <li><a href="JavaScript:void(0);" class="current"><span><?=$output['statusString']?></span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form id="finance_form" action="<?=url('seller_finance','save_recharge_ok');?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="form_submit" id="submit_type" value="recharge" />
        <input type="hidden" name="drb_id" id="drb_id" value="<?=$output['find']['drb_id'];?>" />
        <input type="hidden" name="ref_url" value="<?php echo getReferer();?>" />
        <table class="table tb-type2">
            <tbody>
            <tr class="noborder">
                <td class="required"><label for="drb_id">工单编号：</label></td>
                <td class="vatop rowform">
                    <?=$output['find']['drb_id']?>
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
                <td class="required"><label for="money">提交金额：</label></td>
                <td class="vatop rowform">
                    <?=$output['find']['money']?>
                </td>
                <td class="required"><label for="voucher">提交凭证：</label></td>
                <td class="vatop rowform">
                    <img src="<?=$output['find']['voucher']?>" width="200" height="100" />
                </td>
                <td class="required">&nbsp;</td>
                <td class="vatop rowform">&nbsp;</td>
            </tr>
            <!-- 入账 -->
            <tr>
                <td class="required" colspan="6" style="margin-left: 5px; border-bottom: 1px #000 solid"> <b>入账</b></td>
            </tr>


            <tr>
                <td class="required" colspan="3">银行到账金额：￥0</td>
                <td class="required" colspan="3">&nbsp;</td>
            </tr>

            <?php
            if($output['operations']) {
                foreach($output['operations'] as $operation) {
                    $stepArrStr = ['提交','核实','确认'];
                    $stepStr = $stepArrStr[$operation['step']];
                    ?>
                    <tr>
                        <td class="required" colspan="3">入账<?=$stepStr?>人：<?=$operation['user_name'] ?> </td>
                        <td class="required" colspan="3">入账<?=$stepStr?>时间：<?=$operation['created_at'] ?></td>
                    </tr>
                    <?php
                }
            }
            ?>

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
                                        if($output['find']['delCommentCount'] ==0 || ($ov['user_id'] == $output['find']['admin_id'])){
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
                            <td colspan="6" align="left">&nbsp;<button type="button" onclick="addRemarkDialog(<?=$output['find']['drb_id']?>,<?=$output['find']['kefu_confirm_status']?>)" class="button button-small">添加</button></td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
            <tfoot>
            <tr class="tfoot">
                <td colspan="15" style="text-align: center;" >
                    <a href="<?=url('seller_finance','recharge');?>" class="button button-glow button-border button-highlight button-small"> 返回 </a>
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
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/SellerFinance/recharge.js" charset="utf-8"></script>

