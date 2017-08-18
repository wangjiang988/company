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
<h4 style="color:#0000C2;font-size:14px">平台裁判</h4>
<hr/>
<p>判定结论：<?=$output['arbitrate']['arbitrate_result']==1?'裁判客户违约':($output['arbitrate']['arbitrate_result']==2?'裁判售方违约':'客户支付买车担保金违约');;?></p>
<br/>
<h4 style="color:#0000C2;font-size:14px">当前执行</h4>
<hr/>
<table class="table table-bordered">
    <tr>
        <td>客户</td>
        <td>售方</td>
        <td>平台</td>
    </tr>
    <tr>
        <td>买车担保金赔偿￥<?=number_format($output['arbitrate']['seller_deposit_from_userjxb']+$output['cousult']['hwache_deposit_from_userjxb'],2);?></td>
        <td>获得客户买车定金补偿￥<?=$output['arbitrate']['seller_deposit_from_userjxb'];?></td>
        <td>华车获得买车担保金补偿￥<?=$output['arbitrate']['hwache_deposit_from_userjxb'];?></td>
    </tr>
    <tr>
        <td>已退还可用余额￥<?=$output['arbitrate']['return_user_available_deposit_from_userjxb'];?></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>转付华车服务费￥<?=$output['arbitrate']['transfer_hwache_service_charge_from_userjxb'];?></td>
        <td>售方服务费实得￥<?=$output['arbitrate']['transfer_seller_service_charge_from_userjxb'];?></td>
        <td>获得华车服务费￥<?=$output['arbitrate']['transfer_hwache_service_charge_from_userjxb'];?></td>
    </tr>
    <tr>
        <td>获得歉意金N补偿￥<?=$output['arbitrate']['apology_money_from_sellerjxb'];?></td>
        <td>歉意金N赔偿￥<?=$output['arbitrate']['apology_money_from_sellerjxb'];?></td>
        <td></td>
    </tr>
    <tr>
        <td>获得客户买车担保金利息N补偿￥<?=$output['arbitrate']['user_deposit_interest_from_sellerjxb'];?></td>
        <td>客户买车担保金利息N赔偿￥<?=$output['arbitrate']['user_deposit_interest_from_sellerjxb'];?></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>已退还可提现余额￥<?=$output['arbitrate']['return_user_avaiable_from_sellerjxb'];?></td>
        <td></td>
    </tr>
    <tr>
        <td>获得客户买车其他损失补偿￥<?=$output['arbitrate']['user_damage'];?></td>
        <td>客户买车其他损失赔偿￥<?=$output['arbitrate']['user_damage'];?></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>华车平台损失赔偿￥<?=$output['arbitrate']['hwache_damage'];?></td>
        <td>华车损失补偿￥<?=$output['arbitrate']['hwache_damage'];?></td>
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