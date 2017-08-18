define(function (require,exports,module) { 

      $(".delseller").click(function(){ 
          require("module/common/hc.popup.jquery")
          var delBt = $(this);
          $("#delSeller").hcPopup().find(".do").unbind('click').bind("click",function(){
             var _daili_dealer_id= delBt.attr('data-daili-dealer-id');
             var _dealer_id= delBt.attr('data-dealer-id');
             $.ajax({
                    url: '/dealer/del-dealer/'+_daili_dealer_id+'/'+_dealer_id, //url为根据id获取用户信息的请求路径
                    type: "get",
                    dataType: "json",
                    data: { 
                    },
                    beforeSend: function () {

                    }
                    ,
                    success: function (data) {
                        var _error_code = data.error_code;
                        var _error_msg = data.message; 
                        var _win = _error_code == 0 ? $("#tip-succeed") : $("#tip-error") 
                        if (_error_code == 1 ) {
                           _win.hcPopup({content:_error_msg,callback:function(){
                                
                           }})
                        } 
                        else if (_error_code == 0) {
                           _win.hcPopup({content:_error_msg,callback:function(){
                            $("#delSeller").hide();
                            window.location.href='/dealer/editdealer/add/0';
                           }})
                        }
                        
                    }
                    ,
                    error:function(){
                        _error_code = 1
                        var _win = _error_code == 0 ? $("#tip-succeed") : $("#tip-error") 
                        if (_error_code == 1 ) {
                           _win.hcPopup({content:"删除失败！"})
                        } 
                        else if (_error_code == 1) {
                           _win.hcPopup({content:"删除成功！",callback:function(){
                            window.location.href='/dealer/editdealer/add/0';
                           }})
                        }
                    }
                })
             
          })

      })
    

})