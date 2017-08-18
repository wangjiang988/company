define(function (require, exports, module) {
    var app_setUrl,app_successUrl;
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/left") 

    var app = new Vue({
        el: '.user-content',
        data: {
            sendUrl:"",
            successUrl:""
        },
        mounted:function(){
            
        },
        methods:{
            sendMail:function(){
                $.ajax({
                    type: "post",
                    url: app_setUrl,
                    data: {
                        email:$("#email").val(),
                        _token:$("input[name='_token']").val()
                    },
                    dataType: "json",
                    success: function(data){
                       if(data.success ==1){
                           window.location.href = app_successUrl;
                       }
                    },
                    error: function(data){

                    }    
                })   
            }
        }

    })
  
    module.exports = {
       init:function(_sendUrl,_successUrl){
           app_setUrl = _sendUrl;
           app_successUrl = _successUrl;
       }
    }
})
