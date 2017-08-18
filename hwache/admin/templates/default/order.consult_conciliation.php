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
<h4 class="title-weight"><font>协商终止处理方案</font></h4>
<form method="post" action="">
    <input type="hidden" name="query" value="1">
    <div class="row">
        <div class="col-sm-2">客户加信宝：￥<span id="user_jxb"><?=$output['order']['user_freeze_jxb'];?></span></div>
        <div class="col-sm-2">已选合计：￥<span id="user_indemnity">0.00</span></div>
    </div>
    <hr/>
    <div class="col-sm-3 cut-off">
        <div class="form-group">
            <label>赔偿售方</label>
            <div class="form-inline">
                <label><input type="checkbox" name="selectDamage" value="1">买车担保金赔偿￥</label>
                <input type="text"  class="form-control check_user_price" high_price="<?=$output['baojia_price']['client_hand_price'];?>" name="seller_deposit_from_userjxb" placeholder="">
            </div>
        </div>
    </div>
    <div class="col-sm-3 cut-off">
        <div class="form-group">
            <label>赔偿平台</label>
            <div class="form-inline">
                <label><input type="checkbox" name="selectDamage" value="1">买车担保金赔偿￥</label>
                <input type="text"  class="form-control check_user_price"  high_price="<?=$output['order']['sponsion_price']-$output['baojia_price']['client_hand_price'];?>" name="hwache_deposit_from_userjxb" placeholder="">
            </div>
        </div>
    </div>
    <div class="col-sm-3 cut-off">
        <div class="form-group">
            <label>退回客户</label>
            <div class="form-inline">
                <label><input type="checkbox" name="selectDamage" value="1">退还可用余额￥</label>
                <input type="text"  class="form-control check_user_price" name="return_user_available_deposit_from_userjxb" placeholder="">
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <label >转付</label>
            <div class="form-inline">
                <label><input type="checkbox" name="selectDamage" value="1">转付华车服务费￥</label>
                <input type="text"  class="form-control check_user_price" high_price="<?=$output['baojia_price']['hwache_service_price'];?>" name="transfer_hwache_service_charge_from_userjxb" placeholder="">
            </div>
            <div class="form-inline">
                <label>含售方服务费￥</label>
                <input type="text"  class="form-control check_user_price" high_price="<?=$output['baojia_price']['agent_service_price'];?>" name="transfer_seller_service_charge_from_userjxb" placeholder="">
            </div>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-2">售方加信宝：￥<span id="seller_jxb"><?=$output['order']['seller_freeze_jxb'];?></span></div>
        <div class="col-sm-2">已选合计：￥<span id="seller_indemnity">0.00</span></div>
    </div>
    <hr/>
    <div class="col-sm-6 cut-off">
        <div class="form-group">
            <label >赔偿客户</label>
            <div class="form-inline">
                <label><input type="checkbox" name="selectDamage" value="1">歉意金N赔偿￥</label>
                <input type="text"  class="form-control check_seller_price" name="apology_money_from_sellerjxb" placeholder="0~499.00">
            </div>
            <div class="form-inline">
                <label><input type="checkbox" name="selectDamage" value="1">客户买车担保金利息N赔偿￥</label>
                <input type="text"  class="form-control check_seller_price" name="user_deposit_interest_from_sellerjxb" placeholder="">
            </div>
        </div>
    </div>
    <div class="col-sm-6 cut-off">
        <div class="form-group">
            <label>退回售方</label>
            <div class="form-inline">
                <label><input type="checkbox" name="selectDamage" value="1">退还可提现余额￥</label>
                <input type="text"  class="form-control check_seller_price" name="return_user_avaiable_from_sellerjxb" placeholder="">
            </div>
        </div>
    </div>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <div class="row">
        <div class="col-sm-2">售方可提现余额：￥<?=$output['seller_account']['avaliable_deposit'];?></div>
        <div class="col-sm-2">已选合计：￥<span id="seller_deposit_indemnity">0.00</span></div>
    </div>
    <hr/>
    <div class="col-sm-6 cut-off">
        <div class="form-group">
            <label>赔偿客户</label>
            <div class="form-inline">
                <label><input type="checkbox" name="selectDamage" value="1">客户买车其他损失赔偿￥</label>
                <input type="text"  class="form-control check_seller_deposit" high_price="10000" name="user_damage" placeholder="0~10,000.00">
            </div>
        </div>
    </div>
    <div class="col-sm-6 cut-off">
        <div class="form-group">
            <label> 赔偿平台</label>
            <div class="form-inline">
                <label><input type="checkbox" name="selectDamage" value="1">华车平台损失赔偿￥</label>
                <input type="text"  class="form-control check_seller_deposit" high_price="2147483647" name="hwache_damage" placeholder="">
            </div>
        </div>
    </div>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <div class="form-inline text-center">
        <button type="button" class="btn btn-warning" onclick="submit_consult()">提交</button>
        <button type="button" class="btn btn-default" onclick="javascript:window.history.go(-1)">返回</button>
    </div>
</form>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script>
    $(function(){
        $(".order-intro td").css("border","none");

        //检查赔偿售方买车担保金
        $(".check_user_price").change(function(){
            var input_value = parseInt($(this).val());
            var high_price = $(this).attr("high_price");
            if(isNaN(input_value) || input_value<0 || input_value > high_price){
                layer.msg('金额填写范围错误');
            }else{
                var total_price =  0;
                if($("input[name=seller_deposit_from_userjxb]").val()){
                    total_price += parseInt($("input[name=seller_deposit_from_userjxb]").val());
                }
                if($("input[name=hwache_deposit_from_userjxb]").val()){
                    total_price += parseInt($("input[name=hwache_deposit_from_userjxb]").val());
                }

                if($("input[name=return_user_available_deposit_from_userjxb]").val()){
                    total_price += parseInt($("input[name=return_user_available_deposit_from_userjxb]").val());
                }


                if($("input[name=transfer_hwache_service_charge_from_userjxb]").val()){
                    total_price += parseInt($("input[name=transfer_hwache_service_charge_from_userjxb]").val());
                }

                $("#user_indemnity").html(total_price.toFixed(2));
            }
        })

        //检查赔偿客户买车担保金
        $(".check_seller_price").change(function(){
            var total_price =  0;
            if($("input[name=apology_money_from_sellerjxb]").val()){
                total_price += parseInt($("input[name=apology_money_from_sellerjxb]").val());
            }
            if($("input[name=user_deposit_interest_from_sellerjxb]").val()){
                total_price += parseInt($("input[name=user_deposit_interest_from_sellerjxb]").val());
            }

            if($("input[name=return_user_avaiable_from_sellerjxb]").val()){
                total_price += parseInt($("input[name=return_user_avaiable_from_sellerjxb]").val());
            }

            $("#seller_indemnity").html(total_price.toFixed(2));
        })

        //检查赔偿售方买车担保金
        $(".check_seller_deposit").change(function(){
            var input_value = parseInt($(this).val());
            var high_price = $(this).attr("high_price");
            if(isNaN(input_value) || input_value<0 || input_value > high_price){
                layer.msg('金额填写范围错误');
            }else{
                var total_price =  0;
                if($("input[name=user_damage]").val()){
                    total_price += parseInt($("input[name=user_damage]").val());
                }
                if($("input[name=hwache_damage]").val()){
                    total_price += parseInt($("input[name=hwache_damage]").val());
                }

                $("#seller_deposit_indemnity").html(total_price.toFixed(2));
            }
        })
    })

    function submit_consult()
    {
        // 检查赔偿售方担保金范围
        var seller_deposit_from_userjxb = parseInt($("input[name=seller_deposit_from_userjxb]").val());
        var high_price = parseInt($("input[name=seller_deposit_from_userjxb]").attr("high_price"));
        if(isNaN(seller_deposit_from_userjxb)||seller_deposit_from_userjxb<0 || seller_deposit_from_userjxb>high_price){
            layer.msg("赔偿售方买车担保金错误");
            return false;
        }

        // 检查赔偿平台担保金范围
        var hwache_deposit_from_userjxb = parseInt($("input[name=hwache_deposit_from_userjxb]").val());
        var high_price = parseInt($("input[name=hwache_deposit_from_userjxb]").attr("high_price"));
        if(isNaN(hwache_deposit_from_userjxb)|| hwache_deposit_from_userjxb<0 || hwache_deposit_from_userjxb>high_price){
            layer.msg("赔偿平台买车担保金错误");
            return false;
        }

        //检查转付华车服务费范围
        var transfer_hwache_service_charge_from_userjxb = parseInt($("input[name=transfer_hwache_service_charge_from_userjxb]").val());
        var high_price = parseInt($("input[name=transfer_hwache_service_charge_from_userjxb]").attr("high_price"));
        if(isNaN(transfer_hwache_service_charge_from_userjxb)|| transfer_hwache_service_charge_from_userjxb<0 || transfer_hwache_service_charge_from_userjxb>high_price){
            layer.msg("转付华车服务费错误");
            return false;
        }

        //检查转付售方服务费范围
        var transfer_seller_service_charge_from_userjxb = parseInt($("input[name=transfer_seller_service_charge_from_userjxb]").val());
        var high_price = parseInt($("input[name=transfer_seller_service_charge_from_userjxb]").attr("high_price"));
        if(isNaN(transfer_seller_service_charge_from_userjxb)||transfer_seller_service_charge_from_userjxb<0 || transfer_seller_service_charge_from_userjxb>high_price){
            layer.msg("转付售方服务费错误");
            return false;
        }

        //检查客户加信宝已选合计
        if(parseFloat($("#user_indemnity").html()) != parseFloat($("#user_jxb").html())){
            layer.msg("客户加信宝已选合计错误");
            return false;
        }

        //检查售方加信宝
        if(parseFloat($("#seller_indemnity").html()) != parseFloat($("#seller_jxb").html())){
            layer.msg("售方加信宝已选合计错误");
            return false;
        }

        //检查售方其他赔偿
        var user_damage = parseInt($("input[name=user_damage]").val());
        var high_price = parseInt($("input[name=user_damage]").attr("high_price"));
        if(isNaN(user_damage)||user_damage<0 || user_damage>high_price){
            layer.msg("客户买车其他损失赔偿错误");
            return false;
        }

        //提示用户确认下信息
        layer.confirm('确定向双方发出协商终止处理方案吗？',{
            btn:['确认','取消'],
            title:'发出方案确认'
        }, function(){
            $("form").submit();
        }, function(){
            layer.close();
        });
    }

</script>