/**
 * Created by Administrator on 2017/6/13.
 */

var LayerConfirm;
var subTypeObject = new Array();
subTypeObject['agree']  ={'confirm':"确定同意投放吗？",'title':'同意投放'};
subTypeObject['notAgree'] ={'confirm':"确定不同意投放吗？",'title':'不同意投放'};
subTypeObject['endAgree'] ={'confirm':"确定终止投放吗？",'title':'终止投放'};
subTypeObject['download'] ={'confirm':"确定下载该组激活码吗？",'title':'确认下载激活码'};
subTypeObject['confirm']  ={'confirm':"您确认要投放免激活码吗？",'title':'确认投放'};

function ajaxSub(type,id,status,tabType,activated){
    var confirmStr = subTypeObject[''+type+''].confirm;
    var titleStr   = subTypeObject[''+type+''].title;
    LayerConfirm = layer.confirm(confirmStr, {
        title:titleStr,
        btn: ['确定','取消'] //按钮
    }, function(){
        setStatus(id,status,tabType,activated);
    }, function(){
        layer.close(LayerConfirm);
    });
}

function submitData(type,status){
    var confirmStr = subTypeObject[''+type+''].confirm;
    var titleStr   = subTypeObject[''+type+''].title;
    LayerConfirm = layer.confirm(confirmStr, {
        title:titleStr,
        btn: ['确定','取消'] //按钮
    }, function(){
        $('#status').val(status);
        setTimeout('closeAll()',1000);
    }, function(){
        layer.close(LayerConfirm);
    });
}

function closeAll(){
    layer.closeAll();
    $('#release_form').submit();
}

function setStatus(id,status,tabType,activated){
    var url = "index.php?act=hc_vouchers&op=setStatus&type="+tabType+"&id="+id+"&status="+status+"&activated="+activated;
    $.getJSON(url,function(result){
        layer.alert(result.Msg);
        setTimeout('DelayClose()',1000);
    });
}

function DelayClose(){
    layer.closeAll();
}


function validateRelese(){
    $('#release_form').validate({
        rules : {
            remark:{required   : true ,minlength : 4,maxlength : 255}
        },
        messages :{
            remark:{required   : '请填写说明！' ,minlength:'说明文字不能小于4个汉字！',maxlength:"说明文字不能大于255个汉字|"}
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
    addUserStr();
});

function addUser(){
    $('#user_input').append('<input type="text" class="member"/>');
    addUserStr();
}

function addUserStr(){
    $("#user_input .member").blur(function(){
        var _self = $(this);
        var _phone = _self.val();
        if(_phone==''){
            $("label[for='userValue']").remove();
            $("label[for='users']").remove();
            $(this).after('<label for="userValue" class="error">请填写手机号！</label>');
        }else{
            $.getJSON("index.php?act=admin&op=is_phone&mobile="+_phone,function(result){
                if(result.Success ==0){
                    $("label[for='userValue']").remove();
                    _self.after('<label for="userValue" class="error">'+result.Msg+'</label>');
                }else{
                    $("label[for='userValue']").remove();
                    getUserInput();
                    _self.hide();
                }
            });
        }
    })
}

function getUserInput(){
    var _member = new Array();
    jQuery.each( $("#user_input .member"), function(i, field){
        if($(this).val() != ''){
            _member[i] = $(this).val();
        }
    });
    $('#userList').html(_member.join('、'));
    $('#users').val(_member.join('、'));
}

function submitJhRelease(){
    validateRelese();
    var release_total_max = parseInt($("#release_total_num").attr('max'));
    $("#release_total_num").rules("add",{
        required: true, number:true , min:0 , max: release_total_max,
        messages: {
            required: "请填写投放条数数！",
            number : "投放条数必须是数字",
            min :"不能小于0",
            max :"投放条数不能大于"+release_total_max+"！"
        }
    });
    var _object_val = $("input[name='release_object']:checked").val();
    if(_object_val ==0){
        $("#guide_dept").rules("add",{ required: true, messages: { required: "请选择部门！"} });
        $("#guide_name").rules("add",{ required: true, messages: { required: "请选择员工！"} });
    }else{
        $("#guide_dept").removeClass('error').rules("remove");
        $("#guide_name").removeClass('error').rules("remove");
    }
    if(_object_val ==1){
        $("#proxy_name").rules("add",{ required: true, messages: { required: "请填写代理人姓名！"} });
        $("#d_dept").rules("add",{ required: true, messages: { required: "指导-请选择部门！"} });
        $("#d_guide_name").rules("add",{ required: true, messages: { required: "指导-请选择员工！"} });
        var _reward_type = $("input[name='reward_type']:checked").val();
        if(_reward_type ==1){
            $("#reward_money2").val('');
            $("#reward_money1").rules("add", {
                required: true, number: true, min:0 , max: 100,
                messages: {
                    required: "请填写结算金额比例！",
                    number: "结算金额比例必须是数字",
                    min:"不能小于0",
                    max: "结算金额比例最大100%！"
                }
            });
        }else{
            $("#reward_money1").removeClass('error').rules("remove");
        }
        if(_reward_type ==2){
            $("#reward_money1").val('');
            $("#reward_money2").rules("add", {
                required: true, number: true, min:0 ,max: 500,
                messages: {
                    required: "请填写结算订单金额！",
                    number: "结算订单金额必须是数字",
                    min:"不能小于0",
                    max: "结算订单金额最多￥500！"
                }
            });
        }else{
            $("#reward_money2").removeClass('error').rules("remove");
        }
    }else{
        $("#proxy_name").removeClass('error').rules("remove");
        $("#d_dept").removeClass('error').rules("remove");
        $("#d_guide_name").removeClass('error').rules("remove");
        $("#reward_money1").removeClass('error').rules("remove");
        $("#reward_money2").removeClass('error').rules("remove");
    }
    if($('#release_form').valid()){
        LayerConfirm = layer.confirm("确定投放需激活代金券组吗?", {
            title:"确认投放",
            btn: ['确定','取消'] //按钮
        }, function(){
            $('#release_form').submit();
        }, function(){
            layer.close(LayerConfirm);
        });
    }
}

function submitRelease(){
    validateRelese();
    setRelus();
    if ($('#release_form').valid()) {
        LayerConfirm = layer.confirm("确定投放需激活代金券组吗?", {
            title:"确认投放",
            btn: ['确定','取消'] //按钮
        }, function() {
            $('#release_form').submit();
        }, function(){
            layer.close(LayerConfirm);
            return false;
        });
    }
}

function setRelus(){
    var _type = $("input[name='ignore_object']:checked").val();
    if (_type == 1) {
        $("#users").rules("add", {
            required: true,
            messages: {required: "请填写客户手机号！"}
        });
    } else {
        $("#users").removeClass('error').rules("remove");
        $("#users").hide();
        $("#users").val('');
    }
    var _ignore_time_type = $("input[name='ignore_time_type']:checked").val();
    if(_ignore_time_type ==1){
        $("#fixed_start_time").rules("add", {
            required: true,
            messages: {required: "请选择投放时间！"}
        });
    }else{
        $("#fixed_start_time").removeClass('error').rules("remove");
    }
}

function failure(id){
    var html = '<table class="tab"><tr><td class="right">终止投放原因：</td>';
    html += '<td><textarea name="remark" id="remark" style="width:200px; height: 60px;" ></textarea> </td>';
    html += '</tr><tr><td class="center"><button class="button button-small" type="button" onclick="setFailure('+id+',3,2)">确定</button></td>';
    html += '<td class="center"><button class="button button-small" type="reset">取消</button></td></tr></table>';
    layer.open({
        type: 1,
        title:'终止投放',
        shadeClose: true,
        skin: 'layui-layer-rim', //加上边框
        area: ['420px', '250px'], //宽高
        content: html
    });
}

function setFailure(id,status,tabType){
    var remark = $('#remark').val();
    if(remark ==''){
        $("label[for='ramark']").remove();
        $('#remark').after('<label for="ramark" class="error">请填写终止投放原因！</label>');
        return false;
    }else{
        $("label[for='ramark']").remove();
    }
    LayerConfirm = layer.confirm("确定要终止投放吗？", {
        title:"确认终止投放",
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