define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    //开发环境务必删除AJAX的error方法
    require("/webhtml/common/js/module/vue.common.mixin")
    require("/webhtml/common/js/vendor/hc.popup.jquery")
    require("./user-code-count-down-component")

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
            tips:[{isShow:!1},{isShow:!1}],
            isEmtpy:!1,
            isError:!1,
            isCodeError:!1,
            code:"",
            simpleCountDown:5,
            isAuthentication :!1,
            hasNoAuthentication :!0,
            isBankAuth:!1,//是否银行认证
            doWithdrawalURl:"",//提交提现申请
            addIdCartUrl:"",//添加实名认证
            addBankUrl:"",//添加银行卡
            successUrl:"",
            checkMsgCode:""//验证手机短信
        },
        mounted:function(){
            this.checkIsAuthentication()
        }
        ,
        methods:{
            //检查是否实名认证
            checkIsAuthentication:function(){
                $.ajax({
                    url: _checkIdCartUrl,
                    type: "get",
                    data: {},
                    dataType: "json",
                    beforeSend: function() {

                    },
                    success: function(data) {
                         //返回值 true or false
                         //true为已经实名认证
                         app.isAuthentication = data.success
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                         app.isAuthentication = !1
                    }
                })

            },
            countDown:function(){
                var app = this
                var _time = setInterval(function() {
                    if (app.simpleCountDown == 0) {
                        window.location.href = app.addIdCartUrl; //"K.1.26实名认证文件修改新增.html"
                        clearInterval(_time)
                    } else {
                        app.simpleCountDown--
                    }
                },1000)
            },
            authentication:function(){
                if (this.isAuthentication) {
                    window.location.href = app.addBankUrl;//"K.1.30银行账户文件新增.html"
                }else{
                    $("#authentication").hcPopup({'width':'420'})
                    app.countDown()
                }
            },
            doWithdrawal:function(){
                if (this.code == "") {
                    this.isEmtpy = !0
                    setTimeout(function(){
                        app.isEmtpy = !1
                    },3000)
                }
                else if(!app.checkCode()){
                    this.isEmtpy = !0
                    setTimeout(function(){
                        app.isEmtpy = !1
                    },3000)
                }
                else{
                    this.isEmtpy     = !1
                    this.isCodeError = !1
                    this.isError     = !1

                    $.ajax({
                        url: app.doWithdrawalURl,
                        type: "post",
                        data: $('#withdrawal_application').serialize(),
                        dataType: "json",
                        beforeSend: function() {

                        },
                        success: function(data) {
                            //验证码错误
                            if (data.success == 0) {
                                app.isError = !0
                                setTimeout(function(){
                                    app.isError = !1
                                },3000)
                            }
                            //验证码失效
                            else if (data.success == 2) {
                                app.isCodeError = !0
                                setTimeout(function(){
                                    app.isCodeError = !1
                                },3000)
                            }
                            //成功
                            else if (data.success == 1) {
                                window.location.href = app.successUrl;//"k.2.8页面"
                            }
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            app.isCodeError = !0
                            setTimeout(function(){
                                app.isCodeError = !1
                            },3000)
                        }
                    })
                }
            },
            getCode:function(code){
                this.code = code
                if (code == "") {
                    this.isEmtpy = !0
                    setTimeout(function(){
                        app.isEmtpy = !1
                    },3000)
                }

            },
            checkCode:function(){
                var _flag = !1;
                var _phone = $("input[name='phone']").val();
                $.ajax({
                    url: app.checkMsgCode,
                    type: "get",
                    data: {'phone':_phone,'code':this.code,'type':'status'},
                    dataType: "json",
                    async:false,
                    beforeSend: function() {

                    },
                    success: function(data) {
                        _flag = data.success
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {

                    }
                })
                return _flag
            },
            withdrawal:function(){
                this.checkBank()
               if(!this.isAuthentication){
                    this.hasNoAuthentication = !1
                    setTimeout(function(){
                        app.hasNoAuthentication = !0
                    },3000)
                }
               else{
                  //this.isBankAuth = 1;
                  if(!this.isBankAuth){
                      $("#phoneValiteConfirm").hcPopup({'width':'420'})
                  }else{
                      app.doShowPhoneValite();
                  }
               }
            },
            checkBank:function(){
                var _flag = !0
                $(".isBank").each(function(index,item){
                    var _isbank = $(item).attr("data-valite")
                    if(_isbank == "0"){
                        app.isBankAuth = 0;
                        _flag = !1
                        return false;
                    }
                })
                if (_flag) app.isBankAuth = 1;

            },
            displayHide:function(){
                $("#phoneValiteConfirm").hide()
            },
            doShowPhoneValite:function(){
                $("#phoneValite").hcPopup({'width':'420'})
                $(".fs14 .pull-left").eq(0).text("验 证 码：")
            },
            tip:function(index){
                if (index == 0) {
                    this.tips[0].isShow = !this.tips[0].isShow
                    this.tips[1].isShow = !1
                }
                else if (index == 1) {
                    this.tips[1].isShow = !this.tips[1].isShow
                    this.tips[0].isShow = !1
                }
            }
        },
        watch:{

        }

    })

    module.exports = {
         initUrl:function(doWithdrawalURl,addIdCartUrl,addBankUrl,successUrl,checkMsgCode){
             app.doWithdrawalURl= doWithdrawalURl;//提交提现申请
             app.addIdCartUrl   = addIdCartUrl;//添加实名认证
             app.addBankUrl     = addBankUrl;//添加银行卡
             app.successUrl     = successUrl;
             app.checkMsgCode   = checkMsgCode;
         }
    }
})
