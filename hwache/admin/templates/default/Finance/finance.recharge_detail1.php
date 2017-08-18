<?php defined('InHG') or exit('Access Invalid!');?>
    <div class="page finance">
        <div class="fixed-bar">
            <div class="item-title">
                <h3>客户财务</h3>
                <ul class="tab-base">
                    <li><a href="index.php?act=finance&op=index"><span>客户财务</span></a></li>
                    <li><a href="index.php?act=finance&op=recharge_index&recharge_method=1"><span>转入-线上支付</span></a>▷</li>
                    <li><a href="javascript:void(0);" class="current"><span>详情</span></a></li>
                </ul>
            </div>
        </div>
        <div class="fixed-empty"></div>
        <div class="info">
            <div class="span4">
                <span class="label">转入编号：</span>
                <span class="val"><?php echo $output['recharge']['ur_id'] ;?></span>
            </div>
            <div class="span4">
                <span class="label">提交时间：</span>
                <span class="val"><?php echo $output['recharge']['created_at'] ;?></span>
            </div>
            <div class="span4">
                <span class="label">状态：</span>
                <span class="val"><?php echo show_recharge_status($output['recharge']['status'] , 1) ;?>
                     </span>
            </div>

        </div>
        <div class="info">
            <div class="span4">
                <span class="label">客户会员号：</span>
                <span class="val"><?php echo $output['recharge']['user_id'] ;?></span>
            </div>
            <div class="span4">
                <span class="label">客户姓名（称呼）：</span>
                <span class="val"><?php echo $output['recharge']['user']['last_name'].$output['recharge']['user']['first_name']."(".show_sex($output['recharge']['user']['sex']).")" ;?></span>
            </div>
            <div class="span4">
                <span class="label">客户手机号：</span>
                <span class="val"><?php echo $output['recharge']['user']['phone'] ;?></span>
            </div>

        </div>
        <div class="info">
            <div class="span4">
                <span class="label">提交金额：</span>
                <span class="val"><?php echo '￥'.$output['recharge']['money'] ;?></span>
            </div>
            <div class="span4">
                <span class="label">提交用途：</span>
                <span class="val"><?php echo show_recharge_use_type($output['recharge']['use_type']);
                    if($output['recharge']['use_type']>0) echo '(订单号:'.$output['recharge']['order_id'].')'
                ?></span>
            </div>
            <div class="span4">
                <span class="label">支付方式：</span>
                <span class="val"><?php echo show_recharge_type($output['recharge']['recharge_type']) ;?></span>
            </div>
        </div>
        <div class="clear"></div>
        <div class="big_title">
            入账
        </div>
        <div class="clear"></div>

        <div class="info">
            <div class="span4">
                <span class="label">入账金额：</span>
                <span class="val"><?php echo '￥'.$output['recharge']['recharge_money'] ;?></span>
            </div>
            <div class="span4">
                <span class="label">转入用途：</span>
                <span class="val"><?php echo show_recharge_use_type($output['recharge']['use_type']);
                    if($output['recharge']['use_type']>0) echo '(订单号:'.$output['recharge']['order_id'].')'
                    ?></span>
            </div>
            <div class="span4">
                <span class="label">支付方式：</span>
                <span class="val"><?php echo show_recharge_type($output['recharge']['recharge_type']) ;?></span>
            </div>
        </div>
        <div class="info">
            <div class="span4">
                <span class="label">转入方账户：</span>
                <span class="val"><?php echo $output['recharge']['alipay_user_name'] ;?></span>
            </div>
            <div class="span4">
                <span class="label">确认入账时间：</span>
                <span class="val"><?php echo $output['recharge']['recharge_confirm_at']; ?></span>
            </div>
            <div class="span4">
                <span class="label">入账流水号：</span>
                <span class="val"><?php echo $output['recharge']['trade_no'] ;?></span>
            </div>
        </div>
        <div class="info">
            <div class="span4">
                <span class="label">备注：</span>
                <span class="val"><?php if( $output['recharge'] == 4) echo $output['recharge']['remark'] ;?></span>
            </div>
        </div>

        <div class="info footer">
            <a href="javascript:history.go(-1);" class="button">返回</a>
        </div>
    </div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<script type="text/javascript">
    $(function(){
        $('#recharge_confirm_at').datepicker({dateFormat: 'yy-mm-dd'});
        disable_ruzhang();
        $('#recharge_operation_yes').change(function(){
            let val = $(this).prop('checked');
            console.log(val);
            if(val)
            {
                $('#recharge_operation_no').prop('checked',false);
                enable_ruzhang();
            }else
            {
                disable_ruzhang();
            }
        });
        $('#recharge_operation_no').change(function(){
            let val = $(this).prop('checked');
            console.log(val);
            if(val)
            {
                $('#recharge_operation_yes').prop('checked',false);
                disable_ruzhang();
            }
        })
    });

    //已入账表单未勾选
    function disable_ruzhang()
    {
        $('#transfer_to_account').attr('disabled','disabled');
        $('#recharge_money').attr('disabled','disabled');
        $('#transfer_to_order').attr('disabled','disabled');
        $('#recharge_confirm_at').attr('disabled','disabled');
    }

    //已入账勾选
    function enable_ruzhang() {
//        $('#transfer_to_account').removeAttr('disabled');
        $('#recharge_money').removeAttr('disabled');
//        $('#transfer_to_order').removeAttr('disabled');
        $('#recharge_confirm_at').removeAttr('disabled');
    }
</script>
