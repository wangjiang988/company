define(function (require, exports, module) {

    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/vue.common.mixin")
    require("module/common/hc.popup.jquery")

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
         checklist:[]
        },
        mounted:function(){
            setTimeout(function(){
                console.log(app.checklist.join(","))
            },1000)
        }
        ,
        methods:{

            send:function () {
                $("#sendWin").hcPopup({'width':'400'})
            },
            stopOrder:function () {
                $("#stopWin").hcPopup({'width':'400'})
            },
            doStopOrder:function () {
                $("#stopOrder").submit();
                $("#stopWin").hide()
            },
            doSend:function () {
                $("#step-c3").submit();
                $("#sendWin").hide()
            },
            selectCheck:function (index) {
               /* console.log(index)
                console.log(app.checklist.join(","))
                console.log(this.checklist[index])*/
                app.checklist[index] = app.checklist[index] == true ? !1 : !0
            },
            isInArray:function (val) {
                /*console.log(val)
                console.log(app.checklist.join(","))
                return $.inArray(val, app.checklist)*/
            },
        },
        watch:{



        }

    })

    module.exports = {

        initList:function(array){
            app.checklist.push(array)
            console.log(app.checklist.join(","))
        }
    }
})


