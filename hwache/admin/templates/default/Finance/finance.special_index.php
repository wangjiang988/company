<?php defined('InHG') or exit('Access Invalid!');?>
    <div class="page finance">
        <div class="fixed-bar">
            <div class="item-title">
                <h3>客户财务</h3>
                <ul class="tab-base">
                    <li><a href="index.php?act=finance&op=index"><span>客户财务</span></a></li>
                    <li><a href="index.php?act=finance&op=recharge_index"><span>转入-银行转账</span></a></li>
                    <li><a href="index.php?act=finance&op=recharge_index&recharge_method=1"><span>转入-线上支付</span></a></li>
                    <li><a href="index.php?act=finance&op=withdraw_index"><span>提现</span></a></li>
                    <li><a href="javascript:void(0);" class="current"><span>特事审批</span></a></li>
                </ul>
            </div>
        </div>
        <div class="fixed-empty"></div>

        <form method="get" action="index.php" name="formSearch" id="formSearch">
            <input type="hidden" name="act" value="finance" />
            <input type="hidden" name="op" value="special_index" />
            <input type="hidden" name="is_search" value="1" />
            <input type="hidden" name="curpage" id="cur_page" value="<?php echo $_GET['curpage']?>" />
            <input type="hidden" name="export" id="export" value="0" />
            <table class="tb-type1 noborder search">
                <tbody>
                <tr>
                    <th>工单编号 </th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="id" value="<?php echo trim($_GET['id']); ?>" />
                    </th>
                    <th>客户会员号 </th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="user_id" value="<?php echo trim($_GET['user_id']); ?>" />
                    </th>
                    <th>客户手机 </th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text" name="phone" value="<?php echo trim($_GET['phone']); ?>" />
                    </th>
                </tr>
                <tr>
                    <th>申请项目 </th>
                    <th class="input">
                        <span class="icon"></span>
                        <select name="special_type" id="special_type">
                            <option value=" ">全部</option>
                            <option value="1"
                            <?php if($_GET['special_type']==1) echo 'selected' ?>
                            >冻结</option>
                            <option value="2"
                                <?php if($_GET['special_type']==2) echo 'selected' ?>
                            >解冻</option>
                            <option value="3"
                                <?php if($_GET['special_type']==3) echo 'selected' ?>
                            >转出</option>
                            <option value="4"
                                <?php if($_GET['special_type']==4) echo 'selected' ?>
                            >转入</option>
                            <option value="5"
                                <?php if($_GET['special_type']==5) echo 'selected' ?>
                            >返还已得</option>
                            <option value="6"
                                <?php if($_GET['special_type']==6) echo 'selected' ?>
                            >获得返还</option>
                        </select>
                    </th>
                    <th>申请部门 </th>
                    <th class="input">
                        <span class="icon"></span>
                        <select name="dept_id">
                            <option value="">全部</option>
                            <?php if(!empty($output['dept_list']) && is_array($output['dept_list'])){ ?>
                                <?php foreach($output['dept_list'] as $kk => $vv){?>
                                    <option value="<?=$vv['id']?>"
                                        <?php if($_GET['dept_id']==$vv['id']) echo 'selected' ?>
                                    ><?=$vv['name']?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </th>
                    <th>申请人姓名 </th>
                    <th class="input">
                        <span class="icon"></span><input type="text" class="text " name="apply_admin_name" value="<?php echo trim($_GET['apply_admin_name']); ?>" />
                    </th>
                    <th>状态 </th>
                    <th class="input">
                        <span class="icon"></span>
                        <select name="status" id="">
                            <option value="">全部</option>
                            <option value="0"
                                <?php if($_GET['status']==="0") echo 'selected' ?>
                            >待批准</option>
                            <option value="1"
                                <?php if($_GET['status']==1) echo 'selected' ?>
                            >已通过</option>
                            <option value="2"
                                <?php if($_GET['status']==2) echo 'selected' ?>
                            >未通过</option>
                        </select>
                    </th>
                </tr>
                <tr>
                    <th>申请时间 </th>
                    <th class="input" colspan="3">
                        <span class="icon"></span><input type="text" class="text wt150 date" id="s_created_at" name="s_created_at" value="<?php echo trim($_GET['s_created_at']); ?>" />
                        -
                        <input type="text" class="text wt150 date" id="e_created_at" name="e_created_at" value="<?php  if($_GET['e_created_at']) echo trim($_GET['e_created_at']); else echo get_now(); ?>" />
                    </th>

                    <th>状态更新时间 </th>
                    <th class="input" colspan="3">
                        <span class="icon"></span><input type="text" class="text wt150 date" id="s_updated_at" name="s_updated_at" value="<?php echo trim($_GET['s_updated_at']); ?>" />
                        -
                        <input type="text" class="text wt150 date" id="e_updated_at" name="e_updated_at" value="<?php if($_GET['e_updated_at']) echo trim($_GET['e_updated_at']); else echo get_now(); ?>" />
                    </th>

                </tr>
                <tr >
                    <th class="input" colspan="10">
                        <div class="pull-right">
                            <a href="JavaScript:void(0);" class="button" id="sub_btn"><span>查找</span></a>
                            <a href="index.php?act=finance&op=special_index" class="button"><span>重置</span></a>
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
                <th class="wt70">工单编号</th>
                <th >工单时间</th>
                <th >客户会员号</th>
                <th >申请人</th>
                <th >申请项目</th>
                <th >申请原因</th>
                <th >状态更新时间</th>
                <th >状态</th>
                <th class="align-center">操作</th>
            </tr>
            </thead>
            <tbody id="datatable">
            <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
                <?php foreach($output['list'] as $k => $v){ ?>
                    <tr class="hover">
                        <td><?php echo $v['id']?></td>
                        <td><?php echo $v['created_at'] ?></td>
                        <td><?php echo $v['user_id']?></td>
                        <td><?php echo $v['apply_admin_name']?></td>
                        <td><?php echo $v['name']?></td>
                        <td><?php echo $v['reason']?></td>
                        <td><?php echo $v['updated_at']?></td>
                        <td>
                            <?php echo show_special_status($v['status']); ?>
                        </td>
                        <td>
                            <?php if($v['status'] <1) { ?>
                                <a href="index.php?act=finance&op=special_detail&id=<?php echo $v['id'] ?>">审批</a>
                            <?php }else{ ?>
                                <a href="index.php?act=finance&op=special_detail&id=<?php echo $v['id'] ?>">查看</a>
                            <?php } ?>
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
        $('#s_created_at').datepicker({dateFormat: 'yy-mm-dd'});
        $('#e_created_at').datepicker({dateFormat: 'yy-mm-dd'});

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

        $("#sub_btn").click(function(){
            $("#cur_page").val(1);
            $("#export").val(0);
            document.formSearch.submit();
        });
    });
</script>
