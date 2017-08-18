<?php defined('InHG') or exit('Access Invalid!');?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>售方财务</h3>
            <ul class="tab-base">
                <li><a href="<?=url('seller_finance','index');?>"><span>管理</span></a><em> | </em></li>
                <li><a href="<?=url('seller_finance','withdraw_end',['id'=>$output['seller']['member_id']])?>"><span>特别情况</span></a> | </li>
                <li><a class="current"><span>提交申请</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>

    <div class="info">
        <span class="label">售方用户名：</span>
        <span class="val"><?php echo $output['seller']['member_name'] ;?></span>
        <span class="label">售方姓名：</span>
        <span class="val"><?php echo $output['seller']['member_truename'] ;?></span>
        <span class="label">售方手机：</span>
        <span class="val"><?php echo $output['seller']['member_mobile'] ;?></span>
    </div>
    <div class="info">
        <span class="label">客户可用余额：</span>
        <span class="val">￥ <?php echo $output['seller']['avaliable_deposit']?> </span>
        <span class="label">平台冻结可用余额：</span>
        <span class="val">￥ <?php echo $output['seller']['temp_deposit']?> </span>
    </div>

    <div class="clear"></div>
    <div class="big_title">
        申请内容
    </div>
    <div class="clear"></div>
    <form method="post" action="<?=url('seller_finance','add_special')?>" id="finance_form">
        <input type="hidden" name="act" value="seller_finance">
        <input type="hidden" name="op" value="add_special">
        <input type="hidden" name="id" value="<?php echo $output['seller']['member_id']?>">
        <input type="hidden" name="form_submit" value="ok">
        <div class="info">
            <div class="title">
                <span class="icon" style="color: red;">*</span>
                申请项目与金额
            </div>
            <div class="info" style="padding-left: 50px; ">
                <div class="number-data">
                    <span class="title span2">
                    <input type="radio" id="special_type_1" class="special_type" name="special_type" value="1" checked>
                        <label for="special_type_1">冻结</label>
                     </span>
                    <span class="span2">
                    </span>
                    <span class="val span3">
                        ￥ <input type="text" placeholder="范围￥ 0 ~ <?=$output['seller']['avaliable_deposit'];?>" data-range="<?=$output['seller']['avaliable_deposit'];?>" name="special_type_1_val">
                    </span>
                </div>

                <div class="number-data">
                    <span class="title span2">
                     <input type="radio" id="special_type_2" class="special_type" name="special_type" value="2">
                        <label for="special_type_2">解冻</label>
                     </span>
                    <span class="span2">
                    </span>
                    <span class="val span3">
                        ￥ <input type="text" placeholder="范围￥ 0 ~<?=$output['seller']['freeze_deposit'];?>" data-range="<?=$output['seller']['freeze_deposit'];?>" name="special_type_2_val">
                    </span>
                </div>

                <div class="number-data">
                    <span class="title span2">
                     <input type="radio" id="special_type_3" class="special_type" name="special_type" value="3">
                        <label for="special_type_3">转出</label>
                     </span>
                    <span class="span2">
                    </span>
                    <span class="val span3">
                        ￥ <input type="text" placeholder="范围￥ 0 ~<?=$output['seller']['avaliable_deposit'];?>" data-range="<?=$output['seller']['avaliable_deposit'];?>" name="special_type_3_val">
                    </span>
                </div>

                <div class="number-data">
                    <span class="title span2">
                     <input type="radio" id="special_type_4" class="special_type" name="special_type" value="4">
                        <label for="special_type_4">转入</label>
                     </span>
                    <span class="span2">
                    </span>
                    <span class="val span3">
                        ￥ <input type="text" placeholder="范围￥ 0 ~ <?=$output['max_transfer_to_user_account']?>" data-range="<?=$output['max_transfer_to_user_account']?>" name="special_type_4_val">
                    </span>
                </div>

                <div class="number-data">
                    <span class="title span2">
                     <input type="radio" id="special_type_5" class="special_type" name="special_type" value="5">
                        <label for="special_type_5">华车平台损失赔偿</label>
                     </span>
                    <span class="span2">
                        <span class="wt200">
                        <input type="text" name="order_id_1"  id="order_id_1" placeholder="请输入订单号" maxlength="40" >
                        </span>
                    </span>
                    <span class="val span3"><!--TODO  这里需要取最大值。未定-->
                        华车获得损失补偿  ￥
 <input type="text" class="number-val" placeholder="范围￥ 0 ~ 1000" data-range="1000" name="special_type_5_val">
                    </span>
                </div>

                <div class="number-data">
                    <span class="title span2">
                        <input type="radio" id="special_type_6" class="special_type" name="special_type" value="6">
                        <label for="special_type_6">获得赔偿返还</label>
                     </span>
                    <span class="wt200">
                        <input type="text" name="order_id_2"  id="order_id_2" placeholder="请输入订单号" maxlength="40" >
                    </span>
                    <span class="val span2">
                        平台返还 <em>：</em>1.从平台余额
                    </span>
                    <span class="span2">
                        ￥ <input type="text" placeholder="范围￥ 0 ~1000" name="special_type_7_val">
                    </span>
                </div>
                <div class="info">
                    <span class="title span2">&nbsp;</span>
                    <span class="span2">&nbsp;</span>
                    <span class="val span2"><em style="margin-left: 65px;"></em> 2.从待申报收入</span>
                    <span class="span2">
                        ￥ <input type="text" placeholder="范围￥ 0 ~ 1000" name="special_type_8_val">
                    </span>
                </div>


                <div class="info">
                  <span class="title span2">
                       <span class="icon" style="color: red;">*</span>原因
                  </span>
                    <span class="val span6">
                     <input type="text" class="wt400" name="reason" id="reason"/>
                </span>
                </div>
                <div class="info">
                  <span class="title span2">备注</span>
                    <span class="val span6">
                     <textarea class="wt400" style="min-height: 120px;" name="remark" id="remark" rows="10"></textarea>
                </span>
                </div>
            </div>
        </div>

        <div class="info footer">
            <a href="javascript:void(0);" class="button confirm button-small" style="margin-right: 100px;" onclick="confirm_form();">提交</a>
            <a href="javascript:history.go(-1);" class="button button-small">返回</a>
        </div>
    </form>

</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/buttons.css"  />
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-autocompleter-master/css/jquery.autocompleter.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-autocompleter-master/js/jquery.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-autocompleter-master/js/jquery.autocompleter.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-autocompleter-master/js/main.js" charset="utf-8"></script>


<script type="text/javascript">
var order_list= <?=json_encode($output['order_list']);?>;
$(function(){
    console.log(order_list);
    $('#order_id_1').autocompleter({
        highlightMatches: true,
        source: order_list,
        template: '{{ label }}',
        hint: true,
        empty: false,
        limit: 5,
        callback: function (value, index, selected) {
            console.log("xxxx")
            if (selected) {
//                $('.icon').css('background-color','#555555');
            }
        }
    });
    $('#order_id_2').autocompleter({
        highlightMatches: true,
        source: order_list,
        template: '{{ label }}',
        hint: true,
        empty: false,
        limit: 5,
        callback: function (value, index, selected) {
            if (selected) {
//                $('.icon').css('background-color','#555555');
            }
        }
    });

});
        //input  不能超过值上限
function isRangeVal(obj){
    _this  =  obj;
    value  =  parseFloat(_this.val());
    range  =  parseFloat(_this.data("range"));
    console.log(range);
    if(value > range){
        return false;
        _this.val("");
//     _this.focus();
    }else{
        return true;
    }
}



   /* function get_order_info(order_id, element_name)
    {
        //order_id
        post('index.php?act=seller_finance&op=ajax_get_orderinfo', {"id":order_id}).then(function(res){
            if(res.data.code == 200)
            {
                //alert(res.data.msg);
               //$("#"+element_name).val();
            }else
            {
                alert(res.data.msg);
            }
        }).catch(function(err){
            console.log(err);
        });
    }*/

    /**
     * 确认按钮
     */
    function confirm_form(){
        //确认转账日期已填写
        //valid_form();
        var _radio = $(".number-data input[type='radio']:checked")
        var _input = _radio.parents(".number-data").find("input[type='text']");
        var _val = _input.val();

        //console.log(isNaN(_val) ,!_input.hasClass("autocompleter-node"),isNaN(_val) && !_input.hasClass("autocompleter-node"))
        if(isNaN(_val) || _val == ""){
            if(_input.next("span").length != 0){
                //var _isRange = isRangeVal(_input);
                // TODO
                _input.next("span").show();
            }else{
                _input.after("<span style='color:#ff0000;margin-left:5px;'>您输入的不是数字</span>").show()
            }
            return false
        }

        layer.confirm('确定提交该特别事项申请吗？？', {
            btn: ['确认','取消'] //按钮
        }, function(){
            submit_form();
            closeLayer();
        }, function(){
            closeLayer();
        });
    }

    function submit_form(){
        var _reason = $('#reason').val();
        if(_reason ==''){
            alert("请填写原因");
            return false;
        }
        $('#finance_form').submit();
    }

$(".number-data input[type='text']").blur(function(){
    var _this = $(this)
    var _val = _this.val()
    if(isNaN(_val)){//&& !_this.hasClass("autocompleter-node")
        if(_this.next("span").length != 0){
            //_isRange = isRangeVal(_this);
            // TODO
            _this.next("span").show()
        }else{
            _this.after("<span style='color:#ff0000;margin-left:5px;'>您输入的不是数字</span>").show()
        }
        //_this.select()
         return false
    }
    return true
}).focus(function(){
    $(this).next("span").hide()
})
$(".number-data input[type='radio']").change(function(){
    _init()
})
var _init = function(){
    $(".number-data input[type='text']").attr("disabled","disabled").val("").next("span").hide()
    $(".number-data input[type='radio']:checked").parents(".number-data").find("input[type='text']").removeAttr("disabled")

}
_init();


</script>
