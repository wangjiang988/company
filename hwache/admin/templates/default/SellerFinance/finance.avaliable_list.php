<style type="text/css">
    .tab-base em{ padding: 0 10px;}
    .search input.txt{width: 120px;}
    .search span{width: auto; margin-right: 15px; padding:0 5px;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>售方财务</h3>
            <ul class="tab-base">
                <li><a href="<?=url('seller_finance','index');?>"><span>管理</span></a><em> | </em></li>
                <li><a href="JavaScript:void(0);" class="current"><span>可提现余额</span></a><em> | </em></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form name="formSearch" id="searchFrom" action="<?=url('seller_finance','avaliable')?>" method="get">
        <input type="hidden" value="seller_finance" name="act">
        <input type="hidden" value="avaliable" name="op">
        <table class="tb-type1 noborder search" style="width:100%;">
            <tbody>
            <tr>
                <th><label for="search_title">售方用户名：</label></th>
                <td>
                    <input type="hidden" name="id" value="<?=$output['seller']['member_id']?>" />
                    <span><?=$output['seller']['member_name'];?></span>
                    <label for="seller_name">售方姓名：</label>
                    <span><?=$output['seller']['member_truename'];?></span>
                    <label for="name_phone">售方手机号：</label>
                    <span><?=$output['seller']['member_mobile'];?></span>

                    <label for="item">项目：</label>
                    <input type="text" value="<?=$output['search']['item'];?>" name="item" class="txt">
                    <label for="remark">说明：</label>
                    <input type="text" value="<?=$output['search']['remark'];?>" name="remark" class="txt">
                </td>
            </tr>
            <tr>
                <th><label for="search_jxb">发生时间：</label></th>
                <td>
                    <input type="text" name="start_date" value="<?=$output['search']['start_date'];?>" id="start_date" class="date">
                     -
                    <input type="text" name="end_date" value="<?=$output['search']['end_date'];?>" id="end_date" class="date">
                    <span><a <?=getDateHover($output['search']['date'],1)?> href="<?=url('seller_finance','avaliable',['id'=>$output['seller']['member_id'],'date'=>'1'])?>">一个月</a></span>
                    <span><a <?=getDateHover($output['search']['date'],2)?> href="<?=url('seller_finance','avaliable',['id'=>$output['seller']['member_id'],'date'=>'2'])?>">一年</a></span>
                    <span><a <?=getDateHover($output['search']['date'],3)?> href="<?=url('seller_finance','avaliable',['id'=>$output['seller']['member_id'],'date'=>'3'])?>">一年以上</a></span>
                </td>
            </tr>
            <tr><th></th>
                <td>
                    <div style="float:right; text-align:left; width:auto;">
                        <button type="submit" class="button button-primary button-small" style="margin-right: 10px;">搜索</button>
                        <a href="<?=url('seller_finance','avaliable',['id'=>$output['seller']['member_id']])?>" class="button button-primary button-small" style="margin-right: 10px;">重置</a>
                        <a href="<?=url('seller_finance','exportAvaliableFile',$output['uri'])?>" class="button button-primary button-small">导出</a>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </form>

    <table class="table tb-type2">
        <thead>
        <tr class="thead">
            <th class="w120">发生时间</th>
            <th class="w100">项目</th>
            <th class="align-center">说明</th>
            <th class="align-center">收支金额</th>
            <th class="align-center">可提现余额</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                <tr class="hover">
                    <td><?=$v['created_at']; ?></td>
                    <td><?=$v['item']; ?></td>
                    <td class="align-center"><?=$v['remark'];?></td>
                    <td class="align-center"><?=$v['money_type'].$v['money'];?></td>
                    <td class="align-center"><?=$v['credit_avaiable'];?></td>
                </tr>
            <?php } ?>
        <?php }else { ?>
            <tr class="no_data">
                <td colspan="5"><?php echo $lang['nc_no_record'];?></td>
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <tr class="tfoot">
                <td>&nbsp;</td>
                <td colspan="5">
                    <div class="pagination"> <?php echo $output['page'];?></div>
                </td>
            </tr>
        <?php } ?>
        </tfoot>
    </table>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/buttons.css"  />
<script>
    $(function() {
        $('#start_date').datepicker({dateFormat: 'yy-mm-dd'});
        $('#end_date').datepicker({dateFormat: 'yy-mm-dd'});
    });
</script>