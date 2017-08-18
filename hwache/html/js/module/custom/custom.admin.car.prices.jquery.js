define(function (require,exports,module) { 

	
	//价格设置 
	function moneyFormat(obj){
		var _val = obj.val() || obj.text()
		var _price = parseFloat(_val).formatMoney(2,"")
		obj.parents("td").next().find("span").text(changeNumMoneyToChinese(obj))
		if (obj[0].tagName.toLowerCase() == "input")
			obj.val(_price) 
		else
			obj.text(_price)
	}

	function initMoney(obj){
		var _val = obj.val()   
		var _price = _val.split(",").join("")
		//console.log(parseFloat(_price).toRmbString())
		obj.val(_price)   
	}

	function changeNumMoneyToChinese(obj) {
		  var money = obj.val() || obj.text()
		  var cnNums = new Array("零", "壹", "贰", "叁", "肆", "伍", "陆", "柒", "捌", "玖"); //汉字的数字
		  var cnIntRadice = new Array("", "拾", "佰", "仟"); //基本单位
		  var cnIntUnits = new Array("", "万", "亿", "兆"); //对应整数部分扩展单位
		  var cnDecUnits = new Array("角", "分", "毫", "厘"); //对应小数部分单位
		  var cnInteger = "整"; //整数金额时后面跟的字符
		  var cnIntLast = "元"; //整型完以后的单位
		  var maxNum = 999999999999999.9999; //最大处理的数字
		  var IntegerNum; //金额整数部分
		  var DecimalNum; //金额小数部分
		  var ChineseStr = ""; //输出的中文金额字符串
		  var parts; //分离金额后用的数组，预定义
		  if (money == "") {
		    return "";
		  }
		  money = parseFloat(money);
		  if (money >= maxNum) {
		    alert('超出最大处理数字');
		    return "";
		  }
		  if (money == 0) {
		    ChineseStr = cnNums[0] + cnIntLast + cnInteger;
		    return ChineseStr;
		  }
		  money = money.toString(); //转换为字符串
		  if (money.indexOf(".") == -1) {
		    IntegerNum = money;
		    DecimalNum = '';
		  } else {
		    parts = money.split(".");
		    IntegerNum = parts[0];
		    DecimalNum = parts[1].substr(0, 4);
		  }
		  if (parseInt(IntegerNum, 10) > 0) { //获取整型部分转换
		    var zeroCount = 0;
		    var IntLen = IntegerNum.length;
		    for (var i = 0; i < IntLen; i++) {
		      var n = IntegerNum.substr(i, 1);
		      var p = IntLen - i - 1;
		      var q = p / 4;
		      var m = p % 4;
		      if (n == "0") {
		        zeroCount++;
		      } else {
		        if (zeroCount > 0) {
		          ChineseStr += cnNums[0];
		        }
		        zeroCount = 0; //归零
		        ChineseStr += cnNums[parseInt(n)] + cnIntRadice[m];
		      }
		      if (m == 0 && zeroCount < 4) {
		        ChineseStr += cnIntUnits[q];
		      }
		    }
		    ChineseStr += cnIntLast;
		    //整型部分处理完毕
		  }
		  if (DecimalNum != '') { //小数部分
		    var decLen = DecimalNum.length;
		    for (var i = 0; i < decLen; i++) {
		      var n = DecimalNum.substr(i, 1);
		      if (n != '0') {
		        ChineseStr += cnNums[Number(n)] + cnDecUnits[i];
		      }
		    }
		  }
		  if (ChineseStr == '') {
		    ChineseStr += cnNums[0] + cnIntLast + cnInteger;
		  } else if (DecimalNum == '') {
		    ChineseStr += cnInteger;
		  }
		  return ChineseStr;
	 
	}
	

	$.each($(".format-money"),function(){
		moneyFormat($(this))
	})

	$(".format-money").focus(function(){
		initMoney($(this))
	}).blur(function(){
		$(this).parents("td").next().find("span").text(changeNumMoneyToChinese($(this)))
		moneyFormat($(this))
	})

	//GPS定位
	_GPS = null
	function getGPS(){
		$.ajax({
             type: "GET",
             url: "getGPS",
             data: {
                
             },
             dataType: "json",
             success: function(data){
 				$.each($("#province-city-select .city"),function(){
					var _span = $(this).find("span")
					if (_span.text().trim() == data.cityname.trim() ) {
						var _id = _span.parents("dd").prev().find("span:eq(0)").attr("id") 
						_GPS = _id
					}
				})
             },
             error:function(){
             	//真实环境 请把error方法删除 别忘了error前面的那个逗号
             	var data = {'cityname':"长春市"}
             	$.each($("#province-city-select .city"),function(){
					var _span = $(this).find("span")
					if (_span.text().trim() == data.cityname.trim() ) {
						var _id = _span.parents("dl").find("dt span:eq(0)").attr("id") 
						 
						_GPS = _id
					}
				})
             }
        })
	}

	getGPS()

	var _win = $("#SelectTimeWin")
	$(".area-textarea").click(function(){
		$(this).blur() 
		//弹窗
		require("module/common/hc.popup.jquery")
    	_win.hcPopup({'width':'785','top':'120px'})
    	//GPS定位后的结果
    	var _a = $("a[href='#" + _GPS + "']")
    	_a.click()
    	var _span     = $("#"+_GPS)
    	var _parent   = _span.parents(".province-city-select-wrapper")
    	var _prevAll  = _parent.prevAll()
    	var _height   = 0
    	$.each(_prevAll,function(){
    		_height += $(this).height() + 10
    	})
    	//console.log(_height)
    	$("#province-city-select").scrollTop(_height)
    	//console.log(_a.offsetTop)
    	/*var _top = $("#"+_GPS).offset().top
    	var _height = $("#"+_GPS).height()
    	var _scroll = _top - _height
    	console.log(_top)
    	console.log(_height)
    	console.log(_scroll)
    	console.log($("#"+_GPS)[0])
		$("#province-city-select").scrollTop(_scroll)*/
    	
    	//绑定值
    	var _area = $(".area-textarea")
    	if (!isempty(_area)) {
    		var _val      = _area.val()
    		    _val      = _val.slice(0, _val.length - 1)
    		var _province = _val.split(";") 
    		var _cityArr  =  []
    		$.each(_province,function(index,item){
    			var _provObj  = _province[index] 
    			var _provText = ""
    			
    			if (_provObj.indexOf("(") > 1) {
    				//城市
    				_provText     = _provObj.slice(0, _provObj.indexOf("("))
    				var _citylist = _provObj.substring(_provObj.indexOf("(") + 1 , _provObj.indexOf(")")).split(',')
    				for (var i = 0; i < _citylist.length; i++) {
    					_cityArr.push(_citylist[i])
    				}  
    			}else{
    				//整个省份 
    				_provText = _provObj
    			}

    			$.each($("#province-city-select .province"),function(){
					if ($(this).prop("innerHTML") == _provText ) {
						$(this).prev().prop("checked",true)
						//判断是否整个省份
						if (_provText == _provObj) {
							$(this).parents("dt").next().find("input[type='checkbox']").prop("checked",true)
						}
					}
				})

				$.each($("#province-city-select .city"),function(){
					var _span = $(this).find("span")
					if (_cityArr.indexOf( _span.prop("innerHTML") ) != -1 ) {
						_span.prev().prop("checked",true)
					}
				})


    		})
    	}

	})
	_win.find("i").click(function(){
		_win.hide()
		$(".city-list").remove()
	}).end().find(".do").click(function(){
		//点击城市选择中的保存后...
		var _html = "" , _id = ""
		$.each($(".province-city-select-wrapper"),function(){
			//debugger
			var _this         = $(this)
			var _province     = _this.find(".check-all-province")
			$.each(_province,function(){
				//debugger
				var _next     = $(this).parents("dt").next()
				var _cityList = _next.find("input[type='checkbox']")
				var _leg      = _cityList.length 
				var _legCheck = _next.find("input[type='checkbox']:checked").length
				if ($(this).prop("checked") ) {
					_html += $(this).next().text() + ";"
					_id   += $(this).parent().attr("data-id") + ";"
					if (_leg !== _legCheck) {
					
						//当前的_html最后面是有一个';'的去掉它 然后加括号
						_html = _html.slice(0, _html.length - 1)
						_id   = _id.slice(0, _id.length - 1)
						_html +="("
						_id   +=","
						var _cityArr  = [], _cityIdArr  = []
						$.each(_cityList,function(){
							//debugger
							if ($(this).prop("checked")){
								_cityArr.push($(this).next().text())
								_cityIdArr.push($(this).parent().attr("data-id"))
							}
						})
						_html += _cityArr.join(",")
						_html +=");"
						_id   += _cityIdArr.join(",")
						_id   +=";"
					}

				}
			})
			
		})

		$(".area-textarea").html(_html)
		$("#hfProvinceCityId").val(_id)
		_win.hide().find("input[type='checkbox']").prop("checked",false) //隐藏并清空选中的值
	})

    //字母导航
	$(".letter-nav a").click(function(){
		$(this).find("span").addClass('cur').end().siblings().find("span").removeClass('cur')
	})
	//全国
	$(".check-all").click(function(){
		_win.find("input[type='checkbox']").prop("checked",$(this).prop("checked"))
	})
	//检查是否选中全国
	function checkIsAllChecked(obj){
		//debugger
		var _totalCheckLength = _win.find("input[type='checkbox']:checked").length
		var _status           = obj.prop("checked")
		if (!_status) {
			$(".check-all").prop("checked",false)
		}else{
			if (_totalLength == _totalCheckLength + 1 ) {
				$(".check-all").prop("checked",true)
			}
		}
	}
    //省份点击事件
	$(".check-all-province").click(function(){
		$(this).parents("dt").next().find("input[type='checkbox']").prop("checked",$(this).prop("checked"))
		checkIsAllChecked($(this))
	})
    
	var _totalLength      = _win.find("input[type='checkbox']").length
	//城市点击事件
	$(".area-city-list input[type='checkbox']").click(function(){
		var _this         = $(this)
		var _dd           = _this.parents("dd")
		var _dt           = _dd.prev()
		var _length       = _dd.find("input[type='checkbox']").length
		var _checklength  = _dd.find("input[type='checkbox']:checked").length
		if (_checklength == 0) {
			_dt.find("input[type='checkbox']").prop("checked",false)
		}else{
			_dt.find("input[type='checkbox']").prop("checked",true)
		}
		checkIsAllChecked(_this)
	}) 

	$(".autocomplete").blur(function(){
		var _val   = $(this).val()
		var _error = $(this).next()
		if (_val.length > 20) {
			errorshow(_error)
		}else{
			var _reg = /^(?=.*\d)|[a-z\d]{4,20}$/
			if (!_reg.test(_val)) {
				errorshow(_error)
			}else{
				errorhide(_error)
			}
		}
	})
	//裸车开票价格的验证
	$(".invoice-price").blur(function(){
		var _val    = $(this).val().split(",").join("")
		var _guided = $(this).attr("data-bind")
		var _error  = $(this).next()
		var _error2 = _error.next()
		var _error3 = _error2.next()
		var _error4 = $("#error-div")
		if (parseFloat(_val) > parseFloat(_guided)) {
			errorshow(_error)
		}
		else if (parseFloat(_val) < parseFloat(_guided) * 0.5) {
			errorshow(_error3)
			errorshow(_error4)
		}
		else if (parseFloat(_val) < parseFloat(_guided) * 0.7) {
			errorshow(_error2)
		}
		var _max = Math.round((parseFloat(_val)*0.03))
		_max = _max > 50000 ? 50000 : _max
		$(".service-fee").parents("td").next().find("label:eq(1)").html("0.00~"+_max+".00")
	}).keypress(function(event) {
		errorhide($(this).next())
		errorhide($(this).next().next())
	})
	//我的服务费的验证
	$(".service-fee").blur(function(){
		var _val     = $(this).val().split(",").join("")
		var _invoice = $(".invoice-price").val().split(",").join("")
		var _error  = $(this).next()
		if (parseFloat(_val) > parseFloat(_invoice) * 0.03) {
			errorshow(_error)
		}else{
			errorhide(_error)
		}
	}).keypress(function(event) {
		errorhide($(this).next()) 
	})

	$(".next").click(function(){

		var _autocomplete = $(".autocomplete")
		var _textarea     = $(".textarea")
		var _flag         = true

	 	$.each($(".btn-jquery-event"),function(){
	 		var _event = $(this)
	 		if (_event.find("input[type='hidden']").val().trim() == "") {
				_event.parents("td").find(".error-div").show()
				_flag = false
			}
	 	})
		
		if (_autocomplete.val() == "" && $("#xianche").prop("checked")) {
			_autocomplete.next().show()
			_flag = false
		}
		
		if (_textarea.html() == "" ) {
			_textarea.parent().next().show()
			_flag = false
		}

		if (_flag) {
			$("form").submit()
		}
	})
	$(".btn-jquery-event li").click(function(){
		$(this).parents("td").find(".error-div").hide()
	})

	$("#notxianche").click(function(event) {
		$(this).parents("tr").prev().find("input[type='text']").val("").next().hide()
	})


    var _OBJ = null //记录点击的是哪个li.province
    /*//阻止事件冒泡 以防止多次提交
    $(".area-province-list input[type='checkbox']").click(function(e){  
    	event = arguments.callee.caller.arguments[0] || window.event
	    if (event.stopPropagation) 
			event.stopPropagation() 
		else if (window.event)
			window.event.cancelBubble = true 
	    $(this).parents("li").click()

	})*/
	//阻止事件冒泡 以防止多次提交
	/*$(".area-province-list label").click(function(e){  
		event.stopPropagation()
	})*/
	/*$(".area-province-list input[type='checkbox']").click(function(){
		
		_OBJ = $(this)
 
		$.ajax({
             type: "GET",
             url: "getById",
             data: {
                id:"" 
             },
             dataType: "json",
             success: function(data){
                 
 
             },
             error:function(){
                //真实环境 请把error方法删除 别忘了error前面的那个逗号
                var data = [
                	{'cityname':'苏州','cityid':'5849'},
                	{'cityname':'无锡','cityid':'5849'},
                	{'cityname':'常州','cityid':'5849'},
                	{'cityname':'南京','cityid':'5849'},
                	{'cityname':'无锡','cityid':'5849'},
                	{'cityname':'连云港','cityid':'5849'},
                	{'cityname':'徐州','cityid':'5849'},
                	{'cityname':'扬州','cityid':'5849'},
                	{'cityname':'南通','cityid':'5849'},
                	{'cityname':'盐城','cityid':'5849'},
                	{'cityname':'镇江','cityid':'5849'},
                	{'cityname':'泰州','cityid':'5849'},
                	{'cityname':'淮海','cityid':'5849'}
                ]
                $(".city-list").remove()
                var _wrapper = $("#city-list-wrapper-tpl").html()
                var _item    = $("#city-list-item-tpl").html()
                var _html    = ""
                $.each(data,function(index, el) {
                	_html   += _item.replace("{0}",el.cityname).replace("{1}",el.cityid)
                }) 
                _wrapper     = _wrapper.replace("{0}",_html)
                var _split   = _OBJ.parents("li").nextUntil(".clear-split")
                var _appento = _split.length == 0 ? _OBJ.parents("li").next() : _split.last()  
                //console.dir(_OBJ.nextUntil(".clear-split"))
                _appento.after(_wrapper)
                _appento.next().find("input[type='checkbox']").prop("checked",_OBJ.prop("checked"))
             }
        }) 

	})*/

	/*$(document).delegate('.city-list li', 'click', function(event) {
		console.log("xx")
	})
*/
	




})