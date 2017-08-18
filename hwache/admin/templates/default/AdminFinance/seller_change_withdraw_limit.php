<?php defined('InHG') or exit('Access Invalid!');?>
<?php extract($output);?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><a href="index.php?act=admin_finance&op=home">平台财务</a></h3>
            <ul class="tab-base">
                <li><a  href="index.php?act=admin_finance&op=seller_withdraw_limit"><span>售方提现银行手续费设定</span></a></li>
                <li><a class="current"><span>更换模板</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <div class="container">
        <div class="big_title2">
            更换模板
        </div>

        <form id="ajax_form">
            <input type="hidden" name="in_ajax" value="1">
        <div class="ml50 content">
            <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
                <?php foreach($output['list'] as $i => $template){ ?>
                <div class="pure-g line">
                    <div class="pure-u-1-5">
                        <input type="radio" name="template" value="<?=$template['id']?>" id="template_<?=$template['id']?>"
                        <?php if($filter['template_id'] ==  $template['id']) echo 'checked';?>
                        /> <lable for="template_1">
                            <?=$template['name']?>
                        </lable>
                    </div>
                    <div class="pure-u-4-5">
                        <div> <?=$template['description']?> </div>
                    </div>
                </div>
                <?php } ?>
            <?php }?>
        </div>
        </form>

        <div class="info footer">
            <a href="javascript:void(0);" onclick="confirm_form();" class="button confirm">确定</a>
            <a href="javascript:history.go(-1);" class="button  ml-20">返回</a>
            <a href="index.php?act=admin_finance&op=seller_add_withdraw_limit"   class="ml-50 font-14" >添加模板</a>
            <a href="index.php?act=admin_finance&op=seller_change_withdraw_limit_history" class="ml-20 font-14">查看更新记录</a>
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
        console.log(json);
        parent.layer.confirm('确定要更新模板吗？', {
            btn: ['确认','取消'] //按钮
        }, function(){
            url  = 'index.php?act=admin_finance&op=seller_change_withdraw_limit'
            submit_ajax_form(url,json,location.href);
            closeLayer();
        }, function(){
            closeLayer();
        });
    }

</script>
