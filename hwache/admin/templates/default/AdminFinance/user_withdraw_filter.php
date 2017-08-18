<?php defined('InHG') or exit('Access Invalid!');?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><a href="index.php?act=admin_finance&op=home">平台财务</a></h3>
            <ul class="tab-base">
                <li><a class="current"><span>客户提现申请拦截条件设定</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <div class="container">
        <div class="big_title">
            客户提现拦截
        </div>

        <div class="ml50 content">
            <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                <span><?=$v['name'].'. '.$v['description']?></span>
            <?php } ?>
            <?php } ?>
        </div>

        <div class="info footer">
            <a href="javascript:history.go(-1);" class="button">返回</a>
            <a href="index.php?act=admin_finance&op=user_change_withdraw_filter" class="button confirm ml-20" style="margin-right: 100px;">修改</a>
        </div>

    </div>

</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript">
    $(function(){

    });
</script>
