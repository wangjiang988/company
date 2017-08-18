
define(function (require, exports, module) {


    $("#login input").bind("keydown", function () {
        $(this).next().addClass("hide")
    })

    !(function(){

        var isIE = !!window.ActiveXObject
        var isIE6 = isIE && !window.XMLHttpRequest
        if (isIE && isIE6) {
            var _iefix = {
                init:function(){
                     this.fixLocation()
                     this.initIE6Png()
                     //this.fixMinLogin()
                }
                ,
                initIE6Png : function(){
                    $('img[src$=".png"]').each(function() {
                       if (!this.complete) {
                         this.onload = function() { fixPng(this) }
                       } else {
                         fixPng(this)
                       }
                    })
                }
                ,
                fixPng : function(png) {
                   var src = png.src;
                   if (!png.style.width) { png.style.width = $(png).width(); }
                   if (!png.style.height) { png.style.height = $(png).height(); }
                   png.onload = function() { };
                   png.src = blank.src;
                   png.runtimeStyle.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + src + "',sizingMethod='scale')";
                }
                ,
                fixLocation : function() {
                    /*var _logininfo = $(".loca-c")
                    $(".location.pos-abt").hover(function(){
                        _logininfo.show()
                    },function(){
                        _logininfo.hide()
                    })

                    _logininfo.find("a").bind("click",function(){
                        var _this = $(this)
                        _this.parent().parent().find("label").text(_this.text())
                        _this.parent().hide()
                    })*/
                } 
                ,
                fixMinLogin:function(){

                    $(".control li:eq(0) a").bind("click",function(){

                        require("cookie");
                        var _error = parseInt($.cookie(vmweb.logincookiename));
                        if (_error == 1) {
                            $(".code").removeClass("code");
                            vmweb.loginerror = 1;
                            $.cookie(vmweb.logincookiename, 1);
                        } else {
                            vmweb.loginerror = 0;
                            $.cookie(vmweb.logincookiename, 0);
                        }

                        var _scrollTop = $(document).scrollTop();
                        var _winWidth = $(window).width();
                        var _loginWin = $('#login');
                        var _winLeft = (_winWidth / 2) - (_loginWin.width() / 2);
                        _loginWin.css(
                                        {
                                            "left": _winLeft + "px",
                                            "top":  "20%"
                                        }
                                    )
                                .show();
                        $('.zm').css({"top": _scrollTop +"px"}).fadeIn('300');

                    })


                }

            }
           

            _iefix.init()
            
        }

    })()
    
    var vmweb = avalon.define({
        $id: 'web',
        headbg: "i-h-bg",
        ishasLoac: true,
        loginerror: 1,
        logincookiename: "loginerror",//记录是否登陆错误的cooike名字
        errormsgforname: "\u8bf7\u8f93\u5165\u8d26\u53f7",//Unicode编码后的登录名错误提示(请输入登录名)
        errormsgforpwd: "\u8bf7\u8f93\u5165\u5bc6\u7801",//Unicode编码后的登录密码错误提示(请输入密码)
        errormsgforcode: "\u8bf7\u8f93\u5165\u9a8c\u8bc1\u7801",//Unicode编码后的登录验证码错误提示(请输入验证码)
        header: "include/head.html",
        regheader: "include/head-reg.html",
        searchheader: "include/head-search.html",
        itemheader: "include/head-item.html",
        payheader: "include/head-pay.html",
        pwdheader: "include/head-pwd.html",
        customheader: "include/head-custorm.html",
        detialheader: "include/head-detial.html",
        userheader: "include/head-user.html",
        cutomloginheader: "include/head-custom-login.html",
        cutomadminheader: "include/head-admin-custom.html",
        footer: "include/foot.html",
        footernew: "include/foot-new.html",
        selectArea: function () {
            var _this = $(this);
            _this.parent().parent().find("label").text(_this.text());
            _this.parent().hide();
        }
         ,
        showLocation: function () {
            var _this = $(this);
            _this.children().last().show();
        }
        ,
        hideLocation: function () {
            var _this = $(this);
            _this.children().last().hide();
        }
        ,
        login: function () {
            //$("body").scrollTop(0);
            vmweb.init();
            $(this).blur();
            var _scrollTop = $(document).scrollTop();
            var _winWidth = $(window).width();
            var _loginWin = $('#login');
            var _winLeft = (_winWidth / 2) - (_loginWin.width() / 2);
            _loginWin.css(
                            {
                                "left": _winLeft + "px",
                                "top":  "20%"
                            }
                        )
                    .show();
            $('.zm').css({"top": _scrollTop +"px"}).fadeIn('300');
          
        }
        ,
        closeLogin: function () {
            $(".zm").fadeOut(300, function () {
                $(".zm").find("input").val("");
                if (vmweb.loginerror==0) {
                    var _logincode = $("input[name='logincode']");
                    _logincode.parent().addClass("code")
                } 
            })
        }
        ,
        SmipSubLoign: function () {
            
            var _flag = true;//是否通过验证
            var _this = $(this);
            var _loginnane = $("input[name='loginname']");
            var _loginpwd = $("input[name='loginpwd']");
            var _logincode = $("input[name='logincode']");

            if ($.trim(_loginnane.val())=="") {
                _loginnane.focus().next().text(vmweb.errormsgforname).removeClass("hide");
                _flag = false;
            }
            else if ($.trim(_loginpwd.val()) == "") {
                _loginpwd.focus().next().text(vmweb.errormsgforpwd).removeClass("hide");
                _flag = false;
            }
            if (_flag && vmweb.loginerror && $.trim(_logincode.val()) == "") {
                _logincode.focus().next().text(vmweb.errormsgforcode).removeClass("hide");
                _flag = false;
            }
            
            if (_flag) {
                    
                    $.ajax({
                        url: "http://www.llmztt.com/wp/getlist/2/",//真实环境请用这个 什么？我为什么不用？跨域！！！！ http://www.hwache.cn/login/
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
                            //测试环境中使用 正式环境请把下面三个data设置删除
                            //data = { "error_code": 0, "error_msg": "登录成功" }
                            //data = { "error_code": 1, "error_msg": "用户名或密码错误" }
                            //data = { "error_code": 2, "error_msg": "验证码错误" } 

                            var _error_code = data.error_code;
                            var _error_msg = data.error_msg;
                            if (_error_code == 0) {
                                $.cookie(vmweb.logincookiename, 0);
                                window.location.href = "http://user.hwache.cn/index.php?act=login&op=index";

                            } else {

                                if (_error_code == 1) {
                                    _loginpwd.focus().next().text(_error_msg).removeClass("hide");
                                }
                                else if (_error_code == 2) {
                                    _logincode.focus().next().text(_error_msg).removeClass("hide");
                                    vmweb.loginerror = true;//需要显示验证码
                                    _logincode.focus().parent().removeClass("code");
                                }
                                $.cookie(vmweb.logincookiename, 1);

                            }
                            _this.next().fadeOut(300);
                        }
                    });

               
            }

            return false;

        }
        ,
        init: function () {
            //var _href = window.location.href;
            //var _name = _href.substring(_href.lastIndexOf('/')+1);
            //if (!(_name == "" || _name == "index.html" || _name == "index.php")) {
            //    vmweb.headbg = "";
            //}
            require("cookie");
            var _error = parseInt($.cookie(vmweb.logincookiename));
     
            if (_error == 1) {
                $(".code").removeClass("code"); 
                vmweb.loginerror = 1;
                $.cookie(vmweb.logincookiename, 1);
            } else {
                vmweb.loginerror = 0;
                $.cookie(vmweb.logincookiename, 0);
            }
        }
        
    });


});