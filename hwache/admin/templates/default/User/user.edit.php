<?php defined('InHG') or exit('Access Invalid!'); ?>
<style type="text/css">
    .table .required{width: 100px; margin: 0; padding: 0;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><?=$lang['index_title'];?></h3>
            <ul class="tab-base">
                <li><a href="<?=url('new_user','list')?>"><span>列表</span></a></li>
                <li><a href="JavaScript:void(0);" class="current"><span>查看</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <table class="table tb-type2">
        <tbody>
        <tr>
            <td class="required" colspan="2" style="background:#0A8CD2; color: #ffffff; margin-left: 5px;"> | <b>会员基本信息</b></td>
        </tr>
        <?php
        $user_id = $output['find']['id'];
        ?>
        <tr>
            <td width="required">
                <label for="status">会员号:</label>
            </td>
            <td class="vatop rowform">
                <?=$output['find']['id']?>
            </td>
        </tr>
        <tr>
            <td width="required">
                <label for="seller_name">姓名:</label>
            </td>
            <td class="vatop rowform">
                <?=$output['find']['last_name'];?>&nbsp;<?=$output['find']['first_name'];?>
                <?php
                if($output['find']['is_id_verify'] ==1) {
                    ?>
                    <span class="red">已实名认证</span>
                    <a href="<?= url('new_user', 'idcart', ['id' => $output['find']['id']]) ?>"><span>查看认证信息</span></a>
                    <?php } ?>
            </td>
        </tr>
        <tr>
            <td width="required">
                <label for="member_truename">手机:</label>
            </td>
            <td class="vatop rowform">
                <?=$output['find']['phone'];?>
                <?=isFreeze($user_id,$output['find']['phone']) ? '<font class="red">（免扰）</font>' : '';?>
                <?php if($output['mobile']['status'] ==1) { ?>
                    <font class="red">
                        （找密冻结  <span class="mobile-countdown">  <span>0</span> : <span>0</span>  </span>)
                    </font>
                    <a href="javascript:;" class="btn" onclick="setDj('<?= $output['mobile']['id']; ?>',0,'pwd_dj')"><span>解冻</span></a>
                    <?php   } ?>
                <a href="<?=url('new_user','showMobile',['id'=>$output['find']['id']])?>" class="btn"><span>更换手机</span></a>
            </td>
        </tr>

        <tr>
            <td class="required">
                <label for="seller_card_num">客户称呼:</label>
            </td>
            <td class="vatop rowform">
                <?=$output['find']['call'];?>
            </td>
        </tr>
        <tr>
            <td class="required">
                <label for="seller_bank_addr">常用地址:</label>
            </td>
            <td class="vatop rowform">
                <?=$output['find']['province'];?>
                <?=$output['find']['city'];?>
                <?=$output['find']['address']?>
            </td>
        </tr>
        <tr>
            <td class="required"><label for="seller_bank_account">账号状态:</label></td>
            <td class="vatop rowform">
                <?php if (empty($output['find']['status'])){ ?>
                <span class="red">（无效）</span><a class="btn" onclick="setStatus(<?=$output['find']['id']?>,1)"><span>恢复</span></a>
                <?php }else{ ?>
                    <span>有效</span> <a class="btn" onclick="setStatus(<?=$output['find']['id']?>,0)"><span>无效</span></a>
                <?php } ?>
                <!--  登录冻结     -->
                <?php
                $loginDj = $output['djMobile'];
                if($loginDj['status'] ==1) {
                ?>
                    <font class="red">
                        （登录冻结
                        <?php

                             echo '<span class="login_countdown"><span>0</span> : <span>0</span> : <span>0</span></span>';

                        ?>
                        ）
                    </font>
                    <a href="javascript:;" class="btn" onclick="setDj('<?= $loginDj['id']; ?>',0,'dj')"><span>设为允许</span></a>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td class="required"><label for="seller_bank_account">邮箱:</label></td>
            <td class="vatop rowform">
                <?=$output['find']['email']; ?>
                <?=isFreeze($user_id,$output['find']['email']) ? '<font class="red">（免扰）</font>' : '';?>
                <?php if($output['email']['status'] ==1) { ?>
                    <font class="red">
                        找密冻结  <span class="countdown"> <span>0</span> : <span>0</span>  </span> ）
                    </font>
                    <a href="javascript:;" class="btn" onclick="setDj('<?= $output['email']['id']; ?>',0,'pwd_dj')"><span>解冻</span></a>
                    <?php } ?>
            </td>
        </tr>
        <tr>
            <td class="required"><label for="seller_bank_account">头像:</label></td>
            <td class="vatop rowform">
                <?php
                echo empty($output['find']['photo']) ? "暂无" : '<a href="'.getImgidToImgurl($output['find']['photo']).'" target="_blank" />查看</a>';
                ?>
            </td>
        </tr>
        <?php
        if($output['bank']['is_verify'] ==1){
        ?>
        <tr>
            <td class="required" colspan="2" style="background:#0A8CD2; color: #ffffff; margin-left: 5px;"> | <b>资金账户信息</b></td>
        </tr>
        <tr>
            <td class="required"><label for="seller_bank_account">银行账户:</label></td>
            <td class="vatop rowform">
                <font class="red">审核通过</font>
                <a href="<?=url('new_user','bank',['id'=>$output['find']['id']])?>" >查看审核信息</a>
            </td>
        </tr>
        <tr>
            <td class="required">开户行:</td>
            <td class="vatop rowform">
                (<?=$output['bank']['province'];?><?=$output['bank']['city'];?>)<?=$output['bank']['bank_address'];?>
            </td>
        </tr>
        <tr>
            <td class="required"><label for="seller_bank_account">账号:</label></td>
            <td class="vatop rowform"><?=$output['bank']['bank_code']; ?></td>
        </tr>
        <tr>
            <td width="required">
                <label for="seller_phone">户名:</label>
            </td>
            <td class="vatop rowform"><?=$output['find']['real_name'];?></td>
        </tr>
        <?php } ?>
        <tr>
            <td class="required" colspan="2" style="background:#0A8CD2; color: #ffffff; margin-left: 5px;"> | <b>会员登录信息</b></td>
        </tr>
        <tr>
            <td class="required">
                <label for="seller_phone">注册时间:</label>
            </td>
            <td class="vatop rowform">
                <?=$output['find']['created_at']?>
            </td>
        </tr>
        <tr>
            <td class="required">
                <label for="seller_phone">登录次数:</label>
            </td>
            <td class="vatop rowform">
                <?=$output['find']['login_num']?>
            </td>
        </tr>
        <tr>
            <td class="required">
                <label for="seller_phone">最后登录时间:</label>
            </td>
            <td class="vatop rowform">
                <?=$output['find']['updated_at']?>
            </td>
        </tr>
        <tr>
            <td class="required">
                <a class="btn" style="background: #f1c40f;" href="javascript:;" onclick="passwordReset(<?=$output['find']['photo']?>)"><span style="background: #f1c40f;">重置密码</span></a>
            </td>
            <td class="vatop rowform">
                <a class="btn" href="<?=url('new_user','list')?>"><span>返 回</span></a>
            </td>
        </tr>
        </tbody>
        <input type="hidden" id="user_id" value="<?=$output['find']['id']?>" />
    </table>
</div>

<script>
    var update_status_url = "<?=url('new_user','updateStatus')?>";
    var dj_user_url = "<?=url('new_user','freeze')?>";
</script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/diy_validate.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/time.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/User/edit.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/jquery.countdown.js" charset="utf-8"></script>

<script>
    $(function() {
        $(".countdown").CountDown({
            format: "mm:ss",
            startTime:'<?=get_now2()?>',
            endTime:'<?=date('Y-m-d H:i:s', strtotime($output['email']['updated_at']) + $output['email']['validity_time'])?>',
            timekeeping: 'countdown'
        });

        $(".mobile-countdown").CountDown({
            startTime:'<?=get_now2()?>',
            endTime:'<?=date('Y-m-d H:i:s', strtotime($output['mobile']['updated_at']) + $output['mobile']['validity_time'])?>',
            timekeeping: 'countdown'
        })
        $(".login_countdown").CountDown({
            format: "hh:mm",
            startTime:'<?=get_now2()?>',
            endTime:'<?=date('Y-m-d H:i:s', strtotime($output['djMobile']['updated_at']) + $output['djMobile']['validity_time'])?>',
            timekeeping: 'countdown'
        });

    });
</script>