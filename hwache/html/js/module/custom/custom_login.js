
define(function (require,exports,module) {

    require("jq") 
    var vm = avalon.define({
        $id: 'custom',
        init: function () {
             
        }
        ,
        showhidetip:function(obj,msg){
            obj.show().find(".error-txt").text(msg)
            setTimeout(function(){
                obj.fadeOut('400', function() {
                    
                })
            },3000)
        }   
        ,
        customlogin:function(){
            var _this  = $(this)
            var _form  = $("form[name='customform']")
            var _name  = _form.find("input[name='loginname']")
            var _pwd   = _form.find("input[name='loginpwd']")
            var _code  = _form.find("input[name='code']")
            var _flag  = true
            var _error = _form.find(".error-tip")
            if ($.trim(_name.val()) == "") {
                _flag  = false
                vm.showhidetip(_error,"\u8bf7\u8f93\u5165\u7528\u6237\u540d")
            }
            else if ($.trim(_pwd.val()) == "") {
                _flag  = false
                vm.showhidetip(_error,"\u8bf7\u8f93\u5165\u5bc6\u7801")
            }
            else if ($.trim(_code.val()) == "") {
                _flag  = false
                vm.showhidetip(_error,"\u8bf7\u8f93\u5165\u9a8c\u8bc1\u7801")
            }
            //ajax 登陆验证
            if (_flag) {
  

                $.ajax({
                        url: '登陆验证的路径', //自行替换url
                        type: "post",
                        dataType: "json",
                        data: {
                            loginname:_name.val(),
                            loginpwd:_pwd.val(),
                            code: _code.val()
                        },
                        beforeSend: function () {

                            _form.find("input").attr("disabled","disabled")
                            _this.attr("disabled","disabled").addClass("loginstatus").attr("data-value",_this.val()).val('\u6b63\u5728\u767b\u9646...')
                        }
                        ,
                        success: function (data) {
                            
                            var _error_code = data.error_code;
                            var _error_msg = data.error_msg;
                            //假定 _error_code 值
                            //case 0 验证码错误
                            //case 2 账号不存在
                            //case 3 密码错误
                            //case 4 三次密码错误
                            //case 1 通过-登陆-页面跳转
                            if (_error_code == 0 || _error_code == 2 || _error_code == 3) {
                                _flag  = false
                                vm.showhidetip(_error,error_msg)
                            } 
                            else if (_error_code == 4) {
                                _form[0].reset()
                                _form.find("input").removeAttr("disabled")
                                _form.find(".custom-login-wrapper").slideUp(200)
                                     .next().slideDown(200)
                            }
                            else if (_error_code == 1) {
                                _form[0].reset()
                                window.location.href = 'http://custom.hwache.com/'
                            }

                            
                            
                        }
                        ,
                        error:function(){
                            //下面代码是测试用的
                            //在实际环境中请删除
                            _form[0].reset()
                            _form.find("input").removeAttr("disabled")
                            _form.find(".custom-login-wrapper").slideUp(200)
                                 .next().slideDown(200)
                        }
                })
 

            }
 

        }
        ,
        customphonevalite:function(){

            var _this   = $(this)//发送按钮本身 
            var _form   = $("form[name='customform']")
            var _error  = _form.find(".error-tip")
            var _valite = $("input[name='phonevalite']") 
            
            /*if ($.trim(_valite.val()) == "") {
                vm.showhidetip(_error,"\u8bf7\u8f93\u5165\u624b\u673a\u9a8c\u8bc1\u7801")
                return
            }*/
            var _phone = '这手机号码从哪里来？自己看吧。。。只能帮你到这里！'

            $.ajax({
                    url: 'http://www.hwache.cn/sendcode/'+ _phone, 
                    type: "post",
                    dataType: "json",
                    data: {},
                    beforeSend: function () {
                        _this.attr("disabled","disabled")
                        //下面代码是测试用的
                        //在实际环境中请删除
                        var _stxt = _this.attr("data-s")
                        var _curtxt = _this.attr("data-send")
                        var _$time = 60
                        _this.val(_curtxt.replace("$1", _$time))
                        var _timeTMP = setInterval(function () {
                          
                            if (_$time == 0) {
                                _this.val(_stxt) 
                                _$time = 60
                                _this.removeAttr("disabled","disabled")
                                clearInterval(_timeTMP)
                            } else {
                                _$time--
                                _this.val(_curtxt.replace("$1", _$time))
                            }
                        }, 1000)
                    }
                    ,
                    success: function (data) {
                        
                        var _error_code = data.error_code
                        var _error_msg = data.error_msg
                        //假定_error_code = 0 为发送失败
                        if (_error_code == 0) {
                            vm.showhidetip(_error,error_msg)
                            return 
                        }
                        var _$time = 60
                        var _stxt = _this.attr("data-s")
                        var _curtxt = _this.attr("data-send")
                        _this.val(_curtxt.replace("$1", _$time))
                        var _timeTMP = setInterval(function () {
                            if (_$time == 0) {
                                _this.val(_stxt) 
                                _$time = 60
                                _this.removeAttr("disabled","disabled")
                                clearInterval(_timeTMP)
                            } else {
                                _$time--
                                _this.val(_curtxt.replace("$1", _$time))
                            }
                        }, 1000)
                        
                        
                    }
                    ,
                    error:function(){
                        
                       
                    }
            }) 
        }
        ,
        customphonelogin:function(){

            var _this   = $(this) 
            var _form   = $("form[name='customform']")
            var _error  = _form.find(".error-tip")
            var _valite = $("input[name='phonevalite']") 
            
            if ($.trim(_valite.val()) == "") {
                vm.showhidetip(_error,"\u8bf7\u8f93\u5165\u624b\u673a\u9a8c\u8bc1\u7801")
                return
            }
            var _phonevalite = _valite.val()

            $.ajax({
                    url: 'http://www.hwache.cn/customphonelogin/'+ _phonevalite, 
                    type: "post",
                    dataType: "json",
                    data: {},
                    beforeSend: function () {
                        _this.attr("disabled","disabled")
                    }
                    ,
                    success: function (data) {
                        
                        var _error_code = data.error_code
                        var _error_msg = data.error_msg
                        //假定_error_code 
                        //case 0 验证码失效
                        //case 2 验证码错误
                        //case 1 验证通过 - 登陆 - 跳转
                        _this.removeAttr("disabled","disabled")
                        if (_error_code == 0 || _error_code == 2) {
                            vm.showhidetip(_error,error_msg)
                            return 
                        } 
                        else if (_error_code == 0) {
                            window.location.href = 'http://custom.hwache.com/'
                        }  
                    }
                    ,
                    error:function(){
                        
                       
                    }
            }) 
        }
        ,
        ruzhu:function(){
            require("module/common/hc.popup.jquery")
            $("#ruzhu-tip").hcPopup({'width':'486'})
        }
        ,
        closepopup:function(){
            $("#ruzhu-tip").fadeOut(200)
        }
        
    })
        
})

   