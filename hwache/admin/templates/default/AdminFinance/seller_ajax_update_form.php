<?php defined('InHG') or exit('Access Invalid!');?>
<?php extract($output)?>
<div class="content" id="form" style="font-size: 12px">
    <form id="ajax_form">
        <input type="hidden" name="in_ajax" value="1">
        <input type="hidden" id="object_id" name="id" value="<?=$template['id'];?>">
        <div class="pure-g line">
            <div class="pure-u-1-3">
                支付渠道
            </div>
            <div class="pure-u-2-3">
                支付宝
            </div>
        </div>
        <div class="pure-g line">
            <div class="pure-u-1-3">
                客户申请退款有效周期
            </div>
            <div class="pure-u-2-3">
                <input type="text" name="user" value="<?=$template['user_range']?>">日
            </div>
        </div>
        <div class="pure-g line">
            <div class="pure-u-1-3">
                平台办理退款有效周期
            </div>
            <div class="pure-u-2-3">
                <input type="text" name="admin" value="<?=$template['admin_range']?>">日
            </div>
        </div>
    </form>
    <div class="mt-50"></div>
    <div class="center">
        <a href="javascript:void(0);" onclick="show_confirm_dialog();" class="button confirm">确定</a>
        <a href="javascript:closeLayer();" class="button  ml-20">返回</a>
    </div>
</div>

<div class="content" id="confirm_form" style="display: none;">
    <form id="ajax_confirm_form" >
        <input type="hidden" name="in_ajax" value="1">
        <input type="hidden" id="id" name="id" value="<?=$template['id']?>">
        <input type="hidden" id="user" name="user">
        <input type="hidden" id="admin" name="admin">
        <div class="pure-g line">
            <div class="pure-u-1-3">
                支付渠道
            </div>
            <div class="pure-u-2-3">
                支付宝
            </div>
        </div>
        <div class="pure-g line">
            <div class="pure-u-1-3">
                客户申请退款有效周期
            </div>
            <div class="pure-u-2-3">
                <span id="user_val"></span>日
            </div>
        </div>
        <div class="pure-g line">
            <div class="pure-u-1-3">
                平台办理退款有效周期
            </div>
            <div class="pure-u-2-3">
                <span id="admin_val"></span>日
            </div>
        </div>
        <div class="mt-50"></div>
        <div class="center">
            <a href="javascript:void(0);" onclick="submit_ajax_form2('index.php?act=admin_finance&op=user_update_online_pay_limit','ajax_confirm_form','');" class="button confirm">确定</a>
            <a href="javascript:closeLayer();" class="button  ml-20">返回</a>
        </div>
    </form>

</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>

<script type="text/javascript">
    $(function(){

    });

    //确认弹框
    function  show_confirm_dialog() {
        let json= $('#ajax_form').serializeJSON();

        if(json.user =="" || !valid2(json.user,/^[0-9]*$/))
        {
            alert('请以正确格式填写客户申请退款有效周期');
            return false;
        }
        if(json.admin =="" || !valid2(json.admin,/^[0-9]*$/))
        {
            alert('请以正确格式填写平台办理退款有效周期');
            return false;
        }

        $("#form").hide();
        $("#confirm_form").show();
//        closeLayer()

//        show_dialog('确认退款周期','#confirm_html','500px','250px');
        $('#user_val').text(json.user)
        $('#id').val(json.id)
        $('#user').val(json.user)
        $('#admin').val(json.admin)
        $('#admin_val').text(json.admin)
    }
</script>
