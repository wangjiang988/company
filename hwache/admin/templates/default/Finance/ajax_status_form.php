<?php defined('InHG') or exit('Access Invalid!');?>
<form method="get" name="ajax_form" id="ajax_form">
    <input type="hidden" name="act" value="finance">
    <input type="hidden" name="op" value="ajax_set_status">
    <input type="hidden" name="uwl_id" value="<?php echo $output['uwl_id']?>">
    <input type="hidden" name="uid" value="<?php echo $output['user_id']?>">
    <input type="hidden" name="status" value="<?php echo $output['status']?>">
    <input type="hidden" name="in_ajax" value="1">
    <div class="panel_title" style="padding: 20px;text-align: center;font-size: large;">
        <?php if($output['status'] == 0){ ?>
            确定将该路线设为失效吗？
        <?php } else{?>
            确定将该路线设为有效吗？
        <?php } ?>
    </div>
    <div class="panel_body" style="">
        <span class="label" style="padding-left:20px; "><span style="color: red;">*</span>备注：</span>
        <span class="input">
            <textarea name="remark" id="remark" cols="30" rows="10" style="width: 350px;height: 60px;"></textarea>
        </span>
    </div>
    <div class="panel_footer" style="padding: 10px;text-align: center;">
        <span class="label">
            <a href="javascript:void(0);"  onclick="setStatus();" class="button">确认</a>
            <a href="javascript:void(0);" onclick="closeLayer();" class="button">取消</a>
        </span>
    </div>
</form>

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