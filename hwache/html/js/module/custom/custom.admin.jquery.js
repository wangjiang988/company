define(function (require,exports,module) {


	$(".registrationCondition").click(function(){
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
		if (_isok) {
			$(this).parents("form").submit()
		}

	})

	$(".valite-1 input[type='radio']:eq(1)").click(function(event) {
		$(this).parent().prev().find("input[type='text']").val("")
	})

	$(".registrationCondition-adv").click(function(){
	    var _isok = true
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
		}else if(parseInt(_input.val()) % 100 != 0){
			errorshowhide(_input.parent().next())
			_isok = false
		}
		 
		if (_isok) {
			$(this).parents("form").submit()
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
		 _li.bind("click",function(){
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
		        _td.find('.save-label').html("￥"+_td.find("input").val()).show().removeClass('hide').prev().hide()
		        _td = _td.next()
		        _td.find(".save").hide().next().show().next().hide().next().show()
             }
        })
        //真实环境请删除下面代码
        _td.find('.save-label').html(_td.find("input[type='hidden']").val()).removeClass('hide').prev().hide()
        _td = _td.next() 
        _td.find('.save-label').empty().html("￥"+_td.find("input").val()).show().removeClass('hide').prev().hide()
        _td = _td.next()
        _td.find(".save").hide().next().show().next().hide().next().show()

	}
	controlModel.prototype.edit = function(arg) {
		event = arguments.callee.caller.arguments[0] || window.event
		var _td  = $(event.target).parents('tr').eq(0).find("td").eq(0)
		_td.find('.save-label').addClass('hide').prev().css("display","inline-block").find(".dropdown-label span").text(_td.find('.save-label').text()).end().find("input[type='hidden']").val(_td.find('.save-label').text())
		_td = _td.next()
		_td.find('.save-label').hide().prev().css("display","inline-block").find("input").val(_td.find('.save-label').text().replace("￥",""))
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
		var _form  = $("form[name='apply-form']")
		var _input = _form.find("input[type='text']")
		if (_input.val().trim() == "") {
			errorshowhide(_input.next())
		}else{
		    _form.submit()
		}
	})

	function errorshowhide(obj){
        obj.show(function(){
            setTimeout(function(){
                obj.fadeOut(300)
            },3000)
        })
    }
    function isempty(obj){
        return $.trim(obj.val()) == ""
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
				console.log(11)
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

    //刷卡标准 next 验证
    $(".chargeStandard").click(function(){
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

		if (_arr[0] == true && _arr[1] == true) {
			$(this).parents("form").submit()
		}
		
    })
	
	//补贴情况 next 验证
	$(".subsidy").click(function(){
    	var _arr  = [false,false]
		$.each($(".tbl-list-tool-panle"),function(idx,it){
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
						var _c1 = _this.find("input[type='checkbox']").eq(0)
						var _c2 = _this.find("input[type='checkbox']").eq(1)
						
						var _flag  = false
						var _first = true
						if (_c1.prop("checked")){
							if (_t1.val().trim() != "") {

								if (!isNaN(_t1.val()) && _t1.val().toString().split('.').length == 1) {
									_flag  = true
									_first = true
									_t1.next().next().hide()
									
								}else{
									_flag  = false
									_first = false
									_t1.next().next().show()
								}
							}
							else{
								_flag  = false
								_first = false
							}
						}
						if (_c2.prop("checked")){
							if (_t2.val().trim() != "") {
								_flag = _first == true ? true : false
								if (!isNaN(_t2.val()) && _t2.val().toString().split('.').length == 1) {
									_flag  = true
									_first = true
									_t2.next().next().hide()
									
								}else{
									_flag  = false
									_first = false
									_t2.next().next().show()
								}
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
				 
			})
			//console.log(_arr.join(","))
			
		})

		if (_arr[0] == true) {
			$(this).parents("form").submit()
		}
		
    })

    //竞争分析
    $(".tbl-competitive .edit-new").click(function(){
    	var _tr = $(this).parents("tr").eq(0)
    	var _td = _tr.find("td").eq(0)
    	_td.find(".form-txt.psr.inlineblock").show().next().hide()
    	_td = _td.next()
    	_td.find(".btn-group").show().next().hide()
    	$(this).hide().prev().removeClass('hide').show()
    })
    $(".province-wrapper").click(function(){
    	var _this = $(this)
    	_this.next(".dropdown-menu").show().find("dl").eq(0).find("dd").click(function(){
    		var _dd = $(this)
    		_this.next().find("input[name='province']").val($(this).text())
    		_this.find(".dropdown-label span").text($(this).text())
			_dd.parent().prev().find("span").eq(0).removeClass('cur-tab').next().addClass('cur-tab')
    		_dd.parent().hide().next().empty().show().append(function(){
				var _html = ""
				$.ajax({
		             type: "GET",
		             url: "getCityList",
		             async:false,
		             data: {
		                id:_dd.attr("data-id") //根据province-id查询city list
		             },
		             dataType: "json",
		             success: function(data){
		                 //输出格式cityname、cityid这两个可以自己定义
		                 //[{cityname:'苏州',cityid:211},{cityname:'常熟',cityid:213}]
		                 //data = [{cityname:'苏州',cityid:211},{cityname:'常熟',cityid:213}]
		                 $.each(data,function(idx,item){
		                 	_html += "<dd data-id='"+$(item)[0].cityid+"'>"+$(item)[0].cityname+"</dd>"
		                 })
		             }
		        })
		        //真实环境删除下面的data和each方法
				var data = [{cityname:'苏州',cityid:211},{cityname:'常熟',cityid:213}]
	            $.each(data,function(idx,item){
	             	_html += "<dd data-id='"+$(item)[0].cityid+"'>"+$(item)[0].cityname+"</dd>"
	            })
    			return _html
    		})
    		 
    	}).end().next().delegate("dd","click",function(){
    		var _dd = $(this)
    		_this.next(".dropdown-menu").hide()
    		_this.next().find("input[name='city']").val($(this).text())
    		_dd.parent().hide().prev().show().prev().find("span").eq(1).removeClass('cur-tab').prev().addClass('cur-tab')
    		_this.find(".dropdown-label span").text(
    			_this.next().find("input[name='province']").val()+_this.next().find("input[name='city']").val()
    		)
    		var _html = ""
			$.ajax({
	             type: "GET",
	             url: "getDealerList",
	             data: {
	                id:_dd.attr("data-id") //city-id查询dealer list
	             },
	             dataType: "json",
	             success: function(data){
	                 $.each(data,function(idx,item){
	                 	_html += "<dd data-id='"+$(item)[0].cityid+"'>"+$(item)[0].cityname+"</dd>"
	                 })
	                 _dd.parents("td").eq(0).next().find(".dropdown-menu").empty().append(_html).delegate('li', 'click', function() {
		            	$(this).parent().next().val($(this).text())
		            	$(this).parents(".btn-group").eq(0).find(".dropdown-label span").text($(this).text())
		            	$(this).parents('td').eq(0).next().find("span").text($(this).attr("data-address"))
		            })
	             }
	        })
	        //真实环境删除下面的data和each方法
			data = [{dealername:'苏州路虎',dealerid:211,address:'江苏省苏州市竹园路209号'},{dealername:'苏州宝马',dealerid:213,address:'江苏省苏州市金枫路123号'}]
            $.each(data,function(idx,item){
             	_html += "<li data-address='"+$(item)[0].address+"' data-id='"+$(item)[0].dealerid+"'><a><span>"+$(item)[0].dealername+"</span></a></li>"
            })
            _dd.parents("td").eq(0).next().find(".dropdown-menu").empty().append(_html).delegate('li', 'click', function() {
            	$(this).parent().next().val($(this).text())
            	$(this).parents(".btn-group").eq(0).find(".dropdown-label span").text($(this).text())
            	$(this).parents('td').eq(0).next().find("span").text($(this).attr("data-address"))
            })
            //真实环境删除以上
            //下面保留
            _dd.parents("tr").eq(0).find("td:last").find(".save").unbind("click").click(function(){
            	var _tr  = $(this).parents("tr").eq(0)
            	var _td  = _tr.find("td").eq(0)
            	var _td2 = _td.next()
            	if (_td2.find("input[type='hidden']").val().trim() != "") {
	            	_td.find(".form-txt.psr.inlineblock").hide().next().show().text(_td.find(".dropdown-label span").text())
	            	_td2.find(".btn-group").hide().next().show().text(_td2.find(".dropdown-label span").text())
	            	$(this).hide().next().removeClass('hide').show()
            	}

            })



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
		$(".custom-form-tbl").addClass('hide')
	}) 
	//基本资料 删除
	$(".base-info-del").click(function(){
		//弹窗
		 require("module/common/hc.popup.jquery")
         $("#delInfoWin").hcPopup({'width':'450'})
         $("#delInfoWin").find(".btn").eq(0).click(function(){
         	$.ajax({
	             type: "GET",
	             url: "delDealer",
	             data: {
	                id:"" 
	             },
	             dataType: "json",
	             success: function(data){
	                 //do
	             }
	        })
         })
	})  

	//基本资料 修改提交验证
	$(".SubEditDealersForm").click(function(){
		  
           //hfbrand  province  city hfdealers txt-dealers-shot-name
           var _from      = $("form[name='edit-dealers-form']")
           var _brand     = _from.find("input[name='hfbrand']")
           var _province  = _from.find("input[name='province']")
           var _city      = _from.find("input[name='city']")
           var _dealers   = _from.find("input[name='hfdealers']")
           var _shotname  = _from.find("input[name='txt-dealers-shot-name']")
           var _txtcode   = _from.find("input[name='txtcode']")
           var _reg = /^\d{18}\w+$/g //选填 统一社会信用代码由18位数字+至少一位字母
           if (isempty(_brand)) {
                errorshowhide(_brand.parents('.btn-group').next())
                console.log(_from[0])
           }
           else if (isempty(_province)) {
                errorshowhide(_province.parents('.btn-group').next())
           }
           else if (isempty(_city)) {
                errorshowhide(_city.parents('.btn-group').next().next())
           }
           else if (isempty(_dealers)) {
                errorshowhide(_dealers.parents('.btn-group').next())
           }
           else if (isempty(_shotname)) {
                errorshowhide(_shotname.next())
           }
           else if(!isempty(_txtcode) && !_reg.test(_txtcode.val())){
              errorshowhide(_txtcode.next())
           }
           else{
                _form.submit()
           }

    })

    //保险条件 修改 
    $(".baoxian-modify").click(function(){
    	$(this).hide().next().show().next().show()
    }).next().click(function(){
    	$(this).parents("form").submit()
    }).next().click(function(){
    	$(this).hide().prev().hide().prev().show()
    })

    //上牌条件 修改 
    $(".registration-modify").click(function(){
    	$(this).hide().next().removeClass('hide').next().removeClass('hide')
    }).next().next().click(function(){
    	$(this).addClass('hide').prev().addClass('hide').prev().show()
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
    	_parant.find("input[type='hidden']").val(_this.text())
    	_parant.find(".dropdown-label span").text(_this.text())

    })
    //新增常用车型 车系和车型规格的点击事件
    //dropdown-cars-demio dropdown-cars-standard
    $(".dropdown-cars-demio li").click(function(){
    	var _this = $(this)
    	$.ajax({
             type: "GET",
             url: "getStandardByDemioId", 
             data: {
                id:_this.attr("data-id")
             },
             dataType: "json",
             success: function(data){
                 //do
                 var _html = ""
                 $.each(data,function(){
                 	_html += "<li data-id='"+$(this)[0].车型id+"'><a><span>"+$(this)[0].车型名称+"</span></a></li>"
                 })
                 $(".dropdown-cars-standard").find("li").remove().end().append(_html)
             },
             error:function(msg){
             	 //success 中 data数据的格式 参考如下
             	 //真实环境请删除error方法
                 var data = [{'车型名称':'sDrive20i* 领先型','车型id':'1524'},{'车型名称':'sDrive35i* 豪华型 ','车型id':'1526'}]
                 var _html = ""
                 $.each(data,function(){
                 	_html += "<li data-id='"+$(this)[0].车型id+"'><a><span>"+$(this)[0].车型名称+"</span></a></li>"
                 })
                 $(".dropdown-cars-standard").find("li").remove().end().append(_html)
             }
        })  
    })

    $(".dropdown-cars-standard").delegate('li', 'click', function(event) {
    	var _this = $(this)
    	 
    	$.ajax({
             type: "GET",
             url: "getCarInfoByStandardId", 
             data: {
                id:_this.attr("data-id")
             },
             dataType: "json",
             success: function(data){
                 //do
                 //厂商指导价
                 $(".guide-price").val(parseFloat(data.价格).formatMoney())
                 //处理前装和后装  
                 var _front = "" , after = "" , 
                     _fronttpl = $("#FrontLoading").html() ,
                     _aftertpl = $("#AfterLoading").html() 
                 $.each(data.加装,function(){
                 	if (this.类型 == '前装') {
                 	 	_front += _fronttpl.replace("{0}",this.名称)
                 	 			           .replace("{1}",this.型号)
                 	 			           .replace("{2}",this.厂商编号)
                 	 			           .replace("{3}",parseFloat(this.厂商指导价).formatMoney())
                 	 			           .replace("{4}",this.单车可装件数) 
                 	}
                 	else if (this.类型 == '后装') {
                 		after  += _aftertpl.replace("{0}",this.名称)
                 	 			           .replace("{1}",this.型号)
                 	 			           .replace("{2}",this.厂商编号)
                 	 			           .replace("{3}",parseFloat(this.厂商指导价).formatMoney())
                 	 			           .replace("{4}",this.安装费) 
                 	 			           .replace("{5}",this.单车可装件数) 
                 	 			           .replace("{6}",this.可供件数) 
                 	}
                 })
                 var _tbl_front = $(".tbl-front")
                 var _tr = _tbl_front.find("tr").slice(1)
                 _tr.parent().append(_front).end().remove()
                 var _tbl_front = $(".tbl-after")
                 _tr = _tbl_front.find("tr").slice(1)
                 _tr.parent().append(after).end().remove()
                 initLoadCount(_tbl_front.find(".dropdown-menu"),100)

                 //非原厂选装精品  
                 var _goods    = ""  
                     _goodstpl = $("#GoodsLoading").html()  
                 $.each(data.非原厂选装精品,function(){
                 	 
             		_goods += _goodstpl.replace("{0}",this.品牌)
             	 			           .replace("{1}",this.名称)
             	 			           .replace("{2}",this.型号)
             	 			           .replace("{3}",this.厂商编号)
             	 			           .replace("{4}",this.含安装费折后单价) 
             	 			           .replace("{5}",this.单车可装件数) 
             	 			           .replace("{6}",this.可供件数) 
             	 			           .replace("{id}",this.id) 
             	 
                 })

                 var _tbl_goods = $(".tbl-goods")
                 _tr            = _tbl_goods.find("tr").slice(1)
                 _tr.parent().append(_goods).end().remove()

                 //tool
                 var _tool_wrapper = $(".present-tool-content")
                 var _tool         = ""
                 $.each(data.附件,function(){
             		  _tool += "<p><b>"+this.附件名称+"：</b>"+this.附件内容+"</p>" 
                 })
                 _tool_wrapper.empty().html(_tool)
             },
             error:function(msg){
             	 //success 中 data数据的格式 参考如下
             	 //真实环境请删除error方法
                 var data =  {
                 	'价格':'123456789',
                 	'加装':[
                 		{
                 			'名称':'轮毂',
                 			'型号':'双福605型，17英寸',
                 			'厂商编号':'1012',
                 			'厂商指导价':'9000',
                 			'安装费':'120',
                 			'单车可装件数':'4',
                 			'可供件数':'0',
                 			'类型':'前装'
                 		},
                 		{
                 			'名称':'雨刮',
                 			'型号':'xl-5',
                 			'厂商编号':'yg-55',
                 			'厂商指导价':'35',
                 			'安装费':'0',
                 			'单车可装件数':'2',
                 			'可供件数':'0',
                 			'类型':'前装'
                 		},
                 		{
                 			'名称':'轮毂',
                 			'型号':'双福605型，17英寸',
                 			'厂商编号':'1012',
                 			'厂商指导价':'9000',
                 			'安装费':'90',
                 			'单车可装件数':'2',
                 			'可供件数':'2',
                 			'类型':'后装'
                 		},
                 		{
                 			'名称':'雨刮',
                 			'型号':'xl-5',
                 			'厂商编号':'yg-55',
                 			'厂商指导价':'35',
                 			'安装费':'0',
                 			'单车可装件数':'2',
                 			'可供件数':'3',
                 			'类型':'后装'
                 		}
                 	],
                 	'非原厂选装精品':[
                 		{
                 			'id':'3523',
                 			'品牌':'特仑苏',
                 			'名称':'雨刮器',
                 			'型号':'xl-5',
                 			'厂商编号':'sn-12383',
                 			'含安装费折后单价':'25',
                 			'单车可装件数':'2',
                 			'可供件数':'2'
                 		},
                 		{
                 			'id':'9523',
                 			'品牌':'特仑苏',
                 			'名称':'雨刮器3',
                 			'型号':'xl-56',
                 			'厂商编号':'sn-11383',
                 			'含安装费折后单价':'35',
                 			'单车可装件数':'4',
                 			'可供件数':'99'
                 		},
                 	]
                 	,
                 	//随车工具 随车移交文件 基本配置 之类的 不知道叫什么 就叫附件吧！！！
                 	'附件':[
                 		{
                 			'附件名称':'随车工具',
                 			'附件内容':'钥匙'
                 		},
                 		{
                 			'附件名称':'随车移交文件',
                 			'附件内容':'车辆合格证、发票'
                 		},
                 		{
                 			'附件名称':'xxx',
                 			'附件内容':'ooo'
                 		},
                 	]
                 }

                 //厂商指导价
                 $(".guide-price").val(parseFloat(data.价格).formatMoney())
                 //处理前装和后装  
                 var _front = "" , _after = "" , 
                     _fronttpl = $("#FrontLoading").html() ,
                     _aftertpl = $("#AfterLoading").html() 
                 $.each(data.加装,function(){
                 	if (this.类型 == '前装') {
                 	 	_front += _fronttpl.replace("{0}",this.名称)
                 	 			           .replace("{1}",this.型号)
                 	 			           .replace("{2}",this.厂商编号)
                 	 			           .replace("{3}",parseFloat(this.厂商指导价).formatMoney())
                 	 			           .replace("{4}",this.单车可装件数) 
                 	}
                 	else if (this.类型 == '后装') {
                 		_after += _aftertpl.replace("{0}",this.名称)
                 	 			           .replace("{1}",this.型号)
                 	 			           .replace("{2}",this.厂商编号)
                 	 			           .replace("{3}",parseFloat(this.厂商指导价).formatMoney())
                 	 			           .replace("{4}",this.安装费) 
                 	 			           .replace("{5}",this.单车可装件数) 
                 	 			           .replace("{6}",this.可供件数) 
                 	}
                 })
                 var _tbl_front = $(".tbl-front")
                 var _tr = _tbl_front.find("tr").slice(1)
                 _tr.parent().append(_front).end().remove()
                 var _tbl_front = $(".tbl-after")
                 _tr = _tbl_front.find("tr").slice(1)
                 _tr.parent().append(_after).end().remove()
                 initLoadCount(_tbl_front.find(".dropdown-menu"),100)

                 //非原厂选装精品  
                 var _goods    = ""  
                     _goodstpl = $("#GoodsLoading").html()  
                 $.each(data.非原厂选装精品,function(){
                 	 
             		_goods += _goodstpl.replace("{0}",this.品牌)
             	 			           .replace("{1}",this.名称)
             	 			           .replace("{2}",this.型号)
             	 			           .replace("{3}",this.厂商编号)
             	 			           .replace("{4}",this.含安装费折后单价) 
             	 			           .replace("{5}",this.单车可装件数) 
             	 			           .replace("{6}",this.可供件数) 
             	 			           .replace("{id}",this.id) 
             	 
                 })

                 var _tbl_goods = $(".tbl-goods")
                 _tr            = _tbl_goods.find("tr").slice(1)
                 _tr.parent().append(_goods).end().remove()

                 //tool
                 var _tool_wrapper = $(".present-tool-content")
                 var _tool         = ""
                 $.each(data.附件,function(){
             		  _tool += "<p><b>"+this.附件名称+"：</b>"+this.附件内容+"</p>" 
                 })
                 _tool_wrapper.empty().html(_tool)
                  
             }
        })  
    })

    
    //新增常用车型
    $(".btn-new-offerings").click(function(){
    	var _this = $(this)
        var _flag = true
        var _form = _this.parents("form").eq(0)
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
        	var _input = $(item).find("input[type='text']")
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
                success: function (data) {}
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
        _win.find(".do").unbind('click').bind("click",function(){
        	  
            var _form  = $("form[name='addNoOriginalGoodsForm']")
            var _flag = true
            $.each(_form.find("input[type='text']"),function(){
            	if (isempty($(this))) {
            		errorshowhide($(this).next())
            		_flag = false
            	}
            	else if ($(this).hasClass('valite-money')) {
            		if (isNaN($(this).val())) {
            			errorshowhide($(this).next().next())
            			_flag = false
            		}
            	} 
            })
            if (_flag) {
            	//非原厂选装精品  
                 var _goods    = ""  
                     _goodstpl = $("#GoodsLoading").html()  
                     _input    = _win.find("input[type='text']")
                     _label    = _win.find(".dropdown-label")
         		 _goods += _goodstpl.replace("{0}",_input.eq(0).val())
         	 			           .replace("{1}",_input.eq(1).val())
         	 			           .replace("{2}",_input.eq(2).val())
         	 			           .replace("{3}",_input.eq(3).val())
         	 			           .replace("{4}",_input.eq(4).val()) 
         	 			           .replace("{5}",_label.eq(0).find("span").text()) 
         	 			           .replace("{6}",_label.eq(1).find("span").text()) 
         	 			           .replace("{id}",'')  
                 var _tbl_goods = $(".tbl-goods")
                 _tbl_goods.append(_goods)
            	  
            }
            
            
        })
    })

    $(".tbl-goods").delegate('.edit-original-goods', 'click', function(event) {
    	require("module/common/hc.popup.jquery")
    	var _this = $(this)
    	var _win  = $("#editNoOriginalGoods")
    	_win.hcPopup({'width':'550'})
    	_win.find(".do").unbind('click').bind("click",function(){
        	
            var _form  = $("form[name='editNoOriginalGoodsForm']")
            var _flag = true
            $.each(_form.find("input[type='text']"),function(){
            	if (isempty($(this))) {
            		errorshowhide($(this).next())
            		_flag = false
            	}
            	else if ($(this).hasClass('valite-money')) {
            		if (isNaN($(this).val())) {
            			errorshowhide($(this).next().next())
            			_flag = false
            		}
            	} 
            })
            if (_flag) {
              
            }
            
        })
      
         //do
         var _input   = _win.find("input[type='text']")
         //品牌 名称 都是字段名称 
         _input.eq(0) = data.品牌 
         _input.eq(1) = data.名称
         _input.eq(2) = data.型号
         _input.eq(3) = data.折后单价
         var _drop    = $(".btn-group") 
         _drop.eq(0).find(".dropdown-label span").text(data.单车可装件数)
         _drop.eq(1).find(".dropdown-label span").text(data.可供件数)

    }) 

    $(".tbl-goods").delegate('.del-original-goods', 'click', function(event) {
    	require("module/common/hc.popup.jquery")
    	var _this = $(this)
    	var _win  = $("#delNoOriginalGoods")
    	_win.hcPopup({'width':'400'})
    	_win.find(".do").click(function(){
        	 $.ajax({
	             type: "GET",
	             url: "delGoodsById",
	             data: {
	                id:_this.attr("data-id") 
	             },
	             dataType: "json",
	             success: function(data){
	                 //do
	                 _win.hide()
	                 _this.parents('tr').fadeOut('300')
	             },
	             error:function(){
	             	 //真实环境 请把error方法删除 别忘了error前面的那个逗号
	             	 _win.hide()
	                 _this.parents('tr').fadeOut('300')
	             }
	        })
        }) 
    }) 

    $(".del-common-models").click(function(){
    	require("module/common/hc.popup.jquery")
    	var _this  = $(this)
    	var _isdel = _this.attr("model-isdel")
    	//判断是否具有“有效报价”
    	var _win   = _isdel == 1 ? $("#delCommonModels") : $("#delNoCommonModels")
    	_win.hcPopup({'width':'400'})
    	_win.find(".do").click(function(){
        	 $.ajax({
	             type: "GET",
	             url: "delGoodsById",
	             data: {
	                id:_this.attr("data-id") 
	             },
	             dataType: "json",
	             success: function(data){
	                 //do
	                 _win.hide()
	                 _this.parents('tr').fadeOut('300')
	             },
	             error:function(){
	             	 //真实环境 请把error方法删除 别忘了error前面的那个逗号
	             	 _win.hide()
	                 _this.parents('tr').fadeOut('300')
	             }
	        })
        
    	})
    })

    


 

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