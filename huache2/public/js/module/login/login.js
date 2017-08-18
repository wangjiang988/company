define(function (require) {

    require("jq");//
    require("module/reg/reg-common");
    var _$time = 59;
   
    var vm = avalon.define({
        $id: 'reg',
        init: function () {

        } 
        ,
        SendAndSetPwd: function () {

            var _loginnane = $("input[name='login-name']");
            var _loginpwd = $("input[name='login-pwd']");
            var _logincode = $("input[name='login-code']"); 
            var _flag = true;
            if ($.trim(_loginnane.val())=="") {
                _loginnane.focus().next().removeClass("hide");
                _flag = false;
            }
            else if ($.trim(_loginpwd.val())=="") {
                _loginpwd.focus().next().removeClass("hide");
                _flag = false;
            }
            else if ($.trim(_logincode.val()) == "") {
                _logincode.focus().next().removeClass("hide");
                _flag = false;
            }
            //通过填写验证
            if (_flag) {

                try {

                    var _this = $(this)

                    $.ajax({
                        url: "http://www.hwache.cn/login",
                        type: "post",
                        dataType: "json",
                        data: {
                            loginname: _loginnane.val(),
                            loginpwd: _loginpwd.val(),
                            logincode: _logincode.val()
                        },
                        beforeSend: function () {
                            _this.next().fadeIn(200);
                        }
                        ,
                        success: function (data) {
                            
                            var _error_code = data.error_code;
                            var _error_msg = data.error_msg;
                            if (_error_code == 0) {
                                $.cookie(vmweb.logincookiename, 0);
                                window.location.href = "http://user.hwache.cn/index.php?act=login&op=index";

                            } else {

                                if (_error_code == 1 || _error_code == 3) {
                                    _loginpwd.focus().next().text(_error_msg).removeClass("hide");
                                }
                                else if (_error_code == 2) {
                                    _logincode.focus().next().text(_error_msg).removeClass("hide");
                                }

                            }
                            _this.next().fadeOut(300);
                        }
                    });



                   /* $.getJSON("http://www.hwache.cn/login", function (data) {
                         
                            if (data.error_code == 0 ) {
                                window.location.href= $("#hfLoginBackUrl").val()
                            }else if (data.error_code == 1 || data.error_code == 3 ){
                                _loginpwd.focus().next().removeClass("hide");
                            }
                            else if (data.error_code == 2 ){
                                _logincode.focus().next().removeClass("hide");
                            }
                    })*/

                    

                } catch (e) {
                    alert('服务器异常！');
                } 
            }
        }
      
    });
    
    vm.init();

});