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
            },
            prev:function(event){
                var _this = event.target
                var _input = $(_this).next()
                var _val = parseInt(_input.val())
                var _oldval = parseInt(_input.attr("def-value"))
                var _min = 0
                _input.val(_val == _min ? _min : (_val - 1))
                if (parseInt(_input.val()) != _oldval) {_input.addClass('juhuang-bg')}
                else _input.removeClass('juhuang-bg')
                this.setValue(_input)
            },
            next:function(event,max){
                var _max = max || 1 //可在页面传参 注：此件数选择不超过单车可装件数，也不超过可供件数。
                var _this = event.target
                var _input = $(_this).prev()
                var _oldval = parseInt(_input.attr("def-value"))
                var _val = parseInt(_input.val())
                _input.val(_val == _max ? _max : (_val + 1))
                if (parseInt(_input.val()) != _oldval) {_input.addClass('juhuang-bg')}
                else _input.removeClass('juhuang-bg')
                this.setValue(_input)
            },
            send:function(){
                var _flag = !1
                $.each($(".tbl-xzj input[readonly='readonly']"),function(idx,input){
                    var _input = input
                    var _count = parseInt($(_input).val())
                    var _oldcount = $(_input).attr("def-value")
                    if (_count != _oldcount) {
                        _flag = !0
                        return false
                    }
                })
                if(_flag) {
                    $("#sendWin").hcPopup({'width':'620'})
                    app.orderList = []
                    $.each($(".tbl-xzj"),function(idx,item){
                        $.each($(item).find("tr").slice(1),function(index,tr){
                            var _input = $(tr).find("input[readonly='readonly']")
                            //console.log(_input[0],parseInt(_input[0].value),parseInt($(_input).attr("data-price")))
                            var _count = parseInt(_input[0].value)
                            var _oldcount = $(_input).attr("def-value")
                            if (_count != _oldcount) {
                                app.orderList.push({
                                    brand:$(tr).find("td").eq(0).text(),
                                    name:$(tr).find("td").eq(1).text(),
                                    count:_count,
                                    oldcount:_oldcount
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
                         beforeSend: function(data){

                         },
                         success: function(data){
                             //0:验证码错误 2：验证码超时 1：验证成功！！！
                             if (data.code == 1) {   //临时性的改掉
                                _this.codeStatus = -1
                                _this.subForm()
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
                // var _form  = $("form")
                // var _this  = this
                // var options = {
                //     type: 'post' ,
                //     beforeSend: function(data) {

                //     },
                //     success: function(data) {
                //        //0:已经被订购了 1：订购成功
                //        if (data.success == 0) $("#errorWin").hcPopup({'width':'420'})
                //        else if (data.success == 1) $("#successWin").hcPopup({'width':'420'})
                //     },
                //     error:function(){
                //         $("#errorWin").hcPopup({'width':'420'})
                //     }
                // }
                // _form.ajaxForm(options).ajaxSubmit(options)

            },
            setTout:function(){
                 setTimeout(function(){
                    app.codeStatus = -1
                 },3000)
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
            }
        },
        watch:{
            '':function(n,o){

            }
        }

    })

    module.exports = {

    }
})