<?php defined('InHG') or exit('Access Invalid!');?>
    <div class="page finance">
        <div class="fixed-bar">
            <div class="item-title">
                <h3><a href="index.php?act=admin_finance&op=home">平台财务</a></h3>
                <ul class="tab-base">
                    <li><a class="current"><span>售方收入开票入账</span></a></li>
                </ul>
            </div>
        </div>
        <div class="fixed-empty"></div>

        <form method="get" action="index.php" name="formSearch" id="formSearch">
            <input type="hidden" name="act" value="admin_finance" />
            <input type="hidden" name="op" value="seller_settlement" />
            <input type="hidden" name="is_search" value="1" />
            <input type="hidden" name="curpage" id="cur_page" value="<?php echo $_GET['curpage']?>" />
            <input type="hidden" name="export" id="export" value="0" />
            <table class="tb-type1 noborder search">
                <tbody>
                <tr>
                    <th class="th_title">售方用户名 </th>
                    <th class="input"  style="width: 15%">
                        <span class="icon"></span><input type="text" class="text" name="member_name" value="<?php echo trim($_GET['member_name']); ?>" />
                    </th>

                    <th class="th_title">姓名 </th>
                    <th class="input"  >
                        <span class="icon"></span><input type="text" class="text" name="member_truename" value="<?php echo trim($_GET['member_truename']); ?>" />
                    </th>

                    <th class="th_title ml-30" >结算年月 </th>
                    <th class="input" style="width: 15%">
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
                                <?php if($_GET['month'] ==3 ) echo 'selected'; ?>
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
                            <option value="11"
                                <?php if($_GET['month'] ==11 ) echo 'selected'; ?>
                            >11</option>
                            <option value="12"
                                <?php if($_GET['month'] ==12 ) echo 'selected'; ?>
                            >12</option>
                        </select>
                    </th>

                    <th class="th_title">售方入账状态 </th>
                    <th class="input" >
                        <span class="icon"></span>
                        <select name="status">
                            <option value="">全部</option>
                            <option value="0"
                                <?php if($_GET['status'] ==="0" ) echo 'selected'; ?>
                            >未入账</option>
                            <option value="1"
                                <?php if($_GET['status'] ==1 ) echo 'selected'; ?>
                            >已入账</option>
                        </select>
                    </th>


                </tr>
                <tr>
                    <th>结算总金额：￥ </th>
                    <th class="input" colspan="3">
                        <span class="icon"></span><input type="text" class="text wt150" id="s_money" name="s_money" value="<?php echo trim($_GET['s_money']); ?>" />
                        -
                        <input type="text" class="text wt150" id="e_money" name="e_money" value="<?php echo trim($_GET['e_money']); ?>" />
                    </th>
                    <th  >实入售方金额：￥ </th>
                    <th class="input" colspan="3">
                        <span class="icon"></span><input type="text" class="text wt150" id="s_confirm_money" name="s_confirm_money" value="<?php echo trim($_GET['s_confirm_money']); ?>" />
                        -
                        <input type="text" class="text wt150" id="e_confirm_money" name="e_confirm_money" value="<?php echo trim($_GET['e_confirm_money']); ?>" />
                    </th>
                </tr>
                <tr>
                    <th>实入售方时间：￥ </th>
                    <th class="input" colspan="3">
                        <span class="icon"></span><input type="text" class="text wt150 date" id="s_confirm_at" name="s_confirm_at" value="<?php echo trim($_GET['s_confirm_at']); ?>" />
                        -
                        <input type="text" class="text wt150 date" id="e_confirm_at" name="e_confirm_at" value="<?php echo trim($_GET['e_confirm_at']); ?>" />
                    </th>
                    <th>结算文件剩余数 </th>
                    <th class="input" >
                        <span class="icon"></span><input type="text" class="text" name="file_number" value="<?php echo trim($_GET['file_number']); ?>" />
                    </th>
                </tr>

              <tr >
                    <th class="input" colspan="5">
                        <div class="pull-left">
                            <a href="JavaScript:void(0);" class="button" id="sub_btn"><span>查找</span></a>
                            <a href="index.php?act=admin_finance&op=seller_settlement" class="button"><span>重置</span></a>
                            <a href="JavaScript:void(0);" class="button" id="export_btn"><span>导出</span></a>
                        </div>
                    </th>
                    <th >结算总金额合计  &nbsp;&nbsp;<span style="display: inline-block;min-width: 100px;" id="count"></span></th>
                    <th>实入售方金额合计 &nbsp;&nbsp; <span style="display: inline-block;min-width: 100px;"  id="total_money"></span> </th>
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
                <th >结算年月</th>
                <th >售方用户名</th>
                <th >姓名</th>
                <th >身份证号</th>
                <th >结算文件剩余数</th>
                <th >结算总金额</th>
                <th >实入售方金额</th>
                <th >实入售方时间</th>
                <th >操作</th>
            </tr>
            </thead>
            <tbody id="datatable">
            <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
                <?php foreach($output['list'] as $k => $v){ ?>
                    <tr class="hover">
                        <td><?php echo $v['year'].'-'.$v['month'] ?></td>
                        <td><?php echo $v['member_name']?></td>
                        <td><?php echo $v['member_truename']?></td>
                        <td><?php echo $v['seller_card_num']?></td>
                        <td><?php if($v['status']>0)  echo  '—' ; else echo $v['file_number'] ?></td>
                        <td><?php echo "￥".$v['money'] ?></td>
                        <td><?php if($v['status']>0) echo "￥".$v['confirm_money'] ?></td>
                        <td><?php if($v['status']>0) echo $v['confirm_at'] ?></td>
                        <td>
                            <a href="javascript:void(0);" onclick="show_detail(<?=$v['id']?>)">结算订单明细</a>
                            <?php if($v['status']>0) {?>
                            <a href="javascript:void(0);"  onclick="show_confirm(<?=$v['id']?>,1)">实入售方详情</a>
                             <?php }else{?>
                                <a href="javascript:void(0);"  onclick="show_confirm(<?=$v['id']?>,0)">确认实入售方</a>
                            <?php }?>
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
                    <td colspan="10"><div class="pagination"> <?php echo $output['page'];?> </div></td>
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
    $('#s_confirm_at').datepicker({dateFormat: 'yy-mm-dd'});
    $('#e_confirm_at').datepicker({dateFormat: 'yy-mm-dd'});
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

//查看结算订单明细
function show_detail(id){
    parent.layer.open({
        type: 2,
        title:"查看结算订单明细",
        skin: 'layui-layer-rim', //加上边框
        area: ['800px', '500px'], //宽高
        content: "index.php?act=admin_finance&op=seller_ajax_settlement_detail&settlement_id="+id
    });
}

//确定实入售方
function show_confirm(id,type){
    let title = '提交实入售方信息'

    if(type>0)
        title = '查看实入售方详情'
    parent.layer.open({
        type: 2,
        title:title,
        skin: 'layui-layer-rim', //加上边框
        area: ['500px', '400px'], //宽高
        content: "index.php?act=admin_finance&op=seller_ajax_settlement_confirm&settlement_id="+id
    });
}

//calc
function calc()
{
    let json  = $('#formSearch').serializeJSON();
    url = 'index.php?act=admin_finance&op=seller_settlement_calc'
    post(url, json).then(function(res){
        if(res.data.code == 200)
        {
            let data  = res.data.data;
            $('#count').text(data.sum_money);
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
