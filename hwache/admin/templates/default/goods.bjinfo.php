<?php defined('InHG') or exit('Access Invalid!');?>
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<!-- 新 Bootstrap 核心 CSS 文件 -->
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<!--<script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>-->

<table class="table baojia-intro" >
    <tr>
        <td><?=$lang['goods_system_bj_number'];?></td>
        <td><?=$output['baojia']['bj_id'];?></td>
        <td><?=$lang['goods_dealer_bj_number'];?></td>
        <td><?=$output['baojia']['bj_serial'];?></td>
        <td><?=$lang['common_bj_created_time2'];?></td>
        <td><?=$output['baojia']['bj_create_time'];?></td>
        <td><?=$lang['goods_bj_status'];?></td>
        <td><?=$output['baojia']['bj_status'];?>
            <?php
            if(in_array($output['baojia']['bj_status'],array('暂时下架', '失效报价'))){
                echo '('.$output['baojia']['bj_reason'].')';
            };?>
        </td>
    </tr>
    <tr>
        <td><?=$lang['goods_bj_create_user'];?></td>
        <td><?=$output['member']['member_name'];?></td>
        <td><?=$lang['common_name'];?></td>
        <td><?=$output['member']['member_name'];?></td>
        <td><?=$lang['goods_user_and_mobile'];?></td>
        <td><?=$output['member']['member_truename'].'/'.$output['member']['member_mobile'];?></td>
        <?php if($output['baojia']['bj_status']=='暂时下架'):?>
        <td><?=$lang['goods_temporary_lower_frame_time'];?></td>
        <td><?=$output['baojia']['bj_update_time'];?></td>
        <?php else:?>
        <td></td>
        <td></td>
        <?php endif;?>
    </tr>
    <tr>
        <td><?=$lang['goods_bj_setting_effect_time'];?></td>
        <td colspan="3"><?php if($output['baojia']['bj_start_time']):?><?=date("Y-m-d H:i",$output['baojia']['bj_start_time']);?>~<?=date("Y-m-d H:i",$output['baojia']['bj_end_time']);?><?php else:?>未设置<?php endif;?></td>
        <td><?=$lang['common_bj_effectived_time2'];?>:</td>
        <td><?php if($output['baojia']['bj_start_time']):?>
            <?php if($output['baojia']['bj_start_time']<time()):?><?=date("Y-m-d H:i:s",$output['baojia']['bj_start_time']);?><?php endif;?>
            <span id="djs" bj_start_time="<?=date("Y-m-d H:i:s",$output['baojia']['bj_start_time']);?>"></span>
            <?php else:?>
                未设置
            <?php endif;?>
        </td>
        <?php if($output['baojia']['bj_status']=='失效报价'):?>
        <td><?=$lang['goods_bj_expired_time'];?></td>
        <td><?=$output['baojia']['bj_update_time'];?></td>
        <?php else:?>
        <td></td>
        <td></td>
        <?php endif;?>
    </tr>
    <tr>
        <td><?=$lang['common_dealer_name'];?></td>
        <td><?=$output['dealer']['d_name']; ?></td>
        <td><?=$lang['common_area'];?></td>
        <td colspan="5"><?=$output['dealer']['d_areainfo'];?> </td>
    </tr>
    <tr>
        <td><?=$lang['goods_place_of_business'];?></td>
        <td colspan="3"><?=$output['dealer']['d_yy_place'];?></td>
        <td><?=$lang['goods_place_of_delivery'];?></td>
        <td colspan="3"><?=$output['dealer']['d_jc_place'];?></td>
    </tr>
    <tr>
        <td><?=$lang['goods_bj_car_model'];?></td>
        <td colspan="3"><?=$output['baojia']['gc_name'];?><a style="margin-left:60px;"class="text-warning"  target="_blank" href="<?=$output['car_goods_class']['detail_img']?UPLOAD_SITE_URL.'/'.$output['car_goods_class']['detail_img']:'?act=goods_class&op=goods_class_edit&gc_id='.$output['baojia']['brand_id'];?>"><?=$lang['goods_show_car_info'];?></a></td>
        <td><?=$lang['goods_class_vehicle_model'];?></td>
        <td><?=$output['car_goods_class']['vehicle_model']; ?></td>
        <td><?=$lang['goods_is_xianche'];?></td>
        <td><?=$output['baojia']['bj_is_xianche']?'现车':'非现车'; ?></td>
    </tr>
    <tr>
        <td><?=$lang['goods_car_paifang'];?></td>
        <td><?=$output['paifang'];?></td>
        <td><?=$lang['goods_car_body_color'];?></td>
        <td><?=$output['baojia']['bj_body_color'];?></td>
        <td><?=$lang['goods_car_interior_color'];?></td>
        <td><?=$output['interior_color'];?></td>
        <td><?=$lang['goods_car_seats_number'];?></td>
        <td><?=$output['seat_num'];?></td>
    </tr>
    <tr>
        <?php if($output['baojia']['bj_is_xianche']==1):?>
            <td><?=$lang['goods_car_ex_factory_date'];?></td>
            <td><?=date("Y年m月",strtotime($output['baojia']['bj_producetime']));?></td>
            <td><?=$lang['goods_car_travel_mileage'];?></td>
            <td><?php if($output['baojia']['bj_licheng']):?><?=$output['baojia']['bj_licheng'];?><?=$lang['goods_kilometers'];?><?php endif;?></td>
            <td><?=$lang['goods_car_internal_number'];?></td>
            <td><?=$output['baojia']['bj_dealer_internal_id'];?></td>
        <?php else:?>
            <td><?=$lang['goods_car_jc_period'];?></td>
            <td><?=$output['baojia']['bj_step']>2?$output['baojia']['bj_jc_period'].'个月':'';?></td>
        <?php endif;?>
        <td><?=$lang['goods_car_spot_quantity'];?></td>
        <td>2(<?=$lang['goods_car_total_quantity'];?>:<?=$output['baojia']['bj_num'];?>)</td>
    </tr>
    <tr>
        <td><?=$lang['goods_bj_order_list'];?></td>
        <td colspan="7"><a class="text-warning" href="#">1234532133、</a><a class="text-warning" href="#">1234532133、</a><a class="text-warning" href="#">1234532133、</a><a class="text-warning" href="#">1234532133、</a><a class="text-warning" href="#">1234532133、</a><a class="text-warning" href="#">1234532133、</a><a class="text-warning" href="#">1234532133、</a><a class="text-warning" href="#">1234532133、</a></td>
    </tr>
    <tr>
        <td><?=$lang['goods_guide_price'];?></td>
        <td class="price">￥<?=$output['zhidaojia']; ?></td>
        <td><?=$lang['goods_sale_price'];?></td>
        <td class="price">￥<?=$output['price']['bj_lckp_price']; ?> </td>
        <td><?=$lang['goods_service_fee'];?></td>
        <td class="price">￥<?=$output['price']['bj_agent_service_price']; ?></td>
        <td><?=$lang['goods_doposit_price'];?></td>
        <td class="price">￥<?=$output['price']['bj_doposit_price'];?></td>
    </tr>
    <tr>
        <td><?=$lang['goods_bj_dbcp_service_fee'];?></td>
        <td class="price"><?=$output['price']['bj_shangpai_price']>0?'￥'.$output['price']['bj_shangpai_price']:'';?></td>
        <td><?=$lang['goods_bj_dblscp_service_fee'];?></td>
        <td class="price"><?=$output['price']['bj_linpai_price']>0?'￥'.$output['price']['bj_linpai_price']:'' ?> </td>
        <td><?=$lang['goods_car_license_fee'];?></td>
        <td colspan="3" class="price"><?=$output['price']['bj_license_plate_break_contract']>0?'￥'.$output['price']['bj_license_plate_break_contract']:''; ?></td>
    </tr>
    <tr>
        <td><?=$lang['goods_bj_dbcp_requirement'];?></td>
        <td><?=$output['baojia']['bj_step']>5?($output['baojia']['bj_shangpai']==1?'强制':'自由'):''; ?></td>
        <td><?=$lang['goods_bj_dblscp_requirement'];?></td>
        <td><?=$output['baojia']['bj_step']>5?($output['baojia']['bj_linpai']==1?'强制':'自由'):''; ?> </td>
        <td><?=$lang['goods_bj_car_license_requirement'];?></td>
        <td><?=$output['baojia']['bj_step']>5?($output['price']['bj_license_plate_break_contract']>0?'有条件':'无此要求'):'';?></td>
        <td><?=$lang['goods_bj_xzjp_discount'];?></td>
        <td><?=($output['baojia']['bj_xzj_zhekou']&&$output['baojia']['bj_step']>3)?$output['baojia']['bj_xzj_zhekou'].'%':''; ?></td>
    </tr>
    <tr>
        <td><?=$lang['goods_bj_baoxian_requirement'];?></td>
        <td><?=$output['baojia']['bj_step']>4?($output['baojia']['bj_baoxian']?'强制':'自由'):'';?></td>
        <td><?=$lang['goods_bj_baoxian_company'];?></td>
        <td colspan="3"><?=$output['baojia']['bj_step']>4?$output['baoxian']['bx_title']:'';?></td>
        <td><?=$lang['goods_bj_baoxian_claim_scope'];?></td>
        <td><?=$output['baojia']['bj_step']>4?($output['baoxian']['bx_is_quanguo']?'本地、异地':'本地'):'';?></td>
    </tr>
    <tr>
        <td><?=$lang['goods_sales_territory'];?></td>
        <td colspan="6" id="area" area="<?=$output['area'];?>"><?=mb_strlen($output['area'],'utf-8')>80?mb_substr($output['area'],0,80,'utf-8').'......':$output['area'];?></td>
        <td><a href="javascript:void(0)" onclick="show_sale_area()"><?=$lang['goods_show_sales_territory'];?></a></td>
    </tr>
    <tr>
        <td colspan="8" id="more-baojia"><a href="javascript:void(0)"><?=$lang['goods_list_more_bj'];?></a></td>
    </tr>
</table>
<div id="other-baojia-info">
    <?php if($output['baojia']['bj_is_xianche']):?>
    <h4>已装原厂选装精品：<?php if(!$output['xzjs']||$output['baojia']['bj_step']<=3):?>无<?php endif;?></h4>
    <?php if($output['xzjs']&&$output['baojia']['bj_step']>3):?>
    <table class="table table-bordered table-condensed">
        <tr>
            <td>名称</td>
            <td>型号说明</td>
            <td>华车内部编号</td>
            <td>厂商编号</td>
            <td>厂商指导价</td>
            <td>数量</td>
            <td>附加价值</td>
        </tr>
        <?php foreach($output['xzjs'] as $xzj):?>
        <tr>
            <td><?=$xzj['xzj_title'];?></td>
            <td><?=$xzj['xzj_model'];?></td>
            <td><?=$xzj['id'];?></td>
            <td><?=$xzj['xzj_cs_serial'];?></td>
            <td class="price">￥<?=number_format($xzj['xzj_guide_price'],2);?></td>
            <td><?=$xzj['num'];?></td>
            <td class="price">￥<?=number_format($xzj['xzj_guide_price']*$xzj['num'],2);?></td>
        </tr>
        <?php endforeach;?>
    </table>
    <?php endif;?>
    <?php endif;?>
    <h4>免费礼品和服务：<?php if(!$output['zengpin']||$output['baojia']['bj_step']<=2):?>无<?php endif;?></h4>
    <?php if($output['baojia']['bj_step']>2&&$output['zengpin']):?>
    <table class="table table-bordered table-condensed">
        <tr>
            <td>名称</td>
            <td>数量</td>
            <td>状态</td>
        </tr>
        <?php foreach($output['zengpin'] as $z):?>
        <tr>
            <td><?=$z['title'];?></td>
            <td><?=$z['num'];?></td>
            <td><?=$z['is_install']==1?"已安装":"/";?></td>
        </tr>
        <?php endforeach;?>
    </table>
    <?php endif;?>
    <h4>其他收费：<?php if(!$output['otherPrice']||$output['baojia']['bj_step']<=5):?>无<?php endif;?></h4>
    <?php if($output['baojia']['bj_step']>5&&$output['otherPrice']):?>
    <table class="table table-bordered table-condensed">
        <tr>
            <td>费用名称</td>
            <td>金额</td>
        </tr>
        <?php foreach($output['otherPrice'] as $op):?>
        <tr>
            <td><?=$op['other_name'];?></td>
            <td class="price">￥<?=number_format($op['other_price'],2);?></td>
        </tr>
        <?php endforeach;;?>
    </table>
    <?php endif;?>
    <?php if($output['baojia']['bj_is_xianche']==0):?>
    <h4>可选装精品（出厂前装）：<?php if(!$output['xzjs']||$output['baojia']['bj_step']<=3):?>无<?php endif;?></h4>
    <?php if($output['xzjs']&&$output['baojia']['bj_step']>3):?>
        <table class="table table-bordered table-condensed">
            <tr>
                <td>名称</td>
                <td>型号说明</td>
                <td>华车内部编号</td>
                <td>厂商编号</td>
                <td>厂商指导价</td>
                <td>数量</td>
                <td>附加价值</td>
            </tr>
            <?php foreach($output['xzjs'] as $xzj):?>
                <tr>
                    <td><?=$xzj['xzj_title'];?></td>
                    <td><?=$xzj['xzj_model'];?></td>
                    <td><?=$xzj['id'];?></td>
                    <td><?=$xzj['xzj_cs_serial'];?></td>
                    <td class="price">￥<?=number_format($xzj['xzj_guide_price'],2);?></td>
                    <td><?=$xzj['num'];?></td>
                    <td class="price">￥<?=number_format($xzj['xzj_guide_price']*$xzj['num'],2);?></td>
                </tr>
            <?php endforeach;?>
        </table>
    <?php endif;?>
    <?php endif;?>
    <h4>可选装精品<?=$output['baojia']['bj_is_xianche']?'':'（后装）';?>：<?php if(!$output['kxjps']||$output['baojia']['bj_step']<=3):?>无<?php endif;?></h4>
    <?php if($output['baojia']['bj_step']>3&&$output['kxjps']):?>
    <table class="table table-bordered table-condensed">
        <tr>
            <td>名称</td>
            <td>型号说明</td>
            <td>华车内部编号</td>
            <td>厂商编号</td>
            <td>厂商指导价</td>
            <td>安装费</td>
            <td>含安装费折后总价</td>
            <td>可供件数</td>
        </tr>
        <?php foreach($output['kxjps'] as $kxjp):?>
        <tr>
            <td><?=$kxjp['xzj_title'];?></td>
            <td><?=$kxjp['xzj_model'];?></td>
            <td><?=$kxjp['id'];?></td>
            <td><?=$kxjp['xzj_cs_serial'];?></td>
            <td class="price">￥<?=number_format($kxjp['xzj_guide_price'],2);?></td>
            <td class="price">￥<?=number_format($kxjp['fee'],2);?></td>
            <td class="price">￥<?=number_format(($output['baojia']['bj_xzj_zhekou']*$kxjp['xzj_guide_price'])/100+$kxjp['fee'],2);?></td>
            <td><?=($kxjp['xzj_has_num']==0)?'不限':$kxjp['xzj_has_num'];?></td>
        </tr>
        <?php endforeach;?>
    </table>
    <?php endif;?>

    <p>刷卡标准:</p>
    <p>单车付款刷信用卡免费次数:
    <?php if($output['baojia']['bj_step']>5):?>
        <?php if($output['expandInfo']['xyk_status']==0):?>不限
        <?php elseif($output['expandInfo']['xyk_status']==1):?>
        <?=$output['expandInfo']['xyk_number'];?>次；超出次数收费：
            <?php if($output['expandInfo']['xyk_status']==1&&$output['expandInfo']['xyk_per_num']>0):?>刷卡金额的<?=$output['expandInfo']['xyk_per_num'];?>%（百分之）<?php endif;?>
            <?php if($output['expandInfo']['xyk_status']==1&&$output['expandInfo']['xyk_per_num']>0&&$output['expandInfo']['xyk_yuan_num']>0):?>，<?php endif;?>
            <?php if($output['expandInfo']['xyk_status']==1&&$output['expandInfo']['xyk_yuan_num']>0):?>每次<?=$output['expandInfo']['xyk_yuan_num'];?>元（封顶）<?php endif;?>
        <?php endif;?>
    <?php endif;?>
    </p>
    <p>单车付款刷借记卡免费次数：
    <?php if($output['baojia']['bj_step']>5):?>
        <?php if($output['expandInfo']['jjk_status']==0):?>不限
        <?php elseif($output['expandInfo']['jjk_status']==1 && $output['expandInfo']['jjk_number']>0):?>
        <?=$output['expandInfo']['jjk_number'];?>次；超出次数收费：
            <?php if($output['expandInfo']['jjk_status']==1&&$output['expandInfo']['jjk_per_num']>0):?>刷卡金额的<?=$output['expandInfo']['jjk_per_num'];?>%（百分之）<?php endif;?>
            <?php if($output['expandInfo']['jjk_status']==1&&$output['expandInfo']['jjk_per_num']>0&&$output['expandInfo']['jjk_yuan_num']>0):?>，<?php endif;?>
            <?php if($output['expandInfo']['jjk_status']==1&&$output['expandInfo']['jjk_yuan_num']>0):?>每次<?=$output['expandInfo']['jjk_yuan_num'];?>元（封顶）<?php endif;?>
        <?php endif;?>
    <?php endif;?>
    </p>
    <?php if($output['baojia']['bj_step']>6):?>
        <?php if($output['expandInfo']['bt_status']==0):?>
        <?php elseif($output['expandInfo']['bt_status']==1):;?>
            <p>国家节能补贴:
            <?php if($output['expandInfo']['bt_work_day']>0):?>经销商代办上牌的，交车上牌时当场兑现；由客户本人上牌的，上牌资料齐全后，经销商垫付给客户，时限<?=$output['expandInfo']['bt_work_day'];?>个工作日。
            <?php endif;?>
            <?php if($output['expandInfo']['bt_work_month']>0):?>上牌资料齐全后，经销商将所有资料交给汽车厂商，厂商直接付给客户，或者（厂商付经销商再由）经销商垫付给客户，时限<?=$output['expandInfo']['bt_work_month'];?>个月。
            <?php endif;?>
            </p>
        <?php endif;?>
    <?php endif;?>

    <p>地方政府置换补贴：<?=$output['baojia']['bj_step']>6?($output['baojia']['bj_zf_butie']>0?'有':'无'):'';?></p>
    <p>厂家或经销商置换补贴:<?=$output['baojia']['bj_step']>6?($output['baojia']['bj_cj_butie']>0?'有':'无'):'';?></p>
    <p>随车工具:<?php if(isset($output['suicheInfo'][2])) { echo implode('、',$output['suicheInfo'][2]); }?></p>
    <p>随车移交文件:<?php if(isset($output['suicheInfo'][1])) { echo implode('、',$output['suicheInfo'][1]); }?></p>
</div>


<div class="text-center">
    <a href="javascript:void" onclick="parent.layer.closeAll();"><button type="button" class="btn btn-warning"><?=$lang['common_close'];?></button></a>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/tictac.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>

<script>
$(function() {
    $(".price").each(function(){
        var h = $(this).html().replace(/\B(?=(?:\d{3})+\b)/g, ',');
        $(this).html(h);
    })

    Tictac.init({
        currentTime: Date.parse(new Date()), //设置当前时间
        interval: 3000, //执行callback的时间间隔
        callback: function() {
            //重复执行的回调

        }
    });

    var start_time = $("#djs").attr("bj_start_time");
    Tictac.create('djs', {
        targetId: 'djs', //显示计时器的容器
        expires: Date.parse(start_time), //目标时间
        format: { //格式化对象
            days: '{d}天 ',
            hours: '{hh}小时 ',
            minutes: '{mm}分 ',
            seconds: '{ss}秒'
        },
        timeout: function() {
            //计时器 timeout 回调
        },
    });

    //去除页面边框
    $(".baojia-intro td").css("border","none");

    $("#other-baojia-info").hide();
    $("#more-baojia").toggle(function(){
        $("#more-baojia a").html('收起其余报价详情');
        $("#other-baojia-info").show();
    },function(){
        $("#more-baojia a").html('打开其余报价详情');
        $("#other-baojia-info").hide();
    });
});

function show_goods_class(gc_id)
{
    layer.open({
        type: 2,
        title: false,
        shadeClose: true,
        shade: false,
        maxmin: true, //开启最大化最小化按钮
        area: ['900px', '500px'],
        content: '/index.php?act=goods_class&op=goods_class_edit&gc_id='+gc_id
    });
}

function show_sale_area()
{
    var area = $("#area").attr("area");
    layer.alert(area,{
        skin:'layui-layer-molv',
        closeBtn:0
    });
}
</script>
