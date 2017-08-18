<?php defined('InHG') or exit('Access Invalid!');?>
<?php extract($output)?>
<div class="content" id="form" style="font-size: 12px">
    <form id="ajax_form">
        <input type="hidden" name="in_ajax" value="1">
        <input type="hidden" id="object_id" name="id" value="<?=$template['id'];?>">
        <input type="hidden" name="type"  value="<?=$template['content']['type']?>">
        <?php if($template['content']['type'] == 1){ // 每月免费次数  +  超出次数每次收费?>
        <div class="pure-g line">
            <div class="pure-u-1-3">
                每月免费次数
            </div>
            <div class="pure-u-2-3">
                <span>&nbsp;&nbsp;&nbsp;&nbsp;</span><input type="text" name="freetime" value="<?=$template['content']['freetime']?>">
            </div>
        </div>
        <div class="pure-g line">
            <div class="pure-u-1-3">
                超出次数每次收费
            </div>
            <div class="pure-u-2-3">
               <span>￥</span> <input type="text" name="fee" value="<?=$template['content']['fee']?>">
            </div>
        </div>

        <div class="pure-g line">
            <div class="pure-u-1-3">
                模板描述
            </div>
            <div class="pure-u-2-3">
               <textarea name="description" style="width:300px; height: 60px;"><?=$template['description']?></textarea>
            </div>
        </div>

        <?php }?>

        <?php if($template['content']['type'] == 2){ // 每月免费金额  +  超出金额收费?>
            <div class="pure-g line">
                <div class="pure-u-1-3">
                    每月免费金额
                </div>
                <div class="pure-u-2-3">
                   ￥ <input type="text" name="user" value="<?=$template['user_range']?>">
                </div>
            </div>
            <div class="pure-g line">
                <div class="pure-u-1-3">
                    超出金额每次收费
                </div>
                <div class="pure-u-2-3">
                    ￥ <input type="text" name="admin" value="<?=$template['admin_range']?>">
                </div>
            </div>

            <div class="pure-g line">
                <div class="pure-u-1-3">
                    模板描述
                </div>
                <div class="pure-u-2-3">
                   <textarea name="description" style="width:300px; height: 60px;"><?=$template['description']?></textarea>
                </div>
            </div>
        <?php }?>
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
        <input type="hidden" id="fee" name="fee">
        <input type="hidden" id="freetime" name="freetime">
        <input type="hidden" name="type"  value="<?=$template['content']['type']?>">
        <input type="hidden" id="description" name="description">
        <div class="pure-g line">
            <div class="pure-u-1-3" id>
                每月免费次数
            </div>
            <div class="pure-u-2-3">
                <span id="user_val"></span>
            </div>
        </div>
        <div class="pure-g line">
            <div class="pure-u-1-3">
                超出次数每次收费
            </div>
            <div class="pure-u-2-3">
                <span id="admin_val"></span>
            </div>
        </div>
        <div class="center font-14" style="color:red;">确定么？</div>
        <div class="mt-50"></div>
        <div class="center">
            <a href="javascript:void(0);" onclick="submit_ajax_form2('index.php?act=admin_finance&op=seller_ajax_change_template','ajax_confirm_form','');" class="button confirm">确定</a>
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
        console.log(json)

        if(json.fee =="" || !valid2(json.fee,/^[0-9]+(.[0-9]{2})?$/))
        {
            alert('超出次数每次收费格式错误');
            return false;
        }
        if(json.freetime =="" || !valid2(json.freetime,/^[0-9]*$/))
        {
            alert('每月免费次数为格式错误');
            return false;
        }

        $("#form").hide();
        $("#confirm_form").show();
//        closeLayer()

//        show_dialog('确认退款周期','#confirm_html','500px','250px');
        $('#user_val').text(json.freetime)
        $('#id').val(json.id)
        $('#freetime').val(json.freetime)
        $('#fee').val(json.fee)
        $('#admin_val').text(json.fee)
        $('#description').val(json.description);
    }
</script>
