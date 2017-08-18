
<?php defined('InHG') or exit('Access Invalid!');?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>客户财务</h3>
            <ul class="tab-base">
                <li><a href="index.php?act=finance&op=index"><span>客户财务</span></a>▷</li>
                <li><a href="index.php?act=finance&op=user_special_index&uid=3"><span>特别事项</span></a>▷</li>
                <li><a href="javascript:void(0);" class="current"><span>详情</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>

    <form method="get" action="index.php" name="ajax_form" id="ajax_form">
        <div class="info">
            <span class="label">工单编号：</span>
            <span class="val"><?php echo $output['application']['id'] ;?></span>
            <span class="label">工单时间：</span>
            <span class="val"><?php echo $output['application']['created_at'] ;?></span>
            <span class="label">状态：</span>
            <span class="val"><?php echo show_special_status($output['application']['status']);?></span>
        </div>
        <div class="info">
            <span class="label">客户会员号：</span>
            <span class="val"><?php echo $output['user']['id'] ;?></span>
            <span class="label">客户姓名：</span>
            <span class="val"><?php echo $output['user']['last_name'].$output['user']['first_name'] ;?></span>
            <span class="label">客户手机：</span>
            <span class="val"><?php echo $output['user']['phone'] ;?></span>
        </div>
    </form>
    <div class="clear"></div>
    <div class="big_title">
        申请内容
    </div>
    <div class="clear"></div>
    <div class="info">
        <div class="info">
            <span class="title">申请项目与金额:</span>
            <span class="val span2"><?=$output['application']['name'].'，￥'.$output['application']['money']?></span>

            <span class="title">申请人:</span>
            <span class="val span2"><?=$output['application']['apply_admin_name']?></span>
        </div>
        <div class="info">
            <span class="title">原因:</span>
            <span class="val span2"><?=$output['application']['reason']?></span>
        </div>
        <div class="info">
            <span class="title">备注:</span>
            <span class="val span2"><?=$output['application']['remark']?></span>
        </div>
    </div>
    <?php if($output['application']['status']>0){ ?>
    <div class="big_title">
        审批结论
    </div>
    <div class="clear"></div>
    <div class="info">
        <div class="info">
            <span class="title">结论:</span>
            <span class="val span2"><?=show_special_status($output['application']['status'])?></span>
        </div>
        <div class="info">
            <span class="title">备注:</span>
            <span class="val span2"><?=$output['application']['judge_remark']?></span>
        </div>
        <div class="info">
            <span class="title">审批人:</span>
            <span class="val span2"><?=$output['application']['judge_admin_name']?></span>
            <span class="title">审批时间:</span>
            <span class="val span2"><?=$output['application']['judge_at']?></span>
        </div>
    </div>
    <?php }?>
    <div class="info footer">
        <a href="javascript:history.go(-1);" class="button">返回</a>
    </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>

<script type="text/javascript">

</script>
