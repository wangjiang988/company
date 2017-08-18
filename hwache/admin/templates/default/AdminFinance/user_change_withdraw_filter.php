<?php defined('InHG') or exit('Access Invalid!');?>
<?php extract($output);?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><a href="index.php?act=admin_finance&op=home">平台财务</a></h3>
            <ul class="tab-base">
                <li><a  href="index.php?act=admin_finance&op=user_withdraw_filter"><span>客户提现申请拦截条件设定</span></a></li>
                <li><a class="current"><span>修改</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <div class="container">
        <div class="big_title2">
            客户提现拦截修改
        </div>

        <form id="ajax_form">
            <input type="hidden" name="in_ajax" value="1">
            <input type="hidden" name="count" value="<?=count($output['list'])?>">
        <div class="ml50 content">
            <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
                <?php foreach($output['list'] as $i => $template){ ?>
                <div class="pure-g line">
                    <input type="hidden"  name="name_<?=$i?>" value="<?=$template['id']?>" />
                    <div class="pure-u-2-5">
                        <span >
                            <?=$template['name'].":".$template['description']?>
                        </span>

                    </div>
                    <div class="pure-u-1-5">
                        <input type="radio"  name="template_<?=$i?>" value="1" id="template_<?=$template['id']?>"
                            <?php if($template['status'] ==  1) echo 'checked';?>
                        /><label for="">启用</label>
                    </div>
                    <div class="pure-u-1-5">
                        <input type="radio"  name="template_<?=$i?>" value="0" id="template_<?=$template['id']?>"
                            <?php if($template['status'] !=  1) echo 'checked';?>
                        /><label for="">禁用</label>
                    </div>
                </div>
                <?php } ?>
            <?php }?>
        </div>
        </form>

        <div class="info footer">
            <a href="javascript:void(0);" onclick="confirm_form();" class="button confirm">确定</a>
            <a href="javascript:history.go(-1);" class="button  ml-20">返回</a>
            <a href="index.php?act=admin_finance&op=user_add_withdraw_filter"   class="ml-50 font-14" >添加条件</a>
            <a href="index.php?act=admin_finance&op=user_change_withdraw_filter_history" class="ml-20 font-14">查看操作记录</a>
        </div>

    </div>

</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript">
    $(function(){

    });

    //表单提交
    function confirm_form() {
        let json= $('#ajax_form').serializeJSON();
        parent.layer.confirm('确定提交拦截条件吗？', {
            btn: ['确认','取消'] //按钮
        }, function(){
            url  = 'index.php?act=admin_finance&op=user_change_withdraw_filter'
            submit_ajax_form(url,json,location.href);
            closeLayer();
        }, function(){
            closeLayer();
        });
    }

</script>
