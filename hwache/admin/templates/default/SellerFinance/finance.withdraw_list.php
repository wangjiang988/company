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
                <li><a href="<?=url('seller_finance','recharge');?>"><span>充值</span></a><em> | </em></li>
                <li><a href="JavaScript:void(0);" class="current"><span>提现</span></a><em> | </em></li>
                <li><a href="<?=url('seller_finance','special_service');?>"><span>特情审批</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form name="formSearch" id="searchFrom" action="<?=url('seller_finance','withdraw')?>" method="get">
        <input type="hidden" value="seller_finance" name="act">
        <input type="hidden" value="withdraw" name="op">
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
                    <label for="name_phone">收款方：</label>
                    <input type="text" value="<?=$output['search']['receipt_user'];?>" name="receipt_user" class="txt">
                </td>
            </tr>
            <tr>
                <th><label for="seller_jiaxb">提现手续费：</label></th>
                <td>
                    <select name="fee">
                        <option value="">  --全部--  </option>
                        <option value="1" <?=isSelected([$output['search']['fee'],'==',1],'select')?> >有</option>
                        <option value="4" <?=isSelected([$output['search']['fee'],'==',4],'select')?>>无</option>
                    </select>
                    <label for="search_jxb">提交金额：</label>
                    ￥<input type="text" value="<?=$output['search']['start_money'];?>" name="start_money" class="txt">~
                    <input type="text" value="<?=$output['search']['end_money'];?>" name="end_money" class="txt">
                    <label for="seller_jiaxb">银行凭证号：</label>
                    <input type="text" value="<?=$output['search']['bank_voucher_code'];?>" name="bank_voucher_code" class="tet">
                    <label for="seller_jiaxb">记账凭证号：</label>
                    <input type="text" value="<?=$output['search']['accounting_voucher'];?>" name="accounting_voucher" class="tet">

                    <label for="seller_jiaxb">状态：</label>
                    <select name="status">
                        <option value="">  --全部--  </option>
                        <option value="-1" <?=isSelected([$output['search']['status'],'==',-1],'select')?> >待批准</option>
                        <option value="3" <?=isSelected([$output['search']['status'],'==',3],'select')?>>待接单</option>
                        <option value="4" <?=isSelected([$output['search']['status'],'==',4],'select')?>>待转账</option>
                        <option value="2" <?=isSelected([$output['search']['status'],'==',2],'select')?>>已转账未补充</option>
                        <option value="1" <?=isSelected([$output['search']['status'],'==',1],'select')?>>已转账已补充</option>
                        <option value="5" <?=isSelected([$output['search']['status'],'==',5],'select')?>>未成功</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th><label for="seller_jiaxb">工单时间：</label></th>
                <td>
                    <input type="text" value="<?=$output['search']['start_order_time'];?>" name="start_order_time" class="date">~
                    <input type="text" value="<?=$output['search']['end_order_time'];?>" name="end_order_time" class="date">
                    <label for="search_jxb">确认入账时间：</label>
                    <input type="text" value="<?=$output['search']['start_recorded_time'];?>" name="start_recorded_time" class="date">~
                    <input type="text" value="<?=$output['search']['end_recorded_time'];?>" name="end_recorded_time" class="date">
                    <label for="seller_jiaxb">状态更新时间：</label>
                    <input type="text" value="<?=$output['search']['start_update_time'];?>" name="start_update_time" class="date">~
                    <input type="text" value="<?=$output['search']['end_update_time'];?>" name="end_update_time" class="date">
                </td>
            </tr>

            <tr>
                <th><label>办理步骤：</label></th>
                <td>
                    <select name="step">
                        <option value="">全部</option>
                        <option value="1" <?=isSelected([$output['search']['step'],'==',1],'select')?> >批准</option>
                        <option value="2" <?=isSelected([$output['search']['step'],'==',2],'select')?> >接单</option>
                        <option value="3" <?=isSelected([$output['search']['step'],'==',3],'select')?> >转账</option>
                        <option value="4" <?=isSelected([$output['search']['step'],'==',4],'select')?> >补充</option>
                        <option value="5" <?=isSelected([$output['search']['step'],'==',5],'select')?> >报错</option>
                    </select>
                    <label>步骤操作人：</label>
                    <input type="text" name="step_user_name" value="<?=$output['search']['step_user_name']?>" class="text" />
                    <div style="float:right; text-align:left; width:auto;">
                        <button type="submit" class="button button-primary button-small">搜索</button>
                        <a href="<?=url('seller_finance','withdraw')?>" class="button button-primary button-small">重置</a>
                        <a href="<?=url('seller_finance','exportWithdraw',$output['uri'])?>" class="button button-primary button-small">导出</a>
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
            <th class="w80">工单时间</th>
            <th class="w60">售方用户名</th>
            <th class="w60">售方姓名</th>
            <th class="align-center">售方手机</th>
            <th class="align-center">提现金额</th>
            <th class="align-center">提现手续费</th>
            <th class="align-center">收款方</th>
            <th>银行凭证号</th>
            <th>状态更新时间</th>
            <th>状态</th>
            <th class="w30 align-center">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                <tr class="hover">
                    <td><?=$v['dwb_id']; ?></td>
                    <td><?=$v['created_at']?></td>
                    <td><?=$v['member_name']; ?></td>
                    <td><?=$v['member_truename']; ?></td>
                    <td class="align-center"><?=$v['member_mobile'];?></td>
                    <td class="align-center"><?=$v['money'];?></td>
                    <td class="align-center"><?=$v['fee']?></td>
                    <td class="nowrap align-center"><?php
                        echo chanageStr($v['bank_account'],0,-4,'***').chanageStr($v['daili_bank_name'],3,strlen($v['daili_bank_name']),'***');
                        ?></td>
                    <td><?=$v['bank_voucher_code']?>
                    </td>
                    <td class="nowrap align-center">
                        <?php
                        if($v['kefu_confirm_status'] > 0){
                            echo $v['updated_at'];
                        }
                        ?>
                    </td>
                    <td class="nowrap align-center">
                        <?php
                        $statusArr = ['待批准','已转账已补充','已转账未补充','待接单','待转账','未成功','已转账已补充待更正','已转账已补充已更正'];
                        if($v['kefu_confirm_status']==5){
                            switch($v['reject_status']){
                                case 51://平台拒绝
                                    echo '平台拒绝';
                                    break;
                                default:
                                    echo $statusArr[$v['kefu_confirm_status']];
                            }
                        }else{
                            echo $statusArr[$v['kefu_confirm_status']];
                        }
                        ?>
                    </td>
                    <td class="align-center">
                        <a href="<?=url('seller_finance','withdraw_detail',['id'=>$v['dwb_id']]); ?>">查看</a>
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
    $(function() {
        $('.date').datepicker({dateFormat: 'yy-mm-dd'});
    });
</script>