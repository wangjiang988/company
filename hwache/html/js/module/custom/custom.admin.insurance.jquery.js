define(function (require,exports,module) { 

	$(".icon-disabled").hover(function(){
		$(".scope-tip").show()
	},function(){
		$(".scope-tip").hide()
	})

	$(".claims-scope").click(function(){
		var _this  = $(this)
		var _input = _this.parents("tr").next().find("input[type='checkbox']")
		$.each(_input,function(index,item){
			var _arr = _this.attr("data-bind").toString().split(",")
			_input.eq(index).prop("checked",_arr[index] == 1 ? true : false)
			if (index == 1 && _arr[1] == 0) {
				$(".icon-disabled").show()
			}else{
				$(".icon-disabled").hide()
			}
		})
		$(".card-txt-price").val("")
	})

	/*$.each($(".card-txt-price"),function(){
		var _this = $(this)
		if (isempty(_this) || isNaN(_this.val())) {
			$(this).focus().val(0)
			errorshow($("#inpurerror"))
		}
	})*/
	$(".card-txt-price").bind("change blur",function(){
		var _this = $(this)
		var _val  = _this.val()
		if (!isempty(_this)) {
			if ( !isNaN(_val)) {
				_this.removeClass('red-border')
				var _price = parseFloat(_val)
				if (_price < 0) {
					_this.val("0.00")
				}
				else if (_this.hasClass('card-txt-price-min')) {
					if (_price > 30) {
						_this.val(30)
					}else{
						 _this.val(parseInt(_val))
					}
				}else{
					_this.val(parseFloat(_val).formatMoney(2,""))
				}
				
			}else{
				_this.focus().select()
			}
		}
	})

	$(".next").click(function(){
		var _form  = $(this).parents("form")
		var _flag  = true 
		$.each($(".card-txt-price"),function(){
			var _this = $(this)
			if (isempty(_this) || isNaN(_this.val())) {
				$(this).addClass('red-border')//.focus()
				_flag = false
				errorshow($("#inpurerror"))
				//return false
			}
		})

		if (_flag) {
			var options = {
	                success: function (data) {
	                   
	                }
	                ,
	                beforeSubmit:function(){}
	                ,
	                error:function(){
	                   
	                }
	                ,
	                clearForm:true
	            }
	                         
		     // ajaxForm 
		     _form.ajaxForm(options) 
		     // 表单提交
		     _form.ajaxSubmit(options) 
	     }
		 
	})




})