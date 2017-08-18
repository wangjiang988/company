define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/vue.common.mixin") 
    require("/webhtml/common/js/vendor/time.jquery")
    require("/webhtml/common/js/vendor/hc.popup.jquery")
    require("/webhtml/custom/js/module/custom/dropdown-components")
    require("/webhtml/user/js/module/user/user-code-count-down-component")
    require("/webhtml/common/js/vendor/jquery.form")
    
    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
           countDownNum:5, 
           countDownObj:{},
           isSelectVouchers:!1,
           selectTxt:"",
           vouchersObj:{},
           vouchersList:[],
           code:"",
           codeStatus:-1,
           successStatus:-1
        },
        mounted:function(){
            $(".pay-form .pull-left:eq(1)").text("\u77ed\u4fe1\u9a8c\u8bc1\u7801\uff1a")
        }
        ,
        methods:{
          surePay:function(){
              if(!this.checkNum(this.code)){
                  this.codeStatus = 2
                  this.setTout()
              }else{
                  $.ajax({
                       type: "get",
                       url:"/member/checkSms/",
                       data: {
                           phone:app.$refs.phonecode.phone,
                           template_code:app.$refs.phonecode.sendtype,
                           code:app.code
                       },
                       dataType: "json",
                       beforeSend: function(data){ 
                           app.codeStatus = -1
                       },
                       success: function(data){
                           //0:验证失败  1：验证成功！！！
                           if (data.success == 0)
                              app.codeStatus = 2 
                           else if (data.success == 1)
                               $("#payDeposit").submit()
                       },
                  })    
              }
          },
          checkNum:function(num){
              if (num.length < 6 || isNaN(num)) {
                  return !1
              }
              return !0
          },
          setTout:function(){
             setTimeout(function(){
                app.codeStatus = -1
             },3000)
          },
          getCode:function(code,isError){
              this.code = code 
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
          reload :function(){
              $("#successWin").hide()
              window.location.reload()
          },
          simpleCountDown:function(call){
              var _time = setInterval(function () {
                  if (app.countDownNum <=0) { 
                     clearInterval(app.countDownObj)
                     app.reload()
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
        init:function(startTime,endTime,call){
            app.init(startTime,endTime,call)
        } 
         
    }
})


