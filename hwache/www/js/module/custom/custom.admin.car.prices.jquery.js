define(function (require,exports,module) { 

	//keyup
	$(".tbl-price input").blur(function(event) {
		var _val = $(this).val()
		$(this).val(_val.toString().replace(/[+,-,*,/]/g,'').replace(/-/g,''))
	})

	//价格设置 
	function moneyFormat(obj){
		var _val = obj.val() || obj.text()
		var _price = parseFloat(_val).formatMoney(2,"")
		obj.parents("td").next().find("span").text(changeNumMoneyToChinese(obj))
		if (obj[0].tagName.toLowerCase() == "input")
			obj.val(_price) 
		else
			obj.text(_price)
		return _price
	}

	function initMoney(obj){
		var _val = obj.val()   
		var _price = _val.split(",").join("")
		if (_price.indexOf(".") != -1) {
			if (_price.split('.')[1] == "00") {
				_price = _price.split('.')[0]
			}
		}
		//console.log(parseFloat(_price).toRmbString())
		obj.val(_price)//.select()   
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
		  money = parseFloat(money.split(",").join("")).toFixed(2);
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
			if (DecimalNum == "00") {
			  ChineseStr += cnInteger;
			}
		    //整型部分处理完毕
		  }
		  if (DecimalNum != '') { //小数部分
		    var decLen = DecimalNum.length;
		    for (var i = 0; i < decLen; i++) {
		      var n = DecimalNum.substr(i, 1);
		      if (n != '0' && i<2) {//modify by jerry
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

		if ($(this).attr('name') == 'bj_lckp_price') {
			//console.log($(this).val().toString().indexOf("."))
			var _idx1 , _idx2 ;
			var _val = $(this).val()
			_idx1 = _val.toString().indexOf(".")
			_idx2 = _val.toString().lastIndexOf(".")
			if (_idx1 != _idx2) {
				_val = _val.substr(0,_idx2) 
			} 
			var limit = Math.round(Math.round(_val) * 0.03);
			if (limit > 50000) {
				limit = 50000;
			}
			$("input[name='bj_agent_service_price']").attr('placeholder', '0~' + limit);
			if ($("input[name='bj_agent_service_price']").val() == '0.00') {
				$("input[name='bj_agent_service_price']").val('');
			}
		}
		moneyFormat($(this))
		$(this).parents("td").next().find("span").text(changeNumMoneyToChinese($(this)))
	})

	//GPS定位
	_GPS = null
	function getGPS(){
		if($("input[name=gps_city]").length>=1){
			var cityName = $("input[name=gps_city]").val();//根据城市定位
			if(cityName!=''){
				var obj = $("#province-city-select").find("li[data-value*="+cityName.trim()+"]");
				obj = obj.parent().parent().parent().find('dt').find('span:eq(0)');
				_GPS = obj.attr('id');
			}
		}
	}

	getGPS()

	var _win = $("#SelectTimeWin")
	$(".area-readonly-wrapper").click(function(){
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
    	$("#province-city-select").scrollTop(_height)
    	
    	
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
	$("input[name='bj_is_xianche']").click(function(){
		if($(this).val()==0 && $(this).prop('checked')==true){
			$('input[name=bj_dealer_internal_id]').val('');
		}
	})

	$(".autocomplete").blur(function(){
		var _val   = $(this).val()
		var _error = $(this).next()
		if (_val.length > 20) {
			errorshow(_error)
		}else{
			var _reg = /^[a-zA-Z0-9]{1,20}$/
			if (_val!='' && !_reg.test(_val)) {
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
			errorhide(_error2)
		}
		else if (parseFloat(_val) < parseFloat(_guided) * 0.7) {
			errorshow(_error2)
			errorhide(_error)
		}
		var _max = Math.round((parseFloat(_val)*0.03))
		_max = _max > 50000 ? 50000 : _max
		//$(".service-fee").parents("td").next().find("label:eq(1)").html("0.00~"+_max+".00")
		$("input[name='bj_agent_service_price']").attr("placeholder","0.00~"+_max+".00")
	}).keypress(function(event) {
		errorhide($(this).next())
		errorhide($(this).next().next())
		errorhide($(".error-invoice"))
		//修改金额后，检查服务费是否正常
		var _val    = $(this).val().split(",").join("")
		var service_fee     = $(".service-fee").val().split(",").join("")
		if (parseFloat(service_fee) > Math.round(parseFloat(_val) * 0.03)) {
			errorhide($(".service-fee").next());
		}
	})
	//我的服务费的验证
	$(".service-fee").blur(function(){
		try
		{
		    var _val     = $(this).val().split(",").join("")
			var _invoice = $(".invoice-price").val().split(",").join("")
			var _error  = $(this).next()
			if (parseFloat(_val) > Math.round(parseFloat(_invoice) * 0.03)) {
				errorshow(_error)
			}else{
				errorhide(_error)
			}	 
		}
		catch(err)
		{
		   
		}
		
		 
	}).keypress(function(event) {
		errorhide($(this).next()) 
	})

	//客户买车定金验证
	$(".doposit-fee").blur(function(){
		try
		{
		    var _val     = $(this).val().split(",").join("")
			var _invoice = $(".invoice-price").val().split(",").join("")
			var _guide = $("input[name='zhidaojia']").val().split(",").join("")
			var _error  = $(this).next()
			if (parseFloat(_val) > Math.round(parseFloat(_invoice) * 0.5)) {
				errorshow(_error)
				return
			}
			if (parseFloat(_val) > Math.round(parseFloat(_guide) * 0.5)) {
				errorshow(_error)
				return
			}
			errorhide(_error)
			errorhide($(".error-payment"))
		}
		catch(err)
		{
		   
		}
		
	}).keypress(function(event) {
		errorhide($(this).next())
		errorhide($(".error-payment"))
	})


	_win.find("i").click(function(){
		_win.hide()
		$(".city-list").remove()
	}).end().find(".do").unbind("click").click(function(){
		//点击城市选择中的保存后...
		var _html = ""
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
					if (_leg !== _legCheck) {
					
						//当前的_html最后面是有一个';'的去掉它 然后加括号
						_html = _html.slice(0, _html.length - 1)
						_html +="("
						var _cityArr  = []
						$.each(_cityList,function(){
							//debugger
							if ($(this).prop("checked")){
								_cityArr.push($(this).next().text())
							}
						})
						_html += _cityArr.join(",")
						_html +=");"
						//alert(_html);
					}

				}
			})
			
		})

		$(".area-textarea").val(_html)
		_win.hide()
		//_win.hide().find("input[type='checkbox']").prop("checked",false) //隐藏并清空选中的值
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

    var _OBJ = null //记录点击的是哪个li.province
   

	$(document).delegate('.city-list li', 'click', function(event) {
		//console.log("xx")
	})	

	
	$(".btn-jquery-event-dealer").delegate('li', 'click', function(event) {
		//var data = [] //清空车辆内部编号

        var _this   = $(this)
        var _brand_id = _this.attr('data-car-brand-id');
        var _brand = _this.attr('data-car-brand');
        var _dealer_id = _this.attr('data-dealer-id');
		var _daili_dealer_id =  _this.attr('data-daili-dealer-id');
        $("span[name='dealer-area-str']").text(_this.attr('data-dealer-area').replace('(','').replace(')',''));
        $("input[name='dealer_id']").val(_dealer_id);
		$("input[name='daili_dealer_id']").val(_daili_dealer_id);
        $("#brand-str-default").text(_brand);
        $("#chexi-label-str").text('请选择车系');
        $("#chexing-label-str").text('请选择车型规格');
        $("input[name='car-brand-id']").val('');
        $("input[name='car-chexi-id']").val('');
        $("input[name='car-chexing-id']").val('');
		$("textarea").val(_this.attr('data-dealer-area')+";");
		$("input[name*='sale_area']").attr('checked',false);
		$("input[name='sale_area["+_this.attr('data-dealer-area-id')+"]']").prop('checked',true);
		$("input[name='hfbrand']").val(_brand);
        $("input[name='hfbrand-chexi']").parent().find('li').remove();
        $("input[name='hfbrand-chexing']").parent().find('li').remove();
        
       
        //$("#brand-str-for-span").text(_brand);
        var _parant = _this.parents(".btn-group")
        _this.addClass('active').siblings().removeClass("active")
        _parant.find("input[type='hidden']").val(_this.text())
        _parant.find(".dropdown-label span").text(_this.text())
        $("input[name='car-brand-id']").val(_brand_id);
        $("input[name='hfbrand']").val(_brand);
        
        $("#chexi-label-str").text('请选择车系');
        $("#chexing-label-str").text('请选择车型规格');
        $("input[name='car-chexi-id']").val('');
        $("input[name='car-chexing-id']").val('');
		$("#official_url").addClass('hide')
		var _daili_dealer_id = $("input[name='daili_dealer_id']").val();
        $.getJSON("/dealer/baojia/ajax-get-data/get-chexi-by-dealer?brand_id="+_brand_id+"&dealer_id="+_dealer_id+"&daili_dealer_id="+_daili_dealer_id, function(data){
        	 var  str='';
        	 $.each(data,function(item,index){
        		 str=str+"<li data-brand-id='"+index.gc_id+"'><a><span>"+index.gc_name+"</span></a></li>";
        	 })
        	 $("input[name='hfbrand-chexi']").parent().find('li').remove();
        	 $("input[name='hfbrand-chexi']").after(str);
        	 if(str==''){
        		 $("#chexi-label-str").html('<font color="red">没有对应的车系</font>');
        	 }
        });
		$(".autocomplete").flushCache();//清除自动加载内部编号的数据
    })
    
   
    
    $(".btn-jquery-event-brand-chexi").delegate('li', 'click', function(event) {
        var _this   = $(this)
        var _brand_id = $(this).attr('data-brand-id');
        var _parant = _this.parents(".btn-group")
        var _dealer_id = $("input[name='dealer_id']").val();
        _this.addClass('active').siblings().removeClass("active")
        _parant.find("input[type='hidden']").val(_this.text())
        _parant.find(".dropdown-label span").text(_this.text())
        $("#chexing-label-str").text('请选择车型规格');
        $("input[name='car-chexi-id']").val(_brand_id);
		
		
        $("input[name='car-chexing-id']").val('');
		var _daili_dealer_id = $("input[name='daili_dealer_id']").val();
        $.getJSON("/dealer/baojia/ajax-get-data/get-chexing-by-dealer?brand_id="+_brand_id+"&dealer_id="+_dealer_id+"&daili_dealer_id="+_daili_dealer_id, function(data){
        	 var  str='';
        	 $.each(data,function(item,index){
        		 str=str+"<li data-brand-id='"+index.gc_id+"' data-staple-id='"+index.staple_id+"'><a><span>"+index.gc_name+"</span></a></li>";
        	 })
        	 $("input[name='hfbrand-chexing']").parent().find('li').remove();
        	 $("input[name='hfbrand-chexing']").after(str);
        	 if(str==''){
        		 $("#chexi-label-str").html('<font color="red">没有对应的车型</font>');
        	 }
        });
		$(".autocomplete").flushCache();//清除自动加载内部编号的数据
    })
   function initAutocomplete(url){
        var _this = $(".autocomplete")
		_this.flushCache();
        if (_this[0]) {
            require("vendor/jquery.autocomplete")
            var data = []
            //debugger
            $.ajax({
                 type: "GET",
                 url: url,
                 async:false,
                 data: {
                    key:_this.val()
                 },
                 dataType: "json",
                 success: function(datas){
                    data = datas
                 },
                 error: function(){
                   // data = [{name:"jquery",id:"1002"},{name:"javascript",id:"1042"},{name:"react",id:"1005"},{name:"05697",id:"1005"},{name:"05597",id:"1005"}]
                 }
            })
			_this.autocomplete(data, {
				width:_this.width() + 26,
				max:10,
				formatItem: function (row, i, max) {
					return row.name
				},
				formatMatch: function(row, i, max){
					return row.name
				}
			})
			

            
        }
    }

    $(".btn-jquery-event-brand-chexing").delegate('li', 'click', function(event) {
        var _this   = $(this)
        var _brand_id = $(this).attr('data-brand-id');
        var _parant = _this.parents(".btn-group")
        _this.addClass('active').siblings().removeClass("active")
        _parant.find("input[type='hidden']").val(_this.text())
        _parant.find(".dropdown-label span").text(_this.text())
        $("input[name='car-chexing-id']").val(_brand_id);
		$("input[name='car-staple-id']").val($(this).attr('data-staple-id'));
        $.getJSON("/dealer/baojia/ajax-get-data/get-carinfo-by-brand-id?brand_id="+_brand_id, function(data){
        	$("#zhidaojia-price-str").text(parseFloat(data.zhidaojia).formatMoney(2,""));
        	$("input[name='zhidaojia']").val(data.zhidaojia);
			$("input[name='bj_lckp_price").attr('data-bind',data.zhidaojia);
			$("#zhidaojia-chinese-price").text(changeNumMoneyToChinese($("input[name='zhidaojia']")));
			
        	$("span[name='seat-num-str']").text(data.seat_num);
        	$("span[name='vehicle_model-str']").text(data.vehicle_model);
			$("#official_url").attr('href',data.official_url);
			$("#official_url").removeClass('hide')
			//服务费设限制
			//var service_fee = Math.round(Math.round(data.zhidaojia)*0.03);
			//if(service_fee>=50000){
				//service_fee = 50000;
			//}
			//$("input[name='bj_agent_service_price']").attr("placeholder",'0~'+service_fee).attr('data-limit',service_fee).val('');
			
       });

	   initAutocomplete("/dealer/baojia/ajax-get-data/get-internal-id?brand_id="+$("input[name='car-chexing-id']").val()+"&dealer_id="+$("input[name='dealer_id']").val())
    })
})