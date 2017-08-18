define(function (require, exports, module) {
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
           pwdStatus:0,
           loginStatus:0,
           codeStatus:0,
           isLoading:!1,
           loginSuccess:!1,
           isCodeValite:!1,
           loginErrorCount:0,
           isLoginTips:!1,
           isFreeze:!1,
           freezeCount:0,
           isFreezeAgain:!1,
           rememberMe:["1"]
        },
        mounted:function(){
           //this.loginErrorCount = 0
            var _name = this.getCookie("name")
            if (_name) this.form.phone = _name
        }
        , 
        methods:{
        	simpleLoign:function() { 
        		if (this.loginErrorCount >= 10) return
        		if (this.isLoading) return	
                var _flag = !0
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
                	if (this.isCodeValite) {
                		if(this.trim(this.form.code) === ""){
                			this.codeStatus = 2
                			_flag = !1
                		}
                	}
                }

                if (_flag) {
                	require("vendor/jquery.form")
                	var _form  = $("#smiple-login-form")
					var options = {
						type: 'post' ,
						beforeSend: function(data) {
						    app.isLoading = !0
						},
						success: function(data) {
							app.isLoading = !1
						    if (data.code == 0) {
						    	app.loginStatus = 2
						    	app.loginErrorCount++
						    	$("#codeimg").click()
						    }
						    else if (data.code == 1) {
						    	if(app.rememberMe.length > 0) app.setCookie("name",app.form.phone)
						        else app.delCookie("name")		
						    	app.loginSuccess = !0
						    	app.form.phone = ""
						    	app.form.pwd = ""
						    	app.loginErrorCount = 0 
						    	window.location.reload()
						    }
						},
						error: function(msg) {
						    app.isLoading = !1
						}
					}
					_form.ajaxForm(options).ajaxSubmit(options)
                }
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
                $(event.target).prev().click()
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
            }
        }
        ,
        watch:{
        	'form.phone':function(){
                this.loginNameStatus = 0
                this.loginStatus = 0
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
        		 if (n >= 2) {
        		 	this.isCodeValite = !0
        		 }
        		 if (n === 8 || n === 9) {
        		 	this.isLoginTips = !0
        		 }
        		 if (n === 10) {
        		 	this.isLoginTips = !1
        		 	if(this.freezeCount == 0)
        		 		this.isFreeze = !0 
        		 }
            }


        }
    })

})