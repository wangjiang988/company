
define(function (require,exports,module) {

    require("jq");//
    //xuanzhuang model
    function model () {
        this.id          = ""
        this.pingpai     = ""
        this.name        = "" 
        this.xinghao     = "" 
        this.zhidaojia   = "" 
        this.anzhuangfei = "" 
        this.danjia      = "" 
        this.shuliang    = "" 
        this.price       = "" 
        this.total       = ""
        this.oldtotal    = ""
    }
    var ycjingping       = [] //原厂选装精品
    var fycjingping      = [] //非原厂选装精品

    var vm = avalon.define({
        $id: 'item',
        init: function () {
           
        },
        ycjingping : [],
        fycjingping: [], 
        dinggou:function(){

            //yuanchang
            $.each($(".bgtbl:eq(0)").find("tr").slice(1),function(index,item){
                var _item = $(item)
                var _ck = _item.find("td:eq(0)").find("input[type='checkbox']")
                if (_item.find("input.input").val() == "0" || !_ck.prop("checked")) {
                    return
                }
                var _model = new model()
                _model.id           = _item.attr("data-id")
                _model.name         = _item.find("td:eq(1)").text().replace("*","")
                _model.xinghao      = _item.find("td:eq(2)").text()
                _model.zhidaojia    = _item.find("td:eq(3)").text()
                _model.anzhuangfei  = _item.find("td:eq(4)").text()
                _model.danjia       = _item.find("td:eq(5)").text()
                _model.shuliang     = _item.find("td:eq(6) input").val()
                _model.price        = _item.find("td:eq(7)").text()
                _model.total        = _item.parents("table").next().find("input[type='hidden']").val()
                _model.oldtotal     = _item.find("td:eq(6) input").attr("data-def-value")
                vm.ycjingping.push(_model)
                _item.find("td:eq(5) input").siblings().css("visibility","hidden")
                
            })

            //feiyuanchang
            $.each($(".bgtbl:eq(1)").find("tr").slice(1),function(index,item){
                
                var _item = $(item)
                var _ck = _item.find("td:eq(0)").find("input[type='checkbox']")
                if (_item.find("input.input").val() == "0" || !_ck.prop("checked")) {
                    return
                } 
                var _model = new model()
                _model.id           = _item.attr("data-id")
                _model.pingpai      = _item.find("td:eq(1)").text()
                _model.name         = _item.find("td:eq(2)").text().replace("*","")
                _model.xinghao      = _item.find("td:eq(3)").text()
                _model.danjia       = _item.find("td:eq(4)").text()
                _model.shuliang     = _item.find("td:eq(5) input").val()
                _model.price        = _item.find("td:eq(6)").text()
                _model.total        = _item.parents("table").next().find("input[type='hidden']").val()
                _model.oldtotal     = _item.find("td:eq(5) input").attr("data-def-value")
                vm.fycjingping.push(_model)
                _item.find("td:eq(4) input").siblings().css("visibility","hidden")
            })

            //是否选择其中之一
            if (vm.ycjingping.length != 0 || vm.fycjingping.length != 0)
            {  
                $("#dinggou-tip").hcPopup()
            }

            //console.log(vm.ycjingping)
            //console.log(vm.fycjingping)
        }
        ,
        setValue:function(obj){
        	var _this = $(obj)
        	var _price = parseInt( _this.parents("td").prev().text() )
        	var _val = parseInt(_this.val())
        	var _txt = _val * _price
        	_txt =  _txt == 0 ? "" : _txt+""
        	//每项的金额 选择购买件数 * 含安装费折后总单价
        	_this.parents("td").next().text(_txt)
        	var _total = 0
        	var _tbl = _this.parents(".bgtbl")
        	//总金额 
        	$.each(_tbl.find("tr").slice(1),function(index,item){
        		var _itemval  = parseInt($(item).find("td:last").text())
        		_total += !isNaN(_itemval) ?_itemval: 0
        	})
        	_tbl.next().find("input[name='price']").val(_total)//后台取值对象
        	_tbl.next().find("label").text(_total)
        }
        ,
        prev:function(){
        	var _this = $(this)
        	var _input = $(this).next()
        	var _val = parseInt(_input.val())
    		var _min = 0
    		_input.val(_val == _min ? _min : (_val - 1))
    		vm.setValue(_input)
        }
        ,
        next:function(max){
        	var _max = max || 0 //可在页面传参 注：此件数选择不超过单车可装件数，也不超过可供件数。
        	var _this = $(this)
        	var _input = $(this).prev()
        	var _val = parseInt(_input.val())
        	_input.val(_val == _max ? _max : (_val + 1))
        	vm.setValue(_input)
        }
         ,
        displayTm: function () {
            $(this).next().fadeIn(300)
        }
        ,
        hideTm: function () {
            $(this).next().hide()
        }
        ,
        initTime:function(){
            require("module/common/time.jquery")
        }
        ,
        initPopup:function(){
            require("module/common/hc.popup.jquery")
        }
        ,
        xzorder:function(){
            $("#dinggou-tip").hide()
            //下面几行是静态页面用的
            //被注释的是开发时候用的
            //数据参数是两个数组 vm.ycjingping 和 vm.fycjingping
            var _btn = $(".tac .btn:eq(0)")
            _btn.nextAll().removeClass("hide")
            if (!_btn.hasClass("end")) {
                _btn.addClass("hide")
            }
            $(".bgtbl").slice(0, 2).find("input[type='checkbox']").attr("disabled",true).end().find(".prev,.next").css("visibility","hidden")
            vm.ycjingping  = [] //清空
            vm.fycjingping = [] //清空
            //提交
            /*$.ajax({
                    url: "http://www.hwache.cn/saveuserxzj/",
                    type: "post",
                    dataType: "json",
                    data: {
                        yc:vm.ycjingping,
                        fyc:vm.fycjingping
                    },
                    beforeSend: function () {
                        
                    }
                    ,
                    success: function (data) {
                        
                        var _error_code = data.error_code;
                        var _error_msg = data.error_msg;
                        
                        if (_error_code == 1) {
                           //成功
                           $("#dinggou-tip").hide()
                           var _btn = $(".tac .btn:eq(0)")
                           _btn.nextAll().removeClass("hide")
                            if (!_btn.hasClass("end")) {
                                _btn.addClass("hide")
                            }
                            $(".bgtbl").slice(0, 2).find("input[type='checkbox']").attr("disabled",true).end().find(".prev,.next").css("visibility","hidden")
                            vm.ycjingping  = []
                            vm.fycjingping = []

                        } else {

                        }
                        
                    }
            })*/
        }
        ,
        modifyDinggou:function(){
            $("#dinggou-modify").hcPopup({'width':'800','min':true})
        }
        ,
        prevFix:function(){
            var _this = $(this)
            var _input = _this.next()
            if (_input.attr("data-def-value")) {
                var _def = parseInt(_input.attr("data-def-value"))
                if (_def != 0) {
                    //已经选过了 _def是选过的数量
                    if (parseInt(_input.val()) < _def) {
                        //当前数量少于以前选过的数量
                        _this.addClass('preverror').next().addClass('countinputrror').parent().next().show()
                    }else{
                        _this.removeClass('preverror').next().removeClass('countinputrror').parent().next().hide()
                    }
                }
            }else{
                //
            }
        }
        ,
        nextFix:function(){
            var _this = $(this)
            var _input = _this.prev()
            if (_input.attr("data-def-value")) {
                var _def = parseInt(_input.attr("data-def-value"))
                if (_def != 0) {
                    //已经选过了 _def是选过的数量
                    if (parseInt(_input.val()) == _def) {
                        //当前数量等于以前选过的数量
                        _input.prev().removeClass('preverror').next().removeClass('countinputrror').parent().next().hide()
                    }else{
                        
                    }
                }
            }else{
                //
            }
        }
        ,
        initorder:function(){
            vm.ycjingping  = []
            vm.fycjingping = []
            //yuanchang
            $.each($(".bgtbl:eq(2)").find("tr").slice(1),function(index,item){
                
                var _item = $(item)
                var _ck = _item.find("td:eq(0)").find("input[type='checkbox']")
                //var _shulinag = _item.find("td:eq(6) input")
                if (!_ck.prop("checked")) {
                    return
                }
                var _model = new model()
                _model.id           = _item.attr("data-id")
                _model.name         = _item.find("td:eq(1)").text().replace("*","")
                _model.xinghao      = _item.find("td:eq(2)").text()
                _model.zhidaojia    = _item.find("td:eq(3)").text()
                _model.anzhuangfei  = _item.find("td:eq(4)").text()
                _model.danjia       = _item.find("td:eq(5)").text()
                _model.shuliang     = _item.find("td:eq(6) input").val()
                _model.price        = _item.find("td:eq(7)").text()
                _model.total        = _item.parents("table").next().find("input[type='hidden']").val()
                 _model.oldtotal     = _item.find("td:eq(6) input").attr("data-def-value")
                vm.ycjingping.push(_model)
                
            })

            //feiyuanchang
            $.each($(".bgtbl:eq(3)").find("tr").slice(1),function(index,item){
                
                var _item = $(item)
                var _ck = _item.find("td:eq(0)").find("input[type='checkbox']")
                if (  !_ck.prop("checked")) {
                    return
                }
                var _model = new model()
                _model.id           = _item.attr("data-id")
                _model.pingpai      = _item.find("td:eq(1)").text()
                _model.name         = _item.find("td:eq(2)").text().replace("*","")
                _model.xinghao      = _item.find("td:eq(3)").text()
                _model.danjia       = _item.find("td:eq(4)").text()
                _model.shuliang     = _item.find("td:eq(5) input").val()
                _model.price        = _item.find("td:eq(6)").text()
                _model.total        = _item.parents("table").next().find("input[type='hidden']").val()
                _model.oldtotal     = _item.find("td:eq(5) input").attr("data-def-value")
                vm.fycjingping.push(_model)
            })  

            $("#dinggou-order").hcPopup({'width':'660','min':true})

        }
        ,
        sureorder:function(){
            $(".popupbox").hide()
            $(".dinggou-fix").hide().removeClass('showtag').removeAttr('style')
            //ajax操作
            //...
        }

    })

    vm.init()

    module.exports = {
        init:function(){
            vm.initTime()
            vm.initPopup()
        }
    }

});