define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/left") 
    require("/webhtml/common/js/vendor/hc.popup.jquery")

    var app = new Vue({
        el: '.user-content',
        data: {
            code:"",
            codeError:!1,
            mail:"",
            sendUrl:"",
            successUrl:"",
            checkEmailUrl:""
        },
        mounted:function(){
            
        },
        methods:{
            sendMail:function(){
                $.ajax({
                    type: "post",
                    url: app.sendUrl,
                    data: {
                        email:app.mail,
                        _token:$("input[name='_token']").val()
                    },
                    dataType: "json",
                    success: function(data){
                        if (data.success == 0) {
                            $("#sendErrorWin").hcPopup({'width':'500'})
                        }
                    },
                    error: function(data){
                        $("#sendErrorWin").hcPopup({'width':'500'})
                    }    
                })
            },
            next:function(){
                if (this.code === "") {
                    app.codeError = !0
                    return
                }
                $.ajax({
                    type: "post",
                    url: app.checkEmailUrl,
                    data: {
                        code:app.code,
                        _token:$("input[name='_token']").val()
                    },
                    dataType: "json",
                    success: function(data){
                        if (data.success == 0) app.codeError = !0
                        else window.location.href = app.successUrl;
                    },
                    error: function(data){
                        app.codeError = !0
                    }    
                })   
            },
            initCode:function(){
                this.codeError = !1
            },
            sure:function(){
                $("#sendErrorWin").hide()
            },
        }

    }) 
     
  
    module.exports = {
        initEmail:function(mail,_sendUrl,_successUrl,_checkEmailUrl){
            app.mail = mail;
            app.sendUrl = _sendUrl;
            app.successUrl = _successUrl;
            app.checkEmailUrl = _checkEmailUrl;
        }
    }
})
