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
            phone:"",
            isPhone:!0,
            errorPhone:!1,
            sendCount:0,
            sendCountTmp:0,
            simpleCountDown:5,
            successUrl:"",
            errorUrl:"",
            checkPhoneUrl:""
        },
        mounted:function(){
            //this.initSendControl()
        }
        ,
        methods:{
            initSendControl:function(){
                if (this.sendCount>=5) $(".btn-send-code").attr("disabled",true)
            },
            initPhone:function(){
                this.isPhone = !0
            },
            checkPhone:function(){
                if (this.phone === "" || !this.isPhoneNo(this.phone)){
                    this.isPhone = !1;
                    $(".btn-send-code").attr("disabled",true);
                }else{
                    this.isPhone = !0
                    var _this = this;
                    $.ajax({
                        type: "GET",
                        url:app.checkPhoneUrl,
                        data: {phone:_this.phone},
                        dataType: "json",
                        success: function(data) {
                            
                            switch(data.success){
                                case 1:
                                    _this.errorPhone = !1;
                                    _this.isPhone = !0;
                                    $(".btn-send-code").attr("disabled",false);
                                    break;
                                case 2:
                                    _this.errorPhone = !0;
                                    $(".btn-send-code").attr("disabled",true);
                                    break;
                                default:
                                    _this.isPhone = !1;
                                    $(".btn-send-code").attr("disabled",true);
                            }
                           
                        },
                        error: function(data){
                            _this.isPhone !=1; 
                        }
                    })
                }
            },
            isPhoneNo:function(phone) { 
                var pattern = /^1[34578]\d{9}$/
                return pattern.test(phone)
            },
            getCode:function(code,isError,noPhone){
            	this.code = code
            	this.error = !isError
                this.isPhone = noPhone == undefined ? true : noPhone
            },
            getSendCount:function(count) { 
                console.log("getSendCount:"+count)
                this.sendCount = count
            },
            next:function(){
                if (this.phone === "" || !this.isPhoneNo(this.phone)){
                    this.isPhone = !1
                }
            	else if (this.code === "") {
                    this.error = !0 
                }
                else if (!this.checkNum(this.code)) {
                    this.error = !0 
                }else{
                	var _this = this
	                var _form = $("form[name='code-phone']")
	                var options = {
	                   success: function (data) {
                           //0:验证码错误 2:手机号被占用
                           switch(data.success){
                               case 0:
                                   _this.error = !0
                                   break;
                               case 1:
                                   window.location.href = _this.successUrl;
                                   break;
                               case 2:
                                   _this.errorPhone = !0
                                   break;
                               case 4:
                                   window.location.href = _this.errorUrl;
                                   break;
                               default:
                                   _this.error = !0
                           }
	                   },
	                   error:function(){
	                       _this.error = !0 
                           _this.errorPhone = !0 
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
            'sendCount':function(n,o){
                 //this.initSendControl()
            }
        }
    }) 
     
  
    module.exports = {
        initSendCount:function(count,SuccessUrl,ErrorUrl,CheckPhoneUrl){
            //app.sendCount = count;
            app.successUrl = SuccessUrl;
            app.errorUrl   = ErrorUrl;
            app.checkPhoneUrl = CheckPhoneUrl;
            $(".btn-send-code").attr("disabled",true);
        }
    }
})
