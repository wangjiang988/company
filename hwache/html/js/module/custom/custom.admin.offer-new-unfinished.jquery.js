define(function (require,exports,module) { 

	 
	$(".del").click(function(){

		var _this = $(this)
		var _tr   = _this.parents("tr")
		var _win  = $("#DelWin")
		require("module/common/hc.popup.jquery")
        _win.hcPopup({'width':'450'}).find(".do").unbind('click').click(function(event) {
        	//这是个真删操作...
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

	$(".reset").click(function(){
		window.location.reload()
	})

})