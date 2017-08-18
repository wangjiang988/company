<style type="text/css">
    .tab-base em{ padding: 0 10px;}
    .search input.txt{width: 120px;}
    .search input.small{width: 63px;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>代金券</h3>
            <ul class="tab-base">
                <?php
                    require_once('vouchers_nav.php');
                ?>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>

        <table class="tb-type1 noborder search" style="width:100%;">
            <tbody>
            <tr>
                <th></th>
                <td>
                    <div style="float:right; text-align:left; width:auto;">
                        <a href="<?=url('hc_vouchers','add_clever')?>" class="button button-primary button-small">添加</a>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>


    <table class="table tb-type2">
        <thead>
        <tr class="thead">
            <th class="w85">编码</th>
            <th class="w60">功能名称</th>
            <th class="w48">描述</th>
            <th class="align-center">投放对象</th>
            <th class="align-center">来源</th>
            <th class="w120 align-center">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                <tr class="hover">
                    <td><?=$v['voucher_sn']; ?></td>
                    <td class="align-center"><?=$v['tf_id'];?></td>
                    <td class="align-center"><?=$v['user_id'];?></td>
                    <td>TODO</td>
                    <td class="nowrap align-center">TODO</td>
                    <td class="align-center">
                        <a href="<?=url('hc_vouchers','view',['id'=>$v['id']]); ?>">查看</a>
                    </td>
                </tr>
            <?php } ?>
        <?php }else { ?>
            <tr class="no_data">
                <td colspan="12"><?php echo $lang['nc_no_record'];?></td>
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <tr class="tfoot">
                <td>&nbsp;</td>
                <td colspan="12">
                    <div class="pagination"> <?php echo $output['page'];?></div>
                </td>
            </tr>
        <?php } ?>
        </tfoot>
    </table>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/buttons.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/Vouchers/group.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/region.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script>
