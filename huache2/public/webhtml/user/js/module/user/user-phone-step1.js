define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    //开发环境中请把每个$.ajax或ajaxForm中的error方法删除
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/left") 
    require("/webhtml/common/js/vendor/jquery.form") 
    require("./user-code-count-down-component")

    var app = new Vue({
        el: '.user-content',
        //mixins: [mixin],
        data: {
            code:"",
            error:!1,
            successUrl:"",
            isEmpty:!1
        },
        mounted:function(){
            $(".valite-phone-code input").focus(function(event) {
                app.error = !1
            })
        }
        ,
        methods:{
        
            getCode:function(code,isError){
            	this.code = code
            	//this.error = !isError
            },
            next:function(){
            	if (this.code === "") {
                    this.isEmpty = !0 
                    this.$refs.phoneCode.isEmpty = !0
                }
                else if (!this.checkNum(this.code)) {
                    this.error = !0 
                }else{
                	var _this = this
	                var _form = $("form[name='code-phone']")
	                var options = {
	                   success: function (data) {
	                       if (data.success == 0) {
                                _this.error = !0 
                                //_this.$refs.phoneCode.isEmpty = !0
                           }
	                       else{
                                window.location.href = _this.successUrl
                           }
	                   },
	                   error:function(){
	                       _this.error = !0 

	                   } 
	                }
	                _form.ajaxForm(options).ajaxSubmit(options) 
                }
            },
            checkNum:function(num){
                if (num.length < 6 || isNaN(num)) {
                    return !1
                }
                return !0
            }
        }
        ,
        watch:{
            
            '':function(){
                 
            }  
            
        }

    }) 
     
  
    module.exports = {
        init:function(SuccessUrl){
            app.successUrl = SuccessUrl
        }
    }
})
