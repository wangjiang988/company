<?php defined('InHG') or exit('Access Invalid!'); extract($output);?>
    <div class="page finance">
        <div class="fixed-bar">
            <div class="item-title">
                <h3><a href="index.php?act=admin_finance&op=home">平台财务</a></h3>
                <ul class="tab-base">
                    <li><a class="current"><span>收入支出申报</span></a></li>
                </ul>
            </div>
        </div>
        <div class="fixed-empty"></div>

        <form method="get" action="index.php" name="formSearch" id="formSearch">
            <input type="hidden" name="act" value="admin_finance" />
            <input type="hidden" name="op" value="account_settlement" />
            <input type="hidden" name="is_search" value="1" />
            <input type="hidden" name="curpage" id="cur_page" value="<?php echo $_GET['curpage']?>" />
            <input type="hidden" name="export" id="export" value="0" />
            <table class="tb-type1 noborder search">
                <tbody>
                <tr>
                    <th>类型 </th>
                    <th class="input">
                        <span class="icon"></span>
                        <select name="type">
                            <option value="">全部</option>
                            <option value="10"
                                <?php if($_GET['type'] ==10 ) echo 'selected'; ?>
                            >订单收入</option>
                            <option value="20"
                                <?php if($_GET['type'] ==20 ) echo 'selected'; ?>
                            >提现手续费</option>
                            <option value="30"
                                <?php if($_GET['type'] ==30 ) echo 'selected'; ?>
                            >转入收入</option>
                            <option value="40"
                                <?php if($_GET['type'] ==40 ) echo 'selected'; ?>
                            >转出支出</option>
                        </select>
                    </th>
                    <th>业务申报年月 </th>
                    <th class="input">
                        <span class="icon"></span>
                        <select name="year">
                            <option value="">全部</option>
                            <?php if(!empty($output['years']) && is_array($output['years'])){ ?>
                            <?php foreach($output['years'] as $k => $v){ ?>
                                    <option value="<?=$v?>"
                                        <?php if($v ==$_GET['year'] ) echo 'selected'; ?>
                                    ><?=$v?></option>
                            <?php }?>
                            <?php }?>

                        </select>
                        <select name="month">
                            <option value="">全部</option>
                            <option value="1"
                                <?php if($_GET['month'] ==1 ) echo 'selected'; ?>
                            >01</option>
                            <option value="2"
                                <?php if($_GET['month'] ==2 ) echo 'selected'; ?>
                            >02</option>
                            <option value="3"
                                <?php if($_GET['month'] ==2 ) echo 'selected'; ?>
                            >03</option>

                            <option value="4"
                                <?php if($_GET['month'] ==4 ) echo 'selected'; ?>
                            >04</option>

                            <option value="5"
                                <?php if($_GET['month'] ==5 ) echo 'selected'; ?>
                            >05</option>

                            <option value="6"
                                <?php if($_GET['month'] ==6 ) echo 'selected'; ?>
                            >06</option>

                            <option value="7"
                                <?php if($_GET['month'] ==7 ) echo 'selected'; ?>
                            >07</option>
                            <option value="8"
                                <?php if($_GET['month'] ==8 ) echo 'selected'; ?>
                            >08</option>
                            <option value="9"
                                <?php if($_GET['month'] ==9 ) echo 'selected'; ?>
                            >09</option>
                            <option value="10"
                                <?php if($_GET['month'] ==10 ) echo 'selected'; ?>
                            >10</option>
                            <option value="2"
                                <?php if($_GET['month'] ==11 ) echo 'selected'; ?>
                            >11</option>
                            <option value="2"
                                <?php if($_GET['month'] ==12 ) echo 'selected'; ?>
                            >12</option>
                        </select>
                    </th>
                    <th>暂不申报 </th>
                    <th class="input">
                        <span class="icon"></span>
                        <select name="do_settlement">
                            <option value="">全部</option>
                            <option value="1"
                                <?php if($_GET['do_settlement'] ==1 ) echo 'selected'; ?>
                            >是</option>
                            <option value="0"
                                <?php if($_GET['do_settlement'] ==="0" ) echo 'selected'; ?>
                            >否</option>
                        </select>
                    </th>
                    <th>状态 </th>
                    <th class="input">
                        <span class="icon"></span>
                        <select name="status" id="">
                            <option value="">全部</option>
                            <option value="10"
                                <?php if($_GET['status'] ==10 ) echo 'selected'; ?>
                            >未申报</option>
                            <option value="20"
                                <?php if($_GET['status'] ==20 ) echo 'selected'; ?>
                            >已申报</option>
                            <option value="30"
                                <?php if($_GET['status'] ==30 ) echo 'selected'; ?>
                            >免申报</option>
                        </select>
                    </th>
                    <th>平台开票 </th>
                    <th class="input">
                        <span class="icon"></span>
                        <select name="is_invoice">
                            <option value="">全部</option>
                            <option value="1"
                                <?php if($_GET['is_invoice'] ==1 ) echo 'selected'; ?>
                            >是</option>
                            <option value="0"
                                <?php if($_GET['is_invoice'] ==="0" ) echo 'selected'; ?>
                            >否</option>
                        </select>
                    </th>
                    <th>售方入账</th>
                    <th class="input">
                        <span class="icon"></span>
                        <select name="to_seller_account" id="">
                            <option value="">全部</option>
                            <option value="1"
                                <?php if($_GET['to_seller_account'] ==1 ) echo 'selected'; ?>
                            >是</option>
                            <option value="0"
                                <?php if($_GET['to_seller_account'] ==="0" ) echo 'selected'; ?>
                            >否</option>
                        </select>
                    </th>
                </tr>
                <tr>
                    <th>说明 </th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="description" value="<?php echo trim($_GET['description']); ?>" />
                    </th>
                    <th>申报凭证号 </th>
                    <th class="input">
                        <span class="icon"></span>
                        <select name="maintenance_status">
                            <option value="">全部</option>
                            <option value="0"
                                <?php if($_GET['maintenance_status'] ==="0" ) echo 'selected'; ?>
                            >未添加</option>
                            <option value="1"
                                <?php if($_GET['maintenance_status'] ==="1" ) echo 'selected'; ?>
                            >已添加</option>
                        </select>
                        <span class="icon"></span><input type="text" class="text" name="voucher_number" value="<?php echo trim($_GET['from_name']); ?>" />
                    </th>
                </tr>
                <tr >
                    <th class="input" colspan="12">
                        <div class="pull-right">
                            <a href="JavaScript:void(0);" class="button" id="sub_btn"><span>查找</span></a>
                            <a href="index.php?act=admin_finance&op=account_settlement" class="button"><span>重置</span></a>
                            <a href="JavaScript:void(0);" class="button" id="export_btn"><span>导出</span></a>
                            <a href="index.php?act=admin_finance&op=account_declare_maintain"  class="ml-50 mr-50"><span>申报凭证号维护</span></a>
                        </div>
                    </th>
                </tr>
                </tbody>
            </table>
        </form>
        <table class="table tb-type2">
            <thead>
            <tr class="thead blue">
                <th >最近更新时间</th>
                <th >类别</th>
                <th >说明</th>
                <th >收入金额</th>
                <th >业务成本</th>
                <th >含税毛利</th>
                <th >业务申报年月</th>
                <th >暂不申报</th>
                <th >状态</th>
                <th >申报凭证号</th>
                <th >平台开票</th>
                <th >售方入账</th>
                <th >操作</th>
            </tr>
            </thead>
            <tbody id="datatable">
            <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
                <?php foreach($output['list'] as $k => $v){ ?>
                    <tr class="hover">
                        <td><?php echo $v['updated_at'] ?></td>
                        <td><?= show_declare_type($v['type']) ?></td>
                        <td><?= $v['description'] ?></td>
                        <td><?php echo $v['money'] ?></td>
                        <td><?= $v['first_cost'] ?></td>
                        <td><?= $v['gross_profit'] ?></td>
                        <td><?= $v['year'].'-'.$v['month'] ?></td>
                        <td><?php if($v['status'] ==10 ) { ?>
                                <input type="checkbox" >
                                  <?php if($v['year'].'-'.$v['month'] == get_now_year_month() ) echo "?";  ?>
                           <?php }
                           ?></td>
                        <td><?= show_declare_status($v['status']) ?></td>
                        <td><?php
                                    $voucher= '';
                                    if($v['income_voucher_number'])
                                        $voucher.=$v['income_voucher_number'];
                                    if($voucher && $v['firstcost_voucher_number'])
                                        $voucher.=','.$v['firstcost_voucher_number'];
                                    else
                                        $voucher.=$v['firstcost_voucher_number'];

                                    echo $voucher;
                            ?></td>
                        <td><?= show_declare_is_invoice($v['is_invoice']) ?></td>
                        <td><?= show_to_seller_account($v['to_seller_account']) ?></td>
                        <td>
                            <?php if($v['type'] !=40) {?>
                                <a href="javascript:void(0);" onclick="show_income_detail(<?=$v['type']?>,<?=$v['id']?>,1)">收入详情</a>
                            <?php }?>
                            <?php if($v['type'] !=30 && $v['type'] !=20 && $v['type'] !=21) {?>
                                <a href="javascript:void(0);"  onclick="show_income_detail(<?=$v['type']?>,<?=$v['id']?>,2)">成本详情</a>
                            <?php }?>
                            <a href="javascript:void(0);" onclick="show_comment(<?php echo $v['id']?>);">备注</a>
                        </td>
                    </tr>
                <?php } ?>
            <?php }else { ?>
                <tr class="no_data" id="no_data" data-has="1">
                    <td colspan="13"><?php echo $lang['nc_no_record'];?></td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
                <tr class="tfoot">
                    <td colspan="13"><div class="pagination"> <?php echo $output['page'];?> </div></td>
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
<script type="text/html" id="list_dialog">
    <div style="padding: 20px 50px;" id="list">
        <div class="pure-g">
            <div class="pure-u-12-24">
                订单号：<span id="order_num"></span>
            </div>
            <div class="pure-u-12-24">
                收入序列号：<span id="income_series_number"></span>
            </div>
        </div>
        <table class="table tb-type2">
            <thead>
            <tr class="thead blue">
                <th >项目</th>
                <th >进出金额</th>
                <th >时间</th>
            </tr>
            </thead>
            <tbody id="data_table">

            </tbody>
            <tfoot>
            <tr>
                <td colspan="3" style="text-align: center">
                    总金额:￥ <span id="total_num">0</span>
                </td>
            </tr>
            </tfoot>
        </table>
        <div class="panel_footer" style="margin-top:50px;padding: 10px;text-align: center;">
            <span class="label">
                <a href="javascript:void(0);" onclick="closeLayer();" class="button">关闭</a>
            </span>
        </div>
    </div>
</script>

<script type="text/html" id="reason_dialog">
    <div style="padding: 20px 50px;margin-top: 20px;font-size: 14px;" id="list">
        <div class="pure-g">
            <div class="pure-u-24-24" style="text-align: center">
                原因：<span id="reason"></span>
            </div>
        </div>

        <div class="panel_footer" style="margin-top:50px;padding: 10px;text-align: center;">
            <span class="label">
                <a href="javascript:void(0);" onclick="closeLayer();" class="button">关闭</a>
            </span>
        </div>
    </div>
</script>
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


    /**备注
     *
     */
    function show_comment(id)
    {
        comment_dialog = parent.layer.open({
            type: 2,
            title:"添加备注",
            skin: 'layui-layer-rim', //加上边框
            area: ['500px', '400px'], //宽高
            content: 'index.php?act=admin_finance&op=account_ajax_add_comment&declare_id='+id
        });
    }

    /**
     * 收入详情
     * flow_type 1  收入，2 成本
     */
    function show_income_detail(type,declare_id,flow_type){
        post('index.php?act=admin_finance&op=account_ajax_get_declare_reason',{
            'declare_id' : declare_id,
            'type'       : type,
            'flow_type' :flow_type
        }).then(function(res){
            if(res.data.code==200){
                show_income_result(type,res.data.data,flow_type);
            }
        }).catch(function(err){
            console.log(err);
        })
    }

    function show_income_result(type,declare,flow_type){
        let title  = "收入详情"
        if(flow_type  ==2 ) title= '成本详情'
        if(type =='10' || type =="30" || type=="40") //订单
        {
            let  html = $('#list_dialog').html();
            parent.layer.open({
                type: 2,
                title:title,
                skin: 'layui-layer-rim', //加上边框
                area: ['500px', '400px'], //宽高
                content:'index.php?act=admin_finance&op=account_ajax_show_income&flow_type='+flow_type+'&declare_id='+declare.id
            });
            $("#order_num").text(declare.order_id);
            $("#income_series_number").text(declare.income_series_number);
            if(declare.order_logs){
                let total  = 0 ;
                declare.order_logs.forEach(function(val,index,arr){
                    td = '<tr><td>'+val.remark+"</td>"
                    td += '<td>'+val.sign+' '+val.money+"</td>"
                    td += '<td>'+val.created_at+"</td></tr>"
                    if(val.sign =='+'){
                        total += parseFloat(val.money);
                    }else{
                        total -= parseFloat(val.money);
                    }
                    $("#data_table").append(td);
                    $("#total_num").text(total)
                })
            }

        }else if(type>=20 && type<30){ //提现手续费
            location.href = 'index.php?act=admin_finance&op=account_show_withdraw&declare_id='+declare.id;
        }
//        else if(type =="30" || type=="40"){ //转入收入  转出支出
//            let  html = $('#reason_dialog').html();
//            parent.layer.open({
//                type: 2,
//                title:"收入详情",
//                skin: 'layui-layer-rim', //加上边框
//                area: ['400px', '250px'], //宽高
//                content:html
//            });
//            $("#reason").text(declare.application.reason);
//        }
    }


</script>
