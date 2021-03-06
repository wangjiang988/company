<?php defined('InHG') or exit('Access Invalid!');?>
<?php extract($output)?>
    <div class="page finance">
        <div class="fixed-bar">
            <div class="item-title">
                <h3>审批操作日志</h3>
                <ul class="tab-base">
                    <li><a href="index.php?act=operation_log&op=index"><span>操作日志</span></a></li>
                    <li><a href="javascript:void(0);" class="current"><span>详情</span></a></li>
                </ul>
            </div>
        </div>
        <div class="fixed-empty"></div>
        <div class="info">
            <div class="span4">
                <span class="label">工单编号：</span>
                <span class="val"><?=$data['id']?></span>
            </div>
            <div class="span4">
                <span class="label">操作人ID-操作人：</span>
                <span class="val"><?=$data['user_id'].'-'.$data['user_name'] ?></span>
            </div>
            <div class="span4">
                <span class="label">步骤(step)：</span>
                <span class="val"><?=$data['step']?></span>
            </div>
        </div>
        <div class="info">
            <div class="span4">
                <span class="label">类型：</span>
                <span class="val"><?=show_operation_type($data['type'])?></span>
            </div>
            <div class="span4">
                <span class="label">操作：</span>
                <span class="val"><?=$data['operation']?></span>
            </div>
            <div class="span4">
                <span class="label">备注：</span>
                <span class="val"><?=$data['remark']?></span>
            </div>

        </div>
        <div class="info">
            <div class="span4">
                <span class="label">关联表和关联数据id：</span>
                <span class="val"><?=$data['related']?></span>
            </div>

        </div>
        <div class="clear"></div>
        <div class="big_title">
            明细
        </div>
        <div class="clear"></div>
        <div class="info">
            <table class="table tb-type2">
                <thead>
                <tr class="thead blue">
                    <th >表名</th>
                    <th >字段名</th>
                    <th >对应数据ID</th>
                    <th >修改前</th>
                    <th >修改后</th>
                    <th >created_at</th>
                    <th >updated_at</th>
                </tr>
                </thead>
                <tbody>
                <?php if(!empty($output['list']) && is_array($output['list'])){ $i=0; ?>
                    <?php foreach($output['list'] as $k => $v){ ?>
                        <tr class="hover">
                            <td><?=$v['table_name']?></td>
                            <td><?=$v['field_name']?></td>
                            <td><?=$v['related_id']?></td>
                            <td><?=$v['old_val']?></td>
                            <td><?=$v['now_val']?></td>
                            <td><?=$v['created_at']?></td>
                            <td><?=$v['updated_at']?></td>
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
        </div>
        <div class="info footer">
            <a href="javascript:history.go(-1);" class="button">返回</a>
        </div>
    </div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<script type="text/javascript">
    $(function(){
        $('#recharge_confirm_at').datepicker({dateFormat: 'yy-mm-dd'});
        disable_ruzhang();
        $('#recharge_operation_yes').change(function(){
            let val = $(this).prop('checked');
            console.log(val);
            if(val)
            {
                $('#recharge_operation_no').prop('checked',false);
                enable_ruzhang();
            }else
            {
                disable_ruzhang();
            }
        });
        $('#recharge_operation_no').change(function(){
            let val = $(this).prop('checked');
            console.log(val);
            if(val)
            {
                $('#recharge_operation_yes').prop('checked',false);
                disable_ruzhang();
            }
        })
    });

    //已入账表单未勾选
    function disable_ruzhang()
    {
        $('#transfer_to_account').attr('disabled','disabled');
        $('#recharge_money').attr('disabled','disabled');
        $('#transfer_to_order').attr('disabled','disabled');
        $('#recharge_confirm_at').attr('disabled','disabled');
    }

    //已入账勾选
    function enable_ruzhang() {
//        $('#transfer_to_account').removeAttr('disabled');
        $('#recharge_money').removeAttr('disabled');
//        $('#transfer_to_order').removeAttr('disabled');
        $('#recharge_confirm_at').removeAttr('disabled');
    }
</script>
                                                               