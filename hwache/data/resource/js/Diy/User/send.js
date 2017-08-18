function sendSms() {
    var _phone = $('#phone').val();
    if(_phone ==''){
        layer.msg('手机号不能为空！');return false;
    }
    $.ajax({
        type: "get",
        url: send_sms_url,
        data: {phone: _phone, template_code: '78530065',max:20,code:1},
        dataType: "json",
        success: function (_result) {
            console.log(_result);
            if (_result.success == 1) {
                layer.alert(_result.msg);
            } else {
                layer.msg(_result.msg);
            }
        }, error: function (XMLHttpRequest, textStatus, errorThrown) {
            console.log("XMLHttpRequest:" + XMLHttpRequest);
            console.log("textStatus:" + textStatus);
            console.log("errorThrown:" + errorThrown);
        }
    });
}

function subReset(){
    //验证手机是否存在
    var _phone = $('#phone').val();
    var _code  = $('#code').val();
    var _user_id = $('#user_id').val();
    $.ajax({
        type: "post",
        url: sub_reset_url,
        data: {phone: _phone, template_code: '78530065','code':_code,'user_id':_user_id},
        dataType: "json",
        success: function (_result) {
            console.log(_result);
            if (_result.Success == 1) {
                layer.alert(_result.Msg);
            } else {
                layer.msg(_result.Msg);
            }
            return false;
        }, error: function (XMLHttpRequest, textStatus, errorThrown) {
            console.log("XMLHttpRequest:" + XMLHttpRequest);
            console.log("textStatus:" + textStatus);
            console.log("errorThrown:" + errorThrown);
        }
    });
}
