<?php defined('InHG') or exit('Access Invalid!');?>
<?php extract($output)?>
<?php if($declare['type'] == 10){?>
<div style="padding: 20px 50px;" id="list">
    <div class="pure-g">
        <div class="pure-u-12-24">
            订单号：<span id="order_num"><?=$declare['order_id']?></span>
        </div>
        <div class="pure-u-12-24">
            收入序列号：<span id="income_series_number"><?=$declare['income_series_number']?></span>
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
        <?php $total =0; ?>
        <?php if ($declare['order_logs'] ){?>
            <?php foreach ($declare['order_logs'] as $order_log){?>
                <?php if($order_log['sign']=='+')
                          $total +=(float)$order_log['money'];
                       else
                           $total -=(float)$order_log['money'];
                ?>
                <tr>
                    <td><?=$order_log['remark']?></td>
                    <td><?=$order_log['sign'].$order_log['money']?></td>
                    <td><?=$order_log['created_at']?></td>
                </tr>
            <?php }?>
        <?php }?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3" style="text-align: center">
                总金额:￥ <span id="total_num"><?=$total?></span>
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
<?php }else{?>
    <div style="padding: 20px 50px;margin-top: 20px;font-size: 14px;" id="list">
        <div class="pure-g">
            <div class="pure-u-24-24" style="text-align: center">
                原因：<span id="reason"><?=$declare['application']['reason']?></span>
            </div>
        </div>

        <div class="panel_footer" style="margin-top:50px;padding: 10px;text-align: center;">
            <span class="label">
                <a href="javascript:void(0);" onclick="closeLayer();" class="button">关闭</a>
            </span>
        </div>
    </div>


<?php }?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>

<script type="text/javascript">



</script>