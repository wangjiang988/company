<?php defined('InHG') or exit('Access Invalid!');?>
<?php $filter =$output['filter'];  $template = $output['filter']['template'];?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><a href="index.php?act=admin_finance&op=home">平台财务</a></h3>
            <ul class="tab-base">
                <li><a class="current"><span>客户提现银行手续费设定</span></a></li>
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
                <div class="pure-u-4-5">
                    <?php if(!empty($template['content']) && is_array($template['content'])){ ?>
                    <?php foreach($template['content'] as $k => $v){ ?>
                        <div>提现金额：￥<?=$v['range'][0]?> ~ <?=$v['range'][1]?>, 提现手续费：￥<?=$v['fee']?></div>
                    <?php } ?>
                    <?php }?>
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
            <a href="index.php?act=admin_finance&op=user_change_withdraw_limit" class="button confirm ml-20" style="margin-right: 100px;">更换模板</a>
        </div>

    </div>

</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript">
    $(function(){

    });
</script>
