define(function (require, exports, module) {
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/left")
    var app = new Vue({
        el: '.user-content',
        data: { 
           url:{
            
           },
           form:{
                pwdS:"",
                pwd:"",
                pwd2:""
           },
           isOpenEye:!0,
           pwdStrongStatus:0,
           pwdStatus:0,
           pwdInputStatus:0,
           pwd2InputStatus:0,
           pwd3InputStatus:0,
           isSame:!0,
           isPwdInput:!1,
           isPwd2Input:!1,
           noticeCount:5,
           isNotCanSendNotice:!1,
           isCanCountDown:!1,
           seePwdStrong:!1,
           sourcePwd:0,
           count:3,
           sendUrl:"",
           updateSuccessUrl:"",
           updateErrorUrl:""
        },
        mounted:function(){

        }
        ,
        methods:{ 
            send:function(event){
                this.isReg = !1
                var _flag = true  
                if (this.pwd3InputStatus != 1) {
                    _flag = false
                    this.checkPwdS()
                } 
                if (this.pwdInputStatus != 1) {
                    _flag = false
                    this.checkPwd()
                }  
                if (!this.isOpenEye && this.pwd2InputStatus != 1) {
                    _flag = false
                    this.checkPwd2()
                }  

                if (_flag) {
                    $.ajax({
                         type: "POST",
                         url:app.sendUrl,
                         data: {
                            old_password:app.form.pwdS,
                            password:app.form.pwd,
                            _token:$("input[name='_token']").val()
                         },
                         dataType: "json",
                         beforeSend: function(data){ 
                             event.target.setAttribute("disabled", true)
                         },
                         success: function(data){
                             event.target.removeAttribute("disabled");
                             //data.count 表示已经发送多少次了
                             app.count = 10 - data.count
                             if (data.count <= 6) app.sourcePwd = 2
                             if (data.count >6 && data.count < 10) app.sourcePwd = 3
                             if (data.count >= 10){
                                 window.location.href = app.updateErrorUrl;
                             } else {
                                 switch(data.success){
                                     case 1:
                                         window.location.href = app.updateSuccessUrl;
                                         break;
                                     case 0:
                                         window.location.href = app.updateErrorUrl;
                                         break;
                                 }
                             }
                         },
                         error: function(data){
                             event.target.removeAttribute("disabled")  
                             app.sourcePwd = 2
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
            },
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
            checkPwdS:function(){
                if (this.form.pwdS === "") {
                    this.pwd3InputStatus = 2 
                }
                else{
                    this.pwd3InputStatus = 1
                }
            },
            checkPwd:function(){
                if (this.form.pwd === "") {
                    this.pwdInputStatus = 2 
                }
                else if (this.form.pwd.length < 6) {
                    this.pwdInputStatus = 3 
                }
                else if (this.form.pwd != this.form.pwd2) {
                    this.pwd2InputStatus = 3 
                    this.pwdStatus = 1
                    this.pwdInputStatus = 1 
                }
                else if (this.form.pwd == this.form.pwd2) {
                    this.pwd2InputStatus = 1 
                    this.pwdStatus = 1
                    this.pwdInputStatus = 1 
                }
                else{
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
            setPwd3Status:function(){
                this.pwd3InputStatus = 0 
                this.sourcePwd = 0
            }
            ,
            pwdSee:function(event){
                 $(event.target).parent().find("input").focus()
            }

        }
        ,
        watch:{
           
            'form.pwd':function(n,o){
                if (n.length >= 6) this.seePwdStrong = !0
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
        init : function(_sendUrl,_successUrl,_errorUrl){
            app.sendUrl          = _sendUrl;
            app.updateSuccessUrl = _successUrl;
            app.updateErrorUrl   = _errorUrl;
        } 
    }
});