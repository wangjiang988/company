<style type="text/css">
    .tab-base em{ padding: 0 10px;}
    .search input.txt{width: 100px;}
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
                <li><a href="<?=url('seller_finance','withdraw');?>"><span>提现</span></a><em> | </em></li>
                <li><a href="JavaScript:void(0);" class="current"><span>特情审批</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form name="formSearch" id="searchFrom" action="/index.php" method="get">
        <input type="hidden" value="seller_finance" name="act">
        <input type="hidden" value="special_service" name="op">
        <table class="tb-type1 noborder search" style="width:100%;">
            <tbody>
            <tr>
                <th><label for="">工单编号：</label></th>
                <td>
                    <input type="text" value="<?=$output['search']['code'];?>" name="code" class="txt">
                    <label for="">售方用户名：</label>
                    <input type="text" value="<?=$output['search']['member_name'];?>" name="member_name" class="txt">
                    <label for="">售方手机号：</label>
                    <input type="text" value="<?=$output['search']['member_mobile'];?>" name="member_mobile" class="txt">
                    <label for="">申请人部门：</label>
                    <select name="dept_id">
                        <option value="">--全部--</option>
                        <?php
                        foreach($output['dept'] as $value){
                            $selected = isSelected([$output['search']['dept_id'],'==',$value['id']],'select');
                            echo '<option value="'.$value['id'].'" '.$selected.'>'.$value['name'].'</option>';
                        }
                        ?>
                        ?>
                    </select>
                    <label for="">申请人姓名：</label>
                    <input type="text" value="<?=$output['search']['apply_admin_name'];?>" name="apply_admin_name" class="txt">
                    <label for="">申请项目：</label>
                    <select name="item">
                        <option value="">  --全部--  </option>
                        <option value="冻结" <?=isSelected([$output['search']['item'],'==','冻结'],'select')?>> 冻结 </option>
                        <option value="解冻" <?=isSelected([$output['search']['item'],'==','解冻'],'select')?>>  解冻  </option>
                        <option value="转出" <?=isSelected([$output['search']['item'],'==','转出'],'select')?>>  转出  </option>
                        <option value="转入" <?=isSelected([$output['search']['item'],'==','转入'],'select')?>>  转入  </option>
                        <option value="损失赔偿" <?=isSelected([$output['search']['item'],'==','损失赔偿'],'select')?>>  平台损失赔偿  </option>
                        <option value="赔偿返还" <?=isSelected([$output['search']['item'],'==','赔偿返还'],'select')?>>  获得赔偿返还  </option>
                    </select>
                </td>
            </tr>

            <tr>
                <th><label for="seller_jiaxb">工单时间：</label></th>
                <td>
                    <input type="text" value="<?=$output['search']['start_order_time'];?>" name="start_order_time" class="date">~
                    <input type="text" value="<?=$output['search']['end_order_time'];?>" name="end_order_time" class="date">
                    <label for="seller_jiaxb">状态更新时间：</label>
                    <input type="text" value="<?=$output['search']['start_update_time'];?>" name="start_update_time" class="date">~
                    <input type="text" value="<?=$output['search']['end_update_time'];?>" name="end_update_time" class="date">

                <label>状态：</label>
                <select name="status">
                    <option value="">全部</option>
                    <option value="0" <?=isSelected([$output['search']['status'],'==',0],'select')?> >待批准</option>
                    <option value="1" <?=isSelected([$output['search']['status'],'==',1],'select')?> >已通过</option>
                    <option value="2" <?=isSelected([$output['search']['status'],'==',2],'select')?> >未通过</option>
                </select>

                <div style="float:right; text-align:left; width:auto;">
                    <button type="submit" class="button button-primary button-small">搜索</button>
                    <a href="<?=url('seller_finance','special_service')?>" class="button button-primary button-small">重置</a>
                    <a href="<?=url('seller_finance','exportSpecial',$output['uri'])?>" class="button button-primary button-small">导出</a>
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
            <th class="w60">售方手机</th>
            <th class="align-center">申请人</th>
            <th class="align-center">申请项目</th>
            <th class="align-center">申请原因</th>
            <th class="align-center">状态更新时间</th>
            <th>状态</th>
            <th class="w30 align-center">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                <tr class="hover">
                    <td><?=$v['id']; ?></td>
                    <td><?=$v['created_at']?></td>
                    <td><?=$v['user_name']?></td>
                    <td><?=$v['user_phone']?></td>
                    <td><?=$v['apply_admin_name']; ?></td>
                    <td><?=$v['name']; ?></td>
                    <td class="align-center"><?=$v['reason'];?></td>
                    <td class="align-center"><?=$v['updated_at'];?></td>
                    <td class="nowrap align-center">
                        <?php
                        $statusArr = ['待批准','通过','未通过'];
                        echo $statusArr[$v['status']];
                        ?>
                    </td>
                    <td class="align-center">
                        <a href="<?=url('seller_finance','finance_service_detail',['id'=>$v['id']]); ?>">
                        <?php
                            echo ($v['status'] ==0) ? '审核':'查看';
                        ?>
                        </a>
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