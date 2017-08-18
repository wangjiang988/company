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
    <div class="col-sm-2">查看订单&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$output['conciliation']['admin_id']=$output['admin_info']['admin_id']?'处理工单':'查看工单'?></div>
</div>

<?php require_once("order.conciliation.base.php"); ?>

<?php if($output['consult']):?>
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
<?php endif;?>

<div class="form-inline text-center">
    <a href="/index.php?act=order&op=show_order&id=<?=$output['conciliation']['order_id'];?>"><button class="btn btn-default">返回订单</button></a>
    <?php if($output['admin_info']['dept_id']==$output['conciliation']['follow_depid'] && $output['conciliation']['status']==4):?>
        <button type="button" class="btn btn-default" onclick="giveup_consult_conciliation()">无法达成，换方案</button>
    <?php endif;?>
</div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script>
    $(function(){
        $(".order-intro td").css("border","none");
    })

    //接单
    function giveup_consult_conciliation(){
        //提示用户确认下信息
        layer.confirm('确定放弃本次协商方案吗？',{
            btn:['确认','取消'],
            title:'确定放弃协商方案'
        }, function(){
            $.ajax({
                type: 'get',
                url:   '/index.php?act=order&op=ajax_giveup_consult_conciliation&id=<?=$_GET['id'];?>',
                dataType: 'json',
                success : function(result){
                    if(result.error_code){
                        layer.msg(result.error_msg);
                    }else{
                        window.location.href= "/index.php?act=order&op=doing_conciliation&id=<?=$_GET['id'];?>"
                    }
                }
            });
        }, function(){
            layer.close();
        });


    }
</script>