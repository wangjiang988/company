define(function (require,exports,module) {
    function isPositiveInteger(num){
        var _reg = /^[1-9]\d*$/
        return _reg.test(num)
    }

    $(".panel-body a.sort").click(function(){
       $(this).find(".fa").toggleClass('fa-sort-down');
    })

    $(".registrationCondition").click(function(){

        var _isok = true
        var _btn = $(this)
        $.each($(".valite-1"),function(){
            var _radio = $(this).find("input[type='radio']")
            var _this  = $(this)
            var _flag  = false
            $.each(_radio,function(index,item){
                if (index == 0 ) {
                    var _val = _this.find("input[type='text']").val().trim()
                    if ($(item).prop("checked") && _val !="" && !isNaN(_val) ) {
                        if (parseFloat(_val) % 100 == 0 && isPositiveInteger(_val)) {
                            _flag = true
                        }else{
                            if (_val=="0") {
                                 _flag = true
                            }else{
                                _flag = false
                            }
                        }
                    }else{
                        _flag = false
                    }
                }
                else if(index == 1 ){
                    if ($(item).prop("checked")) {
                        _flag = true
                    }
                }

            })
            if (_flag) {
                _this.find(".error-info").hide()
            }else{
                _this.find(".error-info").eq(0).show().next().hide().removeClass('show')
                _isok = false
            }

        })

        $.each($(".valite-txt"),function(){
            var _this  = $(this)
            var _input = _this.find("input[type='text']")
            var _val   =  _input.val().trim()
            var _error = _this.find(".error-div")
            if (isempty(_input)) {
                _error.show().next().hide()
                _isok = false
            }else{
               /* console.log(parseInt(_val) % 100 != 0)
                console.log(!isPositiveInteger(_val))*/
                if (parseInt(_val) % 100 != 0 || (!isPositiveInteger(_val) && _val!="0")) {
                    _error.show().next().hide()
                    _isok = false
                }else{
                    _error.hide()
                }
            }
        })

        if (_isok) {
             var _form  = $("form[name='next-form']")
             var options = {
                type: 'post',
                success: function(data) {
                   if(data.step == 3) {
                       var url = window.location.href
                       var site =url.substr(0,url.length-1);
                       window.location.href=site+4;
                   }
                   else if(data.error_code == 0 || data.error_code == 1){
                        if (_btn.hasClass('isnext')) {
                            window.location.reload()
                        }else{
                            $("#tip-succeed").hcPopup({content:'恭喜，修改成功！',callback:function(){
                                window.location.reload()
                            }})
                        }


                   }
                }
             }
             _form.ajaxForm(options).ajaxSubmit(options);
             require("vendor/jquery.form")
          }

    })

    $(".registrationEvent").click(function(){
        var _xx     = $(".valite-xx")
        var _yy     = $(".valite-yy")
        var _input1 = _xx.find("input[type='text']")
        var _input2 = _yy.find("input[type='text']")
        var _c1     = _yy.find("input[type='radio']").eq(0)
        var _c2     = _yy.find("input[type='radio']").eq(1)
        var _error1 = _xx.find(".error-div").eq(0)
        var _error2 = _yy.find(".error-div").eq(0)
        var _isok   = true
        var _val1   = _input1.val().trim()
        var _val2   = _input2.val().trim()
        var _btn    = $(this)

        _error1.hide()
        _error2.hide()

        if (isempty(_input1)) {
            _error1.show().next().hide()
            _isok = false
        }else if(parseFloat(_val1) < 0){
            _error1.show().next().hide()
            _isok = false
        }
        else if(parseFloat(_val1) % 100 != 0){
            _error1.show().next().hide()
            _isok = false
        }
        else if(_c1.prop("checked")){

            if (isempty(_input2)) {
                _error2.show().next().removeClass('show').hide()
                _isok = false
            }else if(parseFloat(_val2) < 0){
                _error2.show().next().removeClass("show").hide()
                _isok = false
            }
            else if(parseFloat(_val2) % 100 != 0){
                _error2.show().next().removeClass("show").hide()
                _isok = false
            }
        }

        if (_isok) {
             var _form  = $("form[name='next-form']")
             var options = {
                type: 'post',
                success: function(data) {
                   if(data.step == 3) {
                       var url = window.location.href
                       var site =url.substr(0,url.length-1);
                       window.location.href=site+4;
                   }
                   else if(data.error_code == 0 || data.error_code == 1){
                        if (_btn.hasClass('isnext')) {
                            window.location.reload()
                        }else{
                            $("#tip-succeed").hcPopup({content:'恭喜，修改成功！',callback:function(){
                                window.location.reload()
                            }})
                        }


                   }
                }
             }
             _form.ajaxForm(options).ajaxSubmit(options);
             require("vendor/jquery.form")
          }


    })


    $(".valite-1 input[type='radio']:eq(1)").click(function(event) {
        $(this).parent().prev().find("input[type='text']").val("")
    })
    $(".valite-yy input[type='radio']:eq(1)").click(function(event) {
        $(this).parent().prev().find("input[type='text']").val("")
    })

    $('input[name="dl_shangpai_object_fee"]').click(function(){
        $(this).parents("label").find("input[type='radio']").prop("checked",true)
    })

    $(".registrationCondition-adv").click(function(){
        var _btn   = $(this)
        var _isok  = true
        var _input = $(".edit-wp input")
        if ($(".tbl-list-tool-panle input[type='radio']:checked").length === 0) {
            _isok = false
        }
        if(isempty(_input)){
            errorshowhide(_input.parent().next())
            _isok = false
        }else if(isNaN(_input.val())){
            errorshowhide(_input.parent().next())
            _isok = false
        }else if(parseInt(_input.val()) % 100 != 0 || !isPositiveInteger(_input.val())){
            if (_input.val().trim()!="0") {
                errorshowhide(_input.parent().next())
                _isok = false
            }

        }

        if (_isok) {
             var _form  = $("form[name='next-form']")
             var options = {
                type: 'post',
                success: function(data) {
                   if(data.step == 4) {
                        var url = window.location.href
                        var site =url.substr(0,url.length-1);
                        window.location.href=site+5;
                   }
                   else if(data.error_code == 0 || data.error_code == 1){
                        if (_btn.hasClass('isnext')) {
                                window.location.reload()
                        }else{
                            $("#tip-succeed").hcPopup({content:'恭喜，修改成功！',callback:function(){
                            //window.location.reload()
                            }})
                        }
                   }
                },
             }
             _form.ajaxForm(options).ajaxSubmit(options);
            require("vendor/jquery.form")
          }else{
            _input.parent().next().next().hide()
        }

    })

	var controlModel = function(opt){
		this.target = opt.target || null
	}
	controlModel.prototype.init = function(arg) {
		 controlModel.target = arg
	}
	controlModel.prototype.eventBind = function() {
         //select
         var _li = controlModel.target.find("li")
         _li.unbind('click').bind("click",function(){
             //alert($(this).attr('id'))
            $(this).addClass("active").siblings().removeClass("active").parent().find("input[type='hidden']").val($(this).attr('data-id')).parent().prev().find(".dropdown-label span").text($(this).text())
            var _td = $(this).parents("td").next()
            _td.find(".hide").removeClass('hide').end().find(".save-label").hide().end().next().find(".init").hide().prev().removeClass('hide')
            if (_td.find(".gray")[0]) {
                _td.find(".gray").show().prev().hide()
            }
         })
         //counter
         var _counter = controlModel.target.find(".counter-wrapper")
         _counter.find(".prev").unbind('click').click(function(){
            var _input = $(this).next()
            if (parseInt(_input.val()) <= 1) {
                _input.val(1)
            }else{
                _input.val((parseInt(_input.val())-1))
            }
         }).next().unbind('keyup').keyup(function(){
            var _input = $(this)
            if (isNaN(_input.val())) {
                _input.val("")
            }
         }).unbind('blur').blur(function(){
            var _input = $(this)
            if (isNaN(_input.val())) {
                _input.val("")
            } else{
                var _tmp = Math.abs(_input.val())
                _input.val(_tmp == 0 ? "" : _tmp)
            }
         }).next().unbind('click').click(function(){
            var _input = $(this).prev()
            _input.val((parseInt(_input.val())+1))
         })
         //applynew
         var _applynew = controlModel.target.find(".applynew")
         _applynew.unbind('click').click(function(){
             require("module/common/hc.popup.jquery")
             $("#applyControlModel").hcPopup({'width':'450'})
         })

    }

	controlModel.prototype.add = function(tmpl) {
		 controlModel.target.removeClass('hide').show().find("tbody").append($("#"+tmpl).html())
	}

	controlModel.prototype.edit = function(arg) {
        event = arguments.callee.caller.arguments[0] || window.event
        var _td  = $(event.target).parents('tr').eq(0).find("td").eq(0)
        var _label = _td.find('.save-label')
        $.each(_td.find("li"),function(){
            if ($(this).text() == _label.text()) {
                $(this).click()
                return false
            }
        })
        _td.find('.save-label').addClass('hide').removeClass('show').prev().css("display","inline-block").find(".dropdown-label span").text(_label.text()).end()
        _td = _td.next()
        _td.find('.save-label').hide().prev().css("display","inline-block").find("input").val(_td.find('.save-label').text())
        if (_td.find(".gray")[0]) {
            _td.find(".gray").show()
        }
        _td = _td.next()
        _td.find(".save").removeClass('hide').show().next().hide().next().show().next().hide()


    }

	 //刷卡标准 文本框输入绑定
    !(function(){
        $.each($(".form .tbl-list-tool-panle"),function(idx,it){

            var _this  = $(this)
            var _t1 = _this.find("input[type='text']").eq(0)
            var _t2 = _this.find("input[type='text']").eq(1)
            var _t3 = _this.find("input[type='text']").eq(2)
            var _c1 = _this.find("input[type='checkbox']").eq(0)
            var _c2 = _this.find("input[type='checkbox']").eq(1)

            _t1.bind("blur",function(){
                if (isNaN($(this).val().trim()) ) {
                    $(this).val(1)
                }
                //console.log(11)
            })
            _t2.bind("blur",function(){
                if (isNaN($(this).val().trim()) || parseInt($(this).val().trim()) > 100) {
                    $(this).val(1)
                }
            })
            _t3.bind("blur",function(){
                if (isNaN($(this).val().trim()) ) {
                    $(this).val(1000)
                }
            })

        })

    })()


     $(".valite-3").each(function(){
        var _radio = $(this).find("input[type='radio']:eq(0)")
        var _clear = $(this).find(".radio-label")
        _radio.click(function(){
            _clear.find("input[type='text']").val("").end().find("input[type='checkbox']").prop("checked",false).end().find(".error-div").hide()
        })
    })

    //刷卡标准 单独验证
    //返回 是否验证成功
    function CheckCardStandard(obj){
        var _flag  = true
        var _that  = obj
        var _radio = _that.find("input[type='radio']")
        var _t1    = _that.find("input[type='text']").eq(0)
        var _t2    = _that.find("input[type='text']").eq(1)
        var _t3    = _that.find("input[type='text']").eq(2)
        var _c1    = _that.find("input[type='checkbox']").eq(0)
        var _c2    = _that.find("input[type='checkbox']").eq(1)
        _t1.parents("tr").find(".total-error").hide()
        if (_radio.eq(0).prop("checked")) {
            _flag = true
        }else{
            _radio = _radio.eq(1)
            if (_radio.prop("checked")) {
                //count error div
                var _error_count = _t1.parents("td").next().find(".error-div").eq(0)
                if (_t1.val().trim() != "") {

                    if (isNaN(_t1.val())) {
                        _error_count.show()
                        _flag = false
                        _t1.focus().select()
                    }
                    else{
                        if (_t1.val().split(".").length > 1) {
                            _error_count.show()
                            _flag = false
                            _t1.focus().select()
                        }
                        else if (parseFloat(_t1.val()) < 1) {
                            _error_count.show()
                            _flag = false
                            _t1.focus().select()
                        }
                        else{
                            _error_count.hide()
                            var _arr = [true,true]
                            if (_c1.prop("checked")) {
                                if (!CheckCardSmipStep(_t2,false)) {
                                    _flag = false
                                }
                            }//first checkbox no checked
                            else{
                                _arr[0] = false
                            }
                            if (_c2.prop("checked")) {
                                if (!CheckCardSmipStep(_t3,true)) {
                                    _flag = false
                                }
                            }//second checkbox no checked
                            else{
                                _arr[1] = false
                            }
                            //必须两个都不成真说明一个都没有选择
                            if (!_arr[0] && !_arr[1] ) {
                                _flag = false
                                _t1.parents("tr").find(".total-error").show()
                            }
                        }// count has not decimal point
                    } //count is a num
                }//no count
                else{
                    _error_count.show()
                    _flag = false
                }
            }// no select

        }



        return _flag
    }

    function CheckCardSmipStep(obj,isInteger){
        var _flag = true
        var _t2   = obj
        if (isNaN(_t2.val())) {
            _flag = false
            _t2.focus().select().next().next().hide().next().show()
        }
        else{
            var _val  = parseFloat(_t2.val())
            if (_t2.val().trim() != "") {

                if (isNaN(_t2.val())) {
                    _t2.focus().select().next().next().hide().next().show()
                }else{
                    var _split = _t2.val().toString().split('.')
                    if ((_split.length >= 2 && _split[1].length > 1) ) {
                        _flag = false
                        _t2.focus().select().next().next().hide().next().show()
                    }
                    else{

                        if (isInteger) {
                            if (_val % 10 != 0 ) {
                                _flag = false
                                _t2.focus().select().next().next().hide().next().show()
                            }
                            else{
                                if (_val <= 0) {
                                    _flag = false
                                    _t2.focus().select().next().next().hide().next().show()
                                }else{
                                    _t2.next().next().next().hide()
                                }
                            }
                        }else{
                            if (_val <= 0) {
                                _flag = false
                                _t2.focus().select().next().next().hide().next().show()
                            }else{
                                _t2.next().next().next().hide()
                            }
                        }

                    }


                }

            }else{
                _t2.next().next().hide().next().show()
                _flag = false
                //console.log(_t2.next().next().hide().next()[0])
            }
        }
        return _flag
    }

    function CheckSubsidySituation(obj){
        var _flag  = true
        var _that  = obj
        var _radio = _that.find("input[type='radio']")
        var _t1    = _that.find("input[type='text']").eq(0)
        var _t2    = _that.find("input[type='text']").eq(1)
        var _c1    = _that.find("input[type='radio']").eq(2)
        var _c2    = _that.find("input[type='radio']").eq(3)
        var _error = $("#inputerror")
        _error.hide()
        if (_radio.eq(0).prop("checked")) {
            _flag = true
        }else{
            _radio = _radio.eq(1)
            if (_radio.prop("checked")) {
                //error div
                var _error1 = _t1.next().next()
                var _error2 = _t2.next().next()
                //
                var _arr = [true,true]
                if (_c1.prop("checked")) {
                    _arr[0] = false
                    if (_t1.val().trim() == "") {
                        _flag = false
                        _error1.show().next().hide()
                    }else{
                        if (isNaN(_t1.val())) {
                            _error1.show().next().hide()
                            _flag = false
                            _t1.focus().select()
                        }else{
                            if (_t1.val().toString().split(".").length == 2 || parseFloat(_t1.val()) < 0) {
                                _error1.show().next().hide()
                                _flag = false
                                _t1.focus().select()
                            }else{
                                _error1.hide().next().show()
                            }
                        }
                    }
                }
                else if (_c2.prop("checked")) {
                    _arr[1] = false
                    if (_t2.val().trim() == "") {
                        _flag = false
                        _error2.show().next().hide()
                    }
                    else{
                        if (isNaN(_t2.val())) {
                            _error2.show().next().hide()
                            _flag = false
                            _t2.focus().select()
                        }else{
                            if (_t2.val().toString().split(".").length == 2 || parseFloat(_t2.val()) < 0) {
                                _error2.show().next().hide()
                                _flag = false
                                _t2.focus().select()
                            }else{
                                _error2.hide().next().show()
                            }
                        }
                    }
                }
                if (_arr[0] && _arr[1]) {
                    _flag = false
                    _error.show()
                }

            }// no select
            else{
               _flag = false
               _error.show()
            }

        }

        return _flag
    }

    function bindCheckOver(obj){
        var _that  = obj
        var _radio = _that.find("input[type='radio']")
        var _t1    = _that.find("input[type='text']").eq(0)
        var _t2    = _that.find("input[type='text']").eq(1)
        var _t3    = _that.find("input[type='text']").eq(2)
        var _c1    = _that.find("input[type='checkbox']").eq(0)
        var _c2    = _that.find("input[type='checkbox']").eq(1)

        _c1.unbind('click').bind("click",function(){
            if (!$(this).prop("checked")) {
                _t2.val("")
            }
            _radio.eq(1).prop("checked",true)
        })

        _c2.unbind('click').bind("click",function(){
            if (!$(this).prop("checked")) {
                _t3.val("")
            }
            _radio.eq(1).prop("checked",true)
        })

        _t1.unbind('focus').focus(function(event) {
            _radio.eq(1).prop("checked",true)
        })

        _t2.unbind('focus').focus(function(event) {
            _radio.eq(1).prop("checked",true)
            _c1.prop("checked",true)
        })

        _t3.unbind('focus').focus(function(event) {
            _radio.eq(1).prop("checked",true)
            _c2.prop("checked",true)
        })
    }

    function initCheckOver(){
        var _v3  = $(".valite-3")
        bindCheckOver(_v3.eq(0))
        bindCheckOver(_v3.eq(1))
    }

    function bindSubsidy(obj){
        var _that  = obj
        var _radio = _that.find("input[type='radio']")
        var _t1    = _that.find("input[type='text']").eq(0)
        var _t2    = _that.find("input[type='text']").eq(1)
        var _c1    = _that.find("input[type='checkbox']").eq(0)
        var _c2    = _that.find("input[type='checkbox']").eq(1)

        _c1.unbind('click').bind("click",function(){
            if (!$(this).prop("checked")) {
                _t1.val("")
            }
            _radio.eq(1).prop("checked",true)
        })

        _c2.unbind('click').bind("click",function(){
            if (!$(this).prop("checked")) {
                _t2.val("")
            }
            _radio.eq(1).prop("checked",true)
        })

        _t1.unbind('focus').focus(function(event) {
            _radio.eq(1).prop("checked",true)
            _c1.prop("checked",true)
        })

        _t2.unbind('focus').focus(function(event) {
            _radio.eq(1).prop("checked",true)
            _c2.prop("checked",true)
        })

    }

    function initSubsidy(){
        var _panle = $(".tbl-list-tool-panle.valite-1")
        bindSubsidy(_panle.eq(0))
    }

    //填写辅助
    //刷卡标准
    initCheckOver()
    //补贴情况
    initSubsidy()

    //刷卡标准 next 验证
    $(".chargeStandard").click(function(){
        var _this = this
        var _arr  = [false,false]
        var _v3   = $(".valite-3")
        //刷卡标准单独验证
        $(".error-div").hide()
        _arr[0] = CheckCardStandard(_v3.eq(0))
        _arr[1] = CheckCardStandard(_v3.eq(1))

        if (_arr[0] == true && _arr[1] == true) {
             var _form  = $("form[name='next-form']")
             var options = {
                type: 'post',
                success: function(data) {
                    //console.log(data)
                    if(data.step == 7) {
                        var url = window.location.href
                        var site =url.substr(0,url.length-1);
                        window.location.href=site+9;
                    }
                    else if(data.error_code == 1){
                      if ($("#add-temp")[0]) {
                          window.location.reload()
                      }
                      else{
                          $("#tip-succeed").hcPopup({content:'恭喜，修改成功！',callback:function(){
                                window.location.reload()
                          }})
                      }
                    }else{
						$("#tip-error").hcPopup({content:'抱歉！修改失败，请重新尝试~'})
					}
                },
             }
             _form.ajaxForm(options).ajaxSubmit(options);
             require("vendor/jquery.form")

        }

    })

    //补贴情况 next 验证
    $(".subsidy").click(function(){
        require("vendor/jquery.form")
        var _this  = $(this)
        var _arr   = [false,false]
        var _panle = $(".tbl-list-tool-panle")
        _arr[0]    = CheckSubsidySituation(_panle.eq(0))

        if (_arr[0] == true) {
             var _form  = $("form[name='next-form']")
             var options = {
                type: 'post',
                success: function(data) {

                   if(data.step == 8) {
                        var url = window.location.href
                        var site =url.substr(0,url.length-1);
                        window.location.href=site+9;
                   }
                   else if (_this.hasClass('isadd')) {
                        var url = window.location.href
                        var site =url.substr(0,url.length-1);
                        window.location.href=site+9;
                   }else{
                       if(data.error_code ==1){
                           $("#tip-succeed").hcPopup({content:"操作成功",callback:function(){
                               // window.location.reload()
                           }})
                       }
                   }
                }
             }
             _form.ajaxForm(options).ajaxSubmit(options);

        }

    })

    //杂费删除部分
    controlModel.prototype.del = function(dl_zp_id) {

        event = arguments.callee.caller.arguments[0] || window.event
        var _target = $(event.target).parents('tr').eq(0)
         require("module/common/hc.popup.jquery")
         $("#delControlModel").hcPopup({'width':'450'})

        var _this = $(event.target)
        var type = $(_this).attr("data-type")
        var dealer_id = $(_this).attr("dealer")
        if (type == 'del-fees'){
             $("#delControlModel .delControlModel").unbind("click").click(function(){
                 $.ajax({
                         type: "post",
                         url: "/dealer/ajaxsubmitdealer/del-fees/",
                         data: {
                            dealer_id:dealer_id,
                             dl_zf_id:dl_zp_id,
                             _token:$("meta[name=csrf-token]").attr('content'),
                         },
                         dataType: "json",
                         success: function(data){
                            if (data.error_code == 0) {
                                $("#tip-error").hcPopup({content: '抱歉！删除失败，请重新尝试~'})
                            } else {
                                $("#delControlModel").hide()
                                var _tr = _this.parents("tr").eq(0)
                                var _tbl = _this.parents("table")
                                _tr.fadeOut(300, function() {
                                    _tr.remove()
                                    _tbl.find("tr.no-tr").remove()
                                    if (_tbl.find("tr.def-temp").length == 0) {
                                        _tbl.find("#temp-file").show()
                                    }
                                })
                            }
                         }
                   })

             })

        } else if(type == 'del-zengpin'){

            $("#delControlModel .delControlModel").unbind("click").click(function(){
                 $.ajax({
                         type: "post",
                         url: "/dealer/ajaxsubmitdealer/del-zengpin/",
                         data: {
                            dealer_id:dealer_id,
                             dl_zp_id:dl_zp_id,
                             _token:$("meta[name=csrf-token]").attr('content'),
                         },
                         dataType: "json",
                         success: function(data){
                            if (data.error_code == 0) {
                                $("#tip-error").hcPopup({content: '抱歉！删除失败，请重新尝试~'})
                            } else {
                                $("#delControlModel").hide()
                                var _tr  = _this.parents("tr").eq(0)
                                var _tbl = _this.parents("table")
                                _tr.fadeOut(300, function() {
                                    _tr.remove()
                                    _tbl.find("tr.no-tr").remove()
                                    if (_tbl.find("tr.def-temp").length == 0) {
                                        _tbl.find("#temp-file").show()
                                    }
                                })
                            }
                         }
                    })

                 })

        }

    }




	controlModel.prototype.cancel = function(arg) {
		event = arguments.callee.caller.arguments[0] || window.event
	     //$(event.target).parents('tr').eq(0).fadeOut(500)
        var _td  = $(event.target).parents('tr').eq(0).find("td").eq(0)
        var _droplabel = _td.find(".dropdown-label span").text()
        _td.find('.save-label').addClass('show').prev().css("display","none").text(_td.find('.save-label').text()).end()
        _td = _td.next()
        _td.find('.save-label').show().prev().css("display","none").find("input").val(_td.find('.save-label').text())//.next().hide()
        _td.find(".error-div").hide()
        if (_td.find(".gray")[0]) {
            _td.find(".gray").hide()
        }
        _td = _td.next()
        var evet = $(event.target).parents('tr').eq(0).find('td').eq(2);
        evet.find("a:odd").css("display","inline");
        evet.find("a:even").css("display","none");


	}
	var _target = $("#controlModel")
    var _model  = new controlModel({target:_target})

	$(".controlModelAdd").click(function(){
		 _model.init(_target)
		 _model.add("controlModel-tmpl")
		 _model.eventBind()
         $("#temp-file").css('display','none');
	})

	//save

	_model.target.delegate(".zengpin","click",function(){
	 	 //_model.save(dl_zp_id)
         var _this     = $(this)
         var dealer_id = $(this).attr("data-id")
         var num       = $("#ttt").val()
         var dl_zp_id  = $(".active").attr("data-id")
         $.ajax({
             type: "post",
             url: "/dealer/ajaxsubmitdealer/addzengpin/",
             data: {
                dl_zp_id:dl_zp_id,
                dealer_id:dealer_id,
                num:num,
                daili_dealer_id:$(this).attr('daili-dealer-id'),
                _token:$("meta[name=csrf-token]").attr('content'),
             },
             dataType: "json",
             success: function(data){
                 //console.log(data)
                 if(data.error_code==0){
                     $("#tip-error").hcPopup({content:''+data.error_msg+''})
                 }else{
                    var _td  = _this.parents('tr').eq(0).find("td").eq(0)
                    var _txt = _td[0].innerText
                    _td.find('.save-label').attr("id","comm").addClass('show').text(_txt).prev().css("display","none").find(".dropdown-label span").text(_txt).end()
                    _td = _td.next()
                    _td.find('.save-label').show().text(_td.find("input").val()).prev().css("display","none").find("input")//.next().hide()
                    if (_td.find(".gray")[0]) {
                        _td.find(".gray").hide()
                    }
                    _td = _td.next()
                    _td.find('a').eq(0).hide().next().show().next().hide().next().show()
                    _td.find(".error-div").hide()
                    _this.attr("data-type","edit-zengpin").attr("iscounter",1).attr("data-id",data.id).removeClass('zengpin').addClass('zpxiugai')
                 }
             }
	     })
     })
	//edit
	_model.target.delegate(".edit","click",function(){
		 _model.init(_target)
	 	 _model.edit()
	 	 _model.eventBind()
	})
	//cancel
	_model.target.delegate(".cancel","click",function(){
        var comm = $(this).parents('tr').find('#comm').text()
        if (comm.trim() == "") {
             event = arguments.callee.caller.arguments[0] || window.event
             var _this = $(event.target)
             var _tr   = _this.parents("tr").eq(0)
             var _tbl  = _this.parents("table")
             _tr.fadeOut(300,function(){
                 _tr.remove()
                 _tbl.find("tr.no-tr").remove()
                 if (_tbl.find("tr.def-temp").length == 0 ) {
                    _tbl.find("#temp-file").show()
                 }
             })

        } else {
	 	 _model.cancel()
        }
	})
	//del
	_model.target.delegate(".del","click",function(){
		var dl_zp_id = $(this).attr('data-id');
	 	 _model.del(dl_zp_id)
	})

	$(".sub-apply-new").click(function(){
		var _form  = $("form[name='addServiceSpecialistForm']")
		var _input = _form.find("input[type='text']")
		if (_input.val().trim() == "") {
			errorshowhide(_input.next())
		}else{
            var options = {
                type: 'post',
                success: function(data) {
                 if (data.error_code == 0) {
					 $("#tip-error").hcPopup({content:'抱歉！提交失败，请重新提交~'})
                 }else{
					$("#tip-info-success").hcPopup({})
					_form.find("input[name=title]").val('');
					$("#applyControlModel").css('display','none');
				 }

                },
             }
                _form.ajaxSubmit(options)
		}
	})


   $(document).delegate(".zpxiugai","click",function(){

       var _this = $(this)
       var type  = $(this).attr("data-type")
       var _tr   = _this.parents('tr')
        //console.log(type)
       if (type == 'edit-zengpin') {

          var dealer_id = _this.attr("data-did")
          var zp_id     = _this.parents('tr').find("td").find("input[name=zengpin]").val()
          var num       = _this.parents('tr').find("td").eq(1).find("input[name=zengpin_num]").val() || _this.parents('tr').find("td").eq(1).find("input[name=zengpin_nums]").val()
          var dl_zp_id  = _this.attr("data-id")
          var _flag     = true
          console.log("num:"+num)
          if (!isPositiveInteger(num)  ) {
            _flag = false
            _tr.find(".error-div").show().addClass('juhuang')
            if (_tr.find(".gray")[0]) {
                _tr.find(".gray").hide()
            }
            console.log("isPositiveInteger")
          }
          else{
            if (!_this.attr("iscounter")) {
                if (parseFloat(num) % 100 != 0) {
                    _tr.find(".error-div").show().addClass('juhuang')
                    if (_tr.find(".gray")[0]) {
                        _tr.find(".gray").hide()
                    }
                }
            }
            console.log("iscounter")
          }
          console.log(_flag)
          if (!_flag) {
             return
          }

          $.ajax({
             type: "post",
             url: "/dealer/ajaxsubmitdealer/editzengpin/",
             data: {
                dl_zp_id:dl_zp_id,
                zp_id:zp_id,
                dealer_id:dealer_id,
                num:num,
                _token:$("meta[name=csrf-token]").attr('content'),
             },
             dataType: "json",
             success: function(data){
                 console.log(data)
                 if (data.error_code==0) {
                    $("#tip-error").hcPopup({content:''+data.error_msg+'',callback:function(){
                        //window.location.reload()
                    }})

                 } else {
                    _this.next().next().click()
                    //$("#tip-succeed").hcPopup({content:''+data.error_msg+'',callback:function(){
                    //window.location.reload()
                    //}})
                 }
             }

          })

       }
       else if(type=='editzafei'){

            var _this     = $(this)
            var dealer_id = $(this).attr("data-did")
            var id        = $(this).attr("data-id")
            var price     = $(this).parents('tr').find('td').eq(1).find("input[name=fees_id]").val()
            var other_id  = $(this).parents('tr').find('td').find("input[name=zafei]").val()
            if (!isPositiveInteger(price) || parseFloat(price) % 100 != 0) {

                _tr.find(".error-div").show().addClass('juhuang')
                if (_tr.find(".gray")[0]) {
                    _tr.find(".gray").hide()
                }
                return
            }
            $.ajax({
                 type: "post",
                 url: "/dealer/ajaxsubmitdealer/editzafei/",
                 data: {
                    other_id:other_id,
                    id:id,
                    dealer_id:dealer_id,
                    price:price,
                    _token:$("meta[name=csrf-token]").attr('content'),
                 },
                 dataType: "json",
                 success: function(data){
                     if (data.error_code==0) {
                        $("#tip-error").hcPopup({content:data.error_msg,callback:function(){
                            //window.location.reload()
                        }})
                     } else {
                        _this.next().next().click()
                       // $("#tip-succeed").hcPopup({content:'恭喜,修改成功!',callback:function(){
                            window.location.reload()
                       // }})
                     }
                 },
                 error: function(data){
                    _this.next().next().click()
                    $("#tip-error").hcPopup({content:'保存成功!',callback:function(){

                    }})

                 }
            })
       }

       _tr.find(".error-div").hide()

    })

	function errorshowhide(obj){
        obj.show(function(){
            setTimeout(function(){
                obj.fadeOut(300)
            },3000)
        })
    }


	module.exports = {
        init:function(){

        }
        ,
        initControlModel:function(){
            var _tbl = $("#controlModel")
            if (_tbl.find("tr").length <= 2) {
                _tbl.addClass('hide')
            }
        }
    }


    //杂费标准
        _model.target.delegate(".zafei","click",function(){

            event         = arguments.callee.caller.arguments[0] || window.event
            var _this     = $(event.target)
            var dealer_id = $(_this).attr('dealer-id');
            var id        = _this.parents('tr').find("input[name=insuranceCompany]").val();
            var price     = _this.parents('tr').find("input[name=long_price]").val();
            var sort      = _this.parents("input[name=zafei_name]").val();
            //isPositiveInteger

            if (!isPositiveInteger(price) || price % 100 != 0) {
                var _tr = _this.parents('tr')
                _tr.find(".error-div").show().addClass('juhuang')
                if (_tr.find(".gray")[0]) {
                    _tr.find(".gray").hide()
                }
                //$("#tip-error").hcPopup({content:'金额输入有误'})
                return
            }
            _this.parents('tr').find(".error-div").hide()
            $.ajax({
             type: "post",
             url: "/dealer/ajaxsubmitdealer/add-fees/",
             data: {
                dealer_id:dealer_id,
                id:id,
                price:price,
                daili_dealer_id:$(_this).attr('daili-dealer-id'),
                _token:$("meta[name=csrf-token]").attr('content'),
             },
             dataType: "json",
             success: function(data){
                 if(data.error_code==1){
                     //$("#tip-succeed").hcPopup({content:'添加成功!',callback:function(){
                        window.location.reload()
                    // }})
                 }else{
                    $("#tip-error").hcPopup({callback:function(){
                        //window.location.reload()
                    }})
                 }
             }
        })

    })


    //竞争分析
    $(".tbl-competitive .edit-new").click(function(){
    	var _tr = $(this).parents("tr").eq(0)
    	var _td = _tr.find("td").eq(0)
    	_td.find(".form-txt.psr.inlineblock").show().next().hide()
        var _html = _td.find(".save-label").html()
        var _arr  = []
        _arr.push(_html.slice(0, 2),_html.slice(2))
        _td.find("input[type='hidden']").eq(0).val(_arr[0]).end().eq(1).val(_arr[1])
        _td.find(".dropdown-label span").html(_arr[0].trim()+_arr[1].trim())

        var _flag = false

    	_td = _td.next()
    	_td.find(".btn-group").show().next().hide()
        _td.find(".dropdown-label span").html(_td.find(".save-label").html())
    	$(this).hide().prev().removeClass('hide').css("display","inline-block")
    })


    $(document).delegate(".tbl-competitive dl.test dd", 'click', function(event) {
        $(this).parents("td").next().find(".dropdown-label span").text("--请选择--").end().next().find("span").text("")
    })

    $(".province-wrapper").click(function(){
    	var _this   = $(this)
    	var _citydd = _this.next(".dropdown-menu").show().find("dl").eq(0).find("dd").unbind("click").click(function(){
    		var _dd = $(this)
    		_this.next().find("input[name='province']").val($(this).text())
    		_this.find(".dropdown-label span").text($(this).text())
			_dd.parent().prev().find("span").eq(0).removeClass('cur-tab').next().addClass('cur-tab')
    		_dd.parent().hide().next().empty().show().append(function(){
				var _html = ""
				$.ajax({
		             type: "post",
		             url: "/dealer/ajaxsubmitdealer/citylist",
		             async:false,
		             data: {
		                id:_dd.attr("data-id"),
                         _token:$("meta[name=csrf-token]").attr('content')
		             },
		             dataType: "json",
		             success: function(data){

		                 $.each(data,function(idx,item){
		                 	_html += "<dd data-id='"+$(item)[0].id+"'>"+$(item)[0].name+"</dd>"
		                 })

		             }
		        })

    			 return _html
    		})

    	}).end().next().unbind().delegate("dd","click",function(){
    		var _dd = $(this)
    		_this.next(".dropdown-menu").hide()
    		_this.next().find("input[name='city']").val($(this).text())
    		_dd.parent().hide().prev().show().prev().find("span").eq(1).removeClass('cur-tab').prev().addClass('cur-tab')
    		_this.find(".dropdown-label span").text(
    			_this.next().find("input[name='province']").val()+_this.next().find("input[name='city']").val()
    		)
    		var _html = ""
			$.ajax({
	             type: "post",
	             url: "/dealer/ajaxsubmitdealer/list",
	             data: {
	                id:_dd.attr("data-id"), //city-id查询dealer list
                    _token:$("meta[name=csrf-token]").attr('content')
	             },
	             dataType: "json",
	             success: function(data){
	                 $.each(data,function(idx,item){
	                 	_html += "<li data-address='"+$(item)[0].d_yy_place+"' data-id='"+$(item)[0].d_id+"' data-shi='"+$(item)[0].d_shi+"'><a><span>"+$(item)[0].d_name+"</span></a></li>"
	                 })
	                 _dd.parents("td").eq(0).next().find(".dropdown-menu").empty().append(_html).unbind().delegate('li', 'click', function() {
		            	$(this).parent().next().val($(this).text())
		            	$(this).parents(".btn-group").eq(0).find(".dropdown-label span").text($(this).text())
		            	$(this).parents('td').eq(0).next().find("span").text($(this).attr("data-address"))
                        var _city = _this.parents("tr").find("td:last").find(".city")
                        _city.attr('data-id',$(this).attr('data-id'))
                             .attr('data-shi',$(this).attr('data-shi'))
		            })

	             }
	        })

            /*_dd.parents("tr").eq(0).find("td:last").find(".save").click(function(){

            	var _tr = $(this).parents("tr").eq(0)
            	var _td = _tr.find("td").eq(0)
            	_td.find(".form-txt.psr.inlineblock").hide().next().show().text(_td.find(".dropdown-label span").text())
            	_td = _td.next()
            	_td.find(".btn-group").hide().next().show().text(_td.find(".dropdown-label span").text())
            	$(this).hide().next().removeClass('hide').show()

            })*/



    	})
    })

    function displayNone(obj){
        var _tr = obj.parents("tr").eq(0)
        var _td = _tr.find("td").eq(0)
        _td.find(".form-txt.psr.inlineblock").hide().next().show().text(_td.find(".dropdown-label span").text())
        _td = _td.next()
        _td.find(".btn-group").hide().next().show().text(_td.find(".dropdown-label span").text())
        obj.hide().next().removeClass('hide').show()
    }

    $(".tac .city").click(function() {
        event = arguments.callee.caller.arguments[0] || window.event
        var _this     = $(this)
        var _tr       = _this.parents("tr")
        var _td       = _tr.find("td")
        var _flag     = true
        var _province = _td.eq(0).find("input:hidden[name='province']")
        var _city     = _td.eq(0).find("input:hidden[name='city']")
        var _dealer   = _td.eq(1).find(".dropdown-label span")

        if (_province.val().trim()=="" || _city.val().trim()=="" || _dealer.text().trim().indexOf("请选择") != -1) {
            _flag = false
        }

        if (!_flag) {
            $("#tip-error").hcPopup({
                content: '请选择地区和经销商名称!'
            })
            return
        }
        var dealer_id = $(".dl_types").find("input[name=dealer]").val()
        var dl_type   = $(this).attr("data-type")

        /*console.log("_daili_dealer_id:"+_daili_dealer_id)
        console.log("$(this).attr(\"data-id\"):"+$(this).attr("data-id"))
        console.log("$(this).attr(\"data-shi\"):"+$(this).attr("data-shi"))
        console.log("dl_type:"+dl_type)
        console.log("dealer_id:"+dealer_id)
        return*/

        $.ajax({
            type: "post",
            url: "/dealer/ajaxsubmitdealer/analysis/",
            data: {
                id: _daili_dealer_id,
                d_id: _this.attr("data-id"),
                d_shi: _this.attr("data-shi"),
                dl_type: dl_type,
                dealer_id: dealer_id,
                _token: $("meta[name=csrf-token]").attr('content')
            },
            dataType: "json",
            success: function(data) {

                if (data.error_code == 1) {
                    $("#tip-error").hcPopup({
                        content: '' + data.error_msg + '',
                        callback:function(){
                            displayNone(_this)
                        }
                    })
                    _this.attr("is-add","1")

                } else {

                    $("#tip-error").hcPopup({
                        content: '' + data.error_msg,
                        callback: function() {
                            //window.location.reload()
                        }
                    })
                }
            }

        })
    })

    //基本资料 修改
    $(".base-info-edit").click(function(){
        $(this).parent().hide().prev().prev().hide()
        $(".custom-form-tbl").removeClass('hide')
    })
    //基本资料 取消修改
    $(".base-info-chance").click(function(){
        $(".custom-info-tbl").show().next().next().show()
        var _account = $(".custom-form-tbl").find("input[name=\"account\"]")
        $(".custom-form-tbl").addClass('hide')
        _account.val(_account.attr("data-def"))
    })
    //基本资料 删除
    $(".base-info-del").click(function(){
        //弹窗
         require("module/common/hc.popup.jquery")
         var win = $("#delInfoWin");
         $("#delInfoWin").hcPopup({'width':'450'})
         $("#delInfoWin").find(".btn").eq(0).click(function(){
            $.ajax({
                 type: "post",
                 url: "/dealer/ajaxsubmitdealer/del-dealer",
                 data: {
                    id:$(this).attr("data-id"),
                    _token:$("meta[name=csrf-token]").attr('content')

                 },
                 dataType: "json",
                 success: function(data){
                    win.hide();
                     if (data.error_code==1) {
                        alert(data.msg);window.location.href='/dealer/member_info';
                     }
                 }
            })
         })
    })

    //基本资料 修改提交验证
    $(".SubEditDealersForm").click(function(){

           //hfbrand  province  city hfdealers txt-dealers-shot-name
           var _from       = $("form[name='edit-dealers-form']")
           var _bank       = _from.find("input[name='bank']")
           var _account    = _from.find("input[name='account']")
           var _txtcode    = _from.find("input[name='txtcode']")
           var id          = _from.find("input[name='id']").val()
           var type        = _from.find("input[name='type']").val()
           var _regAccount = /^(\d{16}|\d{19})$/
           var _reg        = /^[a-zA-Z0-9]{18}$/ //选填 统一社会信用代码由18位数字或者字母

           if(_txtcode.val() !='' && !_reg.test(_txtcode.val())){
              errorshowhide(_txtcode.next())
           }
           else if(_account.val() !='' && !_regAccount.test(_account.val())){
              errorshowhide(_account.next())
           }
           else{

             $.ajax({
             type: "post",
             url: "/dealer/ajaxsubmitdealer/save-step/"+id,
             data: {
                id:id,
                step:0,
                txtcode:_txtcode.val(),
                bank:_bank.val(),
                account:_account.val(),
                type:type,
                _token:$("meta[name=csrf-token]").attr('content')
             },
             dataType: "json",
             success: function(data){

                 if(data.error_code == 1 ){
                    $("#tip-succeed").hcPopup({content:"修改成功",callback:function(){
                        window.location.reload()
                    }})
                 }
                 if(data.step) {
                    var url   = window.location.href
                    var leng  = url.length-1
                    url       = url.substr(0,leng)
                    window.location.href = url + 1
                 }

              },error:function(){

              }


        })
   }
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

    //
    $(".btn-jquery-event").delegate('li', 'click', function(event) {
    	var _this   = $(this)
    	var _parant = _this.parents(".btn-group")
    	_this.addClass('active').siblings().removeClass("active")
    	_parant.find("input[type='hidden']").val(_this.text());
    	_parant.find(".dropdown-label span").text(_this.text());

    })
    //新增车型
    $(".add-carmodl").delegate('li', 'click', function(event) {
       var _this   = $(this)
        var _parant = _this.parents(".btn-group")
        _this.addClass('active').siblings().removeClass("active")
        _parant.find("input[type='hidden']").val(_this.text());
        var gc_id_3 = $(".dropdown-cars-standard").find(".active").attr("data-id");
        _parant.find("input[name='gc_id_3']").val(gc_id_3);
        _parant.find(".dropdown-label span").text(_this.text());

    })



    $(".btn-search-car").delegate('li', 'click', function(event) {
        var _this   = $(this)
        var _parant = _this.parents(".btn-group")
        _this.addClass('active').siblings().removeClass("active")
        _parant.find("input[type='hidden']").val(_this.text())
        _parant.find("input[name='id']").val(_this.find("span").attr("data-id"))
        _parant.find(".dropdown-label span").text(_this.text())
        $("#forms").submit()

    })
    //新增常用车型 车系和车型规格的点击事件
    //dropdown-cars-demio dropdown-cars-standard
    $(".dropdown-cars-demio li").click(function(){
    	var _this = $(this)
    	$.ajax({
             type: "post",
             url: "/dealer/ajaxcarmodel/list",
             data: {
                id:_this.attr("data-id"),
                _token:$("meta[name=csrf-token]").attr('content')
             },
             dataType: "json",
             success: function(data){
                 var _html = ""
                 $.each(data,function(){
                 	_html += "<li data-id='"+$(this)[0].gc_id+"'><a><span>"+$(this)[0].gc_name+"</span></a></li>"
                 })
                 $(".dropdown-cars-standard").find("li").remove().end().append(_html)
             }

        })
    })

    function isempty(obj){
        return $.trim(obj.val()) == ""
    }

    $(".dropdown-cars-standard").delegate('li', 'click', function(event) {
    	var _this = $(this)
        $("#copys").val(_this.attr("data-id"))

    	$.ajax({
             type: "post",
             url: "/dealer/ajaxcarmodel/listAll",
             data: {
                gc_id_3:_this.attr("data-id"),
                //gc_id_3:1081,
                _token:$("meta[name=csrf-token]").attr('content'),
                dealer_id:$(".content-wapper").find("input[name=dealer_id]").val()
             },
             dataType: "json",
             success: function(data) {
              $(".guide-price").html(parseFloat(data.price.value).formatMoney());
              $(".vehicle").text(data.price.vehicle_model);

              if (data.check == 'true') {
                $(".check_hint").removeClass('hide').show();
                $(".btn-new-jixu").attr('disabled','true')
                $(".btn-new-jixu").unbind( "click" );


              } else {
                $(".check_hint").removeClass('show').hide()
                $(".btn-new-jixu").removeAttr('disabled')
                $(".btn-new-jixu").bind("click",function(){
                     var _form  = $("#forms");
                     _form.submit();
                      });
                   }
                }
             })
    })

    $(".btn-new-jixu.btn-continue").click(function(){
        var _flag = true
        $.each($("input[type='hidden']"),function(){
            if ($(this).val().trim() == "") {
                _flag = false
            }
        })
        if (!_flag) {
            $("#tip-error").hcPopup({content:'请选择车系和车型规格!'})
        }

    })



    //新增常用车型
    $(".btn-new-offerings").click(function(){
    	var _this = $(this)
        var _flag = true
        var _form = $("#forms")
  		var _tr   = _form.find("tr").slice(1)

  		//
  		$.each($(".new-offerings-drop-valite"),function(index,item){
  			var _input = $(item).find("input[type='hidden']")

  			if (isempty(_input)) {
  				_flag = false
  				errorshowhide($(item).next())
  			}
  		})

  		if (!_flag) {
  			return
  		}

        $.each(_tr,function(index,item){
        	var _input = $(item).find("input[type='text']:not([name*=_cs_serial])")
        	$.each(_input,function(idx,it){
	        	var _inputcur = $(it)
	        	if (_inputcur.val().trim() == "") {
	        		errorshowhide(_inputcur.next())
	        		_flag = false
	        	}else{
	        		if (_inputcur.hasClass('valite')) {
	        			if (_inputcur.hasClass('money-valite')) {
	        				if (isNaN(_inputcur.val())) {
				        		errorshowhide(_inputcur.next())
				        		_flag = false
			        		}
			        	}

		        	}
	        	}

	        })

        })

        if (_flag) {
            var options = {
                success: function (data) {
                    if (data.error_code ==1) {
                        window.location.href='/dealer/carmodel/'+data.error_msg;
                    } else {
                        alert('添加失败,重复添加');
                        return false;
                    }

                }
                ,
                beforeSubmit:function(){}
                ,
                clearForm:false
            }
            // ajaxForm
            _form.ajaxForm(options)
            // ajaxSubmit
            _form.ajaxSubmit(options)
        }
    })



    $(".addNoOriginalGoods").click(function(){
        require("module/common/hc.popup.jquery")
        var _win = $("#addNoOriginalGoods")
        _win.hcPopup({'width':'550'})
        var _canAdd = true
        _win.find(".do").unbind('click').bind("click",function(){
            if (_canAdd) {
                var _form  = $("form[name='addNoOriginalGoodsForm']")
                var _flag = true
                $.each(_form.find("input[type='text']"),function(){
                    if (isempty($(this))) {
                        if ($(this).attr("name")!="model") {
                            errorshowhide($(this).next())
                            _flag = false
                        }
                    }
                    else if ($(this).hasClass('valite-money')) {
                        var _val = $(this).val()
                        if (isNaN(_val)) {
                            errorshowhide($(this).next().next())
                            _flag = false
                        }else if (parseFloat(_val) < 0) {
                            errorshowhide($(this).next().next())
                            _flag = false
                        }else if (_val.split('.').length == 2) {
                            if (_val.split('.')[1].toString().length > 2) {
                                errorshowhide($(this).next().next())
                                _flag = false
                            }
                        }
                    }
                })
                if (_flag) {
                    //非原厂选装精品
                    var _label    = _win.find(".dropdown-label")
                    var brand = _form.find("input[type='text']").eq(0).val()
                    var title = _form.find("input[type='text']").eq(1).val()
                    var serial = _form.find("input[type='text']").eq(3).val()
                    var model = _form.find("input[type='text']").eq(2).val()
                    var price = _form.find("input[type='text']").eq(4).val()
                    var max_num = _label.eq(0).find("span").text()
                    var has_num = _label.eq(1).find("span").text()
                    var staple_id = $("#forms").find("input[name='staple_id']").val()
                    var dealer_id = _form.find("input[name='dealer_id']").val()
                    var edit = $(".tbl-goods").find("input[name=gc-id-3]").val()
                    var add =$(".dropdown-cars-standard").find(".active").attr("data-id")
                    if (typeof(edit)=='undefined') {
                        var gc_id_3= add
                    } else {
                        var gc_id_3 = edit
                    }
                    $.ajax({
                         type: "post",
                         url: "/dealer/ajaxcarmodel/add",
                         data: {
                               gc_id_3:gc_id_3,
                               dealer_id:dealer_id,
                               brand:brand,
                               title:title,
                               serial:serial,
                               model:model,
                               price:price,
                               has_num:has_num,
                               max_num:max_num,
                               staple_id:staple_id,
                               _token:$("meta[name=csrf-token]").attr('content')

                         },
                         dataType: "json",
                         beforeSend:function(){
                            _canAdd = false
                         },
                         success: function(data){
                            //console.log(data)
                            $("#remod").hide();
                            _win.hide()


                            var _goods    = ""
                            var _goodstpl = $("#GoodsLoading").html()
                            var _input    = _win.find("input[type='text']")
                            var _label    = _win.find(".dropdown-label")
                            var _price    = _input.eq(4).val()
                            if (_price.indexOf(".") != -1 ) {
                                var _split = _price.split(".")
                                if (_split[1].length == 1) {
                                    _price = _price + "0"
                                }
                                else if (_split[1].length == 2) {
                                    _price = _price.toString()
                                }
                                else if (_split[1].length > 2) {
                                    _price = _price.toFixed(2)
                                }
                            }else{
                                _price = _price + ".00"
                            }
                            _goods += _goodstpl.replace("{0}",_input.eq(0).val())
                                               .replace("{1}",_input.eq(1).val())
                                               .replace("{2}",_input.eq(2).val())
                                               .replace("{3}",_input.eq(3).val())
                                               .replace("{4}",_price)
                                               .replace("{5}",_label.eq(0).find("span").text())
                                               .replace("{6}",_label.eq(1).find("span").text())
                                               .replace("{id}",data.id)
                                               .replace("{id}",data.id)
                             var _tbl_goods = $(".tbl-goods")
                             _tbl_goods.append(_goods)
                             _form.find("input[type='text']").val('')
                             _form.find(".dropdown-label").eq(0).find("span").text('0').end().end().eq(1).find('span').text("不设限")
                             _canAdd = true
                         }
                         ,
                         error:function(){
                            _canAdd = true
                         }
                    })

                }
            }
        })
    })

    $(".tbl-goods").delegate('.edit-original-goods', 'click', function(event) {
    	//console.log('start')
        require("module/common/hc.popup.jquery")
    	var _this = $(this)
    	var _win  = $("#editNoOriginalGoods")
    	_win.hcPopup({'width':'550'})
    	_win.find(".do").unbind('click').bind("click",function(){
            var _form  = $("form[name='editNoOriginalGoodsForm']")
            var _flag = true
            $.each(_form.find("input[type='text']"),function(){
            	if (isempty($(this))) {
            		if ($(this).attr("name")!="model") {
                        errorshowhide($(this).next())
                        _flag = false
                    }
            	}
            	else if ($(this).hasClass('valite-money')) {
                    var _val = $(this).val()
            		if (isNaN(_val)) {
            			errorshowhide($(this).next().next())
            			_flag = false
            		}else if (parseFloat(_val) < 0) {
                        errorshowhide($(this).next().next())
                        _flag = false
                    }else if (_val.split('.').length == 2) {
                        if (_val.split('.')[1].toString().length > 2) {
                            errorshowhide($(this).next().next())
                            _flag = false
                        }

                    }

            	}
            })
            if (_flag) {
                var _label = _win.find(".dropdown-label")
                var brand = _form.find("input[type='text']").eq(0).val()
                var title = _form.find("input[type='text']").eq(1).val()
                var model = _form.find("input[type='text']").eq(2).val()
                var serial = _form.find("input[type='text']").eq(3).val()
                var price = _form.find("input[type='text']").eq(4).val()
                var staple_id = $("#forms").find("input[name='staple_id']").val()
                var has_num = _label.eq(1).find("span").text()
                var max_num = _label.eq(0).find("span").text()
                var dealer_id = $("#d_id").val()
                var gc_id_3 = $(".tbl-goods").find("input[name=gc-id-3]").val()
                var gc_id_bak = $(".dropdown-cars-standard").find(".active").attr("data-id")
                if (typeof(gc_id_3) == 'undefined') {
                     gc_id_3 = gc_id_bak
                } else {
                     gc_id_3 = gc_id_3
                }
                var id = _this.attr("data-id")
                //console.log("before")
                $.ajax({
                    type: "post",
                    url: "/dealer/ajaxcarmodel/edit",
                    data: {
                        gc_id_3: gc_id_3,
                        id: id,
                        dealer_id: dealer_id,
                        brand: brand,
                        title: title,
                        serial: serial,
                        price: price,
                        has_num: has_num,
                        max_num: max_num,
                        model: model,
                        staple_id: staple_id,
                        _token: $("meta[name=csrf-token]").attr('content')
                    },
                    dataType: "json",
                    success: function(data) {
                        _win.hide()
                        $("#tip-succeed").hcPopup({
                            content: '修改成功',
                            callback: function() {
                                //window.location.reload()
                            }
                        })
                        var _tr = _this.parents("tr")
                        var _td = _tr.find("td")
                        var _price    = price
                        if (_price.indexOf(".") != -1 ) {
                            var _split = _price.split(".")
                            if (_split[1].length == 1) {
                                _price = _price + "0"
                            }
                            else if (_split[1].length == 2) {
                                _price = _price.toString()
                            }
                            else if (_split[1].length > 2) {
                                _price = _price.toFixed(2)
                            }
                        }else{
                            _price = _price + ".00"
                        }
                        _td.eq(0).find("span").text(brand)
                        _td.eq(1).find("span").text(title)
                        _td.eq(2).find("span").text(model)
                        _td.eq(3).find("span").text(serial)
                        _td.eq(4).find("span").text("￥"+_price)
                        _td.eq(5).find("span").text(max_num)
                        _td.eq(6).find("span").text(has_num)
                        //console.log("success")
                        //console.log(_td[0])

                    },
                    error: function() {
                        _win.hide()
                        $("#tip-error").hcPopup({
                            content: '修改失败'
                        })
                    }

                })

            }

        })

          var _input   = _win.find("input[type='text']")
          var _public = _this.parents('tr').find('td')
         //品牌 名称 都是字段名称
         _input.eq(0).val(_public.eq(0).text())
         _input.eq(1).val(_public.eq(1).text())
         _input.eq(2).val(_public.eq(2).text())
         _input.eq(3).val(_public.eq(3).text())
         _input.eq(4).val(_public.eq(4).text().replace("￥",""))
         _win.find(".dropdown-label:eq(0) span").text(_public.eq(5).text())
         _win.find(".dropdown-label:eq(1) span").text(_public.eq(6).text())

    })




    //删除
     $(".tbl-goods").delegate('.del-original-goods', 'click', function(event) {
    	require("module/common/hc.popup.jquery")
    	var _this = $(this)
    	var gc_id_3 = $(".tbl-goods").find("input[name=gc-id-3]").val()
        var gc_id_bak = $(".dropdown-cars-standard").find(".active").attr("data-id")
        if (typeof(gc_id_3)=='undefined') {
                var gc_id_3= gc_id_bak
            } else {
                var gc_id_3 = gc_id_3
            }
    	var _win  = $("#delNoOriginalGoods")
    	_win.hcPopup({'width':'400'})
    	_win.find(".do").unbind("click").click(function(){
        	 $.ajax({
	             type: "post",
	             url: "/dealer/ajaxcarmodel/del",
	             data: {
	             	  gc_id_3:gc_id_3,
	             	  dealer_id:$("#d_id").val(),//_this.attr("dealer-id"),
	                 id:_this.attr("data-id"),
	                _token:$("meta[name=csrf-token]").attr('content')
	             },
	             dataType: "json",
	             success: function(data){
	                 //do
	                 _win.hide()
                     var _tr = _this.parents('tr')
	                 _tr.fadeOut('300',function(){
                        _tr.remove()
                        if ($(".def-temp").length == 0) {
                            $("#remod").show()
                        }
                     })
                     //def-temp
                      /*$("#tip-succeed").hcPopup({content:'删除成功',callback:function(){
                         //window.location.reload()
                     }})*/
	             }
	        })
        })
    })

    $(".del-common-models").click(function(){
    	require("module/common/hc.popup.jquery")
    	var _this  = $(this)
    	var _isdel = _this.attr("model-isdel")
    	//判断是否具有“有效报价”
    	var _win   = _isdel == 0 ? $("#delCommonModels") : $("#delNoCommonModels")
    	_win.hcPopup({'width':'400'})
    	_win.find(".do").unbind("click").click(function(){
        	 $.ajax({
	             type: "post",
	             url: "/dealer/ajaxcarmodel/del_carmodel",
	             data: {
	                staple_id:_this.attr("data-id"),
                    _token:$("meta[name=csrf-token]").attr('content')
	             },
	             dataType: "json",
	             success: function(data){
	                 _win.hide()
                     if (data.error_code == 0) {
                       var _tr = _this.parents('tr')
                     _tr.fadeOut('300',function(){
                        _tr.remove()
                        if ($(".def-temp").length == 0) {
                            $(".del-row").show()
                        }
                     })
                     }else{
                        var win = $("#delNoCommonModels")
                        win.hcPopup({'width':'400'})
                     }

                     //$("#tip-error").hcPopup({content:''+data.msg+'',callback:function(){
                            //window.location.reload()
                     //}})
                     /*if (data.error_code ==1) {
                         alert(data.msg)
                     } else {
                         alert(data.msg)
                     }*/

	             }

	        })

    	})
    })




   function initLoadCount(obj,count){
         //J.4.4A常用车型
         var _html = ""
         for (var i = 1; i <= count; i++) {
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

    $(".return").click(function(event) {
        var _url = window.location.href
        if (_url.indexOf("?") > 0) {
            _url = _url.slice(0, _url.indexOf("?") )
        }
        window.location.href = _url
    })

    /*$('input[name="search-waitor-key"]').change(function(){
        var s       = $(this).val()
        var pattern = new RegExp("[`~!@#$^&*()=|{}':;',\\[\\].<>/?~！@#￥……&*（）&mdash;—|{}【】‘；：”“'。，、？]")
        var rs      = ""
        for (var i = 0; i < s.length; i++) {
            rs = rs + s.substr(i, 1).replace(pattern, '')
        }
        $(this).val(rs)
    })*/

    $(document).delegate('input[name="zengpin_nums"],input[name="zengpin_num"]', 'focus', function(event) {
         $(this).blur()
         return false
    })

    $('.max-valite').change(function(){
        if (parseFloat($(this).val()) > 100) {
            $(this).val(100)
        }
    })


	module.exports = {
        init:function(){

        }
        ,
        initControlModel:function(){
            var _tbl = $("#controlModel")
            if (_tbl.find("tr").length <= 2) {
                _tbl.addClass('hide')
            }
        }
        ,
        initCount:function(obj,count){
             initLoadCount(obj,count)
        }
    }

})