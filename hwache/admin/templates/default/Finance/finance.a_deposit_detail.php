<?php defined('InHG') or exit('Access Invalid!');?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>客户财务</h3>
            <ul class="tab-base">
                <li><a href="index.php?act=finance&op=index"><span>客户财务</span></a>▷</li>
                <li><a class="current"><span>余额详情</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>

    <form method="get" action="index.php" name="formSearch" id="formSearch">
        <input type="hidden" name="act" value="finance" />
        <input type="hidden" name="op" value="a_deposit_detail" />
        <input type="hidden" name="uid" value="<?php echo $output['user']['id'] ;?>" />
        <input type="hidden" name="is_search" value="1" />
        <input type="hidden" name="curpage" id="cur_page" value="<?php echo $_GET['curpage']?>" />
        <input type="hidden" name="export" id="export" value="0" />
        <input type="hidden" name="period"  id="period"   value="<?php $_GET['period']?>" />
        <div class="info">
            <span class="label">客户会员号：</span>
            <span class="val"><?php echo $output['user']['id'] ;?></span>
            <span class="label">客户姓名：</span>
            <span class="val"><?php echo $output['user']['last_name'].$output['user']['first_name'] ;?></span>
            <span class="label">客户手机：</span>
            <span class="val"><?php echo $output['user']['phone'] ;?></span>
        </div>
        <table class="tb-type1 noborder search">
            <tbody>
            <tr >
                <th class="wt60">发生时间 </th>
                <th class="wt400">
                    <span class="icon"></span><input type="text" class="text wt150 date" name="s_created_at" id="s_created_at" value="<?php echo trim($_GET['s_created_at']); ?>" />
                    -
                    <input type="text" class="text wt150 date" name="e_created_at" id="e_created_at" value="<?php echo trim($_GET['e_created_at']); ?>" />
                </th>
                <th>
                    <a href="JavaScript:void(0);" class="button"  id="sub_btn"><span>查找</span></a>
                </th>
                <th class="input wt100">
                    <a href="JavaScript:void(0);" id="in_month" <?php if($_GET['period'] ==1) echo 'class="actived"'?> ><span>1个月</span></a>
                </th>
                <th class="input wt100">
                    <a href="JavaScript:void(0);" id="in_year" <?php if($_GET['period'] ==2) echo 'class="actived"'?>><span>1年</span></a>
                </th>
                <th class="input wt100">
                    <a href="JavaScript:void(0);" id="over_year" <?php if($_GET['period'] ==3) echo 'class="actived"'?>><span>1年以上</span></a>
                </th>
                <th class="input">
                    <a href="JavaScript:void(0);" class="button"  id="export_btn"><span>导出</span></a>
                </th>
            </tr>
            </tbody>
        </table>

    </form>
    <table class="table tb-type2">
        <thead>
        <tr class="thead blue">
            <th >发生时间</th>
            <th >项目</th>
            <th >说明</th>
            <th >收支金额</th>
            <th >可用余额</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                <tr class="hover">
                    <td><?= $v['created_at'];
                        ?></td>
                    <td><?php echo $v['item'] ?></td>
                    <td><?php
                        if($v['remark'] =='正在办理') echo "<span style='color:red;padding: 0 10px;'>{$v['remark']}</span>";
                        else   echo $v['remark']?>
                    </td>
                    <td><?php echo $v['money_type'].'￥'.$v['money']?></td>
                    <td><?php echo '￥'.$v['credit_avaliable']?></td>
                </tr>
            <?php } ?>
        <?php }else { ?>
            <tr class="no_data" id="no_data" data-has="1">
                <td colspan="8"><?php echo $lang['nc_no_record'];?></td>
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
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
    $(function(){
        $('#s_created_at').datepicker({dateFormat: 'yy-mm-dd'});
        $('#e_created_at').datepicker({dateFormat: 'yy-mm-dd'});
        $('#ncsubmit').click(function(){
            $("#period").val(0);
            $('#formSearch').submit();
        });
        $('#in_month').click(function(){
            setTimeNull();
            $("#period").val(1);
            $('#formSearch').submit();
        });
        $('#in_year').click(function(){
            setTimeNull();
            $("#period").val(2);
            $('#formSearch').submit();
        });
        $('#over_year').click(function(){
            setTimeNull();
            $("#period").val(3);
            $('#formSearch').submit();
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
        $("#sub_btn").click(function(){
            $("#cur_page").val(1);
            $("#export").val(0);
            document.formSearch.submit();
        });
    });
    function setTimeNull(){
        $("#s_created_at").val("");
        $("#e_created_at").val("");
    }
</script>
