
define(function (require, exports, module) {
    
    var vm = avalon.define({
        $id: 'calc',
        addValue:0.00,//所有增量值
        minusValue:0.00,//所有减量值
        flatValue:0.00,//所有的平量值
        FallingLandPrices:"", //落地价
        PrepareTotalPrices :"", //总预备资金
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
            return result
        }
        ,
        subsidy:function(){

        }
        ,
        setValue:function(){
            $(".add-total").text(vm.toThousands(vm.addValue ))
            $(".minus-total").text(vm.toThousands(vm.minusValue) )
            $(".fixed-total").text(vm.toThousands(vm.flatValue) )
            //增项合计—减项合计
            vm.FallingLandPrices  = vm.toThousands((parseFloat(vm.addValue) - parseFloat(vm.minusValue)).toFixed(2))
            //增项合计+平项合计
            vm.PrepareTotalPrices = vm.toThousands((parseFloat(vm.addValue) + parseFloat(vm.flatValue)).toFixed(2))
            $(".calc-total").text(vm.FallingLandPrices)
            $(".calc-prev-total").text(vm.PrepareTotalPrices)
        }
        ,
        statistics:function(){

             vm.statisticsAdd()
             vm.statisticsMinus()
             vm.statisticsFlat()
             vm.setValue() 
        } 
        ,
        statisticsAdd:function(){
            var _addexpression = ''
            $(".fixed-value").each(function(){
                _addexpression += $(this).text() + "+"
            })
            $(".dym-value").each(function(){
                if ($.trim($(this).val())!="" && !isNaN($(this).val()))  
                    if(parseFloat(vm.getNumValue($(this).val())) != 0) 
                        _addexpression += $(this).val() + "+"
            })
            var _addValue = vm.minusLastSymbol(_addexpression) //去除逗号的表达式
            vm.addValue   = eval(vm.getNumValue(_addValue)).toFixed(2)
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
            vm.minusValue = eval(vm.getNumValue(vm.minusLastSymbol(_minusexpression))).toFixed(2)
        }
        ,
        statisticsFlat:function(){
            var _flatexpression = '' 
            $(".flat-value").each(function(){
                if (!$(this).hasClass('none')) {
                    _flatexpression += $(this).text() + "+"
                }
            })
            vm.flatValue = eval(vm.getNumValue(vm.minusLastSymbol(_flatexpression))).toFixed(2)
        }
        ,
        init: function () {
       
           vm.statistics()
           
           $(".tbl input[type='text']").focus(function(event) {
                $(this).val(vm.getNumValue($(this).val()))
           }).blur(function(event) {
                var reg = /(^[1-9]([0-9]+)?(\.[0-9]{1,2})?$)|(^(0){1}$)|(^[0-9]\.[0-9]([0-9])?$)/;
                if (!reg.test($(this).val())) $(this).val(0) 
                $(this).val(vm.toThousands($(this).val()))
           }).keyup(function(event) {
               $(this).val(
                    $(this).val().replace(/[^0-9,.?]/g,"")
               ) 
           }).change(function(event) { 
                var reg = /(^[1-9]([0-9]+)?(\.[0-9]{1,2})?$)|(^(0){1}$)|(^[0-9]\.[0-9]([0-9])?$)/;
                if (!reg.test($(this).val())) $(this).val(0) 
                vm.statistics()
           })

           
        }
        
    })
    vm.init()
})