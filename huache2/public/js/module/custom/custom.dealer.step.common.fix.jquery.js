define(function (require, exports, module) {
	 
	$(document).delegate(".positive-integer", 'blur change', function(event) {
		positiveIntegerBlur(this)
	})

	$(".max-valite").bind("blur change",function(){
		var _val = parseFloat($(this).val())
		if (!isPositiveInteger(_val)) {
			if (_val < 0) {
				_val = -_val
			}
			if (_val > 100 ){
				_val = 100
			}
		}
		$(this).val(isNaN(_val) ? "" : _val) 
	})
	

	module.exports = {
        init:function(){
           
        } 
    }

})

function positiveIntegerBlur(obj){
	var _this = $(obj)
	_this.val(_this.val().split(".")[0])
}

function isPositiveInteger(num){
    var _reg = /^[1-9]\d*$/
    return _reg.test(num)
}

$(".area-tab-div .area-tab span").eq(0).click(function(){
	$(this).addClass('.cur-tab').next().removeClass('cur-tab').parent().next().show().next().hide()
})