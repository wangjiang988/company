<?php defined('InHG') or exit('Access Invalid!');?>
<?php if($output['recharge_method'] ==2){ //银行转账模板 ?>
    <div class="page finance">
        <div class="fixed-bar">
            <div class="item-title">
                <h3>客户财务</h3>
                <ul class="tab-base">
                    <li><a href="index.php?act=finance&op=index"><span>客户财务</span></a></li>
                    <li><a href="javascript:void(0);" class="current"><span>转入-银行转账</span></a></li>
                    <li><a href="index.php?act=finance&op=recharge_index&recharge_method=1"><span>转入-线上支付</span></a></li>
                    <li><a href="index.php?act=finance&op=withdraw_index"><span>提现</span></a></li>
                    <li><a href="index.php?act=finance&op=special_index"><span>特事审批</span></a></li>
                </ul>
            </div>
        </div>
        <div class="fixed-empty"></div>

        <form method="get" action="index.php" name="formSearch" id="formSearch">
            <input type="hidden" name="act" value="finance" />
            <input type="hidden" name="op" value="recharge_index" />
            <input type="hidden" name="is_search" value="1" />
            <input type="hidden" name="recharge_method" value="<?php echo $output['recharge_method'];?>" />
            <input type="hidden" name="curpage" id="cur_page" value="<?php echo $_GET['curpage']?>" />
            <input type="hidden" name="export" id="export" value="0" />
            <table class="tb-type1 noborder search">
                <tbody>
                <tr>
                    <th>转入编号 </th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="ur_id" value="<?php echo trim($_GET['ur_id']); ?>" />
                    </th>
                    <th>客户手机 </th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="phone" value="<?php echo trim($_GET['phone']); ?>" />
                    </th>
                    <th>汇款人账号 </th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="bank_account" value="<?php echo trim($_GET['bank_account']); ?>" />
                    </th>
                    <th>汇款人户名 </th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="user_bank_name" value="<?php echo trim($_GET['user_bank_name']); ?>" />
                    </th>
                    <th>转入用途 </th>
                    <th class="input">
                        <span class="icon"></span>
                        <select name="use_type" id="">
                            <option value="">全部</option>
                            <option value="0"
                                <?php if($_GET['use_type'] ==="0" ) echo 'selected'; ?>
                            >充值</option>
                            <option value="1"
                                <?php if($_GET['use_type'] ==1 ) echo 'selected'; ?>
                            >支付买车担保金</option>
                            <option value="2"
                                <?php if($_GET['use_type'] ==2 ) echo 'selected'; ?>
                            >支付诚意金</option>
                        </select>
                    </th>


                </tr>
                <tr>
                    <th>提交金额 </th>
                    <th class="input">
                        <span class="icon">￥</span><input type="text" class="text wt80" name="s_money" value="<?php echo trim($_GET['s_money']); ?>" />
                        -
                        <input type="text" class="text wt80" name="e_money" value="<?php echo trim($_GET['e_money']); ?>" />
                    </th>
                    <th>入账金额 </th>
                    <th class="input">
                        <span class="icon">￥</span><input type="text" class="text wt80" name="s_recharge_money" value="<?php echo trim($_GET['s_recharge_money']); ?>" />
                        -
                        <input type="text" class="text wt80" name="e_recharge_money" value="<?php echo trim($_GET['e_recharge_money']); ?>" />
                    </th>
                    <th>银行凭证号</th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="trade_no" value="<?php echo trim($_GET['trade_no']); ?>" />
                    </th>
                    <th>记账凭证号 </th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="accounting_voucher" value="<?php echo trim($_GET['accounting_voucher']); ?>" />
                    </th>

                    <th>状态 </th>
                    <th class="input">
                        <span class="icon"></span>
                        <select name="status" id="">
                            <option value="">全部</option>
                            <option value="0"
                                <?php if(!isset($_GET['status'])||$_GET['status'] ==="0" ) echo 'selected'; ?>
                            >待入账</option>
                            <option value="1"
                                <?php if($_GET['status'] ==1 ) echo 'selected'; ?>
                            >核实入账</option>
                            <option value="2"
                                <?php if($_GET['status'] ==2 ) echo 'selected'; ?>
                            >已入账未补充</option>
                            <option value="3"
                                <?php if($_GET['status'] ==3 ) echo 'selected'; ?>
                            >已入账已补充</option>
                            <option value="4"
                                <?php if($_GET['status'] ==4 ) echo 'selected'; ?>
                            >无此款项</option>
                        </select>
                    </th>

                </tr>
                <tr>
                    <th>订单号 </th>
                    <th class="input">
                        <span class="icon">&nbsp;</span><input type="text" class="text" name="trade_no" value="<?php echo trim($_GET['trade_no']); ?>" />
                    </th>
                    <th>工单时间 </th>
                    <th class="input" colspan="3">
                        <span class="icon"></span><input type="text" class="text wt150 date" id="s_created_at" name="s_created_at" value="<?php echo trim($_GET['s_created_at']); ?>" />
                        -
                        <input type="text" class="text wt150 date" id="e_created_at" name="e_created_at" value="<?php if(empty($_GET['e_recharge_confirm_at'])) echo get_now2();else echo trim($_GET['e_created_at']); ?>" />
                    </th>
                    <th>确认入账时间 </th>
                    <th class="input" colspan="3">
                        <span class="icon">&nbsp;</span><input type="text" class="text wt150 date" id="s_recharge_confirm_at" name="s_recharge_confirm_at" value="<?php echo trim($_GET['s_recharge_confirm_at']); ?>" />
                        -
                        <input type="text" class="text wt150 date" id="e_recharge_confirm_at" name="e_recharge_confirm_at" value="<?php if(empty($_GET['e_recharge_confirm_at'])) echo get_now2();else echo trim($_GET['e_recharge_confirm_at']); ?>" />
                    </th>
                </tr>
                <tr >
                    <th class="input" colspan="10">
                        <div class="pull-right">
                            <a href="JavaScript:void(0);" class="button" id="sub_btn"><span>查找</span></a>
                            <a href="index.php?act=finance&op=recharge_index" class="button" ><span>重置</span></a>
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
                <th class="wt70">转入编号</th>
                <th >客户手机</th>
                <th >提交金额</th>
                <th >汇款银行</th>
                <th >汇款人账号</th>
                <th >汇款人户名</th>
                <th >转入用途</th>
                <th >入账金额</th>
                <th >银行凭证号</th>
                <th >状态</th>
                <th class="align-center">操作</th>
            </tr>
            </thead>
            <tbody id="datatable">
            <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
                <?php foreach($output['list'] as $k => $v){ ?>
                    <tr class="hover">
                        <td><?php echo $v['ur_id']?></td>
                        <td><?php echo $v['phone'] ?></td>
                        <td><?php echo $v['money']?></td>
                        <td><?php echo $v['bank_name']?></td>
                        <td><?php echo $v['bank_account']?></td>
                        <td><?php echo $v['user_bank_name']?></td>
                        <td><?php
                            if(!in_array($v['status'],[0,1,4])){
                                echo show_recharge_use_type($v['use_type']);
                                if($v['use_type']>0) echo "(订单号：{$v['order_id']})";
                            }
                            ?></td>
                        <td><?php
                            if($v['status'] ==4){
                                  echo 0;
                            }elseif($v['status']>1){
                                echo $v['recharge_money'];
                            }
                           ?></td>

                        <td>

                            <?php
                            if(!in_array($v['status'],[0,1,4])){
                                echo $v['trade_no'];
                            }
                          ?>
                        </td>
                        <td>
                            <?php echo show_recharge_status($v['status']); ?>
                        </td>
                        <td>
                            <a href="index.php?act=finance&op=recharge_detail&ur_id=<?php echo $v['ur_id'] ?>">查看</a>
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
<?php }else{  //线上支付模板 ?>
    <div class="page finance">
        <div class="fixed-bar">
            <div class="item-title">
                <h3>客户财务</h3>
                <ul class="tab-base">
                    <li><a href="index.php?act=finance&op=index"><span>客户财务</span></a></li>
                    <li><a href="index.php?act=finance&op=recharge_index" ><span>转入-银行转账</span></a></li>
                    <li><a href="javascript:void(0);" class="current"><span>转入-线上支付</span></a></li>
                    <li><a href="index.php?act=finance&op=withdraw_index"><span>提现</span></a></li>
                    <li><a href="index.php?act=finance&op=special_index"><span>特事审批</span></a></li>
                </ul>
            </div>
        </div>
        <div class="fixed-empty"></div>

        <form method="get" action="index.php" name="formSearch" id="formSearch">
            <input type="hidden" name="act" value="finance" />
            <input type="hidden" name="op" value="recharge_index" />
            <input type="hidden" name="is_search" value="1" />
            <input type="hidden" name="recharge_method" value="<?php echo $output['recharge_method'];?>" />
            <input type="hidden" name="curpage" id="cur_page" value="<?php echo $_GET['curpage']?>" />
            <input type="hidden" name="export" id="export" value="0" />
            <table class="tb-type1 noborder search">
                <tbody>
                <tr>
                    <th>转入编号 </th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="ur_id" value="<?php echo trim($_GET['ur_id']); ?>" />
                    </th>
                    <th>客户会员号 </th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="user_id" value="<?php echo trim($_GET['user_id']); ?>" />
                    </th>
                    <th>转入方账户 </th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="alipay_user_name" value="<?php echo trim($_GET['alipay_user_name']); ?>" />
                    </th>
                    <th>支付方式 </th>
                    <th class="input">
                        <span class="icon"></span>
                        <select name="recharge_type">
                            <option value="">全部</option>
                            <option value="1"
                                <?php if($_GET['recharge_type'] ==1 ) echo 'selected'; ?>
                            >支付宝</option>
                            <option value="3"
                                <?php if($_GET['recharge_type'] ==3 ) echo 'selected'; ?>
                            >财付通</option>
                            <option value="4"
                                <?php if($_GET['recharge_type'] ==4 ) echo 'selected'; ?>
                            >通联支付</option>
                        </select>
                    </th>
                    <th>转入用途 </th>
                    <th class="input">
                        <span class="icon"></span>
                        <select name="use_type" id="">
                            <option value="">全部</option>
                            <option value="0"
                                <?php if($_GET['use_type'] ==="0" ) echo 'selected'; ?>
                            >充值</option>
                            <option value="1"
                                <?php if($_GET['use_type'] ==1 ) echo 'selected'; ?>
                            >支付买车担保金</option>
                        </select>
                    </th>


                </tr>
                <tr>
                    <th>提交金额 </th>
                    <th class="input">
                        <span class="icon">￥</span><input type="text" class="text wt80" name="s_money" value="<?php echo trim($_GET['s_money']); ?>" />
                        -
                        <input type="text" class="text wt80" name="e_money" value="<?php echo trim($_GET['e_money']); ?>" />
                    </th>
                    <th>入账金额 </th>
                    <th class="input">
                        <span class="icon">￥</span><input type="text" class="text wt80" name="s_recharge_money" value="<?php echo trim($_GET['s_recharge_money']); ?>" />
                        -
                        <input type="text" class="text wt80" name="e_recharge_money" value="<?php echo trim($_GET['e_recharge_money']); ?>" />
                    </th>
                    <th>入账流水号</th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="trade_no" value="<?php echo trim($_GET['trade_no']); ?>" />
                    </th>
                    <th>订单号 </th>
                    <th class="input">
                        <span class="icon">&nbsp;</span><input type="text" class="text" name="trade_no" value="<?php echo trim($_GET['trade_no']); ?>" />
                    </th>

                    <th>状态 </th>
                    <th class="input">
                        <span class="icon"></span>
                        <select name="status" id="">
                            <option value="">全部</option>
                            <option value="0"
                                <?php if($_GET['status'] ==="0" ) echo 'selected'; ?>
                            >待入账</option>
                            <option value="1"
                                <?php if($_GET['status'] ==2 ) echo 'selected'; ?>
                            >已入账</option>
                            <option value="4"
                                <?php if($_GET['status'] ==4 ) echo 'selected'; ?>
                            >无此款项</option>
                        </select>
                    </th>

                </tr>
                <tr>

                    <th>工单时间 </th>
                    <th class="input" colspan="3">
                        <span class="icon"></span><input type="text" class="text wt150 date" id="s_created_at" name="s_created_at" value="<?php echo trim($_GET['s_created_at']); ?>" />
                        -
                        <input type="text" class="text wt150 date" id="e_created_at" name="e_created_at" value="<?php if(empty($_GET['e_recharge_confirm_at'])) echo get_now();else echo trim($_GET['e_created_at']); ?>" />
                    </th>
                    <th>确认入账时间 </th>
                    <th class="input" colspan="3">
                        <span class="icon">&nbsp;</span><input type="text" class="text wt150 date" id="s_recharge_confirm_at" name="s_recharge_confirm_at" value="<?php echo trim($_GET['s_recharge_confirm_at']); ?>" />
                        -
                        <input type="text" class="text wt150 date" id="e_recharge_confirm_at" name="e_recharge_confirm_at" value="<?php if(empty($_GET['e_recharge_confirm_at'])) echo get_now();else echo trim($_GET['e_recharge_confirm_at']); ?>" />
                    </th>
                </tr>
                <tr >
                    <th class="input" colspan="10">
                        <div class="pull-right">
                            <a href="JavaScript:void(0);" class="button" id="sub_btn"><span>查找</span></a>
                            <a href="index.php?act=finance&op=recharge_index&recharge_method=1" class="button" ><span>重置</span></a>
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
                <th class="wt70">转入编号</th>
                <th >客户会员号</th>
                <th >提交金额</th>
                <th >支付方式</th>
                <th >转入方账户</th>
                <th >转入用途</th>
                <th >入账金额</th>
                <th >入账流水号</th>
                <th >状态</th>
                <th class="align-center">操作</th>
            </tr>
            </thead>
            <tbody id="datatable">
            <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
                <?php foreach($output['list'] as $k => $v){ ?>
                    <tr class="hover">
                        <td><?php echo $v['ur_id']?></td>
                        <td><?php echo $v['user_id'] ?></td>
                        <td><?php echo $v['money']?></td>
                        <td><?php echo show_recharge_type($v['recharge_type'])?></td>
                        <td><?php echo $v['alipay_user_name']?></td>
                        <td><?php
                            if(!in_array($v['status'],["0","1","4"])){
                                echo show_recharge_use_type($v['use_type']);
                                if($v['use_type']>0) echo "(订单号：{$v['order_id']})";
                            }
                            ?></td>
                        <td><?php echo $v['recharge_money']?></td>

                        <td>
                            <?php echo $v['trade_no']; ?>
                        </td>
                        <td>
                            <?php echo show_recharge_status($v['status'],1); ?>
                        </td>
                        <td>
                            <a href="index.php?act=finance&op=recharge_detail&recharge_method=1&ur_id=<?php echo $v['ur_id'] ?>">查看</a>
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
<?php } ?>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<script type="text/javascript">

    $(function(){
        $('#s_created_at').datepicker({dateFormat: 'yy-mm-dd'});
        $('#e_created_at').datepicker({dateFormat: 'yy-mm-dd'});

        $('#s_recharge_confirm_at').datepicker({dateFormat: 'yy-mm-dd'});
        $('#e_recharge_confirm_at').datepicker({dateFormat: 'yy-mm-dd'});

        //导出图表
        $("#export_btn").click(function(){
            let has  =  $('#no_data').data('has');
            if(has) alert('没有数据导出');
            else{
                $("#export").val(1);
                document.formSearch.submit();
            }
        });

        $("#sub_btn").click(function(){
            $("#cur_page").val(1);
            $("#export").val(0);
            document.formSearch.submit();
        });
    });
</script>
