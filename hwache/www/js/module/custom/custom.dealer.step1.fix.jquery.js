define(function (require, exports, module) {
	$(document).delegate(".area-tab-div .dl:eq(1) dd", 'click', function(event) {
		var _label = $(this).parents("tr").next().find(".dropdown-label")
		_label.find("span").empty().html(_label.attr("data-def"))
		initLabel()
	})

	$(document).delegate(".area-tab-div .area-tab span:eq(0)", 'click', function(event) {
		 $(this).addClass('cur-tab').next().removeClass('cur-tab').parent().next().show().next().hide()
	})

	function initDealersGroup(){
		var _dropdown = $("#dealers-group .dropdown-menu")
		var _label = _dropdown.prev().find(".dropdown-label span")
		avalon.vmodels["custom"].dealerlist = []
		_dropdown.find("li.info").remove().end().append('<li class="tac info"><a><span class="red">请选择销售品牌和归属地区!</span></a></li>')
		_label.html(_label.parent().attr("data-def"))
	}

	function initArea(){
		var _label = $("#area-group .dropdown-label")
		_label.find("span").html(_label.attr("data-def"))
	}

	function initLabel(){
		$("#yy_place,#jc_place,#d_id").html("").next().val("")
		//$("input[type='text']").val()
	}

	initDealersGroup()

	$("#sell-brand li").click(function(){
		initDealersGroup()
		initArea()
		initLabel()
	})
 

})