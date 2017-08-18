<?php defined('InHG') or exit('Access Invalid!'); extract($output);?>
    <div class="page finance">
        <div class="fixed-bar">
            <div class="item-title">
                <h3><a href="index.php?act=admin_finance&op=home">平台财务</a></h3>
                <ul class="tab-base">
                    <li><a  href="index.php?act=admin_finance&op=account_settlement"><span>收入支出申报</span></a></li>
                    <li><a class="current"><span>申报凭证号维护</span></a></li>
                </ul>
            </div>
        </div>
        <div class="fixed-empty"></div>

        <form method="get" action="index.php" name="formSearch" id="formSearch">
            <input type="hidden" name="act" value="admin_finance" />
            <input type="hidden" name="op" value="account_declare_maintain" />
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
                    <th>维护状态 </th>
                    <th class="input">
                        <span class="icon"></span>
                        <select name="maintenance_status">
                            <option value="">全部</option>
                            <option value="0"
                                <?php if($_GET['maintenance_status']  ==="0" ) echo 'selected'; ?>
                            >未添加</option>
                            <option value="1"
                                <?php if($_GET['maintenance_status'] ==1) echo 'selected'; ?>
                            >已添加</option>
                        </select>
                    </th>
                    <th>说明 </th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="description" value="<?php echo trim($_GET['description']); ?>" />
                    </th>
                </tr>
                <tr>
                    <th>收入序列号 </th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="income_series_number" value="<?php echo trim($_GET['income_series_number']); ?>" />
                    </th>
                    <th>收入凭证号 </th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="income_voucher_number" value="<?php echo trim($_GET['income_voucher_number']); ?>" />
                    </th>
                    <th>成本序列号 </th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="firstcost_series_number" value="<?php echo trim($_GET['firstcost_series_number']); ?>" />
                    </th>
                    <th>成本凭证号 </th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="firstcost_voucher_number" value="<?php echo trim($_GET['firstcost_voucher_number']); ?>" />
                    </th>

                </tr>
                <tr >
                    <th class="input" colspan="12">
                        <div class="pull-right">
                            <a href="JavaScript:void(0);" class="button" id="sub_btn"><span>查找</span></a>
                            <a href="index.php?act=admin_finance&op=account_declare_maintain" class="button"><span>重置</span></a>
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
                <th >业务申报年月</th>
                <th >类别</th>
                <th >说明</th>
                <th >维护状态</th>
                <th >收入金额</th>
                <th >收入序列号</th>
                <th >收入凭证号</th>
                <th >业务成本</th>
                <th >成本序列号</th>
                <th >成本凭证号</th>
                <th >操作</th>
            </tr>
            </thead>
            <tbody id="datatable">
            <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
                <?php foreach($output['list'] as $k => $v){ ?>
                    <tr class="hover">
                        <td><?=$v['year'].'-'.$v['month']?></td>
                        <td><?=show_declare_type($v['type'])?></td>
                        <td><?=$v['description']?></td>
                        <td><?=show_declare_maintenance_status($v['maintenance_status'])?></td>
                        <td><?=$v['money']?></td>
                        <td><?=$v['income_series_number']?></td>
                        <td><?=$v['income_voucher_number']?></td>
                        <td><?=$v['first_cost']?></td>
                        <td><?=$v['firstcost_series_number']?></td>
                        <td><?=$v['firstcost_voucher_number']?></td>
                        <td>
                            <?php if($v['maintenance_status'] =="0") {?>
                                <a href="javascript:void(0);" onclick="show_add_maintain(<?=$v['id']?>)">添加 </a>
                            <?php }else{?>
                                <a href="javascript:void(0);" onclick="modify_maintain(<?=$v['id']?>)">修改 </a>
                                <a href="javascript:void(0);" onclick="show_log(<?php echo $v['id']?>);">日志</a>
                            <?php }?>
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



<script type="text/javascript">

    $(function(){
        $("#sub_btn").click(function(){
            $("#cur_page").val(1);
            $("#export").val(0);
            document.formSearch.submit();
        });

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


    /**
     * 添加
     */
    function show_add_maintain(id)
    {
        add_maintain = parent.layer.open({
            type: 2,
            title:"添加申报凭证号",
            skin: 'layui-layer-rim', //加上边框
            area: ['500px', '400px'], //宽高
            content: "index.php?act=admin_finance&op=account_ajax_get_declare&declare_id="+id
        });
    }
//修改
    function modify_maintain(id){
        update_maintain = parent.layer.open({
            type: 2,
            title:"修改申报凭证号",
            skin: 'layui-layer-rim', //加上边框
            area: ['500px', '400px'], //宽高
            content: "index.php?act=admin_finance&op=account_ajax_update_declare&declare_id="+id
        });
    }
    //日志
    function show_log(id){
        maintain_log = parent.layer.open({
            type: 2,
            title:"查看日志",
            skin: 'layui-layer-rim', //加上边框
            area: ['500px', '500px'], //宽高
            content: "index.php?act=admin_finance&op=account_ajax_declare_log&declare_id="+id
        });
    }

</script>
