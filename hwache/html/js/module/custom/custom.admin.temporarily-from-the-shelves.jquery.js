define(function (require,exports,module) { 

	 
	$(".del").click(function(){

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
	                _tr.fadeOut(200,function(){
	             		_tr.remove()
	             	})
	             },
	             error:function(){
	             	_win.hide()
	             	_tr.fadeOut(200,function(){
	             		_tr.remove()
	             	})
	             }
	        })

        }) 

	})



	$(".restore").click(function(){

		var _this   = $(this) 
		var _win    = $("#restoreSmipWin")
		var _tr     = _this.parents("tr")
		/*//每个tr 上面会有一个 data-status 属性 记录 当前报价的状态
		//为了模拟定义了三个状态 
		//0:主动暂时下架
		//1:资金池可提现余额不足
		//2:非工作原因
		//具体是什么改_status的值就可以了
		//优先度 资金池可提现余额不足 > 非工作原因
		var _status = _tr.attr("data-status")*/
		var _txt    = _tr.find("td:eq(0) a").text()
		_win.find(".msg").text(_win.find(".msg").text().replace("{0}",_txt))
		
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
		                _tr.fadeOut(200,function(){
		             		_tr.remove()
		             	})
		             },
		             error:function(){
		             	_win.hide()
		             	_tr.fadeOut(200,function(){
		             		_tr.remove()
		             	})
		             }
		        })
        	/*if (_status == 2) {
        		$("#restoreNoWorkTimeWin").hcPopup({'width':'450'}).find(".do").unbind('click').click(function(event) {

        		})
        		var _time     = 4
     			var _span     = $("#restoreNoWorkTimeWin").find(".seconds")
     			var _interval = setInterval(function(){
     				if (_time == 0) {
     					window.location.reload() 
     				}
     				_span.text(_time)
     				_time--

     			},1000)    

			}else if(_status == 1){
				$("#restoreNoMoneyWin").hcPopup({'width':'450'}).find(".do").unbind('click').click(function(event) {
        			
        		})
        		var _time     = 4
     			var _span     = $("#restoreNoMoneyWin").find(".seconds")
     			var _interval = setInterval(function(){
     				if (_time == 0) {
     					window.location.reload() 
     				}
     				_span.text(_time)
     				_time--

     			},1000)    

			}
			else{ }*/
	        	

	        

        }) 

	})

	$(".stop").click(function(){

		var _this = $(this) 
		var _win  = $("#StopWin")
		var _tr   = _this.parents("tr")
		_win.find(".msg").text(_win.find(".msg").text().replace("{0}",_tr.find("td:eq(0) a").text()))
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
	                _tr.fadeOut(200,function(){
	             		_tr.remove()
	             	})
	             },
	             error:function(){
	             	_win.hide()
	             	_tr.fadeOut(200,function(){
	             		_tr.remove()
	             	})
	             }
	        })

        }) 

	})


	$("#shelves-all").click(function(){

		var _this = $(this) 
		var _win  = $("#ShelvesAllWin")
		require("module/common/hc.popup.jquery")
		_win.hcPopup({'width':'450'}).find(".do").unbind('click').click(function(event) {
			//每个tr 上面会有一个 data-status 属性 记录 当前报价的状态
			//为了模拟定义了三个状态 
			//0:主动暂时下架
			//1:资金池可提现余额不足
			//2:非工作原因
			//具体是什么改_status的值就可以了
			var _status       = 0
        	var _statusLength = $('tr[data-status="'+_status+'"]').length 
			if (_statusLength == 0) {
				$("#NoWin").hcPopup({'width':'450'}).find(".do").unbind('click').click(function(event) {
					        
		        })  
			}else{
				$.ajax({
		             type: "GET",
		             url: "del",
		             data: {
		                id:'' 
		             },
		             dataType: "json",
		             success: function(data){
		             	_win.hide()
		             	var _restoreWin = $("#RestoreAllWin")
		             	_restoreWin.hcPopup({'width':'450'}).find(".msg")
		             	.html
		             	    (
			             		function(){
			             		 	var _html = [] 
			             		 	$.each($('tr[data-status="'+_status+'"]'),function(){
			             		 		_html.push( $(this).find("td:eq(0)").text() )
			             		 		$(this).remove()
			             		 	})
			             		 	return _html.join("、")
			             		}
		             		)
		             		.end().find(".do").unbind('click').click(function(event) { window.location.reload() })
						//		             		
             			var _time     = 4
             			var _span     = _restoreWin.find(".seconds")
             			var _interval = setInterval(function(){
             				if (_time == 0) {
             					window.location.reload() 
             				}
             				_span.text(_time)
             				_time--

             			},1000)    
                        _this.hide()
		                
		             },
		             error:function(){
		             	_win.hide()
		             	var _restoreWin = $("#RestoreAllWin")
		             	_restoreWin.hcPopup({'width':'450'}).find(".msg")
		             	.html
		             	    (
			             		function(){
			             		 	var _html = [] 
			             		 	$.each($('tr[data-status="'+_status+'"]'),function(){
			             		 		_html.push( $(this).find("td:eq(0)").text() )
			             		 		$(this).remove()
			             		 	})
			             		 	return _html.join("、")
			             		}
		             		)
		             		.end().find(".do").unbind('click').click(function(event) { window.location.reload() })
						//		             		
             			var _time     = 4
             			var _span     = _restoreWin.find(".seconds")
             			var _interval = setInterval(function(){
             				if (_time == 0) {
             					window.location.reload() 
             				}
             				_span.text(_time)
             				_time--

             			},1000)    
                        _this.hide()



		             }


		        })

				
			}
			_win.hide() 

        }) 
 

	})
 

})