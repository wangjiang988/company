define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/vue.common.mixin") 
    require("/webhtml/common/js/vendor/time.jquery")
    require("/webhtml/common/js/vendor/hc.popup.jquery")

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
           isSend:!1,
           countDownNum:5, 
           countDownObj:{},
           sendCount:0
        },
        mounted:function(){

        }
        ,
        methods:{
          
          sendCode:function(){
              if (this.sendCount >= 3) {
                 $("#endWin").hcPopup({'width':'420'})
              }else{
                if (this.isSend) $("#errorWin").hcPopup({'width':'420'})
                else $("#tipWin").hcPopup({'width':'420'})
                this.isSend = !0
                setTimeout(function(){
                  app.isSend = !1
                },60*2*1000)
              }
          },
          doSendCode:function(){

              $.ajax({
                 type: "get",
                 url:$("#sendcode").attr('sendurl'),
                 data: {
                    phone:$("#sendcode").attr('phone'),
                    template_code:$("#sendcode").attr('template_code'),
                 },
                 dataType: "json",
                 beforeSend: function(data){ 
                     
                 },
                 success: function(data){
                    $("#tipWin").hide()
                    //验证码间隔在两分钟之内
                    if (data.success == 0) {
                        $("#errorWin").hcPopup({'width':'420'})
                    }
                    else if (data.success == 1) {
                        $("#successWin").hcPopup({'width':'420'})
                        app.simpleCountDown(function(){
                           $("#successWin").hide()
                        })
                        //返回已经发送几次了
                        app.sendCount = data.count
                    }
                 }
              })
          },
          simpleCountDown:function(call){
              var _time = setInterval(function () {
                  if (app.countDownNum <=0) { 
                     clearInterval(app.countDownObj)
                     if (call) call()  
                  }else
                    app.countDownNum--
              },1000)
              this.countDownObj = _time
          },
          init: function (startTime,endTime,call) {
              $(".countdown").CountDown({
                    startTime:startTime,
                    endTime :endTime,
                    timekeeping:'countdown',
                    callback:function(){
                        if (call) {
                          call() 
                          $(".countdown").hide()
                          $(".timeout-text").removeClass("hide")
                          $(".timeout").removeClass("hide").CountDown({
                              startTime:endTime,
                              endTime :endTime,
                              timekeeping:'timeout',
                              callback:function(){
                                  //if (call) {call()} 
                              }
                          }) 
                        }
                        
                    }
              }) 
          },
          
          
      
        }
        ,
        watch:{
            
            '':function(){
                 
            }  
            
        }

    })  
  
    module.exports = {
        init:function(startTime,endTime,call){
            app.init(startTime,endTime,call)
        },
        initSendCount:function(count){
            app.sendCount = count
        }  
         
    }
})


