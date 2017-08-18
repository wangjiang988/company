define(function (require,exports,module) { 

	 $(".psr").hover(function(){
	 	$(this).find(".th-tip").stop().fadeIn(200)
	 },function(){
	 	$(this).find(".th-tip").stop().fadeOut(200)
	 })

	 $(".custom-control-min").change(function(){
	 	 
		 var _val = $(this).val()
	 	 if (isNaN(_val)) {
	 	 	$(this).focus().select().next().next().show()
	 	 	return
	 	 }else if(parseInt(_val) > 100){
	 	 	$(this).val(100)
	 	 }
	 	 else {
	 	 	if (_val.trim() != "") {
		 	 	$(this).val(parseInt(_val))
		 	 	$(this).next().next().hide()
	 	 	}
	 	 }
	 	 

	 	var _this = $(this)  
		var _front = $("#tpl-is-front");
		var _not_front = $("#tpl-not-front");
	 	
 		$.each(_not_front.find("tr"),function(idx,tr){
 			if (idx != 0) {
 				var _guided       = $(tr).find("td:eq(4)").find("span").text().split(",").join("")
 				var _installation = $(tr).find("td:eq(5)").find("span").text().split(",").join("")
 				var _total        = $(tr).find("td:eq(6)").find("span")
 				var _sum 		  = (_val / 100) * (parseFloat(_guided)) + parseFloat(_installation)
 				_total.text(_sum.formatMoney(2,""))
 			}
 		})
 	
 		$.each(_front.find("tr"),function(idx,tr){
 			if (idx != 0) {
 				var _guided = $(tr).find("td:eq(4)").find("span").text().split(",").join("")
 				var _total  = $(tr).find("td:eq(5)").find("span")
 				var _sum 	= (_val / 100) * (parseFloat(_guided))  
 				_total.text(_sum.formatMoney(2,""))
 			}
 		})
	 	

	 })


	 $("#tpl-is-front,#tpl-not-front").find("input[type='checkbox']").click()

})