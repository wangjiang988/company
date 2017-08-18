define(function (require,exports,module) {

    require("jq")
    var vm = avalon.define({
        $id: 'baoxian',
        init: function () {
            setTimeout(function(){
            	vm.sumTotalPrice()
            },1000)
        }
        ,
        totalprice : 0
        ,
        iszixuan:false
        ,
        sumTotalPrice:function(){
        	 
        	var _total = 0 
        	$.each(vm.baoxian,function(idx,item){
        		 //console.dir(avalon.isPlainObject(item))
        		 if (avalon.isPlainObject(item)) {}
        		 	 if (item.select) {

        		 	 	if (item.listselect) {
        		 	 		$.each(item.listselect.item,function(index,it){
        		 	 			////console.log(it.myprice)
        		 	 			_total += parseFloat(it.myprice)
        		 	 		})
        		 	 	}else{
        		 	 		_total += parseFloat(item.myprice)
        		 	 	}
        		 	 }
        		 	 //console.dir(item)
        		 
        	})
        	var _tmp = _total.toString()
        	var _splitindex = _tmp.indexOf('.')
        	if (_splitindex > 0) {
        		_total = _tmp.slice(0, _splitindex + 3)
        	}else{
        		_total = _total +".00"
        	}
        	//console.dir(_total)
        	vm.totalprice = _total
        	return _total
        }
        ,
        getBaoxianItem:function(index){
        	var i = 0
        	var flag = true
        	var _item = null
        	$.each(vm.baoxian,function(idx,item){
        		////console.log(index)
        		////console.log(idx)
        		if (i == index) {
        			flag = false
        			_item = item
        		}
        		i++ 
        		if (!flag) {
        			return 
        		}
        	})
        	 
        	return _item
        }
        ,
        changeStatus:function(index){
        	var item = vm.getBaoxianItem(index)
        	if (item) {
        		item.select       = !item.select
        		item.myprice      = item.select ? item.defprice : 0
        		//radio select
        		item.selectprice  = item.select ? item.defselectprice : 0
        	}
        }
        ,
        changeStatusMuli:function(index,idx){
        	var item = vm.getBaoxianItem(index)
        	if (item) {
        		item.select         = !item.select
        		$.each(item.listselect.item,function(i,it){
        			it.baseprice    = item.select ? it.defprice : 0
	        		it.myprice      = item.select ? it.defmyprice : 0
	        		it.selectprice  = item.select ? it.defselectprice : 0
        		})
        		/*item.listselect.item[0].baseprice    = item.select ? item.listselect.item[0].defprice : 0
        		item.listselect.item[0].myprice      = item.select ? item.listselect.item[0].defmyprice : 0
        		item.listselect.item[0].selectprice  = item.select ? item.listselect.item[0].defselectprice : 0*/
        	}
        }
        ,
        defSelect:function(index,defvalue,curvalue){
        	var item = vm.getBaoxianItem(index)
        	var flag = true
        	if (item) {
        		flag = item.select && defvalue == curvalue
        	} 
        	return flag
        }
        ,
        selectRadio:function(index,baseprice,myprice){
        	var item = vm.getBaoxianItem(index)
        	if (item) {
        		item.select  			           = true
        		item.listselect.item[0].baseprice  = baseprice
        		item.listselect.item[0].myprice    = myprice
        		item.baseprice                     = baseprice
        		item.myprice                       = myprice
        		item.selectprice                   = this.value
        		vm.iszixuan 					   = item.myprice == item.defprice ? false : true
        		$(this).click()
        	} 
        }
        ,
        selectRadioMuli:function(index,idx,opt){
        	var item = vm.getBaoxianItem(index)
        	if (item) {
        		item.select                              = opt[0]
    			item.listselect.item[idx].baseprice      = opt[1]
    			item.listselect.item[idx].myprice        = opt[2]
        		item.listselect.item[idx].selectprice    = opt[4]
        		item.listselect.item[idx].defselectprice = opt[3]
        		vm.iszixuan 					         = item.listselect.item[idx].myprice == item.listselect.item[idx].defprice ? false : true
        		//console.log(item,item.listselect.item[idx].myprice,item.listselect.item[idx].defprice)
        		$(this).click()
        	} 
        }
        ,
        setMianpei:function(index,baseprice,myprice){
        	index  = index == 5 ? 10 :( index + 6 )
        	var item = vm.getBaoxianItem(index)
        	////console.log(item)
        	if (item) {
        		item.baseprice  = baseprice
        		item.myprice    = myprice
        		//item.defprice   = myprice
        	} 
        }
        ,
        mianpeiSelect:function(index,opt){
        	index  = index == 4 ? 10 :( index + 6 )
        	var item = vm.getBaoxianItem(index)
        	if (item) {
        		item.select       = !item.select
        		item.myprice      = item.select ? item.defprice : 0
        	} 
        }
        ,
        sumCSRYmianpei:function(){
        	//车上人员责任险
        	var item             = vm.getBaoxianItem(3)
        	//驾驶人每次事故责任限额
        	var jsrsgzr          = item.listselect.item[0]
        	//乘客每次事故每人责任限额
        	var cksgzr           = item.listselect.item[1]
        	//乘客座位数
        	var ckzws            = item.listselect.item[2]
            //计算好的报价基准
            var _totalbaseprice  = cksgzr.baseprice * ckzws.baseprice + jsrsgzr.baseprice
            //计算好的我的投保
            var _totalmyprice    = cksgzr.myprice * ckzws.myprice + jsrsgzr.myprice
            //车上人员责任险 不计免赔特约险
            var bjmcsryzrx       = vm.getBaoxianItem(9)
            //console.log(cksgzr.baseprice , ckzws.baseprice , jsrsgzr.baseprice)
            //console.log(cksgzr.myprice , ckzws.myprice , jsrsgzr.myprice)
        	if (bjmcsryzrx) {
				bjmcsryzrx.baseprice  =  _totalbaseprice 		
				bjmcsryzrx.myprice    =  _totalmyprice 
        	} 
        }
        ,
        baoxian:{
        	jdc_ssx:{//机动车损失险
        		select:true,
        		issingle:true,
        		baseprice:0,
        		defprice:0,
        		myprice:0
        	},
        	jdc_dqx:{//机动车盗抢险	
        		select:true,
        		issingle:true,
        		baseprice:0,
        		defprice:0,
        		myprice:0
        	},
        	dszzrbx:{//第三者责任保险
        		select:true,
        		issingle:false,
        		baseprice:0,//报价基准 默认值
        		myprice:0, //我的投保 默认值
        		selectprice:0,//选中的额度
        		defselectprice:0,//默认额度
        		defprice:0,
        		listselect:{//选择列表
        			item:[{
        				baseprice:0, //报价基准
	        			myprice:0   //我的投保 
        			}]
        		}
        	},
        	csryzrx:{//车上人员责任险
        		select:true,
        		issingle:false,
        		baseprice:0,//报价基准 默认值
        		myprice:0, //我的投保 默认值
        		selectprice:0,//选中的事故责任限额
        		defselectprice:0,//默认额度
        		listselect:{//选择列表
        			item:[{
        				selectprice:0,
        				defselectprice:0,
        				baseprice:0,
	        			myprice:0,
	        			defprice:0,
	        			defmyprice:0
        			},
        			{
        				selectprice:0,
        				defselectprice:0,
        				baseprice:0,
	        			myprice:0,
	        			defprice:0,
	        			defmyprice:0

        			},
        			{
        				selectprice:0,
        				defselectprice:0,
        				baseprice:0,
	        			myprice:0,
	        			defprice:0,
	        			defmyprice:0
        			}]
        		}
        	},
        	blddpxx:{//玻璃单独破碎险
        		select:true,
        		issingle:false,
        		baseprice:0,//报价基准 默认值
        		myprice:0, //我的投保 默认值
        		selectprice:0,//选中的额度
        		defselectprice:0,//默认额度
        		defprice:0,
        		listselect:{//选择列表
        			item:[{
        				baseprice:0, //报价基准
	        			myprice:0   //我的投保 
        			}]
        		}
        	},
        	qchhssx:{//车身划痕损失险
        		select:true,
        		issingle:false,
        		baseprice:0,//报价基准 默认值
        		myprice:0, //我的投保 默认值
        		selectprice:0,//选中的额度
        		defselectprice:0,//默认额度
        		defprice:0,
        		listselect:{//选择列表
        			item:[{
        				baseprice:0, //报价基准
	        			myprice:0   //我的投保 
        			}]
        		}
        	},
        	bjm_jdcssx:{//不计免赔特约险 机动车损失险	
        		select:true,
        		issingle:true,
        		baseprice:0,
        		defprice:0,
        		myprice:0
        	},
        	bjm_jdcdqx:{//不计免赔特约险 机动车盗抢险	
        		select:true,
        		issingle:true,
        		defprice:0,
        		baseprice:0,
        		myprice:0
        	},
        	bjm_dszzrx:{//不计免赔特约险 第三者责任险	
        		select:true,
        		issingle:true,
        		defprice:0,
        		baseprice:0,
        		myprice:0
        	},
        	bjm_csrxzrx:{//不计免赔特约险 车上人员责任险	
        		select:true,
        		issingle:true,
        		defprice:0,
        		baseprice:0,
        		myprice:0
        	},
        	bjm_cshhssx:{//不计免赔特约险 车身划痕损失险	
        		select:true,
        		issingle:true,
        		defprice:0,
        		baseprice:0,
        		myprice:0
        	},



        }
          
    })

	vm.$watch("baoxian.*.select", function(a,b) {
	    vm.sumTotalPrice()
	    vm.iszixuan = !vm.iszixuan
	})
	vm.$watch("baoxian.*.myprice", function() {
	    vm.sumTotalPrice()
	})


	/*$(".baoxian input[type='checkbox'],.baoxian input[type='radio']").bind("click",function(){
		vm.sumTotalPrice()
	})*/

    module.exports = {
    	init:function(){
    		vm.init()
    	}
    	,
    	baoxian:function(index,opt){
    		switch(index){
    			case 0 : //配置机动车损失险
    				 vm.baoxian.jdc_ssx.select      = opt[0]
    				 vm.baoxian.jdc_ssx.baseprice   = opt[1]
    				 vm.baoxian.jdc_ssx.myprice     = opt[2]
    				 vm.baoxian.jdc_ssx.defprice    = opt[2]
    			break;
    			case 1 : //配置机动车盗抢险
    				 vm.baoxian.jdc_dqx.select      = opt[0]
    				 vm.baoxian.jdc_dqx.baseprice   = opt[1]
    				 vm.baoxian.jdc_dqx.myprice     = opt[2]
    				 vm.baoxian.jdc_dqx.defprice    = opt[2]
    			break;
    			case 2 : //配置第三者责任保险
    				 vm.baoxian.dszzrbx.select            = opt[0]
    				 vm.baoxian.dszzrbx.baseprice         = opt[1]
    				 vm.baoxian.dszzrbx.myprice           = opt[2]
    				 vm.baoxian.dszzrbx.defprice          = opt[2]
    				 vm.baoxian.dszzrbx.selectprice       = opt[3]
    				 vm.baoxian.dszzrbx.defselectprice    = opt[3]
    			break;
    			case 3 : //配置车上人员责任险
    				 	vm.baoxian.dszzrbx.select                                   = opt[1]
    				 	vm.baoxian.csryzrx.listselect.item[opt[0]].baseprice        = opt[2]
    				 	vm.baoxian.csryzrx.listselect.item[opt[0]].myprice          = opt[3]
    				 	vm.baoxian.csryzrx.listselect.item[opt[0]].selectprice      = opt[4]
    				 	vm.baoxian.csryzrx.listselect.item[opt[0]].defselectprice   = opt[4]
    				 	vm.baoxian.csryzrx.listselect.item[opt[0]].defprice         = opt[2]
    				 	vm.baoxian.csryzrx.listselect.item[opt[0]].defmyprice       = opt[3]
    			break;
    			case 4 : //配置玻璃单独破碎险
    				 vm.baoxian.blddpxx.select            = opt[0]
    				 vm.baoxian.blddpxx.baseprice         = opt[1]
    				 vm.baoxian.blddpxx.myprice           = opt[2]
    				 vm.baoxian.blddpxx.defprice          = opt[2]
    				 vm.baoxian.blddpxx.selectprice       = opt[3]
    				 vm.baoxian.blddpxx.defselectprice    = opt[3]
    			break;
    			case 5 : //配置车身划痕损失险
    				 vm.baoxian.qchhssx.select            = opt[0]
    				 vm.baoxian.qchhssx.baseprice         = opt[1]
    				 vm.baoxian.qchhssx.myprice           = opt[2]
    				 vm.baoxian.qchhssx.defprice          = opt[2]
    				 vm.baoxian.qchhssx.selectprice       = opt[3]
    				 vm.baoxian.qchhssx.defselectprice    = opt[3]
    			break;
    			//不计免赔特约险
    			case 6 : //配置机动车损失险
    				 vm.baoxian.bjm_jdcssx.baseprice  = opt[0]
    				 vm.baoxian.bjm_jdcssx.myprice    = opt[1]
    				 vm.baoxian.bjm_jdcssx.defprice   = opt[2]
    			break;
    			case 7 : //配置机动车盗抢险
    				 vm.baoxian.bjm_jdcdqx.baseprice  = opt[0]
    				 vm.baoxian.bjm_jdcdqx.myprice    = opt[1]
    				 vm.baoxian.bjm_jdcdqx.defprice   = opt[2]
    			break;
    			case 8 : //配置第三者责任险
    				 vm.baoxian.bjm_dszzrx.baseprice  = opt[0]
    				 vm.baoxian.bjm_dszzrx.myprice    = opt[1]
    				 vm.baoxian.bjm_dszzrx.defprice   = opt[2]
    			break;
    			case 9 : //配置车上人员责任险
    				 vm.baoxian.bjm_csrxzrx.baseprice  = opt[0]
    				 vm.baoxian.bjm_csrxzrx.myprice    = opt[1]
    				 vm.baoxian.bjm_csrxzrx.defprice   = opt[2]
    			break;
    			case 10 : //配置车身划痕损失险
    				 vm.baoxian.bjm_cshhssx.baseprice  = opt[0]
    				 vm.baoxian.bjm_cshhssx.myprice    = opt[1]
    				 vm.baoxian.bjm_cshhssx.defprice   = opt[2]
    			break;


    		}
    	}
    }

})