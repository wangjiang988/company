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
<h4 class="title-weight"><font>审核意见</font></h4>
<form method="post" action="">
    <input type="hidden" name="query" value="1">
    <div class="radio">
        <label class="col-sm-offset-1">
            <input type="radio" name="check_result"  value="1">
            同意，按裁判结论执行
        </label>
    </div>
    <div class="radio">
        <label class="col-sm-offset-1">
            <input type="radio" name="check_result"  value="2">
            不同意，理由如下：
        </label>
    </div>
    <div class="col-sm-offset-1 col-sm-6">
        <textarea class="form-control" rows="3" name="content"></textarea>
    </div>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <div class="form-inline text-center">
        <button type="button" class="btn btn-warning" onclick="submit_check_judge()">提交</button>
        <a href="/index.php?act=order&op=show_order&id=<?=$output['conciliation']['order_id'];?>"><button type="button" class="btn btn-default">返回订单</button></a>
    </div>
</form>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script>
    $(function(){
        $(".order-intro td").css("border","none");
    })

    function submit_check_judge()
    {
        //检查是否选择了判定结论
        var check_result = $("input[type=radio]:checked").val();
        if(check_result==undefined){
            layer.msg("请选择审核意见");
            return false;
        }

        if(check_result==2 && $("textarea").val()==""){
            layer.msg("请填写不同意理由");
            return false;
        }

        //提示用户确认下信息
        layer.confirm('确定提交裁判意见吗？',{
            btn:['确认','取消'],
            title:'裁判确认'
        }, function(){
            $("form").submit();
        }, function(){
            layer.close();
        });

    }


</script>