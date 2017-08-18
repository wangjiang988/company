<?php defined('InHG') or exit('Access Invalid!');?>
<style type="text/css">
    .table .required{width: 100px; margin: 0; padding: 0;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><?=$lang['index_title'];?></h3>
            <ul class="tab-base">
                <li><a href="<?=url('new_user','list')?>"><span>管理</span></a></li>
                <li><a href="JavaScript:void(0);" class="current"><span>编辑</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form id="reset_phone_form">
        <table class="table tb-type2">
            <tbody>
            <tr>
                <td class="required">
                    <label for="status">原手机号:</label>
                </td>
                <td class="vatop rowform">
                    <?=$output['find']['phone']?>
                </td>
            </tr>
            <tr class="noborder">
                <td class="required">
                    <label for="real_name">新手机号:</label>
                </td>
                <td class="vatop rowform">
                    <input type="text" name="new_mobile" id="phone" class="txt">
                    <a href="javascript:;" class="btn" onclick="sendSms()"><span>发送验证码</span></a>
                </td>
            </tr>

            <tr class="noborder">
                <td class="required">
                    <label for="real_name">手机验证码:</label>
                </td>
                <td class="vatop rowform">
                    <input type="text" name="code" id="code" class="txt">
                </td>
            </tr>

            </tbody>
            <tfoot>
            <tr class="tfoot">
                <td style="text-align: right;">
                    <input type="hidden" name="user_id" id="user_id" value="<?=$output['find']['id']?>" />
                    <a class="btn" onclick="subReset()" ><span><?=$lang['nc_submit']?></span></a>
                </td>
                <td>
                    <a href="<?=url('new_user','list')?>" class="btn"><span>返回</span></a>
                </td>
            </tr>
            </tfoot>
        </table>
    </form>
</div>
<script type="text/javascript">
    var send_sms_url = '<?php echo API_URL . '/sendTestSms';?>';
    //var check_phone_url =  '<?=url('new_user','checkUser')?>';
    var sub_reset_url = '<?=url('new_user','updatePhone')?>';
</script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/diy_validate.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/User/send.js" charset="utf-8"></script>