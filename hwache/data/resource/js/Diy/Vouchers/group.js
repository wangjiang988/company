var _validator;
function validateGroup(){
    _validator = $('#voucher_form').validate({
        rules : {
            life_start_time : { required   : true},
            life_end_time:{ required   : true },
            remark:{required   : true ,minlength:4}
        },
        messages :{
            life_start_time : { required   : '有效开始时间不能空！'},
            life_end_time:{ required   : '有效结束时间不能空！' },
            remark:{required   : '请填写说明！' ,minlength:'说明文字必须大于4个汉字！'}
        },
        //设置错误信息存放标签
        errorElement: "label",
        //指定错误信息位置
        errorPlacement: function (error, element) {
            if (element.is(':radio') || element.is(':checkbox')) {
                var eid = element.attr('name');
            }
            error.appendTo(element.parent());
        },
        //设置验证触发事件
        focusInvalid: true,
        //设置验证成功提示格式
        success:function(e)
        {
            e.html("&nbsp;").addClass("valid").text('ok');
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
}
$(function() {
    $('.date').datepicker({dateFormat: 'yy-mm-dd'});
    $.mask.definitions['2'] = "[0-2]";
    $.mask.definitions['3'] = "[0-3]";
    $.mask.definitions['4'] = "[0-4]";
    $.mask.definitions['5'] = "[0-5]";
    $(".start_time").mask("29:59",{placeholder:"00:00"});
    $(".time").mask("29:59",{placeholder:"24:00"});
    $("#use_collateral").change(function(){
        var _use_sincerity = $("#use_sincerity").is(':checked');
        if(!_use_sincerity){
            if($(this).is(":checked") ==true){
                $("label[for='use_sincerity']").remove();
            }else{
                $("#use_sincerity").parent().after('<label for="use_sincerity" class="error">请选择代用款项！</label>');
            }
        }
    });
});

function addBrand(target,value,sub_target){
    var url = 'index.php?act=hc_vouchers&op=brand&parent='+value;
    $.getJSON(url,function(data){
        var _option = '<option value="">--选择--</option>';
        $(target).html(_option);
        $(sub_target).html(_option);
        if(sub_target !=''){
            $(sub_target).html(_option);
        }
        $.each(data,function(index,item){
            _option += '<option value="'+item.gc_id+'">'+item.gc_name+'</option>';
        });
        $(target).html(_option);
    });
}


function submitJhGroup(){
    validateGroup();
    $("#activated_num").rules("add",{ required: true, messages: { required: "请选择激活位数！"} });
    $("#activated_rule").rules("add",{ required: true, messages: { required: "请选择激活规则！"} });
    $("#activated_start_time").rules("add",{ required: true, messages: { required: "请选择激活有效期开始时间！"} });
    $("#activated_end_time").rules("add",{ required: true, messages: { required: "请选择激活有效期结束时间！"} });
    $("#activated_total_num").rules("add",{
        required: true, number : true , min:0 , max:500 ,
        messages: { required: "请填写条数！", number :'请填写数字！' , min:"不能小于0", max:"每组条数不能大于500！"}
    });
    var _type = $("input[name='type']:checked").val();
    if(_type ==1){
        $("#brand_id").rules("add", {
            required: true,
            messages: { required: "请选择品牌！"}
        });
    }else{
        $("#brand_id").removeClass('error').rules("remove");
    }
    var _use_sincerity = $("#use_sincerity").is(':checked');
    var _use_collateral = $("#use_collateral").is(':checked');
    if(!_use_collateral && !_use_sincerity){
        $("#use_sincerity").rules("add",{
            required: true,
            messages: { required: "请选择代用款项！"}
        })
    }else{
        $("#use_sincerity").removeClass('error').rules("remove","required");
    }
    if(_use_sincerity){
        $("#sincerity_money").rules("add", {
            required: true,
            number:true,
            min:0,
            max:200,
            messages: {
                required: '请输入诚意金金额！',
                number : "诚意金必须是数字！",
                min:"不能小于0",
                max : "诚意金不能大于200！"
            }
        });
    }else{
        $("#sincerity_money").removeClass('error').rules("remove");
        $("#sincerity_money").val('');
    }
    if(_use_collateral){
        $("#collateral_money").rules("add", {
            required: true,
            number:true,
            min:0,
            max:300,
            messages: {
                required: '请输入担保金金额！',
                number : "担保金必须是数字！",
                min:"不能小于0",
                max : "担保不能大于300！"
            }
        });
    }else{
        $("#collateral_money").removeClass('error').rules("remove");
        $("#collateral_money").val('');
    }
    //console.table($('#voucher_form').rules());
    if($('#voucher_form').valid()){
        $('#voucher_form').submit();
    }
}

function submitGroup(){
    validateGroup();
    var _type = $("input[name='type']:checked").val();
    if(_type ==1){
        $("#brand_id").rules("add", {
            required: true,
            messages: { required: "请选择品牌！"}
        });
    }else{
        $("#brand_id").removeClass('error').rules("remove");
    }
    var _use_sincerity = $("#use_sincerity").is(':checked');
    var _use_collateral = $("#use_collateral").is(':checked');
    if(!_use_collateral && !_use_sincerity){
        $("#use_sincerity").rules("add",{
            required: true,
            messages: { required: "请选择代用款项！"}
        })
    }else{
        $("#use_sincerity").removeClass('error').rules("remove","required");
    }
    if(_use_sincerity){
        $("#sincerity_money").rules("add", {
            required: true,
            number:true,
            min:0,
            max:200,
            messages: {
                required: '请输入诚意金金额！',
                number : "诚意金必须是数字！",
                min:"不能小于0",
                max : "诚意金不能大于200！"
            }
        });
    }else{
        $("#sincerity_money").removeClass('error').rules("remove");
        $("#sincerity_money").val('');
    }
    if(_use_collateral){
        $("#collateral_money").rules("add", {
            required: true,
            number:true,
            min:0,
            max:300,
            messages: {
                required: '请输入担保金金额！',
                number : "担保金必须是数字！",
                min:"不能小于0",
                max : "担保不能大于300！"
            }
        });
    }else{
        $("#collateral_money").removeClass('error').rules("remove");
        $("#collateral_money").val('');
    }
    if($('#voucher_form').valid()){
        $('#voucher_form').submit();
    }
}

function failure(id){
    var html = '<table class="tab"><tr><td class="right">请输入失效原因：</td>';
    html += '<td><textarea name="remark" id="remark" style="width:200px; height: 60px;" ></textarea> </td>';
    html += '</tr><tr><td class="center"><button class="button button-small" type="button" onclick="setStatus('+id+',2,1)">确定</button></td>';
    html += '<td class="center"><button class="button button-small" type="reset">取消</button></td></tr></table>';
    layer.open({
        type: 1,
        title:'券组失效',
        shadeClose: true,
        skin: 'layui-layer-rim', //加上边框
        area: ['420px', '250px'], //宽高
        content: html
    });
}

function setStatus(id,status,tabType){
    var remark = $('#remark').val();
    if(remark ==''){
        $("label[for='ramark']").remove();
        $('#remark').after('<label for="ramark" class="error">请输入失效原因！</label>');
        return false;
    }else{
        $("label[for='ramark']").remove();
    }
    LayerConfirm = layer.confirm("确定要将该代金券组失效吗？", {
        title:"确认失效",
        btn: ['确定','取消'] //按钮
    }, function(){
        $.ajax({
            url:'/index.php?act=hc_vouchers&op=setStatus',
            type:"post",
            dataType:"json",
            data:{"type":tabType,"id":id,"status":status,"remark":remark},
            success:function(result){
                layer.alert(result.Msg);
                setTimeout('DelayClose()',1000);
            },error:function(){
                alert('Error');return false;
            }
        });
    }, function(){
        layer.close(LayerConfirm);
    });
}

function DelayClose(){
    layer.closeAll();
}