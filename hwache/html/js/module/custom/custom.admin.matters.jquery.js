define(function (require,exports,module) { 

	$(".next").click(function(){
		var _form = $(this).parents("form")
		var _arr  = [false,false]
		$.each($(".valite-1"),function(idx,it){
			var _this  = $(this)
			var _radio = _this.find("input[type='radio']")
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
									errorhide(_t1.next().next())
									
								}else{
									_flag  = false
									_first = false
									errorshow(_t1.next().next())
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
									errorhide(_t2.next().next())
									
								}else{
									_flag  = false
									_first = false
									errorshow(_t2.next().next())
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
		if (!_arr[0]) {
			//inputerror
			//errorshowhide($("#inputerror"))
		}
		var _timevalite = true 
		//Date.parse(_start.val())-0
		if ($(".time-set").prop("checked")) {
			var _timewrapper = $(".start")
			$.each(_timewrapper,function(){
				if ($(this).val().trim() == "") {
					_timevalite = false
					errorshowhide($("#timerror"))
					return
				}
			})
			if ((Date.parse(_timewrapper.eq(1).val())-0) - (Date.parse(_timewrapper.eq(0).val())-0) <=0) {
				errorshowhide($("#timerror"))
				_timevalite = false
			}
		}
		if (_arr[0] == true && _timevalite) {
			var options = {
                success: function (data) {
                   
                }
                ,
                beforeSubmit:function(){}
                ,
                error:function(){
                   
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

	$(".am").focus(function(){
		require("module/common/hc.popup.jquery")
    	var _this  = $(this).blur()  
        var _win   = $("#SelectTimeWin")
        var _area  = ['00','01','02','03','04','05','06','07','08','09','10','11','12']
    	_win.hcPopup({'width':'400'}) 
    	//var _val   = _this.hasClass('end') ? _area[3] : _area[0]
    	//$(".center-txt").first().text(_val).attr("min",_area[0]).attr("max",_area[3]).end().eq(1).text("0").end().eq(2).text("0")
    	
    	_win.find(".do").unbind('click').bind("click",function(){
    		var _html  = ""
            $.each($(".center-txt"),function(indx,item){
            	_html += $(item).text()
            	if (indx == 0) {_html += ":"}
            })
            _this.val(_html)
            _win.attr(("am-" + (_this.hasClass('end') ? "end" : "start")),_html)
            if (_this.hasClass('end')) {
            	var _start = _win.attr("am-start")
            	if (parseFloat(_html.replace(":",".")) - parseFloat(_start.replace(":",".")) <= 0) {
            		 errorshowhide($("#time-select-error"))
            	}else{
            		_win.hide()
            	}
            }else{
            	_win.hide()
            }
            
        })
	})

	$(".time-show-wrapper .minus").click(function(){
		var _this = $(this)
		var _txt  = _this.next().find(".center-txt")
		var _min  = parseInt(_txt.attr("min"))
		var _max  = parseInt(_txt.attr("max"))
		var _cur  = parseInt(_txt.text())
		var _val  = ""
		if (_cur == _min) {
			_val = _min
		}else{
			_val = _cur-1
		}
		if (_txt.hasClass('double')) {
			_val = _val.toString().length == 1 ? "0"+_val :_val
		}
		_txt.text(_val).attr("cur",_val)

	})

	$(".time-show-wrapper .add").click(function(){
		var _this = $(this)
		var _txt  = _this.prev().find(".center-txt")
		var _min  = parseInt(_txt.attr("min"))
		var _max  = parseInt(_txt.attr("max"))
		var _cur  = parseInt(_txt.text())
		var _val  = ""
		if (_cur == _max) {
			_val = _max
		}else{
			_val = _cur+1
		}
		if (_txt.hasClass('double')) {
			_val = _val.toString().length == 1 ? "0"+_val :_val
		}
		_txt.text(_val).attr("cur",_val)

	})

	$(".sub").click(function(){
		var _this = $(this)
		var _tr   = _this.parents("tr")
		 
		var _win  = $("#DelWin")
		require("module/common/hc.popup.jquery")
        _win.hcPopup({'width':'450'}).find(".do").unbind('click').click(function(event) {
        	$.ajax({
	             type: "GET",
	             url: "del",
	             data: {
	                id:'' 
	             },
	             dataType: "json",
	             success: function(data){
	             	_win.hide() 
	             },
	             error:function(){
	             	_win.hide() 
	             }
	        })

        }) 

	})


})