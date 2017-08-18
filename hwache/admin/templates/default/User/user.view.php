<?php defined('InHG') or exit('Access Invalid!');?>
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
                <?php if($output['bank']['is_verify'] ==1) { ?><?=$output['find']['real_name'];?><?php } ?>
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
                    <font class="red">（找密冻结 <span class="mobile-countdown">  <span>0</span> : <span>0</span>  </span>）</font>
                    <?php
                }
                ?>
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
                <?php
                $phoneFreeze = $output['djMobile'];
                $status    = '有效';
                $statusStr = '（登录：允许）';
                if(!is_null($phoneFreeze)){
                    $status    = ($phoneFreeze['status']) ? '无效' : '有效';
                    $hour = '<span class="login_countdown">  <span>0</span> : <span>0</span> : <span>0</span>  </span>';
                    $statusStr = ($phoneFreeze['status']) ? '（登录冻结 '.$hour.'）' : '（登录：允许）';
                }
                echo '<span>'.$status.'</span> <font class="red">'.$statusStr.'</font></span>';
                ?>
            </td>
        </tr>
        <tr>
            <td class="required"><label for="seller_bank_account">邮箱:</label></td>
            <td class="vatop rowform">
                <?=$output['find']['email']; ?>
                <?=isFreeze($user_id,$output['find']['email']) ? '<font class="red">（免扰）</font>' : '';?>
                <?php if($output['email']['status'] ==1) { ?>
                    <font class="red">（找密冻结 <span class="countdown"> <span>0</span> : <span>0</span>  </span>）</font>
                <?php  } ?>
            </td>
        </tr>
        <tr>
            <td class="required"><label for="seller_bank_account">头像:</label></td>
            <td class="vatop rowform">
                <?php
                    echo empty($output['find']['img_url']) ? "暂无" : '<a href="'.$output['find']['img_url'].'" target="_blank" />查看</a>';
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

            </td>
        </tr>
        <tr>
            <td class="required">开户行:</td>
            <td class="vatop rowform">
               (<?= $output['bank']['province']; ?><?= $output['bank']['city']; ?>)<?= $output['bank']['bank_address']; ?>
            </td>
        </tr>
        <tr>
            <td class="required"><label for="seller_bank_account">账号:</label></td>
            <td class="vatop rowform"> <?=$output['bank']['bank_code']; ?></td>
        </tr>
        <tr>
            <td width="required">
                <label for="seller_phone">户名:</label>
            </td>
            <td class="vatop rowform">
               <?=$output['find']['real_name'];?>
            </td>
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
                <a class="btn" href="<?=url('new_user','edit',['id'=>$output['find']['id']])?>"><span>编 辑</span></a>
            </td>
            <td class="vatop rowform">
                <a class="btn" href="<?=url('new_user','list')?>"><span>返 回</span></a>
            </td>
        </tr>
        </tbody>
    </table>
</div>

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
        });
        $(".login_countdown").CountDown({
            startTime:'<?=get_now2()?>',
            endTime:'<?=date('Y-m-d H:i:s', strtotime($output['djMobile']['updated_at']) + $output['djMobile']['validity_time'])?>',
            timekeeping: 'countdown'
        });

    });
</script>