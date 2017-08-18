
define(function (require, exports, module) {

   require("/webhtml/custom/js/module/custom/dropdown-components")

   var app = new Vue({
        el: '.box-wrapper',
        data: {
            addValue:0.00,//所有增量值
            minusValue:0.00,//所有减量值
            flatValue:0.00,//所有的平量值
            FallingLandPrices:"", //落地价
            PrepareTotalPrices :"", //总预备资金
            yongtuList:[
                {id:1,name:"\u975e\u8425\u4e1a\u4e2a\u4eba\u5ba2\u8f66\uff08\u79c1\u5bb6\u8f66\uff09"},
                {id:2,name:"\u975e\u8425\u4e1a\u4f01\u4e1a\u5ba2\u8f66\uff08\u516c\u53f8\u81ea\u5907\u8f66\uff09"},
                {id:3,name:"\u5176\u4ed6"}
            ],
            isQita:!1

        },
        mounted:function(){
             this.init()
        },
        methods:{
            getYongTu:function(id,val){
                if (id == 3) {
                   this.isQita = !0
                   $(".empty").val("")
                }else{
                   this.isQita = !1
                   $(".empty").each(function(index,item){
                        $(item).val($(item).attr("data-value"))
                   })
                 //$(".empty").val($(".empty").attr('data-value'));
                }

            },
            getNumValue:function(val){
                return val.replace(/[,]/ig,"")
            }
            ,
            minusLastSymbol:function(val){
                return val.slice(0, val.length - 1)
            }
            ,
            toThousands:function(num) {
                var num = (num || 0).toString(), result = '',truncation = '',truncationindex = ''
                truncationindex = num.indexOf(".")
                if (truncationindex > 0) {
                    truncation = num.slice(truncationindex)
                    num = num.slice(0, truncationindex)
                }
                while (num.length > 3) {
                    result = ',' + num.slice(-3) + result
                    num = num.slice(0, num.length - 3)
                }
                if (num) { result = num + result }
                result = result + (truncation == '' ? ".00" : truncation)

                return result == "0.00" ? "" : result
            }
            ,
            subsidy:function(){

            }
            ,
            setValue:function(){
                $(".add-total").text(this.toThousands(this.addValue ))
                var _minusvalue = this.toThousands(this.minusValue)
                $(".minus-total").text(_minusvalue == "" ? "0.00" : _minusvalue )
                $(".fixed-total").text(this.toThousands(this.flatValue) )
                //增项合计—减项合计
                this.FallingLandPrices  = this.toThousands((parseFloat(this.addValue) - parseFloat(this.minusValue)).toFixed(2))
                //增项合计+平项合计
                this.PrepareTotalPrices = this.toThousands((parseFloat(this.addValue) + parseFloat(this.flatValue)).toFixed(2))
                $(".calc-total").text(this.FallingLandPrices)
                $(".calc-prev-total").text(this.PrepareTotalPrices)
            }
            ,
            statistics:function(){

                 this.statisticsAdd()
                 this.statisticsMinus()
                 this.statisticsFlat()
                 this.setValue()
            }
            ,
            statisticsAdd:function(){
                var _addexpression = ''
                $(".fixed-value").each(function(){
                    _addexpression += $(this).text() + "+"
                })
                $(".dym-value").each(function(){
                    if ($.trim($(this).val())!="")
                        _addexpression += $(this).val() + "+"
                })
                var _addValue = this.minusLastSymbol(_addexpression) //去除逗号的表达式
                this.addValue   = eval(this.getNumValue(_addValue)).toFixed(2)
            }
            ,
            statisticsMinus:function(){
                var _minusexpression = ''
                $(".minus-value").each(function(){
                    if ($.trim($(this).text()) !="") {
                        _minusexpression += $(this).text() + "+"
                    }
                })
                $(".dym-subsidy").each(function(){
                    if ($.trim($(this).val())!="")
                        _minusexpression += $(this).val() + "+"
                })
                var _sum = this.getNumValue(this.minusLastSymbol(_minusexpression))
                this.minusValue = _sum == "" ? "0.00" : eval(_sum).toFixed(2)
            }
            ,
            statisticsFlat:function(){
                var _flatexpression = ''
                $(".flat-value").each(function(){
                    if (!$(this).hasClass('none')) {
                        _flatexpression += $(this).text() + "+"
                    }
                })
                this.flatValue = eval(this.getNumValue(this.minusLastSymbol(_flatexpression))).toFixed(2)
            }
            ,
            init: function () {
               //global set
               /*$(".tbl input[def-select='1']").attr("disabled","disabled")
               $(".tbl input[def-select='0']").click(function(){
                    var _this = $(this)
                    var _tr = _this.parents("tr").eq(0)
                    if (_this.prop("checked")) {
                        _tr.find(".none").show()

                    }else{
                        _tr.find(".none").hide().eq(1).val("0.00")
                    }
                    this.statistics()
               })*/
               this.statistics()

               $(".tbl input[type='text']").focus(function(event) {
                    $(this).val(app.getNumValue($(this).val()))
               }).blur(function(event) {
                   $(this).val(app.toThousands($(this).val()))
               }).keyup(function(event) {
                   $(this).val(
                        $(this).val().replace(/[^0-9,.?]/g,"")
                   )
               }).change(function(event) {
                     app.statistics()
               })


            }
        }

    })

})