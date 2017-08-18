define(function (require,exports,module) { 
 
	$('.max-valite').change(function(){
        if (parseFloat($(this).val()) > 100) {
            $(this).val(100)
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
		 _li.unbind("click").bind("click",function(){
		 	
		 	$(this).addClass("active").siblings().removeClass("active").parent().find("input[type='hidden']").val($(this).attr('data-value')).parent().prev().find(".dropdown-label span").text($(this).text())
		 	var _td = $(this).parents("td").next()
		 	_td.find(".hide").removeClass('hide').end().find(".save-label").hide().end().next().find(".init").hide().prev().removeClass('hide')
		 	_td.find(".gray").show()

		 })
		 //counter
		 var _counter = controlModel.target.find(".counter-wrapper")
		 _counter.find(".prev").unbind("click").click(function(){
		 	var _input = $(this).next()
		 	if (parseInt(_input.val()) <= 1) {
		 		_input.val("")
		 	}else{
		 		_input.val((parseInt(_input.val())-1))
		 	}
		 }).next().unbind("keyup").keyup(function(){
		 	var _input = $(this) 
		 	if (isNaN(_input.val())) {
		 		_input.val("")
		 	} 
		 }).unbind("blur").blur(function(){
		 	var _input = $(this) 
		 	if (isNaN(_input.val())) {
		 		_input.val("")
		 	} 
		 }).next().unbind("click").click(function(){
		 	var _input = $(this).prev()
		 	_input.val((parseInt(_input.val())+1))
		 })
		 //applynew
		 var _applynew = controlModel.target.find(".applynew")
		 _applynew.unbind("click").click(function(){
		 	 //console.log(13)
		 	 require("module/common/hc.popup.jquery")
         	 $("#applyControlModel").hcPopup({'width':'450'})
		 })
		
	}
	controlModel.prototype.add = function(tmpl) {
		var _tbl = controlModel.target
		if (_tbl.find(".def-add").length != 0) {
			return
		}
		$.each(_tbl.find(".def-temp"),function(index,item){
			$(".btn-group,.checkbox-wrapper,.save,.cancel").hide()
			$(".save-label,.edit,.del").removeClass('hide').show()
			$(".active").removeClass('active')
		})
		_tbl.removeClass('hide').show().find("tbody").append($("#"+tmpl).html()).end().find("#temp-file").hide()
	}
	controlModel.prototype.save = function(id) {
		event            = arguments.callee.caller.arguments[0] || window.event
		var _this        = $(event.target)
		//var _tbl         = _this.parents("table")
		var _td          = _this.parents('tr').eq(0).find("td").eq(0)
		var _other_id    = _td.find("input[type='hidden']").val()
		var _other_name  = _td.find(".dropdown-label").text()
		var _price       = _td.next().find("input")
		var _other_price = _price.val()
		var _error       = _td.next().find(".error-div")
		if (!isPositiveInteger(_other_price) || parseFloat(_other_price) % 100 != 0) {
			_error.show().next().hide()
			return
		}
		//记录新添加的值
		_price.attr("data-def",_other_price)
		
		_error.hide()
        $.ajax({
             type: "POST",
             url: "/dealer/baojia/ajaxsubmit/edit-other-price",
             data: {
                id:id,
				bj_id:_bj_id,
				other_id:_other_id,
				other_name:_other_name,
				other_price:_other_price,
				_token:$("meta[name=csrf-token]").attr('content'),
             },
             dataType: "json",
             success: function(data){
               
               if(data.error_code == 1){
				    $("#tip-error").hcPopup({content:data.message})
			   }else{
					/*if(id > 0){
						$("#tip-succeed").hcPopup({content:data.message})
					}*/
				    _td.find('.save-label').html(_other_name).removeClass('hide').prev().hide()
					_td = _td.next() 
					_td.find('.save-label').html("￥"+(_price.val())).show().removeClass('hide').prev().hide()
                    _td.find('.save-label').next().next().hide()
					_td = _td.next()
					_td.find(".save").hide().next().show().next().hide().next().show()
					_td.find("a").attr('data-id',data.id).attr('data-value',_other_id)
					_this.parents('tr').removeClass('def-add').addClass('def-temp')
					//window.location.reload();
			   }

             }
        })
        

	}
	controlModel.prototype.edit = function(other_id) {
		event = arguments.callee.caller.arguments[0] || window.event
		$(event.target).parents('table').find(".def-add").remove()
		var _tr  = $(event.target).parents('tr').eq(0)
		var _td  = _tr.find("td").eq(0)
		_td.find('.save-label').addClass('hide').prev().css("display","inline-block").find(".dropdown-label span").text(_td.find('.save-label').text()).end().find("input[type='hidden']").val(other_id)
		_td = _td.next()
		_td.find('.save-label').hide().prev().css("display","inline-block").find("input").val(_td.find('.save-label').text().replace('￥',''))
		_td = _td.next()
		_td.find(".save").removeClass('hide').show().next().hide().next().show().next().hide()
		//只允许一个修改对象
		_tr.siblings('tr').find(".btn-group,.checkbox-wrapper,.save,.cancel").hide()
		.end().find(".save-label,.edit,.del").removeClass('hide').show()
		.end().find(".active").removeClass('active')
	}
	controlModel.prototype.del = function(id) {
		 event = arguments.callee.caller.arguments[0] || window.event
		 var _target = $(event.target).parents('tr').eq(0)
		 require("module/common/hc.popup.jquery")
         $("#delControlModel").hcPopup({'width':'450'})
         $("#delControlModel .delControlModel").click(function(){
         	  $.ajax({
					 type: "POST",
					 url: "/dealer/baojia/ajaxsubmit/delete-other-price",
					 data: {
						id:id,
						bj_id:_bj_id,
						_token:$("meta[name=csrf-token]").attr('content'),
					 },
					 dataType: "json",
					 success: function(data){
					   if(data.error_code==1){
							 $("#tip-error").hcPopup({content:data.message})
					   }else{
							//window.location.reload();
						   if($(".def-temp").length<2){//最后一个被删除时，添加个新的模版
							   _model.add("controlModel-tmpl")
							   _model.eventBind()
						   }
							_target.remove();
							$("#delControlModel").hide()
					   }
					 }
				})
         })
	}
	controlModel.prototype.cancel = function(arg) {
		event = arguments.callee.caller.arguments[0] || window.event
		if($(event.target).attr('data-id')==0){
			/*if($(".def-temp").length == 0){//最后一个被删除时，添加个新的模版
				_model.add("controlModel-tmpl")
				_model.eventBind()
			}*/
			if($(".def-temp").length != 0){
				$(event.target).parents('tr').eq(0).fadeOut(500).remove().end()
			}else{
				var _tr = $(event.target).parents('tr')
				_tr.find('.error-div').hide()
				_tr.find(".checkbox-wrapper,.inline,.gray").addClass('hide')
				_tr.find(".save-label,.init").removeClass('hide').show()
				_tr.find(".active").removeClass('active')
				_tr.find(".dropdown-label span").text("--请选择--")
			}
		}else{
			var _this  = $(event.target)
			var _td    = _this.parents('tr').eq(0).find("td").eq(0)
			var _price = _td.next().find("input").attr("data-def")
			_td.find('.save-label').html(_td.find('.save-label').text()).removeClass('hide').prev().hide()
			_td = _td.next()
			_td.find('.save-label').html("￥"+(_price)).show().removeClass('hide').prev().hide()
			_td = _td.next()
			_td.find(".save").hide().next().show().next().hide().next().show()
			_td.prev().find(".error-div").hide().next().hide()
			$(".active").removeClass('active')
		}

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
	 	 _model.save($(this).attr('data-id'))
	})
	//edit
	_model.target.delegate(".edit","click",function(){
		 //console.log(_model.target[0])
	 	 _model.edit($(this).attr('data-value'))
	})
	//cancel
	_model.target.delegate(".cancel","click",function(){
	 	 _model.cancel()
	})
	//del
	_model.target.delegate(".del","click",function(){
	 	 _model.del($(this).attr('data-id'))
	})

	$(".sub-apply-new").click(function(){
		var _win   = $("#applyControlModel")
		var _form  = $("form[name='apply-form']")
		var _input = _form.find("input[type='text']")
		if (_input.val().trim() == "") {
			errorshowhide(_input.next())
		}else{
		    var options = {
				dataType: "json",
	            success: function (data) {
	               if(data.error_code==1){
						$("#tip-error").hcPopup({content:data.message})
				   }else{
					  $("#tip-info-success").hcPopup({content:data.message})
					   _win.hide() 
						//window.location.reload();
				   }
	            }
	            ,
	            beforeSubmit:function(){
				
				}
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
						_flag = true
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
				_this.find(".error-info").show()
				_isok = false
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
							var _flag = false
							if (_c1.prop("checked")){
								if (_t2.val().trim() != "") {
									_flag = true
								}
								else{
									_flag = false
								}
							}
							if (_c2.prop("checked")){
								if (_t3.val().trim() != "") {
									_flag = true
								}
								else{
									_flag = false
								}
							}
							if (_flag) {
								_arr[idx] = true
							}else{
								_arr[idx] = false
							}
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

	$("input[name=benrenshangpai]").click(function(){
		if($(this).val()==0 && $(this).prop('checked')){
			$("input[name=bj_license_plate_break_contract]").val('');
		}
	})
	$(".clear-value").click(function(){
		if($(this).prop('checked')==false){
			$(this).parent().find('input[type=text]').val('');
		}
	})

	$(".card-input").focus(function(){
		$(this).parent().find('input[type=checkbox]').prop('checked',true).end();
		$(this).parent().parent().parent().parent().find("input[type=radio]").prop('checked',true).end();
	})

	$("input[name=bj_license_plate_break_contract]").focus(function(){
		//if($(this).val()!=""){}
		$(this).parent().parent().find("input[type=radio]").eq(0).prop("checked",true).end();
		
	}).blur(function(){
		console.log(123)
	})

})