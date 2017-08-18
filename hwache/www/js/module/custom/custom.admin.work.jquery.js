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
		$(this).prevAll(".btn-group").find("input[type='text']").attr("placeholder","2017-01-01").val("2017-01-01")
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
    	var data = _this.val()
    	var hou = data.slice(0,2)
    	var min = data.slice(3,4)
    	var bit = data.slice(-1)
    	$(".center-c").find(".double").html(hou)
    	$(".center-c").find(".min").html(min)
    	$(".center-c").find(".bit").html(bit)

    	_win.find(".do").unbind('click').bind("click",function(){
    		var _html  = ""
    		var _flag  = true
            $.each($(".center-txt"),function(indx,item){
            	_html += $(item).text()
            	if (indx == 0) {_html += ":"}
            })
            
            _win.attr(("am-" + (_this.hasClass('end') ? "end" : "start")),_html)
            if (_this.hasClass('end')) {
            	var _start = _win.attr("am-start")
            	if (parseFloat(_html.replace(":",".")) - parseFloat(_start.replace(":",".")) <= 0) {
            		 errorshowhide($("#time-select-error"))
            		 _flag = false
            	}else{
            		_win.hide()
            	}
            }else{
            	_win.hide()
            }
            if (_flag) {
		    	 _this.val(_html)
            }
            
        })
	})

	$(".ml50").focus(function(){
		$(".report-form").css('display','none')
		$(".report-result").css('display','block')


	})


	$(".pm").focus(function(){
		require("module/common/hc.popup.jquery")
    	var _this  = $(this).blur()
        var _win   = $("#SelectTimeWin")
        _win.removeAttr('am').removeAttr('pm').attr("pm",1)
        var _area  = ['13','14','15','16','17']
    	_win.hcPopup({'width':'400'}) 
    	var _val   = _this.hasClass('end') ? _area[4] : _area[0]
    	$(".center-txt").first().text(_val).attr("min",_area[0]).attr("max",_area[4]).end().eq(1).text("0").end().eq(2).text("0")
    	var datas = _this.val()
    	var hou = datas.slice(0,2)
    	var min = datas.slice(3,4)
    	var bit = datas.slice(-1)
    	$(".center-c").find(".double").html(hou)
    	$(".center-c").find(".min").html(min)
    	$(".center-c").find(".bit").html(bit)
    	 
    	_win.find(".do").unbind('click').bind("click",function(){
    		var _html  = ""
    		var _flag  = true
            $.each($(".center-txt"),function(indx,item){
            	_html += $(item).text()
            	if (indx == 0) {_html += ":"}
            })
            //_this.val(_html)
            _win.attr(("am-" + (_this.hasClass('end') ? "end" : "start")),_html)
            if (_this.hasClass('end')) {
            	var _start = _win.attr("am-start")
            	if (parseFloat(_html.replace(":",".")) - parseFloat(_start.replace(":",".")) <= 0) {
            		 errorshowhide($("#time-select-error"))
            		 _flag = false
            	}else{
            		_win.hide()
            	}
            }else{
            	_win.hide()
            }

            if (_flag) {
		    	_this.val(_html)
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
		var _flag   = true
		var _form   = $(this).parents("form")
		var _length = 0

		$.each($(".work-list-wrapper:eq(0) .day input[type='checkbox']"),function(){
			if ($(this).prop("checked")) {
				_length++
			}
		})
		 
		if (_length == 0) {
			errorshowhide($(".work-list-wrapper").eq(0).find(".error-div") )
			_flag = false
		}
		if ($(".work-list-wrapper:eq(1) input[value='']").length > 0) {
			errorshowhide($(".work-list-wrapper").eq(1).find(".error-div") )
			_flag = false
		}
		/*
		* 报价日报价有效时段
		*
		*/
		var _wp1  = $(".work-list-wrapper:eq(1)")
		var _ipt1 = _wp1.find("input[type='text']").eq(0),
		    _ipt2 = _wp1.find("input[type='text']").eq(1)
		    _ipt3 = _wp1.find("input[type='text']").eq(2)
		    _ipt4 = _wp1.find("input[type='text']").eq(3)
		if (
			(parseFloat(_ipt2.val().replace(":","")) - parseFloat(_ipt1.val().replace(":","")) <= 0)
			||
			(parseFloat(_ipt4.val().replace(":","")) - parseFloat(_ipt3.val().replace(":","")) <= 0)

			)
		{
			errorshowhide(_wp1.find(".error-div").eq(1))
			_flag = false
		}    
        //
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
		//开始日期和结束日期可以相等 操蛋的设定
		if (_timelist[1] - _timelist[0] < 0 ||_timelist[3] - _timelist[2] < 0 ) {
			errorshowhide($(".no-offer").eq(1).next().next())
			_flag = false
		}//判断交集( 1开 > 2开 && 1 开 < 2结 ) || ( 1结 > 2开 && 1结 < 2结 )
		else if ((_timelist[0] >= _timelist[2]  && _timelist[0] < _timelist[3]) || (_timelist[1] > _timelist[2]  && _timelist[1] < _timelist[3]) ) {
			errorshowhide($(".no-offer").eq(1).next().next().next())
			_flag = false
		}
		else if (_timelist[2] < _timelist[1]  && _timelist[3] < _timelist[1]  && _timelist[0] < _timelist[2]) {
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
                   window.location.reload();
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