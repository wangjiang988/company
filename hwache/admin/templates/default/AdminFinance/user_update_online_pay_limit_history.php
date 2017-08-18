<?php defined('InHG') or exit('Access Invalid!');?>
<?php extract($output);?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><a href="index.php?act=admin_finance&op=home">平台财务</a></h3>
            <ul class="tab-base">
                <li><a  href="index.php?act=admin_finance&op=user_online_pay_limit"><span>客户线上支付退款周期设定</span></a></li>
                <li><a class="current"><span>修改记录</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <div class="container">
        <div class="big_title">
            修改记录
        </div>

        <div class="ml50 content">
            <table class="table tb-type2">
                <thead>
                <tr class="thead blue">
                    <th >时间</th>
                    <th >支付渠道</th>
                    <th >修改项目</th>
                    <th >修改为</th>
                    <th >设定人</th>

                </tr>
                </thead>
                <tbody id="datatable">
                <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
                    <?php foreach($output['list'] as $k => $v){ ?>
                        <tr class="hover">
                            <td><?php echo $v['created_at']?></td>
                            <td><?php echo $v['template_name']?></td>
                            <td><?php if($v['field_name'] == 'user_range'){
                                    echo "客户申请退款有效周期";
                                }
                                if($v['field_name'] == 'admin_range'){
                                    echo "平台办理退款有效周期";
                                }?></td>
                            <td><?php echo $v['now_val'].'日'?></td>
                            <td><?php echo $v['user_name']?></td>
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

</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript">
    $(function(){

    });
</script>
