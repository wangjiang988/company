define(function (require,exports,module) { 

	  $(".delseller").click(function(){	
	  	  require("module/common/hc.popup.jquery")
		  $("#delSeller").hcPopup().find(".do").unbind('click').bind("click",function(){
		  	 $.ajax({
                    url: 'delseller', //url为根据id获取用户信息的请求路径
                    type: "post",
                    dataType: "json",
                    data: { 
                    },
                    beforeSend: function () {

                    }
                    ,
                    success: function (data) {
                        
                        var _error_code = data.error_code;
                        var _error_msg = data.error_msg; 
                        var _win = _error_code == 1 ? $("#tip-succeed") : $("#tip-error") 
                        if (_error_code == 0 ) {
                           _win.hcPopup({content:"删除失败！"})
                        } 
                        else if (_error_code == 1) {
                           _win.hcPopup({content:"删除成功！"})
                        }
                        
                    }
                    ,
                    error:function(){
                    	_error_code = 1
                        var _win = _error_code == 1 ? $("#tip-succeed") : $("#tip-error") 
                        if (_error_code == 0 ) {
                           _win.hcPopup({content:"删除失败！"})
                        } 
                        else if (_error_code == 1) {
                           _win.hcPopup({content:"删除成功！"})
                        }
                    }
                })
		  	 
		  })

	  })
    

})


    
    