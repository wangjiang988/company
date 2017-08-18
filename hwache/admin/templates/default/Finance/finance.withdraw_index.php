<?php defined('InHG') or exit('Access Invalid!');?>
    <div class="page finance">
        <div class="fixed-bar">
            <div class="item-title">
                <h3>客户财务</h3>
                <ul class="tab-base">
                    <li><a href="index.php?act=finance&op=index"><span>客户财务</span></a></li>
                    <li><a href="index.php?act=finance&op=recharge_index"><span>转入-银行转账</span></a></li>
                    <li><a href="index.php?act=finance&op=recharge_index&recharge_method=1"><span>转入-线上支付</span></a></li>
                    <li><a href="javascript:void(0);" class="current"><span>提现</span></a></li>
                    <li><a href="index.php?act=finance&op=special_index"><span>特事审批</span></a></li>
                </ul>
            </div>
        </div>
        <div class="fixed-empty"></div>

        <form method="get" action="index.php" name="formSearch" id="formSearch">
            <input type="hidden" name="act" value="finance" />
            <input type="hidden" name="op" value="withdraw_index" />
            <input type="hidden" name="is_search" value="1" />
            <input type="hidden" name="curpage" id="cur_page" value="<?php echo $_GET['curpage']?>" />
            <input type="hidden" name="export" id="export" value="0" />
            <table class="tb-type1 noborder search">
                <tbody>
                <tr>
                    <th>提现编号 </th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="uw_id" value="<?php echo trim($_GET['uw_id']); ?>" />
                    </th>
                    <th>客户会员号 </th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="user_id" value="<?php echo trim($_GET['user_id']); ?>" />
                    </th>
                    <th>客户手机 </th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="phone" value="<?php echo trim($_GET['phone']); ?>" />
                    </th>
                    <th>收款方</th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="bank_account" value="<?php echo trim($_GET['bank_account']); ?>" />
                    </th>
                    <th>提现付款金额 </th>
                    <th class="input">
                        <span class="icon">￥</span><input type="text" class="text wt80" name="s_recharge_money" value="<?php echo trim($_GET['s_recharge_money']); ?>" />
                        -
                        <input type="text" class="text wt80" name="e_recharge_money" value="<?php echo trim($_GET['e_recharge_money']); ?>" />
                    </th>
                </tr>
                <tr>
                    <th>银行凭证号/转账流水号 </th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="trade_no" value="<?php echo trim($_GET['trade_no']); ?>" />
                    </th>
                    <th>记账凭证号 </th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="accounting_voucher" value="<?php echo trim($_GET['accounting_voucher']); ?>" />
                    </th>

                    <th>提现方式 </th>
                    <th class="input">
                        <span class="icon"></span>
                        <select name="recharge_method" id="">
                            <option value="">全部</option>
                            <option value="1"
                                <?php if($_GET['recharge_method'] ==1 ) echo 'selected'; ?>
                            >线上支付</option>
                            <option value="2"
                                <?php if($_GET['recharge_method'] ==2 ) echo 'selected'; ?>
                            >银行转账</option>
                        </select>
                    </th>
                    <th>收款路线</th>
                    <th class="input">
                        <span class="icon"></span>
                        <select name="line_type" id="">
                            <option value="">全部</option>
                            <option value="1"
                                <?php if($_GET['line_type'] ==1 ) echo 'selected'; ?>
                            >支付宝</option>
                            <option value="2"
                                <?php if($_GET['line_type'] ==2 ) echo 'selected'; ?>
                            >银行卡</option>
                            <option value="3"
                                <?php if($_GET['line_type'] ==3 ) echo 'selected'; ?>
                            >微信</option>
                            <option value="4"
                                <?php if($_GET['line_type'] ==4 ) echo 'selected'; ?>
                            >银联</option>
                        </select>
                    </th>
                    <th>状态 </th>
                    <th class="input">
                        <span class="icon"></span>
                        <select name="status" id="">
                            <option value="">全部</option>
                            <option value="0"
                                <?php if($_GET['status'] === "0" ) echo 'selected'; ?>
                            >待批准</option>
                            <option value="2"
                                <?php if($_GET['status'] ==2 ) echo 'selected'; ?>
                            >待接单</option>
                            <option value="3"
                                <?php if($_GET['status'] ==3 ) echo 'selected'; ?>
                            >待转账</option>
                            <option value="5"
                                <?php if($_GET['status'] ==5 ) echo 'selected'; ?>
                            >已转账未补充</option>
                            <option value="1"
                                <?php if($_GET['status'] ==1 ) echo 'selected'; ?>
                            >已转账已补充</option>
                            <option value="4"
                                <?php if($_GET['status'] ==4 ) echo 'selected'; ?>
                            >未成功</option>
                        </select>
                    </th>

                </tr>
                <tr>
                    <th>申请时间 </th>
                    <th class="input" colspan="3">
                        <span class="icon"></span><input type="text" class="text wt150 date" id="s_created_at" name="s_created_at" value="<?php echo trim($_GET['s_created_at']); ?>" />
                        -
                        <input type="text" class="text wt150 date" id="e_created_at" name="e_created_at" value="<?php echo trim($_GET['e_created_at']); ?>" />
                    </th>
                    <th>转账时间 </th>
                    <th class="input" colspan="3">
                        <span class="icon">&nbsp;</span><input type="text" class="text wt150 date" id="s_transfer_at" name="s_transfer_at" value="<?php echo trim($_GET['s_transfer_at']); ?>" />
                        -
                        <input type="text" class="text wt150 date" id="e_transfer_at" name="e_transfer_at" value="<?php echo trim($_GET['e_transfer_at']); ?>" />
                    </th>
                </tr>
                <tr>
                    <th>状态更新时间 </th>
                    <th class="input" colspan="3">
                        <span class="icon"></span><input type="text" class="text wt150 date" id="s_updated_at" name="s_updated_at" value="<?php echo trim($_GET['s_updated_at']); ?>" />
                        -
                        <input type="text" class="text wt150 date" id="e_updated_at" name="e_updated_at" value="<?php echo trim($_GET['e_updated_at']); ?>" />
                    </th>
                    <th>办理步骤 </th>
                    <th class="input">
                        <span class="icon"></span>
                        <select name="step" id="">
                            <option value="">全部</option>
                            <option value="0"
                                <?php if($_GET['step'] ==="0" ) echo 'selected'; ?>
                            >批准</option>
                            <option value="2"
                                <?php if($_GET['step'] ==2 ) echo 'selected'; ?>
                            >接单</option>
                            <option value="3"
                                <?php if($_GET['step'] ==3 ) echo 'selected'; ?>
                            >转账</option>
                            <option value="5"
                                <?php if($_GET['step'] ==5 ) echo 'selected'; ?>
                            >补充</option>
                            <option value="4"
                                <?php if($_GET['step'] ==4 ) echo 'selected'; ?>
                            >报错</option>
                        </select>
                    </th>
                    <th>步骤操作人 </th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="user_name" value="<?php echo trim($_GET['user_name']); ?>" />
                    </th>
                </tr>
                <tr >
                    <th class="input" colspan="10">
                        <div class="pull-right">
                            <a href="JavaScript:void(0);" class="button" id="sub_btn"><span>查找</span></a>
                            <a href="index.php?act=finance&op=withdraw_index" class="button"><span>重置</span></a>
                            <a href="JavaScript:void(0);" class="button" id="export_btn"><span>导出</span></a>
                        </div>
                    </th>
                </tr>
                </tbody>
            </table>
        </form>
        <table class="table tb-type2">
            <thead>
            <tr class="thead blue">
                <th class="wt70">提现编号</th>
                <th >申请时间</th>
                <th >客户会员号</th>
                <th >客户手机号</th>
                <th >提现付款金额</th>
                <th >提现方式</th>
                <th >收款方</th>
                <th >银行凭证号/转账流水号</th>
                <th >状态更新时间</th>
                <th >状态</th>
                <th class="align-center">操作</th>
            </tr>
            </thead>
            <tbody id="datatable">
            <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
                <?php foreach($output['list'] as $k => $v){ ?>
                    <tr class="hover">
                        <td><?php echo $v['uw_id']?></td>
                        <td><?php echo $v['created_at'] ?></td>
                        <td><?php echo $v['user_id'] ?></td>
                        <td><?php echo $v['u_phone'] ?></td>
                        <td><?php echo $v['money']-$v['fee']?></td>
                        <td><?php echo show_recharge_method($v['ur_recharge_type'])?></td>
                        <td><?php if($v['ur_recharge_type']!=2){
                                       echo show_recharge_type($v['ur_recharge_type']).'/'.$v['ur_alipay_user_name'];
                                    }else{
                                        echo $v['ur_bank_account'].'/'.$v['ur_user_bank_name'];
                                   }
                            ?></td>
                        <td><?php
                            if(!in_array($v['status'],[5])){ //已转账未补充阶段 这个不显示
                                echo $v['transfer_trade_no'];
                            }
                            ?>
                           </td>
                        <td><?php
                            if(!in_array($v['status'],[0])) {
                                echo $v['updated_at'];
                            }
                            ?></td>
                        <td>
                            <?php echo show_withdraw_status($v['status']); ?>
                        </td>
                        <td>
                            <a href="index.php?act=finance&op=withdraw_detail&uw_id=<?php echo $v['uw_id'] ?>">查看</a>
                        </td>
                    </tr>
                <?php } ?>
            <?php }else { ?>
                <tr class="no_data" id="no_data" data-has="1">
                    <td colspan="12"><?php echo $lang['nc_no_record'];?></td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
                <tr class="tfoot">
                    <td colspan="20"><div class="pagination"> <?php echo $output['page'];?> </div></td>
                </tr>
            <?php } ?>
            </tfoot>
        </table>
        <div class="clear"></div>
    </div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<script type="text/javascript">

    $(function(){

        $("#sub_btn").click(function(){
            $("#cur_page").val(1);
            $("#export").val(0);
            document.formSearch.submit();
        });
        //申请时间
        $('#s_created_at').datepicker({dateFormat: 'yy-mm-dd'});
        $('#e_created_at').datepicker({dateFormat: 'yy-mm-dd'});
        //转账时间
        $('#s_transfer_at').datepicker({dateFormat: 'yy-mm-dd'});
        $('#e_transfer_at').datepicker({dateFormat: 'yy-mm-dd'});
        //状态更新时间
        $('#s_updated_at').datepicker({dateFormat: 'yy-mm-dd'});
        $('#e_updated_at').datepicker({dateFormat: 'yy-mm-dd'});
        //导出图表
        $("#export_btn").click(function(){
            let has  =  $('#no_data').data('has');
            if(has) alert('没有数据导出');
            else{
                $("#export").val(1);
                document.formSearch.submit();
            }
        });


    });
</script>
