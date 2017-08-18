<style type="text/css">
    .tab-base em{ padding: 0 10px;}
    .search input.txt{width: 120px;}
    .search input.date , .search input.date:hover{width:100px;}
    .search input.text{width: 280px;}
    .table .thead{ background: #00BFFF;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>售方财务</h3>
            <ul class="tab-base">
                <li><a href="<?=url('seller_finance','index');?>"><span>管理</span></a><em> | </em></li>
                <li><a href="JavaScript:void(0);" class="current"><span>特别情况</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form name="formSearch" id="searchFrom" action="<?=url('seller_finance','withdraw_end')?>" method="get">
        <input type="hidden" value="seller_finance" name="act">
        <input type="hidden" value="withdraw_end" name="op">
        <input type="hidden" name="id" value="<?=$output['seller']['member_id']?>" />
        <table class="tb-type1 noborder search" style="width:100%;">
            <tbody>
            <tr>
                <td><label>售方用户名：</label><span><?=$output['seller']['member_name'];?></span></td>
                <td><label>售方姓名：<span><?=$output['seller']['member_truename'];?></span></label></td>
                <td><label>售方手机号：</label><span><?=$output['seller']['member_mobile'];?></span></td>
                <td><div style="float:right; text-align:left; width:auto;">
                        <a href="<?=url('seller_finance','add_special',['id'=>$output['seller']['member_id']])?>" class="button button-highlight button-small">发起申请</a>
                    </div>
                </td>
            </tr>
            <tr>
                <td><label>售方可提现余额：</label><span>￥：<?=$output['seller']['avaliable_deposit'];?></span></td>
                <td colspan="2"><label>平台冻结可提现余额：</label><span>￥<?=$output['seller']['temp_deposit'];?></span></td>
                <td><label>状态：</label>
                    <select name="status" onchange="searchStatus()">
                        <option value="">  --全部--  </option>
                        <option value="0" <?=isSelected([$output['search']['status'],'==',0],'select')?> >待批准</option>
                        <option value="1" <?=isSelected([$output['search']['status'],'==',1],'select')?>>已通过</option>
                        <option value="2" <?=isSelected([$output['search']['status'],'==',2],'select')?>>未通过</option>
                    </select>
                </td>
            </tr>
            </tbody>
        </table>
    </form>

    <table class="table tb-type2">
        <thead>
        <tr class="thead">
            <th class="w60">工单编号</th>
            <th class="w80">工单时间</th>
            <th class="w60">申请项目与金额</th>
            <th class="w60">原因</th>
            <th class="align-center">申请人</th>
            <th class="align-center">状态</th>
            <th class="w30 align-center">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])) {
            foreach ($output['list'] as $k => $v) {
                ?>
                <tr class="hover">
                <td><?= $v['id']; ?></td>
                <td><?= $v['created_at'] ?></td>
                <td><?= $v['name'] . ',' . $v['money']; ?></td>
                <td><?= $v['reason']; ?></td>
                <td class="align-center"><?= $v['apply_admin_name']; ?></td>
                <td class="align-center">
                <?php
                $statusArr = ['待批准', '通过', '未通过'];
                echo $statusArr[$v['status']];
                ?>
            </td>
            <td class="align-center">
                <a href="<?= url('seller_finance', 'special_detail', ['id' => $v['id']]); ?>">查看</a>
            </td>
            </tr>
            <?php
            }}else {
            ?>
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
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/buttons.css"  />
<script>
    function searchStatus(){
        $('#searchFrom').submit();
    }
</script>