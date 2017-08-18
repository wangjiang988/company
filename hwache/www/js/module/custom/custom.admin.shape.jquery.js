define(function (require,exports,module) { 
	//下拉选项 默认选择第一个
	$(".default-value").each(function(){
		$(this).find("li").eq(0).click();
	})
	$(".counter-wrapper").modifiedBox()

	$("#mileage").blur(function(){
		var _val = parseFloat($(this).val())
		var _error = $("#mileage").parent().find(".error-div");
		/*if (!isempty($(this))) {
			var _val = parseFloat($(this).val())
			$(this).val(_val.into())
		}*/
		if (isNaN(_val)) {
			errorshow(_error)
			//$(this).focus()
	 	 	return
	 	}else{
	 		$(this).val(_val.into())
			errorhide(_error)
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
	$(document).on("click",".add-solution",function(event) {
		require("module/common/hc.popup.jquery")
		var _win  = $("#SolutionAddWin")
		var _form = _win.find("form")
		_CURWIN   = _win
    	_win.hcPopup({'width':'450','top':'120px'}).find(".do").unbind('click').click(function(event) {
    		var _selectName = _win.find(".dropdown-menu input[type='hidden']")
    		if (isempty(_selectName)) {
    			errorshowhide(_selectName.parents(".btn-group").next())
    			return
    		}

    		var options = {
				dataType: "json",
	            success: function (data) {
	            	if(data.error_code==1){
						$("#tip-error").hcPopup({content:data.message})
					}else{
						//window.location.reload();
						
						var tr_data = ''; 
						$.each(data.data,function(i,n){
								if(n.is_install==1){
									intallStr = '已安装';
								}else{
									intallStr = '/';
								}
								tr_data=tr_data+"<tr class='tr-zengpin'>";
								tr_data=tr_data+"<td class='tac'><span>"+n.zp_title+"</span></td>"
								tr_data=tr_data+"<td class='tac'><span>"+n.num+"</span></td>"
								tr_data=tr_data+"<td class='tac'><span>"+intallStr+"</span></td>"
								tr_data=tr_data+"<td class='tac'>"
									tr_data=tr_data+"<a href='javascript:;' class='juhuang tdu edit-solution' data-id='"+n.id+"' data-value='"+n.zengpin_id+"' data-install='"+n.is_install+"'>修改</a>"
									tr_data=tr_data+"<a href='javascript:;' class='juhuang tdu ml10 del-solution' data-id='"+n.id+"' >删除</a>"
								tr_data=tr_data+"</td>"
								tr_data=tr_data+"</tr>";
						})
						$(".tr-zengpin").remove()
						$("#table-zengpin").append(tr_data);
						_win.hide()
						$(".hidenTr").hide();
					}
					//清空选择
					_win.find(".dropdown-label span").text("--\u8bf7\u9009\u62e9\u514d\u8d39\u793c\u54c1\u548c\u670d\u52a1--")
					_win.find("input[name='num']").val(1)
					_win.find("input[type='checkbox']").prop("checked",false)
					_win.find(".dropdown-menu input[type='hidden']").val("")
	            }
	            ,
	            beforeSubmit:function(){}
	            ,
	            error:function(){
	               _win.hide()  
	            }
	            ,
	            clearForm:false
            }
         
	         // ajaxForm 
	         _form.ajaxForm(options) 
	         // 表单提交
	         _form.ajaxSubmit(options)  

    	})

	})
	$(document).on("click",".edit-solution",function(event) {
		require("module/common/hc.popup.jquery")
		var _win  = $("#SolutionEditWin")
		var _form = _win.find("form")
		_CURWIN   = _win
		_form.find("input[name=id]").val($(this).attr('data-id'));
		_form.find("input[name=zengpin_id]").val($(this).attr('data-value'));
		
		_form.find("input[name=num]").val($(this).parent().prev().prev().text());
		_form.find(".dropdown-label").find('span').text($(this).parent().prev().prev().prev().text());
		if($(this).attr('data-install')==1){
			_form.find("input[name=is_install]").prop('checked',true);
		}else{
			_form.find("input[name=is_install]").prop('checked',false);
		}
		//alert(_form.find("input[name=id]").val());
		//return false;
    	_win.hcPopup({'width':'450','top':'120px'}).find(".do").unbind('click').click(function(event) {
    		var _selectName = _win.find(".dropdown-menu input[type='hidden']")
    		if (isempty(_selectName)) {
    			errorshowhide(_selectName.parents(".btn-group").next())
    			return
    		}

    		var options = {
				dataType: "json",
	            success: function (data) {
					if(data.error_code==1){
						$("#tip-error").hcPopup({content:data.message})

					}else{
						$("#tip-succeed").hcPopup({content:data.message})
						var tr_data = ''; 
						$.each(data.data,function(i,n){
							if(n.is_install==1){
								intallStr = '已安装';
							}else{
								intallStr = '/';
							}
							tr_data=tr_data+"<tr class='tr-zengpin'>";
							tr_data=tr_data+"<td class='tac'><span>"+n.zp_title+"</span></td>"
							tr_data=tr_data+"<td class='tac'><span>"+n.num+"</span></td>"
							tr_data=tr_data+"<td class='tac'><span>"+intallStr+"</span></td>"
							tr_data=tr_data+"<td class='tac'>"
							tr_data=tr_data+"<a href='javascript:;' class='juhuang tdu edit-solution' data-id='"+n.id+"' data-value='"+n.zengpin_id+"' data-install='"+n.is_install+"'>修改</a>"
							tr_data=tr_data+"<a href='javascript:;' class='juhuang tdu ml10 del-solution' data-id='"+n.id+"' >删除</a>"
							tr_data=tr_data+"</td>"
							tr_data=tr_data+"</tr>";
						})
						$(".tr-zengpin").remove()
						$("#table-zengpin").append(tr_data);
						_win.hide()
					}
	            }
	            ,
	            beforeSubmit:function(){}
	            ,
	            error:function(){
	               _win.hide()  
	            }
	            ,
	            clearForm:false
            }
         
	         // ajaxForm 
	         _form.ajaxForm(options) 
	         // 表单提交
	         _form.ajaxSubmit(options)  

    	})

	})

	$(document).on("click",".del-solution",function(event) {
		require("module/common/hc.popup.jquery")
		var _win  = $("#SolutionDelWin")
		var _form = _win.find("form")
		var delTr = $(this).parent().parent();
		_form.find("input[name=id]").val($(this).attr('data-id'));
    	_win.hcPopup({'width':'450','top':'120px'}).find(".do").unbind('click').click(function(event) {
    		var options = {
				dataType: "json",
	            success: function (data) {

	                if(data.error_code==1){
					   $("#tip-error").hcPopup({content:data.message})
					}else{
						delTr.remove();
						_win.hide()
						setTimeout(function(){
							if($(".tr-zengpin").length == 0){
								$(".hidenTr").removeClass('hide').show()
							}
						},100)
					}
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
				dataType: "json",
	            success: function (data) {
	               if(data.error_code==1){
					   $("#tip-error").hcPopup({content:data.message})
						//alert(data.message);
					}else{
						//window.location.reload();
						$("#tip-info-success").hcPopup({content:data.message})
						_win.hide()
						_form.find('input[name=title]').val('');
					}
	            }
	            ,
	            beforeSubmit:function(){}
	            ,
	            error:function(){
	               _win.hide()  
	            }
	            ,
	            clearForm:false
            }
	         // ajaxForm 
	         _form.ajaxForm(options) 
	         // 表单提交
	         _form.ajaxSubmit(options)  
    	})

	})






})
