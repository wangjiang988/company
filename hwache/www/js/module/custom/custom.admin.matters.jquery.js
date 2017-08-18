define(function (require,exports,module) { 

	$(".next").click(function(){
		

	})

	$(".am").focus(function(){
		require("module/common/hc.popup.jquery")
    	var _this  = $(this).blur()  
        var _win   = $("#SelectTimeWin")
        var _area  = ['00','01','02','03','04','05','06','07','08','09','10','11','12']
    	_win.hcPopup({'width':'400'}) 
    	//var _val   = _this.hasClass('end') ? _area[3] : _area[0]
    	//$(".center-txt").first().text(_val).attr("min",_area[0]).attr("max",_area[3]).end().eq(1).text("0").end().eq(2).text("0")
    	if($(this).val()!=''){
    		var timeArr = $(this).val().split(':');
    		var h = timeArr[0];
    		var m1 = timeArr[1].substring(0,1);
    		var m2 = timeArr[1].substring(1,2);
    		$("#time-hour-str").text(h);
    		$("#time-minute-str-1").text(m1);
    		$("#time-minute-str-2").text(m2);
    	}
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
	$("input[name=bt_status]").click(function(){
		if($(this).val()==0 || $(this).prop('checked')==true){
			$(this).parent().next().find('input[type=text]').val('');
			$(this).parent().next().find('input[type=radio]').prop('checked',false);
		}
	})
	$("input[name=jnbt]").click(function(){
		$(this).parent().parent().find("input[name=bt_status]").prop('checked',true).end();
	})

	$(".card-input").focus(function(){
		$(this).parent().find('input[type=radio]').prop('checked',true).parents("p").siblings('p').find("input[type=text]").val("")
		$(this).parent().parent().parent().parent().find("input[name=bt_status]").prop('checked',true).end();
	})
})