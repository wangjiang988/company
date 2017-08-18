define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/vue.common.mixin") 
    require("/webhtml/common/js/vendor/time.jquery")
    require("/webhtml/common/js/vendor/hc.popup.jquery")
    require("/webhtml/custom/js/module/custom/dropdown-components")
    
    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
           countDownNum:5, 
           countDownObj:{},
           successStatus:-1,
           isSelectVouchers:!1,
           selectTxt:"",
           vouchersObj:{},
           vouchersList:[],
           voucher_id:0,
        },
        mounted:function(){
            this.toolTip()
        }
        ,
        methods:{
          sureVouchers:function(){
             this.isSelectVouchers  = !0
             var _split             = this.selectTxt.split(" ")
             this.vouchersObj.price = _split[0]
             this.vouchersObj.type  = _split[1]
             this.vouchersObj.time  = _split[2]
          },
          getVouchers:function(obj){
             this.selectTxt         = obj.array[0] + " " +  obj.array[1] + " " +  obj.array[2]
             this.voucher_id = obj.id
          },
          vouchersPay:function(){
              $("#payDeposit").submit()
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
                            startTime:startTime,
                            endTime :endTime,
                            timekeeping:'timeout',
                            callback:function(){
                                //if (call) {call()} 
                            }
                        }) 
                      }
                      
                  }
            }) 
          }
          ,
          toolTip:function(){
             $(".tip").hover(function(){
                $(this).parents(".psr-wapper").find(".tooltip").addClass("in")
             },function(){
                $(this).parents(".psr-wapper").find(".tooltip").removeClass("in")
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
          chanceVouchers:function(){
              $("#vouchersWin").hcPopup({'width':'450'})
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
        initVouchersList:function(array) {
            app.vouchersList = array
            //console.table(array)
        },
        initVouchersObj:function(obj) {
            app.vouchersObj = obj
            app.$refs.vouchers.valId = obj.id
            app.voucher_id = obj.id
            app.$refs.vouchers.def = app.vouchersList.length == 0 ? "" : app.vouchersList[0].name //obj.price + " 券编码尾号：" + obj.sn
            //console.log(obj)
        },
         
    }
})


