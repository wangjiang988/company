define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/vue.common.mixin")
    require("/webhtml/common/js/vendor/hc.popup.jquery")
    require("/webhtml/common/js/vendor/jquery.form")
    require("/webhtml/user/js/module/user/user-code-count-down-component")
    /*
    *
    *   开发环境请删除error方法 或者 注释掉 error方法
    *
    *
    */
    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
           isHasNegotiation:!1,
           countDownNum:5,
           url:'',
           countDownObj:{},

        },
        mounted:function(){
            this.sumTotal()
        }
        ,
        methods:{
            sumTotal:function(){
                var _sum = 0.0
                $(".price").each(function(index,item){
                    var _price = parseFloat($(item).attr("data-price"))
                    _sum+=_price
                })
                $("#sum-total").text(this.formatMoney(_sum,2,"￥"))
            },
            simpleCountDown:function(call){
                var _time = setInterval(function () {
                    if (app.countDownNum <=0) {
                        app.sure()
                        if (call) call()
                    }
                    app.countDownNum--
                },1000)
                this.countDownObj = _time
            },
            dontWant:function(){
                if (this.isHasNegotiation) {
                    $("#errorWin").hcPopup({'width':'420'})
                    this.simpleCountDown()
                }else{
                    window.location.href = this.url
                }
            },
            sure:function(){
                clearInterval(app.countDownObj)
                location.reload()
            }
        },
        watch:{
            '':function(n,o){

            }
        }

    })

    module.exports = {
        initNegotiation:function(flag){
            app.isHasNegotiation = flag
        },
        url:function(url)
        {
            app.url = url
        }
    }
})