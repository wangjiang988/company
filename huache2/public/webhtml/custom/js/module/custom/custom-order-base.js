define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/vue.common.mixin")
    require("/webhtml/common/js/vendor/time.jquery")
    require("/webhtml/common/js/vendor/hc.popup.jquery")
    require("/webhtml/common/js/vendor/jquery.form")
    require("/webhtml/user/js/module/user/user-code-count-down-component")
    require("./dropdown-components")
    require("./dropdown-date-components")

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {

        },
        mounted:function(){

        }
        ,
        methods:{

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
        },
        watch:{


        }

    })

    module.exports = {
        init:function(startTime,endTime,call){
           app.init(startTime,endTime,call)
        },
        format:function(price){
            return app.formatMoney(price,2,"￥")
        }
    }
})
