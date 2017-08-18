define(function (require, exports, module) {

    var app = new Vue({
        el: '#vue',
        data: { 
           url:{
             checkPhoneUrl:"/user/checkPhoneUrl/",
             sendCodeUrl:"/user/sendCodeUrl/",
             checkCodeUrl:"/user/checkCodeUrl/",
             regUrl:"/user/regUrl/",
           },
           form:{
                phone:"",
                code:"",
                pwd:"",
                pwd2:""
           },
           isPhone:!0, 
           checkPhoneCode:0,                                    //验证手机号是否可用 1.通过验证 2.号码不可用 3.手机号码为空
           isOpenEye:!0,
           isSendCode:!1,
           isCodeError:!1, 
           countDownTime:60,
           sendCodeTxtArr:["\u70b9\u51fb\u83b7\u53d6\u77ed\u4fe1\u9a8c\u8bc1\u7801","\u91cd\u65b0\u83b7\u53d6","\u540e\u91cd\u65b0\u83b7\u53d6"],
           sendCodeTxt:"",
           checkCodeStatus:0,
           pwdStrongStatus:0,
           pwdStatus:0,
           pwdInputStatus:0,
           pwd2InputStatus:0,
           isSame:!0,
           agree:[],
           isAgree:!1,
           isReg:!1,
           isPwdInput:!1,
           isPwd2Input:!1,
           noticeCount:5,
           isNotCanSendNotice:!1,
           isCanCountDown:!1


          
        },
        mounted:function(){
            this.sendCodeTxt = this.sendCodeTxtArr[0]
            /*require("module/common/hc.popup.jquery")
            $("#reg-error-info").hcPopup({'width':'400'})*/
        }
        ,
        methods:{ 
            send:function(event){
                this.isReg = !1
                var _flag = true  
                if (this.checkPhoneCode != 1 ) {
                    _flag = false
                    if (this.form.phone === "") {
                     this.checkPhoneCode = 3
                    }
                    else if (!this.isPhoneNo(this.form.phone)) {
                      this.isPhone = !0 
                    } 
                }  
                if (this.pwdInputStatus != 1) {
                    _flag = false
                    this.checkPwd()
                }  
                if (!this.isOpenEye && this.pwd2InputStatus != 1) {
                    _flag = false
                    this.checkPwd2()
                }  

                if (this.form.code === "") {
                    this.checkCodeStatus = 3
                    _flag = false
                }

                if (this.checkCodeStatus == 2 && this.isSendCode && _flag) {
                    return
                }
                if (this.agree.length === 0 && _flag) {
                    this.isReg = !0
                    this.isAgree = !1
                    _flag = false
                }

                if (_flag) {
                    $.ajax({
                         type: "POST",
                         url:app.url.regUrl,
                         data: {
                            phone:app.form.phone,
                            pwd:app.form.pwd,
                            code:app.form.code
                         },
                         dataType: "json",
                         beforeSend: function(data){ 
                             event.target.setAttribute("disabled", true)
                         },
                         success: function(data){
                             event.target.removeAttribute("disabled")  
                         },
                         error: function(data){
                             event.target.removeAttribute("disabled")  
                         }
                    }) 
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
            isPhoneNo:function(phone) { 
                var pattern = /^1[34578]\d{9}$/
                return pattern.test(phone)
            }
            ,
            setPhoneStatus:function(status) { 
                this.checkPhoneCode = 0 
            }
            ,
            checkPhone:function(){
              if ( this.form.phone =="") {
                 this.checkPhoneCode = 3
              }
              else if (!this.isPhoneNo(this.form.phone)) {
                  this.isPhone = !1  
                  this.checkPhoneCode = 4  
              }else{
                this.isPhone = !0
                 $.ajax({
                     type: "GET",
                     url:app.url.checkPhoneUrl,
                     data: {phone:app.form.phone},
                     dataType: "json",
                     success: function(data){
                        //返回内容{code:1,count:1}
                        //code 1:通过验证 2:已经注册过
                        //count是当前号码还能发送多少次验证码
                        app.checkPhoneCode = data.code   
                        app.noticeCount = data.count     
                     },
                     error: function(data){
                        app.checkPhoneCode = 1  
                        //app.noticeCount = 1  
                     }
                }) 
              }
             
            } 
            ,
            
            getCode:function($event){
                 if (!this.isPhoneNo(this.form.phone)) {
                    this.isPhone = !1 
                 }else{

                    if(this.checkPhoneCode != 1) return
                    if(this.noticeCount <= 0) { 
                        this.isNotCanSendNotice = !0
                        this.noticeCount = -1
                        return
                    } 
                    
                    $.ajax({
                         type: "GET",
                         url:app.url.sendCodeUrl,
                         data: {phone:app.form.phone},
                         dataType: "json",
                         beforeSend: function(data){ 
                            app.sendCodeTxt = app.sendCodeTxtArr[2] 
                            app.isCanCountDown = !0
                            --app.noticeCount
                         },
                         success: function(data){
                            app.countDown($event.target)
                            app.isSendCode = !0   
                         },
                         error: function(data){
                            app.countDown($event.target)
                            app.isSendCode = !0      
                         }
                    }) 
                 }
            } 
            ,
            countDown:function(evt){
                evt.setAttribute("disabled", true)
                var _time =
                setInterval(function() { 
                    if (app.countDownTime == 0) { 
                        evt.removeAttribute("disabled")  
                        app.countDownTime = 60
                        //app.isSendCode = !1 
                        app.isCanCountDown = !1
                        app.sendCodeTxt = app.sendCodeTxtArr[0]
                        clearInterval(_time)
                    } else { 
                        app.countDownTime--
                    }  

                },1000)
            } 
            ,
            checkCode:function(){
                if (this.form.code === "" ) 
                {
                    this.checkCodeStatus = 3
                    return
                }
                $.ajax({
                     type: "GET",
                     url:app.url.checkCodeUrl,
                     data: {code:app.form.code},
                     dataType: "json",
                     success: function(data){
                         app.checkCodeStatus = data.code
                     },
                     error: function(data){
                         app.checkCodeStatus = 1
                     }
                })  
            }
            ,
            initCodeStatus :function(){
                if (this.checkCodeStatus != 1)  
                    this.checkCodeStatus = 0
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
                

            }
            ,
            checkPwd:function(){
                if (this.form.pwd === "") {
                    this.pwdInputStatus = 2 
                }
                else if (this.form.pwd.length < 6) {
                    this.pwdInputStatus = 3 
                }else{
                    this.pwdStatus = 1
                    this.pwdInputStatus = 1 
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
            'form.phone':function(){
                 
            },
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
            },
            'agree.length':function(){
                this.isAgree = !0
            }

              
            
        }

    })
  
    module.exports = {
        init : function(count){
            
        } 
    }
});