/**
 * Created by jerry on 2016/11/21.
 */
$(document).ready(function(){
    $('#seller_form').validate({
        rules : {
            seller_name : {
                required   : true,
                minlength:4,
                remote: {
                    url: _checkUrl,
                    type: "post",
                    dataType:'json',
                    data: {
                        username: function() {
                            return $("#seller_name").val();
                        }
                    },
                    dataFilter:function(result){
                        console.log(result);
                        return result;
                    }
                }
            },member_truename : {
                required   : true,
                isChinese  : true,
                minlength  : 2
            },
            member_mobile : {
                required : true,
                isMobile  : true
            },
            password : {
                required   : true,
                isPassword : true
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
            seller_photo : {
                checkPicSize:true
            }
        },
        messages : {
            seller_name : {
                required   : '用户名不能为空！',
                minlength  : '用户名必须大于4个字符哟！',
                remote     : '用户名已经存在！'
            },
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
            password : {
                required   : '密码不能为空！',
                isPasswrod : '密码长度6-20位，并且必须包含大小写字母和数字'
            },
            seller_bank_addr  : {
                required   : '开户行不能为空！'
            },
            seller_bank_account : {
                required : '银行卡号不能为空！'
            },
            seller_photo : {
                checkPicSize : "上传图片大小不得超过1M！"
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
