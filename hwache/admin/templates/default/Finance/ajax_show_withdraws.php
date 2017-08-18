<?php defined('InHG') or exit('Access Invalid!');?>

<table class="table tb-type2" style="padding: 10px;">
    <thead>
    <tr class="thead blue">
        <th style="text-align: center;">提现(工单)编号</th>
        <th style="text-align: center;">使用提现额度</th>
        <th style="text-align: center;">提现额度扣减时间</th>
    </tr>
    </thead>
    <tbody>
            <tr class="hover" >
                <td style="text-align: center;"><?php echo $output['consume']['withdraw']['uw_id'] ?></td>
                <td style="text-align: center;"><?php echo $output['consume']['withdraw']['money']?></td>
                <td style="text-align: center;"><?php echo $output['consume']['withdraw']['updated_at']?></td>
            </tr>
    </tbody>
    <tfoot>
        <tr class="tfoot">
            <th colspan="3" style="text-align: center;">
                <a href="javascript:void(0);" onclick="closeLayer();" class="button">关闭</a>
            </th>
        </tr>
    </tfoot>
</table>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>
<script type="text/javascript">
    function setStatus()
    {
        let json= $('#ajax_form').serializeJSON();

        post('index.php?act=finance&op=ajax_set_status', json).then(function(res){
            if(res.data.code == 200)
            {
                alert(res.data.msg);
                refresh_workspace();
                closeLayer();
            }else
            {
                alert(res.data.msg);
            }
        }).catch(function(err){
            console.log(err);
        });
    }
</script>