define(function (require, exports, module) {
	 require("vendor/jquery.form")
	 require("module/common/hc.popup.jquery")

	 var checkNum = function (num) {
        return /^[1-9]\d*00$/.test(num) 
     }
	 $(".positive-integer").blur(function(){
	 	if ($(this).val().trim() == "") {
	 		$(this).next().next().show()
	 	}
	 	else if (!checkNum($(this).val())) {
	 		$(this).next().show()
	 	}
	 }).keydown(function(){
	 	$(this).next().hide().next().hide()
	 })

	 $(".registrationEvent").click(function(){
	 	var _input = $(".positive-integer")
	 	var _btn    = $(this)
	 	if (_input.val().trim() == "") {
	 		_input.next().next().show()
	 	}
	 	else if (!checkNum(_input.val())) {
	 		_input.next().show()
	 	}else{

	 		 var _form  = $("form[name='next-form']")
             var options = {
                type: 'post',
                success: function(data) {
                   console.log(data)	
                   if(data.step == 4) {
                       var url = window.location.href
                       var site =url.substr(0,url.length-1);
                       window.location.href=site+5;
                   }
                   else if(data.error_code == 0 || data.error_code == 1){
                        if (_btn.hasClass('isnext')) {
                            window.location.reload()
                        }else{
                            $("#tip-succeed").hcPopup({content:'恭喜，修改成功！',callback:function(){
                                window.location.reload()
                            }})
                        }
                       
                       
                   }
                }
             }
             _form.ajaxForm(options).ajaxSubmit(options)
             
	 	}
	 })
	 

})
 