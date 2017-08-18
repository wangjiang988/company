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



	$(".pause").click(function(){

		var _this = $(this) 
		var _win  = $("#ShelvesWin")
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
        	var _pause = $('tr[data-pause="1"]')
			if (_pause.length == 0) {
				_win.hide() 
				$("#NoWin").hcPopup({'width':'450'}).find(".do").unbind('click').click(function(event) {
					        
		        }) 

			}else{
				
				
			}

        }) 

		

        /*_win.hcPopup({'width':'450'}).find(".do").unbind('click').click(function(event) {
        	 
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

        }) */

	})

})