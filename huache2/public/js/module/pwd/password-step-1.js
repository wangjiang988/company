
define(function (require, exports, module) {

    var app = new Vue({
        el: '#vue',
        data: { 
           form:{
                phone:"",
                code:""                 
           },
           phoneStatus:0,
           codeStatus:0,
           isPhoneError:!1,
           isCodeError:!1,
           isLoading:!1
          
        },
        mounted:function(){
            
        }
        ,
        methods:{ 
            send:function(event){
              	this.changPhone() 
              	this.changCode()
                if (!this.isPhoneError && !this.isCodeError) {
                   	require("vendor/jquery.form")
                  	var _form  = $("#password-step-1")
          					var options = {
          						type: 'post' ,
          						beforeSend: function(data) {
          						    app.isLoading = !0
          						},
          						success: function(data) {
                          
          						    app.isLoading = !1
          						    if (data.success == 0) {
                            app.isCodeError = !0
                            app.codeStatus = 3
          						    	app.refreshCode()
          						    }
          						    else if (data.success == 1) {
          						    	window.location.href = data.url;                                                        
          						    }
          						},
          						error: function(msg) {
          						    app.refreshCode()
          						    app.isLoading = !1
          						    /*app.isCodeError = !0
          						    app.codeStatus = 3*/
          						}
          					}
          					_form.ajaxForm(options).ajaxSubmit(options)
                }

            },
            setPhone:function() { 
                this.phoneStatus = 0 
            }
            ,
            changPhone:function() { 
            	  this.isPhoneError = !1 
                if (this.form.phone === "") {
                	this.phoneStatus = 2
                	this.isPhoneError = !0
                }
                else if (!this.isPhoneNo(this.form.phone) && !this.isEmail(this.form.phone)) {
                	this.phoneStatus = 3
                	this.isPhoneError = !0	
                } 
            }
            ,
            setCode:function() { 
                this.codeStatus = 0 
            }
            ,
            changCode:function() { 
            	  this.isCodeError = !1
                if (this.form.code === "") {
                	this.codeStatus = 2
                	this.isCodeError = !0	
                } 
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
             
            },
            refreshCode:function(status) { 
                document.getElementById('codeimg').click()
            }  
             

        }
        ,
        watch:{
            'form.phone':function(){
                 
            }  
        }

    })
  
    module.exports = {
        init : function(count){
            
        } 
    }
});