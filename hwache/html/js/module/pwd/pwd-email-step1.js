﻿define(function (require) {

    require("jq");//
    require("module/reg/reg-common");
    var _$time = 59;
   
    var vm = avalon.define({
        $id: 'reg',
        isClick:false,
        init: function () {

        } 
        ,
        SendCode: function () {
            if (vm.isClick) {
                return;
            }
            var _email = $("input[name='email']");
            var email = _email.val();
            var emailReg = !!email.match(/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/);
            var _flag = true;
            var _this = $(this);//发送按钮本身
            if ($.trim(_email.val()) == "") {
                _email.focus().next().removeClass("hide");
                _flag = false;
            }
            else if (emailReg == false) {
                _email.focus().next().removeClass("hide");
                _flag = false;
            }
            //通过填写验证
            if (_flag) {
                //真实环境请把$.post释放出来当然也可以自己写ajax
                //$.post("/code/phone/?num="+ tel, function (data) {
                    var _stxt = _this.attr("data-s");
                    var _curtxt = _this.attr("data-send");
                    //_this.css('background-color','#eee');
                    $(".form-loading").show()
                    vm.isClick = true;
                    _this.removeClass("email-code");
                    //_this.addClass("form-loading");
                    window.location.href = "reg-email-send-sucess.html";
                //})
            }
            
        }
      
      
    });
    
    vm.init();

});