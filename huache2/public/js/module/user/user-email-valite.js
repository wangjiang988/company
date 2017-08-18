define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    //开发环境中请把每个$.ajax或ajaxForm中的error方法删除
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/left") 
    require("/webhtml/common/js/vendor/jquery.form") 

    var app = new Vue({
        el: '.user-content',
        data: {
            error:!1,
            email:"",
            isEmail:!0,
            errorEmail:!1,
            successUrl:""
        },
        mounted:function(){
            
        }
        ,
        methods:{
            initSendControl:function(){
                if (this.sendCount>=5) $(".btn-send-code").attr("disabled",true)
            },
            initEmail:function(){
                this.isEmail = !0
            },
            checkEmail:function(){
                if (this.email === "" || !this.isEmailAddress(this.email)){
                    this.isEmail = !1
                }
                this.errorEmail = !1
            },
            isEmailAddress:function(email) { 
                var pattern = /^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/
                return pattern.test(email)
            },
          
            next:function(){
                if (this.email === "" || !this.isEmailAddress(this.email)){
                    this.isEmail = !1
                }
                else{
                	var _this = this
	                var _form = $("form[name='code-email']")
	                var options = {
	                    success: function (data) {
                           //0:验证码错误 2:手机号被占用
	                       if (data.success == 0) _this.error = !0 
                           if (data.success == 2) _this.errorEmail = !0  
	                       else window.location.href = app.successUrl;
	                    },
	                    error:function(){
	                       _this.error = !0 
                           _this.errorEmail = !0 
	                    } 
	                }
	                _form.ajaxForm(options).ajaxSubmit(options) 
                }
            }
        },
        watch:{
            
        }

    }) 
     
  
    module.exports = {
        initSendCount:function(count,_successUrl){
            app.sendCount = count;
            app.successUrl = _successUrl;
        }
    }
})
