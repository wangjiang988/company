<?php defined('InHG') or exit('Access Invalid!');?>
<?php extract($output);?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><a href="index.php?act=admin_finance&op=home">平台财务</a></h3>
            <ul class="tab-base">
                <li><a class="current"><span>客户线上支付退款周期设定</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <div class="container">
        <div class="big_title">
            退款时间
        </div>

        <div class="ml50 content">
            <table class="table tb-type2">
                <thead>
                <tr class="thead blue">
                    <th >线上支付渠道</th>
                    <th >客户申请退款有效周期</th>
                    <th >平台办理退款有效周期</th>
                </tr>
                </thead>
                <tbody id="datatable">
                <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
                    <?php foreach($output['list'] as $k => $v){ ?>
                        <tr class="hover">
                            <td><?php echo $v['name']?></td>
                            <td><?php echo $v['user_range'].'日'?></td>
                            <td><?php echo $v['admin_range'].'日'?></td>
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
            <a href="index.php?act=admin_finance&op=user_update_online_pay_limit" class="button confirm ml-20" style="margin-right: 100px;">修改</a>
        </div>

    </div>

</div>

<script type="text/javascript">
    $(function(){

    });
</script>
