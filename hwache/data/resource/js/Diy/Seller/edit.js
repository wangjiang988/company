/**
 * Created by jerry on 2016/11/21.
 */
$(document).ready(function(){
    $('#seller_form').validate({
        rules : {
            member_truename : {
                required   : true,
                isChinese  : true,
                minlength  : 2
            },
            member_mobile : {
                required : true,
                isMobile  : true
            },
            seller_card_num : {
                required  : true,
                isIdCardNo : true
            },
            seller_bank_addr : {
                required   : true
            },
            seller_bank_account : {
                required : true
            },
            basic_deposit : {
                required : true,
                number : true,
                min:1
            },
            credit_line: {
                required : true,
                number : true,
                min:1
            }
        },
        messages : {
            member_truename : {
                required   : '姓名不能为空！',
                isChinese  : '姓名必须是中文！',
                minlength  : '姓名必须大于两个字符！'
            },
            member_mobile : {
                required : '手机号码不能为空！',
                isMobile  : '手机号码格式错误！'
            },
            seller_card_num : {
                required   : '身份证号码不能为空！',
                isIdCardNo : '身份证号码格式错误!'
            },
            seller_bank_addr  : {
                required   : '开户行不能为空！'
            },
            seller_bank_account : {
                required : '银行卡号不能为空！'
            },
            basic_deposit : {
                required : "固定保证金不能为空！",
                number : "必须是数字!",
                min:"必须是大于0"
            },
            credit_line: {
                required : "授信额度不能为空!",
                number : "必须是数字!",
                min:"必须是大于0"
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
});

function dialogDiv(){
    var _sellerId = $('#seller_id').val();
    layer.open({
        type: 2,
        title:'添加联系方式',
        skin: 'layui-layer-molv', //加上边框
        area: ['420px', '250px'],
        fixed: false, //不固定
        maxmin: true,
        content: ['index.php?act=seller&op=other&id='+_sellerId,'no']
    });
}
/**
 * 关闭弹窗并刷新页面
 */
function layerBack(){
    layer.closeAll();
    window.location.href=window.location.href;
}
/**
 * 删除确认弹窗
 * @param _key
 */
function delOther(_key){
    var _sellerId = $('#seller_id').val();
    var tcIndex = layer.confirm('您确认要删除吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        delOtherIndex(_sellerId,_key);
    }, function(){
        layer.close(tcIndex);
    });
}
/**
 * 删除处理
 * @param _sellerId
 * @param _key
 */
function delOtherIndex(_sellerId,_key){
    $.ajax({
        type : "post",
        url  : del_url,
        data : {id:_sellerId,key:_key},
        dataType : "json",
        success : function(_result){
            console.log(_result);
            if(_result.Success == 1){
                layerBack();
            }else{
                layer.alert('删除失败！');
            }
        },error:function (XMLHttpRequest, textStatus, errorThrown) {
            console.log("XMLHttpRequest:"+XMLHttpRequest);
            console.log("textStatus:"+textStatus);
            console.log("errorThrown:"+errorThrown);
        }
    });
}