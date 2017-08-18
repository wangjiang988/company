var LayerConfirm;
var subTypeObject = new Array();
subTypeObject['agree']  ={'confirm':"确定批准该笔提现申请吗？",'title':'批准办理'};
subTypeObject['refuse'] ={'confirm':"确定不办理该笔提现申请吗？",'title':'确认拒绝办理'};
subTypeObject['orders'] ={'confirm':"确定由你操作该提现转账吗？",'title':'提现接单'};
subTypeObject['confirms'] ={'confirm':"确定已完成该笔提现转账了吗？",'title':'提现确认'};
subTypeObject['saves'] ={'confirm':"确定保存上述补充信息吗？",'title':'保存补充信息'};
subTypeObject['subSaves'] ={'confirm':"确定提交上述补充信息吗？",'title':'提交补充信息'};
subTypeObject['updates'] ={'confirm':"确定要更正转账信息吗？",'title':'更正转账信息确认'};
subTypeObject['notUps'] ={'confirm':"确定要不更正转账信息吗？",'title':'不更正转账信息确认'};
subTypeObject['returns'] ={'confirm':"确定要退出操作该提现转账吗？",'title':'退单确认'};

function notSuccess(){
    var html ='<div id="notId"><p><input type="radio" name="reason" value="53" checked>我方原因：办理时输错银行账户信息。</p>';
        html +='<p><input type="radio" name="reason" value="52">客户原因：售方提供的银行账户信息有误。（下一步：提现金额重新转入售方可提现余额）</p>';
        html +='</div>';
    LayerConfirm =layer.confirm('请选择本次转账被退回的原因：', {
        title:'转账失败原因',
        content:html,
        btn: ["确定","取消"] //按钮
    }, function(){//我方原因  //进去到更正页面
        var values = $("input[name='reason']:checked").val();
        var id     = $('#dwb_id').val();
        location.href="/index.php?act=seller_finance&op=withdraw_result&id="+id+"&reason="+values;
    }, function(){//客户原因
        layer.close(LayerConfirm);
    });
}

function saveSubmitType(type){
    if(type =='saves' || type =='updates' || type =='subSaves'){
        submitSave();
        if($("#seller_form").valid()){
            submitData(type);
        }
    }else{
        submitData(type);
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
        $('#seller_form').submit();
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
        content: '/index.php?act=finance&op=ajax_common_add_comment&operation_type=23&status='+status+'&id='+id
    });
}

//添加备注弹框
function show_del_dialog(comment_id){
    parent.layer.confirm('您确认删除该条备注消息么？', {
        btn: ['确认','取消'] //按钮
    }, function(){
        post('index.php?act=seller_finance&op=ajax_del_comment&id='+comment_id).then(function(res){
            if(res.data.code == 200)
            {
                layer.alert(res.data.msg);
                window.location.reload();
                closeLayer();
            }else
            {
                parent.layer.msg(res.data.msg, {
                    time: 20000, //20s后自动关闭
                    btn: ['明白了']
                });
            }
        }).catch(function(err){
            console.log(err);
        });

    }, function(){
        closeLayer();
    });
}

$(function() {
    $('.date').datepicker({dateFormat: 'yy-mm-dd'});
});

function submitSave(){
    $('#seller_form').validate({
        rules : {
            bank_voucher_code : { required   : true  },
            accounting_voucher:{ required   : true  },
            recharge_voucher:{ required   : true}
        },
        messages : {
            bank_voucher_code : { required   : '银行凭证号不能为空！' },
            accounting_voucher:{ required   : '记账凭证号不能为空！' },
            recharge_voucher:{ required   : '请上传凭证！'}
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