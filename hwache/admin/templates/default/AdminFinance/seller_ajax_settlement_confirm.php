<?php defined('InHG') or exit('Access Invalid!');?>
<?php extract($output)?>
 <div class="content">
     <form id="ajax_form">
         <input type="hidden" name="in_ajax" value="1">
         <input type="hidden" name="id" value="<?=$settlement['id']?>">
     <div class="pure-g line">
         <div class="pure-u-1-3">
             售方用户名:
         </div>
         <div class="pure-u-2-3">
             <?=$settlement['member_name']?>
         </div>
     </div>
     <div class="pure-g line">
         <div class="pure-u-1-3">
             姓名:
         </div>
         <div class="pure-u-2-3">
             <?=$settlement['member_truename']?>
         </div>
     </div>
     <div class="pure-g line">
         <div class="pure-u-1-3">
             实入售方金额:
         </div>
         <div class="pure-u-2-3">
             <input type="hidden" class="input" id="money" name="money" value="<?=$settlement['money']?>">
                 <span class="span" id="confirm_money"><?=$settlement['confirm_money']?></span>
             <?php if($settlement['status'] ==0){?>
             <input type="text" class="input" name="confirm_money" placeholder="￥ 0~<?=$settlement['money']?>">
             <?php }?>
         </div>
     </div>
     <div class="pure-g line">
         <div class="pure-u-1-3">
             劳务费发票号:
         </div>
         <div class="pure-u-2-3">
                <span class="span" id="service_invoice_number"><?=$settlement['service_invoice_number']?></span>
             <?php if($settlement['status'] ==0){?>
                <input class="input" type="text" name="service_invoice_number">
             <?php }?>
         </div>
     </div>
     <div class="pure-g line">
         <div class="pure-u-1-3">
             记账凭证号:
         </div>
         <div class="pure-u-2-3">
             <span class="span" id="accord_voucher_number"><?=$settlement['accord_voucher_number']?></span>
             <?php if($settlement['status'] ==0){?>
             <input class="input" type="text"  name="accord_voucher_number">
             <?php }?>
         </div>
     </div>
         <?php if($settlement['status'] ==1){ ?>
         <div class="pure-g line">
             <div class="pure-u-1-3">
                 实入售方时间:
             </div>
             <div class="pure-u-2-3">
                 <span class="span" id="accord_voucher_number"><?=$settlement['confirm_at']?></span>
             </div>
         </div>
         <?php } ?>
         <?php if($settlement['status'] ==1){ ?>
             <div class="pure-g line">
                 <div class="pure-u-1-3">
                     操作人:
                 </div>
                 <div class="pure-u-2-3">
                     <span class="span" id="accord_voucher_number"><?=$settlement['confirm_user_name']?></span>
                 </div>
             </div>
         <?php } ?>
     </form>

     <div class="panel_footer" style="margin-top:30px;padding: 10px;text-align: center;">
            <span class="label">
                <?php if($settlement['status'] ==0){?>
                    <a  href="javascript:void(0);"  onclick="confirm();"  id="confirm" class="button">提交</a>
                    <a  href="javascript:void(0);"  onclick="sub();" style="display:none;"  id="sub" class="button">确定</a>
                <?php }?>
                <a href="javascript:void(0);" onclick="closeLayer();" class="button">取消</a>
            </span>
     </div>
 </div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<?php if($settlement['status'] ==0){?>
<style>
    .span{
        display: none;
    }
<?php }?>
</style>
<script type="text/javascript">
function confirm(){
        let json  = $("#ajax_form").serializeJSON();
        if(!valid2(json.confirm_money,/^[0-9]+(.[0-9]{2})?$/)){
            alert("实入售方金额格式错误！");
            return false;
        }

        if( parseFloat(json.confirm_money<0) || parseFloat(json.confirm_money) >  parseFloat(json.money)){
            alert("实入售方金额格式错误！");
            return false;
        }
        if(json.service_invoice_number == '')
        {
            alert("输入的内容不完整～");
            return false;
        }
        if(json.accord_voucher_number == '')
        {
            alert("输入的内容不完整～");
            return false;
        }
        $('#confirm_money').text(json.confirm_money);
        $('#service_invoice_number').text(json.service_invoice_number);
        $('#accord_voucher_number').text(json.accord_voucher_number);
        $(".span").show();
        $('.input').hide();
        $("#confirm").hide();
        $("#sub").show();

}

function sub()
{
    let json  = $("#ajax_form").serializeJSON();

    post('index.php?act=admin_finance&op=seller_ajax_settlement_confirm', json).then(function(res){
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