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
           sendSmsUrl:"",
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
                 url: app.sendSmsUrl,
                 data: {
                    phone:$("input[name='phone']").val(),
                    code:1,
                    template_code:'78590067',
                    max:3
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

                 },
                 //开发环境删除error方法
                 error: function(data){
                    $("#tipWin").hide()
                    $("#successWin").hcPopup({'width':'420'})
                    app.simpleCountDown(function(){
                       $("#successWin").hide()
                    })
                    app.sendCount = 2
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
          }

        }
        ,
        watch:{

            '':function(){

            }

        }

    })

    module.exports = {

        initSendCount:function(count){
            app.sendCount = count
        },
        initSetUrl:function(sendSmsUrl){
            app.sendSmsUrl  = sendSmsUrl;
        }
    }
})


