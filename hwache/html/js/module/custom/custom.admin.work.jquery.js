define(function (require,exports,module) { 

	//加载当前时间
	var _curtime = new Date()
	var week     = ['日','一','二','三','四','五','六'] 
	$("#time-span").text(_curtime.getFullYear() + "-" + (_curtime.getMonth() + 1) + "-" + _curtime.getDate() + " 周" + week[_curtime.getDay()] )

	var _checkbox = $(".report-form input[type='checkbox']")
	_checkbox.slice(0, _checkbox.length - 1).bind("click",function(){
		if (!$(this).prop("checked")) {
			$("#checkall").prop("checked",false)
		}
		if ($(".report-form input[type='checkbox']:checked").length == 7) {
			$("#checkall").prop("checked",true)
		}
	})

	$("#checkall").click(function(){
		$(this).parents(".day").find("input[type='checkbox']").prop("checked",$(this).prop("checked"))
	})

	$(".reset").click(function(){
		$(this).prevAll(".btn-group").find("input[type='text']").attr("placeholder","1900-01-01").val("")
	})

	$(".am").focus(function(){
		require("module/common/hc.popup.jquery")
    	var _this  = $(this).blur()  
        var _win   = $("#SelectTimeWin")
        _win.removeAttr('am').removeAttr('pm').attr("pm",0)
        var _area  = ['09','10','11','12']
    	_win.hcPopup({'width':'400'}) 
    	var _val   = _this.hasClass('end') ? _area[3] : _area[0]
    	$(".center-txt").first().text(_val).attr("min",_area[0]).attr("max",_area[3]).end().eq(1).text("0").end().eq(2).text("0")
    	
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

	$(".pm").focus(function(){
		require("module/common/hc.popup.jquery")
    	var _this  = $(this).blur()
        var _win   = $("#SelectTimeWin")
        _win.removeAttr('am').removeAttr('pm').attr("pm",1)
        var _area  = ['12','13','14','15','16','17']
    	_win.hcPopup({'width':'400'}) 
    	var _val   = _this.hasClass('end') ? _area[5] : _area[1]
    	$(".center-txt").first().text(_val).attr("min",_area[0]).attr("max",_area[5]).end().eq(1).text("0").end().eq(2).text("0")
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

	function changeValue(_txt){
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
		return _val
	}

	$(".time-show-wrapper .add").click(function(){
		var _this = $(this)
		var _box  = _this.parents(".time-fn-box").find(".center-txt")
		var _hour = _box.eq(0)
		var _mint = _box.eq(1)
		var _secs = _box.eq(2)
		var _txt  = _this.prev().find(".center-txt")
		var _pm   = $("#SelectTimeWin").attr("pm")
		if (_pm == "0") {
			if (_hour.text() != "12") {
				if (changeValue(_txt) == 12) {
					_mint.text(0)
					_secs.text(0)
				}
			} else{
				_mint.text(0)
				_secs.text(0)
			}
		}else{
			if (_hour.text() != "17") {
				if (changeValue(_txt) == 17) {
					_mint.text(0)
					_secs.text(0)
				}
			} else{
				_mint.text(0)
				_secs.text(0)
			}
		}

	})
	
	$(".btn-work").click(function(){
		var _flag = true
		var _form = $(this).parents("form")
		if ($(".work-list-wrapper .day input[type='checkbox']:checked").length == 0) {
			errorshowhide($(".work-list-wrapper").eq(0).find(".error-div") )
			_flag = false
		}
		if ($(".work-list-wrapper:eq(1) input[value='']").length > 0) {
			errorshowhide($(".work-list-wrapper").eq(1).find(".error-div") )
			_flag = false
		}
		var _timelist  = []
		$.each($(".no-offer"),function(){
			var _start = $(this).find(".start")
			var _end   = $(this).find(".end")
			if ((!isempty(_start) && isempty(_end)) || isempty(_start) && !isempty(_end)) {
				errorshowhide($(".no-offer").eq(1).next())
				_flag = false
			}
			_timelist.push(Date.parse(_start.val())-0)
			_timelist.push(Date.parse(_end.val())-0)
		})
		if (_timelist[1] - _timelist[0] <=0 ||_timelist[3] - _timelist[2] <=0 ) {
			errorshowhide($(".no-offer").eq(1).next().next())
			_flag = false
		}//判断交集( 1开 > 2开 && 1 开 < 2结 ) || ( 1结 > 2开 && 1结 < 2结 )
		else if ((_timelist[0] > _timelist[2]  && _timelist[0] < _timelist[3]) || (_timelist[1] > _timelist[2]  && _timelist[1] < _timelist[3]) ) {
			errorshowhide($(".no-offer").eq(1).next().next().next())
			_flag = false
		}
		if (_flag) {
			
			$.each($(".report-form input[type='checkbox']"),function(index,item){
				$(".report-result input[type='checkbox']").eq(index).prop("checked",$(item).prop("checked")).attr("disabled","disabled")
			})

			$.each($(".time-sl"),function(index,item){
				$(".time-dlp").eq(index).text($(this).find("input[type='text']").val())
			})

			$.each($(".no-offer").find("input[type='text']"),function(index,item){
				$(".time-dlp-long").eq(index).text($(this).val() == "" ? "1900-01-01" : $(this).val())
			})

			var options = {
                success: function (data) {
                   $(".report-form").hide().next().show()
                }
                ,
                beforeSubmit:function(){}
                ,
                error:function(){
                   $(".report-form").hide().next().show()
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

	$(".modify-work").click(function(){
		$.each($(".report-result input[type='checkbox']"),function(index,item){
			$(".report-form input[type='checkbox']").eq(index).prop("checked",$(item).prop("checked")) 
		})

		$.each($(".time-dlp"),function(index,item){
			$(".time-sl").find("input[type='text']").eq(index).val($(this).text())
		})

		$.each($(".time-dlp-long"),function(index,item){
			$(".no-offer").find("input[type='text']").eq(index).val($(this).text() == "1900-01-01" ? "" : $(this).text())
		})

		$(".report-form").show().next().hide()
	})


	
})

 
