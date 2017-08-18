define(function (require, exports, module) {

    var app = new Vue({
        el: '#vue',
        data: {
           url:{
             setPwdUrl:"",
           },
           form:{
                pwd:"",
                pwd2:""
           },
           time:{
                hours:[0,0],
                minites:[0,0],
                seconds:[0,0],
                hour:0,
                minite:0,
                second:0
            },
           isOpenEye:!0,
           isSendCode:!1,

           countDownTime:60,
           sendCodeTxtArr:["\u70b9\u51fb\u83b7\u53d6\u77ed\u4fe1\u9a8c\u8bc1\u7801","\u91cd\u65b0\u83b7\u53d6","\u540e\u91cd\u65b0\u83b7\u53d6"],
           sendCodeTxt:"",

           pwdStrongStatus:0,
           pwdStatus:0,
           pwdInputStatus:0,
           pwd2InputStatus:0,
           isSame:!0,
           isReg:!1,
           isPwdInput:!1,
           isPwd2Input:!1,
           noticeCount:5,
           isNotCanSendNotice:!1,
           isCanCountDown:!1,
           isLoading:!1,
           countDownObj:{},
           errorBackUrl:"",
           successUrl:""
        },
        mounted:function(){
            this.sendCodeTxt = this.sendCodeTxtArr[0]
        }
        ,
        methods:{
            send:function(event){
                this.isReg = !1
                var _flag = true

                if (this.pwdInputStatus != 1) {
                    _flag = false
                    this.checkPwd()
                }
                if (!this.isOpenEye && this.pwd2InputStatus != 1) {
                    _flag = false
                    this.checkPwd2()
                }

                if (_flag) {
                    require("vendor/jquery.form")
                    var _form  = $("#password-step-4")
                    var options = {
                        type: 'post' ,
                        beforeSend: function(data) {
                            app.isLoading = !0
                        },
                        success: function(data) {
                            app.isLoading = !1
                            if (data.success == 0) {
                               app.answerError = !0
                               app.errorCount++
                            }
                            else if (data.success == 1) {
                                window.location.href= app.successUrl;
                            }
                        },
                        error: function(msg) {
                            app.answerError = !0
                            app.isLoading = !1
                            app.errorCount++
                        }
                    }
                    _form.ajaxForm(options).ajaxSubmit(options)
                }

            }
            ,
            openEye:function(phone) {
                this.isOpenEye = !this.isOpenEye
                if(this.form.pwd != this.form.pwd2) this.isSame = this.isOpenEye
                this.pwdInputStatus = this.pwdInputStatus == 1 ? 1 : 0
                this.pwd2InputStatus = this.pwd2InputStatus == 1 ? 1 : 0
            }
            ,
            countDown:function(curTime,endTime,isTime){
                if(isTime == 'false'){
                    window.location.href=app.errorBackUrl;
                }
                this.doCountDown(curTime,endTime,function(){
                    //倒计时回调
                    window.location.href=app.errorBackUrl;
                })
            },
            doCountDown:function(curTime,endTime,callback){
                var _this = this
                var _start = new Date(curTime) , _end = new Date(endTime)

                var _diff = (_end - _start) / 1000
                var _set = setInterval(function(){
                    if(_diff == 0) {
                        clearInterval(_set)
                        if (callback) {callback()}
                    }
                    var hh = parseInt(_diff / 60 / 60 % 24, 10)
                    var mm = parseInt(_diff / 60 % 60, 10)
                    var ss = parseInt(_diff % 60, 10)

                    _this.time.hours = [hh.toString().length == 2 ? hh.toString().slice(0,1) : 0 , hh.toString().length == 1 ? hh : hh.toString().slice(1)]
                    _this.time.minites = [mm.toString().length == 2 ? mm.toString().slice(0,1) : 0 , mm.toString().length == 1 ? mm : mm.toString().slice(1)]
                    _this.time.seconds = [ss.toString().length == 2 ? ss.toString().slice(0,1) : 0 , ss.toString().length == 1 ? ss : ss.toString().slice(1)]
                    _diff--
                },1000)
            },
            clearZero:function(){
                this.time.hours = [0,0]
                this.time.minites = [0,0]
                this.time.seconds = [0,0]
                this.time.hour = 0
                this.time.minite = 0
                this.time.second = 0
            }
            ,
            pwdStrong: function () {
                //检测密码强度
                //强|中|弱
                //弱：纯数字，纯字母，纯特殊字符
                //中：字母+数字，字母+特殊字符，数字+特殊字符
                //强：字母+数字+特殊字符
                if (this.form.pwd === "") {
                    this.pwdStatus = 2
                    return
                }
                else if (this.form.pwd.length < 6) {
                    this.pwdStatus = 3
                    return
                }
                this.pwdStatus = 1
                var _max    = !!this.form.pwd.match(/^(?![a-zA-z]+$)(?!\d+$)(?![!@#$%^&*]+$)(?![a-zA-z\d]+$)(?![a-zA-z!@#$%^&*]+$)(?![\d!@#$%^&*]+$)[a-zA-Z\d!@#$%^&*]+$/);
                var _normal = !!this.form.pwd.match(/^(?![a-zA-z]+$)(?!\d+$)(?![!@#$%^&*]+$)[a-zA-Z\d!@#$%^&*]+$/);
                var _less   = !!this.form.pwd.match(/^(?:\d+|[a-zA-Z]+|[!@#$%^&*]+)$/);

                if (_less) {
                    this.pwdStrongStatus = 1
                } else if (_normal) {
                    //满足强强度必定满足中强度
                    //所以在中强度中再次判断是否满足高强度
                    if (_max) {
                        this.pwdStrongStatus = 3
                    } else {
                        this.pwdStrongStatus = 2
                    }
                }
            },
            checkPwd:function(){
                if (this.form.pwd === "") {
                    this.pwdInputStatus = 2
                }
                else if (this.form.pwd.length < 6) {
                    this.pwdInputStatus = 3
                }else{
                    this.pwdStatus = 1
                    this.pwdInputStatus = 1;
                    $('#password_confirmation').val(this.form.pwd);
                }
            }
            ,
            checkPwd2:function(){
                if (this.form.pwd2 === "") {
                    this.pwd2InputStatus = 2
                }
                else if (this.form.pwd == this.form.pwd2) {
                    this.pwd2InputStatus = 1
                }
                else{
                    if (this.form.pwd === "" || this.form.pwd.length < 6) this.pwdInputStatus = 2
                    this.pwd2InputStatus = 3
                }
                $('#password_confirmation').val(this.form.pwd2);
            }
            ,
            setPwdStatus:function(){
                this.pwdInputStatus = 0
            }
            ,
            setPwd2Status:function(){
                this.pwd2InputStatus = 0
            }
            ,
            pwdSee:function(event){
                 $(event.target).parent().find("input").focus()
            }


        }
        ,
        watch:{

            'form.pwd':function(){
                app.pwdStrong()
                if(!this.isOpenEye){
                    if (this.form.pwd == this.form.pwd2) {
                        this.isSame = !0
                    }else{
                        this.isSame = !1
                    }
                }
                this.isPwdInput = this.form.pwd.length == 0 ? !1 : !0
            },
            'form.pwd2':function(){
                if(!this.isOpenEye){
                    if (this.form.pwd == this.form.pwd2) {
                        this.isSame = !0
                    }else{
                        this.isSame = !1
                    }
                }
                this.isPwd2Input = this.form.pwd2.length == 0 ? !1 : !0
            }
        }

    })

    module.exports = {
        init : function(nowTime,endTime,isTime){
             app.countDown(nowTime,endTime,isTime)
        },
        initUrl :function(errorBackUrl,successUrl){
            app.errorBackUrl      = errorBackUrl;
            app.successUrl        = successUrl;
        }
    }
});
