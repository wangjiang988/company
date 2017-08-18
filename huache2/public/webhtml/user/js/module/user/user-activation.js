define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    //开发环境中请把每个$.ajax或ajaxForm中的error方法删除
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/left") 
    require("/webhtml/common/js/vendor/hc.popup.jquery")
    require("/webhtml/common/js/vendor/jquery.form") 
   
    var app = new Vue({
        el: '.content',
        data: {
            activationCode:"" ,
            countDownNum:5, 
            countDownObj:{},
            errorCount:0,
            sn:""
        },
        mounted:function(){
           //检测激活码激活错误次数
           this.getCodeErrorCount()
        }
        ,
        methods:{
            activation:function(){
                if (this.activationCode =="") {
                    $("#noneWin").hcPopup({'width':'420'})
                    this.simpleCountDown(function(){
                        window.location.reload()
                    })
                }else{
                    this.doActivation()
                }
            },
            getCodeErrorCount:function(){
                var _this = this
                $.ajax({
                     type: "POST",
                     url:"/user/getActivationErrorCount/",
                     data: {
                         token:$("input[name='token']").val()
                     },
                     dataType: "json",
                     success: function(data){
                        //返回已经输入错误几次了
                        _this.errorCount = data.count 
                     },
                     error: function(){
                        
                     },
                }) 
            },
            doActivation:function(){
                var _this = this
                if (_this.errorCount >= 5) {
                    $("#countErrorWin").hcPopup({'width':'420'})
                    this.simpleCountDown(function(){
                        window.location.href = "k.2.15我的福利.html"
                    })
                    return
                }
                $.ajax({
                     type: "POST",
                     url:"/user/activation/",
                     data: {
                         code:_this.activationCode,            
                         token:$("input[name='token']").val()
                     },
                     dataType: "json",
                     success: function(data){
                        //返回已经输入错误几次了
                        _this.errorCount = data.count 
                        //激活码错误
                        if (data.success == 0) {
                            $("#codeErrorWin").hcPopup({'width':'420'}) 
                        }
                        //激活成功
                        else if (data.success == 1) {
                            //激活的编号
                            _this.sn = data.code
                            $("#successWin").hcPopup({'width':'420'}) 
                        }
                        //激活码已经激活过
                        else if (data.success == 3) {
                            $("#usedWin").hcPopup({'width':'420'}) 
                            _this.simpleCountDown(function(){
                                window.location.href = "k.2.15我的福利.html"
                            })
                        }
                     },
                     error: function(){
                        $("#usedWin").hcPopup({'width':'420'}) 
                        _this.simpleCountDown(function(){
                            window.location.href = "k.2.15我的福利.html"
                        })
                         
                     },
                }) 
            },
            simpleCountDown:function(call){
                var _time = setInterval(function () {
                    if (app.countDownNum <=0) { 
                        app.sure()
                        if (call) call()
                    }
                    app.countDownNum--
                },1000)
                this.countDownObj = _time
            },
            sure:function(){
                clearInterval(app.countDownObj)
                $(".popupbox").hide()
                app.countDownNum = 5
            } 
        },
        watch:{
            
        }

    }) 
     
  
    module.exports = {
        
    }
})
