define(function(require, exports, module) {

    $(".abs").change(function() {
        var _val = $(this).val()
        $(this).next().next().hide()
        if (!isNaN(_val)) {
            if (_val != "") {
                $(this).val(Math.abs($(this).val()))
            } else {
                $(this).focus().select().next().next().show()
                if ($(this).attr("name") === "bj_license_plate_break_contract") {
                    $(this).next().next().next().removeClass('show')
                }
                return
            }
        } else {
            $(this).val('');
        }
    })

    function initLoadCount(obj, count) {
        //J.4.4A常用车型
        var _html = ""
        for (var i = 0; i <= count; i++) {
            _html += "<li><a><span>" + i + "</span></a></li>"
        }
        var _obj = null
        if (typeof(obj) == 'object') {
            _obj = obj
        } else {
            _obj = $("." + obj)
        }
        _obj.append(_html).find("li").click(function() {
            //给定点击事件
            $(this).addClass('active').siblings().removeClass('active').parents(".btn-group").find(".dropdown-label span").html($(this).text()).parents(".btn-group").find("input[type='hidden']").val($(this).text())
        })
    }

    $(".btn-jquery-event").delegate('li', 'click', function(event) {
            var _this = $(this)
            var _parant = _this.parents(".btn-group")
            _this.addClass('active').siblings().removeClass("active")
            _parant.find("input[type='hidden']").val(_this.text())
            _parant.find(".dropdown-label span").text(_this.text())

        })
        //
    $(".btn-jquery-Passenger").delegate('li', 'click', function(event) {
        var _this = $(this)
        var _parant = _this.parents(".btn-group")
        _this.addClass('active').siblings().removeClass("active")
        _parant.find("input[type='hidden']").val(_this.text())
        _parant.find(".dropdown-label span").text(_this.text())

    })

    $(".btn-file").delegate('li', 'click', function(event) {
        var _this = $(this)
        var _parant = _this.parents(".btn-group")
        _this.addClass('active').siblings().removeClass("active")
        _parant.find("input[type='hidden']").val(_this.text())
        _parant.find(".dropdown-label span").text(_this.text())
        var id = _parant.find(".active").attr("data-id");
        _parant.find("input[name=file_id]").val(id);

    })

    $(".btn-jquery-event-use-id").delegate('li', 'click', function(event) {
        var _this = $(this)
        var _parant = _this.parents(".btn-group")
        _this.addClass('active').siblings().removeClass("active")
        _parant.find("input[type='hidden']").val(_this.attr('data-value'))
        _parant.find(".dropdown-label span").text(_this.text())

    })

    //货币格式
    Number.prototype.formatMoney = function(places, symbol, thousand, decimal) {
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

    Number.prototype.into = function() {
        var _split = this.toString().split(".")
        var _num = this
        if (_split.length >= 2) {
            if (_split[1] > 0) {
                _num = parseInt(_split[0]) + 1
            }
        }
        return _num
    }

    String.prototype.toMoney = function() {
        return this.replace("￥", "").split(",", "").join("")
    }

    $('a[data-toggle="collapse"],.panel-heading').click(function() {
        var event = getEvent()
        if (event.target.tagName == "I") {
            return
        }
        var _i = $(this).find("i")
        if (_i.hasClass('fa-sort-up')) {
            _i.removeClass('fa-sort-up').addClass('fa-sort-down')
        } else {
            _i.removeClass('fa-sort-down').addClass('fa-sort-up')
        }
    })

    $.fn.modifiedBox = function(_options) {

        var defaults = {
            min: 1,
            max: 99,
            clickCallBack: function() {}
        }
        var options = $.extend(defaults, _options)

        return this.each(function() {
            var __input = $(this).find("input[type='text']")
            __input.focus(function(event) {
                __input.blur()
            })
            if (__input.attr("disabled")) {
                __input.siblings().addClass('disabled-click')
            }
            $(this).find(".prev").click(function() {
                var _input = $(this).next()
                if (_input.attr("disabled")) {
                    return
                }
                if (parseInt(_input.val()) <= 1) {
                    _input.val(1)
                } else {
                    _input.val((parseInt(_input.val()) - 1))
                }
                options.clickCallBack()

            }).next().keyup(function() {
                var _input = $(this)
                if (isNaN(_input.val())) {
                    _input.val(options.min)
                }
            }).blur(function() {
                var _input = $(this)
                if (isNaN(_input.val())) {
                    _input.val(options.min)
                }
                options.clickCallBack()
            }).next().click(function() {
                var _input = $(this).prev()
                if (_input.attr("disabled")) {
                    return
                }
                var _val = parseInt(_input.val()) + 1
                var _max = parseInt(_input.attr("max")) || options.max
                _input.val(_val >= _max ? _max : _val)
                options.clickCallBack()
            })


        })
    }

    function initAutocomplete(url) {
        var _this = $(".autocomplete")
        if (_this[0]) {
            require("vendor/jquery.autocomplete")
            var data = []
                //debugger
            $.ajax({
                type: "GET",
                url: url,
                async: false,
                data: {
                    key: _this.val()
                },
                dataType: "json",
                success: function(datas) {
                    data = datas
                },
                error: function() {
                    //data = [{name:"jquery",id:"1002"},{name:"javascript",id:"1042"},{name:"react",id:"1005"},{name:"05697",id:"1005"},{name:"05597",id:"1005"}]
                }
            })
            _this.autocomplete(data, {
                width: _this.width() + 26,
                max: 10,
                formatItem: function(row, i, max) {
                    return row.name
                },
                formatMatch: function(row, i, max) {
                    return row.name
                }
            })
        }
    }
    module.exports = {
        initAutocomplete: function(url) {
            initAutocomplete(url)
        }

    }


})

function isPositiveInteger(num) {
    var _reg = /^[1-9]\d*$/
    return _reg.test(num)
}

function errorshowhide(obj) {
    obj.show(function() {
        setTimeout(function() {
            obj.fadeOut(300)
        }, 3000)
    })
}

function errorshow(obj) {
    obj.show()
}

function errorhide(obj) {
    obj.fadeOut(333)
}

function isempty(obj) {
    return $.trim(obj.val()) == ""
}

function getEvent() {
    return arguments.callee.caller.arguments[0] || window.event
}

$(".form-group.psr i").click(function() {
    $(this).prev().focus()
})

function initMoney(obj) {
    var _val = obj.val()
    var _price = _val.split(",").join("")
    obj.val(_price)
}
$(".baojia-submit-button").click(function() {
    var _step = $(this).attr('data-step') //获取报价步骤
    var _type = $(this).attr('data-type') //获取按钮类型（下一步|保存并退出）
        //alert(_type);return false;
    var _form = $("form[name='baojia-submit-form']")
    var _flag = true;

    //console.log(_step)

    if (_step == 1) { //车型价格
        var _autocomplete = $(".autocomplete")
        var _textarea = $(".textarea")
        if (_bjStatus == 'new') {
            $.each($(".btn-group"), function() {
                var _event = $(this)
                if (_event.find("input[type='hidden']").val().trim() == "") {
                    _event.parents("td").find(".error-div").show()
                    _flag = false
                } else {
                    _event.parents("td").find(".error-div").hide()
                }
            })
        }
        if (_autocomplete.val() == "" && $("#xianche").prop("checked")) {
            _autocomplete.next().show()
            _flag = false
        } else {
            _autocomplete.next().hide()
        }
        if ($("input[name*='sale_area[']:checked").length == 0) {
            _textarea.parent().next().show()
            _flag = false
        } else {
            _textarea.parent().next().hide()
        }

        //
        var _bj_dealer_internal_id = $('input[name=bj_dealer_internal_id]').val()
        var _error = $('input[name=bj_dealer_internal_id]').next()
        if (_bj_dealer_internal_id.length > 20) {
            errorshow(_error)
            _flag = false
        } else {
            var _reg = /^[a-zA-Z0-9]{1,20}$/
            if (_bj_dealer_internal_id != '' && !_reg.test(_bj_dealer_internal_id)) {
                errorshow(_error)
                _flag = false
            } else {
                errorhide(_error)
            }
        }

        $(".format-money").each(function() {
            initMoney($(this))
        })
        var _max = 50000
        var _guided = $("input[name='zhidaojia']") //指导价
        var _invoice = $("input[name='bj_lckp_price']") //车辆开票价格
        var _service = $("input[name='bj_agent_service_price']") //我的服务费
        var _payment = $("input[name='bj_doposit_price']") //客户买车定金
        var _guided_price = _guided.val()
        var _invoice_price = _invoice.val().split(",").join("")
        var _service_price = _service.val().split(",").join("")
        var _payment_price = _payment.val().split(",").join("")

        $("#error-div").hide()
        _invoice.next().hide()
        _invoice.next().next().hide()
        if (parseFloat(_invoice_price) < parseFloat(_guided_price) * 0.5) {
            _invoice.next().next().show()
            $("#error-div").addClass("error-invoice").html("<label>温馨提示：设置车辆开票价格过低（不到厂商指导价的50%），修改后方可进入下一步</label>").show()
            _flag = false
        }
        if (parseFloat(_invoice_price) < parseFloat(_guided_price) * 0.7) {
            _invoice.next().next().show()
        }
        if (parseFloat(_invoice_price) > parseFloat(_guided_price) * 2) {
            _invoice.next().show()
            if ($("#error-div").is(":hidden")) {
                $("#error-div").addClass("error-invoice").html("<label>温馨提示：设置车辆开票价格过高（超过厂商指导价的200%），修改后方可进入下一步</label>").show()
            }
            _flag = false
        }
        if (parseFloat(_payment_price) > parseFloat(_guided_price) * 0.5) {
            _payment.next().show()
            if ($("#error-div").is(":hidden")) {
                $("#error-div").addClass("error-payment").html("<label>温馨提示：设置客户买车定金过高（超过厂商指导价的50%），修改后方可进入下一步</label>").show()
            }
            _flag = false
        }

        if (_service_price.trim() != "" && parseFloat(_service_price) > Math.round(parseFloat(_invoice_price) * 0.03)) {
            _flag = false
            _service.next().show()
                //_service.focus().next().show()
        } else {
            if (parseFloat(_service_price) > _max) {
                _flag = false
                _service.next().show()
            }
        }

    } else if (_step == 2) {
        var mydate = new Date();
        if ($("input[name='body_color']").val() == '') {
            $("#tip-error").hcPopup({ content: '请选择车身颜色', callback: function() {} })
            return false;
        }
        //现车内饰颜色选择 判断
        if ($("input[name='interior_color'][type=hidden]").length == 1 && $("input[name='interior_color']").val() == '') {
            $("#tip-error").hcPopup({ content: '请选择内饰颜色', callback: function() {} })
            return false;
        }

        //非现车内饰颜色选择 判断
        if ($("input[name='interior_color[]']").length > 0 && $("input[name='interior_color[]']:checked").length == 0) {
            $("#tip-error").hcPopup({ content: '请选择内饰颜色', callback: function() {} })
            return false;
        }

        //现车出厂年月选择 判断
        if ($("input[name='produce-year']").length == 1 && $("input[name='produce-year']").val() == '') {
            $("#tip-error").hcPopup({ content: '请选择出厂年份', callback: function() {} })
            return false;
        }

        //现车出厂年月选择 判断
        if ($("input[name='produce-month']").length == 1) {
            //alert($("input[name='produce-month']").val())
            if ($("input[name='produce-month']").val() == '') {
                console.log('请选择出厂月份')
                $("#tip-error").hcPopup({ content: '请选择出厂月份', callback: function() {} })
                return false;
            } else {
                var cruentMonth = Number(mydate.getMonth()) + 1;
                //alert(Number($("input[name='produce-month']").val()));
                //return false;
                if (Number($("input[name='produce-year']").val()) == mydate.getFullYear() && 　Number($("input[name='produce-month']").val()) > cruentMonth) {
                    $("#tip-error").hcPopup({ content: '选择的时间不能晚于当前时间', callback: function() {} })
                    return false;
                }
            }
        }
        var _ipt = $("input[name='bj_licheng']")
        if (_ipt[0]) {
            if (parseFloat(_ipt.val()) < 0 || $.trim(_ipt.val()) == "") {
                $("#tip-error").hcPopup({ content: '请输入行驶里程', callback: function() {} })
                return false;
            }
        }
    } else if (_step == 3) {
        var _input = $(".custom-control-min")
        var _val = _input.val()
        if (isNaN(_val)) {
            _input.focus().select().next().next().show()
            _flag = false
        } else if (parseInt(_val) > 100) {
            _input.val(100)
        } else {
            if (_val != "") {
                _input.val(parseInt(_val))
                _input.next().next().hide()
            } else {
                _flag = false
                _input.focus().next().next().show()
            }
        }

    } else if (_step == 4) {
        if (Number($("input[name=bj_bx_select]").val()) <= 0) {
            $("#tip-error").hcPopup({ content: '请选择对应的保险公司', callback: function() {} })
            return false;
        }
        $.each($(".card-txt-price"), function() {
            var _this = $(this)
            var _val = _this.val().split(",").join("")
                //_this.val(Math.floor(Math.random()*800)+200);
            if (isempty(_this) || isNaN(_val)) {
                $(this).addClass('red-border') //$(this).focus()
                _flag = false
                errorshow($("#inpurerror"))
                    //return false
            }

            initMoney($(this))
        })
    } else if (_step == 5) {
        var _isok = true

        $.each($(".valite-txt"), function() {
            var _this = $(this)
            var _input = _this.find("input[type='text']")
            var _val = _input.val().trim()
            var _error = _this.find(".error-div")
            if (isempty(_input)) {
                _error.show().next().hide()
                _isok = false
            } else {
                if (parseFloat(_val) % 100 != 0) {
                    _error.show().next().hide()
                    _isok = false
                } else {
                    _error.hide()
                }
            }
        })


        var _arr = [false, false]
        var _v3 = $(".valite-3")
            //刷卡标准单独验证
        _arr[0] = CheckCardStandard(_v3.eq(0))
        _arr[1] = CheckCardStandard(_v3.eq(1))

        if (_arr[0] == false || _arr[1] == false || _isok == false) {
            return false;
        }

    } else if (_step == 6) {
        //_form.find("input[name=bj_num]").attr('disabled',false);
        var _arr = [false, false]


        var _timevalite = true
            //Date.parse(_start.val())-0
        if ($(".time-set").prop("checked")) {
            var _timewrapper = $(".start")
            $.each(_timewrapper, function() {
                if ($(this).val().trim() == "") {
                    _timevalite = false
                    errorshowhide($("#timerror"))
                    return
                }
            })
            var date1 = Date.parse(_timewrapper.eq(1).val().replace(/-/g, "/") + ' ' + $("input[name=end_time_2]").val());
            var date2 = Date.parse(_timewrapper.eq(0).val().replace(/-/g, "/") + ' ' + $("input[name=start_time_2]").val());
            if (date1 <= date2) {
                errorshowhide($("#timerror"))
                _timevalite = false
            }

        }
        if ($('input[name=bt_status]').length == 0) { //没有节能
            _arr[0] = true
        } else {
            var _t1 = $(".card-input").eq(0).parent().find("input[type='text']")
            var _t2 = $(".card-input").eq(1).parent().find("input[type='text']")
            var _c1 = $(".card-input").eq(0).parent().find("input[type='radio']")
            var _c2 = $(".card-input").eq(1).parent().find("input[type='radio']")
            _t1.next().next().hide()
            _t2.next().next().hide()

            _arr[0] = true

            if (_c1.prop("checked")) {
                var _sp = _t1.val().split(".")
                if (_t1.val().trim() == "") {
                    _arr[0] = false
                    errorshow(_t1.next().next())
                } else if (parseFloat(_t1.val()) <= 0) {
                    _arr[0] = false
                    errorshow(_t1.next().next())
                } else if (_sp.length == 2) {
                    if (_sp[1] != "0" && _sp[1] != "00") {
                        _arr[0] = false
                        errorshow(_t1.next().next())
                    }
                }
            } else if (_c2.prop("checked")) {
                var _sp = _t2.val().split(".")
                if (_t2.val() == "") {
                    _arr[0] = false
                    errorshow(_t2.next().next())
                } else if (parseFloat(_t2.val()) <= 0) {
                    _arr[0] = false
                    errorshow(_t2.next().next())
                } else if (_sp.length == 2) {
                    if (_sp[1] != "0" && _sp[1] != "00") {
                        _arr[0] = false
                        errorshow(_t2.next().next())
                    }
                }
            }
            /*if(!_c1.prop("checked") && _t1.val().trim()!="" ){
                _t1.val("");
            }

            if(!_c2.prop("checked") && _t2.val().trim()!="" ){
                _t2.val("");
            }*/
            //_arr[0]  = false
        }
        if (_arr[0] != true || _timevalite != true) {
            return false;
        }
        $("input[name='bj_num']").removeAttr('disabled');
    }
    if (_flag == true) {
        var options = {
            dataType: "json",
            success: function(data) {
                if (data.error_code == 0) {
                    var _bj_id = data.bj_id;
                    var _next_step = Number(_step) + 1;
                    //return false;
                    if (_step < 6) {
                        if (_type == 2) {
                            window.location.href = "/dealer/baojialist/unfinished";
                        } else {
                            if (_step == 3) {
                                window.location.href = "/dealer/baojia/edit/" + _bj_id + "/5";
                            } else {
                                window.location.href = "/dealer/baojia/edit/" + _bj_id + "/" + _next_step;
                            }

                        }
                    } else {
                        if (_type == 2) {
                            if(data.to)
                                window.location.href = "/dealer/baojialist/"+data.to;
                            else
                                window.location.href = "/dealer/baojialist/online";
                        } else {
                            window.location.href = "/dealer/baojia/view/" + _bj_id + "/6";
                        }


                    }

                } else {
                    if(data.msg) alert(data.msg);
                    else{
                        alert('数据保存失败');
                        $("#tip-error").hcPopup({ content: '数据保存失败', callback: function() {} })
                    }
                   }
            },
            beforeSubmit: function() {

            },
            clearForm: false
        }
        _form.ajaxForm(options)
        _form.ajaxSubmit(options)
    }
})

//刷卡标准 单独验证
//返回 是否验证成功
function CheckCardStandard(obj) {

    var _flag = true
    var _that = obj
    var _radio = _that.find("input[type='radio']")
    var _t1 = _that.find("input[type='text']").eq(0)
    var _t2 = _that.find("input[type='text']").eq(1)
    var _t3 = _that.find("input[type='text']").eq(2)
    var _c1 = _that.find("input[type='checkbox']").eq(0)
    var _c2 = _that.find("input[type='checkbox']").eq(1)
    _t1.parents("tr").find(".total-error").hide()
    if (_radio.eq(0).prop("checked")) {
        _flag = true
    } else {
        _radio = _radio.eq(1)
        if (_radio.prop("checked")) {
            //count error div
            var _error_count = _t1.parents("td").next().find(".error-div").eq(0)
            if (_t1.val().trim() != "") {

                if (isNaN(_t1.val())) {
                    _error_count.show()
                    _flag = false
                    _t1.focus().select()
                } else {
                    if (_t1.val().split(".").length > 1) {
                        _error_count.show()
                        _flag = false
                        _t1.focus().select()
                    } else if (parseFloat(_t1.val()) < 1) {
                        _error_count.show()
                        _flag = false
                        _t1.focus().select()
                    } else {
                        _error_count.hide()
                        var _arr = [true, true]
                        if (_c1.prop("checked")) {
                            if (!CheckCardSmipStep(_t2, false)) {
                                _flag = false
                            }
                        } //first checkbox no checked
                        else {
                            _arr[0] = false
                        }
                        if (_c2.prop("checked")) {
                            if (!CheckCardSmipStep(_t3, true)) {
                                _flag = false
                            }
                        } //second checkbox no checked
                        else {
                            _arr[1] = false
                        }
                        //必须两个都不成真说明一个都没有选择
                        if (!_arr[0] && !_arr[1]) {
                            _flag = false
                            _t1.parents("tr").find(".total-error").show()

                        }
                    } // count has not decimal point
                } //count is a num
            } //no count
            else {
                _error_count.show()
                _flag = false
            }
        } // no select

    }



    return _flag
}

function CheckCardSmipStep(obj, isInteger) {
    var _flag = true
    var _t2 = obj
    var _val = parseFloat(_t2.val())
    if (_t2.val().trim() != "") {

        if (isNaN(_t2.val())) {
            _t2.focus().select().next().next().hide().next().show()
        } else {
            var _split = _t2.val().toString().split('.')
            if ((_split.length >= 2 && _split[1].length > 1)) {
                _flag = false
                _t2.focus().select().next().next().hide().next().show()
            } else {

                if (isInteger) {
                    if (_val % 10 != 0) {
                        _flag = false
                        _t2.focus().select().next().next().hide().next().show()
                    } else {
                        if (_val <= 0) {
                            _flag = false
                            _t2.focus().select().next().next().hide().next().show()
                        } else {
                            _t2.next().next().next().hide()
                        }
                    }
                } else {
                    if (_val <= 0) {
                        _flag = false
                        _t2.focus().select().next().next().hide().next().show()
                    } else {
                        _t2.next().next().next().hide()
                    }
                }

            }


        }

    } else {
        _t2.next().next().hide().next().show()
        _flag = false
            //console.log(_t2.next().next().hide().next()[0])
    }
    return _flag
}

//重置按钮
$(".reset-form").click(function() {
    window.location.reload();
});

//执行报价删除 复制 暂停 终止等方法
function baojiaExcute(action, bj_id, callback) {
    //var _this = this
    if (action == 'delete') {

    } else if (action == 'copy') {

    } else if (action == 'modify') {

    } else if (action == 'suspensive') {

    } else if (action == 'stop') {

    } else if (action == 'stop_all') {

    } else if (action == 'renew') {

    } else if (action == 'regain') {

    } else if (action == 'suspensive-all') {

    } else if (action == 'suspensive_all') {

    } else if (action == 'shelves-all') {

    } else if (action == 'ceaseves-all') {

    } else if (action == 'regain-all') {

    } else {
        alert('此方法不存在');
        return false;
    }
    $.ajax({
        url: '/dealer/baojia/ajaxsubmit/' + action + '-baojia', //
        type: "post",
        dataType: "json",
        data: {
            _token: $("meta[name=csrf-token]").attr('content'),
            bj_id: bj_id
        },
        beforeSend: function() {

        },
        success: function(data) {

            var _error_code = data.error_code;
            var _error_msg = data.error_msg;
            //假定 _error_code 值
            //case 1 删除失败
            //case 0 删除成功

            if (_error_code == 0) {

                switch(action)
                {
                    case 'renew':
                        $("#RenewWin").hide();
                        show_countdown(_error_msg)
                        break;
                    case 'shelves-all':
                        $("#ShelvesAllWin").hide();
                        show_countdown(_error_msg)
                        break;
                    case "delete":
                        $("#DelWin").hide();
                        if (callback) {
                            callback(data)
                        }
                        break;
                    case "copy":
                        window.location.href = "/dealer/baojia/edit/" + data.bj_id + "/1";
                        break;
                    default:
                        if (callback) {
                            callback(data)
                        } else {
                            window.location.reload();
                        }
                };

                // $("#DelWin").hide();
                // if (action == 'copy') {
                //     window.location.href = "/dealer/baojia/edit/" + data.bj_id + "/1";
                // } else if (action == "delete") {
                //     if (callback) {
                //         callback(data)
                //     }
                // } else {
                //     if (callback) {
                //         callback(data)
                //     } else {
                //         window.location.reload();
                //     }
                // }
            } else if (_error_code == 1) {
                switch(action)
                {
                    case 'renew':
                        $("#RenewWin").hide();
                        break;
                    case 'shelves-all':
                        $("#ShelvesAllWin").hide();
                    // default:
                    //      alert('操作失败');
                };

                 show_countdown(_error_msg);
             
            }

        },
        error: function() {


        }
    })
}


function show_countdown(msg){
    $("#notice_pop").find('.msg').html(msg);

    let second_div =$("#notice_pop").find('.second');
    var start = 4;
    var count_down =  setInterval(function (){
        second_div.text(start);
        start--;
        if(start == 0){
            clearInterval(count_down);
            location.reload();
        }
    },1000)
    $("#notice_pop").hcPopup({'width':'450'});
}