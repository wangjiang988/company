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

<h4 class="title-weight"><font>添加记录</font></h4>
<form class="form-horizontal" method="post" enctype="multipart/form-data" id="finish_conciliation" action="">
    <input type="hidden" name="query" value="1">
    <div class="form-group">
        <label class="col-sm-2 control-label">记录内容：</label>
        <div class="col-sm-10">
            <textarea class="form-control" rows="3" name="content"></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">证据：</label>
        <div class="col-sm-2">
            <input type="file" class="form-control" placeholder="点击上传" d-i="0" name="files[0]">
        </div>
        <div class="col-sm-2">
            <input type="file" class="form-control" placeholder="点击上传" d-i="1" name="files[1]">
        </div>
        <div class="col-sm-2">
            <input type="file" class="form-control" placeholder="点击上传" d-i="2" name="files[2]">
        </div>
        <div class="col-sm-2">
            <input type="file" class="form-control" placeholder="点击上传" d-i="3" name="files[3]">
        </div>
        <div class="col-sm-2">
            <input type="file" class="form-control" placeholder="点击上传" d-i="4" name="files[4]">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">跟进处理部门：</label>
        <div class="col-sm-10">
            <?php foreach($output['departments'] as $k=>$dep):?>
            <label class="radio-inline">
                <input type="radio" name="follow_depid"  value="<?=$k;?>"><?=$dep;?>
            </label>
            <?php endforeach;?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">当前处理人：</label>
        <div class="col-sm-10">
            <?=$output['departments'][$output['conciliation_receiver']['dept_id']].$output['conciliation_receiver']['admin_name'];?>
        </div>
    </div>
</form>
<br/>
<br/>

<div class="form-inline text-center">
    <button type="button" class="btn btn-default" onclick="return_conciliation()">不处理了，转单</button>
    <button type="button" class="btn btn-default" onclick="finish_conciliation()">已了结，关闭工单</button>
    <a href="/index.php?act=order&op=show_order&id=<?=$output['conciliation']['order_id'];?>"><button type="button" class="btn btn-default">返回订单</button></a>
    <a href="/index.php?act=order&op=consult_conciliation&id=<?=$output['conciliation']['id'];?>"><button type="button" class="btn btn-default">协商终止订单</button></a>
    <a href="/index.php?act=order&op=judge_conciliation&id=<?=$output['conciliation']['id'];?>"><button type="button" class="btn btn-default">裁判订单</button></a>
</div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script>
    $(function(){
        $(".order-intro td").css("border","none");

        //隐藏上传图片按钮
        $("input[type=file]:gt(0)").hide();

        $("input[type=file]").change(function(){
            var i =parseInt($(this).attr("d-i"))+2;
            $("input[type=file]:lt("+i+")").show();
        });
    })

    function finish_conciliation()
    {
        var content = $("textarea").val()
        if(content==""){
            layer.msg("请填写内容");
            return false;
        }

        var follow_depid = $("input[type=radio]:checked").val();
        if(follow_depid==""||follow_depid==undefined){
            layer.msg('请填写跟进部门');
            return false
        }

        //提示用户确认下信息
        layer.confirm('确定本工单已了结吗？',{
            btn:['确认','取消'],
            title:'确认关闭工单'
        }, function(){
            $("#finish_conciliation").submit();
        }, function(){
            layer.close();
        });
    }

    function return_conciliation()
    {
        var follow_depid = $("input[type=radio]:checked").val();
        if(follow_depid==""||follow_depid==undefined){
            layer.msg('请填写跟进部门');
            return false
        }

        //提示用户确认下信息
        layer.confirm('确定转给其他部门处理吗？',{
            btn:['确认','取消'],
            title:'确定转单'
        }, function(){
            $.ajax({
                type: 'post',
                url:   '/index.php?act=order&op=ajax_return_conciliation',
                data : {
                    id:<?=$_GET['id'];?>,
                    follow_depid:follow_depid
                },
                dataType: 'json',
                success : function(result){
                    if(result.error_code){
                        layer.msg(result.error_msg);
                    }
                }
            });

            //跳转订单详情页
            window.location.href="/index.php?act=order&op=show_order&id=<?=$output['conciliation']['order_id'];?>"

        }, function(){
            layer.close();
        });
    }
</script>