
define(function (require,exports,module) {

    require("/webhtml/common/js/vendor/time.jquery")
    require("module/common/hc.popup.jquery")
    require("vendor/jquery.form")
    var vm = avalon.define({
        $id: 'custom',
        agree:!0,
        agreeList:["1"],
        isSend:!1,
        modifyOrStop:!0,
        noChange:!1,
        kmList:[],
        carTimeList:[],
        yearList:[],
        monthList:[],
        yearAndMonth:"",
        year:"",
        isDecimal:function(decimal){
            return /(^[1-9]([0-9]+)?(\.[0-9]{1,2})?$)|(^(0){1}$)|(^[0-9]\.[0-9]([0-9])?$)/.test(decimal)
        },
        isNumber:function(num){
            return /^\+?[1-9][0-9]*$/.test(num)
        },
        checkDecimal:function(obj){
            if(!vm.isDecimal(obj.value)) $(obj).parent().next().removeClass("hide")
            else $(obj).parent().next().addClass("hide")
        },
        checkNumber:function(obj){
            if(!vm.isNumber(obj.value)) $(obj).parent().next().removeClass("hide")
            else $(obj).parent().next().addClass("hide")
        },
        selectParent:function(obj){
            $(obj).parent().prev().prop("checked",true)
            //vm.clearCurError(obj) 
        },
        clearError:function(){
            $(".red.error").addClass("hide")
            $("#question-error").addClass("hide")
        },
        clearInput:function(obj){
            vm.clearError()
            $(obj).parent().prev().find("input[type='text']").val("")
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
        ,
        selectTime:function(val){
            $(this).parent().find("li").removeClass("active").end().end().addClass("active").parent().find("input[type='hidden']").val($(this).text()).parent().prev().find(".dropdown-label span").text($(this).text())
            if (val) {
                var _def = $(this).parent().attr("data-def-value")
                if (val != _def) $(this).parent().prev().find(".dropdown-label span").addClass("juhuang")
                else $(this).parent().prev().find(".dropdown-label span").removeClass("juhuang")
            }
        }
        ,
        formatMoney : function (_number,places, symbol, thousand, decimal) {
            places = !isNaN(places = Math.abs(places)) ? places : 2;
            symbol = symbol !== undefined ? symbol : "$";
            thousand = thousand || ",";
            decimal = decimal || ".";
            var number = _number,
                negative = number < 0 ? "-" : "",
                i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
                j = (j = i.length) > 3 ? j % 3 : 0;
            /*if (number == 0 || number == "") {
                return ""
            }*/
            return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
        },
        selectTimeWithVal:function(val,txt){
            $(this).parent().find("li").removeClass("active").end().end().addClass("active").parent().find("input[type='hidden']").val(val).parent().prev().find(".dropdown-label span").text($(this).text())
            var _def = $(this).parent().attr("data-def-value")
            if (txt != _def) $(this).parent().prev().find(".dropdown-label span").addClass("juhuang")
            else $(this).parent().prev().find(".dropdown-label span").removeClass("juhuang")
        }

        ,
        setValue:function(obj){
                var _this = $(obj)
                var _price = parseFloat( _this.attr("data-price") )
                var _count = parseFloat(_this.val())
                var _money = _price * _count
                _this.parents("td").next().text(vm.formatMoney(_money,2,"￥"))
                vm.setTotalPrice()
            }
            ,
            setTotalPrice:function(){
                var _total = 0
                var _tbl = $(".bgtbl:eq(0)")
                $.each(_tbl.find("tr").slice(1),function(index,item){
                    var _td = $(item).find("td:last").prev()
                    var _td_input = _td.find("input[readonly='readonly']")
                    _total += parseFloat( _td_input.attr("data-price") ) * parseFloat(_td_input.val())
                })
                _tbl.next().find("input[name='price']").val(_total)//后台取值对象
                _tbl.next().find("label").text(vm.formatMoney(_total,2,"￥"))
            }


        ,
        prev:function(){
            var _this = $(this)
            var _input = $(this).next()
            var _val = parseInt(_input.val())
            var _min = 0
            _input.val(_val == _min ? _min : (_val - 1))
            vm.setValue(_input)
        }
        ,
        next:function(max){
            var _max = max || 1 //可在页面传参 注：此件数选择不超过单车可装件数，也不超过可供件数。
            var _this = $(this)
            var _input = $(this).prev()
            var _val = parseInt(_input.val())
            _input.val(_val == _max ? _max : (_val + 1))
            vm.setValue(_input)
        }
        ,
        prev2:function(){
            var _this = $(this)
            var _input = $(this).next()
            var _val = parseInt(_input.val())
            var _min = 0
            _input.val(_val == _min ? _min : (_val - 1))
        }
        ,
        next2:function(max){
            var _max = max || 1 //可在页面传参 注：此件数选择不超过单车可装件数，也不超过可供件数。
            var _this = $(this)
            var _input = $(this).prev()
            var _val = parseInt(_input.val())
            _input.val(_val == _max ? _max : (_val + 1))
        }
        ,
        modify:function(){
           $(".modifydiv").removeClass('hide')
           $("#btn-control-wapper").hide()
        }
        ,
        noModify:function(){
           $(".modifydiv").addClass('hide')
           $("#btn-control-wapper").show()
        }
        ,
        sureModify:function(){

            if (vm.modifyOrStop) {
                vm.isSend = !0
                if (!vm.agree) {
                    return
                }
                vm.noChange = !0
                $.each($("#user-form-step-1 .dropdown-menu"),function(){
                    var _def = $(this).attr("data-def-value").trim()
                    var _select = $(this).prev().find(".dropdown-label span").text().trim()
                    if (_def != _select ) {
                        vm.noChange = !1
                    }
                })
                $.each($("#user-form-step-1 .bgtbl"),function(idx,item){
                    $.each($(item).find("tr"),function(){
                        var _input = $(this).find("input[type='text']")
                        var _def = _input.attr("def-value")
                        var _select = _input.val()
                        if (_def != _select ) {
                            vm.noChange = !1
                        }
                    })
                })
                if (!vm.noChange)  $("#modifyWin").hcPopup({'width':'420'})

            }else{
                $("#stopWin").hcPopup({'width':'420'})
            }

        }
        ,
        doModify:function(){
            $("#user-form-step-1").submit()
        },
        send:function(){
            var _flag = !0
            var _array = []
            vm.clearError()
            $.each($(".tbl-file tr"),function(){
                var _radio1 = $(this).find("input[type='radio']").eq(0)
                var _radio2 = $(this).find("input[type='radio']").eq(1)
                var _input1 = $(this).find("input[type='text']").eq(0)
                var _input2 = $(this).find("input[type='text']").eq(1)
                //console.log(vm.isNumber(_input2.val()))
                if (_radio1.prop("checked")) {
                    if (_input1.val().trim() == "" || _input2.val().trim() == "") {
                        $("#question-error").removeClass("hide")
                        _flag = !1
                    }else if(!vm.isDecimal(_input1.val()) || !vm.isNumber(_input2.val())){
                        _array.push(!1)
                        _input2.parent().next().removeClass("hide")
                    }
                }
            })
            if(_flag && _array.length == 0) $("#sendWin").hcPopup({'width':'420'})
           
        },
        doSend:function(id){
            //特需无修改
            if ($("input[name='ziliao[0][title]']").length>0) {
                $("#user-form-step-1").attr('action', '/dealer/order/special/'+id);
                $("#user-form-step-1").submit()
            } else {
                $("#user-form-step-1-1").submit()
            }


            $("#sendWin").hide()
        },
        stopOrder:function(){
           //不知道干嘛
           $("#user-form-step-1-2").submit();
           console.log("stopOrder")
        },
        pushMonthList:function(year,month){
            vm.monthList = []
            for (var i = 1; i <= 12; i++) {
                vm.monthList.push(i)
            }
            vm.yearAndMonth = year + "年" + (vm.month ? vm.month : month) + "月"
            vm.year = year
        },
        selectMonth:function(month){
            vm.yearAndMonth = vm.year + "年" + month + "月"
            vm.month = month
        }

    });


    vm.agreeList.$watch('length',function(a,b){
        vm.agree = a === 0 ? !1 : !0
        vm.isSend = !1
    })

    vm.$watch('noChange',function(a,b){
        if (a) {
            setTimeout(function(){
                vm.noChange = !1
            },3000)
        }
    })

    module.exports = {
        init:function(startTime,endTime,call){
           vm.init(startTime,endTime,call)
        },
        initPushKmList:function(start){
           for (var i = start + 1; i <= 100 + start ; i++) {
                vm.kmList.push(i)
           }
           //vm.kmList.push("100以上")
        },
        initPushCarTimeList:function(start){
           for (var i = start + 1; i <= 12; i++) {
                vm.carTimeList.push(i)
           }
        },
        initYearAndMonth:function(year,month){
            for (var i = year - 1; i >= 2010; i--) {
                vm.yearList.push(i)
            }
            for (var i = month + 1; i <= 6; i++) {
                vm.monthList.push(i)
            }

        }
    }


});