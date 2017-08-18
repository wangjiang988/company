<?php defined('InHG') or exit('Access Invalid!');?>
<form method="get" name="ajax_form" id="ajax_form">
    <input type="hidden" name="act" value="finance">
    <input type="hidden" name="op" value="ajax_set_status">
    <input type="hidden" name="uwl_id" value="<?php echo $output['data']['uwl_id']?>">
    <input type="hidden" name="in_ajax" value="1">
    <div class="panel_body" >
        <div>
            <span class="label wt100" style="padding-left:20px; ">提现路线&nbsp;&nbsp;：</span>
            <span class="input wt250">
                <input type="text" name="bank_full_address" value="<?php echo $output['data']['bank_full_address']?>" >
            </span>
        </div>
        <div>
            <span class="label  wt100" style="padding-left:20px; ">&nbsp;</span>
            <span class="input wt250" >
                <input type="text" name="bank_address" value="<?php echo $output['data']['bank']['bank_address']?>">
            </span>
        </div>
        <div>
            <span class="label wt100" style="padding-left:20px; ">收款方账号：</span>
            <span class="input wt250">
                  <input type="text" name="bank_code" value="<?php echo $output['data']['bank']['bank_code']?>">
            </span>
        </div>
        <div>
            <span class="label wt100" style="padding-left:20px; ">收款方户名：</span>
            <span class="input wt250">
                  <input type="text" name="bank_register_name" value="<?php echo $output['data']['bank']['bank_register_name']?>">
            </span>
        </div>
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

        post('index.php?act=finance&op=withdraw_line_edit', json).then(function(res){
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