<?php defined('InHG') or exit('Access Invalid!');?>
<?php $filter =$output['filter'];  $template = $output['filter']['template'];?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><a href="index.php?act=admin_finance&op=home">平台财务</a></h3>
            <ul class="tab-base">
                <li><a class="current"><span>售方提现银行手续费设定</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <div class="container">
        <div class="big_title">
            当前手续费模板-  <span style="color:#ff5500"><?=$template['name']?></span>
        </div>

        <div class="ml50 content">
            <div class="pure-g line">
                <div class="pure-u-1-5">
                    <div><?=$template['description']?></div>
                </div>
                <div class="pure-u-2-5">
                    <?php if($template['content']&& is_array($template['content'])){?>
                        <?php if($template['content']['type'] == 1 ){ // 次数 ?>
                            <div>每月免费次数：<?=$template['content']['freetime']?> , 超出次数每次收费：￥<?=$template['content']['fee']?></div>
                        <?php }elseif($template['content']['type'] == 2) { ?>
                            <div>每月免费金额：￥<?=$template['content']['freetime']?> , 超出次数每次收费：￥<?=$template['content']['fee']?></div>
                        <?php }?>
                     <?php }?>
                </div>
                <div class="pure-u-1-5">
                    <div><a href="javascript:void(0);" class="button confirm" onclick="show_change_dialog(<?=$template['id']?>);">修改</a></div>
                </div>
                <div class="pure-u-1-5">
                    <div><a href="javascript:void(0);"  onclick="show_change_history(<?=$template['id']?>);">修改记录</a></div>
                </div>
            </div>

            <div class="pure-g line">
                <div class="pure-u-1-5">
                    <div>当前模板设定人</div>
                </div>
                <div class="pure-u-4-5">
                    <div><?=$filter['setor_name']?></div>
                </div>
            </div>

            <div class="pure-g line">
                <div class="pure-u-1-5">
                    <div>当前模板设定时间</div>
                </div>
                <div class="pure-u-4-5">
                    <div><?=$filter['updated_at']?></div>
                </div>
            </div>
        </div>

        <div class="info footer">
            <a href="javascript:history.go(-1);" class="button">返回</a>
            <a href="index.php?act=admin_finance&op=seller_change_withdraw_limit" class="button confirm ml-20" style="margin-right: 100px;">更换模板</a>
        </div>

    </div>

</div>


<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>

<script type="text/javascript">
    $(function(){

    });



    /**
     * 修改按钮
     */
    function show_change_dialog(template_id)
    {
        comment_dialog = parent.layer.open({
            type: 2,
            title:"修改手续费标准",
            skin: 'layui-layer-rim', //加上边框
            area: ['500px', '350px'], //宽高
            content: 'index.php?act=admin_finance&op=seller_ajax_change_template&template_id='+template_id
        });
    }


    /**
     * 产看修改记录
     */
    function show_change_history(template_id)
    {
        comment_dialog = parent.layer.open({
            type: 2,
            title:"查看修改记录",
            skin: 'layui-layer-rim', //加上边框
            area: ['500px', '400px'], //宽高
            content: 'index.php?act=admin_finance&op=seller_ajax_change_template_history&template_id='+template_id
        });
    }




</script>
