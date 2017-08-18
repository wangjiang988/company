define(function (require) {

    require("jq");//
    require("module/reg/reg-common");
    var _$time = 59;

   
    var vm = avalon.define({
        $id: 'reg',
        isClick: false,
        init: function () {

        } 
        ,
        SendCode: function () {
            if (vm.isClick) {
                return;
            }
            var _phone = $("input[name='phone']");
            var tel = _phone.val();
            var telReg = !!tel.match(/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/);
            var _flag = true;
            var _this = $(this);//发送按钮本身
            if ($.trim(_phone.val()) == "") {
                _phone.focus().next().removeClass("hide");
                _flag = false;
                
            }
            else if (telReg == false) {
                //如果手机号码不能通过验证
                _phone.focus().next().removeClass("hide");
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
                    //_this.addClass("form-loading");
                    var _timeTMP = setInterval(function () {
                        $(".form-loading").hide();
                        if (_$time == 0) {
                            _this.text(_stxt);
                            vm.isClick = false;
                            _$time = 59;
                            clearInterval(_timeTMP);
                        } else {
                            _this.text(_curtxt.replace("$1", _$time));
                            _$time--;
                        }
                    }, 1000)
                //})
            }
            
        }
        ,
        SendAndSetPwd: function () {
            var _phone = $("input[name='phone']");
            var _code = $("input[name='code']");
            var _flag = true;
            var tel = _phone.val(); 
            var telReg = !!tel.match(/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/);
            if ($.trim(_phone.val())=="") {
                _phone.focus().next().removeClass("hide");
                _flag = false;
            } else if (telReg == false) {
                //如果手机号码不能通过验证
                _phone.focus().next().removeClass("hide");
                _flag = false;
            }
            else if ($.trim(_code.val()) == "") {
                _code.focus().next().removeClass("hide");
                _flag = false;
            }
            //通过填写验证
            if (_flag) {
          
                try {
                    $.get("获取手机验证码", function (data) {
                        if (data == $.trim(_code.val())) {
                            window.location.href = "reg-phone-set-pwd.html";//验证成功，跳转页面
                        } else {
                            _phone.focus().next().removeClass("hide");//验证不成功 错误信息
                        }
                    })
                } catch (e) {
                    alert('服务器异常！');
                }
            }
        }
      
    });
    
    vm.init();

});