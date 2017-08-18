
<?php defined('InHG') or exit('Access Invalid!');?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>客户财务</h3>
            <ul class="tab-base">
                <li><a href="index.php?act=finance&op=index"><span>客户财务</span></a></li>
                <li><a href="index.php?act=finance&op=special_index"><span>特事审批</span></a>▷</li>
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
            <span class="label">客户会员号：</span>
            <span class="val"><?php echo $output['user']['id'] ;?></span>
            <span class="label">客户姓名：</span>
            <span class="val"><?php echo $output['user']['last_name'].$output['user']['first_name'] ;?></span>
            <span class="label">客户手机：</span>
            <span class="val"><?php echo $output['user']['phone'] ;?></span>
        </div>
    <?php if($output['application']['status'] ==0 ){?>
        <div class="info">
            <span class="label">客户可用余额：</span>
            <span class="val">￥ <?php echo $output['user']['avaliable_deposit'] ;?></span>
            <span class="label">平台冻结可用余额：</span>
            <span class="val">￥ <?=$output['user']['temp_deposit'] ;?></span>
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
    <form id="ajax_form">
        <input type="hidden" name="in_ajax" value="1">
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
        parent.layer.confirm('确定提交审批结果吗？', {
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

        ajax_form();
    }



    function ajax_form(){

        let json= $('#ajax_form').serializeJSON();

        post('index.php?act=finance&op=special_detail', json).then(function(res){
            if(res.data.code == 200)
            {
                alert(res.data.msg);
                // refresh_workspace();
                location.reload();
                closeLayer();
            }else
            {
                alert(res.data.msg);
            }
        }).catch(function(err){
            console.log(err);
        });
    }
</script>
