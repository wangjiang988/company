
<?php defined('InHG') or exit('Access Invalid!');?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <ul class="tab-base">
                <li><a href="index.php?act=seller_finance&op=index"><span>售方财务</span></a> | </li>
                <li><a href="index.php?act=seller_finance&op=special_service"><span>特情审批</span></a> | </li>
                <li><a href="javascript:void(0);" class="current"><span>详情</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>

    <div class="info">
        <span class="label">工单编号：</span>
        <span class="val"><?php echo $output['application']['id'] ;?></span>
        <span class="label">工单时间：</span>
        <span class="val"><?php echo $output['application']['created_at'] ;?></span>
        <span class="label">状态：</span>
        <span class="val"><?php echo show_special_status($output['application']['status']);?></span>
    </div>
    <div class="info">
        <span class="label">售方用户名：</span>
        <span class="val"><?php echo $output['seller']['member_name'] ;?></span>
        <span class="label">售方姓名：</span>
        <span class="val"><?php echo $output['seller']['member_truename'] ;?></span>
        <span class="label">售方手机：</span>
        <span class="val"><?php echo $output['seller']['member_mobile'] ;?></span>
    </div>
    <?php if($output['application']['status'] ==0 ){?>
        <div class="info">
            <span class="label">客户可用余额：</span>
            <span class="val">￥ <?php echo $output['seller']['avaliable_deposit'] ;?></span>
            <span class="label">平台冻结可用余额：</span>
            <span class="val">￥<?php echo $output['seller']['temp_deposit'] ;?></span>
        </div>
    <?php }?>
    <div class="clear"></div>
    <div class="big_title">
        申请内容
    </div>
    <div class="clear"></div>
    <div class="info">
        <div class="info">
            <span class="title">申请项目与金额:</span>
            <span class="val span2"><?=$output['application']['name'].'， ￥'.$output['application']['money']?></span>
        </div>
        <div class="info">
            <span class="title">原因:</span>
            <span class="val span2"><?=$output['application']['reason']?></span>
        </div>
        <div class="info">
            <span class="title">备注:</span>
            <span class="val span2"><?=$output['application']['remark']?></span>
        </div>
        <div class="ifno">
            <span class="title">申请人:</span>
            <span class="val span2"><?=$output['application']['apply_admin_name']?></span>
        </div>
    </div>
    <div class="clear"></div>
    <?php if($output['application']['status'] ==0 ){?>
        <form id="ajax_form" method="post" action="/index.php">
            <input type="hidden" name="act" value="seller_finance"/>
            <input type="hidden" name="op" value="save_special" />
            <input type="hidden" name="form_submit" value="ok">
            <input type="hidden" name="id" value="<?=$output['application']['id']?>">
            <div class="big_title">
                审批
            </div>
            <div class="clear"></div>
            <div class="info">
                <div class="info">
                    <span class="title"><span style="color:red;">*</span>结论:</span>
                    <span class="val span6">
                <span class="span4">
                   <input type="radio" name="status" id="status_1" value="1" checked> <label for="status_1">通过</label>
                </span>
                  <span class="span4">
                     <input type="radio" name="status" id="status_2" value="2"> <label for="status_2">不通过</label>
                </span>
            </span>

                </div>
                <div class="info">
                    <span class="title">备注:</span>
                    <span class="val span6">
                <textarea name="judge_remark" style="min-height: 100px;" class="wt400" cols="30" rows="10"></textarea>

            </span>
                </div>
            </div>
        </form>
    <?php }?>
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
        <?php if($output['application']['status'] == 0){?>
            <a href="javascript:void(0);" onclick="confirm_form()" class="button confirm" style="margin-right: 100px;">提交</a>
        <?php } ?>
        <a href="javascript:history.go(-1);" class="button">返回</a>
    </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>

<script type="text/javascript">
    /**
     * 确认按钮
     */
    function confirm_form(){
        layer.confirm('确定提交审批结果吗？', {
            btn: ['确认','取消'] //按钮
        }, function(){
            submit_form();
            closeLayer();
        }, function(){
            closeLayer();
        });
    }
    //提交表单
    function submit_form()
    {
        $('#ajax_form').submit();
    }
</script>
