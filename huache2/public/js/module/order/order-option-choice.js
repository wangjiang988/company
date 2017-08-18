define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/vue.common.mixin")
    require("/webhtml/common/js/vendor/hc.popup.jquery")
    require("/webhtml/common/js/vendor/jquery.form")
    require("/js/module/user/user-code-count-down-component")
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
            code:"",
            codeStatus:-1,
            orderList:[],
            error:!1,
            toggleArray:[{display:!0},{display:!0}],
            marginLeft:0,
            count:1,
            defCount:10
        },
        mounted:function(){
            this.setTotalPrice()
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
            },
            setValue:function(obj){
                var _this = $(obj)
                var _price = parseInt( _this.attr("data-price") )
                var _count = parseInt(_this.val())
                var _money = _price * _count
                _this.parents("td").next().text(this.formatMoney(_money,2,"￥"))
                this.setTotalPrice()
            }
            ,
            setTotalPrice:function(){
                var _tbl = $(".tbl-xzj")
                var _this = this
                $.each(_tbl,function(idx,tbl){
                    var tbl = $(tbl)
                    var _total = 0
                    $.each(tbl.find("tr").slice(1),function(index,item){
                        if (item.lastChild.tagName !== "TH") {
                            var _td = $(item).find("td:last").prev()
                            var _td_input = _td.find("input[readonly='readonly']")
                            _total += parseInt( _td_input.attr("data-price") ) * parseInt(_td_input.val())
                        }
                    })
                    tbl.next().find("input[type='hidden']").val(_total)//后台取值对象
                    tbl.next().find("label").text(_this.formatMoney(_total,2,"￥"))
                })
            }
            ,
            prev:function(event){
                var _this = event.target
                var _input = $(_this).next()
                var _val = parseInt(_input.val())
                var _min = 0
                _input.val(_val == _min ? _min : (_val - 1))
                this.setValue(_input)
            }
            ,
            next:function(event,max){
                var _max = max || 1 //可在页面传参 注：此件数选择不超过单车可装件数，也不超过可供件数。
                var _this = event.target
                var _input = $(_this).prev()
                var _val = parseInt(_input.val())
                _input.val(_val == _max ? _max : (_val + 1))
                this.setValue(_input)
            },
            send:function(){
                var _flag = !1
                $.each($(".tbl-xzj input[readonly='readonly']"),function(idx,input){
                    if (parseInt(input.value) != 0) {
                        _flag = !0
                        return false
                    }
                })
                if(_flag) {
                    $("#sendWin").hcPopup({'width':'680'})
                    app.orderList = []
                    $.each($(".tbl-xzj"),function(idx,item){
                        $.each($(item).find("tr").slice(1),function(index,tr){
                            var _input = $(tr).find("input[readonly='readonly']")
                            //console.log(_input[0],parseInt(_input[0].value),parseInt($(_input).attr("data-price")))
                            if (parseInt(_input[0].value) != 0) {
                                app.orderList.push({
                                    brand:idx == 0 ? "\u539f\u5382" : $(tr).find("td").eq(0).text(),
                                    name:idx == 0 ? $(tr).find("td").eq(0).text() : $(tr).find("td").eq(1).text(),
                                    count:_input[0].value,
                                    price:parseInt(_input[0].value) * parseInt($(_input).attr("data-price"))
                                })
                            }
                        })
                    })
                }
                else {
                    this.error = !0
                    setTimeout(function(){
                        app.error = !1
                    },10000)
                }

            },
            doSend:function(){
                if (this.code == "") {
                     this.codeStatus = 0
                     this.setTout()
                }
                else if(!this.checkNum(this.code)){
                    this.codeStatus = 2
                    this.setTout()
                }
                else {
                    var _this = this
                    $.ajax({
                         type: "post",
                         url:"/parts/checkcode",
                         data: {
                            code:app.code,
                            _token:$("input[name=_token]").val()
                         },
                         //async:false,  //设置为同步
                         dataType: "json",
                         success: function(data){
                             //0:验证码错误  1：验证成功！！！
                             //
                             //console.log(data,data.code,data.code == 0)
                             if (data.code == 1) {
                                _this.codeStatus = -1
                                $("#successWin").hcPopup({width:"420px"})
                                //_this.subForm()
                             }
                             else {
                                _this.codeStatus = 2
                                _this.setTout()
                             }
                         }
                    })
                }

            },
            subForm:function(){
                $("form").submit()
            },
            setTout:function(){
                 setTimeout(function(){
                    app.codeStatus = -1
                 },6000)
            },
            getCode:function(code,isError){
                this.code = code
            },
            checkNum:function(num){
                if (num.length < 6 || isNaN(num)) {
                    return !1
                }
                return !0
            },
            chance :function(){
                $(".form-control.pull-left").val("").removeClass('error-bg')
                this.codeStatus = -1
            },
            reload:function(){
                window.location.reload()
            },
            toggle:function(index){
                this.toggleArray[index].display = !this.toggleArray[index].display
                this.setTotalPrice()
            },
            marqueeImg:function(direction){
                var _width          = 224
                var _length         = $(".xzj-marquee img").length
                var _diff           = _length - this.defCount
                var _defMaxClick    = 6
                if (this.count == _defMaxClick + _diff  ) {
                    this.marginLeft = 0
                    this.count = 1
                }else{
                    if (direction == 'right') {
                        this.marginLeft = -(++this.count * _width)
                    }else if (direction == 'left') {
                        if (this.count == 1) return
                        this.marginLeft = -(--this.count * _width)
                    }
                }
            },
        },
        watch:{
            '':function(n,o){

            }
        }

    })

    module.exports = {

    }
})