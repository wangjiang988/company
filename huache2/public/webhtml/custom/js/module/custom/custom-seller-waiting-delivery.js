define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/vue.common.mixin")
    require("/webhtml/common/js/vendor/time.jquery")
    require("/webhtml/common/js/vendor/hc.popup.jquery")
    require("/webhtml/common/js/vendor/jquery.form")

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {

        },
        computed: {

        },
        mounted:function(){

        }
        ,
        methods:{
            send:function(){
                $("#tipWin").hcPopup({'width':'420'})
            },
            doSend:function(url){
                $.post(url, {_token: $("input[name='_token']").val()}, function(data, textStatus, xhr) {
                window.location.reload()
                });
                $("#tipWin").hide()
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
