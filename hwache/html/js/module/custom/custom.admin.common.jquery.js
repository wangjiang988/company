
define(function (require,exports,module) {

    function initLoadCount(obj,count){
         //J.4.4A常用车型   
         var _html = ""
         for (var i = 0; i <= count; i++) {
            _html += "<li><a><span>" + i + "</span></a></li>"
         }
         var _obj = null 
         if (typeof(obj) == 'object') {
            _obj = obj
         }else{
            _obj = $("."+obj)
         }
         _obj.append(_html).find("li").click(function(){
             //给定点击事件
             $(this).addClass('active').siblings().removeClass('active').parents(".btn-group").find(".dropdown-label span").html($(this).text()).parents(".btn-group").find("input[type='hidden']").val($(this).text())
         })
    }
 
    $(".btn-jquery-event").delegate('li', 'click', function(event) {
        var _this   = $(this)
        var _parant = _this.parents(".btn-group")
        _this.addClass('active').siblings().removeClass("active")
        _parant.find("input[type='hidden']").val(_this.text())
        _parant.find(".dropdown-label span").text(_this.text())

    })

    //货币格式
    Number.prototype.formatMoney = function (places, symbol, thousand, decimal) {
        places = !isNaN(places = Math.abs(places)) ? places : 2;
        symbol = symbol !== undefined ? symbol : "\uffe5";
        thousand = thousand || ",";
        decimal = decimal || ".";
        var number = this,
            negative = number < 0 ? "-" : "",
            i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
            j = (j = i.length) > 3 ? j % 3 : 0;
        return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
    }

    Number.prototype.into = function () {
        var _split = this.toString().split(".")
        var _num = this
        if (_split.length >= 2) {
            if (_split[1] > 0) {
              _num = parseInt(_split[0]) + 1
            }
        }
        return _num
    }

    String.prototype.toMoney = function () {
        return this.replace("￥","").split(",","").join("")
    }

    $('a[data-toggle="collapse"],.panel-heading').click(function(){
        var event = getEvent()
        if (event.target.tagName == "I") {
            return
        }
        var _i = $(this).find("i")
        if (_i.hasClass('fa-sort-up')) {
            _i.removeClass('fa-sort-up').addClass('fa-sort-down')
        }else{
            _i.removeClass('fa-sort-down').addClass('fa-sort-up')
        }
    })


    $.fn.modifiedBox = function(_options){

        var defaults = {
           min:1,
           max:99, 
           clickCallBack:function(){}
        }
        var options = $.extend(defaults, _options)
        
        return this.each(function(){
            var __input = $(this).find("input[type='text']")
            __input.focus(function(event) {
                 __input.blur()
            })
            if (__input.attr("disabled")) {
               __input.siblings().addClass('disabled-click')
            }
            $(this).find(".prev").click(function(){
                var _input = $(this).next()
                if (_input.attr("disabled")) {
                   return
                } 
                if (parseInt(_input.val()) <= 1) {
                    _input.val(1)
                }else{
                    _input.val((parseInt(_input.val())-1))
                }
                options.clickCallBack()

             }).next().keyup(function(){
                var _input = $(this) 
                if (isNaN(_input.val())) {
                    _input.val(options.min)
                } 
             }).blur(function(){
                var _input = $(this) 
                if (isNaN(_input.val())) {
                    _input.val(options.min)
                } 
                options.clickCallBack()
             }).next().click(function(){
                var _input = $(this).prev()
                if (_input.attr("disabled")) {
                   return
                }
                var _val   = parseInt(_input.val()) + 1
                var _max   = parseInt(_input.attr("max")) || options.max
                _input.val(_val >= _max ? _max : _val)
                options.clickCallBack()
             })

             
        })
    }
   
    function initAutocomplete(url){
        var _this = $(".autocomplete")
        if (_this[0]) {
            require("vendor/jquery.autocomplete")
            var data = []
            //debugger
            $.ajax({
                 type: "GET",
                 url: url,
                 async:false,
                 data: {
                    key:_this.val()
                 },
                 dataType: "json",
                 success: function(datas){
                    data = datas
                 },
                 error: function(){
                    data = [{name:"jquery",id:"1002"},{name:"javascript",id:"1042"},{name:"react",id:"1005"},{name:"05697",id:"1005"},{name:"05597",id:"1005"}]
                 }
            })
            _this.autocomplete(data, {
                  width:_this.width() + 26,
                  max:10,
                  formatItem: function (row, i, max) {
                     return row.name
                  },
                  formatMatch: function(row, i, max){
                     return row.name
                 }
            })

            
        }
    }

     
    module.exports = {
        initAutocomplete:function(url){
            initAutocomplete(url)
        }

    }
   

})

function errorshowhide(obj){
    obj.show(function(){
        setTimeout(function(){
            obj.fadeOut(300)
        },3000)
    })
}
function errorshow(obj){
    obj.show()
}
function errorhide(obj){
    obj.fadeOut(333)
}

function isempty(obj){
    return $.trim(obj.val()) == ""
}

function getEvent(){
    return arguments.callee.caller.arguments[0] || window.event
}

$(".form-group.psr i").click(function(){
    $(this).prev().focus()
})


