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
           successStatus:-1,
           payMethod:0,
           price:null,
           errorStatus:-1,
           maxPrice:null
        },
        mounted:function(){
            $(".pay-form .pull-left:eq(1)").text("\u77ed\u4fe1\u9a8c\u8bc1\u7801\uff1a")
        }
        ,
        methods:{
          subPayForm: function () {
              if (!this.price) {
                 this.errorStatus = 0
              }
              else if (isNaN(this.price)) {
                 this.errorStatus = 2
              }
              else if (this.price > this.maxPrice) {
                 this.errorStatus = 3
              }
              else{
                $("#payForm").submit()
              } 
              
          },
          initErrorStatus:function(){
             this.errorStatus = -1
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
          },
          selectPayMethod: function (id,event) {
              var _this = event.target.tagName == "LI" ? $(event.target) : $(event.target).parent()
              _this.find("span").addClass("selectpay").end().siblings('li').find("span").removeClass('selectpay')
              $("input[name='paymethod']").val(_this.index())
              $(".showerror").addClass('hide')
              this.payMethod = id
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
        },
        initMaxPrice :function(maxprice){
            app.maxPrice = maxprice
        },
         
    }
})


