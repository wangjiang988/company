<?php defined('InHG') or exit('Access Invalid!');?>

<!-- 新 Bootstrap 核心 CSS 文件 -->
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<!--<script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>-->
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
<link rel="stylesheet" href="<?php echo ADMIN_TEMPLATES_URL;?>/css/font/font-awesome/css/font-awesome-ie7.min.css">
<![endif]-->
<style>
    hr {height:2px;border:none;border-top:1px solid #000000}
    .cut-off{border-right: solid 1px #000000}
    .title-weight {background-color: rgb(206, 239, 239);padding: 10px;}
    .title-weight font{color:#3590C7;font-size:14px}
</style>

<div class="row">
    <div class="col-sm-1"><a href="mailto:#">订单管理</a></div>
    <div class="col-sm-2">查看订单&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;查看工单</div>
</div>

<?php require_once("order.conciliation.base.php"); ?>
<h4 style="color:#0000C2;font-size:14px">协商终止订单确认</h4>
<hr/>
<table class="table table-bordered">
    <tr>
        <td>确认方</td>
        <td width="60%">确认内容</td>
        <td>确认时间</td>
    </tr>
    <tr>
        <td>客户</td>
        <td><?=$output['user_consult']['content'];?></td>
        <td><?=$output['user_consult']['updated_at']=='0000-00-00 00:00:00'?'':$output['user_consult']['updated_at'];?></td>
    </tr>
    <tr>
        <td>售方</td>
        <td><?=$output['seller_consult']['content'];?></td>
        <td><?=$output['seller_consult']['updated_at']=='0000-00-00 00:00:00'?'':$output['seller_consult']['updated_at'];?></td>
    </tr>
</table>

<h4 style="color:#0000C2;font-size:14px">当前执行</h4>
<hr/>
<table class="table table-bordered">
    <tr>
        <td>客户</td>
        <td>售方</td>
        <td>平台</td>
    </tr>
    <tr>
        <td>买车担保金赔偿￥<?=number_format($output['consult']['seller_deposit_from_userjxb']+$output['cousult']['hwache_deposit_from_userjxb'],2);?></td>
        <td>获得客户买车定金补偿￥<?=$output['consult']['seller_deposit_from_userjxb'];?></td>
        <td>华车获得买车担保金补偿￥<?=$output['consult']['hwache_deposit_from_userjxb'];?></td>
    </tr>
    <tr>
        <td>已退还可用余额￥<?=$output['consult']['return_user_available_deposit_from_userjxb'];?></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>转付华车服务费￥<?=$output['consult']['transfer_hwache_service_charge_from_userjxb'];?></td>
        <td>售方服务费实得￥<?=$output['consult']['transfer_seller_service_charge_from_userjxb'];?></td>
        <td>获得华车服务费￥<?=$output['consult']['transfer_hwache_service_charge_from_userjxb'];?></td>
    </tr>
    <tr>
        <td>获得歉意金N补偿￥<?=$output['consult']['apology_money_from_sellerjxb'];?></td>
        <td>歉意金N赔偿￥<?=$output['consult']['apology_money_from_sellerjxb'];?></td>
        <td></td>
    </tr>
    <tr>
        <td>获得客户买车担保金利息N补偿￥<?=$output['consult']['user_deposit_interest_from_sellerjxb'];?></td>
        <td>客户买车担保金利息N赔偿￥<?=$output['consult']['user_deposit_interest_from_sellerjxb'];?></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>已退还可提现余额￥<?=$output['consult']['return_user_avaiable_from_sellerjxb'];?></td>
        <td></td>
    </tr>
    <tr>
        <td>获得客户买车其他损失补偿￥<?=$output['consult']['user_damage'];?></td>
        <td>客户买车其他损失赔偿￥<?=$output['consult']['user_damage'];?></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>华车平台损失赔偿￥<?=$output['consult']['hwache_damage'];?></td>
        <td>华车损失补偿￥<?=$output['consult']['hwache_damage'];?></td>
    </tr>
</table>
<div class="form-inline text-center">
    <a href="/index.php?act=order&op=show_order&id=<?=$output['conciliation']['order_id'];?>"><button type="button" class="btn btn-default">返回订单</button></a>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script>
    $(function(){
        $(".order-intro td").css("border","none");
    })
</script>