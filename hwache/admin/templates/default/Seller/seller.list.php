<?php defined('InHG') or exit('Access Invalid!');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><?php echo $lang['index_title'];?></h3>
            <ul class="tab-base">
                <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_manage'];?></span></a></li>
                <li><a href="<?=url('seller','add');?>"><span><?php echo $lang['nc_new'];?></span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form method="get" name="formSearch" id="searchFrom" action="<?=url('seller','list')?>">
        <input type="hidden" value="seller" name="act">
        <input type="hidden" value="list" name="op">
        <table class="tb-type1 noborder search" style="width:100%;">
            <tbody>
            <tr>
                <th><label for="search_title">用户名</label></th>
                &nbsp;&nbsp;
                <td><input type="text" value="<?=$output['search']['keyword'];?>" name="search_keyword" class="txt">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                <label for="name_phone">姓名/手机号</label>
                    &nbsp;&nbsp;
                <input type="text" value="<?=$output['search']['name_phone'];?>" name="name_phone" class="txt">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                <label for="status">启用状态</label>
                    &nbsp;&nbsp;
                    <select name="status" id="status" class="">
                        <option value=""><?php echo $lang['nc_please_choose'];?>...</option>
                        <option value="0">未审核</option>
                        <option value="1">审核通过</option>
                        <option value="2">账户冻结</option>
                    </select>
                    &nbsp;&nbsp;&nbsp;&nbsp;
               <label for="kf_user">专属客服</label>
                    &nbsp;&nbsp;
                    <select name="kf_user" id="kf_user" class="">
                        <option value=""><?php echo $lang['nc_please_choose'];?>...</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="search_title">新增时间</label>&nbsp;&nbsp;</th>
                <td>
                    <div style="float: left; text-align: left; width: 80%">
                        <input type="text" name="start_date" value="<?=$output['search']['start_date'];?>" id="start_date" class="txt date">
                        &nbsp;&nbsp;-&nbsp;&nbsp;
                        <input type="text" name="end_date" value="<?=$output['search']['end_date'];?>" id="end_date" class="txt date">
                    </div>
                    <div style="float:right; text-align:left; width: 20%;">
                        <button type="submit" class="btn-search "><?php echo $lang['nc_query'];?></button>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="button" class="btn" onclick="resetForm();">重置</button>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </form>

    <table class="table tb-type2">
            <thead>
            <tr class="thead">
                <th class="w24"><?=$lang['seller_id'];?></th>
                <th class="w60">商户名</th>
                <th class="w48">商户姓名</th>
                <th class="align-center">手机号</th>
                <th class="align-center">固定保证金</th>
                <th class="align-center">平台授信额度</th>
                <th class="align-center">专属客服</th>
                <th>启用状态</th>
                <th class="w60 align-center"><?=$lang['operation'];?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
                <?php foreach($output['list'] as $k => $v){ ?>
                    <tr class="hover">
                        <td><?=$v['seller_id']; ?></td>
                        <td><?=$v['seller_name']; ?></td>
                        <td><?=$v['member_truename']; ?></td>
                        <td class="align-center"><?=$v['member_mobile'];?></td>
                        <td class="align-center"><?=$v['basic_deposit'];?></td>
                        <td class="align-center"><?=$v['credit_line'];?></td>

                        <td class="nowrap align-center">暂无</td>
                        <td><?php echo sellerStatus($v['status']); ?></td>
                        <td class="align-center">
                        <a href="<?=url('seller','view',['id'=>$v['seller_id']]); ?>">查看</a>
                            |
                        <a href="<?=url('seller','edit',['id'=>$v['seller_id']]); ?>"><?php echo $lang['nc_edit'];?></a>
                        </td>
                    </tr>
                <?php } ?>
            <?php }else { ?>
                <tr class="no_data">
                    <td colspan="10"><?php echo $lang['nc_no_record'];?></td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
                <tr class="tfoot">
                    <td>&nbsp;</td>
                    <td colspan="16">
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
<script>
    function resetForm(){
        $('#searchFrom').find(':input').not(':button, :submit, :reset').val('').removeAttr('checked').removeAttr('selected');
        location.href = "<?=url('seller','list')?>";
    }
    $(function() {
        $('#start_date').datepicker({dateFormat: 'yy-mm-dd'});
        $('#end_date').datepicker({dateFormat: 'yy-mm-dd'});
    });
</script>
