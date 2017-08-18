
define(function (require,exports,module) {

    require("module/common/time.jquery")
    require("module/common/hc.popup.jquery")
    require("vendor/jquery.form")

    var vm = avalon.define({
        $id: 'custom',
        agree:!0,
        agreeList:["1"],
        isSend:!1,
        modifyOrStop:!0,
        noChange:!1,
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
        selectTimeWithVal:function(val,txt){
            $(this).parent().find("li").removeClass("active").end().end().addClass("active").parent().find("input[type='hidden']").val(val).parent().prev().find(".dropdown-label span").text($(this).text())
            var _def = $(this).parent().attr("data-def-value")
            if (txt != _def) $(this).parent().prev().find(".dropdown-label span").addClass("juhuang")
            else $(this).parent().prev().find(".dropdown-label span").removeClass("juhuang")
        }
      
        ,
        setValue:function(obj){
            var _this = $(obj)
            var _price = parseInt( _this.parents("td").prev().text().replace(/[,]/g,"") )
            var _val = parseInt(_this.val())
            var _txt = _val * _price
            _txt =  _txt == 0 ? "" : _txt+""
            //每项的金额 选择购买件数 * 含安装费折后总单价
            _this.parents("td").next().text(_txt)
            var _total = 0
            var _tbl = _this.parents(".bgtbl")
            //总金额 
            $.each(_tbl.find("tr").slice(1),function(index,item){
                var _itemval  = parseInt($(item).find("td:last").text())
                _total += !isNaN(_itemval) ?_itemval: 0
            })
            _tbl.next().find("input[name='price']").val(_total)//后台取值对象
            _tbl.next().find("label").text(_total)
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
                    var _def = $(this).attr("data-def-value")
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
            
            var _form  = $("#user-form-step-1")
            var options = {
                type: 'post' ,
                beforeSend: function(data) {
                     
                },
                success: function(data) {
                     
                },
                error: function(msg) {
                     
                }
            }
            _form.ajaxForm(options).ajaxSubmit(options)
            if (vm.modifyOrStop) {
                 $("#modifyWin").hide()
            }else{
                $("#stopWin").hide()
            }
        },
        send:function(){
            $("#sendWin").hcPopup({'width':'420'})
        },
        doSend:function(){
            console.log("doSend")
            $("#sendWin").hide()
        },
        stopOrder:function(){
           //不知道干嘛
           console.log("stopOrder")
        }
        
    });
    
    vm.agreeList.$watch('length',function(a,b){
        vm.agree = a === 0 ? !1 : !0 
        vm.isSend = !1
    }) 
      
    module.exports = {
        init:function(startTime,endTime,call){
           vm.init(startTime,endTime,call)
        }
    }


});