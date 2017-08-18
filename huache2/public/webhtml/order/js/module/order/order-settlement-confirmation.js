define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/vue.common.mixin")
    require("/webhtml/common/js/module/head")
    require("/webhtml/custom/js/module/custom/dropdown-components")
    require("/webhtml/user/js/module/user/user-code-count-down-component")
    require("/webhtml/common/js/vendor/hc.popup.jquery")

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
            doWithdrawalURl:"",
            isEmtpy:!1,
            isError:!1,
            isCodeError:!1,
            code:""
        },
        mounted:function(){

        }
        ,
        methods:{
            send:function(){
                $("#phoneValite").hcPopup({'width':'450'})
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
                        _flag = !1;
                    }
                })
                return _flag
            },
            doSend:function(url){
                if (this.code == "") {
                    this.isEmtpy = !0
                    this.isError = !1
                    setTimeout(function(){
                        app.isEmtpy = !1
                    },3000)
                }
                else{
                    this.isEmtpy     = !1
                    this.isCodeError = !1
                    this.isError     = !1
                var _flag = !1;
                var _phone = $("input[name='phone']").val();
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    contentType: "application/json;charset=utf-8",
                    data: JSON.stringify({  //生成JSON 文本的字符串
                        "code": this.code,
                        "_token": this.$refs.phonecode.token,
                    })
                })
                .done(function(data) {
                   if (data.code == 1) {

                    window.location.reload();

                }else {
                     app.isError = !0
                        setTimeout(function(){
                            app.isError = !1
                        },3000)
                 }
                })
                return _flag
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

        },
        watch:{

        }

    })

    module.exports = {

    }
})
