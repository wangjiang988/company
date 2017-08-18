<style type="text/css">
    .tab-base em{ padding: 0 10px;}
    .search input.txt{width: 120px;}
    .search input.date , .search input.date:hover{width:100px;}
    .search input.text{width: 280px;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>售方财务</h3>
            <ul class="tab-base">
                <li><a href="<?=url('seller_finance','index');?>"><span>管理</span></a><em> | </em></li>
                <li><a href="JavaScript:void(0);" class="current"><span>充值</span></a><em> | </em></li>
                <li><a href="<?=url('seller_finance','withdraw');?>"><span>提现</span></a><em> | </em></li>
                <li><a href="<?=url('seller_finance','special_service');?>"><span>特情审批</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form name="formSearch" id="searchFrom" action="<?=url('seller_finance','recharge')?>" method="get">
        <input type="hidden" value="seller_finance" name="act">
        <input type="hidden" value="recharge" name="op">
        <table class="tb-type1 noborder search" style="width:100%;">
            <tbody>
            <tr>
                <th><label for="search_title">工单编号：</label></th>
                <td>
                    <input type="text" value="<?=$output['search']['code'];?>" name="code" class="txt">
                    <label for="search_title">售方用户名：</label>
                    <input type="text" value="<?=$output['search']['seller_name'];?>" name="seller_name" class="txt">                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="seller_name">售方姓名：</label>
                    <input type="text" value="<?=$output['search']['member_truename'];?>" name="member_truename" class="txt">
                    <label for="name_phone">售方手机号：</label>
                    <input type="text" value="<?=$output['search']['seller_phone'];?>" name="seller_phone" class="txt">
                    <label for="seller_jiaxb">入账状态：</label>
                    <select name="status">
                        <option value="">  ----全部----  </option>
                        <option value="-1" <?=isSelected([$output['search']['status'],'==',-1],'select')?> >待入账</option>
                        <option value="1" <?=isSelected([$output['search']['status'],'==',1],'select')?>>核实入账</option>
                        <option value="2" <?=isSelected([$output['search']['status'],'==',2],'select')?>>已入账未补充</option>
                        <option value="3" <?=isSelected([$output['search']['status'],'==',3],'select')?>>已入账已补充</option>
                        <option value="4" <?=isSelected([$output['search']['status'],'==',4],'select')?>>无此款项</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="search_jxb">提交金额：</label></th>
                <td>
                    ￥<input type="text" value="<?=$output['search']['start_money'];?>" name="start_money" class="txt">~
                    <input type="text" value="<?=$output['search']['end_money'];?>" name="end_money" class="txt">

                    <label for="seller_jiaxb">入账金额：</label>
                    ￥<input type="text" value="<?=$output['search']['start_recorded'];?>" name="start_recorded" class="txt">~
                    <input type="text" value="<?=$output['search']['end_recorded'];?>" name="end_recorded" class="txt">

                    <label for="seller_jiaxb">工单时间：</label>
                    <input type="text" value="<?=$output['search']['start_order_time'];?>" name="start_order_time" class="date">~
                    <input type="text" value="<?=$output['search']['end_order_time'];?>" name="end_order_time" class="date">
                </td>
            </tr>

            <tr>
                <th><label for="seller_jiaxb">提交时间：</label></th>
                <td>
                    <input type="text" value="<?=$output['search']['start_submit_time'];?>" name="start_submit_time" class="date">~
                    <input type="text" value="<?=$output['search']['end_submit_time'];?>" name="end_submit_time" class="date">
                    <label for="search_jxb">确认入账时间：</label>
                    <input type="text" value="<?=$output['search']['start_recorded_time'];?>" name="start_recorded_time" class="date">~
                    <input type="text" value="<?=$output['search']['end_recorded_time'];?>" name="end_recorded_time" class="date">
                </td>
            </tr>

            <tr>
                <th><label for="seller_jiaxb">银行凭证号：</label></th>
                <td>
                    <input type="text" value="<?=$output['search']['bank_voucher_code'];?>" name="bank_voucher_code" class="text">

                    <label for="seller_jiaxb">记账凭证号：</label>
                    <input type="text" value="<?=$output['search']['accounting_voucher'];?>" name="accounting_voucher" class="text">
                    <div style="float:right; text-align:left; width:auto;">
                        <button type="submit" class="button button-primary button-small" style="margin-right: 10px;">搜索</button>
                        <a href="<?=url('seller_finance','recharge')?>" class="button button-primary button-small" style="margin-right: 10px;">重置</a>
                        <a href="<?=url('seller_finance','exportRecharge',$output['uri'])?>" class="button button-primary button-small">导出</a>
                    </div>
                </td>
            </tr>

            </tbody>
        </table>
    </form>

    <table class="table tb-type2">
        <thead>
        <tr class="thead">
            <th class="w60">工单编号</th>
            <th class="w80">提交时间</th>
            <th class="w60">用户名</th>
            <th class="w60">售方姓名</th>
            <th class="align-center">售方手机</th>
            <th class="align-center">提交金额</th>
            <th class="align-center">充值方式</th>
            <th>入账金额</th>
            <th>确认入账时间</th>
            <th>入账状态</th>
            <th class="w30 align-center">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                <tr class="hover">
                    <td><?=$v['drb_id']; ?></td>
                    <td><?=$v['created_at']?></td>
                    <td><?=$v['member_name']; ?></td>
                    <td><?=$v['member_truename']; ?></td>
                    <td class="align-center"><?=$v['member_mobile'];?></td>
                    <td class="align-center"><?=$v['money'];?></td>
                    <td class="nowrap align-center"><?php
                       echo chanageStr($v['bank_account'],0,-4,'***').chanageStr($v['daili_bank_name'],3,strlen($v['daili_bank_name']),'***');
                       ?></td>
                    <td>
                        <?php
                        if(in_array($v['kefu_confirm_status'],[2,3])){
                            echo $v['money'];
                        }
                        if($v['kefu_confirm_status'] ==4){
                            echo 0;
                        }
                        ?>
                    </td>
                    <td class="nowrap align-center">
                        <?php
                        if(in_array($v['kefu_confirm_status'],[2,3,4])){
                            echo $v['updated_at'];
                        }
                        ?>
                    </td>
                    <td class="nowrap align-center">
                        <?php
                        $statusArr = ['待入账','核实入账','已入账未补充','已入账已补充','无此款项'];
                        echo $statusArr[$v['kefu_confirm_status']];
                        ?>
                    </td>
                    <td class="align-center">
                        <a href="<?=url('seller_finance','recharge_detail',['id'=>$v['drb_id']]); ?>">查看</a>
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
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/buttons.css"  />
<script>
    function resetForm(){
        $('#searchFrom').find(':input').not(':button, :submit, :reset').val('').removeAttr('checked').removeAttr('selected');
        location.href = "<?=url('seller','list')?>";
    }
    $(function() {
        $('.date').datepicker({dateFormat: 'yy-mm-dd'});
    });
</script>