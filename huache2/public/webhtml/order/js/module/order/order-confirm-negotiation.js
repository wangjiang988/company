define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/vue.common.mixin");
    require("/webhtml/common/js/module/head")
    require("/webhtml/user/js/module/user/user-code-count-down-component");
    require("/webhtml/common/js/vendor/hc.popup.jquery");

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
            doAgreeUrl:"",
            checkMsgCodeUrl:"",
            successUrl:"",
            isEmtpy:!1,
            isError:!1,
            isCodeError:!1,
            code:"",
            simpleCountDown:5,

        },
        mounted:function(){

        }
        ,
        methods:{
            agree:function(){
                $("#phoneValite").hcPopup({'width':'450'});
            },
            checkCode:function(){
                var _flag = !1;
                var _phone = $("input[name='phone']").val();
                $.ajax({
                    url: app.checkMsgCodeUrl,
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
                        _flag = !1
                    }
                })
                return _flag
            },
            doAgree:function(url,id){
                if (this.code == "") {
                    this.isEmtpy = !0
                    this.isError = !1
                    setTimeout(function(){
                        app.isEmtpy = !1
                    },3000)
                } else{
                    this.isEmtpy     = !1;
                    this.isCodeError = !1;
                    this.isError     = !1;

                    $.ajax({
                        url: url,
                        type: "post",
                        data: {
                            _token: $('input[name=_token]').val(),
                            consult_id:id,
                            code:this.code
                        },
                        dataType: "json",
                        beforeSend: function() {

                        },
                        success: function(data) {
                            //验证码错误
                            if (data.code == 0) {
                                app.isError = !0
                                setTimeout(function(){
                                    app.isError = !1
                                },3000)
                            }
                            //成功
                            else if (data.code == 1) {
                                window.location.reload()
                            }
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

            }

        },
        watch:{

        }

    })

    module.exports = {

        initUrl:function(doAgreeUrl,checkMsgCodeUrl,successUrl){
            app.doAgreeUrl         = doAgreeUrl
            app.checkMsgCodeUrl    = checkMsgCodeUrl
            app.successUrl         = successUrl
        }
    }
})
