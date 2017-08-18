<?php defined('InHG') or exit('Access Invalid!');?>
<?php extract($output)?>
 <div class="content">
<form id="ajax_form">
    <input type="hidden" name="id" value="<?=$declare['id']?>">
    <input type="hidden" name="in_ajax" value="1">
     <div class="pure-g line">
         <?php if($declare['money']>0 ){?>
            <div class="pure-u-12-24">
                收入金额: ￥ <?=$declare['money']?>
            </div>
         <?php }?>
         <?php if($declare['first_cost'] >0){?>
             <div class="pure-u-12-24">
                 业务成本: ￥ <?=$declare['first_cost']?>
             </div>
         <?php }?>
     </div>


     <?php if($declare['money']>0 ){?>
     <div class="pure-g line">
             <div class="pure-u-1-3">
                 收入凭证号:
             </div>
         <div class="pure-u-2-3">
             <input type="text" id="income_voucher_number" name="income_voucher_number">
             <span id="text_income_voucher_number"></span>
         </div>
     </div>
     <?php }?>
     <?php if($declare['first_cost']>0 ){?>
         <div class="pure-g line">
             <div class="pure-u-1-3">
                 成本凭证号:
             </div>
             <div class="pure-u-2-3">
                 <input type="text" id="firstcost_voucher_number" name="firstcost_voucher_number">
                 <span id="text_firstcost_voucher_number"></span>
             </div>
         </div>
     <?php }?>
     </div>

        <div class="panel_footer" style="margin-top:100px;padding: 10px;text-align: center;">
                    <span class="label">
                        <a href="javascript:void(0);"  onclick="sub();" id="sub" class="button">提交</a>
                        <a style="display: none;" href="javascript:void(0);"  onclick="doSub();" id="doSub" class="button">提交</a>
                        <a href="javascript:void(0);" onclick="closeLayer();" class="button">取消</a>
                    </span>
        </div>
</form>
 </div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>

<script type="text/javascript">

    function sub(){
        let json= $('#ajax_form').serializeJSON();
        <?php if($declare['first_cost']>0 ){?>
        if(json.firstcost_voucher_number ==""){
            alert("凭证号不完整～");
            return false;
        }else{
            $('#firstcost_voucher_number').hide();
            $('#text_firstcost_voucher_number').text(json.firstcost_voucher_number);
        }
        <?php } ?>
        <?php if($declare['money']>0 ){?>
        if(json.income_voucher_number ==""){
            alert("凭证号不完整～");
            return false;
        }else{
            $('#income_voucher_number').hide();
            $('#text_income_voucher_number').text(json.income_voucher_number);
        }
        <?php } ?>
        $('#sub').hide();
        $("#doSub").show();

    }
    function doSub(){
        let json= $('#ajax_form').serializeJSON();
        post('index.php?act=admin_finance&op=account_ajax_get_declare', json).then(function(res){
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