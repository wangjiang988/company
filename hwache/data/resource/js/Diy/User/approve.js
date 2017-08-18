/**
 * Created by Administrator on 2017/7/26.
 */

function validateBank() {
    $('#bank_form').validate({
        rules: {
            status: {required: true}
        },
        messages: {
            status: {required: '请选择审核结果！'}
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
        success: function (e) {
            e.html("&nbsp;").addClass("valid").text('ok');
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
}
