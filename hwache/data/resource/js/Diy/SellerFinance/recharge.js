/**
 * Created by Administrator on 2017/5/22.
 */
function seller_validate(){
    $('#seller_form').validate({
        rules : {
            recorded_status : { required  : true },
            recharge_money:{ required : true , number:true },
            recharge_confirm_at:{ required : true }
        },
        messages : {
            recorded_status : { required   : '请选择入账状态！' },
            recharge_money:{ required : '到账金额不能为空' , number:'到账金额必须是数字' },
            recharge_confirm_at:{ required   : '银行到账时间不能为空！' }
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

function finance_valideate(){
    $('#finance_form').validate({
        rules : {
            bank_name : {
                required   : true
            },
            bank_voucher_code:{
                required   : true
            },
            accounting_voucher:{
                required   : true
            }
        },
        messages : {
            bank_name : {
                required   : '请选择收款银行！'
            },
            bank_voucher_code:{
                required   : '银行凭证号不能为空！'
            },
            accounting_voucher:{
                required   : "记账凭证号不能为空！"
            }
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
});

var LayerConfirm;
var subTypeObject = new Array();
var _formName = '#finance_form';

subTypeObject['saves'] ={'confirm':"确定保存补充信息吗？",'title':'保存补充信息'};
subTypeObject['submits'] ={'confirm':"确定提交上述补充信息吗？",'title':'提交补充信息'};
subTypeObject['checks'] ={'confirm':"确定提交核实信息吗？",'title':'提交核实信息'};
subTypeObject['confirms']={'confirm':"确定提交核实信息吗？",'title':'提交核实信息'};

function saveSubmitType(type){
    finance_valideate();
    if($(_formName).valid()){
        submitData(type);
    }
}

function checkStatus(obj){
    var _this = $(obj);
    if(_this.prop("checked")){
        $("input[name='recorded_status']").prop("checked",false);
        _this.prop("checked",true);
        var _status = $("input[name='recorded_status']:checked").val();
        if(_status ==0){
            $("input[name='recharge_money']").attr('readonly',true);
            $("input[name='recharge_confirm_at']").attr('disabled',true);
        }else{
            $("input[name='recharge_money']").attr('readonly',false);
            $("input[name='recharge_confirm_at']").attr('disabled',false);
        }
    }
}

function submitType(type){
    var _status = $("input[name='recorded_status']:checked").val();
    if(typeof(_status) =='undefined'){
        layer.alert('请选择入账状态！');
    }else{
        _formName = '#seller_form';
        if(_status ==1){
            seller_validate();
            if($(_formName).valid()){
                submitData(type);
            }
        }else{
            submitData(type);
        }
    }

}

function submitData(type){
    var confirmStr = subTypeObject[''+type+''].confirm;
    var titleStr   = subTypeObject[''+type+''].title;
    LayerConfirm = layer.confirm(confirmStr, {
        title:titleStr,
        btn: ['确定','取消'] //按钮
    }, function(){
        $('#type').val(''+type+'');
        $(_formName).submit();
    }, function(){
        layer.close(LayerConfirm);
    });
}

function addRemarkDialog(id,status){
    //ajax_common_add_comment
    parent.layer.open({
        type: 2,
        title:"添加备注",
        skin: 'layui-layer-rim', //加上边框
        area: ['500px', '320px'], //宽高
        content: '/index.php?act=finance&op=ajax_common_add_comment&operation_type=22&status='+status+'&id='+id
    });
}

//添加备注弹框
function show_del_dialog(comment_id){
    parent.layer.confirm('您确认删除该条备注消息么？', {
        btn: ['确认','取消'] //按钮
    }, function(){
        post('index.php?act=seller_finance&op=ajax_del_comment&id='+comment_id).then(function(res){
            parent.layer.alert(res.data.msg);
            window.location.reload();
            closeLayer();
        }).catch(function(err){
            console.log(err);
        });

    }, function(){
        closeLayer();
    });
}
