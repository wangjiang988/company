define(function (require,exports,module) { 

	 $(".psr").hover(function(){
	 	$(this).find(".th-tip").stop().fadeIn(200)
	 },function(){
	 	$(this).find(".th-tip").stop().fadeOut(200)
	 })

	 $(".custom-control-min").blur(function(){
	 	 var _val = $(this).val()
	 	 if (isNaN(_val)) {
	 	 	$(this).val(1)
	 	 }else if(parseInt(_val) > 100){
	 	 	$(this).val(100)
	 	 }
	 	 else {
	 	 	$(this).val(parseInt(_val))
	 	 }
	 }).change(function(){
	 	var _this = $(this)
	 	var _val  = _this.val()
	 	var _tbl  = _this.parents("p").next()
	 	var _tr   = _tbl.find("tr:eq(1)")
	 	var _td   = _tr.find("td")
	 	if (_td.length == 8) {
	 		$.each(_tbl.find("tr"),function(idx,tr){
	 			if (idx != 0) {
	 				var _guided       = $(tr).find("td:eq(4)").find("span").text().split(",").join("")
	 				var _installation = $(tr).find("td:eq(5)").find("span").text().split(",").join("")
	 				var _total        = $(tr).find("td:eq(6)").find("span")
	 				var _sum 		  = (_val / 100) * (parseFloat(_guided)) + parseFloat(_installation)
	 				_total.text(_sum.formatMoney(2,""))
	 			}
	 		})
	 	}else{
	 		$.each(_tbl.find("tr"),function(idx,tr){
	 			if (idx != 0) {
	 				var _guided = $(tr).find("td:eq(4)").find("span").text().split(",").join("")
	 				var _total  = $(tr).find("td:eq(5)").find("span")
	 				var _sum 	= (_val / 100) * (parseFloat(_guided))  
	 				_total.text(_sum.formatMoney(2,""))
	 			}
	 		})
	 	}

	 })

})