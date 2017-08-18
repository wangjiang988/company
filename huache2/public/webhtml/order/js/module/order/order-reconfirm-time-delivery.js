define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/vue.common.mixin")
    require("/webhtml/common/js/vendor/time.jquery")
    require("/webhtml/common/js/vendor/hc.popup.jquery")
    require("/webhtml/common/js/vendor/jquery.form")
    require("/webhtml/user/js/module/user/user-code-count-down-component")
    require("/webhtml/custom/js/module/custom/dropdown-components")
    require("/webhtml/custom/js/module/custom/dropdown-date-components")

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
            countDownNum:5,
            countDownObj:{},
        },
        mounted:function(){

        },
        methods:{
            simpleCountDown:function(call){
                var _time = setInterval(function () {
                    if (app.countDownNum <=0) {
                       clearInterval(app.countDownObj)
                       if (call) {call()}
                    }else
                      app.countDownNum--
                },1000)
                this.countDownObj = _time
            },
            agree:function(){
                $("#tipWin").hcPopup({'width':'420'})
            },
            doSend:function(){
                this.subForm()
            },
            reload:function(){
                window.location.reload()
            },
            subForm:function(){
                var _form  = $("form")
                var _this  = this
                var options = {
                  type: 'post' ,
                  beforeSend: function(data) {

                  },
                  success: function(data) {
                       if (data.code == 0 ) {
                           $("#errorWin").hcPopup({'width':'420'})
                       } else if (data.code == 1 ){
                           $("#successWin").hcPopup({'width':'420'})
                       }
                       app.simpleCountDown(function(){
                          app.reload()
                       })
                   },
                   error:function(){
                        $("#errorWin").hcPopup({'width':'420'})
                        app.simpleCountDown(function(){
                          app.reload()
                        })
                   }
                 }
                 _form.ajaxForm(options).ajaxSubmit(options)
            }

        },
        watch:{


        }

    })

    module.exports = {
        initTimeList:function(array){
           app.timeList = array
        },
        initStartEndTime:function(startTime,endTime){
            app.startTime = startTime
            app.endTime = endTime
        }
    }
})
