define(function (require, exports, module) {

/*  登录错误编号及含义：error_code
    '1010'   冻结20分钟
    '1020'   冻结当天
    '1003'   错误次数超过两次，出现验证码
    '1000'   用户名密码错误
    '2000’   验证码错误
    '3001'   验证次数剩余1次
    '3002'   验证次数剩余2次
*/

    if (!document.getElementById('smiple-login')) return
    var app = new Vue({
        el: '#smiple-login',
        data: {
            isOpenEye:!0,
            form:{
                phone:"",
                code:"",
                pwd:""
           },
           isPwdInput:!1,
           isLoginNameInput:!1,
           loginNameStatus:0,
           rememberMe:["1"],
           pwdStatus:0,
           loginStatus:0,
           codeStatus:0,
           isLoading:!1,
           loginErrorCount:0,
           rotate:0,
           errorCode:0,
           codeShowErrorCount:3,
           loginSuccess:!1,
           isCodeValite:!1, 
           //
           isAccountError:!1,
           isShowValiteCode:!1,
           isShowRestCount:!1,
           restCount:2,
           isCodeError:!1,
           isFirstFreeze:!1,
           isSecondFreeze:!1,
           isDisabled:!1,
           isSuccess:!1,
 
           
           
        },
        created:function(){
            require("/webhtml/common/js/module/store.legacy.min")
        },
        mounted:function(){
            this.getLoginName()
            this.isHasValiteCode() 
        }
        ,
        methods:{
          
            checkLoginName:function(){
                this.loginNameStatus = this.form.phone.trim() == "" ? 2 : !this.isPhoneNo(this.form.phone) && !this.isEmail(this.form.phone) ? 3 : 0
            },
            initLoginName:function(){
                this.loginNameStatus = 0
            },
            isHasValiteCode:function(event){
                var _phone = event ? event.target.value : this.form.phone
                if (!_phone && _phone == "") return
                $.get('/member/login/freeze?name='+_phone, function(data) {
                    if (data.error_code == 1003 || data.error_code == 3001 || data.error_code == 3002) {
                        app.isShowValiteCode = !0
                        app.initValiteCode()
                    }
                    else if(data.error_code == 1010){
                        app.firstFreeze()
                    } 
                    else if (data.error_code == 1020){
                        app.secondFreeze()
                    }
                    else if (data.error_code == 1000){
                        app.clearFreezeErrorInfo()
                    }
                    else if (data.error_code == 0){
                        app.clearAllError()
                    }
                })
            },
            clearAllError:function(){
                app.isShowValiteCode = !1
                app.form.code = ""
                app.clearErrorInfo()
                app.clearFreezeErrorInfo()
            },
            clearFreezeErrorInfo:function(){
                this.isFirstFreeze = !1
                this.isSecondFreeze = !1
                this.isDisabled = !1
                this.isSuccess = !1 
            },
            clearErrorInfo:function(){
                this.isAccountError = !1
                this.isShowRestCount = !1
                this.isCodeError = !1
                
            },
            checkLoginAccount:function(event){ 
                this.isHasValiteCode()
            },
            firstFreeze:function(){
                app.freeze() 
                //十次冻结
                app.isFirstFreeze = !0 
            },
            secondFreeze:function(){
                app.freeze() 
                //二十次冻结
                app.isSecondFreeze = !0 
            },
            freeze:function(){
                app.clearErrorInfo()
                app.clearFreezeErrorInfo()
                //验证码隐藏
                app.isShowValiteCode = !1
                //冻结按钮
                app.isDisabled = !0
            },
            
            simpleLoign:function() {
                //消除密码错误提示
                this.isAccountError = !1
                //检测输入状况
                if (this.checkInput()) {
                    require("/js/vendor/jquery.form")
                    var _form  = $("#smiple-login-form")
                    var options = {
                        type: 'post' ,
                        beforeSend: function(data) {
                            app.isLoading = !0
                        },
                        success: function(data) {
                            app.successCallback(data)
                        },
                        error: function(msg) {
                            app.isLoading       = !1
                            app.isAccountError  = !0
                        }
                    }
                    _form.ajaxForm(options).ajaxSubmit(options)
                }
            },
            successCallback:function(data){
                app.isLoading = !1 
                switch(data.success){
                    case 0:
                        app.catchError(data)
                        break;
                    case 1:
                        app.loginSuccessed(data)
                        break
                    case 2:
                        app.isCodeError = !0 
                        app.isShowValiteCode = !0
                        app.initValiteCode()
                        break
                }
            },
            loginSuccessed:function(data){
                if(app.rememberMe.length > 0){
                    app.setCookie("name",app.form.phone)
                    store.set('phone', app.form.phone)
                } 
                else app.delCookie("name")
                app.isSuccess = !0
                app.form.phone = ""
                app.form.pwd = "" 
                store.set('api_token',{token:data.token})
                if (Vue.config.optionMergeStrategies.rejecturl)  
                    window.location.href = Vue.config.optionMergeStrategies.rejecturl()
                else
                    window.location.reload()
            },
            catchError:function(data){
                if (data.error_code == 1000){
                    app.isAccountError = !0 
                } 
                else if (data.error_code == 1003){
                    app.isAccountError = !0
                    app.isShowValiteCode = !0
                    app.initValiteCode() 
                }  
                else if (data.error_code == 1010){
                    app.firstFreeze()
                }  
                else if (data.error_code == 1020){
                    app.secondFreeze()
                }
                else if (data.error_code == 3002) {
                    app.isShowValiteCode = !0
                    app.initValiteCode()
                    app.isShowRestCount = !0
                    app.restCount = 2
                }
                else if (data.error_code == 3001) { 
                    app.isShowValiteCode = !0
                    app.initValiteCode()
                    app.isShowRestCount = !0
                    app.restCount = 1 
                }
                else console.log("未知状态")
            },
            checkInput:function(){
                var _flag = !0
                if (this.isDisabled || this.isLoading) {
                    return !1
                }
                if (this.trim(this.form.phone) === "") {
                    _flag = !1
                    this.loginNameStatus = 2
                }
                if (this.trim(this.form.pwd) === "") {
                    _flag = !1
                    this.pwdStatus = 2
                }
                if (_flag) {

                    if (!this.isPhoneNo(this.form.phone) && !this.isEmail(this.form.phone)) {
                        _flag = !1
                        this.loginNameStatus = 3
                    }
                }
                if (_flag) {
                    if (app.isShowValiteCode) {
                        if($.trim(this.form.code) === ""){
                            this.codeStatus = 2
                            _flag = !1
                        }
                    }
                }
                return _flag
            },
            closeLogin:function(){
                $("#smiple-login").fadeOut(300)
                var _name = this.getCookie("name")
                if (_name != null && _name != this.form.phone) {
                    setTimeout(function(){
                        app.isHasValiteCode()
                    },1000)
                }
                if (_name == null) app.clearAllError()
                this.initLoginName()
                this.getLoginName()
                this.form.pwd  = ""
                this.form.code = ""
                this.codeStatus = 0
                this.clearErrorInfo()
                Vue.config.optionMergeStrategies.rejecturl = null
                
            }
            ,
            getLoginName:function(call){
                var _name = this.getCookie("name")
                this.form.phone = _name || ""
                if (call) call()
            },
            setCookie:function(name, value) {
                var today = new Date()
                var expires = new Date()
                expires.setTime(today.getTime() + 1000*60*60*24*365)
                document.cookie = name + "=" + this.compileStr(value) + "; expires=" + expires.toGMTString()
            },
            delCookie:function(name) {
                var exp = new Date()
                exp.setTime (exp.getTime() - 1)
                var cval = this.getCookie (name)
                document.cookie = name + "=" + cval + "; expires="+ exp.toGMTString()
            },
            getCookie:function(name){
                var arg = name + "="
                var alen = arg.length
                var clen = document.cookie.length
                var i = 0;
                while (i < clen)
                {
                    var j = i + alen;
                    if (document.cookie.substring(i, j) == arg)
                        return this.getCookieVal(j)
                    i = document.cookie.indexOf(" ", i) + 1
                    if (i == 0) break
                }
                return null
            },
            getCookieVal:function(offset) {
                var endstr = document.cookie.indexOf (";", offset)
                if (endstr == -1)
                endstr = document.cookie.length
                return this.uncompileStr(document.cookie.substring(offset, endstr))
            },
            compileStr:function(code){ //对字符串进行加密
                var c=String.fromCharCode(code.charCodeAt(0)+code.length)
                for(var i=1;i<code.length;i++)
                    c+=String.fromCharCode(code.charCodeAt(i)+code.charCodeAt(i-1))
                return escape(c)
            },
            uncompileStr:function(code){
                code=unescape(code)
                var c=String.fromCharCode(code.charCodeAt(0)-code.length)
                for(var i=1;i<code.length;i++)
                    c+=String.fromCharCode(code.charCodeAt(i)-c.charCodeAt(i-1))
                return c
            },
            trim:function(str) {
                return str.replace(/(^\s*)|(\s*$)/g, "")
            },
            refreshCode:function(event) {
                $(event.target).prev().click()//.end()
                $(event.target).css({'transform':"rotateZ("+(this.rotate+=180)+"deg)"})
            },
            openEye:function(phone) {
                this.isOpenEye = !this.isOpenEye
            },
            pwdSee:function(event){
                $(event.target).parent().find("input").focus()
            }
            ,
            isPhoneNo:function(phone) {
                var pattern = /^1[34578]\d{9}$/
                return pattern.test(phone)
            }
            ,
            isEmail:function(email) {
                var pattern =  /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/
                return pattern.test(email)
            },
            initValiteCode:function(){
                var _img = document.getElementById('codeimg')
                _img.src = _img.getAttribute("data-url")
            },
            dymRefreshCode:function(){
                document.getElementById('refresh-code').click()
            }

        }
        ,
        watch:{
            'form.phone':function(n,o){
                /*this.loginStatus = 0
                if (n.length == 11){
                    this.isHasValiteCode() 
                    this.clearErrorInfo()
                }*/
            },
            'form.pwd':function(){
                this.pwdStatus = 0
                this.isPwdInput = this.form.pwd.length == 0 ? !1 : !0
                this.loginStatus = 0
            },
            'form.code':function(){
                this.codeStatus = 0
                this.loginStatus = 0
            },

            'loginErrorCount':function(n,o){
                  
            }


        }
    })

})