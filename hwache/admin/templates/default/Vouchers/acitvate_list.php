<style type="text/css">
    .tab-base em{ padding: 0 10px;}
    .search input.txt,.search select{width: 120px; float: left;}
    .search label{ float: left; margin:0 15px;}
    .search span{width: 200px; margin: 0 30px 0 0; float: left;}
    .search input.big{width: 300px;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>代金券</h3>
            <ul class="tab-base">
                <li><a href="<?=url('hc_vouchers','index');?>"><span>管理</span></a><em> | </em></li>
                <li><a href="javascript:;" class="current"><span>查看激活码</span></a><em> </em></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"><?php $search=$output['search']?></div>
    <form name="formSearch" id="searchFrom" action="" method="get">
        <input type="hidden" value="hc_vouchers" name="act">
        <input type="hidden" value="showAcitvated" name="op">
        <input type="hidden" name="id" value="<?=$output['info']['id']?>" />
        <table class="tb-type1 noborder search" style="width:100%;">
            <tbody>
            <tr>
                <th><label>申请投放编号：</label></th>
                <td>
                    <span><?=$output['info']['id']?></span>
                    <label>有效激活时间：</label>
                    <span><?=$output['info']['life_start_time'].'~'.$output['info']['life_end_time']?></span>
                    <label>本次投放条数：</label>
                    <span><?=$output['info']['release_total_num']?></span>
                </td>
            </tr>
            <tr>
                <th><label for="search_jxb">已激活条数：</label></th>
                <td>
                    <span><?=$output['info']['activated_total']?></span>
                    <label for="brand_id">激活率：</label>
                    <span><?=$output['info']['activated_rate'].'%'?></span>
                </td>
            </tr>

            <tr>
                <th>激活码状态</th>
                <td>
                    <select name="activated">
                        <option value="">--全部--</option>
                        <option value="2" <?=isSelected([$output['search']['activated'],'==',2],'select')?>>已激活</option>
                        <option value="1" <?=isSelected([$output['search']['activated'],'==',1],'select')?>>未激活</option>
                    </select>
                    <label>激活码</label>
                    <input type="text" class="txt big" name="code" value="<?=$output['search']['code']?>">
                    <div style="float:right; text-align:left; width:auto;">
                        <button type="submit" class="button button-primary button-small">搜索</button>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </form>

    <table class="table tb-type2">
        <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                <tr class="hover">
                    <?php
                    foreach($v as $key =>$value) {
                    ?>
                        <td><?=$value['activated_code']; ?></td>
                    <?php
                    }
                    ?>
                </tr>
            <?php } ?>
        <?php }else { ?>
            <tr class="no_data">
                <td colspan="12"><?php echo $lang['nc_no_record'];?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <div style="width: 100%; padding:10px;">
        <p style="height: 40px; line-height: 40px;"><a href="<?=url('hc_vouchers','exportActivated',['id'=>$output['info']['id']])?>">导出日志</a> </p>
        <p style="height: 40px; line-height: 40px; text-align: center;">
            <a href="<?=url('hc_vouchers','exportActivated',['id'=>$output['info']['id']])?>" class="button button-primary button-small" style="margin-right: 100px;"> 导出 </a>
            <a href="<?=url('hc_vouchers','group');?>" class="button button-primary button-small"> 返回 </a>
        </p>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/buttons.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/Vouchers/group.js" charset="utf-8"></script>