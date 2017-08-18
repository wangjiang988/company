<style type="text/css">
    .tab-base em{ padding: 0 10px;}
    .search input.txt{width: 120px;}
    .search input.small{width: 63px;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>售方财务</h3>
            <ul class="tab-base">
               <li><a href="JavaScript:void(0);" class="current"><span>管理</span></a><em> | </em></li>
               <li><a href="<?=url('seller_finance','recharge');?>"><span>充值</span></a><em> | </em></li>
               <li><a href="<?=url('seller_finance','withdraw');?>"><span>提现</span></a><em> | </em></li>
               <li><a href="<?=url('seller_finance','special_service');?>"><span>特情审批</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form name="formSearch" id="searchFrom" action="<?=url('seller_finance','index')?>" method="get">
        <input type="hidden" value="seller_finance" name="act">
        <input type="hidden" value="index" name="op">
        <table class="tb-type1 noborder search" style="width:100%;">
            <tbody>
            <tr>
                <th><label for="search_title">售方用户名</label></th>
                <td>
                    <input type="text" value="<?=$output['search']['seller_name'];?>" name="seller_name" class="txt">                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="seller_name">售方姓名</label>
                    <input type="text" value="<?=$output['search']['member_truename'];?>" name="member_truename" class="txt">
                    <label for="name_phone">售方手机号</label>
                    <input type="text" value="<?=$output['search']['seller_phone'];?>" name="seller_phone" class="txt">
                    <label for="start_deposit">固定保证金</label>
                    ￥<input type="text" value="<?=$output['search']['start_deposit'];?>" name="start_deposit" class="txt small">~
                    <input type="text" value="<?=$output['search']['end_deposit'];?>" name="end_deposit" class="txt small">
                    <label for="start_credit">平台授信额度</label>
                    ￥<input type="text" value="<?=$output['search']['start_credit'];?>" name="start_credit" class="txt small">~
                    <input type="text" value="<?=$output['search']['end_credit'];?>" name="end_credit" class="txt small">
                </td>
            </tr>
            <tr>
                <th><label for="search_jxb">加信宝</label></th>
                <td>
                    ￥<input type="text" value="<?=$output['search']['start_jiaxb'];?>" name="start_jiaxb" class="txt small">~
                    <input type="text" value="<?=$output['search']['end_jiaxb'];?>" name="end_jiaxb" class="txt small">

                    <label for="seller_jiaxb">可提现余额</label>
                    ￥<input type="text" value="<?=$output['search']['start_avaliable'];?>" name="start_avaliable" class="txt small">~
                    <input type="text" value="<?=$output['search']['end_avaliable'];?>" name="end_avaliable" class="txt small">

                    <label for="seller_jiaxb">平台冻结</label>
                    ￥<input type="text" value="<?=$output['search']['start_ptdj'];?>" name="start_ptdj" class="txt small">~
                    <input type="text" value="<?=$output['search']['end_ptdj'];?>" name="end_ptdj" class="txt small">

                    <label for="seller_jiaxb">总资产</label>
                    ￥<input type="text" value="<?=$output['search']['start_total'];?>" name="start_total" class="txt small">~
                    <input type="text" value="<?=$output['search']['end_total'];?>" name="end_total" class="txt small">

                    <label for="seller_jiaxb">账户状态</label>
                    <select name="status">
                        <option value="">  ----全部----  </option>
                        <option value="4" <?=isSelected([$output['search']['status'],'==',4],'select')?> >受限</option>
                        <option value="2" <?=isSelected([$output['search']['status'],'==',2],'select')?>>失效</option>
                        <option value="-1" <?=isSelected([$output['search']['status'],'==',-1],'select')?>>透支</option>
                        <option value="1" <?=isSelected([$output['search']['status'],'==',1],'select')?>>有效</option>
                    </select>
                </td>
            </tr>
            <tr><th></th>
                <td>
                    <div style="float:right; text-align:left; width:auto;">
                        <button type="submit" class="button button-primary button-small" style="margin-right: 10px;">搜索</button>
                        <a href="<?=url('seller_finance','index')?>" class="button button-primary button-small" style="margin-right: 10px;">重置</a>
                        <a href="<?=url('seller_finance','exportFile',$output['uri'])?>" class="button button-primary button-small">导出</a>
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
            <th class="w60">用户名</th>
            <th class="w48">售方姓名</th>
            <th class="align-center">售方手机</th>
            <th class="align-center">固定保证金</th>
            <th class="align-center">平台授信额度</th>
            <th class="align-center">可提现余额</th>
            <th>加信宝</th>
            <th>平台冻结</th>
            <th>总资产</th>
            <th>账户状态</th>
            <th class="w120 align-center">操作</th>
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
                    <td class="nowrap align-center"><?=$v['avaliable_deposit']?></td>
                    <td><?=$v['jxb_total']?></td>
                    <td class="nowrap align-center"><?=$v['temp_deposit']?></td>
                    <td class="nowrap align-center"><?=$v['total_deposit']?></td>
                    <td class="nowrap align-center">
                        <?php
                        if($v['status'] ==2){
                            echo "失效";
                        }else{
                            if($v['avaliable_deposit'] >=0){
                                echo "有效";
                            }else{
                                $isOverdraft = getSellerOverdraftTime($v['$credit_line'],$v['overdraft_time']);
                                echo $isOverdraft ? "透支" : "受限" ;
                            }
                        }
                        //受限//1.当售方资金池可提现余额为负，且超过授信额度归还时限；2.可提现余额为负，且<—授信额度。
                        //透支//当—平台授信额度<=售方资金池可提现余额<0，且没超过授信额度归还时限。售方报价功能仍然不变。
                        ?>
                    </td>
                    <td class="align-center">
                        <a href="<?=url('seller_finance','avaliable',['id'=>$v['member_id']]); ?>">可提现余额</a>
                        |
                        <a href="<?=url('seller_finance','withdraw_end',['id'=>$v['member_id']]); ?>">特别情况</a>
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
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/buttons.css"  />
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