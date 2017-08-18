define(function (require,exports,module) { 

	$(".counter-wrapper").modifiedBox()

	$("#mileage").blur(function(){
		if (!isempty($(this))) {
			var _val = parseFloat($(this).val())
			$(this).val(_val.into())
		}
	})

	$(".goods-add-link").click(function(){
		$(".select").show()
		$(".goods-modify-link").hide()
		$(".goods-control").show()
		$(this).parents("tr").hide().nextAll().show()
	})

	$(".goods-modify-link").click(function(){
		$(".select").show()
		$(".goods-modify-link").hide()
		$(".def").hide()
		$(".goods-control").show()
		$(".loading").show().each(function(){
			var _loading = $(this)
			var _id      = _loading.attr("data-id")
			$(".def").each(function(){
				if (_id == $(this).attr("data-id")) {
					_loading.find("td").eq(0).find("input[type='checkbox']").prop("checked",true).end().end().find("input[type='text']").removeAttr('disabled').siblings().removeClass('disabled-click')
				}
			})
		})

	})
	$(".goods-back-link").click(function(){
		$(".select").hide()
		$(".goods-modify-link").hide()
		$(".def").show()
		$(".loading").hide()
		if ($(".def").length == 0) {
			$('td[colspan="6"]').parent().show()
		}
		$(this).parent().hide()

	})


	

	$(".select-checked input[type='checkbox']").click(function(){
		if ($(this).prop("checked")) {
			$(this).parents("tr").find(".count").find("input[type='text']").removeAttr('disabled').siblings().removeClass('disabled-click')
		}else{
			$(this).parents("tr").find(".count").find("input[type='text']").attr('disabled','disabled').siblings().addClass('disabled-click')
		}
	})

	$(".goods-save-link").click(function(){
		$(".loading").hide()
		$(".select").hide()
		$(".goods-modify-link").show()
		$(".goods-control").hide()

		var _html = ""
		$.each($(".loading"),function(){
			var _clone   = $(this).clone()
			var _first   = _clone.removeClass("loading none").addClass('def').show().find("td").eq(0).hide()
			var _count   = _clone.find(".count")
			if (typeof(_count.find(".disabled-click")[0]) == "undefined") {
				_count.html("<span>"+_count.find("input").val()+"</span>")
				_html   += _clone.prop("outerHTML")
			}
		})
		$(".def").remove()
		$(".title-tag").after(_html)
		if ($(".def").length == 0) {
			$('td[colspan="6"]').parent().show()
			$(".goods-modify-link").hide()
		}
		

	})

	_CURWIN = null

	$(".add-solution").click(function(event) {
		require("module/common/hc.popup.jquery")
		var _win  = $("#SolutionAddWin")
		var _form = _win.find("form")
		_CURWIN   = _win
    	_win.hcPopup({'width':'450','top':'120px'}).find(".do").click(function(event) {
    		var _selectName = _win.find(".dropdown-menu input[type='hidden']")
    		if (isempty(_selectName)) {
    			errorshowhide(_selectName.parents(".btn-group").next())
    			return
    		}

    		var options = {
	            success: function (data) {
	               _win.hide()
	            }
	            ,
	            beforeSubmit:function(){}
	            ,
	            error:function(){
	               _win.hide()  
	            }
	            ,
	            clearForm:true
            }
         
	         // ajaxForm 
	         _form.ajaxForm(options) 
	         // 表单提交
	         _form.ajaxSubmit(options)  

    	})

	})

	$(".edit-solution").click(function(event) {
		require("module/common/hc.popup.jquery")
		var _win  = $("#SolutionEditWin")
		var _form = _win.find("form")
		_CURWIN   = _win
		//数据绑定
		$.ajax({
             type: "GET",
             url: "getSolution",
             data: {
                
             },
             dataType: "json",
             success: function(data){
 				 
             },
             error:function(){
             	//真实环境 请把error方法删除 别忘了error前面的那个逗号
             	var data = {'name':"全车脚垫","isInstall":1,"count":5}
             	_win.find(".dropdown-menu input[type='hidden']").val(data.name).end().find(".dropdown-label span").text(data.name)
             	.end().find("input[type='checkbox']").prop("checked",data.isInstall == 1 ? true : false)
             	.end().find("input[type='text']").val(data.count)
             	 
             }
        })

    	_win.hcPopup({'width':'450','top':'120px'}).find(".do").click(function(event) {
    		var _selectName = _win.find(".dropdown-menu input[type='hidden']")
    		if (isempty(_selectName)) {
    			errorshowhide(_selectName.parents(".btn-group").next())
    			return
    		}

    		var options = {
	            success: function (data) {
	               _win.hide()
	            }
	            ,
	            beforeSubmit:function(){}
	            ,
	            error:function(){
	               _win.hide()  
	            }
	            ,
	            clearForm:true
            }
         
	         // ajaxForm 
	         _form.ajaxForm(options) 
	         // 表单提交
	         _form.ajaxSubmit(options) 




    	})

	})

	$(".del-solution").click(function(event) {
		require("module/common/hc.popup.jquery")
		var _win  = $("#SolutionDelWin")
		var _form = _win.find("form")
    	_win.hcPopup({'width':'450','top':'120px'}).find(".do").unbind('click').click(function(event) {

    		var options = {
	            success: function (data) {
	               _win.hide()
	            }
	            ,
	            beforeSubmit:function(){}
	            ,
	            error:function(){
	               _win.hide()  
	            }
	            ,
	            clearForm:true
            }
         
	         // ajaxForm 
	         _form.ajaxForm(options) 
	         // 表单提交
	         _form.ajaxSubmit(options)  
    	})

	})

	$(".new").click(function(event) {
		_CURWIN.hide()
		require("module/common/hc.popup.jquery")
		var _win  = $("#SolutionNewWin")
		var _form = _win.find("form")
    	_win.hcPopup({'width':'450','top':'120px'}).find(".do").click(function(event) {
    		var _name = _win.find("input[type='text']")
    		if (isempty(_name)) {
    			errorshowhide(_name.parent().next())
    			return
    		}

    		var options = {
	            success: function (data) {
	               _win.hide()
	            }
	            ,
	            beforeSubmit:function(){}
	            ,
	            error:function(){
	               _win.hide()  
	            }
	            ,
	            clearForm:true
            }
         
	         // ajaxForm 
	         _form.ajaxForm(options) 
	         // 表单提交
	         _form.ajaxSubmit(options)  
    	})

	})






})