<?php defined('InHG') or exit('Access Invalid!');?>

<!-- 新 Bootstrap 核心 CSS 文件 -->
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<!--<script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>-->
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
<link rel="stylesheet" href="<?php echo ADMIN_TEMPLATES_URL;?>/css/font/font-awesome/css/font-awesome-ie7.min.css">
<![endif]-->
<link rel="stylesheet" href="<?php echo RESOURCE_SITE_URL;?>/js/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<style>
    hr {height:2px;border:none;border-top:1px solid #000000}
</style>

<div class="row">
    <div class="col-sm-1"><a href="mailto:#">订单管理</a></div>
    <div class="col-sm-2">查看订单</div>
    <div class="col-sm-1"></div>
    <div class="col-sm-2">查看工单</div>
</div>
<hr style="border-top:1px solid #0000C2"/>
<table class="table order-intro" >
    <tr>
        <td>订单号：<?=$output['order']['order_sn'];?></td>
        <td></td>
        <td><a href="/index.php?act=order&op=order_detail&tab=1&order_id=<?=$output['order']['id']?>" style="color:#FF8931">查看订单总详情</a></td>
        <td></td>
        <td>订单时间：</td>
        <td><?=$output['order']['created_at'];?></td>
        <td>结束时间：</td>
        <td><?=$output['order']['order_status']==99?$output['order']['updated_at']:'';?></td>
    </tr>
    <tr>
        <td>品牌/车系/车型 ：</td>
        <td colspan="5"><?=$output['baojia']['gc_name'];?></td>
        <td>基本配置</td>
        <td><a href="#">查看</a></td>
    </tr>
    <tr>
        <td>厂商指导价/整车型号/生产国别 / 座位数 / 排放标准：</td>
        <td colspan="7"><?='￥'.number_format($output['zhidaojia'],2).'/'.$output['car_goods_class']['vehicle_model'].'/'.$output['guobie'].'/'.$output['seat_num'].'/'.$output['paifang'];?></td>
    </tr>
    <tr>
        <td>是否现车 / 交车时限：</td>
        <td colspan="5"><?=$output['baojia']['bj_is_xianche']?'现车':'非现车'; ?>/<?=$output['appoint_car_time'];?></td>
        <td>车身颜色/内饰颜色</td>
        <td><?=$output['body_color'].'/'.$output['interior_color'];?></td>
    </tr>
</table>
<hr/>
<table class="table order-intro" >
    <tr>
        <td>售方用户名/姓名/手机号：</td>
        <td><?=$output['seller']['member_name'].'/'.$output['seller']['member_truename'].'/'.$output['seller']['member_mobile'];?></td>
        <td colspan="5">报价编号</td>
        <td><?=$output['baojia']['bj_serial'];?></td>
    </tr>
    <tr>
        <td>经销商/所属地区/交车地点：</td>
        <td colspan="7"><?=$output['dealer']['d_name'].'/'.$output['dealer']['d_areainfo'].$output['dealer']['d_jc_place'];?></td>
    </tr>
    <tr>
        <td>车辆开票价格/客户买车定金/售方服务费：</td>
        <td colspan="7"><?=$output['price']['car_price'].'/'.$output['price']['client_hand_price'].'/'.$output['price']['agent_service_price'];?></td>
    </tr>
    <tr>
        <td>售方当前状态：</td>
        <td colspan="7"><?=$output['order_progress_status']['seller_progress'];?></td>
    </tr>
</table>
<hr/>
<table class="table order-intro" >
    <tr>
        <td>客户会员号/姓名/称呼/手机号：</td>
        <td colspan="7"><?=$output['user']['id'].'/'.$output['user']['extension']['last_name'].$output['user']['extension']['first_name'].'/'.$output['user']['extension']['call'].'/'.$output['user']['phone']?></td>
    </tr>
    <tr>
        <td>计划上牌地区/交车地点范围：</td>
        <td colspan="7"><?=$output['shangpai_area'].'/'.$output['area'];?></td>
    </tr>
    <tr>
        <td>华车车价/买车担保金/华车服务费：</td>
        <td colspan="7"><?=$output['price']['hwache_price'].'/'.$output['order']['sponsion_price'].'/'.$output['price']['hwache_service_price'];?></td>
    </tr>
    <tr>
        <td>客户当前状态：</td>
        <td colspan="7"><?=$output['order_progress_status']['user_progress'];?></td>
    </tr>
</table>
<hr/>
<div>
    <form class="form-inline">
        <div class="form-group">
            <label>相关工单</label>
        </div>
        <div class="form-group">
            <select class="form-control search_conciliation" id="conciliation_staus">
                <option value="0" <?=$_GET['conciliation_staus']==0?'selected':'';?>>全部状态</option>
                <?php foreach($output['conciliation_status_list'] as $c_status=> $con):?>
                    <option value="<?=$c_status;?>" <?=$_GET['conciliation_staus']==$c_status?'selected':'';?>><?=$con;?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="form-group">
            <select class="form-control search_conciliation" id="target">
                <option value="0" <?=$_GET['target']==0?'selected':'';?>>全部对象</option>
                <option value="1" <?=$_GET['target']==1?'selected':'';?>>平台</option>
                <option value="2" <?=$_GET['target']==2?'selected':'';?>>客户</option>
                <option value="3" <?=$_GET['target']==3?'selected':'';?>>售方</option>
            </select>
        </div>
    </form>
</div>

<table class="table table-bordered">
    <tr class="info">
        <td>工单编号</td>
        <td>工单时间</td>
        <td>工单对象</td>
        <td width="40%">主题</td>
        <td>当前处理部门</td>
        <td>状态</td>
        <td>操作</td>
    </tr>
    <?php if($output['conciliation']):?>
        <?php foreach($output['conciliation'] as $con):?>
            <tr>
                <td><?=$con['id'];?></td>
                <td><?=$con['created_at'];?></td>
                <td><?=$con['target']==1?'平台':($con['target']==2?'客户':'售方');?></td>
                <td width="40%"><?=$con['subject'];?></td>
                <td><?=$output['departments'][$con['follow_depid']];?></td>
                <td><?=$output['conciliation_status_list'][$con['status']];?></td>
                <td>
                    <?php if($con['status']==1):?>
                    <a href="index.php?act=order&op=show_conciliation&id=<?=$con['id'];?>"><?=$con['follow_depid']==$output['admin_info']['dept_id']?'处理':'查看';?></a>
                <?php elseif($con['status']==2 && $output['admin_info']['id']==$con['receive_admin_id']):?>
                    <a href="index.php?act=order&op=doing_conciliation&id=<?=$con['id'];?>">处理</a>
                <?php elseif($con['status']==4):?>
                    <a href="index.php?act=order&op=check_consult_conciliation&id=<?=$con['id'];?>"><?=$output['admin_info']['id'] == $con['admin_id']?'处理':'查看';?></a>
                <?php elseif($con['status'] == 5 && $con['follow_depid']==$output['admin_info']['dept_id']):?>
                    <a href="index.php?act=order&op=check_judge_conciliation&id=<?=$con['id'];?>">处理</a>
                <?php else:?>
                    <?php if($con['type']==2):?>
                    <a href="index.php?act=order&op=end_consult_conciliation&id=<?=$con['id'];?>">查看</a>
                    <?php elseif($con['type']==3):?>
                    <a href="index.php?act=order&op=end_judge_conciliation&id=<?=$con['id'];?>">查看</a>
                    <?php else:?>
                    <a href="index.php?act=order&op=show_conciliation&id=<?=$con['id'];?>">查看</a>
                    <?php endif;?>
                <?php endif;?>
                </td>
            </tr>
        <?php endforeach;?>
    <?php endif;?>

</table>
<br/>
<br/>

<div class="form-inline text-center">
        <button type="button" class="btn btn-default" onclick="javascipt:window.location.href='index.php?act=order&op=create_new_conciliation&id=<?=$output['order']['id'];?>'">新建工单</a></button>
        <button type="button" class="btn btn-default" onclick="javascript:window.history.go(-1);">返回</button>
        <?php $arr = [301,302];?>
    <?php if(in_array($output['order']['order_state'],$arr)):?>
        <button type="button" class="btn btn-default" onclick="<?=$output['order_date']['status']==1?'confirm_appoint_car_cutdown()':'show_appoint_car_cutdown()';?>">停止交车邀请倒计时</button>
    <?php endif;?>
    <?php if($output['order']['order_state'] == 403):?>
        <button type="button" class="btn btn-default" onclick="change_appoint_car_time()">设定交车时间</button>
    <?php endif;?>

</div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script>
    var order_id= <?=$output['id'];?>;
    $(function(){
        $(".order-intro td").css("border","none");

        $(".search_conciliation").change(function(){
            refreshPage();
        })
    })
    function refreshPage()
    {
        var conciliation_staus =  $("#conciliation_staus").val();
        var target = $("#target").val();
        window.location.href="/index.php?act=order&op=show_order&id="+order_id+"&conciliation_staus="+conciliation_staus+"&target="+target;
    }

    function confirm_appoint_car_cutdown(){
        layer.prompt({'title':'请输入延后交车邀请的原因，并确认？'}, function(reason,index){
            layer.confirm('确认售方发生不可抗力，必须立即停止交车邀请倒计时吗？',{
                btn:['确定','取消'],
                title:'确认停止交车倒计时'
            },function(){
                //ajax提交数据
                stop_appoint_car_time(order_id,reason);

                location.reload()

            },function(){
                layer.close();
            });
        }, function(){
            layer.close();
        });
    }

    function show_appoint_car_cutdown()
    {
        layer.confirm('原因：<?=$output['order_date']['reason']?><br/>操作人：<?=$output['admin_info']['name'];?><br/>操作时间：<?=$output['order_date']['admin_operate_at'];?>',{
            btn:['取消'],
            title:' 查看交车邀请倒计时停止记录'
        });
    }

    function change_appoint_car_time(){
        layer.open({
            type: 2,
            title: '设定交车时间',
            shadeClose: true,
            shade: 0.8,
            area: ['800px', '55%'],
            content: '/index.php?act=order&op=change_appoint_car_time&order_id='+order_id //iframe的url
        });
    }

    function stop_appoint_car_time(order_id, reason){
        $.ajax({
            type: 'post',
            url:   '/index.php?act=order&op=ajax_stop_appoint_car_time',
            data : {
                order_id:order_id,
                reason:reason
            },
            dataType: 'json',
            success : function(result){
                if(result.error_code){
                    layer.msg(result.error_msg);
                }
            }
        });
    }
</script>