<?php defined('InHG') or exit('Access Invalid!');?>
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<!-- 新 Bootstrap 核心 CSS 文件 -->
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<!--<script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>-->
<input type="hidden" name="admin_id" id="member_id" value="<?=$output['admin']['id'];?>">
<input type="hidden" name="baojia_id" id="baojia_id" value="<?=$_GET['bj_id'];?>">
<input type="hidden" name="baojia_public_status" id="baojia_public_status" value="<?=$output['baojia']['bj_is_public'];?>">

<table class="table baojia-intro">
    <tr>
        <td><?=$lang['goods_system_bj_number'];?></td>
        <td><?=$output['baojia']['bj_id'];?><a onclick="show_baojia_info(<?=$output['baojia']['bj_id'];?>,<?=$output['baojia']['bj_serial'];?>)" style="text-decoration:underline;float:right;margin-right:100px;" href="javascript:void(0)"><?=$lang['goods_bj_show'];?></a></td>
        <td><?=$lang['goods_dealer_bj_number'];?></td>
        <td><?=$output['baojia']['bj_serial'];?></td>
        <td><?=$lang['goods_bj_status'];?></td>
        <td><?=$output['baojia']['bj_status'];?>
            <?php
            if(in_array($output['baojia']['bj_status'],array('暂时下架', '失效报价'))){
            echo '('.$output['baojia']['bj_reason'].')';
            };?>
        </td>
    </tr>
    <tr>
        <td><?=$lang['common_name'];?></td>
        <td><?=$output['member']['member_name'];?>&nbsp;&nbsp;<a style="text-decoration:underline;float:right;margin-right:100px;" href="index.php?act=seller&op=view&id=<?=$output['seller']['seller_id']?>"><?=$lang['goods_dealer_show'];?></a></td>
        <td><?=$lang['common_dealer_name'];?></td>
        <td><?=$output['baojia']['dealer_name']; ?></td>
        <td><?=$lang['common_area'];?></td>
        <td><?=str_replace("	",'',$output['dealer']['d_areainfo']);?></td>
    </tr>
    <tr>
        <td><?=$lang['goods_bj_car_model'];?></td>
        <td colspan="3"><?=$output['baojia']['gc_name']; ?></td>
        <td><?=$lang['goods_is_xianche'];?></td>
        <td><?=$output['baojia']['bj_is_xianche']?'现车':'非现车';?></td>
    </tr>
    <tr>
        <td><?=$lang['goods_guide_price'];?></td>
        <td class="price">￥<?=$output['zhidaojia']; ?></td>
        <td><?=$lang['goods_sale_price'];?></td>
        <td colspan="3" class="price">￥<?=$output['price']['bj_lckp_price']; ?> </td>
    </tr>
    <tr>
        <td><?=$lang['goods_service_fee'];?></td>
        <td class="price">￥<?=$output['price']['bj_agent_service_price']; ?></td>
        <td><?=$lang['goods_doposit_price'];?></td>
        <td class="price">￥<?=$output['price']['bj_doposit_price'];?></td>
        <td><?=$lang['goods_car_license_fee'];?></td>
        <td class="price"><?=$output['baojia']['bj_step']>5?($output['price']['bj_license_plate_break_contract']>0?'有此要求':'无此要求'):'';?></td>
    </tr>
    </table>
<?php if($output['baojia']['bj_status'] !='新建未完'):?>
    <hr style="margin:0 auto;border-top:8px solid #75ccef"/>
    <table class="table baojia-intro">
    <tr>
        <td><?=$lang['goods_bj_public_status'];?></td>
        <td><?=$output['baojia']['bj_is_public']?($output['baojia']['bj_is_public']==1?'正常':'异常'):'无'?></td>
        <td><?=$lang['goods_car_source'];?></td>
        <td colspan="3"><?=$output['scope'];?></td>
    </tr>
    <tr>
        <td><?=$lang['goods_hc_car_price'];?></td>
        <td><b style="color:red">￥<span class="price"><?=$output['hc_price']['hcPrice'];?></span>(<span class="price"><?=$output['hc_price']['hcMinPrice'];?></span>~￥<span class="price"><?=$output['hc_price']['hcMaxPrice'];?></span>)</b></td>
        <td><?=$lang['goods_hc_service_fee'];?></td>
        <td colspan="3"><b style="color:red">￥<span class="price"><?=$output['hc_price']['hcServerPrice'];?></span></b></td>
    </tr>
    <tr>
        <td><?=$lang['goods_hc_gross_profit'];?></td>
        <td><b style="color:red">￥<span class="price"><?=$output['hc_price']['hcProfit'];?></span></b></td>
        <td><?=$lang['goods_hc_deposit'];?></td>
        <td colspan="3"><b style="color:red">￥<span class="price"><?=$output['hc_price']['minSponsion'];?></span>/￥<span class="price"><?=$output['hc_price']['maxSponsion'];?></span></b></td>
    </tr>
    <tr>
        <td><?=$lang['goods_reason_remark'];?></td>
        <td colspan="5"><?=count($output['baojia_public_list'])?'':'无';?> </td>
    </tr>
</table>
<?php endif;?>

<?php if(count($output['baojia_public_list'])>0):?>
<table class="table table-bordered">
    <tr>
        <td class="text-center"><?=$lang['goods_reason_number'];?></td>
        <td><?=$lang['goods_reason_content'];?></td>
        <td class="text-center"><?=$lang['goods_reason_public_status'];?></td>
        <td class="text-center"><?=$lang['goods_reason_remark_user'];?></td>
        <td class="text-center"><?=$lang['goods_reason_remark_time'];?></td>
    </tr>
    <?php foreach($output['baojia_public_list'] as $p):?>
    <tr>
        <td class="text-center"><?=$p['id'];?></td>
        <td><?= nl2br($p['remark']);?></td>
        <td class="text-center"><?=$p['status']==1?'正常':'异常';?></td>
        <td class="text-center"><?=$p['admin_name'];?></td>
        <td class="text-center"><?=$p['created_at'];?></td>
    </tr>
    <?php endforeach;?>
</table>
<?php endif;?>

<div id="hc_price_form" style="border: 1px solid #ccc;width: 500px;min-height: 120px;position:absolute;top:15%;left:30%;display:none;">
    <div style="background-color: #CFEEF0;"><span>温馨提示</span></div>
    <div style="padding: 20px 20px;background:white;">
        <dl class="dl-horizontal">
            <dt>华车车价范围：</dt>
            <dd>￥<span class="price"><?=$output['hc_price']['hcMinPrice'];?></span>~￥<span class="price"><?=$output['hc_price']['hcMaxPrice'];?></span>
                <input type="hidden" name="hcMinPrice" id="hcMinPrice" value="<?=$output['hc_price']['hcMinPrice'];?>">
                <input type="hidden" name="hcMaxPrice" id="hcMaxPrice" value="<?=$output['hc_price']['hcMaxPrice'];?>">
                <input type="hidden" name="flag" id="flag" value="<?=$output['hc_price']['hcPrice']?1:0;?>">
            </dd>
            <dt>设置华车车价：</dt>
            <dd>
                ￥<input type="text" style="width:80px" name="hc_price" id="hc_price" value="<?=preg_replace('/\B(?=(?:\d{3})+\b)/',',',$output['hc_price']['hcPrice']);?>">
                <span id="price_format_error" style="color: red;display:none">价格不在可设置范围内</span>
                <span id="price_error" style="color: red;display:none">请输入100的整数倍</span>
            </dd>
            <br/>
            <dd>
                <button type="button" class="btn btn-warning" onclick="set_hc_price()"><?=$lang['goods_confirm_button'];?></button>
                <button type="button" class="btn btn-warning" onclick="hide_form()"><?=$lang['goods_return_button'];?></button>
            </dd>
        </dl>
    </div>
</div>

<div class="text-center">
    <?php if(in_array($output['baojia']['bj_status'], array('暂时下架', '正在报价', '等待生效'))):?>
    <button type="button" id="baojia_public" class="btn btn-primary"><?=$lang['goods_public_button'];?></button>
    <?php endif;?>
    <?php if($output['baojia']['bj_is_pass']==0 && in_array($output['baojia']['bj_status'],array('暂时下架', '正在报价', '等待生效'))):?>
    <button type="button" id="baojia_checked_return" class="btn btn-warning"><?=$lang['goods_checked_return_button'];?></button>
    <?php endif;?>
    <button type="button" id="baojia_return" class="btn btn-warning"><?=$lang['goods_return_button'];?></button>
    <?php if(in_array($output['baojia']['bj_status'],array('暂时下架', '正在报价', '等待生效'))):?>
    <button type="button" id="baojia_checked" class="btn btn-success"><?=$output['baojia']['bj_is_public']==1?$lang['goods_check_button']:$lang['goods_check_button2'];?></button>
    <?php endif;?>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script>
    $(function(){
        $(".baojia-intro td").css("border","none");

        $(".price").each(function(){
            var h = $(this).html().replace(/\B(?=(?:\d{3})+\b)/g, ',');
            $(this).html(h);
        })

        //发布车价调整
        $("#baojia_public").click(function(){
            $("#hc_price_form").show();
        })

        //审核报价并返回
        $("#baojia_checked_return").click(function(){
            layer.confirm('确定已审核同意当前报价的发布？', {
                skin: 'layui-layer-molv',
                btn: ['确定','返回'] //按钮
            }, function(){
                layer.close();

                //ajax提交数据
                baojia_pass_op(1)

                window.history.go(-1);
            }, function(){
                layer.close();
            });
        })

        //返回报价上一页
        $("#baojia_return").click(function(){
            window.history.go(-1);
        })

        //审核报价
        var status = $("#baojia_public_status").val();
        var submit_status=status==1?2:1;
        var status_str = status==1?'异常':'正常';
        $("#baojia_checked").click(function(){
            layer.prompt({title: '确定将此报价发布状态设为发布审核'+status_str+'吗？', formType: 2}, function(reason, index){
                layer.close(index);
                
                //记录报价发布的原因
                baojia_public_op(submit_status,reason);

                setTimeout(function(){
                    window.location.reload();
                },200);
            });
        })

        //校验华车车价
        $("#hc_price").focus(function(){
            var hp = $(this).val().replace(",","");
            $(this).val(parseFloat(hp));
        }).blur(function(){
            var hc_price = $(this).val().replace(",","");
            var hcMinPrice = $("#hcMinPrice").val();
            var hcMaxPrice = $("#hcMaxPrice").val();

            $(this).siblings("span").hide();
            var flag=1;
            if(parseFloat(hc_price)%100>0){
                $("#price_error").show();
                flag = 0;
            }

            if(parseFloat(hc_price)<hcMinPrice || parseFloat(hc_price)>hcMaxPrice){
                $("#price_format_error").show();
                flag = 0;
            }
            var hp = hc_price.replace(/\B(?=(?:\d{3})+\b)/g, ',');
            $("#hc_price").val(hp+".00");

            $("#flag").val(flag);
        })

    })

    function baojia_public_op(status, remark){
        var baojia_id = $("#baojia_id").val();
        var member_id = $("#member_id").val();
        $.ajax({
            type: 'post',
            url:   '/index.php?act=goods&op=ajaxshenhe',
            data : {
                baojia_id:baojia_id,
                member_id:member_id,
                baojia_status:status,
                remark:remark
            },
            dataType: 'json',
            success : function(result){
                if(result.error_code){
                    layer.msg(result.error_msg);
                }
            }
        });
    }

    function baojia_pass_op()
    {
        var baojia_id = $("#baojia_id").val();
        $.ajax({
            type: 'post',
            url:   '/index.php?act=goods&op=ajaxpass',
            data : {
                baojia_id:baojia_id,
            },
            dataType: 'json',
            success : function(result){
                if(result.error_code){
                    layer.msg(result.error_msg);
                }
            }
        });
    }

    function show_baojia_info(bj_id,bj_serial)
    {
        layer.open({
            type: 2,
            title:  "您的位置：车型 > 报价管理 > 查看报价"+bj_serial,
            skin: "layui-layer-molv",
            shadeClose: true,
            shade: false,
            maxmin: true, //开启最大化最小化按钮
            area: ['1080px', '500px'],
            content: '/index.php?act=goods&op=show_bj_info&bj_id='+bj_id
        });
    }

    function set_hc_price()
    {
        var hc_price = $("#hc_price").val().replace(/,/g,"");
        var baojia_id = $("#baojia_id").val();
        var flag = $("#flag").val();
        if(hc_price && flag==1){
            $.ajax({
                type: 'post',
                url:   '/index.php?act=goods&op=ajaxsethcprice',
                data : {
                    baojia_id:baojia_id,
                    hc_price:hc_price,
                },
                dataType: 'json',
                success : function(result){
                    if(result.error_code){
                        layer.msg(result.error_msg);
                    }else{
                        window.location.reload();
                    }
                }
            });
        }
    }

    function hide_form(){
        $("#hc_price_form").hide();
    }
</script>