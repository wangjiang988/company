define(function (require,exports,module) { 
 

	var controlModel = function(opt){
		this.target = opt.target || null  
	}
	controlModel.prototype.init = function(arg) {
		 controlModel.target = arg 
	}
	controlModel.prototype.eventBind = function() {
		 //select
		 var _li = controlModel.target.find("li")
		 _li.bind("click",function(){
		 	console.log("xxx")
		 	$(this).addClass("active").siblings().removeClass("active").parent().find("input[type='hidden']").val($(this).text()).parent().prev().find(".dropdown-label span").text($(this).text())
		 	var _td = $(this).parents("td").next()
		 	_td.find(".hide").removeClass('hide').end().find(".save-label").hide().end().next().find(".init").hide().prev().removeClass('hide')
		 })
		 //counter
		 var _counter = controlModel.target.find(".counter-wrapper")
		 _counter.find(".prev").click(function(){
		 	var _input = $(this).next()
		 	if (parseInt(_input.val()) <= 1) {
		 		_input.val(1)
		 	}else{
		 		_input.val((parseInt(_input.val())-1))
		 	}
		 }).next().keyup(function(){
		 	var _input = $(this) 
		 	if (isNaN(_input.val())) {
		 		_input.val(1)
		 	} 
		 }).blur(function(){
		 	var _input = $(this) 
		 	if (isNaN(_input.val())) {
		 		_input.val(1)
		 	} 
		 }).next().click(function(){
		 	var _input = $(this).prev()
		 	_input.val((parseInt(_input.val())+1))
		 })
		 //applynew
		 var _applynew = controlModel.target.find(".applynew")
		 _applynew.click(function(){
		 	 //console.log(13)
		 	 require("module/common/hc.popup.jquery")
         	 $("#applyControlModel").hcPopup({'width':'450'})
		 })
		
	}
	controlModel.prototype.add = function(tmpl) {
		 controlModel.target.removeClass('hide').show().find("tbody").append($("#"+tmpl).html())
	}
	controlModel.prototype.save = function(arg) {
		event = arguments.callee.caller.arguments[0] || window.event
		var _this = $(event.target)
		var _td  = _this.parents('tr').eq(0).find("td").eq(0)
            
        $.ajax({
             type: "GET",
             url: "doSaveFreeGiftsAndServices",
             data: {
                id:'' 
             },
             dataType: "json",
             success: function(data){
                _td.find('.save-label').html(_td.find("input[type='hidden']").val()).removeClass('hide').prev().hide()
		        _td = _td.next() 
		        _td.find('.save-label').html(_td.find("input").val()).show().removeClass('hide').prev().hide()
		        _td = _td.next()
		        _td.find(".save").hide().next().show().next().hide().next().show()
             }
        })
        //真实环境请删除下面代码
        _td.find('.save-label').html(_td.find("input[type='hidden']").val()).removeClass('hide').prev().hide()
        _td = _td.next() 
        _td.find('.save-label').html(_td.find("input").val()).show().removeClass('hide').prev().hide()
        _td = _td.next()
        _td.find(".save").hide().next().show().next().hide().next().show()

	}
	controlModel.prototype.edit = function(arg) {
		event = arguments.callee.caller.arguments[0] || window.event
		var _td  = $(event.target).parents('tr').eq(0).find("td").eq(0)
		_td.find('.save-label').addClass('hide').prev().css("display","inline-block").find(".dropdown-label span").text(_td.find('.save-label').text()).end().find("input[type='hidden']").val(_td.find('.save-label').text())
		_td = _td.next()
		_td.find('.save-label').hide().prev().css("display","inline-block").find("input").val(_td.find('.save-label').text())
		_td = _td.next()
		_td.find(".save").removeClass('hide').show().next().hide().next().show().next().hide()
	}
	controlModel.prototype.del = function(arg) {
		 event = arguments.callee.caller.arguments[0] || window.event
		 var _target = $(event.target).parents('tr').eq(0)
		 require("module/common/hc.popup.jquery")
         $("#delControlModel").hcPopup({'width':'450'})
         $("#delControlModel .delControlModel").click(function(){
         	 $.ajax({
                 type: "GET",
                 url: "doDelControlModel",
                 data: {
                    id:''  
                 },
                 dataType: "json",
                 success: function(data){
                    $("#delControlModel").hide()
                    _target.fadeOut(500)
                 }
            })
            //真实环境请删除下面两行
            $("#delControlModel").hide()
            _target.fadeOut(500)
         })
	}
	controlModel.prototype.cancel = function(arg) {
		 event = arguments.callee.caller.arguments[0] || window.event
		 $(event.target).parents('tr').eq(0).fadeOut(500)
	}
	var _target = $("#controlModel")
    var _model  = new controlModel({target:_target})
    function initControlModel(){
    	_model.init(_target)
    	_model.eventBind()
    }
    initControlModel()
	$(".controlModelAdd").click(function(){
		 _model.init(_target)
		 _model.add("controlModel-tmpl")
		 _model.eventBind()
	})

	//save

	_model.target.delegate(".save","click",function(){
	 	 _model.save()
	})
	//edit
	_model.target.delegate(".edit","click",function(){
		 //console.log(_model.target[0])
	 	 _model.edit()
	})
	//cancel
	_model.target.delegate(".cancel","click",function(){
	 	 _model.cancel()
	})
	//del
	_model.target.delegate(".del","click",function(){
	 	 _model.del()
	})

	$(".sub-apply-new").click(function(){
		var _win   = $("#applyControlModel")
		var _form  = $("form[name='apply-form']")
		var _input = _form.find("input[type='text']")
		if (_input.val().trim() == "") {
			errorshowhide(_input.next())
		}else{
		    var options = {
	            success: function (data) {
	               _win.hide()
	            }
	            ,
	            beforeSubmit:function(){}
	            ,
	            error:function(){
	               _win.hide()  
	            }
	            ,
	            clearForm:true
            }
         
	         // ajaxForm 
	         _form.ajaxForm(options) 
	         // 表单提交
	         _form.ajaxSubmit(options) 
		}
	})


	$(".next").click(function(){
		var _isok = true
		$.each($(".valite-1"),function(){
			var _radio = $(this).find("input[type='radio']")
			var _this  = $(this)
			var _flag  = false
			$.each(_radio,function(index,item){
				if (index == 0 ) {
					var _val = _this.find("input[type='text']").val().trim()
					if ($(item).prop("checked") && _val !="" && !isNaN(_val) ) {
						if (parseInt(_val) % 100 == 0) {
							_flag = true
						}else{
							_flag = false
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
				if (parseInt(_val) % 100 != 0) {
					_error.show().next().hide()
					_isok = false
				}else{
					_error.hide()
				}
			}
		})


		var _arr  = [false,false]
		$.each($(".valite-3"),function(idx,it){
			var _radio = $(this).find("input[type='radio']")
			var _this  = $(this)
			$.each(_radio,function(index,item){
				if (index == 0 ) {
					 if ($(item).prop("checked")) {
					 	_arr[idx] = true
					 }
					 else{
						_arr[idx] = false
					}  
				}
				else if (index == 1) {
				 
					if ($(item).prop("checked")) {
						var _t1 = _this.find("input[type='text']").eq(0)
						var _t2 = _this.find("input[type='text']").eq(1)
						var _t3 = _this.find("input[type='text']").eq(2)
						var _c1 = _this.find("input[type='checkbox']").eq(0)
						var _c2 = _this.find("input[type='checkbox']").eq(1)
						if (_t1.val().trim() != "") {
							_t1.parents("td").next().find(".error-div").eq(0).hide()
							var _flag = false
							if (_t1.val().toString().split('.').length >= 2 || isNaN(_t1.val())) {
								_flag = false
								_t1.parents("td").next().find(".error-div").eq(0).show()
							}
							if (_c1.prop("checked")){
								if (_t2.val().trim() != "") {
									var _split = _t2.val().toString().split('.')
									if ((_split.length >= 2 && _split[1].length > 1) || isNaN(_t2.val())) {
										_flag = false
										_t2.next().next().hide().next().show()
									}else{
										_flag = true
										_t2.next().next().next().hide()
									}
								}
								else{
									_flag = false
									_t2.next().next().hide().next().show()
								}
							}else{

							}
							if (_c2.prop("checked")){
								if (_t3.val().trim() != "") {
									if (parseInt(_t3.val()) % 10 == 0) {
										_flag = true
										_t3.next().next().next().hide()
									}else{
										_flag = false
										_t3.next().next().hide().next().show()
									}
									
								}
								else{
									_flag = false
									_t3.next().next().hide().next().show()
								}
							}
							if (_flag) {
								_arr[idx] = true
							}else{
								_arr[idx] = false
							}
						} 
						else{
							_t1.parents("td").next().find(".error-div").eq(0).show()
						}
 					} 
				}
				 
			})
			//console.log(_arr.join(","))
			
		}) 
		
		if (_arr[0] == true && _arr[1] == true && _isok) {
			$(this).parents("form").submit()
		}
 


	})

	$(".valite-3").each(function(){ 
		var _radio = $(this).find("input[type='radio']:eq(0)")
		var _clear = $(this).find(".radio-label")
		_radio.click(function(){
			_clear.find("input[type='text']").val("").end().find("input[type='checkbox']").prop("checked",false).end().find(".error-div").hide()
		})
	})
	
	$("input[type='text']").blur(function(){
		var _val = $(this).val()
		if (parseInt(_val) < 0) {
			 $(this).val(0)
		}
	})

})