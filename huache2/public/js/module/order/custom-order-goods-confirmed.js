define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/vue.common.mixin")
    require("/webhtml/common/js/vendor/time.jquery")
    require("/webhtml/common/js/vendor/hc.popup.jquery")

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
            goodsList:[],
            isLoading:!1,
            checkboxModel:[],
            checked:!0
        },
        mounted:function(){

        }
        ,
        methods:{
            send:function(){
                $("#sendWin").hcPopup({'width':'450'})
            },
            doSend:function(event){
                $("#form").submit()
                $("#sendWin").hide()

            },
            init: function (startTime,endTime,call) {
                $(".countdown").CountDown({
                      startTime:startTime,
                      endTime :endTime,
                      timekeeping:'countdown',
                      callback:function(){
                          if (call) {call()}
                      }
                })
            }
        }


    })

    module.exports = {
        init:function(startTime,endTime,call){
           app.init(startTime,endTime,call)
        }
    }
})
