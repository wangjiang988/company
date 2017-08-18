<?php defined('InHG') or exit('Access Invalid!');?>
    <div class="page finance">
        <div class="fixed-bar">
            <div class="item-title">
                <h3><a href="index.php?act=admin_finance&op=home">平台财务</a></h3>
                <ul class="tab-base">
                    <li><a class="current"><span>账户资金流动</span></a></li>
                </ul>
            </div>
        </div>
        <div class="fixed-empty"></div>

        <form method="get" action="index.php" name="formSearch" id="formSearch">
            <input type="hidden" name="act" value="admin_finance" />
            <input type="hidden" name="op" value="account_log" />
            <input type="hidden" name="is_search" value="1" />
            <input type="hidden" name="curpage" id="cur_page" value="<?php echo $_GET['curpage']?>" />
            <input type="hidden" name="export" id="export" value="0" />
            <table class="tb-type1 noborder search">
                <tbody>
                <tr>
                    <th>发生时间 </th>
                    <th class="input" colspan="3">
                        <span class="icon"></span><input type="text" class="text wt150 date" id="s_created_at" name="s_created_at" value="<?php echo trim($_GET['s_created_at']); ?>" />
                        -
                        <input type="text" class="text wt150 date" id="e_created_at" name="e_created_at" value="<?php echo trim($_GET['e_created_at']); ?>" />
                    </th>
                    <th>发生金额 </th>
                    <th class="input" colspan="3">
                        <span class="icon">&nbsp;</span>￥<input type="text" class="text wt150" id="s_money" name="s_money" value="<?php echo trim($_GET['s_money']); ?>" />
                        -
                        <input type="text" class="text wt150 " id="e_money" name="e_money" value="<?php echo trim($_GET['e_money']); ?>" />
                    </th>
                </tr>
                <tr>
                    <th>支出方 </th>
                    <th class="input" colspan="3">
                        <span class="icon"></span>
                        <select name="from_where" id="">
                            <option value="">全部</option>
                            <option value="1"
                                <?php if($_GET['from_where'] ==="1" ) echo 'selected'; ?>
                            >客户</option>
                            <option value="2"
                                <?php if($_GET['from_where'] ==2 ) echo 'selected'; ?>
                            >售方</option>
                            <option value="3"
                                <?php if($_GET['from_where'] ==3 ) echo 'selected'; ?>
                            >平台</option>
                            <option value="4"
                                <?php if($_GET['from_where'] ==4 ) echo 'selected'; ?>
                            >外部</option>
                        </select>
                        <span class="icon"></span><input type="text" class="text" name="from_name" value="<?php echo trim($_GET['from_name']); ?>" />
                    </th>
                    <th>收入方 </th>
                    <th class="input" colspan="3">
                        <span class="icon"></span>
                        <select name="to_where" id="">
                            <option value="">全部</option>
                            <option value="1"
                                <?php if($_GET['to_where'] ==="1" ) echo 'selected'; ?>
                            >客户</option>
                            <option value="2"
                                <?php if($_GET['to_where'] ==2 ) echo 'selected'; ?>
                            >售方</option>
                            <option value="3"
                                <?php if($_GET['to_where'] ==3 ) echo 'selected'; ?>
                            >平台</option>
                            <option value="4"
                                <?php if($_GET['to_where'] ==4 ) echo 'selected'; ?>
                            >外部</option>
                        </select>
                        <span class="icon"></span><input type="text" class="text" name="to_name" value="<?php echo trim($_GET['to_name']); ?>" />
                    </th>
                    <th>流水号 </th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="trade_no" value="<?php echo trim($_GET['trade_no']); ?>" />
                    </th>
                </tr>
                <tr>
                    <th>支出方说明 </th>
                    <th class="input">
                        <span class="icon"></span>
                        <select name="" id="">
                            <option value="">全部</option>
                            <option value="1"
                                <?php if($_GET['recharge_method'] ==1 ) echo 'selected'; ?>
                            >线上支付</option>
                            <option value="2"
                                <?php if($_GET['recharge_method'] ==2 ) echo 'selected'; ?>
                            >银行转账</option>
                        </select>
                    </th>
                    <th>收入方说明 </th>
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

                    <th>订单号 </th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="order_id" value="<?php echo trim($_GET['order_id']); ?>" />
                    </th>
                </tr>
                <tr >
                    <th class="input" colspan="6">
                        <div class="pull-left">
                            <a href="JavaScript:void(0);" class="button" id="sub_btn"><span>查找</span></a>
                            <a href="index.php?act=admin_finance&op=account_log" class="button"><span>重置</span></a>
                            <a href="JavaScript:void(0);" class="button" id="export_btn"><span>导出</span></a>
                        </div>
                    </th>
                    <th colspan="2"> 记录条数  &nbsp;&nbsp;<span style="display: inline-block;min-width: 100px;" id="count"></span></th>
                    <th colspan="2">发生金额合计 &nbsp;&nbsp; <span style="display: inline-block;min-width: 100px;"  id="total_money"></span> </th>
                    <th>
                        <a href="JavaScript:void(0);" class="button" onclick="calc();"><span>计算</span></a>
                    </th>
                </tr>
                </tbody>
            </table>
        </form>
        <table class="table tb-type2">
            <thead>
            <tr class="thead blue">
                <th >发生时间</th>
                <th >发生金额</th>
                <th >支出方</th>
                <th >支出方说明</th>
                <th >收入方</th>
                <th >收入方说明</th>
                <th >订单号</th>
                <th >流水号</th>
            </tr>
            </thead>
            <tbody id="datatable">
            <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
                <?php foreach($output['list'] as $k => $v){ ?>
                    <tr class="hover">
                        <td><?php echo $v['created_at'] ?></td>
                        <td><?php echo $v['money']?></td>
                        <td><?= show_account_log_where($v,'from') ?></td>
                        <td><?php echo $v['from_remark'] ?></td>
                        <td><?= show_account_log_where($v,'to') ?></td>
                        <td><?php echo $v['to_remark'] ?></td>
                        <td><?php echo $v['order_id']?$v['order_id']:''; ?></td>
                        <td><?php echo $v['trade_no'] ?></td>
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
                    <td colspan="8"><div class="pagination"> <?php echo $output['page'];?> </div></td>
                </tr>
            <?php } ?>
            </tfoot>
        </table>
        <div class="clear"></div>
    </div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>

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
    function calc()
    {
        let json  = $('#formSearch').serializeJSON();
        url = 'index.php?act=admin_finance&op=account_calc'
        post(url, json).then(function(res){
            if(res.data.code == 200)
            {
                let data  = res.data.data;
                $('#count').text(data.count);
                $('#total_money').text(data.total)
            }else
            {
                alert(res.data.msg);
            }
        }).catch(function(err){
            console.log(err);
        });
    }

</script>
